const header = document.getElementById('siteHeader');
const floatingBar = document.getElementById('floatingBar');
const hero = document.getElementById('hero');

window.addEventListener('scroll', () => {
  const scrollPos = window.scrollY;
  const heroHeight = hero.offsetHeight - 80;

  if (scrollPos > heroHeight) {
    header.classList.add('scrolled');
  } else {
    header.classList.remove('scrolled');
  }

  // HERO & PROBLEM 究極のパララックス
  const heroInner = document.querySelector('.hero-inner');
  const heroBgText = document.querySelector('.hero-bg-text-scrolling');
  const heroMainVisual = document.querySelector('.hero-main-visual');
  const glassPanel = document.querySelector('.hero-glass-panel');
  
  if (heroInner) {
    const heroSpeed = scrollPos * 0.4;
    heroInner.style.transform = `translateY(${heroSpeed}px)`;
    heroInner.style.opacity = 1 - scrollPos / 800;
  }
  
  if (heroBgText) {
    heroBgText.style.transform = `translate(-50%, calc(-50% + ${scrollPos * 0.15}px))`;
  }
  
  if (heroMainVisual) {
    heroMainVisual.style.transform = `perspective(1000px) rotateY(-10deg) rotateX(5deg) translateY(${scrollPos * -0.1}px)`;
  }

  if (glassPanel) {
    glassPanel.style.transform = `translateY(${scrollPos * -0.05}px)`;
  }

  // PROBLEM 背景パララックス強化
  const problemSection = document.getElementById('empathy');
  if (problemSection) {
    const rect = problemSection.getBoundingClientRect();
    const sectionScroll = window.innerHeight - rect.top;
    if (sectionScroll > 0) {
      problemSection.style.backgroundPositionY = `${-sectionScroll * 0.2}px`;
    }
  }
});

// HERO スライダー
const heroImages = document.querySelectorAll('.hero-v-img');
if (heroImages.length > 0) {
  let currentImg = 0;
  setInterval(() => {
    heroImages[currentImg].classList.remove('active');
    currentImg = (currentImg + 1) % heroImages.length;
    heroImages[currentImg].classList.add('active');
  }, 5000);
}

// SERVICEセクション カスタムカーソル
const serviceSection = document.querySelector('.service-section');
const serviceCursor = document.getElementById('serviceCursor');

if (serviceSection && serviceCursor) {
  let mouseX = 0;
  let mouseY = 0;
  let cursorX = 0;
  let cursorY = 0;
  let isActive = false;
  let isMoving = false;

  // カーソル位置の更新
  const updateMousePosition = (e) => {
    mouseX = e.clientX;
    mouseY = e.clientY;
    
    // 初回移動時にカーソルがジャンプするのを防ぐ
    if (!isMoving) {
      cursorX = mouseX;
      cursorY = mouseY;
      isMoving = true;
    }
  };

  serviceSection.addEventListener('mousemove', updateMousePosition, { passive: true });

  serviceSection.addEventListener('mouseenter', (e) => {
    isActive = true;
    updateMousePosition(e);
    serviceCursor.classList.add('active');
    serviceSection.style.cursor = 'pointer'; // カーソルをpointerに変更
  });

  serviceSection.addEventListener('mouseleave', () => {
    isActive = false;
    serviceCursor.classList.remove('active');
    isMoving = false;
    serviceSection.style.cursor = ''; // カーソルを戻す
  });

  // サービスセクションクリック時のリンク機能
  serviceSection.addEventListener('click', (e) => {
    if (isActive) {
      window.location.href = '#cta';
    }
  });

  function animateCursor() {
    if (isActive || isMoving) {
      // スムーズかつ高速な追従 (Lerp) - 0.28に引き上げ
      const lerpFactor = 0.28;
      cursorX += (mouseX - cursorX) * lerpFactor;
      cursorY += (mouseY - cursorY) * lerpFactor;
      
      // スクロール位置による表示制御（FLOWセクション接近時に早めに消す）
      const rect = serviceSection.getBoundingClientRect();
      const hideThreshold = 120; // セクション下端から120px手前で消し始める
      
      if (rect.bottom < hideThreshold) {
        serviceCursor.classList.remove('active');
      } else if (isActive) {
        serviceCursor.classList.add('active');
      }

      // 標準カーソルの右下に配置されるようにオフセット(25px)を追加
      serviceCursor.style.transform = `translate3d(${cursorX + 25}px, ${cursorY + 25}px, 0) translate(-50%, -50%)`;
    }
    
    requestAnimationFrame(animateCursor);
  }
  
  animateCursor();
}

const reasonImageArea = document.querySelector('.reason-image');
const reasonItems = document.querySelectorAll('.reason-item');

if (reasonImageArea && reasonItems.length > 0) {
  const observerOptions = {
    root: null,
    rootMargin: '-40% 0px -40% 0px',
    threshold: 0
  };

  const reasonCallback = (entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const imgClass = entry.target.getAttribute('data-image');
        reasonImageArea.classList.remove('reason-img-1', 'reason-img-2', 'reason-img-3');
        reasonImageArea.classList.add(imgClass);
      }
    });
  };

  const reasonObserver = new IntersectionObserver(reasonCallback, observerOptions);
  reasonItems.forEach(item => reasonObserver.observe(item));
}

