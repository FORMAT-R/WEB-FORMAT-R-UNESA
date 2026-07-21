{{-- =====================================================
     FORMAT-R UNESA — Opening Splash Screen
     • BG putih
     • 2 logo besar berdempetan di tengah
     • SCROLL / swipe untuk membuka pelan-pelan (mengikuti gerakan)
     • Klik / Enter / Space tetap bisa untuk skip instan
     • Refresh = animasi muncul lagi
     ===================================================== --}}

<div id="fr-opening" aria-hidden="true">
    <div class="fr-half fr-half--left" id="fr-left">
        <img
            src="{{ asset('images/logo_unesa.jpg') }}"
            alt="Logo Universitas Negeri Surabaya"
            class="fr-img fr-img--unesa"
            draggable="false"
        >
    </div>
    <div class="fr-half fr-half--right" id="fr-right">
        <img
            src="{{ asset('images/logo_format.png') }}"
            alt="Logo FORMAT-R UNESA"
            class="fr-img fr-img--format"
            draggable="false"
        >
    </div>

    <div class="fr-hint" id="fr-hint">
        <span class="fr-hint-text">Scroll untuk membuka</span>
        <svg class="fr-hint-chevron" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
</div>

<style>
/* ============================================================
   FORMAT-R OPENING SPLASH 
   ============================================================ */

#fr-opening {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: stretch;
    background: #FBF8F1;
    cursor: pointer;
    overflow: hidden;
    touch-action: none; /* kita handle sendiri lewat JS */
    perspective: 1000px;
    contain: layout style paint;
}

/* ── Dua panel kiri & kanan ── */
.fr-half {
    position: relative;
    width: 50%;
    height: 100%;
    display: flex;
    align-items: center;
    background: #FBF8F1;
    will-change: transform;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    transform: translate3d(0,0,0);

    /* Animasi masuk dari luar — kedua logo slide ke tengah */
    animation-duration: 1.15s;
    animation-timing-function: cubic-bezier(0.16, 1, 0.3, 1);
    animation-fill-mode: both;
}

.fr-half--left {
    justify-content: flex-end;
    animation-name: fr-slide-from-left;
}

.fr-half--right {
    justify-content: flex-start;
    animation-name: fr-slide-from-right;
}

@keyframes fr-slide-from-left {
    from { transform: translate3d(-105%,0,0) scale(0.96); opacity: 0.4; }
    to   { transform: translate3d(0,0,0) scale(1); opacity: 1; }
}
@keyframes fr-slide-from-right {
    from { transform: translate3d(105%,0,0) scale(0.96); opacity: 0.4; }
    to   { transform: translate3d(0,0,0) scale(1); opacity: 1; }
}

/* Saat drag/scroll manual → animation & transition CSS dimatikan,
   posisi sepenuhnya dikontrol lewat JS (rAF) supaya tidak dobel-animasi */
.fr-half.fr-dragging {
    animation: none;
    transition: none !important;
}

/* Fade-out keseluruhan splash di akhir — hanya opacity, ringan, tidak bentrok
   dengan transform yang sudah diatur JS */
#fr-opening.fr-exit {
    transition: opacity 0.5s ease;
    opacity: 0;
}

/* ── Logo UNESA (kiri) ── */
.fr-img--unesa {
    display: block;
    width:  clamp(200px, 42vw, 720px);
    height: clamp(200px, 42vw, 720px);
    object-fit: cover;
    object-position: center;
    border-radius: 50%;
    user-select: none;
    -webkit-user-drag: none;
    pointer-events: none;
    margin-right: 0;

    box-shadow:
        0 8px 40px rgba(11,37,69,0.10),
        0 2px  8px rgba(11,37,69,0.06);
    will-change: transform;

    animation: fr-img-in 0.7s 0.75s cubic-bezier(0.22, 1, 0.36, 1) both;
}

