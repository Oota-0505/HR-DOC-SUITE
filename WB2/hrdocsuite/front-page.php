<?php
/**
 * Template Name: Front Page
 * Description: HR DOC SUITE のフロントページテンプレート
 */

get_header();
?>

  <main>
    <section id="hero" class="hero">
      <div class="hero-bg-media">
        <div class="hero-bg-overlay-gradient"></div>
        <div class="hero-bg-text-scrolling">HR DOC SUITE STANDARD</div>
      </div>
      
      <div class="container">
        <div class="hero-inner">
          <div class="hero-glass-panel">
            <div class="hero-content">
              <p class="hero-label hero-reveal-item hero-delay-0">/ DOCUMENT × STANDARD /</p>
              <h1 class="hero-title">
                <span class="t-line hero-reveal-item hero-delay-1">採用と評価の</span><br>
                <span class="t-line hero-reveal-item hero-delay-2">"書類作り"に、</span><br>
                <span class="t-line hero-reveal-item hero-delay-3">共通の型を。</span>
              </h1>
              <p class="hero-lead hero-reveal-item hero-delay-4">
                人が増えるほど、書類も増える。HR DOC SUITE は、中小企業の人事・経営者向けに、採用・評価に必要なドキュメントの型をまとめて提供する月額制のテンプレートサービスです。
              </p>
              <div class="cta-row hero-reveal-item hero-delay-5">
                <a class="btn btn-entry-hero" href="<?php echo esc_url(home_url('/#contact')); ?>">
                  <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                  詳しく見る →
                </a>
              </div>
            </div>
          </div>
          
          <div class="hero-visual-wrap">
            <div class="hero-main-visual">
              <div class="hero-slider">
                <img src="<?php echo get_template_directory_uri(); ?>/images/hero-1.png" alt="ドキュメント作成の様子" class="hero-v-img active">
                <img src="<?php echo get_template_directory_uri(); ?>/images/hero-2.png" alt="社内ミーティングの様子" class="hero-v-img">
              </div>
              <div class="hero-visual-overlay"></div>
            </div>
            <div class="hero-floating-elements">
              <div class="floating-item item-1"></div>
              <div class="floating-item item-2"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="hero-scroll-indicator" aria-hidden="true">
        <span class="scroll-text">
          <span class="scroll-text-inner">SCROLL</span>
        </span>
        <div class="scroll-track">
          <div class="scroll-track-line"></div>
          <div class="scroll-roller"></div>
        </div>
      </div>
    </section>

    <section id="empathy" class="section problem-section">
      <div class="container">
        <div class="problem-header reveal-card">
          <p class="problem-label">/ お悩み /</p>
          <h2 class="problem-title">PROBLEM</h2>
        </div>
        <p class="section-lead problem-lead reveal-card" style="text-align: center; margin-bottom: var(--spacing-xl); max-width: 800px; margin-left: auto; margin-right: auto;">日々の業務に追われ、本来やるべき「人に向き合う時間」が削られていませんか。</p>
        <div class="empathy-grid">
          <!-- Card 1 -->
          <div class="empathy-card reveal-card">
            <span class="card-shimmer" aria-hidden="true"></span>
            <div class="card-ribbon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <div class="card-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <div class="card-content">
              <p class="quote-text">求人票や募集要項を毎回ゼロから作っている</p>
            </div>
            <div class="card-footer-bar">01. 採用業務の非効率 →</div>
          </div>
          
          <!-- Card 2 -->
          <div class="empathy-card reveal-card">
            <span class="card-shimmer" aria-hidden="true"></span>
            <div class="card-ribbon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <div class="card-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
            </div>
            <div class="card-content">
              <p class="quote-text">面接シートがなく、面接官ごとに質問がバラバラ</p>
            </div>
            <div class="card-footer-bar">02. 選考基準の曖昧さ →</div>
          </div>

          <!-- Card 3 -->
          <div class="empathy-card reveal-card">
            <span class="card-shimmer" aria-hidden="true"></span>
            <div class="card-ribbon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <div class="card-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
            </div>
            <div class="card-content">
              <p class="quote-text">評価シートがなく、評価理由の説明が難しい</p>
            </div>
            <div class="card-footer-bar">03. 評価への不満 →</div>
          </div>

          <!-- Card 4 -->
          <div class="empathy-card reveal-card">
            <span class="card-shimmer" aria-hidden="true"></span>
            <div class="card-ribbon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <div class="card-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="card-content">
              <p class="quote-text">1on1の記録が統一されず、フィードバックが属人的</p>
            </div>
            <div class="card-footer-bar">04. マネジメントのバラつき →</div>
          </div>

          <!-- Card 5 -->
          <div class="empathy-card reveal-card">
            <span class="card-shimmer" aria-hidden="true"></span>
            <div class="card-ribbon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <div class="card-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
            </div>
            <div class="card-content">
              <p class="quote-text">コンサルを頼みたいが、予算的に難しい</p>
            </div>
            <div class="card-footer-bar">05. コストの壁 →</div>
          </div>

          <!-- Card 6 -->
          <div class="empathy-card reveal-card">
            <span class="card-shimmer" aria-hidden="true"></span>
            <div class="card-ribbon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <div class="card-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
            </div>
            <div class="card-content">
              <p class="quote-text">フォーマットがバラバラで、引き継ぎがしづらい</p>
            </div>
            <div class="card-footer-bar">06. 属人化・ブラックボックス化 →</div>
          </div>
        </div>
      </div>
    </section>

    <section id="reasons" class="section">
      <div class="container">
        <div class="reason-header">
          <p class="reason-label">/ 選ばれる理由 /</p>
          <h2 class="reason-title">REASON</h2>
        </div>
        
        <div class="reason-wrapper">
          <div class="reason-list">
            <!-- Reason 1 -->
            <div class="reason-item" data-image="reason-img-1">
              <span class="reason-num">01</span>
              <h3>採用と評価に必要な<br>テンプレが一式そろう</h3>
              <p>求人票、ジョブディスクリプション、面接シート、評価シート、1on1記録。「最低限ここまであれば、人事として形になる」というラインをカバーしています。</p>
            </div>
            <!-- Reason 2 -->
            <div class="reason-item" data-image="reason-img-2">
              <span class="reason-num">02</span>
              <h3>すべて"編集前提"の<br>フォーマットで提供</h3>
              <p>Googleドキュメントやスプレッドシート形式で提供するため、社名や評価基準を自社に合わせてそのまま編集可能です。PDFのような「見るだけ」の資料ではありません。</p>
            </div>
            <!-- Reason 3 -->
            <div class="reason-item" data-image="reason-img-3">
              <span class="reason-num">03</span>
              <h3>毎月のアップデートで<br>"古くならない"</h3>
              <p>評価制度や採用手法の変化を踏まえ、毎月新しいフォーマットや改善版を追加。一度作って終わりではなく、常に最新のノウハウを取り入れられます。</p>
            </div>
          </div>
          
          <div class="reason-visual">
            <div class="reason-image reason-img-1"></div>
          </div>
        </div>
      </div>
    </section>

    <section id="voices" class="section voice-section">
      <div class="voice-bg-overlay"></div>
      
      <div class="container voice-container">
        <div class="voice-header">
          <p class="voice-label">/ 利用者の声 /</p>
          <h2 class="voice-title">VOICE</h2>
        </div>
      </div>

      <div class="voice-ticker-wrapper">
        <!-- Row 1: Left to Right -->
        <div class="voice-ticker ticker-ltr">
          <div class="voice-ticker-track">
            <div class="voice-item">
              <span class="voice-meta">製造業・従業員50名</span>
              <p>「評価シートのフォーマットが決まったことで、評価面談が"何となくの印象"から、一歩前に進みました。」</p>
            </div>
            <div class="voice-item">
              <span class="voice-meta">ITベンチャー・従業員20名</span>
              <p>「求人票と面接シートの型が揃っただけで、採用の打合せがスムーズになりました。」</p>
            </div>
            <div class="voice-item">
              <span class="voice-meta">店舗ビジネス・従業員30名</span>
              <p>「1on1の記録テンプレがあるおかげで、店長同士で面談の内容を共有しやすくなりました。」</p>
            </div>
            <div class="voice-item">
              <span class="voice-meta">サービス業・従業員15名</span>
              <p>「導入ガイドがあったおかげで、初めての評価制度導入も迷わずに進めることができました。」</p>
            </div>
            <!-- Duplicate for seamless loop -->
            <div class="voice-item">
              <span class="voice-meta">製造業・従業員50名</span>
              <p>「評価シートのフォーマットが決まったことで、評価面談が"何となくの印象"から、一歩前に進みました。」</p>
            </div>
            <div class="voice-item">
              <span class="voice-meta">ITベンチャー・従業員20名</span>
              <p>「求人票と面接シートの型が揃っただけで、採用の打合せがスムーズになりました。」</p>
            </div>
          </div>
        </div>

        <!-- Row 2: Right to Left -->
        <div class="voice-ticker ticker-rtl">
          <div class="voice-ticker-track">
            <div class="voice-item">
              <span class="voice-meta">物流・従業員100名</span>
              <p>「ジョブディスクリプションの明確化で、ミスマッチのない採用が実現できています。」</p>
            </div>
            <div class="voice-item">
              <span class="voice-meta">Web制作・従業員10名</span>
              <p>「少人数のチームだからこそ、評価の公平性が保てる仕組みが欲しかった。まさに理想的です。」</p>
            </div>
            <div class="voice-item">
              <span class="voice-meta">飲食チェーン・従業員200名</span>
              <p>「各店舗での評価基準がバラバラだった課題が、全社共通のフォーマットで一気に解決しました。」</p>
            </div>
            <div class="voice-item">
              <span class="voice-meta">医療法人・従業員80名</span>
              <p>「専門職の多い現場でも使いやすいシンプルさが魅力。面談の質が向上しました。」</p>
            </div>
            <!-- Duplicate for seamless loop -->
            <div class="voice-item">
              <span class="voice-meta">物流・従業員100名</span>
              <p>「ジョブディスクリプションの明確化で、ミスマッチのない採用が実現できています。」</p>
            </div>
            <div class="voice-item">
              <span class="voice-meta">Web制作・従業員10名</span>
              <p>「少人数のチームだからこそ、評価の公平性が保てる仕組みが欲しかった。まさに理想的です。」</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="services" class="service-section">
      <!-- Custom Cursor for Service Section -->
      <div class="service-cursor" id="serviceCursor">
        <div class="cursor-doc">
          <div class="cursor-lines"></div>
          <span class="cursor-text">DOWNLOAD NOW</span>
        </div>
      </div>

      <div class="service-header-sticky">
        <p class="service-label">/ サービス内容 /</p>
        <h2 class="service-title">SERVICE</h2>
      </div>

      <!-- Strip 1: Recruitment -->
      <div class="service-strip recruit">
        <div class="container">
          <div class="strip-content">
            <span class="strip-num">01</span>
            <h3>採用テンプレート</h3>
            <ul class="service-list">
              <li>求人票／募集要項</li>
              <li>ジョブディスクリプション（職務定義書）</li>
              <li>面接シート（一次／最終面接用）</li>
              <li>内定通知メール文例</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Strip 2: Evaluation -->
      <div class="service-strip evaluate reverse">
        <div class="container">
          <div class="strip-content">
            <span class="strip-num">02</span>
            <h3>評価テンプレート</h3>
            <ul class="service-list">
              <li>目標設定シート</li>
              <li>評価シート（定性・定量の両軸）</li>
              <li>評価フィードバックシート</li>
              <li>評価スケジュール表</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Strip 3: Growth -->
      <div class="service-strip growth">
        <div class="container">
          <div class="strip-content">
            <span class="strip-num">03</span>
            <h3>1on1／面談関連</h3>
            <ul class="service-list">
              <li>1on1記録シート</li>
              <li>キャリア面談シート</li>
              <li>オンボーディングチェックリスト</li>
              <li>面談ガイドライン</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <section id="flow" class="section muted-bg">
      <div class="container">
        <div class="flow-header">
          <p class="flow-label">/ ご利用の流れ /</p>
          <h2 class="flow-title">FLOW</h2>
        </div>
        <div class="flow-wrapper">
          <!-- 左カラム：画像3枚 -->
          <div class="flow-images">
            <div class="flow-image-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/flow-1.png" alt="ステップ1-2の画像">
            </div>
            <div class="flow-image-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/flow-2.png" alt="ステップ3-4の画像">
            </div>
            <div class="flow-image-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/flow-3.png" alt="ステップ5-6の画像">
            </div>
          </div>

          <!-- 中央カラム：数字と線・矢印 -->
          <div class="flow-line">
            <div class="flow-line-inner">
              <div class="flow-step-number" data-step="1"><span class="step-label">STEP</span><span class="step-num">1</span></div>
              <div class="flow-step-number" data-step="2"><span class="step-label">STEP</span><span class="step-num">2</span></div>
              <div class="flow-step-number" data-step="3"><span class="step-label">STEP</span><span class="step-num">3</span></div>
              <div class="flow-step-number" data-step="4"><span class="step-label">STEP</span><span class="step-num">4</span></div>
              <div class="flow-step-number" data-step="5"><span class="step-label">STEP</span><span class="step-num">5</span></div>
              <div class="flow-step-number" data-step="6"><span class="step-label">STEP</span><span class="step-num">6</span></div>
            </div>
            <div class="flow-animated-arrow">
              <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14m0 0l-6-6m6 6l6-6"></path></svg>
            </div>
          </div>

          <!-- 右カラム：ステップカード -->
          <div class="flow-steps">
            <div class="step-card" data-step="1">
              <h3>会員登録</h3>
              <p>メールアドレスとパスワードでアカウント作成。</p>
            </div>
            <div class="step-card" data-step="2">
              <h3>プラン選択</h3>
              <p>ベーシック／スタンダード／プロから選択し、クレジットカードで決済。</p>
            </div>
            <div class="step-card" data-step="3">
              <h3>テンプレートのダウンロード</h3>
              <p>マイページから、必要なフォーマットをダウンロード。</p>
            </div>
            <div class="step-card" data-step="4">
              <h3>自社向けに編集</h3>
              <p>社名や評価基準、職種名などを自社向けに書き換える。</p>
            </div>
            <div class="step-card" data-step="5">
              <h3>社内展開・運用開始</h3>
              <p>評価スケジュールに合わせて実際の運用をスタート。</p>
            </div>
            <div class="step-card" data-step="6">
              <h3>毎月のアップデートを反映</h3>
              <p>新規テンプレや改訂版を確認し、必要に応じて差し替え。</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="faq" class="section faq-section">
      <div class="container faq-container">
        <!-- 左側：固定タイトルエリア -->
        <div class="faq-sidebar">
          <div class="faq-sticky-content">
            <p class="flow-label">/ よくある質問 /</p>
            <h2 class="faq-title-large">FAQ</h2>
          </div>
        </div>

        <!-- 右側：質問リスト -->
        <div class="faq-list">
          <details class="faq-item">
            <summary>
              <span class="faq-q-num">Q1.</span>
              <span class="faq-q-text">契約すると、いつから利用できますか？</span>
              <span class="faq-toggle-icon"></span>
            </summary>
            <div class="faq-content">
              <p>決済完了後すぐに、すべてのテンプレートへアクセスしダウンロードいただけます。</p>
            </div>
          </details>

          <details class="faq-item">
            <summary>
              <span class="faq-q-num">Q2.</span>
              <span class="faq-q-text">ダウンロードしたテンプレートは、自社向けに編集しても良いですか？</span>
              <span class="faq-toggle-icon"></span>
            </summary>
            <div class="faq-content">
              <p>はい。Googleドキュメント／スプレッドシート形式で自由に編集できます。※外部配布・再販は不可です。</p>
            </div>
          </details>

          <details class="faq-item">
            <summary>
              <span class="faq-q-num">Q3.</span>
              <span class="faq-q-text">解約後も、ダウンロード済みのテンプレートを利用できますか？</span>
              <span class="faq-toggle-icon"></span>
            </summary>
            <div class="faq-content">
              <p>解約後も、自社内の運用に限り継続利用可能です。ただし、アップデートの受け取りは停止します。</p>
            </div>
          </details>

          <details class="faq-item">
            <summary>
              <span class="faq-q-num">Q4.</span>
              <span class="faq-q-text">返金はできますか？</span>
              <span class="faq-toggle-icon"></span>
            </summary>
            <div class="faq-content">
              <p>デジタルコンテンツというサービス特性上、返金は受け付けておりません。予めご了承ください。</p>
            </div>
          </details>

          <details class="faq-item">
            <summary>
              <span class="faq-q-num">Q5.</span>
              <span class="faq-q-text">法律や労務に関する相談もできますか？</span>
              <span class="faq-toggle-icon"></span>
            </summary>
            <div class="faq-content">
              <p>本サービスはテンプレート提供を目的としており、個別の法的・労務的アドバイスは行っていません。制度の最終決定や法令適合性については、専門家へのご相談をお願いいたします。</p>
            </div>
          </details>
        </div>
      </div>
    </section>

    <section id="pricing" class="section muted-bg">
      <div class="container">
        <div class="plan-header">
          <p class="plan-label">/ 料金プラン /</p>
          <h2 class="plan-title">PLAN</h2>
        </div>
        <div class="cards grid-3 pricing-cards">
          <!-- ベーシックプラン -->
          <div class="plan-card">
            <div class="plan-basic-section">
              <div class="plan-basic-info">
                <div class="plan-name">ベーシックプラン</div>
                <div class="price">月額 1,980円<span>（税込2,178円）</span></div>
              </div>
              <div class="plan-toggle-icon-wrapper">
                <div class="plan-toggle-icon"></div>
              </div>
            </div>
            <div class="plan-content-section">
              <ul class="plan-features">
                <li>採用・評価テンプレート 10種</li>
                <li>月1回のテンプレ追加／更新</li>
                <li>導入ガイドPDF</li>
              </ul>
            </div>
            <details class="plan-details-toggle">
              <summary class="plan-toggle-summary">
                <span class="sr-only">詳細を表示</span>
              </summary>
              <div class="plan-expanded-content">
                <dl class="plan-details-list">
                  <div class="plan-detail-item">
                    <dt>利用可能テンプレ数</dt>
                    <dd>10種</dd>
                  </div>
                  <div class="plan-detail-item">
                    <dt>テンプレの深さ</dt>
                    <dd>実務レベル</dd>
                  </div>
                  <div class="plan-detail-item">
                    <dt>ガイド文</dt>
                    <dd>軽微</dd>
                  </div>
                  <div class="plan-detail-item">
                    <dt>更新頻度</dt>
                    <dd>月1回</dd>
                  </div>
                  <div class="plan-detail-item">
                    <dt>カスタマイズ相談</dt>
                    <dd>-</dd>
                  </div>
                  <div class="plan-detail-item">
                    <dt>目的</dt>
                    <dd>小規模向け</dd>
                  </div>
                </dl>
              </div>
            </details>
          </div>

          <!-- スタンダードプラン -->
          <div class="plan-card plan-card-featured">
            <div class="plan-basic-section">
              <div class="plan-basic-info">
                <div class="plan-name">スタンダードプラン</div>
                <div class="price">月額 3,980円<span>（税込4,278円）</span></div>
              </div>
              <div class="plan-toggle-icon-wrapper">
                <div class="plan-toggle-icon"></div>
              </div>
            </div>
            <div class="plan-content-section">
              <ul class="plan-features">
                <li>テンプレート 20種</li>
                <li>月2回のテンプレ追加／更新</li>
                <li>カスタマイズ相談（月3質問まで）</li>
                <li>運用チェックリスト／社内説明資料</li>
              </ul>
            </div>
            <details class="plan-details-toggle">
              <summary class="plan-toggle-summary">
                <span class="sr-only">詳細を表示</span>
              </summary>
              <div class="plan-expanded-content">
                <dl class="plan-details-list">
                  <div class="plan-detail-item">
                    <dt>利用可能テンプレ数</dt>
                    <dd>20種</dd>
                  </div>
                  <div class="plan-detail-item">
                    <dt>テンプレの深さ</dt>
                    <dd>実務＋ガイド付き</dd>
                  </div>
                  <div class="plan-detail-item">
                    <dt>ガイド文</dt>
                    <dd>あり</dd>
                  </div>
                  <div class="plan-detail-item">
                    <dt>更新頻度</dt>
                    <dd>月2回</dd>
                  </div>
                  <div class="plan-detail-item">
                    <dt>カスタマイズ相談</dt>
                    <dd>月3質問</dd>
                  </div>
                  <div class="plan-detail-item">
                    <dt>目的</dt>
                    <dd>担当者あり企業</dd>
                  </div>
                </dl>
              </div>
            </details>
          </div>

          <!-- プロプラン -->
          <div class="plan-card">
            <div class="plan-basic-section">
              <div class="plan-basic-info">
                <div class="plan-name">プロプラン</div>
                <div class="price">月額 9,800円<span>（税込10,780円）</span></div>
              </div>
              <div class="plan-toggle-icon-wrapper">
                <div class="plan-toggle-icon"></div>
              </div>
            </div>
            <div class="plan-content-section">
              <ul class="plan-features">
                <li>テンプレート 30種以上</li>
                <li>月4回までのテンプレ追加要望の優先反映</li>
                <li>評価制度との整合コメント（月1回）</li>
                <li>評価スケジュール表／経営層説明資料</li>
              </ul>
            </div>
            <details class="plan-details-toggle">
              <summary class="plan-toggle-summary">
                <span class="sr-only">詳細を表示</span>
              </summary>
              <div class="plan-expanded-content">
                <dl class="plan-details-list">
                  <div class="plan-detail-item">
                    <dt>利用可能テンプレ数</dt>
                    <dd>30種以上</dd>
                  </div>
                  <div class="plan-detail-item">
                    <dt>テンプレの深さ</dt>
                    <dd>実務＋制度整合</dd>
                  </div>
                  <div class="plan-detail-item">
                    <dt>ガイド文</dt>
                    <dd>詳細</dd>
                  </div>
                  <div class="plan-detail-item">
                    <dt>更新頻度</dt>
                    <dd>月4回</dd>
                  </div>
                  <div class="plan-detail-item">
                    <dt>カスタマイズ相談</dt>
                    <dd>月3質問＋コメント</dd>
                  </div>
                  <div class="plan-detail-item">
                    <dt>目的</dt>
                    <dd>制度高度化レベル</dd>
                  </div>
                </dl>
              </div>
            </details>
          </div>
        </div>

        <style>
        /* ============================================
           Pricing Options Styles
           ============================================ */
        .pricing-options {
          margin-top: 80px;
          background: #ffffff;
          padding: 60px 40px;
          border-radius: 24px;
          border: 1px solid var(--gray-line);
          box-shadow: 0 20px 50px rgba(13, 59, 138, 0.05);
          position: relative;
          overflow: hidden;
        }

        .pricing-options::before {
          content: 'OPTIONS';
          position: absolute;
          top: -20px;
          right: -10px;
          font-size: 8rem;
          font-weight: 900;
          color: rgba(37, 99, 235, 0.03);
          letter-spacing: 0.05em;
          pointer-events: none;
          z-index: 0;
        }

        .options-header {
          text-align: center;
          margin-bottom: 40px;
          position: relative;
          z-index: 1;
        }

        .options-label {
          display: inline-block;
          background: rgba(37, 99, 235, 0.1);
          color: var(--blue);
          font-size: 0.8rem;
          font-weight: 700;
          padding: 4px 16px;
          border-radius: 100px;
          margin-bottom: 12px;
          letter-spacing: 0.1em;
          text-transform: uppercase;
        }

        .options-title {
          font-size: 2rem;
          font-weight: 700;
          color: var(--navy);
          margin-bottom: 8px;
        }

        .options-desc {
          font-size: 1rem;
          color: var(--muted);
        }

        .options-grid {
          display: grid;
          grid-template-columns: repeat(2, 1fr);
          gap: 20px;
          position: relative;
          z-index: 1;
        }

        .option-card {
          background: var(--gray-soft);
          padding: 24px 30px;
          border-radius: 16px;
          border: 1px solid transparent;
          transition: all 0.3s ease;
          display: flex;
          align-items: center;
        }

        .option-card:hover {
          background: #ffffff;
          border-color: var(--blue-light);
          box-shadow: 0 10px 30px rgba(37, 99, 235, 0.1);
          transform: translateY(-3px);
        }

        .option-info {
          width: 100%;
          display: flex;
          justify-content: space-between;
          align-items: center;
          gap: 20px;
        }

        .option-name {
          font-size: 1rem;
          font-weight: 600;
          color: var(--navy);
          line-height: 1.4;
        }

        .option-price {
          font-size: 1.4rem;
          font-weight: 800;
          color: var(--blue);
          white-space: nowrap;
          display: flex;
          flex-direction: column;
          align-items: flex-end;
          line-height: 1.2;
        }

        .option-price span {
          font-size: 0.75rem;
          font-weight: 500;
          color: var(--muted);
          margin-top: 2px;
        }

        @media (max-width: 992px) {
          .options-grid {
            grid-template-columns: 1fr;
          }
        }

        @media (max-width: 768px) {
          .pricing-options {
            padding: 40px 20px;
            margin-top: 60px;
          }
          .options-title {
            font-size: 1.6rem;
          }
          .option-card {
            padding: 20px;
          }
          .option-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
          }
          .option-price {
            font-size: 1.25rem;
          }
        }
        </style>

        <div class="pricing-options">
          <div class="options-header">
            <h3 class="options-title">追加オプションメニュー</h3>
            <p class="options-desc">各プランに必要に応じて追加いただけます</p>
          </div>
          <div class="options-grid">
            <div class="option-card">
              <div class="option-info">
                <div class="option-name">テンプレート個別カスタマイズ</div>
                <div class="option-price">10,000円<span>（税込11,000円）</span></div>
              </div>
            </div>
            <div class="option-card">
              <div class="option-info">
                <div class="option-name">評価シートのレビュー・改善コメント</div>
                <div class="option-price">8,000円<span>（税込8,800円）</span></div>
              </div>
            </div>
            <div class="option-card">
              <div class="option-info">
                <div class="option-name">採用フロー全体図の整理PDF</div>
                <div class="option-price">8,000円<span>（税込8,800円）</span></div>
              </div>
            </div>
            <div class="option-card">
              <div class="option-info">
                <div class="option-name">1on1運用ガイドライン作成</div>
                <div class="option-price">12,000円<span>（税込13,200円）</span></div>
              </div>
            </div>
          </div>
        </div>

        <div class="note">
          ※無料プラン・無料テンプレートの提供はありません。<br>
          ※すべてのテンプレートは、プラン契約後に利用できます。
        </div>
      </div>
    </section>

    <section id="contact" class="section contact-section">
      <style>
        /* アイコンとテキストの揃え（競合を上書き） */
        #contact .contact-benefits {
          display: flex !important;
          flex-direction: column !important;
          gap: 24px !important;
        }
        #contact .contact-benefits .benefit-item {
          display: flex !important;
          align-items: flex-start !important;
          gap: 16px !important;
          flex-direction: row !important;
        }
        #contact .contact-benefits .benefit-icon {
          flex-shrink: 0 !important;
          width: 44px !important;
          height: 44px !important;
          min-width: 44px !important;
          display: flex !important;
          align-items: center !important;
          justify-content: center !important;
        }
        #contact .contact-benefits .benefit-content {
          flex: 1 !important;
          min-width: 0 !important;
          padding: 0 !important;
          margin: 0 !important;
        }
        #contact .contact-benefits .benefit-content h4 {
          margin: 0 0 6px 0 !important;
          padding: 0 !important;
          text-align: left !important;
        }
        #contact .contact-benefits .benefit-content p {
          margin: 0 !important;
          padding: 0 !important;
          text-align: left !important;
          text-indent: 0 !important;
        }
      </style>
      <div class="contact-bg-overlay"></div>
      <div class="contact-network-pattern"></div>
      <div class="container">
        <div class="contact-split">
          <div class="contact-info-panel">
            <p class="contact-label">/ お問い合わせ /</p>
            <h2 class="contact-title">CONTACT</h2>
            <p class="contact-lead">採用・評価の"書類作り"に、共通の型を。<br>まずはお気軽にご相談ください。</p>
            <div class="contact-benefits">
              <div class="benefit-item">
                <div class="benefit-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
                <div class="benefit-content">
                  <h4>編集前提のフォーマット</h4>
                  <p>Googleドキュメント形式で提供。社名や評価基準を自社向けにすぐ編集できます。</p>
                </div>
              </div>
              <div class="benefit-item">
                <div class="benefit-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
                <div class="benefit-content">
                  <h4>導入ガイド付き</h4>
                  <p>初めての評価制度導入も迷わず進められる、わかりやすいガイドをご用意しています。</p>
                </div>
              </div>
            </div>
          </div>
          <div class="contact-form-panel">
            <form class="contact-form" action="#" method="POST" id="contactForm">
              <?php wp_nonce_field('hrdoc_contact_action', 'hrdoc_contact_nonce'); ?>
              <input type="hidden" name="hrdoc_contact" value="1">
              <div class="form-row form-row--half">
                <div class="form-group">
                  <label for="contactLastName" class="form-label">姓 <span class="required">*</span></label>
                  <input type="text" id="contactLastName" name="last_name" class="form-input" required>
                </div>
                <div class="form-group">
                  <label for="contactFirstName" class="form-label">名 <span class="required">*</span></label>
                  <input type="text" id="contactFirstName" name="first_name" class="form-input" required>
                </div>
              </div>
              <div class="form-group">
                <label for="contactEmail" class="form-label">メールアドレス <span class="required">*</span></label>
                <input type="email" id="contactEmail" name="email" class="form-input" required>
              </div>
              <div class="form-group">
                <label for="contactCompany" class="form-label">会社名</label>
                <input type="text" id="contactCompany" name="company" class="form-input">
              </div>
              <div class="form-group">
                <label class="form-label">ご検討中のプラン</label>
                <div class="form-radio-group">
                  <label class="form-radio">
                    <input type="radio" name="plan" value="basic">
                    <span class="radio-mark"></span>
                    <span class="radio-label">ベーシック</span>
                  </label>
                  <label class="form-radio">
                    <input type="radio" name="plan" value="standard">
                    <span class="radio-mark"></span>
                    <span class="radio-label">スタンダード</span>
                  </label>
                  <label class="form-radio">
                    <input type="radio" name="plan" value="pro">
                    <span class="radio-mark"></span>
                    <span class="radio-label">プロ</span>
                  </label>
                </div>
              </div>
              <div class="form-group">
                <label for="contactMessage" class="form-label">お問い合わせ内容 <span class="required">*</span></label>
                <textarea id="contactMessage" name="message" class="form-textarea" rows="4" required></textarea>
              </div>
              <div class="form-group form-group--checkbox">
                <label class="form-checkbox">
                  <input type="checkbox" name="privacy" required>
                  <span class="checkbox-mark"></span>
                  <span class="checkbox-label"><a href="<?php echo esc_url(home_url('/privacy')); ?>" target="_blank" rel="noopener">プライバシーポリシー</a>に同意する</span>
                </label>
              </div>
              <div class="form-submit">
                <button type="submit" class="btn btn-entry-hero contact-submit-btn">
                  <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 2L11 13"></path><path d="M22 2L15 22L11 13L2 9L22 2Z"></path></svg>
                  送信する
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="contact-slogan-wrapper">
        <div class="contact-slogan">
          <span class="contact-slogan-text">HR DOC SUITE</span>
          <span class="contact-slogan-text">HR DOC SUITE</span>
          <span class="contact-slogan-text">HR DOC SUITE</span>
        </div>
      </div>
    </section>

    <section id="company" class="section company-section">
      <div class="container">
        <div class="company-header">
          <p class="company-label">/ 会社概要 /</p>
          <h2 class="company-title">COMPANY</h2>
        </div>
        <div class="company-wrapper">
          <!-- 左側：会社情報 -->
          <div class="company-info">
            <dl class="company-list">
              <div class="company-item">
                <dt>事業者名</dt>
                <dd>株式会社 Nearly equal</dd>
              </div>
              <div class="company-item">
                <dt>運営責任者</dt>
                <dd>代表取締役 横田 飛海</dd>
              </div>
              <div class="company-item">
                <dt>所在地</dt>
                <dd>〒107-0061<br>東京都港区北青山一丁目 3 番 1 号<br>アールキューブ青山 3 階</dd>
              </div>
              <div class="company-item">
                <dt>電話番号</dt>
                <dd>
                  050-3529-7622<br>
                  <span class="company-note">※電話によるサポート対応は行っておりません。<br>※お問い合わせは原則としてメールにて承っております。</span>
                </dd>
              </div>
              <div class="company-item">
                <dt>メールアドレス</dt>
                <dd>joy@niajoy.info</dd>
              </div>
            </dl>
           
          </div>
          
          <!-- 右側：地図 -->
          <div class="company-map">
            <iframe src="https://www.google.com/maps?q=%E6%9D%B1%E4%BA%AC%E9%83%BD%E6%B8%AF%E5%8C%BA%E5%8C%97%E9%9D%92%E5%B1%B11-3-1+%E3%82%A2%E3%83%BC%E3%83%AB%E3%82%AD%E3%83%A5%E3%83%BC%E3%83%96%E9%9D%92%E5%B1%B1+3%E9%9A%8E&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
      </div>
    </section>
  </main>

<?php get_footer(); ?>
