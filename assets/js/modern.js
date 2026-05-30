/* ========================================================================
   GSB BIHAR — LIGHTWEIGHT INTERACTIONS
   No Lenis, no ScrollTrigger. IntersectionObserver + CSS only.
   ======================================================================== */

(function () {
  'use strict';

  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  function init() {
    setupScrollState();
    setupBackToTop();
    setupMobileNav();
    setupCounters();
    setupReveals();
    setupHeroIntro();
    setupMagneticButtons();
    setupHeroSlider();
    setupMembersSlider();
    setupTiltCards();
    setupAnchorScroll();
    setupPasswordToggle();
    setupFileUpload();
    setupFormCollapse();
  }

  /* ---------- Form collapse / expand ---------- */
  function setupFormCollapse() {
    const collapse = document.getElementById('formCollapse');
    const triggerWrap = document.getElementById('formTriggerWrap');
    const triggerBtn = document.getElementById('formTriggerBtn');
    if (!collapse) return;

    let open = false;

    function expand(scrollIntoView) {
      if (open) {
        if (scrollIntoView) scrollToForm();
        return;
      }
      open = true;
      collapse.classList.add('open');
      if (triggerWrap) triggerWrap.classList.add('hidden');
      if (triggerBtn) triggerBtn.setAttribute('aria-expanded', 'true');
      if (scrollIntoView) {
        setTimeout(scrollToForm, 300);
      }
      // Focus first field for accessibility
      setTimeout(() => {
        const first = collapse.querySelector('select, input:not([type="hidden"])');
        first?.focus({ preventScroll: true });
      }, 700);
    }

    function scrollToForm() {
      const top = collapse.getBoundingClientRect().top + window.scrollY - 80;
      window.scrollTo({ top, behavior: reducedMotion ? 'auto' : 'smooth' });
    }

    if (triggerBtn) {
      triggerBtn.addEventListener('click', () => expand(false));
    }

    // Any link/button targeting #register expands form
    document.querySelectorAll('a[href="#register"], [data-open-register]').forEach(el => {
      el.addEventListener('click', (e) => {
        e.preventDefault();
        expand(true);
      });
    });

    // If page loads with #register hash, auto-expand
    if (location.hash === '#register') {
      requestAnimationFrame(() => expand(true));
    }
  }

  /* ---------- Password visibility toggle ---------- */
  function setupPasswordToggle() {
    document.querySelectorAll('[data-pwd-target]').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-pwd-target');
        const input = document.getElementById(id);
        if (!input) return;
        const showing = input.type === 'text';
        input.type = showing ? 'password' : 'text';
        btn.classList.toggle('shown', !showing);
        btn.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
      });
    });
  }

  /* ---------- File upload preview ---------- */
  function setupFileUpload() {
    const formatSize = bytes => {
      if (bytes < 1024) return `${bytes} B`;
      if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(0)} KB`;
      return `${(bytes / 1024 / 1024).toFixed(2)} MB`;
    };

    const renderPreview = (dropzone, file) => {
      const img = dropzone.querySelector('.upload-thumb');
      const nameEl = dropzone.querySelector('.upload-name');
      const sizeEl = dropzone.querySelector('.upload-size');
      if (nameEl) nameEl.textContent = file.name;
      if (sizeEl) sizeEl.textContent = formatSize(file.size);
      if (img && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = e => { img.src = e.target.result; };
        reader.readAsDataURL(file);
      }
      dropzone.classList.add('has-file');
    };

    const clearPreview = (dropzone, input) => {
      const img = dropzone.querySelector('.upload-thumb');
      input.value = '';
      if (img) img.removeAttribute('src');
      dropzone.classList.remove('has-file');
    };

    document.querySelectorAll('input[type="file"][data-file-meta]').forEach(input => {
      const dropzone = input.closest('.form-upload');
      if (!dropzone) return;
      const removeBtn = dropzone.querySelector('.upload-remove');

      input.addEventListener('change', () => {
        if (input.files && input.files[0]) renderPreview(dropzone, input.files[0]);
      });

      if (removeBtn) {
        removeBtn.addEventListener('click', e => {
          e.stopPropagation();
          e.preventDefault();
          clearPreview(dropzone, input);
        });
      }

      ['dragenter', 'dragover'].forEach(ev => {
        dropzone.addEventListener(ev, e => {
          e.preventDefault();
          if (!dropzone.classList.contains('has-file')) dropzone.classList.add('is-dragover');
        });
      });

      ['dragleave', 'drop'].forEach(ev => {
        dropzone.addEventListener(ev, e => {
          e.preventDefault();
          dropzone.classList.remove('is-dragover');
        });
      });

      dropzone.addEventListener('drop', e => {
        const file = e.dataTransfer.files && e.dataTransfer.files[0];
        if (!file || !file.type.startsWith('image/')) return;
        try {
          const dt = new DataTransfer();
          dt.items.add(file);
          input.files = dt.files;
        } catch (_) { /* older browsers */ }
        renderPreview(dropzone, file);
      });
    });
  }

  /* ---------- Combined scroll handler (rAF throttled) ---------- */
  function setupScrollState() {
    const bar = document.querySelector('.scroll-progress');
    const header = document.querySelector('.gsb-header');
    const backBtn = document.querySelector('.back-to-top-modern');

    let ticking = false;
    let lastScrolled = false;
    let lastVisible = false;

    function update() {
      const y = window.scrollY;
      const h = document.documentElement;

      if (bar) {
        const max = h.scrollHeight - h.clientHeight;
        bar.style.transform = max > 0 ? `scaleX(${y / max})` : 'scaleX(0)';
      }
      if (header) {
        const should = y > 30;
        if (should !== lastScrolled) {
          lastScrolled = should;
          header.classList.toggle('scrolled', should);
        }
      }
      if (backBtn) {
        const should = y > 400;
        if (should !== lastVisible) {
          lastVisible = should;
          backBtn.classList.toggle('visible', should);
        }
      }
      ticking = false;
    }

    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(update);
        ticking = true;
      }
    }, { passive: true });

    update();
  }

  /* ---------- Back to top ---------- */
  function setupBackToTop() {
    const btn = document.querySelector('.back-to-top-modern');
    if (!btn) return;
    btn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Lift button when footer-bottom enters viewport (avoid overlap with credit)
    const fbottom = document.querySelector('.footer-bottom');
    if (fbottom && 'IntersectionObserver' in window) {
      const io = new IntersectionObserver((entries) => {
        entries.forEach(e => btn.classList.toggle('lifted', e.isIntersecting));
      }, { threshold: 0, rootMargin: '0px 0px 0px 0px' });
      io.observe(fbottom);
    }
  }

  /* ---------- Anchor smooth scroll (native) ---------- */
  function setupAnchorScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(link => {
      const id = link.getAttribute('href');
      // Skip #register — handled by form collapse module
      if (id === '#register') return;
      link.addEventListener('click', (e) => {
        if (id.length <= 1) return;
        const target = document.querySelector(id);
        if (!target) return;
        e.preventDefault();
        const top = target.getBoundingClientRect().top + window.scrollY - 80;
        window.scrollTo({ top, behavior: reducedMotion ? 'auto' : 'smooth' });
      });
    });
  }

  /* ---------- Mobile nav ---------- */
  function setupMobileNav() {
    const toggle = document.querySelector('.gsb-menu-toggle');
    const nav = document.querySelector('.mobile-nav');
    if (!toggle || !nav) return;

    const overlay = document.createElement('div');
    overlay.className = 'mobile-overlay';
    overlay.style.display = 'none';
    document.body.appendChild(overlay);

    function open() {
      nav.classList.add('open');
      overlay.style.display = 'block';
      document.body.style.overflow = 'hidden';
    }
    function close() {
      nav.classList.remove('open');
      overlay.style.display = 'none';
      document.body.style.overflow = '';
    }

    toggle.addEventListener('click', open);
    overlay.addEventListener('click', close);
    nav.querySelector('.mobile-nav-close')?.addEventListener('click', close);
    nav.querySelectorAll('a').forEach(a => a.addEventListener('click', close));
  }

  /* ---------- Counters (IO + rAF) ---------- */
  function setupCounters() {
    const counters = document.querySelectorAll('[data-count]');
    if (!counters.length) return;

    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting || entry.target.dataset.done) return;
        entry.target.dataset.done = '1';
        animateCount(entry.target);
        io.unobserve(entry.target);
      });
    }, { threshold: 0.3 });

    counters.forEach(c => io.observe(c));

    function animateCount(el) {
      const target = parseFloat(el.dataset.count);
      const duration = 1500;
      const start = performance.now();
      function tick(now) {
        const t = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - t, 3);
        el.textContent = Math.round(target * eased).toLocaleString();
        if (t < 1) requestAnimationFrame(tick);
      }
      requestAnimationFrame(tick);
    }
  }

  /* ---------- Reveals (single IO, CSS transition) ---------- */
  function setupReveals() {
    const selector = '.reveal-up, .reveal-fade, .reveal-scale, .reveal-left, .reveal-right, [data-stagger]';
    const els = document.querySelectorAll(selector);
    if (!els.length) return;

    if (reducedMotion) {
      els.forEach(el => el.classList.add('revealed'));
      return;
    }

    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('revealed');
        io.unobserve(entry.target);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

    els.forEach(el => io.observe(el));
  }

  /* ---------- Hero intro (CSS class flip, one-time) ---------- */
  function setupHeroIntro() {
    const hero = document.querySelector('.gsb-hero');
    if (!hero) return;
    if (reducedMotion) {
      hero.classList.add('hero-ready', 'hero-played');
      return;
    }
    requestAnimationFrame(() => {
      hero.classList.add('hero-ready');
    });
  }

  /* ---------- Magnetic buttons (desktop only, throttled) ---------- */
  function setupMagneticButtons() {
    if (reducedMotion) return;
    if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

    document.querySelectorAll('.btn-magnetic').forEach(btn => {
      let rafId = null;
      let tx = 0, ty = 0;
      btn.addEventListener('mousemove', (e) => {
        const r = btn.getBoundingClientRect();
        tx = (e.clientX - r.left - r.width / 2) * 0.2;
        ty = (e.clientY - r.top - r.height / 2) * 0.3;
        if (rafId) return;
        rafId = requestAnimationFrame(() => {
          btn.style.transform = `translate3d(${tx}px, ${ty}px, 0)`;
          rafId = null;
        });
      });
      btn.addEventListener('mouseleave', () => {
        if (rafId) cancelAnimationFrame(rafId);
        rafId = null;
        btn.style.transform = '';
      });
    });
  }

  /* ---------- Card tilt (desktop only, rAF throttled) ---------- */
  function setupTiltCards() {
    if (reducedMotion) return;
    if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

    document.querySelectorAll('[data-tilt]').forEach(card => {
      let rafId = null;
      let rx = 0, ry = 0;
      card.addEventListener('mousemove', (e) => {
        const r = card.getBoundingClientRect();
        const x = (e.clientX - r.left) / r.width - 0.5;
        const y = (e.clientY - r.top) / r.height - 0.5;
        rx = -y * 6;
        ry = x * 6;
        if (rafId) return;
        rafId = requestAnimationFrame(() => {
          card.style.transform = `perspective(1200px) rotateX(${rx}deg) rotateY(${ry}deg)`;
          rafId = null;
        });
      });
      card.addEventListener('mouseleave', () => {
        if (rafId) cancelAnimationFrame(rafId);
        rafId = null;
        card.style.transform = '';
      });
    });
  }

  /* ---------- Members slider (scroll-snap) ---------- */
  function setupMembersSlider() {
    const track = document.querySelector('[data-members-track]');
    if (!track) return;
    const prevBtn = document.querySelector('[data-members-prev]');
    const nextBtn = document.querySelector('[data-members-next]');

    function pageWidth() {
      const card = track.querySelector('.member-card');
      if (!card) return track.clientWidth;
      const gap = parseInt(getComputedStyle(track).columnGap, 10) || 28;
      const cardW = card.getBoundingClientRect().width + gap;
      const perView = Math.max(1, Math.round(track.clientWidth / cardW));
      return cardW * perView;
    }

    function updateArrows() {
      const maxScroll = track.scrollWidth - track.clientWidth - 1;
      if (prevBtn) prevBtn.disabled = track.scrollLeft <= 0;
      if (nextBtn) nextBtn.disabled = track.scrollLeft >= maxScroll;
    }

    nextBtn?.addEventListener('click', () => track.scrollBy({ left: pageWidth(), behavior: 'smooth' }));
    prevBtn?.addEventListener('click', () => track.scrollBy({ left: -pageWidth(), behavior: 'smooth' }));

    let ticking = false;
    track.addEventListener('scroll', () => {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(() => { updateArrows(); ticking = false; });
    }, { passive: true });
    window.addEventListener('resize', updateArrows);

    // Drag to scroll (mouse / desktop)
    let down = false, startX = 0, startScroll = 0, moved = false;
    track.addEventListener('mousedown', (e) => {
      down = true; moved = false;
      startX = e.pageX; startScroll = track.scrollLeft;
    });
    track.addEventListener('mousemove', (e) => {
      if (!down) return;
      const dx = e.pageX - startX;
      if (Math.abs(dx) > 4) { moved = true; track.classList.add('dragging'); }
      track.scrollLeft = startScroll - dx;
    });
    window.addEventListener('mouseup', () => {
      down = false;
      track.classList.remove('dragging');
    });
    // Prevent click navigation after a drag
    track.addEventListener('click', (e) => {
      if (moved) { e.preventDefault(); e.stopPropagation(); moved = false; }
    }, true);

    updateArrows();
  }

  /* ---------- Hero image slider ---------- */
  function setupHeroSlider() {
    const slider = document.querySelector('[data-slider]');
    if (!slider) return;

    const track = slider.querySelector('[data-slider-track]');
    const slides = Array.from(slider.querySelectorAll('.gsb-slide'));
    const dotsWrap = slider.querySelector('[data-slider-dots]');
    const prevBtn = slider.querySelector('[data-slider-prev]');
    const nextBtn = slider.querySelector('[data-slider-next]');
    if (!track || slides.length < 2) {
      slides.forEach(s => s.classList.add('is-active'));
      return;
    }

    const AUTOPLAY_MS = 5000;
    let index = 0;
    let timer = null;

    // Build dot indicators
    const dots = slides.map((_, i) => {
      const dot = document.createElement('button');
      dot.type = 'button';
      dot.setAttribute('role', 'tab');
      dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
      dot.addEventListener('click', () => { goTo(i); restart(); });
      dotsWrap?.appendChild(dot);
      return dot;
    });

    function goTo(n) {
      index = (n + slides.length) % slides.length;
      track.style.transform = `translateX(-${index * 100}%)`;
      slides.forEach((s, i) => {
        const active = i === index;
        s.classList.toggle('is-active', active);
        s.setAttribute('aria-hidden', active ? 'false' : 'true');
      });
      dots.forEach((d, i) => {
        const active = i === index;
        d.classList.toggle('is-active', active);
        d.setAttribute('aria-selected', active ? 'true' : 'false');
      });
    }

    const next = () => goTo(index + 1);
    const prev = () => goTo(index - 1);

    nextBtn?.addEventListener('click', () => { next(); restart(); });
    prevBtn?.addEventListener('click', () => { prev(); restart(); });

    // Keyboard navigation
    slider.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowRight') { next(); restart(); }
      else if (e.key === 'ArrowLeft') { prev(); restart(); }
    });

    // Touch / swipe
    let startX = 0, dx = 0, dragging = false;
    slider.addEventListener('touchstart', (e) => {
      startX = e.touches[0].clientX; dx = 0; dragging = true; stop();
    }, { passive: true });
    slider.addEventListener('touchmove', (e) => {
      if (dragging) dx = e.touches[0].clientX - startX;
    }, { passive: true });
    slider.addEventListener('touchend', () => {
      if (dragging && Math.abs(dx) > 40) (dx < 0 ? next() : prev());
      dragging = false; restart();
    });

    // Autoplay (skip when reduced motion)
    function start() {
      if (reducedMotion) return;
      stop();
      timer = setInterval(next, AUTOPLAY_MS);
    }
    function stop() { if (timer) { clearInterval(timer); timer = null; } }
    function restart() { stop(); start(); }

    slider.addEventListener('mouseenter', stop);
    slider.addEventListener('mouseleave', start);
    slider.addEventListener('focusin', stop);
    slider.addEventListener('focusout', start);
    document.addEventListener('visibilitychange', () => {
      document.hidden ? stop() : start();
    });

    goTo(0);
    start();
  }

})();
