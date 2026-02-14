/**
 * MEAL PREP SYSTEM — LP
 */

document.addEventListener('DOMContentLoaded', () => {
  const header = document.getElementById('siteHeader');
  const hero = document.getElementById('hero');
  const heroBgTint = hero ? hero.querySelector('.hero-bg-tint') : null;
  const heroImagesPc = hero ? hero.querySelector('.hero-images.pc-only') : null;
  const heroScrollWrap = hero ? hero.querySelector('.hero-scroll-wrap') : null;

  function onScroll() {
    const scrollY = window.scrollY;

    if (header && hero) {
      header.classList.toggle('scrolled', scrollY > hero.offsetHeight - 100);
    }

    // HERO パララックス：背景・画像・SCROLLがスクロールより遅れて動く
    if (hero && (heroBgTint || heroImagesPc || heroScrollWrap)) {
      const heroH = hero.offsetHeight;
      const maxParallax = heroH * 0.4;
      const parallaxY = Math.min(scrollY * 0.35, maxParallax);
      if (heroBgTint) heroBgTint.style.transform = `translateY(${parallaxY}px)`;
      if (heroImagesPc) heroImagesPc.style.transform = `translateY(${parallaxY * 0.6}px)`;
      if (heroScrollWrap) heroScrollWrap.style.transform = `translateX(-50%) translateY(${parallaxY * 0.5}px)`;
    }

    // PROBLEM パララックス：画像がスクロールより遅れて動く（全デバイス共通）
    const problemSection = document.getElementById('problem');
    const problemImage = problemSection?.querySelector('.problem-image');
    if (problemSection && problemImage) {
      const rect = problemSection.getBoundingClientRect();
      const viewportH = window.innerHeight;
      if (rect.top < viewportH && rect.bottom > 0) {
        const parallaxY = rect.top * 0.15;
        problemImage.style.transform = `translateY(${parallaxY}px)`;
      } else {
        problemImage.style.transform = '';
      }
    }
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  const menuToggle = document.querySelector('.menu-toggle');
  const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
  if (menuToggle) {
    menuToggle.addEventListener('click', () => document.body.classList.toggle('menu-open'));
  }
  mobileNavLinks.forEach(link => {
    link.addEventListener('click', () => document.body.classList.remove('menu-open'));
  });

  document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener('click', (e) => {
      const href = link.getAttribute('href');
      if (href === '#') return;
      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        const y = target.getBoundingClientRect().top + window.scrollY - (header ? header.offsetHeight : 0);
        window.scrollTo({ top: y, behavior: 'smooth' });
        document.body.classList.remove('menu-open');
      }
    });
  });

  // Contact form
  const contactForm = document.querySelector('.contact-form');
  if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const formData = new FormData(contactForm);
      const data = Object.fromEntries(formData.entries());
      const requiredFields = ['lastName', 'firstName', 'email', 'message'];
      let isValid = true;

      requiredFields.forEach(field => {
        const input = contactForm.querySelector(`[name="${field}"]`);
        if (input) {
          if (!data[field] || String(data[field]).trim() === '') {
            isValid = false;
            input.classList.add('error');
          } else {
            input.classList.remove('error');
          }
        }
      });

      const privacyCheckbox = contactForm.querySelector('[name="privacy"]');
      if (privacyCheckbox) {
        const checkboxWrap = privacyCheckbox.closest('.form-checkbox');
        if (!privacyCheckbox.checked) {
          isValid = false;
          if (checkboxWrap) checkboxWrap.classList.add('error');
        } else {
          if (checkboxWrap) checkboxWrap.classList.remove('error');
        }
      }

      if (isValid) {
        console.log('Form data:', data);
        alert('お問い合わせを送信しました。\n担当者より折り返しご連絡いたします。');
        contactForm.reset();
      } else {
        alert('必須項目を入力してください。');
      }
    });

    contactForm.querySelectorAll('.form-input, .form-textarea').forEach(input => {
      input.addEventListener('input', () => input.classList.remove('error'));
    });
  }

  // サービスセクション：ステップ式アニメーション（3つ切り替え）
  (function initServiceSteps() {
    const steps = document.querySelectorAll('.service-step');
    const images = document.querySelectorAll('.service-image');
    const container = document.querySelector('.service-steps-container');
    const serviceSection = document.querySelector('#service');

    if (!steps.length || !images.length || steps.length !== images.length) return;

    const INTERVAL_MS = 3500;
    const RESTART_DELAY_MS = 3000;
    const HOVER_RESTART_DELAY_MS = 1000;

    let currentIndex = 0;
    let autoPlayTimerId = null;
    let restartTimerId = null;
    let isStarted = false;

    function setActive(index) {
      if (index < 0 || index >= steps.length) return;
      currentIndex = index;

      steps.forEach((el, i) => {
        el.classList.remove('active', 'completed');
        if (i < index) el.classList.add('completed');
        else if (i === index) el.classList.add('active');
      });

      images.forEach((el, i) => {
        el.classList.toggle('active', i === index);
      });
    }

    function goNext() {
      setActive((currentIndex + 1) % steps.length);
    }

    function stopAutoPlay() {
      if (autoPlayTimerId) {
        clearInterval(autoPlayTimerId);
        autoPlayTimerId = null;
      }
    }

    function clearRestartTimer() {
      if (restartTimerId) {
        clearTimeout(restartTimerId);
        restartTimerId = null;
      }
    }

    function startAutoPlay() {
      if (!isStarted) return;
      stopAutoPlay();
      autoPlayTimerId = setInterval(goNext, INTERVAL_MS);
    }

    function scheduleRestart(delayMs) {
      clearRestartTimer();
      restartTimerId = setTimeout(() => {
        restartTimerId = null;
        startAutoPlay();
      }, delayMs);
    }

    function start() {
      if (isStarted) return;
      isStarted = true;
      setActive(0);
      startAutoPlay();
    }

    steps.forEach((step, index) => {
      step.addEventListener('click', () => {
        if (!isStarted) return;
        stopAutoPlay();
        setActive(index);
        scheduleRestart(RESTART_DELAY_MS);
      });
    });

    if (container) {
      container.addEventListener('mouseenter', () => {
        if (!isStarted) return;
        stopAutoPlay();
        clearRestartTimer();
      });
      container.addEventListener('mouseleave', () => {
        if (!isStarted) return;
        scheduleRestart(HOVER_RESTART_DELAY_MS);
      });
    }

    if (serviceSection) {
      const observer = new IntersectionObserver((entries) => {
        for (const entry of entries) {
          if (entry.isIntersecting) {
            setTimeout(start, 500);
            observer.unobserve(entry.target);
            break;
          }
        }
      }, { threshold: 0.3, rootMargin: '0px 0px -80px 0px' });
      observer.observe(serviceSection);
    }
  })();
});