const flowSection = document.getElementById('flow');
const flowLine = document.querySelector('.flow-line');
const flowAnimatedArrow = document.querySelector('.flow-animated-arrow');
const flowStepNumbers = document.querySelectorAll('.flow-line-inner .flow-step-number');
const flowStepCards = document.querySelectorAll('.flow-steps .step-card');

if (flowSection && flowLine && flowAnimatedArrow) {
  let isInViewport = false;
  let rafId = null;
  
  function alignStepNumbers() {
    if (window.innerWidth < 768) return;
    
    if (flowStepCards.length > 0 && flowStepNumbers.length > 0) {
      const flowSteps = document.querySelector('.flow-steps');
      const flowLineInner = document.querySelector('.flow-line-inner');
      
      if (flowSteps && flowLineInner) {
        const flowLineInnerRect = flowLineInner.getBoundingClientRect();
        const flowLineRect = flowLine.getBoundingClientRect();
        const flowLineInnerTop = flowLineInnerRect.top - flowLineRect.top;
        
        flowStepCards.forEach((card, index) => {
          if (flowStepNumbers[index]) {
            const cardRect = card.getBoundingClientRect();
            const cardTop = cardRect.top - flowLineRect.top;
            // カードの中心ではなく、タイトルの位置（上部）に合わせて配置
            // padding-top(28px) + title heightの半分程度 = 約50px
            const targetPos = cardTop + 50; 
            const numberTop = targetPos - flowLineInnerTop;
            flowStepNumbers[index].style.top = `${numberTop}px`;
          }
        });
      }
    }
  }
  
  function updateFlowArrow() {
    if (rafId) {
      cancelAnimationFrame(rafId);
    }
    
    rafId = requestAnimationFrame(() => {
      if (window.innerWidth < 768) {
        rafId = null;
        return;
      }
      const rect = flowSection.getBoundingClientRect();
      const windowHeight = window.innerHeight;
      const sectionTop = rect.top;
      const sectionHeight = rect.height;
      
      if (sectionTop < windowHeight && sectionTop + sectionHeight > 0) {
        isInViewport = true;
        flowLine.classList.add('animate-line');
        
        const viewportCenter = windowHeight * 0.5;
        const sectionStart = rect.top;
        const sectionEnd = rect.top + rect.height;
        let scrollProgress = 0;
        if (viewportCenter >= sectionStart && viewportCenter <= sectionEnd) {
          scrollProgress = (viewportCenter - sectionStart) / (sectionEnd - sectionStart);
        } else if (viewportCenter < sectionStart) {
          scrollProgress = 0;
        } else {
          scrollProgress = 1;
        }

        
        scrollProgress = Math.max(0, Math.min(1, scrollProgress));
        
        const lineInner = document.querySelector('.flow-line-inner');
        if (lineInner) {
          const lineHeight = lineInner.offsetHeight;
          const arrowSize = 56;
          const maxTop = lineHeight - arrowSize - 20;
          const arrowTop = scrollProgress * maxTop;
          flowAnimatedArrow.style.transform = `translateX(-50%) translateY(${arrowTop}px)`;
          flowAnimatedArrow.classList.add('active');
        }
        
        if (flowStepNumbers.length > 0) {
          const totalSteps = flowStepNumbers.length;
          flowStepNumbers.forEach((step, index) => {
            if (index === 0) {
              step.classList.add('visible');
            } else {
              const stepThreshold = (index - 0.3) / totalSteps;
              if (scrollProgress >= stepThreshold) {
                step.classList.add('visible');
              } else {
                step.classList.remove('visible');
              }
            }
          });
        }
      } else {
        if (isInViewport) {
          flowAnimatedArrow.classList.remove('active');
          flowLine.classList.remove('animate-line');
          isInViewport = false;
        }
      }
      
      rafId = null;
    });
  }
  
  window.addEventListener('scroll', updateFlowArrow, { passive: true });
  window.addEventListener('resize', () => {
    alignStepNumbers();
    updateFlowArrow();
  });
  
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      setTimeout(() => {
        alignStepNumbers();
        updateFlowArrow();
      }, 100);
    });
  } else {
    setTimeout(() => {
      alignStepNumbers();
      updateFlowArrow();
    }, 100);
  }
}

const planCards = document.querySelectorAll('.plan-card');
const planDetails = document.querySelectorAll('.plan-details-toggle');
const planToggleIcons = document.querySelectorAll('.plan-toggle-icon-wrapper');

if (planCards.length > 0 && planDetails.length > 0) {
  let isExpanded = false;
  
  function toggleAllPlans() {
    isExpanded = !isExpanded;
    
    planDetails.forEach((details, index) => {
      const planCard = planCards[index];
      
      if (isExpanded) {
        details.setAttribute('open', '');
        planCard.classList.add('plan-expanded');
      } else {
        details.removeAttribute('open');
        planCard.classList.remove('plan-expanded');
      }
    });
  }
  
  planToggleIcons.forEach((icon) => {
    icon.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      toggleAllPlans();
    });
  });
}

// ハンバーガーメニュー制御
const menuToggle = document.querySelector('.menu-toggle');
const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
const body = document.body;

if (menuToggle) {
  menuToggle.addEventListener('click', () => {
    body.classList.toggle('menu-open');
  });
}

// リンククリック時にメニューを閉じる
mobileNavLinks.forEach(link => {
  link.addEventListener('click', () => {
    body.classList.remove('menu-open');
  });
});
