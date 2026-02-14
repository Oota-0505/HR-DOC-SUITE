<?php
/**
 * Template Name: 支払い情報入力
 * Description: クレジットカード入力・申込確認・確定
 *
 * @package HR_DOC_SUITE
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!is_user_logged_in()) {
    wp_safe_redirect(home_url('/login'));
    exit;
}

// 価格定義（マイページと同一）
$prices = array(
    'basic'     => 2178,
    'standard'  => 4278,
    'pro'       => 10780,
    'customize' => 11000,
    'review'    => 8800,
    'flow'      => 8800,
    '1on1'      => 13200
);
$plan_names_jp = array('basic' => 'ベーシックプラン', 'standard' => 'スタンダードプラン', 'pro' => 'プロプラン');
$option_names_jp = array(
    'customize' => 'テンプレート個別カスタマイズ',
    'review'    => '既存の評価シートのレビュー・改善コメント',
    'flow'      => '採用フロー全体図の整理PDF',
    '1on1'      => '1on1運用ガイドライン作成'
);

// GET で受け取り（マイページから遷移時）。パラメータなしの直アクセスはマイページへ
if (!isset($_GET['plan'])) {
    wp_safe_redirect(home_url('/mypage'));
    exit;
}
$plan = sanitize_text_field($_GET['plan']);
if (!in_array($plan, array('basic', 'standard', 'pro'), true)) {
    $plan = 'basic';
}
$opt_customize = isset($_GET['opt_customize']) ? max(0, intval($_GET['opt_customize'])) : 0;
$opt_review    = isset($_GET['opt_review']) ? max(0, intval($_GET['opt_review'])) : 0;
$opt_flow      = isset($_GET['opt_flow']) ? max(0, intval($_GET['opt_flow'])) : 0;
$opt_1on1      = isset($_GET['opt_1on1']) ? max(0, intval($_GET['opt_1on1'])) : 0;

$plan_price = $prices[$plan] ?? $prices['basic'];
$total = $plan_price
    + ($opt_customize * $prices['customize'])
    + ($opt_review * $prices['review'])
    + ($opt_flow * $prices['flow'])
    + ($opt_1on1 * $prices['1on1']);

$plan_label = $plan_names_jp[$plan] ?? 'ベーシックプラン';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>支払い情報入力 | <?php bloginfo('name'); ?></title>
  <?php wp_head(); ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    /* 支払いページ専用 - body.payment-page でスコープし他ページと競合しない */
    html.pay-html,
    body.payment-page { margin: 0 !important; padding: 0 !important; box-sizing: border-box !important; }
    body.payment-page { font-family: 'Noto Sans JP', sans-serif !important; min-height: 100vh !important; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #f0fdfa 100%) !important; color: #1f2937 !important; }
    body.payment-page .pay-wrap { max-width: 560px; margin: 0 auto; padding: 2rem 1rem; }
    body.payment-page .pay-back-link { display: inline-block; margin-bottom: 1.5rem; color: #1e40af; text-decoration: none; font-size: 0.9375rem; }
    body.payment-page .pay-back-link:hover { text-decoration: underline; color: #3b82f6; }
    body.payment-page .pay-h1 { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0 0 1.5rem 0; }
    body.payment-page .pay-summary { background: #fff; border-radius: 12px; padding: 1.25rem 1.5rem; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; }
    body.payment-page .pay-summary .pay-summary-h2 { font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0 0 0.75rem 0; }
    body.payment-page .pay-summary ul { list-style: none; margin: 0; padding: 0; }
    body.payment-page .pay-summary li { padding: 0.35rem 0; display: flex; justify-content: space-between; font-size: 0.9375rem; }
    body.payment-page .pay-summary li.pay-total { font-weight: 700; font-size: 1.25rem; margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid #e5e7eb; color: #1e40af; }
    body.payment-page .pay-form-group { margin-bottom: 1.25rem; }
    body.payment-page .pay-form-group label { display: block; font-weight: 600; margin-bottom: 0.35rem; font-size: 0.875rem; color: #1f2937; }
    body.payment-page .pay-form-group input { width: 100%; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 1rem; font-family: inherit; background: #fff; color: #1f2937; box-sizing: border-box; }
    body.payment-page .pay-form-group input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
    body.payment-page .pay-form-group input.pay-input-error { border-color: #ef4444 !important; }
    body.payment-page .pay-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    body.payment-page .pay-error-msg { color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem; }
    body.payment-page .pay-btn { display: inline-block; padding: 0.875rem 1.5rem; border-radius: 8px; font-weight: 600; text-align: center; cursor: pointer; border: none; font-size: 1rem; font-family: inherit; transition: transform 0.2s, box-shadow 0.2s; }
    body.payment-page .pay-btn-primary { background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: #fff; box-shadow: 0 2px 4px rgba(30,64,175,0.3); }
    body.payment-page .pay-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 8px rgba(30,64,175,0.4); }
    body.payment-page .pay-btn-secondary { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    body.payment-page .pay-btn-secondary:hover { background: #e2e8f0; }
    body.payment-page #payConfirmSection { display: none; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb; }
    body.payment-page #payConfirmSection.pay-is-visible { display: block !important; }
    body.payment-page .pay-confirm-box { background: #f0fdf4; border: 1px solid #86efac; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem; }
    body.payment-page .pay-confirm-box p { margin: 0; font-size: 0.875rem; color: #166534; }
    body.payment-page .pay-confirm-box p.pay-confirm-title { font-weight: 700; }
    body.payment-page .pay-confirm-box p.pay-card-summary { margin-top: 0.75rem; font-size: 0.8125rem; color: #64748b; }
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
      <?php if ($opt_customize > 0) : ?><li><span><?php echo esc_html($option_names_jp['customize']); ?> × <?php echo $opt_customize; ?></span><span>¥<?php echo number_format($opt_customize * $prices['customize']); ?></span></li><?php endif; ?>
      <?php if ($opt_review > 0) : ?><li><span><?php echo esc_html($option_names_jp['review']); ?> × <?php echo $opt_review; ?></span><span>¥<?php echo number_format($opt_review * $prices['review']); ?></span></li><?php endif; ?>
      <?php if ($opt_flow > 0) : ?><li><span><?php echo esc_html($option_names_jp['flow']); ?> × <?php echo $opt_flow; ?></span><span>¥<?php echo number_format($opt_flow * $prices['flow']); ?></span></li><?php endif; ?>
      <?php if ($opt_1on1 > 0) : ?><li><span><?php echo esc_html($option_names_jp['1on1']); ?> × <?php echo $opt_1on1; ?></span><span>¥<?php echo number_format($opt_1on1 * $prices['1on1']); ?></span></li><?php endif; ?>
      <li class="pay-total"><span>合計（税込）</span><span>¥<?php echo number_format($total); ?></span></li>
    </ul>
  </div>

  <form id="paymentForm" action="" method="post">
    <?php wp_nonce_field('hrdoc_payment_confirm', 'hrdoc_payment_nonce'); ?>
    <input type="hidden" name="plan" value="<?php echo esc_attr($plan); ?>">
    <input type="hidden" name="total" value="<?php echo esc_attr($total); ?>">
    <input type="hidden" name="opt_customize" value="<?php echo esc_attr($opt_customize); ?>">
    <input type="hidden" name="opt_review" value="<?php echo esc_attr($opt_review); ?>">
    <input type="hidden" name="opt_flow" value="<?php echo esc_attr($opt_flow); ?>">
    <input type="hidden" name="opt_1on1" value="<?php echo esc_attr($opt_1on1); ?>">

    <div class="pay-form-group">
      <label for="cardNumber">カード番号 <span style="color:#ef4444;">*</span></label>
      <input type="text" id="cardNumber" name="card_number" maxlength="19" placeholder="1234 5678 9012 3456" autocomplete="cc-number">
      <p class="pay-error-msg" id="cardNumberError" aria-live="polite"></p>
    </div>
    <div class="pay-form-row">
      <div class="pay-form-group">
        <label for="cardExpiry">有効期限 <span style="color:#ef4444;">*</span></label>
        <input type="text" id="cardExpiry" name="card_expiry" maxlength="5" placeholder="MM/YY">
        <p class="pay-error-msg" id="cardExpiryError" aria-live="polite"></p>
      </div>
      <div class="pay-form-group">
        <label for="cardCvc">CVC <span style="color:#ef4444;">*</span></label>
        <input type="text" id="cardCvc" name="card_cvc" maxlength="4" placeholder="123" autocomplete="cc-csc">
        <p class="pay-error-msg" id="cardCvcError" aria-live="polite"></p>
      </div>
    </div>
    <div class="pay-form-group">
      <label for="cardName">名義（カード表記） <span style="color:#ef4444;">*</span></label>
      <input type="text" id="cardName" name="card_name" placeholder="TARO YAMADA" autocomplete="cc-name">
      <p class="pay-error-msg" id="cardNameError" aria-live="polite"></p>
    </div>
    <button type="button" class="pay-btn pay-btn-primary" id="btnToConfirm" style="width:100%;">確認画面へ</button>
  </form>

  <div id="payConfirmSection">
    <div class="pay-confirm-box">
      <p class="pay-confirm-title">申込内容・支払い情報の要約</p>
      <p>上記の申込内容と入力いただいたカード情報で決済を行います。</p>
      <p class="pay-card-summary" id="confirmCardSummary"><!-- JSで挿入 --></p>
    </div>
    <p style="margin-bottom:1rem;"><button type="button" class="pay-btn pay-btn-secondary" id="btnBackToForm">入力に戻る</button></p>
    <form id="confirmForm" action="<?php echo esc_url(home_url('/payment-complete')); ?>" method="post" style="margin:0;">
      <?php wp_nonce_field('hrdoc_payment_execute', 'hrdoc_execute_nonce'); ?>
      <input type="hidden" name="plan" value="<?php echo esc_attr($plan); ?>">
      <input type="hidden" name="total" value="<?php echo esc_attr($total); ?>">
      <input type="hidden" name="opt_customize" value="<?php echo esc_attr($opt_customize); ?>">
      <input type="hidden" name="opt_review" value="<?php echo esc_attr($opt_review); ?>">
      <input type="hidden" name="opt_flow" value="<?php echo esc_attr($opt_flow); ?>">
      <input type="hidden" name="opt_1on1" value="<?php echo esc_attr($opt_1on1); ?>">
      <button type="submit" class="pay-btn pay-btn-primary" id="btnSubmit" style="width:100%;">この内容で申し込む</button>
    </form>
  </div>
</div>

  <script>
  (function() {
    var cardNumber = document.getElementById('cardNumber');
    var cardExpiry = document.getElementById('cardExpiry');
    var cardCvc = document.getElementById('cardCvc');
    var cardName = document.getElementById('cardName');
    var cardNumberError = document.getElementById('cardNumberError');
    var cardExpiryError = document.getElementById('cardExpiryError');
    var cardCvcError = document.getElementById('cardCvcError');
    var cardNameError = document.getElementById('cardNameError');
    var btnToConfirm = document.getElementById('btnToConfirm');
    var confirmSection = document.getElementById('payConfirmSection');
    var btnBackToForm = document.getElementById('btnBackToForm');

    function onlyDigits(s) { return (s || '').replace(/\D/g, ''); }
    function setError(el, msgEl, msg) {
      if (msg) { el.classList.add('pay-input-error'); msgEl.textContent = msg; return false; }
      el.classList.remove('pay-input-error'); msgEl.textContent = ''; return true;
    }
    function validateCardNumber(v) {
      var digits = onlyDigits(v);
      if (digits.length < 13 || digits.length > 19) return 'カード番号は13〜19桁で入力してください。';
      return '';
    }
    function validateExpiry(v) {
      var m = v.match(/^(\d{1,2})\s*\/\s*(\d{2,4})$/);
      if (!m) return '有効期限は MM/YY 形式で入力してください。';
      var month = parseInt(m[1], 10);
      var year = parseInt(m[2].length === 2 ? '20' + m[2] : m[2], 10);
      var now = new Date();
      if (month < 1 || month > 12) return '有効な月を入力してください。';
      if (year < now.getFullYear() || (year === now.getFullYear() && month < now.getMonth() + 1)) return '有効期限が切れています。';
      return '';
    }
    function validateCvc(v) {
      var digits = onlyDigits(v);
      if (digits.length < 3 || digits.length > 4) return 'CVCは3〜4桁で入力してください。';
      return '';
    }
    function validateName(v) {
      if (!v || !v.trim()) return '名義を入力してください。';
      return '';
    }

    function validateAll() {
      var ok = true;
      ok = setError(cardNumber, cardNumberError, validateCardNumber(cardNumber.value)) && ok;
      ok = setError(cardExpiry, cardExpiryError, validateExpiry(cardExpiry.value)) && ok;
      ok = setError(cardCvc, cardCvcError, validateCvc(cardCvc.value)) && ok;
      ok = setError(cardName, cardNameError, validateName(cardName.value)) && ok;
      return ok;
    }

    btnToConfirm.addEventListener('click', function() {
      if (!validateAll()) return;
      var digits = onlyDigits(cardNumber.value);
      var last4 = digits.slice(-4);
      var el = document.getElementById('confirmCardSummary');
      if (el) el.textContent = 'カード番号下4桁: ****' + last4 + '　有効期限: ' + (cardExpiry.value || '—') + '　名義: ' + (cardName.value || '—');
      confirmSection.classList.add('pay-is-visible');
      confirmSection.scrollIntoView({ behavior: 'smooth' });
    });
    btnBackToForm.addEventListener('click', function() {
      confirmSection.classList.remove('pay-is-visible');
    });

    cardNumber.addEventListener('input', function() {
      this.value = onlyDigits(this.value).replace(/(\d{4})(?=\d)/g, '$1 ').trim().slice(0, 19);
    });
    cardExpiry.addEventListener('input', function() {
      var v = onlyDigits(this.value);
      if (v.length >= 2) this.value = v.slice(0,2) + '/' + v.slice(2,4);
      else this.value = v;
    });
    cardCvc.addEventListener('input', function() {
      this.value = onlyDigits(this.value).slice(0, 4);
    });
  })();
  </script>
  <?php wp_footer(); ?>
</body>
</html>
