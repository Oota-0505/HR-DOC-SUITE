<?php
/*
Template Name: Join Flow Demo
*/
if (!is_user_logged_in()) {
  wp_safe_redirect(home_url('/login?redirect_to=' . urlencode(get_permalink())));
  exit;
}
get_header();

// step判定
$step = isset($_GET['step']) ? (string)$_GET['step'] : 'plan';
$allowed = ['plan','card','confirm','complete'];
if (!in_array($step, $allowed, true)) $step = 'plan';

// 受け渡し（デモなので簡略：POST値をそのまま使う）
$plan  = (string)($_POST['plan'] ?? ($_GET['plan'] ?? 'standard'));
$opts  = $_POST['opt'] ?? []; // opt[xxx]=1 の配列
$total = (int)($_POST['total'] ?? ($_GET['total'] ?? 0));

// 表示用：プラン/オプション定義（あなたの内容）
$PLANS = [
  'starter'  => ['label' => 'スタータープラン',   'price' => 1980],
  'standard' => ['label' => 'スタンダードプラン', 'price' => 4980],
  'pro'      => ['label' => 'プロプラン',         'price' => 9800],
];
$OPTIONS = [
  'extra_task'            => ['label' => '追加課題',              'price' => 2000],
  'extra_review'          => ['label' => 'レビュー追加',          'price' => 3000],
  'webflow_studio_review' => ['label' => 'Webflow/STUDIO構築添削', 'price' => 9800],
  'api_master'            => ['label' => 'API接続マスター講座',    'price' => 5000],
  'project_template'      => ['label' => '案件獲得テンプレ',        'price' => 3000],
];

// デモ用：totalが0ならサーバ側でも再計算して埋める（表示が崩れないよう保険）
if ($total <= 0) {
  $total = $PLANS[$plan]['price'] ?? 0;
  if (is_array($opts)) {
    foreach ($opts as $k => $v) {
      if ((string)$v === '1' && isset($OPTIONS[$k])) $total += (int)$OPTIONS[$k]['price'];
    }
  }
}

function h($s){ return esc_html((string)$s); }
function u($s){ return esc_url((string)$s); }

$page_url = get_permalink(); // 固定ページのURL
?>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/mypage.css">

<main id="join">
  <div class="wrap">

<?php if ($step === 'plan'): ?>
    <!-- STEP1: プラン・オプション選択 -->
    <form method="post" id="f" action="<?php echo u($page_url . '?step=card'); ?>">
      <section>
        <div class="pricecell">
          <div class="cell card">
            <p class="plangrade"><strong>スタータープラン</strong></p>
            <div class="price">1,980<span class="lrg">円(税込)</span></div>
            <ul><li>月1課題</li><li>UIキットダウンロード</li><li>質問チャット（24h受付・48h以内回答）</li></ul>
            <label><input type="radio" name="plan" value="starter" data-price="1980" <?php checked($plan,'starter'); ?>> このプランを選択する</label>
          </div>

          <div class="cell card reco">
            <p class="plangrade"><strong>スタンダードプラン</strong></p>
            <div class="price">4,980<span class="lrg">円(税込)</span></div>
            <ul><li>毎月2課題</li><li>STUDIO / Webflow / Glide から選択</li><li>レビュー1回</li><li>改善PDF</li><li>月1の学習ステップ相談フォーム</li></ul>
            <label><input type="radio" name="plan" value="standard" data-price="4980" <?php checked($plan,'standard'); ?>> このプランを選択する</label>
          </div>

          <div class="cell card">
            <p class="plangrade"><strong>プロプラン</strong></p>
            <div class="price">9,800<span class="lrg">円(税込)</span></div>
            <ul><li>毎月1〜4課題</li><li>複数ツール横断</li><li>レビュー4回</li><li>API接続課題</li><li>副業・転職向けポートフォリオ構成案</li></ul>
            <label><input type="radio" name="plan" value="pro" data-price="9800" <?php checked($plan,'pro'); ?>> このプランを選択する</label>
          </div>
        </div>
      </section>

      <section>
        <div class="optionselect" id="opts">
          <?php foreach ($OPTIONS as $k => $o): ?>
            <div class="optitem">
              <label style="display:flex;gap:10px;align-items:center;">
                <input type="checkbox" name="opt[<?php echo h($k); ?>]" value="1" data-price="<?php echo (int)$o['price']; ?>"
                  <?php echo (is_array($opts) && isset($opts[$k]) && (string)$opts[$k]==='1') ? 'checked' : ''; ?>>
                <span><?php echo h($o['label']); ?></span>
                <strong>¥<?php echo number_format((int)$o['price']); ?>(税込)</strong>
              </label>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

      <section>
        <h1 class="tac">合計金額</h1>
        <div class="sum">
          <div class="yen" id="total">0円(税込)</div>
        </div>

        <!-- 次ページへ渡す用（JSで入れる） -->
        <input type="hidden" name="total" id="totalInput" value="0">

        <button class="btn" id="nextBtn" type="submit">支払い情報画面へ</button>
      </section>
    </form>

    <script>
    (function(){
      const form = document.getElementById('f');
      const totalEl = document.getElementById('total');
      const totalInput = document.getElementById('totalInput');

      function fmt(n){
        try { return Number(n).toLocaleString('ja-JP') + '円(税込)'; }
        catch(e){ return String(n) + '円(税込)'; }
      }

      function calc(){
        let total = 0;
        const plan = form.querySelector('input[name="plan"]:checked');
        if (plan) total += Number(plan.dataset.price || 0);

        const opts = form.querySelectorAll('input[type="checkbox"][name^="opt["]:checked');
        opts.forEach(cb => total += Number(cb.dataset.price || 0));

        totalEl.textContent = fmt(total);
        totalInput.value = String(total);
      }

      form.addEventListener('change', calc);
      calc();
    })();
    </script>

