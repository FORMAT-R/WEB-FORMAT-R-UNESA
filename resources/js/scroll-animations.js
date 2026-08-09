/* ============================================================
   FORMAT-R UNESA — GSAP ScrollTrigger Animations (Homepage)
   ============================================================ */

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ScrollToPlugin } from 'gsap/ScrollToPlugin';

gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

/* ===== GLOBAL CONFIG ===== */
// Mencegah GSAP panik dan melakukan kalkulasi ulang (refresh) saat address bar browser muncul/hilang
ScrollTrigger.config({ ignoreMobileResize: true });

/* ===== STACK / PIN SECTIONS (All sections including Hero) ===== */
function initStackScroll() {
  // ==========================================
  // MASTER TIMELINE STACKING ARCHITECTURE
  // ==========================================
  const allSections = gsap.utils.toArray('.stack-section');
  if (allSections.length < 2) return;

  // Pisahkan kontak dari tumpukan
  const stackSections = allSections.filter(sec => sec.id !== 'kontak');
  const kontakSection = allSections.find(sec => sec.id === 'kontak');

  // Buat Master Container
  const masterContainer = document.createElement('div');
  masterContainer.className = 'master-stack-container';
  masterContainer.style.position = 'relative';
  masterContainer.style.width = '100%';
  masterContainer.style.height = '100svh';
  masterContainer.style.backgroundColor = '#ffffff';

  // Sisipkan Master Container
  stackSections[0].parentNode.insertBefore(masterContainer, stackSections[0]);

  // Pindahkan seksi ke dalam Master Container & Set Posisi Awal
  stackSections.forEach((sec, i) => {
    sec.style.position = 'absolute';
    sec.style.top = '0';
    sec.style.left = '0';
    sec.style.width = '100%';
    sec.style.zIndex = i + 10;
    
    // Semua kartu KECUALI yang pertama ditaruh di luar layar bawah
    if (i > 0) {
      gsap.set(sec, { y: '100vh' }); 
    }
    
    masterContainer.appendChild(sec);
  });

  // Pastikan kontak tetap di luar dan punya z-index agar bisa menutupi
  if (kontakSection) {
      kontakSection.style.position = 'relative';
      kontakSection.style.zIndex = 50; 
  }

  // Master Timeline
  const tl = gsap.timeline({
    scrollTrigger: {
      trigger: masterContainer,
      start: 'top top',
      end: '+=' + ((stackSections.length - 1) * 100) + '%', 
      pin: true,
      pinSpacing: true,
      scrub: 1,
      invalidateOnRefresh: true,
    }
  });

  // Hero parallax saat scroll awal (index 0)
  gsap.to('#home .hero-logos', {
    y: -120, opacity: 0, ease: 'none',
    scrollTrigger: { trigger: '#home', start: 'top top', end: 'bottom top', scrub: 1 }
  });
  gsap.to('#home .stat-row, #home .btn-row', {
    y: 80, opacity: 0, ease: 'none',
    scrollTrigger: { trigger: '#home', start: 'top top', end: 'bottom top', scrub: 1 }
  });

  // Animasi Stacking
  stackSections.forEach((sec, i) => {
    const next = stackSections[i + 1];
    if (!next) return;

    // Bersamaan: Kartu sekarang mengecil, kartu berikutnya naik
    tl.to(sec, {
      scale: 0.9,
      opacity: 0.4,
      ease: 'none',
    }, i * 1);

    tl.to(next, {
      y: '0vh',
      ease: 'none',
    }, i * 1);
  });

  // ==========================================
  // SECTION REVEAL ANIMATIONS (ABSOLUTE TRIGGER)
  // ==========================================
    const sectionReveal = ({ id, fromSelector, stagger = 0.1, parallaxSpeed = 0.1, yFrom = 50 }) => {
      const sec = document.querySelector(id);
      if (!sec) return;
  
      const elements = sec.querySelectorAll(fromSelector);
      if (elements.length > 0) {
        gsap.fromTo(elements, 
          { y: yFrom, opacity: 0 },
          {
            y: 0, opacity: 1, ease: 'power3.out', duration: 0.5, stagger,
            scrollTrigger: {
              trigger: sec, // Trigger is the section itself, NOT the body with absolute index
              start: 'top 80%', // Starts animation when the top of the section hits 80% down the screen
              toggleActions: 'play none none reverse'
            }
          }
        );
      }

    const parallaxElements = sec.querySelectorAll('.parallax');
    if (parallaxElements.length > 0) {
      gsap.to(parallaxElements, {
        y: (i, el) => -window.innerHeight * parseFloat(el.dataset.parallax || parallaxSpeed),
        ease: 'none',
        scrollTrigger: { 
            trigger: document.body, 
            start: () => (index + 1) * window.innerHeight, 
            end: () => (index + 2) * window.innerHeight, 
            scrub: 1 
        }
      });
    }
  };

    const sectionsList = [
      { id: '#tentang', fromSelector: '.tentang-copy *, .tentang-badge', stagger: 0.1, parallaxSpeed: 0.3, yFrom: 40, index: 0 },
      { id: '#visimisi', fromSelector: '.vm-badge, .vm-content > *', stagger: 0.1, parallaxSpeed: 0.2, yFrom: 40, index: 1 },
      { id: '#pembina', fromSelector: '.pembina-content > *, .pembina-photo-wrap, .pembina-info', stagger: 0.1, parallaxSpeed: 0.1, yFrom: 30, index: 2 },
      { id: '#berita', fromSelector: '.sec-head, .art-card', stagger: 0.08, parallaxSpeed: 0, yFrom: 30, index: 3 },
      { id: '#apresiasi', fromSelector: '.sec-head > *', stagger: 0.08, parallaxSpeed: 0, yFrom: 30, index: 4 },
      { id: '#faq', fromSelector: '.sec-head > *, .faq-item', stagger: 0.05, parallaxSpeed: 0.1, yFrom: 30, index: 5 }
    ];
  sectionsList.forEach(s => sectionReveal(s));

  // ==========================================
  // ABSOLUTE MATH ANCHOR SCROLL
  // ==========================================
  document.querySelectorAll('a[href*="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const url = new URL(this.href, window.location.href);
      if (url.pathname !== window.location.pathname) return;
      
      const targetId = url.hash;
      if (!targetId || targetId === '#') return;
      const target = document.querySelector(targetId);
      
      if (target) {
        e.preventDefault();
        
        let targetY = target.offsetTop; 
        const sectionsArr = gsap.utils.toArray('.stack-section');
        const index = sectionsArr.indexOf(target);
        
        if (index !== -1) {
            // Formula Pasti: 100vh (hero) + (index * 100vh)
            targetY = (index + 1) * window.innerHeight;
        } else {
            targetY = target.offsetTop - 80;
        }

        const distance = Math.abs(window.scrollY - targetY);
        let scrollDuration = 1;
        if (distance > window.innerHeight * 4) {
            scrollDuration = 0;
        } else if (distance > window.innerHeight * 2) {
            scrollDuration = 0.4;
        }

        gsap.to(window, {
            duration: scrollDuration, 
            ease: 'power3.inOut',
            overwrite: 'auto',
            scrollTo: targetY
        });
      }
    });
  });
}

