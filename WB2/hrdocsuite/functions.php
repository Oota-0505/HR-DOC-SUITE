<?php
/**
 * HR DOC SUITE テーマの functions.php
 *
 * テーマの機能定義、スタイル・スクリプトの読み込み、
 * WordPress機能のサポート設定などを行います。
 *
 * @package HR_DOC_SUITE
 * @since 1.0.0
 */

// 直接アクセス禁止
if (!defined('ABSPATH')) {
    exit;
}

/**
 * テーマのセットアップ
 */
function hrdoc_theme_setup() {
    // タイトルタグのサポート
    add_theme_support('title-tag');

    // HTML5マークアップのサポート
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // アイキャッチ画像のサポート
    add_theme_support('post-thumbnails');

    // カスタムロゴのサポート
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // ナビゲーションメニューの登録
    register_nav_menus(array(
        'primary'   => 'メインナビゲーション',
        'mobile'    => 'モバイルナビゲーション',
        'footer'    => 'フッターナビゲーション',
    ));
}
add_action('after_setup_theme', 'hrdoc_theme_setup');

// ファビコン（wp_head で全テンプレートに出力）
add_action('wp_head', function () {
    echo '<link rel="icon" type="image/png" href="' . esc_url(get_template_directory_uri() . '/images/favicon.png') . '">' . "\n";
}, 1);
// ファビコン（管理画面）
add_action('admin_head', function () {
    echo '<link rel="icon" type="image/png" href="' . esc_url(get_template_directory_uri() . '/images/favicon.png') . '">' . "\n";
}, 1);

/**
 * 管理バーを非表示
 */
add_filter('show_admin_bar', '__return_false');

/**
 * スタイルとスクリプトの読み込み
 */
