<?php
add_filter('show_admin_bar', '__return_false');

add_action('init', function () {

  // Editor script
  wp_register_script(
    'lp-mock-block-editor',
    get_stylesheet_directory_uri() . '/lp-mock-block.js',
    ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-data'],
    '1.1.2',
    true
  );

  // Editor style (optional)
  wp_register_style(
    'lp-mock-block-editor-style',
    get_stylesheet_directory_uri() . '/lp-mock-block-editor.css',
    [],
    '1.1.2'
  );

  register_block_type('lp/mock', [
    'api_version'     => 2,
    'editor_script'   => 'lp-mock-block-editor',
    'editor_style'    => 'lp-mock-block-editor-style',
    'render_callback' => 'lp_mock_render_callback',
    'exportedHtml' => ['type' => 'string', 'default' => ''],
    'attributes'      => [
      'advancedHtml'    => ['type' => 'string', 'default' => ''],
      'keyedHtml'       => ['type' => 'string', 'default' => ''],

      'editableText'    => ['type' => 'array',  'default' => []],
      'editableLinks'   => ['type' => 'array',  'default' => []],
      'editableImages'  => ['type' => 'array',  'default' => []],
    ],
    'supports' => [
      'html' => false,
    ],
  ]);
});


function lp_mock_render_callback($attributes) {

  $advanced = isset($attributes['advancedHtml']) ? trim((string)$attributes['advancedHtml']) : '';
  if ($advanced === '') return '';

  $base_html = (isset($attributes['keyedHtml']) && trim((string)$attributes['keyedHtml']) !== '')
    ? (string)$attributes['keyedHtml']
    : $advanced;
  // --- SVG含むHTMLの事前除去（保険）---
  // 明確に危険なタグを除去
  $base_html = preg_replace('#<\s*(script|foreignObject|iframe|object|embed)[^>]*>[\s\S]*?<\s*/\s*\1\s*>#is', '', $base_html);
  $base_html = preg_replace('#<\s*(script|foreignObject|iframe|object|embed)[^>]*/\s*>#is', '', $base_html);

  // on* 属性を除去
  $base_html = preg_replace('/\son[a-z]+\s*=\s*(".*?"|\'.*?\'|[^\s>]+)/i', '', $base_html);

  // href / xlink:href は完全禁止（要件）
  $base_html = preg_replace('/\s(?:href|xlink:href)\s*=\s*(".*?"|\'.*?\'|[^\s>]+)/i', '', $base_html);
  // --- /保険 ---

  // 1) まず許可タグでサニタイズ（data-edit-* も許可）
  $allowed = wp_kses_allowed_html('post');

  $allowed['a'] = array_merge($allowed['a'] ?? [], [
    'href'   => true,
    'target' => true,
    'rel'    => true,
    'class'  => true,
    'id'     => true,
    'style'  => true,
    'data-edit-key'  => true,
    'data-edit-href' => true,
  ]);

  $allowed['img'] = array_merge($allowed['img'] ?? [], [
    'src'      => true,
    'srcset'   => true,   // ← 追加
    'sizes'    => true,   // ← 追加（推奨）
    'alt'      => true,
    'class'    => true,
    'id'       => true,
    'sizes'    => true,
    'width'    => true,
    'height'   => true,
    'loading'  => true,
    'decoding' => true,
    'fetchpriority' => true,
    'data-edit-img' => true,
  ]);
  // --- SVG 最小許可（svg/g/circle/path のみ）---
  $allowed['svg'] = array_merge($allowed['svg'] ?? [], [
    'xmlns'   => true,
    'viewbox' => true, // wp_ksesは小文字扱いになり得る
    'width'   => true,
    'height'  => true,
  ]);

  $allowed['g'] = array_merge($allowed['g'] ?? [], [
    'transform' => true,
  ]);

  $allowed['circle'] = array_merge($allowed['circle'] ?? [], [
    'cx' => true,
    'cy' => true,
    'r'  => true,
    'fill' => true,
    'transform' => true,
  ]);

  $allowed['path'] = array_merge($allowed['path'] ?? [], [
    'd' => true,
    'fill' => true,
    'transform' => true,
  ]);
  // --- /SVG ---


  foreach (['div','span','p','h1','h2','h3','h4','h5','h6','section','ul','ol','li','br'] as $tag) {
    $allowed[$tag] = array_merge($allowed[$tag] ?? [], [
      'class' => true,
      'id'    => true,
      'style' => true,
      'data-edit-key' => true,
    ]);
  }

  $safe_html = wp_kses($base_html, $allowed);

  $texts  = (isset($attributes['editableText']) && is_array($attributes['editableText'])) ? $attributes['editableText'] : [];
  $links  = (isset($attributes['editableLinks']) && is_array($attributes['editableLinks'])) ? $attributes['editableLinks'] : [];
  $images = (isset($attributes['editableImages']) && is_array($attributes['editableImages'])) ? $attributes['editableImages'] : [];

  // 2) DOMで安全に差し替え（正規表現は使わない）
  libxml_use_internal_errors(true);

  $dom = new DOMDocument('1.0', 'UTF-8');
  // DOMDocumentはHTML断片が苦手なので wrapper を付ける
  $wrapped = '<!doctype html><html><head><meta charset="utf-8"></head><body><div id="lp-mock-root">' . $safe_html . '</div></body></html>';
  $dom->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

  $xpath = new DOMXPath($dom);
  $root  = $xpath->query('//*[@id="lp-mock-root"]')->item(0);

  // inner 用の許可（br/spanのみ）
  $allowed_inline = [
    'br'   => [],
    'span' => ['class' => true],
  ];

  // テキスト差し替え
  foreach ($texts as $t) {
    if (!is_array($t)) continue;
    $key = isset($t['key']) ? (string)$t['key'] : '';
    if ($key === '') continue;

    $val = isset($t['value']) ? (string)$t['value'] : '';

    // <p>混入の除去（安全）
    if (preg_match('#^\s*<p[^>]*>([\s\S]*?)</p>\s*$#i', $val, $m)) {
      $val = $m[1];
    }
    $val = preg_replace('#</p>\s*<p[^>]*>#i', '<br>', $val);
    $val = preg_replace('#</?p[^>]*>#i', '', $val);

    $safe_val = wp_kses($val, $allowed_inline);
    $safe_val = preg_replace('#<br\s*/?>#i', '<br />', $safe_val); // XML互換にする


    $nodes = $xpath->query('//*[@data-edit-key="' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '"]');
    if ($nodes->length < 1) continue;

    /** @var DOMElement $node */
    $node = $nodes->item(0);

    // 既存子要素を全削除してから差し込み
    while ($node->firstChild) {
      $node->removeChild($node->firstChild);
    }

    // ★追加：空なら何も入れず（空のまま）終了
    if (trim($safe_val) === '') {
      continue;
    }

    $frag = $dom->createDocumentFragment();
    @$frag->appendXML($safe_val);

    if (!$frag->hasChildNodes()) {
      // appendXMLが失敗した場合はテキストとして入れる（崩れ防止）
      $node->appendChild($dom->createTextNode(wp_strip_all_tags($safe_val)));
    } else {
      $node->appendChild($frag);
    }

  }

  // リンク差し替え
  foreach ($links as $l) {
    if (!is_array($l)) continue;
    $key  = isset($l['key']) ? (string)$l['key'] : '';
    $href = isset($l['href']) ? (string)$l['href'] : '';
    if ($key === '' || $href === '') continue;

    $nodes = $xpath->query('//a[@data-edit-href="' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '"]');
    if ($nodes->length < 1) continue;

    /** @var DOMElement $a */
    $a = $nodes->item(0);
    $a->setAttribute('href', esc_url($href));
  }

  // 画像差し替え
  foreach ($images as $im) {
    if (!is_array($im)) continue;
    $key = isset($im['key']) ? (string)$im['key'] : '';
    if ($key === '') continue;

    $url = isset($im['url']) ? (string)$im['url'] : '';
    $alt = isset($im['alt']) ? (string)$im['alt'] : '';

    $nodes = $xpath->query('//img[@data-edit-img="' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '"]');
    if ($nodes->length < 1) continue;

    /** @var DOMElement $img */
    $img = $nodes->item(0);

    if ($url !== '') $img->setAttribute('src', esc_url($url));
    $img->setAttribute('alt', esc_attr($alt));
  }

  // 3) wrapper から中身だけ取り出す
  $out = '';
  if ($root) {
    foreach ($root->childNodes as $child) {
      $out .= $dom->saveHTML($child);
    }
  }

  libxml_clear_errors();

  return '<div class="lp-mock lp-mock--advanced">' . $out . '</div>';
}