/* ===== MAIN ANIMATION INIT ===== */
export function initHomepageAnimations() {
  const isHome = document.querySelector('#home');
  if (!isHome) return;

  // MATIKAN ANIMASI GSAP DI HP UNTUK MENCEGAH LEMOT DAN BERANTAKAN
  if (window.innerWidth < 768) {
      return;
  }

  initStackScroll();

  // 4. ScrollSpy — single source of truth for active nav state
  const navLinks = document.querySelectorAll('.nav-links a.top-link');
  const sectionsArr = gsap.utils.toArray('.stack-section');

  sectionsArr.forEach((sec, index) => {
    ScrollTrigger.create({
      trigger: document.body,
      // Aktif tepat saat seksi mulai muncul (e.g. 150vh) dan berakhir saat seksi berikutnya muncul
      start: () => (index * window.innerHeight) + (window.innerHeight * 0.5),
      end: () => ((index + 1) * window.innerHeight) + (window.innerHeight * 0.5),
      onToggle: self => {
        if (self.isActive) {
          const id = sec.id;
          navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href) {
                link.classList.toggle('active', href.includes(id));
            }
          });
        }
      }
    });
  });

  // 5. Refresh after images load (handles dynamic heights)
  if (document.readyState === 'complete') {
    ScrollTrigger.refresh();
  } else {
    window.addEventListener('load', () => ScrollTrigger.refresh());
  }

  // Refresh manually when opening splash screen finishes (to fix first load pin bounds bug)
  window.addEventListener('splashScreenClosed', () => {
    ScrollTrigger.refresh();
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    initHomepageAnimations();
  });
} else {
  initHomepageAnimations();
}