function hrdoc_enqueue_assets() {
    // テーマバージョン（キャッシュバスティング用）
    $theme_version = wp_get_theme()->get('Version');

    // Google Fonts（ロゴ用に Plus Jakarta Sans を追加）
    wp_enqueue_style(
        'hrdoc-google-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&family=Roboto:wght@400;500;700&display=swap',
        array(),
        null
    );

    // Font Awesome（認証ページ用）
    wp_enqueue_style(
        'hrdoc-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css',
        array(),
        '6.4.2'
    );

    // メインCSS
    wp_enqueue_style(
        'hrdoc-main-style',
        get_template_directory_uri() . '/css/style.css',
        array('hrdoc-google-fonts'),
        $theme_version
    );

    // 認証・決済ページ用CSS
    if (is_page_template(array(
        'page-templates/page-login.php',
        'page-templates/page-mypage.php',
        'page-templates/page-select-user.php',
        'page-templates/page-payment.php',
        'page-templates/page-payment-complete.php'
    ))) {
        wp_enqueue_style(
            'hrdoc-auth-style',
            get_template_directory_uri() . '/css/auth.css',
            array('hrdoc-main-style'),
            $theme_version
        );
    }

    // メインJS
    wp_enqueue_script(
        'hrdoc-main-script',
        get_template_directory_uri() . '/js/main.js',
        array(),
        $theme_version,
        true
    );

    // スクリプトにデータを渡す
    wp_localize_script('hrdoc-main-script', 'hrdocData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'homeUrl' => home_url('/'),
        'nonce'   => wp_create_nonce('hrdoc_nonce'),
    ));

    // ヘッダー・フッターロゴ（テキスト）のスタイル - スタイリッシュ版
    $logo_css = '
        .logo .logo-text, .footer-logo .logo-text {
            font-family: "Plus Jakarta Sans", "Roboto", sans-serif;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: inline-block;
        }
        .logo .logo-text {
            font-size: 1.2rem;
            background: linear-gradient(135deg, #ffffff 0%, rgba(255,255,255,0.85) 50%, #e0f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .logo .logo-text::after {
            content: "";
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: linear-gradient(90deg, #38bdf8, #818cf8);
            transition: width 0.4s ease;
        }
        .logo a:hover .logo-text {
            letter-spacing: 0.08em;
        }
        .logo a:hover .logo-text::after {
            width: 100%;
        }
        .site-header.scrolled .logo .logo-text,
        body:not(.home) .logo .logo-text {
            background: linear-gradient(135deg, #0d1b3e 0%, #1e40af 50%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .site-header.scrolled .logo .logo-text::after,
        body:not(.home) .logo .logo-text::after {
            background: linear-gradient(90deg, #2563eb, #7c3aed);
        }
        .site-header.scrolled .logo a:hover .logo-text,
        body:not(.home) .logo a:hover .logo-text {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 70%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .footer-logo .logo-text {
            font-size: 1rem;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #0ea5e9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .footer-logo a:hover .logo-text {
            background: linear-gradient(135deg, #2563eb 0%, #0ea5e9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 0.08em;
        }
        /* 認証ページ（ログイン・ユーザー選択・マイページ）のロゴテキスト */
        .auth-login-header-logo .logo-text,
        .auth-login-container .auth-login-logo-top .logo-text,
        .auth-select-container .auth-select-header-logo .logo-text,
        .auth-select-container .auth-select-logo .logo-text,
        .mypage-header-logo .logo-text {
            font-family: "Plus Jakarta Sans", "Roboto", sans-serif;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            text-decoration: none;
            background: linear-gradient(135deg, #ffffff 0%, rgba(255,255,255,0.9) 50%, #e0f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.1rem;
        }
        .auth-login-container .auth-login-logo-top .logo-text { font-size: 1.25rem; }
        .auth-select-container .auth-select-logo .logo-text {
            font-size: 1.2rem;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #0ea5e9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .mypage-header-logo .logo-text {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #0ea5e9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        /* 階層3ページ（利用規約・プライバシーポリシー・特定商取引法）: ナビを黒文字に */
        body:not(.home) .nav-link,
        .site-header.scrolled .nav-link {
            color: #1f2937 !important;
        }
        body:not(.home) .nav-link:hover,
        .site-header.scrolled .nav-link:hover {
            color: #1e40af !important;
        }
        body:not(.home) .hamburger-line,
        .site-header.scrolled .hamburger-line {
            background: #1f2937 !important;
        }
        body:not(.home) .site-header {
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        /* ヒーロー スクロールインジケーターを中央に配置 */
        .hero { position: relative; }
        .hero-scroll-indicator {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }
    ';
    wp_add_inline_style('hrdoc-main-style', $logo_css);
}
add_action('wp_enqueue_scripts', 'hrdoc_enqueue_assets');

/**
 * <title>タグのカスタマイズ
 */
function hrdoc_document_title_parts($title) {
    if (is_front_page()) {
        $title['title'] = get_bloginfo('name') . ' | 採用と評価のドキュメントテンプレート';
    }
    return $title;
}
add_filter('document_title_parts', 'hrdoc_document_title_parts');

/**
 * body_class にカスタムクラスを追加
 */
function hrdoc_body_classes($classes) {
    if (is_front_page()) {
        $classes[] = 'is-front-page';
    }
    if (is_user_logged_in()) {
        $classes[] = 'is-logged-in';
    } else {
        $classes[] = 'is-logged-out';
    }
    return $classes;
}
add_filter('body_class', 'hrdoc_body_classes');

/**
 * ウィジェットエリアの登録
 */
function hrdoc_widgets_init() {
    register_sidebar(array(
        'name'          => 'サイドバー',
        'id'            => 'sidebar-1',
        'description'   => 'サイドバーウィジェットエリア',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => 'フッターウィジェット',
        'id'            => 'footer-widgets',
        'description'   => 'フッターウィジェットエリア',
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'hrdoc_widgets_init');

/**
 * セキュリティ強化
 */
remove_action('wp_head', 'wp_generator');

/**
 * ショートコード: 現在のユーザー名を表示
 */
function hrdoc_shortcode_username($atts) {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        return esc_html($current_user->display_name);
    }
    return 'ゲスト';
}
add_shortcode('hrdoc_username', 'hrdoc_shortcode_username');

/**
 * ショートコード: ログイン状態による表示切り替え
 */
function hrdoc_shortcode_logged_in($atts, $content = null) {
    if (is_user_logged_in() && $content) {
        return do_shortcode($content);
    }
    return '';
}
add_shortcode('hrdoc_logged_in', 'hrdoc_shortcode_logged_in');

function hrdoc_shortcode_logged_out($atts, $content = null) {
    if (!is_user_logged_in() && $content) {
        return do_shortcode($content);
    }
    return '';
}
add_shortcode('hrdoc_logged_out', 'hrdoc_shortcode_logged_out');

/**
 * カスタムユーザーテーブル用ヘルパー関数
 */
function hrdoc_get_custom_user($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_users';
    
    return $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$table_name} WHERE id = %d",
            $user_id
        )
    );
}

function hrdoc_get_all_custom_users() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_users';
    
    return $wpdb->get_results(
        "SELECT * FROM {$table_name}"
    );
}

/**
 * ログイン処理のカスタマイズ（将来の拡張用）
 */
function hrdoc_custom_login_redirect($redirect_to, $request, $user) {
    if (isset($user->roles) && is_array($user->roles)) {
        // 管理者の場合はユーザー選択ページへ
        if (in_array('administrator', $user->roles)) {
            return home_url('/select-user');
        }
        // 一般ユーザーはマイページへ
        return home_url('/mypage');
    }
    return $redirect_to;
}
// add_filter('login_redirect', 'hrdoc_custom_login_redirect', 10, 3);

/**
 * カスタムユーザーテーブルの作成
 * 
 * テーブル: {$wpdb->prefix}custom_users
 * テーマ有効化時に自動で作成されます
 */
function hrdoc_create_custom_users_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'custom_users';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE {$table_name} (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        wp_user_id bigint(20) UNSIGNED NOT NULL,
        name varchar(100) DEFAULT '',
        email varchar(100) DEFAULT '',
        phone varchar(50) DEFAULT '',
        address varchar(255) DEFAULT '',
        company_name varchar(100) DEFAULT '',
        status varchar(50) DEFAULT '受付済' COMMENT 'ユーザー全体のステータス',
        role_type tinyint(1) DEFAULT 0 COMMENT '0:一般ユーザー, 1:管理者',
        plan_type varchar(20) DEFAULT 'basic' COMMENT 'basic:基本, premium:プレミアム',
        option_customize int(1) DEFAULT 0,
        option_review int(1) DEFAULT 0,
        option_flow int(1) DEFAULT 0,
        option_1on1 int(1) DEFAULT 0,
        order_date varchar(50) DEFAULT '' COMMENT '申込日',
        order_plan_name varchar(100) DEFAULT '' COMMENT '申込プラン名',
        order_amount varchar(50) DEFAULT '' COMMENT '決済金額',
        order_payment_method varchar(100) DEFAULT '' COMMENT '決済方法',
        order_status varchar(100) DEFAULT '' COMMENT 'ステータス',
        billing_current varchar(50) DEFAULT '' COMMENT '今月の請求額',
        billing_history text COMMENT '支払い履歴',
        billing_next_date varchar(50) DEFAULT '' COMMENT '次回更新日',
        billing_method varchar(100) DEFAULT '' COMMENT '請求決済方法',
        created_at datetime DEFAULT '0000-00-00 00:00:00',
        updated_at datetime DEFAULT '0000-00-00 00:00:00',
        PRIMARY KEY  (id),
        UNIQUE KEY unique_wp_user (wp_user_id),
        KEY idx_email (email),
        KEY idx_role_type (role_type)
    ) {$charset_collate};";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // dbDelta がカラム追加に失敗する場合があるため、強制的にカラムを追加する
    $columns_to_add = array(
        'option_customize' => "int(1) DEFAULT 0",
        'option_review' => "int(1) DEFAULT 0",
        'option_flow' => "int(1) DEFAULT 0",
        'option_1on1' => "int(1) DEFAULT 0"
    );

    foreach ($columns_to_add as $col => $definition) {
        $check = $wpdb->get_results("SHOW COLUMNS FROM `{$table_name}` LIKE '{$col}'");
        if (empty($check)) {
            $wpdb->query("ALTER TABLE `{$table_name}` ADD `{$col}` {$definition} AFTER `plan_type` ");
        }
    }
    
    // テーブル作成ログ（デバッグ用）
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('HR DOC SUITE: custom_users table created/updated.');
    }
}

/**
 * テーマのアクティベーション時の処理
 */
function hrdoc_theme_activation() {
    // カスタムテーブルを作成
    hrdoc_create_custom_users_table();
    
    // リライトルールをフラッシュ
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'hrdoc_theme_activation');

/**
 * init でもテーブル存在確認・更新
 * dbDelta は差分更新を行うため、テーブルが存在していても実行してカラム追加などを反映させる
 */
function hrdoc_check_custom_table() {
    hrdoc_create_custom_users_table();
}
add_action('init', 'hrdoc_check_custom_table');

/* 重複しているため削除（上の関数に統合）
function hrdoc_init_check_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_users';
    
    // テーブルが存在しない場合のみ作成
    if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {
        hrdoc_create_custom_users_table();
    }
}
add_action('init', 'hrdoc_init_check_table');
*/

function hrdoc_theme_deactivation() {
    flush_rewrite_rules();
    // 注意: テーブルは削除しない（データ保持のため）
}
add_action('switch_theme', 'hrdoc_theme_deactivation');
