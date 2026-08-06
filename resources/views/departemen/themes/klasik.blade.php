@php
  // Deteksi font: jika bg_pattern mengandung mat-green (BPH default), pakai Caveat, sisanya Inter
  $isCaveatTheme = isset($dept['theme']) && !isset($dept['bg_pattern']);

  $themePattern = $dept['bg_pattern']
      ?? 'linear-gradient(var(--mat-green-line) 1px, transparent 1px), linear-gradient(90deg, var(--mat-green-line) 1px, transparent 1px)';

  // Ukuran background: deteksi otomatis berdasarkan jenis pattern
  if (str_contains($themePattern, 'radial-gradient(var(--mat-green-line) 2px')) {
      $themeBgSize = '28px 28px'; // dot grid
  } elseif (str_contains($themePattern, 'repeating-radial-gradient')) {
      $themeBgSize = '40px 40px';
  } elseif (str_contains($themePattern, 'repeating-linear-gradient(0deg') || str_contains($themePattern, 'repeating-linear-gradient(45deg')) {
      $themeBgSize = 'auto';
  } elseif (str_contains($themePattern, 'radial-gradient(circle at center')) {
      $themeBgSize = 'cover';
  } else {
      // Cek manual jika grid dari BPH
      if (str_contains(strtolower($dept['singkatan'] ?? $dept['nama']), 'bph')) {
          $themeBgSize = '30px 30px'; // default grid BPH
      } else {
          $themeBgSize = '45px 45px'; // grid khusus departemen lain seperti POSDM
      }
  }

  // Khusus KOMINFO dan departemen lain yang sudah punya pola grid dengan warna yang berbeda,
  // biar tidak timpa-timpa, kita pastikan pattern originalnya yang dipakai tanpa dimodifikasi oleh if dibawahnya.
  
  // Font heading: semua departemen pakai Inter kecuali jika bg_pattern kosong (BPH default)
  $themeFontUrl  = "https://fonts.googleapis.com/css2?family=Inter:wght@600;800;900&family=Space+Grotesk:wght@400;500;600;700&family=Caveat:wght@600;700&display=swap";
  $themeHeadingFont = "'Inter', sans-serif";

  $scatterPositions = [
      ['top' => '5%',  'left'  => '6%',  'size' => '80px',  'rotate' => '-15deg'],
      ['top' => '12%', 'right' => '8%',  'size' => '110px', 'rotate' => '22deg'],
      ['top' => '20%', 'left'  => '15%', 'size' => '60px',  'rotate' => '45deg'],
      ['top' => '28%', 'right' => '4%',  'size' => '90px',  'rotate' => '-10deg'],
      ['top' => '36%', 'left'  => '8%',  'size' => '120px', 'rotate' => '15deg'],
      ['top' => '44%', 'right' => '12%', 'size' => '70px',  'rotate' => '-30deg'],
      ['top' => '52%', 'left'  => '4%',  'size' => '100px', 'rotate' => '10deg'],
      ['top' => '60%', 'right' => '6%',  'size' => '80px',  'rotate' => '-20deg'],
      ['top' => '68%', 'left'  => '12%', 'size' => '110px', 'rotate' => '25deg'],
      ['top' => '76%', 'right' => '4%',  'size' => '90px',  'rotate' => '-15deg'],
      ['top' => '84%', 'left'  => '6%',  'size' => '75px',  'rotate' => '35deg'],
      ['top' => '92%', 'right' => '10%', 'size' => '130px', 'rotate' => '-10deg'],
      ['top' => '97%', 'left'  => '14%', 'size' => '85px',  'rotate' => '15deg'],
  ];
@endphp

@extends('layouts.dept-base')

@section('theme-fonts')
<link href="{!! $themeFontUrl !!}" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
@endsection

