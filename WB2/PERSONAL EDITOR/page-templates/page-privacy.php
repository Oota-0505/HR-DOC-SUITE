<?php
/**
 * Template Name: プライバシーポリシー
 * PERSONAL EDITOR プライバシーポリシー
 *
 * @package PERSONAL_EDITOR
 * @since 1.0.0
 */

get_header();
?>
<section class="legal-hero">
  <div class="l-inner">
    <p class="legal-label">PERSONAL EDITOR</p>
    <h1 class="legal-heading">プライバシーポリシー</h1>
    <p>個人情報の取り扱い方針についてご案内します。</p>
  </div>
</section>

<main class="site-main">
  <div class="l-inner">
    <article class="page-content page-privacy">
      <div class="page-body entry-content legal-content">
        <p>有限会社フリープラン（以下「当社」）は、本サービスの提供にあたり取得する個人情報について、以下のとおりプライバシーポリシーを定め、適切な管理・保護に努めます。</p>

        <h2>1. 取得する情報の内容</h2>
        <p>当社は、本サービスの提供および運営に必要な範囲で、以下の情報を取得します。</p>
        <ul>
          <li>決済に関する情報（クレジットカード番号等は決済代行会社が管理し、当社は保持しません）</li>
          <li>氏名、会社名、部署名、役職</li>
          <li>メールアドレス、その他連絡先情報</li>
          <li>本サービスに関連して提出された文章データ、構成案、資料</li>
          <li>添削内容、フィードバック内容、編集履歴</li>
          <li>利用履歴、アクセス情報（ログ情報）</li>
        </ul>

        <h2>2. 個人情報の利用目的</h2>
        <p>当社は、取得した情報を以下の目的の範囲内で利用します。</p>
        <ol>
          <li>本サービスの提供、運営、管理のため</li>
          <li>記事構成の提案、添削、編集助言等の実施のため</li>
          <li>利用者からの問い合わせ、連絡、サポート対応のため</li>
          <li>決済処理、契約管理、請求管理のため</li>
          <li>サービス品質の維持・向上および改善のため</li>
          <li>不正利用防止、利用状況の確認、安全管理のため</li>
        </ol>

        <h2>3. 第三者提供について</h2>
        <p>当社は、以下の場合を除き、利用者の個人情報を第三者に提供することはありません。</p>
        <ul>
          <li>利用者本人の同意がある場合</li>
          <li>法令に基づく場合</li>
          <li>決済処理、システム運用等において業務委託先に提供する場合</li>
        </ul>

        <h2>4. 個人情報の管理および安全対策</h2>
        <p>当社は、個人情報の漏洩、滅失、改ざん、不正アクセス等を防止するため、合理的かつ適切な安全管理措置を講じます。</p>

        <h2>5. 開示・訂正・削除等の請求</h2>
        <p>利用者本人から、自己の個人情報の開示、訂正、追加、削除、利用停止等の請求があった場合、本人確認を行ったうえで、法令に従い適切に対応します。</p>

        <h2>6. プライバシーポリシーの変更</h2>
        <p>当社は、法令改正、サービス内容の変更等に応じて、本ポリシーの内容を予告なく変更することがあります。変更後の内容は、当社ウェブサイトに掲載した時点で効力を生じます。</p>
      </div>
    </article>
  </div>
</main>

<style>
.legal-hero{background:#f8fafc;padding:48px 0;border-bottom:1px solid #e5e7eb}
.legal-hero .legal-label{font-size:.875rem;letter-spacing:.1em;color:#64748b;text-transform:uppercase;margin-bottom:8px}
.legal-hero .legal-heading{font-size:clamp(2rem,4vw,2.5rem);font-weight:700;color:#0f172a;margin:0}
.legal-hero p{margin-top:12px;color:#475569;font-size:.95rem;line-height:1.7}
.page-privacy .page-body{background:#fff;border-radius:16px;border:1px solid #e2e8f0;padding:40px;box-shadow:0 20px 60px rgba(15,23,42,.04);margin:60px 0 100px}
.page-privacy h2{font-size:1.15rem;margin-top:32px;margin-bottom:12px;padding-left:12px;border-left:4px solid #2563eb;color:#0f172a}
.page-privacy h2:first-of-type{margin-top:0}
.page-privacy p,.page-privacy ul,.page-privacy ol{color:#475569;line-height:1.8;margin-bottom:12px}
.page-privacy ul,.page-privacy ol{padding-left:20px;margin:12px 0 20px}
.page-privacy ul li,.page-privacy ol li{margin-bottom:6px;line-height:1.7}
</style>

<?php get_footer(); ?>
