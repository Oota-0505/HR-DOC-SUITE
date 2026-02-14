<?php
/* Template Name: 新規登録 */
if (!defined('ABSPATH')) exit;

if (is_user_logged_in()) {
  wp_safe_redirect(home_url('/mypage'));
  exit;
}

$msg = '';
if (isset($_GET['registration']) && $_GET['registration'] === 'success') $msg = '登録が完了しました。';
if (isset($_GET['error'])) $msg = 'エラー：' . esc_html($_GET['error']);
get_header();
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/mypage.css">
<main id="join">
  <div class="wrap">
    <div class="box" style="max-width:480px;margin:60px auto;">
      <h1 class="tac">新規登録</h1>
      <?php if ($msg): ?><p class="tac" style="margin-bottom:16px;"><?php echo $msg; ?></p><?php endif; ?>
      <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="custom_register">
        <?php wp_nonce_field('custom_register_action', 'custom_register_nonce'); ?>
        <label>メールアドレス <span style="color:#dc2626;">*</span></label>
        <input type="email" name="email" required>
        <label>パスワード <span style="color:#dc2626;">*</span></label>
        <input type="password" name="password" required minlength="6">
        <label>お名前</label>
        <input type="text" name="name" placeholder="山田 太郎">
        <label>電話番号</label>
        <input type="tel" name="phone" placeholder="090-1234-5678">
        <label>住所</label>
        <input type="text" name="address" placeholder="都道府県から入力">
        <button class="btn" type="submit" style="margin-top:20px;">登録する</button>
      </form>
      <p class="tac muted" style="margin-top:20px;">
        <a href="<?php echo esc_url(home_url('/login')); ?>">ログインはこちら</a>
      </p>
      <p class="tac" style="margin-top:12px;">
        <a href="<?php echo esc_url(home_url('/')); ?>">← トップへ戻る</a>
      </p>
    </div>
  </div>
</main>
<?php get_footer(); ?>