<?php elseif ($step === 'card'): ?>
  <!-- STEP2: ダミーのカード入力（流用HTML） -->
  <h1 class="tac">支払い情報入力</h1>

  <div class="box">
    <form method="post" action="<?php echo u($page_url . '?step=confirm'); ?>">
      <!-- デモなのでcsrfは飾りでOK（そのまま流用） -->
      <input type="hidden" name="csrf" value="f8e91d45e6082a0f5fac377aa9bc368c74e24501e68b88cb6546c4c5c22d8d50">

      <!-- 選択内容の引き継ぎ（これが重要） -->
      <input type="hidden" name="plan" value="<?php echo h($plan); ?>">
      <?php if (is_array($opts)) foreach ($opts as $k => $v): ?>
        <input type="hidden" name="opt[<?php echo h($k); ?>]" value="<?php echo h($v); ?>">
      <?php endforeach; ?>
      <input type="hidden" name="total" value="<?php echo (int)$total; ?>">

      <label>カード番号</label>
      <input type="text" name="card_number" placeholder="1234 5678 9012 3456">

      <label>カード名義（ローマ字）</label>
      <input type="text" name="card_name" placeholder="TARO YAMADA">

      <div class="row">
        <div>
          <label>有効期限（月）</label>
          <select name="exp_month">
            <?php for ($m=1; $m<=12; $m++): ?>
              <option value="<?php echo $m; ?>"><?php echo $m; ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div>
          <label>有効期限（年）</label>
          <select name="exp_year">
            <?php for ($y=2026; $y<=2035; $y++): ?>
              <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
            <?php endfor; ?>
          </select>
        </div>
      </div>

      <label>セキュリティコード</label>
      <input type="text" name="cvc" placeholder="123">

      <button class="btn" type="submit">次へ（決済額合計）</button>

      <p class="tac" style="margin-top:12px;">
        <a href="<?php echo u($page_url . '?step=plan'); ?>">← プラン選択に戻る</a>
      </p>
    </form>
  </div>


<?php elseif ($step === 'confirm'): ?>
    <!-- STEP3: 確認 -->


    <h1 class="tac">決済額合計（確認）</h1>

    <div class="box">
    	<p class="tac">合計：<strong><?php echo number_format($total); ?>円(税込)</strong></p>
      <p><strong>プラン：</strong><?php echo h($PLANS[$plan]['label'] ?? $plan); ?></p>
      <p><strong>オプション：</strong>
        <?php
          $optLabels = [];
          if (is_array($opts)) {
            foreach ($opts as $k => $v) {
              if ((string)$v==='1' && isset($OPTIONS[$k])) $optLabels[] = $OPTIONS[$k]['label'];
            }
          }
          echo $optLabels ? h(implode(' / ', $optLabels)) : 'なし';
        ?>
      </p>
    </div>

    <form method="post" action="<?php echo u($page_url . '?step=complete'); ?>">
      <input type="hidden" name="plan" value="<?php echo h($plan); ?>">
      <?php if (is_array($opts)) foreach ($opts as $k => $v): ?>
        <input type="hidden" name="opt[<?php echo h($k); ?>]" value="<?php echo h($v); ?>">
      <?php endforeach; ?>
      <input type="hidden" name="total" value="<?php echo (int)$total; ?>">

      <p style="display:flex;gap:12px;justify-content:center;">
        <button class="btn" type="submit">この内容で申し込む</button>
      </p>
    </form>

<?php else: ?>
    <!-- STEP4: 完了 -->
    <h1 class="tac">完了</h1>
		<div class="box">
			<p class="tac"><strong>処理が完了しました</strong></p>
	    <p class="tac">合計：<strong><?php echo number_format($total); ?>円(税込)</strong></p>
	    <p class="tac" style="margin-top:24px;"><a class="btn" href="<?php echo u(home_url('/mypage')); ?>">マイページへ</a></p>
	    <p class="tac" style="margin-top:12px;"><a href="<?php echo u(home_url('/')); ?>">トップページへ戻る</a></p>
		</div>
<?php endif; ?>

  </div>
</main>

<?php get_footer(); ?>
