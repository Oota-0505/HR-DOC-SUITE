<?php
/**
 * Template Name: 新規登録ページ（廃止）
 * Description: 新規登録は廃止。お問い合わせへリダイレクトします。
 *
 * @package HR_DOC_SUITE
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// ログイン済みならマイページへ
if (is_user_logged_in()) {
    wp_safe_redirect(home_url('/mypage/'));
    exit;
}

// 新規登録ページは廃止。お問い合わせへリダイレクト
wp_safe_redirect(home_url('/#contact'));
exit;