/* ── Logo FORMAT-R (kanan) ── */
.fr-img--format {
    display: block;
    width:  clamp(200px, 42vw, 720px);
    height: clamp(200px, 88vh, 900px);
    object-fit: contain;
    object-position: left center;
    user-select: none;
    -webkit-user-drag: none;
    pointer-events: none;
    margin-left: 0;

    filter: drop-shadow(0 8px 30px rgba(11,37,69,0.12));

    animation: fr-img-in 0.7s 0.88s cubic-bezier(0.22, 1, 0.36, 1) both;
}

@keyframes fr-img-in {
    from { opacity: 0; transform: scale(0.9); }
    to   { opacity: 1; transform: scale(1);   }
}

/* ── Hint scroll (muncul setelah logo settle) ── */
.fr-hint {
    position: absolute;
    left: 50%;
    bottom: clamp(28px, 6vh, 64px);
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    color: #0B2545;
    opacity: 0;
    animation: fr-hint-in 0.6s 1.7s ease forwards,
               fr-hint-bounce 1.8s 2.3s ease-in-out infinite;
    pointer-events: none;
    z-index: 2;
}
.fr-hint-text {
    font-size: 13px;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    opacity: 0.65;
    font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
}
.fr-hint-chevron { opacity: 0.75; }

@keyframes fr-hint-in {
    from { opacity: 0; transform: translate(-50%, 8px); }
    to   { opacity: 1; transform: translate(-50%, 0);    }
}
@keyframes fr-hint-bounce {
    0%, 100% { transform: translate(-50%, 0); }
    50%      { transform: translate(-50%, 6px); }
}

/* ── Pointer-events off saat selesai ── */
#fr-opening.fr-done { pointer-events: none; }
#fr-opening.fr-done .fr-hint { display: none; }

/* ── Hilang total (setelah remove dari DOM) ── */
#fr-opening.fr-gone { display: none !important; }

/* ── Responsive ── */
@media (max-width: 600px) {
    .fr-img--unesa {
        width:  clamp(140px, 44vw, 300px);
        height: clamp(140px, 44vw, 300px);
    }
    .fr-img--format {
        width:  clamp(140px, 44vw, 300px);
        height: clamp(140px, 70vh, 500px);
    }
}

@media (prefers-reduced-motion: reduce) {
    .fr-half, .fr-img, .fr-hint { animation: none !important; }
}
</style>