@section('theme-styles')
<style>
  /* Pastikan parent / body mendukung absolute dari ujung atas sampai ujung bawah */
  html, body {
    position: relative; 
  }
  
  :root {
    --mat-green: {{ $dept['theme']['mat-green'] ?? '#28362b' }};
    --mat-green-line: {{ $dept['theme']['mat-green-line'] ?? '#33452f' }};
    --purple: {{ $dept['theme']['purple'] ?? '#4c2f7a' }};
    --purple-dark: {{ $dept['theme']['purple-dark'] ?? '#341f57' }};
    --blue: {{ $dept['theme']['blue'] ?? '#3b6fd1' }};
    --blue-dark: {{ $dept['theme']['blue-dark'] ?? '#1f3f82' }};
    --ink: #111827;
  }

  body {
    background: var(--mat-green) !important;
    background-image: {!! $themePattern !!} !important;
    background-size: {!! $themeBgSize !!} !important;
    background-attachment: fixed !important;
    font-family: 'Space Grotesk', sans-serif !important;
    color: var(--cream) !important;
  }
  
  .script { font-family: 'Caveat', cursive; }

  #globalBackLink {
    color: var(--gold); background: rgba(0,0,0,0.25); border: 1px dashed rgba(227,189,93,0.4);
    font-family: 'JetBrains Mono', monospace; text-transform: uppercase;
  }
  #globalNavSection { background: transparent; border-top: 2px dashed rgba(244,236,216,0.3); }
  #globalNavTitle { color: var(--gold); }
  #globalFooter { background: transparent; border-top:none; padding-top: 0; color: var(--cream-dark); font-family: 'JetBrains Mono', monospace; }
  
  /* Shared: ribbon */
  
  .global-ornaments-wrapper-old {
    position: absolute; top: 0; left: 0; right: 0; bottom: 0;
    overflow: hidden; z-index: 0; pointer-events: none;
  }
  .bg-ornament {
    position: absolute; color: var(--gold); opacity: 0.12;
  }
  .bg-ornament svg, .bg-ornament i { width: 100%; height: 100%; display: block; }
  
  .ribbon {
    position: relative; display: inline-block;
    background: linear-gradient(135deg, var(--purple), var(--purple-dark));
    padding: 14px 34px; color: var(--white-tape);
    clip-path: polygon(3.00% 0.00%,5.61% -1.80%,8.22% -3.12%,10.83% -3.60%,13.44% -3.12%,16.06% -1.80%,18.67% -0.00%,18.67% 0.00%,21.28% -1.80%,23.89% -3.12%,26.50% -3.60%,29.11% -3.12%,31.72% -1.80%,34.33% -0.00%,34.33% 0.00%,36.94% -1.80%,39.56% -3.12%,42.17% -3.60%,44.78% -3.12%,47.39% -1.80%,50.00% -0.00%,50.00% 0.00%,52.61% -1.80%,55.22% -3.12%,57.83% -3.60%,60.44% -3.12%,63.06% -1.80%,65.67% -0.00%,65.67% 0.00%,68.28% -1.80%,70.89% -3.12%,73.50% -3.60%,76.11% -3.12%,78.72% -1.80%,81.33% -0.00%,81.33% 0.00%,83.94% -1.80%,86.56% -3.12%,89.17% -3.60%,91.78% -3.12%,94.39% -1.80%,97.00% -0.00%,100.00% 3.00%,101.55% 5.24%,102.68% 7.48%,103.10% 9.71%,102.68% 11.95%,101.55% 14.19%,100.00% 16.43%,100.00% 16.43%,101.55% 18.67%,102.68% 20.90%,103.10% 23.14%,102.68% 25.38%,101.55% 27.62%,100.00% 29.86%,100.00% 29.86%,101.55% 32.10%,102.68% 34.33%,103.10% 36.57%,102.68% 38.81%,101.55% 41.05%,100.00% 43.29%,100.00% 43.29%,101.55% 45.52%,102.68% 47.76%,103.10% 50.00%,102.68% 52.24%,101.55% 54.48%,100.00% 56.71%,100.00% 56.71%,101.55% 58.95%,102.68% 61.19%,103.10% 63.43%,102.68% 65.67%,101.55% 67.90%,100.00% 70.14%,100.00% 70.14%,101.55% 72.38%,102.68% 74.62%,103.10% 76.86%,102.68% 79.10%,101.55% 81.33%,100.00% 83.57%,100.00% 83.57%,101.55% 85.81%,102.68% 88.05%,103.10% 90.29%,102.68% 92.52%,101.55% 94.76%,100.00% 97.00%,97.00% 100.00%,94.39% 101.80%,91.78% 103.12%,89.17% 103.60%,86.56% 103.12%,83.94% 101.80%,81.33% 100.00%,81.33% 100.00%,78.72% 101.80%,76.11% 103.12%,73.50% 103.60%,70.89% 103.12%,68.28% 101.80%,65.67% 100.00%,65.67% 100.00%,63.06% 101.80%,60.44% 103.12%,57.83% 103.60%,55.22% 103.12%,52.61% 101.80%,50.00% 100.00%,50.00% 100.00%,47.39% 101.80%,44.78% 103.12%,42.17% 103.60%,39.56% 103.12%,36.94% 101.80%,34.33% 100.00%,34.33% 100.00%,31.72% 101.80%,29.11% 103.12%,26.50% 103.60%,23.89% 103.12%,21.28% 101.80%,18.67% 100.00%,18.67% 100.00%,16.06% 101.80%,13.44% 103.12%,10.83% 103.60%,8.22% 103.12%,5.61% 101.80%,3.00% 100.00%,0.00% 97.00%,-1.55% 94.76%,-2.68% 92.52%,-3.10% 90.29%,-2.68% 88.05%,-1.55% 85.81%,-0.00% 83.57%,0.00% 83.57%,-1.55% 81.33%,-2.68% 79.10%,-3.10% 76.86%,-2.68% 74.62%,-1.55% 72.38%,-0.00% 70.14%,0.00% 70.14%,-1.55% 67.90%,-2.68% 65.67%,-3.10% 63.43%,-2.68% 61.19%,-1.55% 58.95%,-0.00% 56.71%,0.00% 56.71%,-1.55% 54.48%,-2.68% 52.24%,-3.10% 50.00%,-2.68% 47.76%,-1.55% 45.52%,-0.00% 43.29%,0.00% 43.29%,-1.55% 41.05%,-2.68% 38.81%,-3.10% 36.57%,-2.68% 34.33%,-1.55% 32.10%,-0.00% 29.86%,0.00% 29.86%,-1.55% 27.62%,-2.68% 25.38%,-3.10% 23.14%,-2.68% 20.90%,-1.55% 18.67%,-0.00% 16.43%,0.00% 16.43%,-1.55% 14.19%,-2.68% 11.95%,-3.10% 9.71%,-2.68% 7.48%,-1.55% 5.24%,-0.00% 3.00%);
    box-shadow: 0 8px 18px rgba(0,0,0,0.35);
  }
  .ribbon.ribbon-blue { background: linear-gradient(135deg, var(--blue), var(--blue-dark)); }

  .star {
    position: absolute; color: var(--gold); font-size: 22px;
    filter: drop-shadow(0 2px 3px rgba(0,0,0,0.4));
    animation: twinkle 3.5s ease-in-out infinite;
  }
  @keyframes twinkle {
    0%,100% { opacity:0.5; transform:scale(0.9) rotate(0deg); }
    50% { opacity:1; transform:scale(1.15) rotate(12deg); }
  }

  /* HERO */
  .hero {
    position: relative; min-height: 92vh;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    text-align: center; padding: 60px 6vw 80px;
  }
  .hero::before {
    content: ""; position: absolute; inset: 0;
    background: radial-gradient(circle at 20% 20%, rgba(76,47,122,0.35), transparent 45%),
                radial-gradient(circle at 80% 75%, rgba(227,189,93,0.12), transparent 40%);
    pointer-events: none;
  }
  
  /* Floating Ornaments — absolute, menempel pada body sehingga bergeser saat discroll */
  .global-ornaments-wrapper {
    position: absolute; top: 0; left: 0; right: 0; bottom: 0;
    z-index: 1; pointer-events: none;
    overflow: hidden;
  }
  .dept-ornament-global {
    position: absolute;
    color: var(--gold);
    opacity: 0.15;
    pointer-events: none;
    z-index: 1; /* Pastikan di belakang card yang z-index: 15 */
    transition: opacity 0.3s;
    width: 150px; height: 150px;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .dept-ornament-global svg {
    width: 100%; height: 100%;
    max-width: 90px; max-height: 90px;
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.25));
  }
  /* Ornament lama di hero — tetap didukung */
  .dept-ornament {
    position: absolute;
    width: 60px; height: 60px;
    color: var(--gold);
    opacity: 0.15;
    pointer-events: none;
    z-index: 0;
  }
  .dept-ornament svg {
    width: 100%; height: 100%;
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
  }
  .eyebrow-tape {
    background: var(--white-tape); color: var(--ink);
    font-family: 'JetBrains Mono', monospace; font-size: 0.72rem;
    letter-spacing: 0.15em; text-transform: uppercase;
    padding: 6px 16px; transform: rotate(-2deg);
    box-shadow: 0 3px 8px rgba(0,0,0,0.3); margin-bottom: 26px;
  }
  .hero h1 {
    font-family: {!! $themeHeadingFont !!}; font-weight: 700;
    font-size: clamp(2rem, 5.5vw, 4rem); line-height: 1.1;
    color: var(--white-tape); text-shadow: 0 6px 0 rgba(0,0,0,0.25);
    transform: rotate(-2deg);
  }
  .hero .subtitle-ribbon { margin-top: 6px; font-size: clamp(1.4rem, 3vw, 2rem); }
  .hero p.lede {
    max-width: 560px; margin-top: 30px; font-size: 1rem; line-height: 1.7; color: var(--cream-dark);
  }
  .stamp {
    position: absolute; top: 14%; right: 8%;
    width: 110px; height: 110px; border: 3px dashed var(--blue); border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: var(--blue); font-family: 'JetBrains Mono', monospace; font-size: 0.62rem;
    text-align: center; letter-spacing: 0.08em; transform: rotate(14deg); opacity: 0.9;
  }
  .polaroid-stack { display: flex; justify-content: center; position: relative; margin-top: 64px; width: 100%; height: 480px; max-width: 95vw; z-index: 2; }
  .polaroid {
    position: absolute; width: 480px; max-width: 85vw; background: var(--white-tape);
    padding: 20px 20px 56px; box-shadow: 0 14px 35px rgba(0,0,0,0.3);
    border: 1px solid rgba(255,255,255,0.1); border-radius: 4px;
    transition: all .4s cubic-bezier(0.34, 1.56, 0.64, 1); cursor: pointer;
  }
  .polaroid .frame-img {
    width: 100%; height: 280px; background: linear-gradient(135deg, var(--navy), var(--navy-light));
    display: flex; align-items: center; justify-content: center; overflow: hidden;
    font-family: 'JetBrains Mono', monospace; color: var(--gold); font-size: 0.85rem; letter-spacing: 0.08em;
  }
  .polaroid .frame-img img {
    width: 100%; height: 100%; object-fit: cover; object-position: center; display: block;
  }
  .polaroid .cap {
    position: absolute; bottom: 16px; left: 0; width: 100%; text-align: center;
    color: var(--ink); font-family: 'Caveat', cursive; font-size: 1.6rem;
  }
  .p1 { transform: rotate(-5deg) translateX(-50px); z-index: 1; }
  .p2 { transform: rotate(4deg) translateX(50px) translateY(15px); z-index: 2; }
  
  .polaroid:hover {
    z-index: 10 !important;
    transform: rotate(0deg) scale(1.08) translateY(-10px) !important;
    box-shadow: 0 25px 45px rgba(0,0,0,0.45);
  }
  
  .scroll-cue {
    margin-top: 60px; font-family: 'JetBrains Mono', monospace; font-size: 0.7rem;
    letter-spacing: 0.2em; text-transform: uppercase; color: var(--cream-dark); opacity: 0.7;
  }

  /* SECTION SHELL */
  section { position: relative; padding: 100px 6vw; z-index: 10; pointer-events: none; }
  section > * { pointer-events: auto; } /* Kembalikan event klik untuk konten dalam section */
  .section-head { display: flex; align-items: center; gap: 18px; margin-bottom: 56px; flex-wrap: wrap; }
  .section-head .ribbon { font-family: 'Caveat', cursive; font-size: 2rem; font-weight: 700; }
  .section-head .rule { flex: 1; height: 0; border-top: 2px dashed rgba(244,236,216,0.35); min-width: 80px; }

  /* ABOUT */
  .about-wrap { display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 56px; align-items: center; }
  .cardstock {
    background: var(--cream); color: var(--ink); padding: 38px 34px; position: relative;
    box-shadow: 0 16px 30px rgba(0,0,0,0.35); transform: rotate(-1deg);
    z-index: 15;
  }
  .cardstock::before {
    content: ""; position: absolute; top: -14px; left: 36px; width: 70px; height: 26px;
    background: rgba(227,189,93,0.55); transform: rotate(-4deg); box-shadow: 0 3px 6px rgba(0,0,0,0.2);
  }
  .cardstock h3 { font-family: {!! $themeHeadingFont !!}; font-size: 2rem; margin-bottom: 14px; color: var(--purple-dark); }
  .cardstock p { font-size: 0.95rem; line-height: 1.8; color: #3a2f22; }
  .stat-row { display: flex; gap: 26px; margin-top: 26px; flex-wrap: wrap; }
  .stat { font-family: 'JetBrains Mono', monospace; }
  .stat .num { font-size: 1.7rem; color: var(--purple-dark); font-weight: 600; }
  .stat .lbl { font-size: 0.68rem; letter-spacing: 0.1em; text-transform: uppercase; color: #6a5c47; }

  .about-visual { display: flex; flex-direction: column; gap: 18px; }
  .washi {
    background: repeating-linear-gradient(45deg, var(--purple), var(--purple) 6px, var(--purple-dark) 6px, var(--purple-dark) 12px);
    height: 26px; width: 78%; align-self: flex-end; opacity: 0.85; box-shadow: 0 4px 8px rgba(0,0,0,0.25);
  }
  .washi.washi-blue {
    background: repeating-linear-gradient(45deg, var(--blue), var(--blue) 6px, var(--blue-dark) 6px, var(--blue-dark) 12px);
    width: 52%; align-self: flex-start;
  }
  .proker-card {
    background: var(--navy-light); border: 2px solid var(--gold); padding: 22px 22px 8px;
    color: var(--cream); transform: rotate(1.5deg); box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    z-index: 15;
  }
  .proker-card .id-title {
    color: var(--gold); letter-spacing: 0.12em; text-transform: uppercase; font-size: 0.68rem;
    font-family: 'JetBrains Mono', monospace; border-bottom: 1px dashed rgba(227,189,93,0.4);
    padding-bottom: 8px; margin-bottom: 14px;
  }
  .proker-item {
    display: flex; gap: 12px; align-items: baseline; padding-bottom: 14px; margin-bottom: 14px;
    border-bottom: 1px dashed rgba(244,236,216,0.18);
  }
  .proker-item:last-child { border-bottom: none; }
  .proker-item .proker-no { font-family: 'Caveat', cursive; font-size: 1.5rem; color: var(--gold); line-height: 1; }
  .proker-item .proker-body .proker-name { font-weight: 600; font-size: 0.85rem; color: var(--white-tape); }
  .proker-item .proker-body .proker-desc { font-family: 'JetBrains Mono', monospace; font-size: 0.65rem; color: var(--cream-dark); margin-top: 3px; line-height: 1.5; }

  /* SOROTAN (spotlight card besar) */
  .spotlight-card {
    position: relative; display: flex; align-items: stretch; gap: 0;
    max-width: 900px; margin: 0 auto;
    z-index: 15;
    min-height: 250px;
    align-items: flex-end; /* Memastikan konten bersandar di bawah */
  }
  
  /* Pseudo-elemen sebagai background card yang memiliki radius */
  .spotlight-card::before {
    content: ""; position: absolute; left: 0; right: 0; bottom: 0;
    height: 250px; /* Tinggi dikunci 250px di bawah, agar atasnya bolong (pop out) */
    background: linear-gradient(120deg, var(--cream) 0%, var(--cream-dark) 100%);
    border-radius: 22px;
    box-shadow: 0 18px 40px rgba(0,0,0,0.35);
    z-index: -1;
  }
  .spotlight-left {
    flex: 1.1; padding: 24px 36px; display: flex; flex-direction: column; justify-content: center;
    gap: 6px; z-index: 2; color: var(--ink);
    height: 250px; /* Selaras dengan background kotak */
  }
  .spotlight-left .eyebrow-tape { margin-bottom: 18px; }
  .spotlight-nama {
    font-family: {!! $themeHeadingFont !!}; font-size: 1.9rem; font-weight: 700;
    color: var(--purple-dark); transition: opacity 0.2s ease;
  }
  .spotlight-jabatan {
    font-family: 'JetBrains Mono', monospace; font-size: 0.78rem; letter-spacing: 0.08em;
    text-transform: uppercase; color: var(--blue-dark); font-weight: 600; transition: opacity 0.2s ease;
  }
  .spotlight-desc { font-size: 0.88rem; line-height: 1.7; color: #4a3f30; max-width: 320px; margin-top: 6px; }
  .spotlight-btn {
    margin-top: 16px; align-self: flex-start; border: none; background: var(--navy); color: var(--cream);
    font-family: 'JetBrains Mono', monospace; font-size: 0.72rem; letter-spacing: 0.06em; text-transform: uppercase;
    padding: 10px 20px; border-radius: 999px; cursor: pointer; transition: transform 0.15s ease, background 0.2s ease;
  }
  .spotlight-btn:hover { background: var(--purple-dark); transform: translateY(-2px); }
  .spotlight-right {
    flex: 1.2; position: relative; display: flex; align-items: flex-end; justify-content: center;
    padding: 0 24px 0;
    /* Dihapus min-height pada kontainer kanan agar ia mengikuti tinggi gambar yang lebih besar */
  }
  .spotlight-orang {
    position: relative; width: 240px; flex-shrink: 0;
    display: flex; flex-direction: column; align-items: center;
    cursor: pointer;
    transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), opacity 0.25s ease;
    transform-origin: bottom center; /* Penting untuk pop-out dari bawah */
    margin-bottom: -1px; /* Rata bawah */
  }
  .spotlight-orang:hover { transform: translateY(-10px) scale(1.05); z-index: 20 !important; opacity: 1 !important; }
  /* so-1: kiri */
  .so-1 { margin-right: -80px; z-index: 2; opacity: 0.80; }
  /* so-2: tengah / depan */
  .so-2 { z-index: 10; }
  /* so-3: kanan */
  .so-3 { margin-left: -80px; z-index: 2; opacity: 0.80; }
  /* Untuk grup 2 orang: keduanya sejajar tanpa overlap */
  .spotlight-group.group-2 .so-1 { margin-right: 0; opacity: 1; }
  .spotlight-group.group-2 .so-2 { z-index: 2; }

  .spotlight-foto {
    width: 250px; height: 420px;
    object-fit: cover; object-position: top center; display: block;
    filter: drop-shadow(0 15px 20px rgba(0,0,0,0.4));
    transition: all 0.3s ease;
    margin-bottom: 0;
  }
  .spotlight-orang.active { transform: translateY(-5px) scale(1.08); z-index: 25 !important; opacity: 1 !important; }
  .spotlight-orang.active .spotlight-foto {
    filter: drop-shadow(0 0 15px rgba(212,175,55,0.70)) drop-shadow(0 18px 25px rgba(0,0,0,0.45));
  }
  .spotlight-svg-fallback {
    width: 250px; height: 420px; display: block;
    filter: drop-shadow(0 10px 18px rgba(0,0,0,0.32));
  }
  .sp-fb-1 .kepala, .sp-fb-1 .badan { fill: var(--blue); }
  .sp-fb-1 .rambut { fill: var(--navy); }
  .sp-fb-2 .kepala, .sp-fb-2 .badan { fill: var(--gold); }
  .sp-fb-2 .rambut { fill: var(--purple-dark); }
  .sp-fb-3 .kepala, .sp-fb-3 .badan { fill: var(--purple); }
  .sp-fb-3 .rambut { fill: var(--navy-light); }
  .sp-fb-bayangan { fill: rgba(0,0,0,0.12); }
  .spotlight-badge { display: none; } /* Badge dihilangkan sesuai request */

  /* Multi-stack connector */
  .sorotan-multi-stack {
    display: flex; flex-direction: column; align-items: stretch; gap: 120px; /* Diperbesar agar ada ruang kosong untuk foto pop-out */
    max-width: 900px; margin: 0 auto;
    padding-top: 50px; /* Beri ruang pop-out untuk card baris pertama */
  }
  .spotlight-card-multi { margin: 0; }


  /* STRUCTURE */
  .org-tree { display: flex; flex-direction: column; align-items: center; gap: 0; }
  .org-node {
    background: var(--white-tape); color: var(--ink); font-family: 'Space Grotesk', sans-serif; font-weight: 600;
    font-size: 0.85rem; padding: 14px 16px; box-shadow: 0 8px 16px rgba(0,0,0,0.3);
    position: relative; text-align: center; display: flex; flex-direction: column; align-items: center;
    width: 180px; min-height: 145px; box-sizing: border-box; justify-content: flex-start;
    z-index: 15;
  }
  .org-node .org-photo {
    position: relative; width: 56px; height: 56px; border-radius: 50%;
    background: linear-gradient(150deg, var(--navy), var(--purple-dark));
    display: flex; align-items: center; justify-content: center; margin-bottom: 12px;
    border: 2px solid var(--gold); box-shadow: 0 4px 8px rgba(0,0,0,0.3); overflow: hidden;
    font-family: 'Space Grotesk', sans-serif; color: #fff; font-size: 18px; flex-shrink: 0;
  }
  .org-node .org-photo img { width: 100%; height: 100%; object-fit: cover; object-position: top center; }
  .org-node small {
    display: block; font-family: 'JetBrains Mono', monospace; font-weight: 400; font-size: 0.62rem;
    letter-spacing: 0.05em; color: var(--purple-dark); text-transform: uppercase; margin-bottom: 4px;
    line-height: 1.3;
  }
  .org-node span.nama {
    line-height: 1.2; word-wrap: break-word; overflow-wrap: break-word;
  }
  .connector { width: 2px; height: 26px; background: repeating-linear-gradient(var(--gold) 0 6px, transparent 6px 12px); }
  .org-row-top { display: flex; justify-content: center; }
  .org-row2 { display: flex; gap: 34px; margin-top: 0; flex-wrap: wrap; justify-content: center; }
  .org-branch { display: flex; flex-direction: column; align-items: center; }
  .org-row3 { display: flex; gap: 20px; margin-top: 26px; flex-wrap: wrap; justify-content: center; }
  
  .div-card { 
    background: var(--navy-light); border: 1.5px dashed var(--gold); padding: 16px 20px; 
    min-width: 170px; text-align: center; position: relative; z-index: 15;
  }
  .div-card.blue-accent { border-color: var(--blue); }
  .div-card.blue-accent .div-title { color: var(--blue); }
  .div-card .div-title { font-family: {!! $themeHeadingFont !!}; font-size: 1.35rem; color: var(--gold); font-weight: 600; }
  .div-card .div-people { font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; color: var(--cream-dark); margin-top: 6px; line-height: 1.6; }

  /* MEMBERS (Stacked Cards) */
  .team-top-row { display: flex; justify-content: center; gap: 34px; flex-wrap: wrap; margin-bottom: 70px; }
  .team-card-wrapper {
    position: relative; isolation: isolate; cursor: pointer;
    width: 100%; max-width: 360px; min-width: 0; margin: 0 auto;
  }
  .team-top-row .team-card-wrapper { width: calc(33.333% - 23px); min-width: 280px; }
  .team-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 64px 34px; max-width: 1180px; margin: 0 auto; }
  .team-grid .team-card-wrapper:nth-child(3n+2) { margin-top: 22px; }

  .team-card {
    position: relative; background: var(--white-tape); border-radius: 22px;
    padding: 22px 20px 30px; box-shadow: 0 22px 40px rgba(0,0,0,0.42);
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    z-index: 15; /* Diperkuat supaya card tidak tertembus ornament */
  }
  
  .team-card.shadow-card {
    position: absolute; inset: 0; z-index: -1; pointer-events: none;
    transform: rotate(-3deg) translate(-5px, 6px);
    background: var(--cream-dark);
  }
  .team-card.shadow-card .avatar-tile { filter: grayscale(40%) brightness(0.8); }
  
  .team-card-wrapper:hover .team-card.main-card {
    transform: translateY(-8px); box-shadow: 0 30px 50px rgba(0,0,0,0.5);
  }
  .team-card-wrapper:hover .team-card.shadow-card {
    transform: rotate(-8deg) translate(-18px, 10px);
    background: var(--cream-dark); box-shadow: 0 15px 25px rgba(0,0,0,0.3);
  }
  
  .team-card:nth-child(3n+2) { margin-top: 22px; }

  .team-card-head { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
  .team-card-head .dot { width: 8px; height: 8px; border-radius: 50%; background: var(--gold); flex-shrink: 0; }
  .team-card-head .tag { font-family: 'JetBrains Mono', monospace; font-size: 0.6rem; letter-spacing: 0.16em; text-transform: uppercase; color: #7a6a4f; }
  .team-card-head .rule { flex: 1; height: 1px; background: rgba(36,26,18,0.15); }

  .team-card-title {
    font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.2rem;
    line-height: 1.15; letter-spacing: 0.01em; color: var(--navy); margin-bottom: 18px; min-height: 2.4em;
  }
  
  .team-photos.single .avatar-tile { width: 100%; aspect-ratio: 3/4; }
  .avatar-tile {
    position: relative; border-radius: 14px; overflow: hidden;
    background: linear-gradient(150deg, var(--navy), var(--purple-dark)); box-shadow: 0 6px 14px rgba(0,0,0,0.3);
  }
  .avatar-tile img { width: 100%; height: 100%; object-fit: cover; object-position: top center; display: block; }
  .avatar-tile .init {
    position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
    font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 3rem; color: rgba(244,236,216,0.85);
  }
  .avatar-tile .name-strip {
    position: absolute; left: 0; right: 0; bottom: 0; padding: 14px 8px 10px;
    background: linear-gradient(to top, rgba(15,15,20,0.85), rgba(15,15,20,0.35) 60%, transparent);
    font-family: 'Caveat', cursive; font-weight: 600; font-size: 1.3rem; line-height: 1.1;
    color: var(--white-tape); text-align: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  }

  .team-label {
    position: absolute; left: 50%; bottom: -16px; transform: translateX(-50%);
    display: flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, var(--navy), var(--navy-light)); color: var(--cream);
    font-family: 'JetBrains Mono', monospace; font-size: 0.62rem; letter-spacing: 0.1em;
    text-transform: uppercase; padding: 9px 20px; border-radius: 999px; white-space: nowrap;
    box-shadow: 0 8px 16px rgba(0,0,0,0.4); border: 1px solid rgba(227,189,93,0.4);
  }
  .team-label::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: var(--gold); flex-shrink: 0; }



  /* RESPONSIVE */
  @media (max-width: 980px) {
    .team-grid { grid-template-columns: repeat(2, 1fr); }
    .about-wrap { grid-template-columns: 1fr; }
    .stamp { display: none; }
  }

  @media (max-width: 720px) {
    .spotlight-card { flex-direction: column; }
    .spotlight-right { padding-top: 0; padding-bottom: 24px; }
    .spotlight-desc { max-width: 100%; }
  }
  @media (max-width: 560px) {
    .team-top-row { gap: 24px; }
    .team-top-row .team-card { width: 100%; max-width: 360px; min-width: unset; }
    .team-grid { grid-template-columns: 1fr; max-width: 360px; margin: 0 auto; }
    .team-card:nth-child(3n+2) { margin-top: 0; }
    .polaroid-stack { width: 280px; height: 280px; }
    .polaroid .frame-img { height: 180px; }
  }

</style>
@endsection

@section('body-ornaments')
@if(isset($dept['ornaments']) && count($dept['ornaments']) > 0)
  <div class="global-ornaments-wrapper" style="position: absolute; left: 0; right: 0; top: 0; bottom: 0; pointer-events: none; z-index: 1; overflow: hidden;">
  @php
    $ornaments = $dept['ornaments'];
    $count = count($ornaments);
    // Distribusi merata: kita pecah jadi array kiri dan kanan agar tidak menumpuk
    // dan posisinya diset masuk ke area sedikit tengah agar kelihatan (bukan di ujung)
    $totalPerSide = 8; // 8 di kiri, 8 di kanan (Total 16)
  @endphp
  
  {{-- Tambahan Ekstra Khusus Bagian Atas (Hero Area) dengan Posisi Fix agar rapi dan proporsional --}}
  @php
    // Daftar koordinat (top, letak kiri/kanan, posisi X, rotasi, dan skala)
    $heroPositions = [
        ['top' => 3,  'side' => 'left',  'x' => 12, 'rotate' => -15, 'scale' => 1.0],
        ['top' => 13, 'side' => 'left',  'x' => 5,  'rotate' => 20,  'scale' => 0.85],
        ['top' => 8,  'side' => 'left',  'x' => 25, 'rotate' => 10,  'scale' => 1.1],
        ['top' => 4,  'side' => 'right', 'x' => 10, 'rotate' => -20, 'scale' => 0.95],
        ['top' => 14, 'side' => 'right', 'x' => 6,  'rotate' => 15,  'scale' => 1.0],
        ['top' => 9,  'side' => 'right', 'x' => 24, 'rotate' => -10, 'scale' => 1.15],
    ];
  @endphp
  
  @foreach($heroPositions as $idx => $hp)
    @php
      $ornExtra = $ornaments[($idx + 3) % $count];
      $sideStyle = $hp['side'] . ': ' . $hp['x'] . '%;';
    @endphp
    <div class="dept-ornament-global" style="top: {{ $hp['top'] }}%; {{ $sideStyle }} transform: rotate({{ $hp['rotate'] }}deg) scale({{ $hp['scale'] }});">
      {!! $ornExtra['svg'] !!}
    </div>
  @endforeach

  {{-- Distribusi Utama dari atas ke bawah --}}
  @for($i = 0; $i < $totalPerSide; $i++)
    {{-- Sisi Kiri --}}
    @php
      $ornLeft = $ornaments[($i * 2) % $count];
      // Bagi tinggi halaman jadi 8 bagian, mulai dari 20% ke bawah (karena 0-18% sudah diisi khusus Hero)
      $baseTopLeft = 20 + ($i * (75 / $totalPerSide)) + rand(1, 4); 
      // Jarak dari pinggir kiri antara 3% sampai 15% (agar masuk ke wilayah yang terlihat)
      $xPosLeft = rand(3, 15); 
      $rotateLeft = rand(-45, 45);
      $scaleLeft = rand(8, 12) / 10;
    @endphp
    <div class="dept-ornament-global" style="top: {{ $baseTopLeft }}%; left: {{ $xPosLeft }}%; transform: rotate({{ $rotateLeft }}deg) scale({{ $scaleLeft }});">
      {!! $ornLeft['svg'] !!}
    </div>

    {{-- Sisi Kanan --}}
    @php
      $ornRight = $ornaments[($i * 2 + 1) % $count];
      // Offset sedikit berbeda dari kiri agar terlihat asimetris
      $baseTopRight = 20 + ($i * (75 / $totalPerSide)) + rand(3, 7);
      // Jarak dari pinggir kanan
      $xPosRight = rand(3, 15);
      $rotateRight = rand(-45, 45);
      $scaleRight = rand(8, 12) / 10;
    @endphp
    <div class="dept-ornament-global" style="top: {{ $baseTopRight }}%; right: {{ $xPosRight }}%; transform: rotate({{ $rotateRight }}deg) scale({{ $scaleRight }});">
      {!! $ornRight['svg'] !!}
    </div>
  @endfor
  </div>
@endif
@endsection

@section('content')

@php
  function getInitials($name){
    $w = explode(' ', $name);
    return strtoupper(substr($w[0],0,1).(isset($w[1]) ? substr($w[1],0,1) : ''));
  }
@endphp

<!-- HERO -->
<section class="hero">

  @if(!isset($dept['ornaments']))
    {{-- Default Bintang (fallback) --}}
    <span class="star" style="top:12%; left:10%;">★</span>
    <span class="star" style="top:70%; left:6%; animation-delay:1s;">★</span>
    <span class="star" style="top:20%; right:22%; animation-delay:2s;">★</span>
  @endif
  <br>
  <br>
  <div class="stamp">DEPT.<br>{{ $dept['singkatan'] }}<br>— UNESA —</div>

  <div class="eyebrow-tape">Dokumentasi & Struktur Kepengurusan</div>
  <h1>Welcome to<br>{{ $dept['nama'] }}</h1>
  <br>
  <br>

  <div class="subtitle-ribbon ribbon">Kabinet Kolaborasi Asa</div>
  <p class="lede">{{ $dept['deskripsi'] }}</p>

  <div class="polaroid-stack">
    <div class="polaroid p1">
      <div class="frame-img"><img src="{{ $dept['doc_image_1'] ?? ($dept['image'] ?? asset('images/logo_format.png')) }}" alt="Rapat Kerja"></div>
      <div class="cap">Rapat Kerja #01</div>
    </div>
    <div class="polaroid p2">
      <div class="frame-img"><img src="{{ $dept['doc_image_2'] ?? ($dept['image'] ?? asset('images/logo_format.png')) }}" alt="Foto Tim"></div>
      <div class="cap">{{ strtoupper($dept['singkatan']) }}, {{ $dept['periode'] ?? '2026/2027' }}</div>
    </div>
  </div>

  <div class="scroll-cue">↓ gulir untuk lihat profil ↓</div>
</section>

<!-- ABOUT -->
<section id="about">
  <div class="section-head">
    <div class="ribbon">Profil Departemen</div>
    <div class="rule"></div>
  </div>

  <div class="about-wrap">
    <div class="cardstock">
      <h3>Tentang {{ $dept['singkatan'] }}</h3>
      <p>{{ $dept['deskripsi'] }}</p>
      <div class="stat-row">
        <div class="stat"><div class="num">{{ count($dept['anggota']) }}</div><div class="lbl">Anggota Aktif</div></div>
        <div class="stat"><div class="num">{{ count($dept['proker']) }}</div><div class="lbl">Program Kerja</div></div>
        <div class="stat"><div class="num">{{ $dept['periode'] ?? '2026/2027' }}</div><div class="lbl">Periode Kepengurusan</div></div>
      </div>
    </div>

    <div class="about-visual">
      <div class="washi"></div>
      <div class="washi washi-blue"></div>
      <div class="proker-card">
        <div class="id-title">Program Kerja Departemen</div>

        @foreach($dept['proker'] as $p)
        <div class="proker-item">
          <div class="proker-no">{{ $p['no'] }}</div>
          <div class="proker-body">
            <div class="proker-name">{{ $p['nama'] }}</div>
            <div class="proker-desc">{{ $p['desc'] }}</div>
          </div>
        </div>
        @endforeach

      </div>
    </div>
  </div>
</section>

<!-- SOROTAN (section baru, gaya kartu-tim.html) -->
<section id="sorotan">
  <div class="section-head">
    <div class="ribbon">Sorotan Pengurus</div>
    <div class="rule"></div>
  </div>

  @php
    $allAnggota  = array_values($dept['anggota']);
    $isBph       = stripos($dept['singkatan'] ?? '', 'BPH') !== false
                || stripos($dept['nama']      ?? '', 'Badan Pengurus Harian') !== false;

    $matchJabatan = function(string $jabatan, array $keywords): bool {
        foreach ($keywords as $kw) {
            if (stripos($jabatan, $kw) !== false) return true;
        }
        return false;
    };

    if ($isBph && count($allAnggota) > 0) {
        /* ── BPH: kelompokkan berdasarkan hierarki jabatan ── */

        // 1. Ketua Umum
        $ketum   = collect($allAnggota)->first(fn($a) => $matchJabatan($a['jabatan'] ?? '', ['ketua umum']));
        // 2. Wakil Ketua Umum
        $waketum = collect($allAnggota)->first(fn($a) => $matchJabatan($a['jabatan'] ?? '', ['wakil ketua', 'waketum']));
        // 3. Bendahara Umum
        $bendum  = collect($allAnggota)->first(fn($a) => $matchJabatan($a['jabatan'] ?? '', ['bendahara umum']));
        // 4. Sekretaris Umum
        $sekum   = collect($allAnggota)->first(fn($a) => $matchJabatan($a['jabatan'] ?? '', ['sekretaris umum']));

        // Kumpulkan yang sudah dipick
        $picked  = array_filter([$ketum, $waketum, $bendum, $sekum]);
        // Sisa anggota
        $sisa    = array_values(array_filter($allAnggota, fn($a) => !in_array($a, $picked, true)));

        // Baris 1: Ketum + Waketum (2 orang)
        $group1 = array_values(array_filter([$ketum, $waketum]));
        // Baris 2: Bendum + Sekum (2 orang)
        $group2 = array_values(array_filter([$bendum, $sekum]));
        // Baris 3+: sisa BPH per 2 orang
        $sisaGroups = [];
        foreach (array_chunk($sisa, 2) as $chunk) {
            $sisaGroups[] = array_values($chunk);
        }

        $sorotanGroups = array_values(array_filter(
            array_merge([$group1, $group2], $sisaGroups),
            fn($g) => count($g) > 0
        ));

    } else {
        /* ── Non-BPH: Ketua+Wakil di baris pertama, staf per 3 ── */
        $ketuaEl = collect($allAnggota)->first(fn($a) => stripos($a['jabatan'] ?? '', 'ketua') !== false);
        $wakilEl = collect($allAnggota)->first(fn($a) => stripos($a['jabatan'] ?? '', 'wakil') !== false);
        $topPicked = array_filter([$ketuaEl, $wakilEl]);
        $sisa    = array_values(array_filter($allAnggota, fn($a) => !in_array($a, $topPicked, true)));

        $group1 = array_values(array_filter([$ketuaEl, $wakilEl]));
        $sisaGroups = [];
        foreach (array_chunk($sisa, 3) as $chunk) {
            $sisaGroups[] = array_values($chunk);
        }
        $sorotanGroups = array_values(array_filter(
            array_merge([$group1], $sisaGroups),
            fn($g) => count($g) > 0
        ));
    }
  @endphp

  @if(count($sorotanGroups) > 0)
  <div class="sorotan-multi-stack">
    @foreach($sorotanGroups as $gIdx => $group)
    @php
      $cnt    = count($group);
      // 2 orang: orang ke-0 aktif default; 3 orang: orang ke-1 (tengah) aktif default
      $midIdx = ($cnt >= 3) ? 1 : 0;
    <div class="sorotan-multi-connector">
      <div class="sorotan-multi-line"></div>
    </div>
    @endif

    <div class="spotlight-card spotlight-card-multi" id="spotlightCard-{{ $gIdx }}">
      {{-- Panel kiri: info orang aktif --}}
      <div class="spotlight-left">
        <span class="eyebrow-tape">Kenali Kami</span>
        <h3 class="spotlight-nama" id="spotlightNama-{{ $gIdx }}">{{ $defaultS['nama'] }}</h3>
        <p class="spotlight-jabatan" id="spotlightJabatan-{{ $gIdx }}">{{ $defaultS['jabatan'] }}</p>
        <p class="spotlight-desc">{{ $dept['deskripsi'] }}</p>
      </div>

      {{-- Panel kanan: foto-foto grup --}}
      <div class="spotlight-right">
        <div class="spotlight-group group-active{{ $cnt === 2 ? ' group-2' : '' }}"
             style="display:flex; align-items:flex-end; justify-content:center; width:100%;">
          @foreach($group as $pos => $s)
          @php
            $soPos   = $pos + 1;
            $isMid   = ($pos === $midIdx);
            $fbClass = 'sp-fb-' . $soPos;
            $fotoUrl = !empty($s['foto_nobg']) 
                ? (isset($s['is_db']) ? $s['foto_nobg'] : asset('images/'.$s['foto_nobg']))
                : (!empty($s['foto']) ? (isset($s['is_db']) ? $s['foto'] : asset('images/'.$s['foto'])) : '');
          @endphp
          <div class="spotlight-orang so-{{ $soPos }}{{ $isMid ? ' active' : '' }}"
               data-nama="{{ $s['nama'] }}"
               data-jabatan="{{ $s['jabatan'] }}"
               data-card="{{ $gIdx }}"
               role="button" tabindex="0"
               aria-label="Lihat profil {{ $s['nama'] }}">

            @if($fotoUrl)
              <img class="spotlight-foto"
                   src="{{ $fotoUrl }}"
                   alt="Foto {{ $s['nama'] }}"
                   loading="lazy"
                   onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
              <svg class="spotlight-svg-fallback {{ $fbClass }}" style="display:none;"
                   viewBox="0 0 160 220" xmlns="http://www.w3.org/2000/svg">
                <ellipse class="sp-fb-bayangan" cx="80" cy="210" rx="45" ry="8"/>
                <path class="badan" d="M25 220 C25 150 40 120 80 120 C120 120 135 150 135 220 Z"/>
                <circle class="kepala" cx="80" cy="80" r="42"/>
                <path class="rambut" d="M38 80 C38 40 60 22 80 22 C100 22 122 40 122 80 C110 62 95 55 80 55 C65 55 50 62 38 80 Z"/>
              </svg>
            @else
              <svg class="spotlight-svg-fallback {{ $fbClass }}"
                   viewBox="0 0 160 220" xmlns="http://www.w3.org/2000/svg">
                <ellipse class="sp-fb-bayangan" cx="80" cy="210" rx="45" ry="8"/>
                <path class="badan" d="M25 220 C25 150 40 120 80 120 C120 120 135 150 135 220 Z"/>
                <circle class="kepala" cx="80" cy="80" r="42"/>
                <path class="rambut" d="M38 80 C38 40 60 22 80 22 C100 22 122 40 122 80 C110 62 95 55 80 55 C65 55 50 62 38 80 Z"/>
              </svg>
            @endif

            <div class="spotlight-badge" style="display: none;">{{ Str::limit($s['nama'], 16) }}</div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    @endforeach
  </div>

  <script>
  (function() {
    /* ── Per-card: klik foto update panel kiri card yg sama ── */
    document.querySelectorAll('.spotlight-card-multi').forEach(function(card) {
      var cardId  = card.id.replace('spotlightCard-', '');
      var namaEl  = document.getElementById('spotlightNama-'    + cardId);
      var jabEl   = document.getElementById('spotlightJabatan-' + cardId);
      var oranEl  = card.querySelectorAll('.spotlight-orang');

      if (!namaEl || !jabEl) return;

      function tampilkan(nama, jabatan) {
        namaEl.style.opacity = 0;
        jabEl.style.opacity  = 0;
        setTimeout(function() {
          namaEl.textContent = nama;
          jabEl.textContent  = jabatan;
          namaEl.style.opacity = 1;
          jabEl.style.opacity  = 1;
        }, 180);
      }

      oranEl.forEach(function(o) {
        function activate() {
          oranEl.forEach(function(x) { x.classList.remove('active'); });
          o.classList.add('active');
          tampilkan(o.getAttribute('data-nama'), o.getAttribute('data-jabatan'));
        }
        o.addEventListener('click', activate);
        o.addEventListener('keydown', function(e) {
          if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); activate(); }
        });
      });
    });
  })();
  </script>
  @endif
