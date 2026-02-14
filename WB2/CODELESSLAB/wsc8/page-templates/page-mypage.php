<?php
/* Template Name: マイページ */
if (!defined('ABSPATH')) exit;
if (!is_user_logged_in()) { wp_safe_redirect(home_url('/login')); exit; }

$current_user = wp_get_current_user();
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : $current_user->ID;
global $wpdb;
$t = $wpdb->prefix . 'custom_users';
$custom_user = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$t} WHERE wp_user_id = %d", $user_id));
$loggedin_cu = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$t} WHERE wp_user_id = %d", $current_user->ID));
$is_admin = user_can($current_user, 'manage_options') || ($loggedin_cu !== null && isset($loggedin_cu->role_type) && (int)$loggedin_cu->role_type === 1);

$PLANS = ['starter' => ['label' => 'スタータープラン', 'price' => 1980], 'standard' => ['label' => 'スタンダードプラン', 'price' => 4980], 'pro' => ['label' => 'プロプラン', 'price' => 9800]];
$OPTIONS = ['extra_task' => ['label' => '追加課題', 'price' => 2000], 'extra_review' => ['label' => 'レビュー追加', 'price' => 3000], 'webflow_studio_review' => ['label' => 'Webflow/STUDIO構築添削', 'price' => 9800], 'api_master' => ['label' => 'API接続マスター講座', 'price' => 5000], 'project_template' => ['label' => '案件獲得テンプレ', 'price' => 3000]];

if (isset($_POST['submit_mypage']) && $is_admin && isset($_POST['mypage_nonce']) && wp_verify_nonce($_POST['mypage_nonce'], 'wsc8_mypage')) {
  $wpdb->update($t, [
    'name' => sanitize_text_field($_POST['name'] ?? ''),
    'email' => sanitize_email($_POST['email'] ?? ''),
    'phone' => sanitize_text_field($_POST['phone'] ?? ''),
    'address' => sanitize_text_field($_POST['address'] ?? ''),
    'plan_name' => sanitize_text_field($_POST['plan_name'] ?? ''),
    'payment_amount' => max(0, (int)($_POST['payment_amount'] ?? 0)),
    'status' => sanitize_text_field($_POST['status'] ?? ''),
  ], ['wp_user_id' => $user_id]);
  wp_safe_redirect(home_url('/mypage/?user_id=' . $user_id)); exit;
}