<script>
(function () {
    'use strict';

    var opening = document.getElementById('fr-opening');
    var left    = document.getElementById('fr-left');
    var right   = document.getElementById('fr-right');
    var hint    = document.getElementById('fr-hint');

    if (!opening) return;

    /* ── Cek sessionStorage: kalau sudah pernah tampil di tab ini, sembunyikan langsung ── */
    try {
        if (sessionStorage.getItem('fr_intro_shown')) {
            opening.parentNode && opening.parentNode.removeChild(opening);
            return;
        }
    } catch (e) {}

    document.body.style.overflow = 'hidden';

    var triggered    = false;
    var exiting      = false;
    var dragStarted  = false;
    var introDone    = false;
    var pendingExit  = false;
    var progress     = 0;
    var target       = 0;
    var lastTime     = null;
    var touchStartY  = null;
    var rafId        = null;

    var TAU = 90;

    setTimeout(function () {
        introDone = true;
        if (pendingExit) instantExit();
    }, 1650);

    /* ---------- Loop animasi ---------- */
    function loop(now) {
        if (lastTime === null) lastTime = now;
        var dt = now - lastTime;
        lastTime = now;

        var alpha = 1 - Math.exp(-dt / TAU);
        progress += (target - progress) * alpha;

        if (Math.abs(target - progress) < 0.0008) {
            progress = target;
        }

        applyProgress(progress);

        if (progress >= 0.999 && target >= 1 && !exiting) {
            beginExit();
        }

        if (!triggered) {
            rafId = requestAnimationFrame(loop);
        }
    }

    function ensureLoopRunning() {
        if (!dragStarted) {
            dragStarted = true;
            left.classList.add('fr-dragging');
            right.classList.add('fr-dragging');
        }
        if (rafId === null) {
            lastTime = null;
            rafId = requestAnimationFrame(loop);
        }
    }

    function applyProgress(p) {
        var leftX  = -p * 108;
        var rightX =  p * 108;
        var scale  = 1 - p * 0.05;

        left.style.transform  = 'translate3d(' + leftX  + '%,0,0) scale(' + scale + ')';
        right.style.transform = 'translate3d(' + rightX + '%,0,0) scale(' + scale + ')';

        if (hint) hint.style.opacity = String(Math.max(0, 1 - p * 4));
    }

    function beginExit() {
        if (exiting) return;
        exiting = true;

        /* Tandai di sessionStorage supaya halaman lain tidak tampilkan lagi */
        try { sessionStorage.setItem('fr_intro_shown', '1'); } catch (e) {}

        opening.classList.add('fr-done');
        opening.classList.add('fr-exit');

        setTimeout(function () {
            triggered = true;
            if (rafId !== null) { cancelAnimationFrame(rafId); rafId = null; }

            document.body.style.overflow = '';

            var main = document.querySelector('main');
            if (main) {
                main.style.opacity    = '0';
                main.style.transform  = 'translateY(18px)';
                main.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () {
                        main.style.opacity   = '1';
                        main.style.transform = 'translateY(0)';
                        // Trik GSAP: Refresh kalkulasi posisi elemen setelah animasi masuk selesai
                        // supaya animasi scroll (pin) tidak lompat saat pertama kali dibuka
                        setTimeout(function() {
                            main.style.transform = '';
                            main.style.transition = '';
                            window.dispatchEvent(new Event('splashScreenClosed'));
                            window.dispatchEvent(new Event('resize'));
                        }, 850);
                    });
                });
            }

            if (opening && opening.parentNode) {
                opening.parentNode.removeChild(opening);
            }
        }, 520);
    }

    function instantExit() {
        if (triggered || exiting) return;
        if (!introDone) { pendingExit = true; return; }
        target = 1;
        ensureLoopRunning();
    }

    /* ---------- Scroll wheel ---------- */
    opening.addEventListener('wheel', function (e) {
        if (triggered || exiting) return;
        e.preventDefault();
        if (!introDone) return;

        var raw = e.deltaY;
        if (e.deltaMode === 1) raw *= 16;
        else if (e.deltaMode === 2) raw *= window.innerHeight;
        raw = Math.max(-120, Math.min(120, raw));

        target += raw * 0.0016;
        target = Math.max(0, Math.min(1, target));
        ensureLoopRunning();
    }, { passive: false });

    /* ---------- Touch swipe ---------- */
    opening.addEventListener('touchstart', function (e) {
        if (triggered || exiting) return;
        touchStartY = e.touches[0].clientY;
        if (introDone) ensureLoopRunning();
    }, { passive: true });

    opening.addEventListener('touchmove', function (e) {
        if (triggered || exiting || touchStartY === null) return;
        e.preventDefault();
        if (!introDone) return;

        var currentY = e.touches[0].clientY;
        var delta = touchStartY - currentY;
        touchStartY = currentY;
        delta = Math.max(-60, Math.min(60, delta));

        target += delta * 0.0032;
        target = Math.max(0, Math.min(1, target));
    }, { passive: false });

    opening.addEventListener('touchend', function () {
        touchStartY = null;
    });

    /* ---------- Klik & keyboard ---------- */
    opening.addEventListener('click', instantExit);

    document.addEventListener('keydown', function onKey(e) {
        if (e.key === 'Enter' || e.key === ' ' || e.key === 'Escape' || e.key === 'ArrowDown') {
            document.removeEventListener('keydown', onKey);
            instantExit();
        }
    });

})();
</script>