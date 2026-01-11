const header = document.getElementById('siteHeader');
const floatingBar = document.getElementById('floatingBar');
const hero = document.getElementById('hero');

window.addEventListener('scroll', () => {
  const scrollPos = window.scrollY;
  const heroHeight = hero.offsetHeight - 80;

  if (scrollPos > heroHeight) {
    header.classList.add('scrolled');
    floatingBar.classList.add('visible');
  } else {
    header.classList.remove('scrolled');
    floatingBar.classList.remove('visible');
  }
});

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
            const cardCenter = cardTop + cardRect.height / 2;
            const numberTop = cardCenter - flowLineInnerTop;
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
