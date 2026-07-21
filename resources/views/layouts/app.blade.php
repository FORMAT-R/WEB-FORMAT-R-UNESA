<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FORMAT-R UNESA — Forum Mahasiswa')</title>
    <meta name="description" content="@yield('description', 'FORMAT-R UNESA adalah forum organisasi kemahasiswaan yang mewadahi pengembangan SDM, penalaran, minat bakat, informasi, kewirausahaan, dan kerohanian mahasiswa UNESA.')">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Page-specific styles --}}
    @stack('styles')
</head>
<body>

{{-- ===== OPENING SPLASH SCREEN ===== --}}
@include('components.opening')

{{-- Scroll Progress Bar --}}
<div class="scroll-progress" id="scrollProgress"></div>

{{-- ===== NAVBAR ===== --}}
<div class="nav-wrap" id="navWrap">
    <nav class="navbar">
        <a href="{{ route('home') }}" class="brand">
            <img src="{{ asset('images/logo_format.png') }}" alt="Logo FORMAT-R" class="brand-logo">
            <span><span class="org">FORMAT-R</span> <span class="unesa">UNESA</span></span>
        </a>

        <ul class="nav-links" id="navLinks">
            <li data-sec="home"><a href="{{ route('home') }}" class="top-link">Beranda</a></li>
            <li data-sec="tentang">
                <a href="{{ route('home') }}#tentang" class="top-link">Profil
                    <svg class="chev" viewBox="0 0 12 12" fill="none"><path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </a>
                <div class="dropdown">
                    <a href="{{ route('home') }}#tentang">Tentang FORMAT-R</a>
                    <a href="{{ route('home') }}#visimisi">Visi &amp; Misi</a>
                </div>
            </li>
            <li data-sec="departemen"><a href="{{ route('departemen.index') }}" class="top-link">Departemen</a></li>
            <li data-sec="berita"><a href="{{ route('home') }}#berita" class="top-link">Berita</a></li>
            <li data-sec="event"><a href="{{ route('event.index') }}" class="top-link">Event</a></li>
            <li data-sec="apresiasi"><a href="{{ route('home') }}#apresiasi" class="top-link">Apresiasi</a></li>
            <li data-sec="arsip"><a href="{{ route('arsip') }}" class="top-link">Arsip</a></li>
            <li data-sec="faq"><a href="{{ route('home') }}#faq" class="top-link">FAQ</a></li>
            <li data-sec="kontak"><a href="{{ route('home') }}#kontak" class="top-link">Kontak</a></li>
        </ul>

        <div class="nav-tools">
            <button class="icon-btn" id="darkToggle" aria-label="Ganti tema gelap/terang">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                    <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                </svg>
            </button>
            <a href="{{ route('home') }}#kontak" class="nav-cta">Gabung Kami</a>
            <button class="icon-btn burger" id="burgerBtn" aria-label="Menu">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
    </nav>
</div>

{{-- ===== MOBILE MENU ===== --}}
<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-panel">
        <button class="mobile-close" id="mobileClose" aria-label="Tutup menu">✕</button>
        <a href="{{ route('home') }}">Beranda</a>
        <a href="{{ route('home') }}#tentang">Tentang</a>
        <a href="{{ route('home') }}#visimisi">Visi &amp; Misi</a>
        <a href="{{ route('departemen.index') }}">Departemen</a>
        <a href="{{ route('home') }}#berita">Berita</a>
        <a href="{{ route('event.index') }}">Event</a>
        <a href="{{ route('home') }}#apresiasi">Apresiasi</a>
<a href="{{ route('arsip') }}">Arsip</a>
        <a href="{{ route('home') }}#faq">FAQ</a>
        <a href="{{ route('home') }}#kontak">Kontak</a>
    </div>
</div>

{{-- ===== MAIN CONTENT ===== --}}
<main>
    @yield('content')
</main>

{{-- ===== FOOTER ===== --}}
<footer>
    <div class="container">
        <div class="foot-grid">
            <div>
                <div class="foot-brand">
                    <span class="mark" style="width:30px;height:30px;">
                        <img src="{{ asset('images/logo_format.png') }}" alt="Logo FORMAT-R" style="width:30px;height:30px;object-fit:contain;border-radius:50%;">
                    </span>
                    FORMAT-R UNESA
                </div>
                <p style="color:var(--ink-soft);font-size:0.9rem;max-width:280px;">
                    Forum organisasi kemahasiswaan Universitas Negeri Surabaya. Satu forum, ribuan langkah bersama.
                </p>
            </div>
            <div class="foot-col">
                <h5>Navigasi</h5>
                <ul>
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="{{ route('home') }}#tentang">Tentang</a></li>
                    <li><a href="{{ route('departemen.index') }}">Departemen</a></li>
                    <li><a href="{{ route('home') }}#berita">Berita</a></li>
                </ul>
            </div>
            <div class="foot-col">
                <h5>Lainnya</h5>
                <ul>
                    <li><a href="{{ route('event.index') }}">Event</a></li>
                    <li><a href="{{ route('home') }}#apresiasi">Apresiasi</a></li>
                    <li><a href="{{ route('arsip') }}">Arsip</a></li>
                    <li><a href="{{ route('home') }}#faq">FAQ</a></li>
                    <li><a href="{{ route('home') }}#kontak">Kontak</a></li>
                </ul>
            </div>
            <div class="foot-col">
                <h5>Sosial Media</h5>
                <ul>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">TikTok</a></li>
                    <li><a href="#">YouTube</a></li>
                </ul>
            </div>
            <div class="foot-col">
            <h5>Kabinet</h5>
                <ul>
                    <li><img src="{{ get_setting('cabinetLogo') ? Storage::url(get_setting('cabinetLogo')) : asset('images/logo_kabinet.jpeg') }}" alt="Logo Kabinet" style="width:120px;height:auto;border-radius:8px;"></li>
                </ul>
            </div>
        </div>
        <div class="foot-bottom">
            <span>&copy; {{ date('Y') }} FORMAT-R UNESA. Seluruh hak cipta dilindungi.</span>
            <span>Dibuat dengan bangga oleh anggota FORMAT-R.</span>
        </div>
    </div>
</footer>
        </div>
    </div>
</footer>

{{-- Back to Top --}}
<button class="to-top" id="toTop" aria-label="Kembali ke atas">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
        <path d="M12 19V5M5 12l7-7 7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</button>

{{-- Page-specific scripts --}}
@stack('scripts')

</body>
</html>