/* --------------------------------------------------
 * 3) style.php (inline CSS) -> also apply in Gutenberg editor
 *    style.php contains ONLY: <style id="theme-inline-css"> ... </style>
 * -------------------------------------------------- */
function theme_get_inline_css_from_style_php(): string {
  $file = get_template_directory() . '/style.php';
  if (!file_exists($file)) return '';

  $html = file_get_contents($file);
  if ($html === false) return '';

  if (preg_match('#<style\b[^>]*\bid=["\']theme-inline-css["\'][^>]*>(.*?)</style>#is', $html, $m)) {
    return trim($m[1]);
  }
  return '';
}

add_action('enqueue_block_editor_assets', function () {

  // まず editor style を enqueue（register だけだと環境で未出力のことがある）
  wp_enqueue_style('lp-mock-block-editor-style');

  // (A) フロントCSSを注入
  $css = theme_get_inline_css_from_style_php();
  if ($css !== '') {
    wp_add_inline_style('lp-mock-block-editor-style', $css);
  }

  // (B) 90%ルールも同じハンドルに
  $editor_css = <<<CSS
.editor-styles-wrapper .block-editor-block-list__layout.is-root-container > :where(.wp-block){
  max-width: 90% !important;
}
.editor-styles-wrapper .wp-block-html{
  max-width: 90% !important;
  width: 90% !important;
}
.editor-styles-wrapper .wp-block-html iframe.components-sandbox{
  width: 90% !important;
  max-width: 90% !important;
  display: block;
}
CSS;

  wp_add_inline_style('lp-mock-block-editor-style', $editor_css);
});