</section>

<!-- STRUKTUR -->
<section id="struktur">
  <div class="section-head">
    <div class="ribbon ribbon-blue">Struktur Organisasi</div>
    <div class="rule"></div>
  </div>

  <div class="org-tree">
    @php
      $isBPH = str_contains(strtolower($dept['singkatan'] ?? $dept['nama']), 'bph') || str_contains(strtolower($dept['nama']), 'harian');
      
      if ($isBPH) {
          $ketua = collect($dept['anggota'])->first(fn($a) => str_contains(strtolower($a['jabatan']), 'ketua umum') || (str_contains(strtolower($a['jabatan']), 'ketua') && !str_contains(strtolower($a['jabatan']), 'wakil')));
          $wakil = collect($dept['anggota'])->first(fn($a) => str_contains(strtolower($a['jabatan']), 'wakil ketua'));
          
          $sekretarisSemua = collect($dept['anggota'])->filter(fn($a) => str_contains(strtolower($a['jabatan']), 'sekretaris'));
          $sekretarisUmum = $sekretarisSemua->first(fn($a) => str_contains(strtolower($a['jabatan']), 'umum'));
          $sekretarisLain = $sekretarisSemua->filter(fn($a) => !$sekretarisUmum || $a['nama'] !== $sekretarisUmum['nama'])->values();
            
          $bendaharaSemua = collect($dept['anggota'])->filter(fn($a) => str_contains(strtolower($a['jabatan']), 'bendahara'));
          $bendaharaUmum = $bendaharaSemua->first(fn($a) => str_contains(strtolower($a['jabatan']), 'umum'));
          $bendaharaLain = $bendaharaSemua->filter(fn($a) => !$bendaharaUmum || $a['nama'] !== $bendaharaUmum['nama'])->values();

          $others = collect($dept['anggota'])->filter(function($a) use ($ketua, $wakil, $sekretarisSemua, $bendaharaSemua) {
              return (!$ketua || $a['nama'] !== $ketua['nama']) && 
                     (!$wakil || $a['nama'] !== $wakil['nama']) &&
                     !$sekretarisSemua->contains('nama', $a['nama']) &&
                     !$bendaharaSemua->contains('nama', $a['nama']);
          })->values();
      } else {
          $ketua = collect($dept['anggota'])->first(fn($a) => (str_contains(strtolower($a['jabatan']), 'ketua') || str_contains(strtolower($a['jabatan']), 'kepala')) && !str_contains(strtolower($a['jabatan']), 'wakil'));
          $wakil = collect($dept['anggota'])->first(fn($a) => str_contains(strtolower($a['jabatan']), 'wakil'));
          $others = collect($dept['anggota'])->filter(function($a) use ($ketua, $wakil) {
              return (!$ketua || $a['nama'] !== $ketua['nama']) && (!$wakil || $a['nama'] !== $wakil['nama']);
          })->values();
      }
    @endphp

    @if($ketua)
    <div class="org-row-top">
      <div class="org-branch">
        <div class="org-node">
          <div class="org-photo">
            @php $fotoKetua = $ketua['foto_nobg'] ?? $ketua['foto']; @endphp
            @if($fotoKetua) <img src="{{ isset($ketua['is_db']) ? $fotoKetua : asset('images/'.$fotoKetua) }}" alt="{{ $ketua['nama'] }}"> @else {{ getInitials($ketua['nama']) }} @endif
          </div>
          <small>{{ $ketua['jabatan'] }}</small><span class="nama">{{ $ketua['nama'] }}</span>
        </div>
      </div>
    </div>
    <div class="connector"></div>
    @endif

    @if($wakil)
    <div class="org-row-top">
      <div class="org-branch">
        <div class="org-node">
          <div class="org-photo">
            @php $fotoWakil = $wakil['foto_nobg'] ?? $wakil['foto']; @endphp
            @if($fotoWakil) <img src="{{ isset($wakil['is_db']) ? $fotoWakil : asset('images/'.$fotoWakil) }}" alt="{{ $wakil['nama'] }}"> @else {{ getInitials($wakil['nama']) }} @endif
          </div>
          <small>{{ $wakil['jabatan'] }}</small><span class="nama">{{ $wakil['nama'] }}</span>
        </div>
      </div>
    </div>
    <div class="connector"></div>
    @endif
    
    @if(isset($isBPH) && $isBPH && isset($sekretarisUmum) && isset($bendaharaUmum))
    <div class="org-row2" style="display: flex; justify-content: center; gap: 40px; flex-wrap: wrap;">
      @foreach([$sekretarisUmum, $bendaharaUmum] as $l3)
        @if($l3)
        <div class="org-branch">
          <div class="org-node">
            <div class="org-photo">
              @php $fotoL3 = $l3['foto_nobg'] ?? $l3['foto']; @endphp
              @if($fotoL3) <img src="{{ isset($l3['is_db']) ? $fotoL3 : asset('images/'.$fotoL3) }}" alt="{{ $l3['nama'] }}"> @else {{ getInitials($l3['nama']) }} @endif
            </div>
            <small>{{ $l3['jabatan'] }}</small><span class="nama">{{ $l3['nama'] }}</span>
          </div>
        </div>
        @endif
      @endforeach
    </div>
    <div class="connector" style="margin-top: 10px;"></div>
    @endif

    @if(isset($isBPH) && $isBPH && ($sekretarisLain->count() > 0 || $bendaharaLain->count() > 0))
    <div class="org-row2" style="display: flex; justify-content: center; gap: 40px; flex-wrap: wrap;">
      @foreach($sekretarisLain->merge($bendaharaLain) as $l4)
        <div class="org-branch">
          <div class="org-node">
            <div class="org-photo">
              @php $fotoL4 = $l4['foto_nobg'] ?? $l4['foto']; @endphp
              @if($fotoL4) <img src="{{ isset($l4['is_db']) ? $fotoL4 : asset('images/'.$fotoL4) }}" alt="{{ $l4['nama'] }}"> @else {{ getInitials($l4['nama']) }} @endif
            </div>
            <small>{{ $l4['jabatan'] }}</small><span class="nama">{{ $l4['nama'] }}</span>
          </div>
        </div>
      @endforeach
    </div>
    <div class="connector" style="margin-top: 10px;"></div>
    @endif

    @if($others->count() > 0)
    <div class="org-row2" style="margin-top:{{ (isset($isBPH) && $isBPH && (isset($sekretarisUmum) || $sekretarisLain->count() > 0)) ? '0' : '26px' }}; display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
        @foreach($others as $o)
        <div class="org-branch">
            <div class="org-node">
            <div class="org-photo">
                @php $fotoO = $o['foto_nobg'] ?? $o['foto']; @endphp
                @if($fotoO) <img src="{{ isset($o['is_db']) ? $fotoO : asset('images/'.$fotoO) }}" alt="{{ $o['nama'] }}"> @else {{ getInitials($o['nama']) }} @endif
            </div>
            <small>{{ $o['jabatan'] }}</small><span class="nama">{{ $o['nama'] }}</span>
            </div>
        </div>
        @endforeach
    </div>
    @endif

  </div>
