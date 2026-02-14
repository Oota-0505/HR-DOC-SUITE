<?php
/**
 * Template Name: 特定商取引法に基づく表記
 * PERSONAL EDITOR 特定商取引法に基づく表記
 *
 * @package PERSONAL_EDITOR
 * @since 1.0.0
 */

get_header();
?>
<section class="legal-hero">
  <div class="l-inner">
    <p class="legal-label">PERSONAL EDITOR</p>
    <h1 class="legal-heading">特定商取引法に基づく表記</h1>
    <p>事業者情報や提供条件など、特定商取引法に基づく事項を掲載しています。</p>
  </div>
</section>

<main class="site-main">
  <div class="l-inner">
    <article class="page-content page-tokushoho">
      <div class="page-body entry-content legal-content">
        <h2>事業者情報</h2>
        <table class="tokushoho-table">
          <tbody>
            <tr><th>事業者名</th><td>有限会社フリープラン</td></tr>
            <tr><th>代表者名</th><td>玉置 真也</td></tr>
            <tr><th>運営責任者</th><td>代表取締役 玉置 真也</td></tr>
            <tr><th>所在地</th><td>〒150-0012<br>東京都渋谷区広尾一丁目2番地1号<br>ヒカリビル4階</td></tr>
            <tr><th>電話番号</th><td>050-3529-7324<br><small>※電話によるサポート対応は行っておりません。<br>※お問い合わせは原則としてメールにて承っております。</small></td></tr>
            <tr><th>メールアドレス</th><td>info@freeplancompany.com</td></tr>
          </tbody>
        </table>

        <h2>事業内容</h2>
        <p>企業の公式noteを中心とした情報発信を対象に、文章構成の整理、編集および運用に関する一般的助言を行う法人向けコンサルティング型サブスクリプションサービスの提供。</p>

        <h2>サービス内容および販売価格</h2>
        <p>本サービス「NOTE PARTNER」は、企業の公式note運用を対象に、構成テンプレートの提供、編集観点からのフィードバック、および一般的な広報・情報発信に関する助言を行う役務提供型・月額制サブスクリプションサービスです。</p>
        <p><small>ベーシック：2,178円（税込）／スタンダード：3,850円（税込）／プロ：8,580円（税込）</small></p>

        <h2>支払い方法</h2>
        <p>クレジットカード決済のみ。</p>

        <h2>支払い時期</h2>
        <ul>
          <li>初回：申込み完了時</li>
          <li>継続課金：初回決済日と同日に毎月自動課金</li>
          <li>オプション：申込み時に都度決済</li>
        </ul>

        <h2>商品代金以外の必要料金</h2>
        <p>消費税、インターネット通信費（利用者負担）</p>

        <h2>サービス提供時期および方法</h2>
        <p>決済確認後、順次サービス提供を開始します。メール、マイページ、オンライン会議ツール等を通じて提供します。</p>

        <h2>返品・返金・キャンセル</h2>
        <p>本サービスは役務提供およびデジタルコンテンツの性質上、利用者都合による返品・返金・キャンセルは一切お受けできません。当社の責に帰すべき事由によりサービス提供が不可能となった場合に限り、未提供分について返金または代替対応を行います。</p>

        <h2>解約</h2>
        <ul>
          <li>解約は次回決済日の5日前までに所定の方法で行うものとします。</li>
          <li>解約後も当月末までは利用可能です。</li>
          <li>日割り返金は行いません。</li>
        </ul>

        <h2>準拠法および管轄裁判所</h2>
        <p>日本法／東京地方裁判所</p>
      </div>
    </article>
  </div>
</main>

<style>
.legal-hero{background:#f8fafc;padding:48px 0;border-bottom:1px solid #e5e7eb}
.legal-hero .legal-label{font-size:.875rem;letter-spacing:.1em;color:#64748b;text-transform:uppercase;margin-bottom:8px}
.legal-hero .legal-heading{font-size:clamp(2rem,4vw,2.5rem);font-weight:700;color:#0f172a;margin:0}
.legal-hero p{margin-top:12px;color:#475569;font-size:.95rem;line-height:1.7}
.page-tokushoho .page-body{background:#fff;border-radius:16px;border:1px solid #e2e8f0;padding:40px;box-shadow:0 20px 60px rgba(15,23,42,.04);margin:60px 0 100px}
.page-tokushoho h2{font-size:1.15rem;margin-top:32px;margin-bottom:12px;padding-left:12px;border-left:4px solid #2563eb;color:#0f172a}
.page-tokushoho h2:first-of-type{margin-top:0}
.page-tokushoho p,.page-tokushoho ul{color:#475569;line-height:1.8;margin-bottom:12px}
.page-tokushoho ul{padding-left:20px;margin:12px 0 20px}
.tokushoho-table{width:100%;border-collapse:collapse;margin-bottom:32px}
.tokushoho-table th,.tokushoho-table td{border-bottom:1px solid #e2e8f0;padding:16px 12px;vertical-align:top;text-align:left;color:#475569}
.tokushoho-table th{width:160px;font-weight:600;color:#0f172a;background:#f8fafc}
.tokushoho-table tr:first-child th,.tokushoho-table tr:first-child td{border-top:1px solid #e2e8f0}
@media(max-width:768px){.tokushoho-table th,.tokushoho-table td{display:block;width:100%}.tokushoho-table th{border-bottom:none;background:transparent;padding-bottom:4px}}
</style>

<?php get_footer(); ?>