/* --------------------------------------------------
 * 4) Frontend scripts (only if you truly need jQuery on public pages)
 *    - Remove this block if unused.
 * -------------------------------------------------- */
add_action('wp_enqueue_scripts', function () {
  // wp_enqueue_script('jquery');
});


/* --------------------------------------------------
 * 5) Deregister plugin CSS (optional)
 *    - Keep only if you are certain it doesn't break plugin front styling.
 * -------------------------------------------------- */
add_action('wp_print_styles', function () {
  wp_deregister_style('wp-pagenavi');
  wp_deregister_style('contact-form-7');
}, 100);

add_filter('body_class', function ($classes) {
  $auth_templates = ['page-templates/page-mypage.php', 'page-templates/page-login.php', 'page-templates/page-register.php', 'page-templates/page-payment.php', 'page-templates/page-payment-complete.php', 'page-templates/page-select-user.php'];
  if (is_page_template($auth_templates)) {
    $classes[] = 'wsc8-auth-page';
  }
  return $classes;
});

/* --------------------------------------------------
 * 6) Editor styles support (optional)
 *    - Your original add_editor_style() was called without specifying a file.
 *    - If you have editor-style.css, set it explicitly; otherwise you can remove this.
 * -------------------------------------------------- */
// add_theme_support('editor-styles');
// add_editor_style('editor-style.css');



