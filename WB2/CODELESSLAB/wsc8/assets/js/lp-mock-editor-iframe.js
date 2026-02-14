(() => {
  const css = window.__LP_MOCK_CSS__;
  if (!css || typeof css !== "string") return;

  const STYLE_ID = "lp-mock-css-preview";

  function injectIntoDocument(doc) {
    if (!doc || !doc.head) return false;

    // すでにあればOK
    if (doc.getElementById(STYLE_ID)) return true;

    const style = doc.createElement("style");
    style.id = STYLE_ID;
    style.textContent = css;
    doc.head.appendChild(style);
    return true;
  }

  function tryInjectIntoIframe(iframe) {
    try {
      const doc = iframe.contentDocument;
      if (!doc) return;

      injectIntoDocument(doc);

      // ★重要：ネスト iframe も走査（components-sandbox はここに居る）
      const nested = doc.querySelectorAll("iframe");
      nested.forEach((child) => tryInjectIntoIframe(child));
    } catch (e) {
      // cross-origin 等は無視
    }
  }

  function run() {
    // まず親ドキュメント（admin側）に入れる（念のため）
    injectIntoDocument(document);

    // topレベル iframe から再帰的に注入
    document.querySelectorAll("iframe").forEach((f) => tryInjectIntoIframe(f));
  }

  // 初回
  run();

  // 遅延生成対策：短時間リトライ
  let n = 0;
  const timer = setInterval(() => {
    run();
    if (++n > 80) clearInterval(timer); // 20秒程度
  }, 250);

  // DOM変化でも追従
  new MutationObserver(run).observe(document.documentElement, {
    childList: true,
    subtree: true,
  });
})();
