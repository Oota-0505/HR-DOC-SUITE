/**
 * LP Template JavaScript（汎用）
 * ========================================
 * 各LPで必要な処理（スクロールアニメ等）はプロジェクトごとに追加してください。
 */

document.addEventListener('DOMContentLoaded', () => {
  // ========================================
  // Header Scroll Effect
  // ========================================
  const header = document.getElementById('siteHeader');
  const hero = document.getElementById('hero');

  if (header && hero) {
    const handleScroll = () => {
      const scrollPos = window.scrollY;
      const heroHeight = hero.offsetHeight - 100;

      if (scrollPos > heroHeight) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
  }

  // ========================================
  // Mobile Menu Toggle
  // ========================================
  const menuToggle = document.querySelector('.menu-toggle');
  const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

  if (menuToggle) {
    menuToggle.addEventListener('click', () => {
      document.body.classList.toggle('menu-open');
    });
  }

  mobileNavLinks.forEach(link => {
    link.addEventListener('click', () => {
      document.body.classList.remove('menu-open');
    });
  });

  // ========================================
  // Smooth Scroll for Anchor Links
  // ========================================
  const anchorLinks = document.querySelectorAll('a[href^="#"]');

  anchorLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      const href = link.getAttribute('href');

      if (href === '#') return;

      const target = document.querySelector(href);

      if (target) {
        e.preventDefault();

        const headerHeight = header ? header.offsetHeight : 0;
        const targetPosition = target.getBoundingClientRect().top + window.scrollY - headerHeight;

        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth'
        });

        document.body.classList.remove('menu-open');
      }
    });
  });

  // ========================================
  // Contact Form（送信は各LPで実装）
  // ========================================
  const contactForm = document.querySelector('.contact-form');

  if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
      e.preventDefault();

      const formData = new FormData(contactForm);
      const data = Object.fromEntries(formData.entries());

      const requiredFields = ['lastName', 'firstName', 'email', 'subject', 'message'];
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
        // 送信処理は各LPで実装（API・Formspree・CF7等）
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
});