</section>

<!-- ANGGOTA -->
<section id="anggota">
  <div class="section-head">
    <div class="ribbon">Tim Kami</div>
    <div class="rule"></div>
  </div>

@php
if (!function_exists('renderTeamCard')) {
  function renderTeamCard($member, $role) {
    if (!$member) return '';
    $isDb = isset($member['is_db']) ? $member['is_db'] : false;
    $foto = $member['foto'];
    $imgSrc = $foto ? ($isDb ? $foto : asset('images/'.$foto)) : '';
    $nama = $member['nama'];
    $nim = $member['nim'] ?? 'N/A';
    $jabatan = $member['jabatan'];
    
    $init = '';
    if (!$foto) {
       $words = explode(' ', $nama);
       $init = strtoupper(substr($words[0], 0, 1));
       if (isset($words[1])) $init .= strtoupper(substr($words[1], 0, 1));
    }
    
    $imgHtml = $foto ? '<img src="'.e($imgSrc).'" alt="'.e($nama).'">' : '<div class="init">'.e($init).'</div>';
    
    return '
    <div class="team-card-wrapper">
      <div class="team-card shadow-card" aria-hidden="true">
        <div class="team-card-head"><span class="dot"></span><span class="tag">'.e($nim).'</span><span class="rule"></span></div>
        <div class="team-card-title">'.e($jabatan).'</div>
        <div class="team-photos single"><div class="avatar-tile">'.$imgHtml.'</div></div>
        <div class="team-label">'.e($role).'</div>
      </div>
      <div class="team-card main-card">
        <div class="team-card-head"><span class="dot"></span><span class="tag">'.e($nim).'</span><span class="rule"></span></div>
        <div class="team-card-title">'.e($jabatan).'</div>
        <div class="team-photos single"><div class="avatar-tile">'.$imgHtml.'<div class="name-strip">'.e($nama).'</div></div></div>
        <div class="team-label">'.e($role).'</div>
      </div>
    </div>';
  }
}
@endphp

  <div class="team-top-row" style="display: flex; justify-content: center; gap: 34px; flex-wrap: wrap; margin-bottom: {{ (isset($isBPH) && $isBPH) ? '34px' : '70px' }};">
    @if($ketua) {!! renderTeamCard($ketua, 'Pimpinan') !!} @endif
    @if($wakil) {!! renderTeamCard($wakil, 'Pimpinan') !!} @endif
  </div>

  @if(isset($isBPH) && $isBPH && (isset($sekretarisUmum) || isset($bendaharaUmum)))
  <div class="team-top-row" style="display: flex; justify-content: center; gap: 34px; flex-wrap: wrap; margin-bottom: {{ ($sekretarisLain->count() > 0 || $bendaharaLain->count() > 0) ? '34px' : '70px' }};">
    @foreach([$sekretarisUmum, $bendaharaUmum] as $l3)
      {!! renderTeamCard($l3, 'Pimpinan') !!}
    @endforeach
  </div>
  @endif

  @if(isset($isBPH) && $isBPH && ($sekretarisLain->count() > 0 || $bendaharaLain->count() > 0))
  <div class="team-top-row" style="display: flex; justify-content: center; gap: 34px; flex-wrap: wrap; margin-bottom: 70px;">
    @foreach($sekretarisLain->merge($bendaharaLain) as $l4)
      {!! renderTeamCard($l4, 'Pimpinan') !!}
    @endforeach
  </div>
  @endif

  <div class="team-grid">
    @foreach($others as $s)
      {!! renderTeamCard($s, 'Staf') !!}
    @endforeach
  </div>
</section>

@endsection

@section('footer-custom')
  <div class="script" style="font-size: 2.2rem; color: var(--gold);">Format R</div>
  <p>DEPARTEMEN {{ strtoupper($dept['nama']) }} · UNIVERSITAS NEGERI SURABAYA</p>
@endsection