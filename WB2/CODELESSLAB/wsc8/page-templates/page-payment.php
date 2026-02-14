<?php
/* Template Name: 支払い情報入力 */
if (!defined('ABSPATH')) exit;
if (!is_user_logged_in()) { wp_safe_redirect(home_url('/login')); exit; }

$PLANS = ['starter' => ['label' => 'スタータープラン', 'price' => 1980], 'standard' => ['label' => 'スタンダードプラン', 'price' => 4980], 'pro' => ['label' => 'プロプラン', 'price' => 9800]];
$OPTIONS = ['extra_task' => ['label' => '追加課題', 'price' => 2000], 'extra_review' => ['label' => 'レビュー追加', 'price' => 3000], 'webflow_studio_review' => ['label' => 'Webflow/STUDIO構築添削', 'price' => 9800], 'api_master' => ['label' => 'API接続マスター講座', 'price' => 5000], 'project_template' => ['label' => '案件獲得テンプレ', 'price' => 3000]];
$opt_keys = array_keys($OPTIONS);

if (!isset($_GET['plan'])) { wp_safe_redirect(home_url('/mypage')); exit; }
$plan = sanitize_text_field($_GET['plan']);
if (!isset($PLANS[$plan])) $plan = 'standard';
$opts = [];
foreach ($opt_keys as $k) {
  $opts[$k] = isset($_GET['opt_' . $k]) ? max(0, (int)$_GET['opt_' . $k]) : 0;
}
$plan_price = $PLANS[$plan]['price'] ?? 4980;
$total = $plan_price;
foreach ($opt_keys as $k) {
  if (isset($OPTIONS[$k]) && ($opts[$k] ?? 0) > 0) $total += $opts[$k] * $OPTIONS[$k]['price'];
}
function h($s){ return esc_html((string)$s); }
function u($s){ return esc_url((string)$s); }
get_header();
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/mypage.css">
<main id="join">
  <div class="wrap">
    <div class="box" style="max-width:560px;margin:60px auto;">
      <p style="margin-bottom:16px;"><a href="<?php echo u(home_url('/mypage')); ?>">← マイページに戻る</a></p>
      <h1 class="tac">支払い情報入力</h1>
      <div class="sum" style="margin-bottom:24px;">
        <p><strong><?php echo h($PLANS[$plan]['label'] ?? $plan); ?></strong> ¥<?php echo number_format($plan_price); ?></p>
        <?php foreach ($opt_keys as $k): if (($opts[$k] ?? 0) > 0 && isset($OPTIONS[$k])): ?>
          <p><?php echo h($OPTIONS[$k]['label']); ?> × <?php echo $opts[$k]; ?> ¥<?php echo number_format($opts[$k] * $OPTIONS[$k]['price']); ?></p>
        <?php endif; endforeach; ?>
        <p style="font-size:1.2rem;font-weight:bold;margin-top:12px;padding-top:12px;border-top:1px solid #e5e7eb;">合計（税込） ¥<?php echo number_format($total); ?></p>
      </div>
      <form id="payForm" method="post" action="">
        <?php wp_nonce_field('wsc8_payment_confirm', 'payment_nonce'); ?>
        <input type="hidden" name="plan" value="<?php echo h($plan); ?>">
        <input type="hidden" name="total" value="<?php echo (int)$total; ?>">
        <?php foreach ($opt_keys as $k): ?><input type="hidden" name="opt[<?php echo h($k); ?>]" value="<?php echo (int)($opts[$k] ?? 0); ?>"><?php endforeach; ?>
        <p id="cardNumberError" style="display:none;color:#dc2626;font-size:14px;margin-bottom:8px;"></p>
        <label>カード番号 <span style="color:#dc2626;">*</span></label>
        <input type="tel" name="card_number" id="cardNumber" maxlength="19" placeholder="1234 5678 9012 3456" required autocomplete="cc-number" inputmode="numeric" pattern="[0-9\s]*">
        <label>カード名義（ローマ字） <span style="color:#dc2626;">*</span></label>
        <input type="text" name="card_name" placeholder="TARO YAMADA" required>
        <div class="row">
          <div><label>有効期限（月）</label><input type="tel" name="exp_month" placeholder="MM" maxlength="2" inputmode="numeric" pattern="[0-9]*" required></div>
          <div><label>有効期限（年）</label><input type="tel" name="exp_year" placeholder="YY" maxlength="2" inputmode="numeric" pattern="[0-9]*" required></div>
        </div>
        <label>セキュリティコード <span style="color:#dc2626;">*</span></label>
        <input type="tel" name="cvc" maxlength="4" placeholder="123" inputmode="numeric" pattern="[0-9]*" required>
        <button class="btn" type="button" id="btnConfirm">確認画面へ</button>
      </form>
      <div id="confirmSection" style="display:none;margin-top:24px;padding-top:24px;border-top:1px solid #e5e7eb;">
        <p class="muted" style="margin-bottom:16px;">上記内容で決済を行います。</p>
        <form method="post" action="<?php echo u(home_url('/payment-complete')); ?>">
          <?php wp_nonce_field('wsc8_payment_execute', 'execute_nonce'); ?>
          <input type="hidden" name="plan" value="<?php echo h($plan); ?>">
          <input type="hidden" name="total" value="<?php echo (int)$total; ?>">
          <?php foreach ($opt_keys as $k): ?><input type="hidden" name="opt[<?php echo h($k); ?>]" value="<?php echo (int)($opts[$k] ?? 0); ?>"><?php endforeach; ?>
          <button class="btn" type="submit">この内容で申し込む</button>
        </form>
        <p style="margin-top:12px;"><button type="button" class="btn" id="btnBack" style="background:#64748b;">入力に戻る</button></p>
      </div>
    </div>
  </div>
</main>
<script>
(function(){
  var btn=document.getElementById('btnConfirm'), sec=document.getElementById('confirmSection'), back=document.getElementById('btnBack');
  var cardInput=document.getElementById('cardNumber'), errEl=document.getElementById('cardNumberError');
  function getCardDigits(v){ return (v||'').replace(/\D/g,''); }
  function validateCardNumber(){ var d=getCardDigits(cardInput?cardInput.value:''); return d.length>=13&&d.length<=16; }
  if(btn) btn.addEventListener('click', function(){
    if(errEl) errEl.style.display='none';
    if(!validateCardNumber()){
      if(errEl){ errEl.textContent='カード番号は13〜16桁で入力してください。'; errEl.style.display='block'; }
      if(cardInput){ cardInput.focus(); cardInput.scrollIntoView({behavior:'smooth',block:'center'}); }
      return;
    }
    sec.style.display='block'; sec.scrollIntoView({behavior:'smooth'});
  });
  if(cardInput) {
    cardInput.addEventListener('input', function(){
      if(errEl) errEl.style.display='none';
      var v=this.value.replace(/\D/g,'').slice(0,16);
      var parts=v.match(/.{1,4}/g)||[];
      this.value=parts.join(' ').trim();
    });
  }
  if(back) back.addEventListener('click', function(){ sec.style.display='none'; if(errEl) errEl.style.display='none'; });
})();
</script>
<?php get_footer(); ?>