function h($s){ return esc_html((string)$s); }
function u($s){ return esc_url((string)$s); }
$cu = $custom_user;
$wp_user = get_userdata($user_id);
get_header();
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/mypage.css">
<main id="join">
  <div class="wrap">
    <div style="margin:40px 0;">
      <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
        <h1>マイページ</h1>
        <?php if ($is_admin): ?>
          <a href="<?php echo u(home_url('/select-user')); ?>" class="btn" style="font-size:14px;padding:10px 20px;">ユーザー選択</a>
        <?php endif; ?>
        <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>">ログアウト</a>
      </div>

      <!-- プラン・オプション選択 -->
      <section class="box" style="margin-bottom:32px;">
        <h2>プラン・オプション選択</h2>
        <form id="planForm">
          <div class="pricecell" style="display:flex;flex-wrap:wrap;gap:16px;margin-top:16px;">
            <?php foreach ($PLANS as $k => $p): ?>
              <div class="cell card" style="flex:1;min-width:200px;">
                <p class="plangrade"><strong><?php echo h($p['label']); ?></strong></p>
                <div class="price"><?php echo number_format($p['price']); ?><span class="lrg">円(税込)</span></div>
                <label><input type="radio" name="plan" class="plan-radio" value="<?php echo h($k); ?>" data-price="<?php echo $p['price']; ?>"> このプランを選択する</label>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="optionselect" style="margin-top:24px;">
            <?php foreach ($OPTIONS as $k => $o): ?>
              <div class="optitem" style="margin-bottom:8px;">
                <label style="display:flex;gap:10px;align-items:center;">
                  <input type="number" class="option-input" name="opt_<?php echo h($k); ?>" min="0" value="0" data-price="<?php echo $o['price']; ?>" style="width:60px;">
                  <span><?php echo h($o['label']); ?></span>
                  <strong>¥<?php echo number_format($o['price']); ?>(税込)</strong>
                </label>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="sum" style="margin-top:24px;">
            <p>合計金額（税込）：<strong id="totalAmount">¥0</strong></p>
          </div>
          <button type="button" class="btn" id="btnPayment" style="margin-top:16px;">決済</button>
        </form>
      </section>

      <!-- アカウント情報・申込履歴 -->
      <section class="box">
        <h2>アカウント情報</h2>
        <form method="post">
          <?php wp_nonce_field('wsc8_mypage', 'mypage_nonce'); ?>
          <input type="hidden" name="submit_mypage" value="1">
          <label>氏名</label>
          <input type="text" name="name" value="<?php echo h($cu->name ?? $wp_user->display_name ?? ''); ?>" <?php echo $is_admin ? '' : 'readonly'; ?>>
          <label>メールアドレス</label>
          <input type="email" name="email" value="<?php echo h($cu->email ?? $wp_user->user_email ?? ''); ?>" <?php echo $is_admin ? '' : 'readonly'; ?>>
          <label>電話番号</label>
          <input type="tel" name="phone" value="<?php echo h($cu->phone ?? ''); ?>" <?php echo $is_admin ? '' : 'readonly'; ?>>
          <label>住所</label>
          <input type="text" name="address" value="<?php echo h($cu->address ?? ''); ?>" <?php echo $is_admin ? '' : 'readonly'; ?>>
          <?php if ($is_admin): ?>
            <h3 style="margin-top:24px;">申込履歴（管理者用）</h3>
            <label>プラン名</label>
            <input type="text" name="plan_name" value="<?php echo h($cu->plan_name ?? ''); ?>">
            <label>決済金額</label>
            <input type="number" name="payment_amount" value="<?php echo (int)($cu->payment_amount ?? 0); ?>">
            <label>ステータス</label>
            <input type="text" name="status" value="<?php echo h($cu->status ?? ''); ?>">
            <button class="btn" type="submit" style="margin-top:16px;">保存する</button>
          <?php else: ?>
            <h3 style="margin-top:24px;">申込履歴</h3>
            <p>プラン：<?php echo h($cu->plan_name ?? '—'); ?></p>
            <p>決済金額：¥<?php echo number_format((int)($cu->payment_amount ?? 0)); ?></p>
            <p>ステータス：<?php echo h($cu->status ?? '—'); ?></p>
          <?php endif; ?>
        </form>
        <p style="margin-top:24px;"><a href="<?php echo u(home_url('/#form')); ?>">お問い合わせはこちら</a></p>
      </section>
    </div>
  </div>
</main>
<script>
(function(){
  function calc(){
    var total=0;
    var plan=document.querySelector('.plan-radio:checked');
    if(plan) total+=parseInt(plan.dataset.price||0,10);
    document.querySelectorAll('.option-input').forEach(function(el){
      total+=(parseInt(el.value,10)||0)*(parseInt(el.dataset.price,10)||0);
    });
    document.getElementById('totalAmount').textContent='¥'+total.toLocaleString();
  }
  document.querySelectorAll('.plan-radio, .option-input').forEach(function(el){
    el.addEventListener('change',calc);
  });
  document.getElementById('btnPayment').addEventListener('click',function(){
    var plan=document.querySelector('.plan-radio:checked');
    if(!plan){alert('プランを選択してください');return;}
    var params=new URLSearchParams({plan:plan.value});
    document.querySelectorAll('.option-input').forEach(function(el){
      var v=parseInt(el.value,10)||0;
      if(v>0) params.set(el.name,v);
    });
    location.href='<?php echo u(home_url('/payment')); ?>?'+params.toString();
  });
  calc();
})();
</script>
<?php get_footer(); ?>
