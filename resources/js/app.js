/* ============================================================
   FORMAT-R UNESA — Global JavaScript
   ============================================================ */

import './scroll-animations.js';

(function () {
    'use strict';

    /* ========== Dark Mode ========== */
    const darkToggles = document.querySelectorAll('#darkToggle, #darkToggleMobile');
    if (darkToggles.length > 0) {
        // Restore saved preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark');
        }
        darkToggles.forEach(toggle => {
            toggle.addEventListener('click', function () {
                document.body.classList.toggle('dark');
                localStorage.setItem('darkMode', document.body.classList.contains('dark'));
            });
        });
    }

    /* ========== FAQ Accordion ========== */
    document.querySelectorAll('.faq-q').forEach(function (q) {
        q.addEventListener('click', function () {
            const item = q.parentElement;
            const wasOpen = item.classList.contains('open');
            document.querySelectorAll('.faq-item').forEach(function (i) {
                i.classList.remove('open');
            });
            if (!wasOpen) item.classList.add('open');
        });
    });

    /* ========== Mobile Menu ========== */
    const burgerBtn   = document.getElementById('burgerBtn');
    const mobileMenu  = document.getElementById('mobileMenu');
    const mobileClose = document.getElementById('mobileClose');

    function openMenu()  { if (mobileMenu) { mobileMenu.classList.add('open'); document.body.style.overflow = 'hidden'; } }
    function closeMenu() { if (mobileMenu) { mobileMenu.classList.remove('open'); document.body.style.overflow = ''; } }

    if (burgerBtn)  burgerBtn.addEventListener('click', openMenu);
    if (mobileClose) mobileClose.addEventListener('click', closeMenu);
    if (mobileMenu) {
        mobileMenu.addEventListener('click', function (e) { if (e.target === mobileMenu) closeMenu(); });
        mobileMenu.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', closeMenu); });
    }

    /* ========== Scroll: Nav Shrink + Back-to-Top (Optimized with requestAnimationFrame) ========== */
    const progress = document.getElementById('scrollProgress');
    const navWrap  = document.getElementById('navWrap');
    const toTop    = document.getElementById('toTop');
    const hasGsapHome = window.innerWidth >= 768 && !!document.querySelector('#home .stack-section, #home.stack-section, .stack-section');

    let scrollTicking = false;
    function onScroll() {
        if (!scrollTicking) {
            requestAnimationFrame(function () {
                const h          = document.documentElement;
                const scrollTop  = h.scrollTop || document.body.scrollTop;
                const scrollHeight = (h.scrollHeight || document.body.scrollHeight) - h.clientHeight;
                const pct        = scrollHeight > 0 ? (scrollTop / scrollHeight) * 100 : 0;

                if (progress && !hasGsapHome) progress.style.width = pct + '%';
                if (navWrap)  navWrap.classList.toggle('scrolled', scrollTop > 40);
                if (toTop)    toTop.classList.toggle('show', scrollTop > 500);

                scrollTicking = false;
            });
            scrollTicking = true;
        }
    }

    document.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    if (toTop) {
        toTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* ========== Scroll Reveal (IntersectionObserver) ========== */
    const revealEls = document.querySelectorAll('.reveal:not([data-reveal]):not([data-stagger-child])');
    if (revealEls.length && 'IntersectionObserver' in window) {
        const io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });
        revealEls.forEach(function (el) { io.observe(el); });
    } else {
        revealEls.forEach(function (el) { el.classList.add('visible'); });
    }

    /* ========== Scrollspy — Active Nav Link (Optimized: Cached offsets + requestAnimationFrame) ========== */
    if (!hasGsapHome) {
        const sectionIds = ['home', 'tentang', 'visimisi', 'pembina', 'berita', 'apresiasi', 'arsip', 'penghargaan', 'faq', 'kontak'];
        const sections   = sectionIds.map(id => document.getElementById(id)).filter(Boolean);
        const navItems   = document.querySelectorAll('#navLinks > li[data-sec]');

        let cachedOffsets = [];
        function updateCachedOffsets() {
            cachedOffsets = sections.map(sec => ({ id: sec.id, top: sec.offsetTop }));
        }
        updateCachedOffsets();
        window.addEventListener('resize', updateCachedOffsets, { passive: true });

        let navTicking = false;
        function updateActiveNav() {
            if (!navTicking) {
                requestAnimationFrame(function () {
                    const scrollPos = window.scrollY + 140;
                    let currentId   = cachedOffsets[0] ? cachedOffsets[0].id : null;
                    for (let i = 0; i < cachedOffsets.length; i++) {
                        if (cachedOffsets[i].top <= scrollPos) {
                            currentId = cachedOffsets[i].id;
                        }
                    }
                    const mapId = (currentId === 'visimisi' || currentId === 'pembina') ? 'tentang' : currentId;
                    navItems.forEach(function (li) {
                        li.classList.toggle('active', li.getAttribute('data-sec') === mapId);
                    });
                    navTicking = false;
                });
                navTicking = true;
            }
        }

        if (sections.length > 0) {
            document.addEventListener('scroll', updateActiveNav, { passive: true });
            updateActiveNav();
        }
    }

    /* ========== Animated Stat Counters ========== */
    const counters     = document.querySelectorAll('.count');
    let countersDone   = false;

    function animateCounters() {
        if (countersDone) return;
        countersDone = true;
        counters.forEach(function (el) {
            const target   = parseInt(el.getAttribute('data-target'), 10) || 0;
            const suffix   = el.getAttribute('data-suffix') || (target >= 100 ? '+' : '');
            let startTime  = null;
            const duration = 1100;

            function step(ts) {
                if (!startTime) startTime = ts;
                const progressRatio = Math.min((ts - startTime) / duration, 1);
                const eased = 1 - Math.pow(1 - progressRatio, 3);
                el.textContent = Math.round(eased * target) + suffix;
                if (progressRatio < 1) requestAnimationFrame(step);
                else el.textContent = target + suffix;
            }
            requestAnimationFrame(step);
        });
    }

    const heroStat = document.querySelector('.stat-row');
    if (heroStat && 'IntersectionObserver' in window) {
        const statIo = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) { animateCounters(); statIo.disconnect(); }
            });
        }, { threshold: 0.4 });
        statIo.observe(heroStat);
    } else if (counters.length > 0) {
        animateCounters();
    }

    /* ========== Contact Form Validation ========== */
    const form      = document.getElementById('kontakForm');
    const submitBtn = document.getElementById('submitBtn');
    const formNote  = document.getElementById('formNote');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const nama         = document.getElementById('inputNama');
            const email        = document.getElementById('inputEmail');
            const pesan        = document.getElementById('inputPesan');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            let valid          = true;

            function toggleError(fieldId, hasError) {
                const field = document.getElementById(fieldId);
                if (!field) return;
                field.classList.toggle('error', hasError);
                if (hasError) valid = false;
            }

            toggleError('fieldNama',  nama.value.trim().length === 0);
            toggleError('fieldEmail', !emailPattern.test(email.value.trim()));
            toggleError('fieldPesan', pesan.value.trim().length === 0);

            if (!valid) {
                if (formNote) { formNote.textContent = 'Mohon lengkapi data dengan benar.'; formNote.style.color = '#ff9a9a'; }
                return;
            }

            if (submitBtn) { submitBtn.textContent = 'Terkirim ✓'; submitBtn.classList.add('sent'); }
            if (formNote)  { formNote.textContent = 'Terima kasih! Pesanmu sudah kami terima.'; formNote.style.color = '#AFC0DA'; }
            form.reset();
            setTimeout(function () {
                if (submitBtn) { submitBtn.textContent = 'Kirim Pesan'; submitBtn.classList.remove('sent'); }
            }, 2600);
        });
    }

})();
