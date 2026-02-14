<?php
/**
 * Template Name: 支払い情報入力
 * PERSONAL EDITOR 決済ページ
 *
 * @package PERSONAL_EDITOR
 * @since 1.0.0
 */

if (!defined('ABSPATH')) exit;
if (!is_user_logged_in()) { wp_safe_redirect(home_url('/login')); exit; }

$prices = array(
    'basic' => 2178, 'standard' => 4378, 'pro' => 8778,
    '1on1' => 2200, 'customize' => 5500, 'note_check' => 5500, 'meeting' => 8800,
    'flow' => 550, 'review' => 1100, 'tag' => 1100, 'theme' => 1650, 'improve' => 3300, 'long' => 2200
);
$plan_names_jp = array('basic' => 'ベーシックプラン', 'standard' => 'スタンダードプラン', 'pro' => 'プロプラン');
$option_names_jp = array(
    '1on1' => '追加添削（1本）', 'customize' => 'プロフィール文章のブラッシュアップ（1アカウント）',
    'note_check' => 'note記事の骨子作成サポート（1本）', 'meeting' => '世界観整理セッション 60分（1回）',
    'flow' => 'フォント変更・テキスト整形（1回）', 'review' => 'タイトルのブラッシュアップ（1回／3案）',
    'tag' => '導入文リライト（1か所／200~300字）', 'theme' => 'キャッチコピー作成（1回／3案）',
    'improve' => '見出しデザイン作成（1回）', 'long' => '箇条書きから本文化（1か所／200~300字）'
);
$option_keys = array('1on1', 'customize', 'note_check', 'meeting', 'flow', 'review', 'tag', 'theme', 'improve', 'long');

if (!isset($_GET['plan'])) { wp_safe_redirect(home_url('/mypage')); exit; }
$plan = sanitize_text_field($_GET['plan']);
if (!in_array($plan, array('basic', 'standard', 'pro'), true)) $plan = 'basic';

$opt_values = array();
foreach ($option_keys as $k) {
    $get_key = 'opt_' . $k;
    $opt_values[$k] = isset($_GET[$get_key]) ? max(0, intval($_GET[$get_key])) : 0;
}

