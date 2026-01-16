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

/**
 * スタイルとスクリプトの読み込み
 */
function hrdoc_enqueue_assets() {
    // テーマバージョン（キャッシュバスティング用）
    $theme_version = wp_get_theme()->get('Version');

    // Google Fonts
    wp_enqueue_style(
        'hrdoc-google-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap',
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

    // 認証ページ用CSS
    if (is_page_template(array(
        'page-templates/page-login.php',
        'page-templates/page-register.php',
        'page-templates/page-mypage.php',
        'page-templates/page-select-user.php'
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
        option_guide int(1) DEFAULT 0,
        option_remake int(1) DEFAULT 0,
        option_extra int(1) DEFAULT 0,
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