add_action('wp_ajax_send_contact_form', 'send_contact_form');
add_action('wp_ajax_nopriv_send_contact_form', 'send_contact_form');

function send_contact_form() {

  $name  = sanitize_text_field($_POST['name'] ?? '');
  $email = sanitize_email($_POST['email'] ?? '');
  $tel   = sanitize_text_field($_POST['tel'] ?? '');
  $msg   = sanitize_textarea_field($_POST['message'] ?? '');

  if (!$name || !$email || !$tel) {
    wp_send_json([
      'success' => false,
      'message' => '必須項目が未入力です。'
    ]);
  }

  /* 管理者宛 */
  $admin_to = get_option('admin_email');
  $subject  = '【お問い合わせ】'.$name.'様';
  $body     =
    "お名前：{$name}\n".
    "メール：{$email}\n".
    "電話番号：{$tel}\n\n".
    "お問い合わせ内容：\n{$msg}";

  $headers = [
    'Content-Type: text/plain; charset=UTF-8',
    'From: '.$name.' <'.$email.'>'
  ];

  wp_mail($admin_to, $subject, $body, $headers);

  /* 自動返信（送信者） */
  $reply_subject = '【自動返信】お問い合わせありがとうございます';
  $reply_body =
    "{$name} 様\n\n".
    "お問い合わせを受け付けました。\n".
    "内容を確認のうえ、担当よりご連絡いたします。\n\n".
    "――――――――――\n".
    $body;

  wp_mail($email, $reply_subject, $reply_body, [
    'Content-Type: text/plain; charset=UTF-8',
    'From: '.get_bloginfo('name').' <'.$admin_to.'>'
  ]);

  wp_send_json([
    'success' => true,
    'message' => '送信が完了しました。ありがとうございました。'
  ]);
}

/* ========== CODELESSLAB 会員・バックエンド ========== */

define('WSC8_SEED_USERS', [
  ['user_login' => 'demo', 'user_email' => 'yamada0000tarou@gmail.com', 'user_pass' => 'Taro0000', 'display_name' => 'デモユーザー', 'role' => 'subscriber', 'role_type' => 0],
  ['user_login' => 'test', 'user_email' => 'ryunosuke.mako@check-raise.jp', 'user_pass' => '@testtest0', 'display_name' => '管理者', 'role' => 'subscriber', 'role_type' => 1],
]);

function wsc8_create_custom_users_table() {
  global $wpdb;
  $table = $wpdb->prefix . 'custom_users';
  $charset = $wpdb->get_charset_collate();
  $sql = "CREATE TABLE {$table} (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    wp_user_id bigint(20) UNSIGNED NOT NULL,
    name varchar(100) DEFAULT '',
    email varchar(100) DEFAULT '',
    phone varchar(50) DEFAULT '',
    address varchar(255) DEFAULT '',
    role_type tinyint(1) DEFAULT 0 COMMENT '0:一般, 1:管理者',
    plan_name varchar(100) DEFAULT '',
    payment_amount int(11) DEFAULT 0,
    history_payment_method varchar(100) DEFAULT '',
    status varchar(50) DEFAULT '',
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY unique_wp_user (wp_user_id)
  ) {$charset};";
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}

