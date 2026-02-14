<?php
/**
 * Template Name: 特定商取引法に基づく表記
 * Description: HR DOC SUITE 特定商取引法に基づく表記
 *
 * @package HR_DOC_SUITE
 * @since 1.0.0
 */

get_header();
?>

<style>
  .legal-hero {
    background: #f8fafc;
    padding: 48px 0;
    border-bottom: 1px solid #e5e7eb;
  }
  .legal-hero .legal-label {
    font-size: 0.875rem;
    letter-spacing: 0.1em;
    color: #64748b;
    text-transform: uppercase;
    margin-bottom: 8px;
  }
  .legal-hero .legal-heading {
    font-size: clamp(2rem, 4vw, 2.5rem);
    font-weight: 700;
    color: #0f172a;
    margin: 0;
  }
  .legal-hero p {
    margin-top: 12px;
    color: #475569;
    font-size: 0.95rem;
    line-height: 1.7;
  }
  .page-content.page-tokushoho {
    padding: 60px 0 100px;
  }
  .page-content.page-tokushoho .page-body {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    padding: 40px;
    box-shadow: 0 20px 60px rgba(15, 23, 42, 0.04);
  }
  .page-content.page-tokushoho h2 {
    font-size: 1.15rem;
    margin-top: 32px;
    margin-bottom: 12px;
    padding-left: 12px;
    border-left: 4px solid #2563eb;
    color: #0f172a;
  }
  .page-content.page-tokushoho h2:first-of-type {
    margin-top: 0;
  }
  .page-content.page-tokushoho p {
    color: #475569;
    line-height: 1.8;
    margin-bottom: 12px;
  }
  .page-content.page-tokushoho ul {
    padding-left: 20px;
    margin: 12px 0 20px;
    color: #475569;
  }
  .page-content.page-tokushoho ul li {
    margin-bottom: 6px;
    line-height: 1.7;
  }
  .tokushoho-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 32px;
  }
  .tokushoho-table th,
  .tokushoho-table td {
    border-bottom: 1px solid #e2e8f0;
    padding: 16px 12px;
    vertical-align: top;
    text-align: left;
    color: #475569;
  }
  .tokushoho-table th {
    width: 160px;
    font-weight: 600;
    color: #0f172a;
    background: #f8fafc;
  }
  .tokushoho-table tr:first-child th,
  .tokushoho-table tr:first-child td {
    border-top: 1px solid #e2e8f0;
  }
  @media (max-width: 768px) {
    .legal-hero {
      padding: 40px 0;
    }
    .page-content.page-tokushoho .page-body {
      padding: 28px 20px;
    }
    .tokushoho-table th,
    .tokushoho-table td {
      display: block;
      width: 100%;
    }
    .tokushoho-table th {
      border-bottom: none;
      background: transparent;
      padding-bottom: 4px;
    }
    .tokushoho-table td {
      padding-top: 0;
    }
  }
</style>

<section class="legal-hero">
  <div class="container">
    <p class="legal-label">HR DOC SUITE</p>
    <h1 class="legal-heading">特定商取引法に基づく表記</h1>
    <p>事業者情報や提供条件など、特定商取引法に基づく事項を掲載しています。</p>
  </div>
</section>

<main class="site-main">
  <div class="container">
    <article class="page-content page-tokushoho">
      <div class="page-body entry-content">
        <h2>事業者情報</h2>
        <table class="tokushoho-table">
          <tbody>
            <tr>
              <th>事業者名</th>
              <td>株式会社 Nearly equal</td>
            </tr>
            <tr>
              <th>運営責任者</th>
              <td>代表取締役 横田 飛海</td>
            </tr>
            <tr>
              <th>所在地</th>
              <td>〒107-0061<br>東京都港区北青山一丁目 3 番 1 号<br>アールキューブ青山 3 階</td>
            </tr>
            <tr>
              <th>電話番号</th>
              <td>050-3529-7622<br>※電話によるサポート対応は行っておりません。<br>※お問い合わせは原則としてメールにて承っております。</td>
            </tr>
            <tr>
              <th>メールアドレス</th>
              <td>joy@niajoy.info</td>
            </tr>
          </tbody>
        </table>

        <h2>販売価格</h2>
        <p>各サービス・プランページに記載の金額（税込）とします。</p>

        <h2>商品代金以外の必要料金</h2>
        <ul>
          <li>消費税</li>
          <li>インターネット接続に伴う通信費・データ通信料（※通信費は利用者の自己負担となります）</li>
        </ul>

        <h2>支払方法</h2>
        <ul>
          <li>クレジットカード決済のみ対応しております。</li>
        </ul>

        <h2>支払時期</h2>
        <ul>
          <li>初回決済：申込み完了時</li>
          <li>継続課金：初回決済日を基準日として、以後毎月自動課金</li>
          <li>オプションサービス：申込み時に都度決済</li>
        </ul>

        <h2>サービス提供時期</h2>
        <p>決済完了後、速やかにサービス提供を開始します。（デジタルコンテンツの性質上、原則即時利用可能）</p>

        <h2>サービス提供方法</h2>
        <ul>
          <li>会員専用ページからのデータダウンロード</li>
          <li>メールによる資料送付</li>
          <li>事前に日程を調整したうえでのオンライン面談・チャット対応</li>
        </ul>
        <p>※サポート対応は原則メール対応とし、対応時間は 10:00〜19:00（平日） とします。営業時間外のお問い合わせについては、翌営業日以降に対応します。緊急対応は行っておりません。</p>

        <h2>返品・キャンセル・返金について</h2>
        <p>本サービスはデジタルコンテンツおよび役務提供を主とするサービスのため、利用者都合による返金・キャンセルには応じられません。</p>
        <p>ただし、当社の責に帰すべき事由によりサービス提供が不可能となった場合に限り、未提供期間分について返金または代替対応を行う場合があります。</p>

        <h2>解約について</h2>
        <ul>
          <li>解約は 次回決済日の 7 日前まで に、当社指定の方法により行うものとします。</li>
          <li>解約手続き完了後も、当該課金期間の終了日までは利用可能とします。</li>
          <li>日割り計算による返金は行いません。</li>
        </ul>
      </div>
    </article>
  </div>
</main>

<?php get_footer(); ?>
