<?php
/**
 * PERSONAL EDITOR ロゴテンプレートパーツ
 *
 * @package PERSONAL_EDITOR
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
$logo_url = function_exists('get_img_path') ? get_img_path() . '/common/header-logo.webp' : get_theme_file_uri('assets/images/common/header-logo.webp');
?>
<img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" width="120" height="32" loading="eager" decoding="async">