$plan_price = $prices[$plan] ?? $prices['basic'];
$total = $plan_price;
foreach ($option_keys as $k) {
    $total += $opt_values[$k] * ($prices[$k] ?? 0);
}
$plan_label = $plan_names_jp[$plan] ?? 'ベーシックプラン';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>支払い情報入力 | <?php bloginfo('name'); ?></title>
  <?php wp_head(); ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    body.payment-page{font-family:'Noto Sans JP',sans-serif;min-height:100vh;background:linear-gradient(135deg,#fff7ed,#ffedd5,#fef3c7);color:#1f2937;margin:0;padding:0}
    body.payment-page .pay-wrap{max-width:560px;margin:0 auto;padding:2rem 1rem}
    body.payment-page .pay-back-link{display:inline-block;margin-bottom:1.5rem;color:#c2410c;text-decoration:none;font-size:.9375rem}
    body.payment-page .pay-back-link:hover{text-decoration:underline;color:#ea580c}
    body.payment-page .pay-h1{font-size:1.5rem;font-weight:700;color:#1F2933;margin:0 0 1.5rem}
    body.payment-page .pay-summary{background:#fff;border-radius:12px;padding:1.25rem 1.5rem;margin-bottom:2rem;box-shadow:0 1px 3px rgba(0,0,0,.1);border:1px solid #e5e7eb}
    body.payment-page .pay-summary .pay-summary-h2{font-size:1rem;font-weight:700;color:#1F2933;margin:0 0 .75rem}
    body.payment-page .pay-summary ul{list-style:none;margin:0;padding:0}
    body.payment-page .pay-summary li{padding:.35rem 0;display:flex;justify-content:space-between;font-size:.9375rem}
    body.payment-page .pay-summary li.pay-total{font-weight:700;font-size:1.25rem;margin-top:.75rem;padding-top:.75rem;border-top:1px solid #e5e7eb;color:#F97316}
    body.payment-page .pay-form-group{margin-bottom:1.25rem}
    body.payment-page .pay-form-group label{display:block;font-weight:600;margin-bottom:.35rem;font-size:.875rem;color:#1f2937}
    body.payment-page .pay-form-group input{width:100%;padding:.75rem 1rem;border:1px solid #e5e7eb;border-radius:8px;font-size:1rem;font-family:inherit;background:#fff;color:#1f2937;box-sizing:border-box}
    body.payment-page .pay-form-group input:focus{outline:none;border-color:#F97316;box-shadow:0 0 0 3px rgba(249,115,22,.1)}
    body.payment-page .pay-form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
    body.payment-page .pay-error-msg{color:#ef4444;font-size:.875rem;margin-top:.25rem}
    body.payment-page .pay-btn{display:inline-block;padding:.875rem 1.5rem;border-radius:8px;font-weight:600;text-align:center;cursor:pointer;border:none;font-size:1rem;font-family:inherit;transition:transform .2s,box-shadow .2s}
    body.payment-page .pay-btn-primary{background:linear-gradient(135deg,#F97316,#1F2933);color:#fff;box-shadow:0 2px 4px rgba(249,115,22,.3)}
    body.payment-page .pay-btn-primary:hover{transform:translateY(-1px);box-shadow:0 4px 8px rgba(249,115,22,.4)}
    body.payment-page .pay-btn-secondary{background:#f1f5f9;color:#475569;border:1px solid #e2e8f0}
    body.payment-page .pay-btn-secondary:hover{background:#e2e8f0}
    body.payment-page #payConfirmSection{display:none;margin-top:2rem;padding-top:2rem;border-top:1px solid #e5e7eb}
    body.payment-page #payConfirmSection.pay-is-visible{display:block!important}
    body.payment-page .pay-confirm-box{background:#f0fdf4;border:1px solid #86efac;border-radius:12px;padding:1.25rem;margin-bottom:1.5rem}
    body.payment-page .pay-confirm-box p{margin:0;font-size:.875rem;color:#166534}
    body.payment-page .pay-confirm-box p.pay-confirm-title{font-weight:700}
  </style>
</head>
<body class="payment-page">
<?php wp_body_open(); ?>
<div class="pay-wrap">
  <a href="<?php echo esc_url(home_url('/mypage')); ?>" class="pay-back-link">&laquo; マイページに戻る</a>
  <h1 class="pay-h1">支払い情報入力</h1>
  <div class="pay-summary">
    <h2 class="pay-summary-h2">申込内容</h2>
    <ul>
      <li><span><?php echo esc_html($plan_label); ?></span><span>¥<?php echo number_format($plan_price); ?></span></li>
      <?php foreach ($option_keys as $k) : if ($opt_values[$k] > 0) : ?>
      <li><span><?php echo esc_html($option_names_jp[$k]); ?> × <?php echo $opt_values[$k]; ?></span><span>¥<?php echo number_format($opt_values[$k] * ($prices[$k] ?? 0)); ?></span></li>
      <?php endif; endforeach; ?>
      <li class="pay-total"><span>合計（税込）</span><span>¥<?php echo number_format($total); ?></span></li>
    </ul>
  </div>
  <form id="paymentForm" action="" method="post">
    <?php wp_nonce_field('personaleditor_payment_confirm', 'personaleditor_payment_nonce'); ?>
    <input type="hidden" name="plan" value="<?php echo esc_attr($plan); ?>">
    <input type="hidden" name="total" value="<?php echo esc_attr($total); ?>">
    <?php foreach ($option_keys as $k) : ?>
    <input type="hidden" name="opt_<?php echo esc_attr($k); ?>" value="<?php echo esc_attr($opt_values[$k]); ?>">
    <?php endforeach; ?>
    <div class="pay-form-group"><label for="cardNumber">カード番号 <span style="color:#ef4444">*</span></label><input type="text" id="cardNumber" name="card_number" maxlength="19" placeholder="1234 5678 9012 3456" autocomplete="cc-number"><p class="pay-error-msg" id="cardNumberError"></p></div>
    <div class="pay-form-row">
      <div class="pay-form-group"><label for="cardExpiry">有効期限 <span style="color:#ef4444">*</span></label><input type="text" id="cardExpiry" name="card_expiry" maxlength="5" placeholder="MM/YY"><p class="pay-error-msg" id="cardExpiryError"></p></div>
      <div class="pay-form-group"><label for="cardCvc">CVC <span style="color:#ef4444">*</span></label><input type="text" id="cardCvc" name="card_cvc" maxlength="4" placeholder="123" autocomplete="cc-csc"><p class="pay-error-msg" id="cardCvcError"></p></div>
    </div>
    <div class="pay-form-group"><label for="cardName">名義（カード表記） <span style="color:#ef4444">*</span></label><input type="text" id="cardName" name="card_name" placeholder="TARO YAMADA" autocomplete="cc-name"><p class="pay-error-msg" id="cardNameError"></p></div>
    <button type="button" class="pay-btn pay-btn-primary" id="btnToConfirm" style="width:100%">確認画面へ</button>
  </form>
  <div id="payConfirmSection">
    <div class="pay-confirm-box"><p class="pay-confirm-title">申込内容・支払い情報の要約</p><p>上記の申込内容と入力いただいたカード情報で決済を行います。</p><p class="pay-card-summary" id="confirmCardSummary"></p></div>
    <p style="margin-bottom:1rem"><button type="button" class="pay-btn pay-btn-secondary" id="btnBackToForm">入力に戻る</button></p>
    <form id="confirmForm" action="<?php echo esc_url(home_url('/payment-complete')); ?>" method="post" style="margin:0">
      <?php wp_nonce_field('personaleditor_payment_execute', 'personaleditor_execute_nonce'); ?>
      <input type="hidden" name="plan" value="<?php echo esc_attr($plan); ?>">
      <input type="hidden" name="total" value="<?php echo esc_attr($total); ?>">
      <?php foreach ($option_keys as $k) : ?>
      <input type="hidden" name="opt_<?php echo esc_attr($k); ?>" value="<?php echo esc_attr($opt_values[$k]); ?>">
      <?php endforeach; ?>
      <button type="submit" class="pay-btn pay-btn-primary" id="btnSubmit" style="width:100%">この内容で申し込む</button>
    </form>
  </div>
</div>
<script>
(function(){
function d(s){return(s||'').replace(/\D/g,'')}
function setError(el,msg,errEl){if(msg){el.classList.add('pay-input-error');errEl.textContent=msg;return false}el.classList.remove('pay-input-error');errEl.textContent='';return true}
function vc(v){var x=d(v);if(x.length<13||x.length>19)return'カード番号は13〜19桁で入力してください。';return''}
function ve(v){var m=v.match(/^(\d{1,2})\s*\/\s*(\d{2,4})$/);if(!m)return'有効期限は MM/YY 形式で入力してください。';var mo=parseInt(m[1],10),yr=parseInt(m[2].length===2?'20'+m[2]:m[2],10);var n=new Date();if(mo<1||mo>12)return'有効な月を入力してください。';if(yr<n.getFullYear()||(yr===n.getFullYear()&&mo<n.getMonth()+1))return'有効期限が切れています。';return''}
function vcvc(v){var x=d(v);if(x.length<3||x.length>4)return'CVCは3〜4桁で入力してください。';return''}
function vn(v){if(!v||!v.trim())return'名義を入力してください。';return''}
var cn=document.getElementById('cardNumber'),ce=document.getElementById('cardExpiry'),cc=document.getElementById('cardCvc'),cna=document.getElementById('cardName');
var cne=document.getElementById('cardNumberError'),cee=document.getElementById('cardExpiryError'),cce=document.getElementById('cardCvcError'),cnae=document.getElementById('cardNameError');
var btn=document.getElementById('btnToConfirm'),sec=document.getElementById('payConfirmSection'),btnBack=document.getElementById('btnBackToForm');
if(!btn||!sec)return;
function val(){var ok=true;ok=setError(cn,vc(cn.value),cne)&&ok;ok=setError(ce,ve(ce.value),cee)&&ok;ok=setError(cc,vcvc(cc.value),cce)&&ok;ok=setError(cna,vn(cna.value),cnae)&&ok;return ok}
btn.addEventListener('click',function(){if(!val())return;var x=d(cn.value),l=x.slice(-4);document.getElementById('confirmCardSummary').textContent='カード番号下4桁: ****'+l+'　有効期限: '+(ce.value||'—')+'　名義: '+(cna.value||'—');sec.classList.add('pay-is-visible');sec.scrollIntoView({behavior:'smooth'})});
if(btnBack)btnBack.addEventListener('click',function(){sec.classList.remove('pay-is-visible')});
cn.addEventListener('input',function(){this.value=d(this.value).replace(/(\d{4})(?=\d)/g,'$1 ').trim().slice(0,19)});
ce.addEventListener('input',function(){var v=d(this.value);if(v.length>=2)this.value=v.slice(0,2)+'/'+v.slice(2,4);else this.value=v});
cc.addEventListener('input',function(){this.value=d(this.value).slice(0,4)});
})();
</script>
<?php wp_footer(); ?>
</body>
</html>
