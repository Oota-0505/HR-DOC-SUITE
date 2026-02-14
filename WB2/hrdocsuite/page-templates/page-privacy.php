<?php
/**
 * Template Name: プライバシーポリシー
 * Description: HR DOC SUITE プライバシーポリシー
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
  .page-content.page-privacy {
    padding: 60px 0 100px;
  }
  .page-content.page-privacy .page-body {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    padding: 40px;
    box-shadow: 0 20px 60px rgba(15, 23, 42, 0.04);
  }
  .page-content.page-privacy h2 {
    font-size: 1.15rem;
    margin-top: 32px;
    margin-bottom: 12px;
    padding-left: 12px;
    border-left: 4px solid #2563eb;
    color: #0f172a;
  }
  .page-content.page-privacy h2:first-of-type {
    margin-top: 0;
  }
  .page-content.page-privacy p {
    color: #475569;
    line-height: 1.8;
    margin-bottom: 12px;
  }
  .page-content.page-privacy ul {
    padding-left: 20px;
    margin: 12px 0 20px;
    color: #475569;
  }
  .page-content.page-privacy ul li {
    margin-bottom: 6px;
    line-height: 1.7;
  }
  @media (max-width: 768px) {
    .legal-hero {
      padding: 40px 0;
    }
    .page-content.page-privacy .page-body {
      padding: 28px 20px;
    }
  }
</style>

<section class="legal-hero">
  <div class="container">
    <p class="legal-label">HR DOC SUITE</p>
    <h1 class="legal-heading">プライバシーポリシー</h1>
    <p>個人情報の取り扱い方針についてご案内します。</p>
  </div>
</section>

<main class="site-main">
  <div class="container">
    <article class="page-content page-privacy">
      <div class="page-body entry-content">
        <p>株式会社 Nearly equal（以下「当社」）は、本サービスの提供にあたり取得する個人情報について、以下のとおりプライバシーポリシーを定め、適切な管理・保護に努めます。</p>

        <h2>1. 取得する個人情報</h2>
        <p>当社は、以下の情報を取得する場合があります。</p>
        <ul>
          <li>氏名、会社名、役職</li>
          <li>メールアドレス</li>
          <li>決済に関する情報</li>
          <li>サービス利用履歴、ダウンロード履歴</li>
          <li>問い合わせ内容、連絡履歴</li>
          <li>その他、サービス提供に必要な情報</li>
        </ul>

        <h2>2. 個人情報の利用目的</h2>
        <p>取得した個人情報は、以下の目的で利用します。</p>
        <ul>
          <li>本サービスの提供および運営管理</li>
          <li>利用者からの問い合わせ対応</li>
          <li>契約・決済管理</li>
          <li>サービス内容の改善および品質向上</li>
          <li>新サービス・重要事項等の案内（メールによる告知を含む）</li>
        </ul>
        <p>※広告配信やリマーケティング目的での利用は行いません。</p>

        <h2>3. 第三者提供について</h2>
        <p>当社は、法令に基づく場合を除き、利用者の同意なく第三者に個人情報を提供することはありません。</p>

        <h2>4. 業務委託について</h2>
        <p>サービス運営上、業務の一部を外部に委託する場合があります。その場合、当社は委託先に対して適切な個人情報保護義務を課し、合理的な監督を行います。</p>

        <h2>5. 安全管理措置</h2>
        <p>当社は、個人情報への不正アクセス、漏えい、改ざん、紛失等を防止するため、以下の安全管理措置を講じます。</p>
        <ul>
          <li>アクセス制限による管理</li>
          <li>管理責任者の設置</li>
          <li>合理的かつ適切な情報管理体制の構築</li>
        </ul>

        <h2>6. 個人情報の開示・訂正・削除</h2>
        <p>利用者本人から、自己の個人情報の開示・訂正・削除等の請求があった場合、法令に基づき、適切に対応します。</p>

        <h2>7. プライバシーポリシーの変更</h2>
        <p>本ポリシーの内容は、法令変更やサービス内容変更等に応じて改定する場合があります。変更後の内容は、本サイトに掲載した時点で効力を生じます。</p>
      </div>
    </article>
  </div>
</main>

<?php get_footer(); ?>