add_action('admin_post_nopriv_custom_register', 'wsc8_handle_registration');
add_action('admin_post_custom_register', 'wsc8_handle_registration');
function wsc8_handle_registration() {
  if (!isset($_POST['custom_register_nonce']) || !wp_verify_nonce($_POST['custom_register_nonce'], 'custom_register_action')) {
    wp_die('Security check failed');
  }
  $email = sanitize_email($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  if (!$email || !$password) {
    wp_safe_redirect(home_url('/register/?error=empty')); exit;
  }
  $username = sanitize_user(current(explode('@', $email)));
  $base = $username;
  $i = 1;
  while (username_exists($username)) {
    $username = $base . $i;
    $i++;
  }
  $user_id = wp_create_user($username, $password, $email);
  if (is_wp_error($user_id)) {
    wp_safe_redirect(home_url('/register/?error=' . urlencode($user_id->get_error_message()))); exit;
  }
  global $wpdb;
  $wpdb->insert($wpdb->prefix . 'custom_users', [
    'wp_user_id' => $user_id,
    'email' => $email,
    'name' => sanitize_text_field($_POST['name'] ?? ''),
    'phone' => sanitize_text_field($_POST['phone'] ?? ''),
    'address' => sanitize_text_field($_POST['address'] ?? ''),
    'role_type' => 0,
  ]);
  wp_set_current_user($user_id);
  wp_set_auth_cookie($user_id);
  wp_safe_redirect(home_url('/mypage')); exit;
}

function wsc8_seed_users() {
  global $wpdb;
  $table = $wpdb->prefix . 'custom_users';
  foreach (WSC8_SEED_USERS as $u) {
    $role_type = (int) ($u['role_type'] ?? 0);
    $user_data = $u;
    unset($user_data['role_type']);
    if (username_exists($u['user_login'])) {
      $user = get_user_by('login', $u['user_login']);
      if ($user) {
        $exists = $wpdb->get_var($wpdb->prepare("SELECT 1 FROM {$table} WHERE wp_user_id = %d", $user->ID));
        if (!$exists) {
          $wpdb->insert($table, ['wp_user_id' => $user->ID, 'name' => $u['display_name'] ?? '', 'email' => $u['user_email'] ?? '', 'role_type' => $role_type], ['%d', '%s', '%s', '%d']);
        }
      }
      continue;
    }
    $user_id = wp_insert_user($user_data);
    if (is_wp_error($user_id)) continue;
    $exists = $wpdb->get_var($wpdb->prepare("SELECT 1 FROM {$table} WHERE wp_user_id = %d", $user_id));
    if (!$exists) {
      $wpdb->insert($table, ['wp_user_id' => $user_id, 'name' => $u['display_name'] ?? '', 'email' => $u['user_email'] ?? '', 'role_type' => $role_type], ['%d', '%s', '%s', '%d']);
    }
  }
}

function wsc8_seed_pages() {
  $pages = [
    ['post_name' => 'login', 'post_title' => 'ログイン', 'template' => 'page-templates/page-login.php'],
    ['post_name' => 'register', 'post_title' => '新規登録', 'template' => 'page-templates/page-register.php'],
    ['post_name' => 'mypage', 'post_title' => 'マイページ', 'template' => 'page-templates/page-mypage.php'],
    ['post_name' => 'select-user', 'post_title' => 'ユーザー選択', 'template' => 'page-templates/page-select-user.php'],
    ['post_name' => 'payment', 'post_title' => '支払い情報入力', 'template' => 'page-templates/page-payment.php'],
    ['post_name' => 'payment-complete', 'post_title' => '決済完了', 'template' => 'page-templates/page-payment-complete.php'],
  ];
  $author_id = get_current_user_id() ?: 1;
  foreach ($pages as $p) {
    $page = get_page_by_path($p['post_name']);
    if ($page) {
      if (!empty($p['template'])) {
        update_post_meta($page->ID, '_wp_page_template', $p['template']);
      }
      continue;
    }
    $id = wp_insert_post([
      'post_type' => 'page',
      'post_name' => $p['post_name'],
      'post_title' => $p['post_title'],
      'post_status' => 'publish',
      'post_content' => '',
      'post_author' => $author_id,
    ]);
    if (!is_wp_error($id) && !empty($p['template'])) {
      update_post_meta($id, '_wp_page_template', $p['template']);
    }
  }
}
add_action('after_switch_theme', function() {
  wsc8_create_custom_users_table();
  wsc8_seed_pages();
  wsc8_seed_users();
});
add_action('init', function() {
  wsc8_create_custom_users_table();
  wsc8_seed_pages();
  wsc8_seed_users();
}, 999);
