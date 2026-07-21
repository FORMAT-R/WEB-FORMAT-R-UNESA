@extends('layouts.dept-base')

@section('theme-fonts')
<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('theme-styles')
<style>
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
    background-image: {!! $dept['bg_pattern'] ?? 'linear-gradient(var(--mat-green-line) 1px, transparent 1px), linear-gradient(90deg, var(--mat-green-line) 1px, transparent 1px)' !!} !important;
    background-size: 28px 28px !important;
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
    text-align: center; padding: 60px 6vw 80px; overflow: hidden;
  }
  .hero::before {
    content: ""; position: absolute; inset: 0;
    background: radial-gradient(circle at 20% 20%, rgba(76,47,122,0.35), transparent 45%),
                radial-gradient(circle at 80% 75%, rgba(227,189,93,0.12), transparent 40%);
    pointer-events: none;
  }
  
  /* Floating Ornaments */
  .dept-ornament {
    position: absolute;
    width: 60px; height: 60px;
    color: var(--gold);
    opacity: 0.15; /* semi transparan menyatu dengan background */
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
    font-family: 'Caveat', cursive; font-weight: 700;
    font-size: clamp(2.5rem, 7.5vw, 5.5rem); line-height: 0.95;
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
  .polaroid-stack { position: relative; margin-top: 64px; width: 520px; height: 360px; max-width: 88vw; }
  .polaroid {
    position: absolute; inset: 0; background: var(--white-tape);
    padding: 22px 22px 54px; box-shadow: 0 20px 38px rgba(0,0,0,0.45);
  }
  .polaroid .frame-img {
    width: 100%; height: 250px; background: linear-gradient(135deg, var(--navy), var(--navy-light));
    display: flex; align-items: center; justify-content: center; overflow: hidden;
    font-family: 'JetBrains Mono', monospace; color: var(--gold); font-size: 0.85rem; letter-spacing: 0.08em;
  }
  .polaroid .frame-img img {
    width: 100%; height: 100%; object-fit: cover; object-position: center; display: block;
  }
  .polaroid .cap {
    color: var(--ink); font-family: 'Caveat', cursive; font-size: 1.5rem; text-align: center; margin-top: 12px;
  }
  .p1 { transform: rotate(-6deg); z-index: 1; }
  .p2 { transform: rotate(4deg) translate(40px,18px); z-index: 2; }
  
  .scroll-cue {
    margin-top: 60px; font-family: 'JetBrains Mono', monospace; font-size: 0.7rem;
    letter-spacing: 0.2em; text-transform: uppercase; color: var(--cream-dark); opacity: 0.7;
  }

  /* SECTION SHELL */
  section { position: relative; padding: 100px 6vw; }
  .section-head { display: flex; align-items: center; gap: 18px; margin-bottom: 56px; flex-wrap: wrap; }
  .section-head .ribbon { font-family: 'Caveat', cursive; font-size: 2rem; font-weight: 700; }
  .section-head .rule { flex: 1; height: 0; border-top: 2px dashed rgba(244,236,216,0.35); min-width: 80px; }

  /* ABOUT */
  .about-wrap { display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 56px; align-items: center; }
  .cardstock {
    background: var(--cream); color: var(--ink); padding: 38px 34px; position: relative;
    box-shadow: 0 16px 30px rgba(0,0,0,0.35); transform: rotate(-1deg);
  }
  .cardstock::before {
    content: ""; position: absolute; top: -14px; left: 36px; width: 70px; height: 26px;
    background: rgba(227,189,93,0.55); transform: rotate(-4deg); box-shadow: 0 3px 6px rgba(0,0,0,0.2);
  }
  .cardstock h3 { font-family: 'Caveat', cursive; font-size: 2rem; margin-bottom: 14px; color: var(--purple-dark); }
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

  /* STRUCTURE */
  .org-tree { display: flex; flex-direction: column; align-items: center; gap: 0; }
  .org-node {
    background: var(--white-tape); color: var(--ink); font-family: 'Space Grotesk', sans-serif; font-weight: 600;
    font-size: 0.85rem; padding: 14px 16px; box-shadow: 0 8px 16px rgba(0,0,0,0.3);
    position: relative; text-align: center; display: flex; flex-direction: column; align-items: center;
    width: 180px; min-height: 145px; box-sizing: border-box; justify-content: flex-start;
  }
  .org-node .org-photo {
    position: relative; width: 56px; height: 56px; border-radius: 50%;
    background: linear-gradient(150deg, var(--navy), var(--purple-dark));
    display: flex; align-items: center; justify-content: center; margin-bottom: 12px;
    border: 2px solid var(--gold); box-shadow: 0 4px 8px rgba(0,0,0,0.3); overflow: hidden;
    font-family: 'Space Grotesk', sans-serif; color: #fff; font-size: 18px; flex-shrink: 0;
  }
  .org-node .org-photo img { width: 100%; height: 100%; object-fit: cover; }
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
  
  .div-card { background: var(--navy-light); border: 1.5px dashed var(--gold); padding: 16px 20px; min-width: 170px; text-align: center; }
  .div-card.blue-accent { border-color: var(--blue); }
  .div-card.blue-accent .div-title { color: var(--blue); }
  .div-card .div-title { font-family: 'Caveat', cursive; font-size: 1.35rem; color: var(--gold); }
  .div-card .div-people { font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; color: var(--cream-dark); margin-top: 6px; line-height: 1.6; }

  /* MEMBERS (Stacked Cards) */
  .team-top-row { display: flex; justify-content: center; gap: 34px; flex-wrap: wrap; margin-bottom: 70px; }
  .team-top-row .team-card { width: calc(33.333% - 23px); min-width: 280px; max-width: 360px; }
  .team-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 64px 34px; max-width: 1180px; margin: 0 auto; }
  
  .team-card {
    position: relative; background: var(--white-tape); border-radius: 22px;
    padding: 22px 20px 30px; isolation: isolate; box-shadow: 0 22px 40px rgba(0,0,0,0.42);
  }
  .team-card::before, .team-card::after {
    content: ""; position: absolute; inset: 0; border-radius: 22px; background: var(--white-tape); z-index: -1;
  }
  .team-card::before { transform: rotate(-2.2deg) translate(-3px,5px); background: var(--cream-dark); box-shadow: 0 10px 20px rgba(0,0,0,0.28); }
  .team-card::after { transform: rotate(2.6deg) translate(4px,7px); background: var(--cream); box-shadow: 0 14px 24px rgba(0,0,0,0.3); }
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
  .avatar-tile img { width: 100%; height: 100%; object-fit: cover; display: block; }
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

  .team-grid .team-card:hover { transform: translateY(-4px); transition: transform .25s ease; }

  /* RESPONSIVE */
  @media (max-width: 980px) {
    .team-grid { grid-template-columns: repeat(2, 1fr); }
    .about-wrap { grid-template-columns: 1fr; }
    .stamp { display: none; }
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

@section('content')

@php
  function getInitials($name){
    $w = explode(' ', $name);
    return strtoupper(substr($w[0],0,1).(isset($w[1]) ? substr($w[1],0,1) : ''));
  }
@endphp

<!-- HERO -->
<section class="hero">
  
  {{-- Render Floating Ornaments jika ada --}}
  @if(isset($dept['ornaments']))
    @foreach($dept['ornaments'] as $ornament)
      <div class="dept-ornament" style="{!! $ornament['pos'] !!}">
        {!! $ornament['svg'] !!}
      </div>
    @endforeach
  @else
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
      <div class="frame-img"><img src="{{ $dept['doc_image_1'] ?? asset('images/IMG_8310.JPG') }}" alt="Rapat Kerja"></div>
      <div class="cap">Rapat Kerja #01</div>
    </div>
    <div class="polaroid p2">
      <div class="frame-img"><img src="{{ $dept['doc_image_2'] ?? asset('images/IMG_8312.JPG') }}" alt="Foto Tim"></div>
      <div class="cap">{{ $dept['singkatan'] }}, 2026</div>
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
        <div class="stat"><div class="num">{{ count($dept['divisi']) }}</div><div class="lbl">Divisi Kerja</div></div>
        <div class="stat"><div class="num">2026</div><div class="lbl">Periode Kepengurusan</div></div>
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
            @if($ketua['foto']) <img src="{{ isset($ketua['is_db']) ? $ketua['foto'] : asset('images/'.$ketua['foto']) }}" alt="{{ $ketua['nama'] }}"> @else {{ getInitials($ketua['nama']) }} @endif
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
            @if($wakil['foto']) <img src="{{ isset($wakil['is_db']) ? $wakil['foto'] : asset('images/'.$wakil['foto']) }}" alt="{{ $wakil['nama'] }}"> @else {{ getInitials($wakil['nama']) }} @endif
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
              @if($l3['foto']) <img src="{{ isset($l3['is_db']) ? $l3['foto'] : asset('images/'.$l3['foto']) }}" alt="{{ $l3['nama'] }}"> @else {{ getInitials($l3['nama']) }} @endif
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
              @if($l4['foto']) <img src="{{ isset($l4['is_db']) ? $l4['foto'] : asset('images/'.$l4['foto']) }}" alt="{{ $l4['nama'] }}"> @else {{ getInitials($l4['nama']) }} @endif
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
                @if($o['foto']) <img src="{{ isset($o['is_db']) ? $o['foto'] : asset('images/'.$o['foto']) }}" alt="{{ $o['nama'] }}"> @else {{ getInitials($o['nama']) }} @endif
            </div>
            <small>{{ $o['jabatan'] }}</small><span class="nama">{{ $o['nama'] }}</span>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <div class="org-row3" style="margin-top:26px;">
      @foreach($dept['divisi'] as $idx => $div)
      <div class="div-card {{ $idx % 2 != 0 ? 'blue-accent' : '' }}">
        <div class="div-title">{{ $div['nama'] }}</div>
        <div class="div-people">{!! implode('<br>', $div['anggota']) !!}</div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ANGGOTA -->
<section id="anggota">
  <div class="section-head">
    <div class="ribbon">Tim Kami</div>
    <div class="rule"></div>
  </div>

  <div class="team-top-row" style="display: flex; justify-content: center; gap: 34px; flex-wrap: wrap; margin-bottom: {{ (isset($isBPH) && $isBPH) ? '34px' : '70px' }};">
    @if($ketua)
    <div class="team-card">
      <div class="team-card-head">
        <span class="dot"></span>
        <span class="tag">{{ $ketua['nim'] ?? 'N/A' }}</span>
        <span class="rule"></span>
      </div>
      <div class="team-card-title">{{ $ketua['jabatan'] }}</div>
      <div class="team-photos single">
        <div class="avatar-tile">
          @if($ketua['foto']) <img src="{{ isset($ketua['is_db']) ? $ketua['foto'] : asset('images/'.$ketua['foto']) }}" alt="{{ $ketua['nama'] }}"> @else <div class="init">{{ getInitials($ketua['nama']) }}</div> @endif
          <div class="name-strip">{{ $ketua['nama'] }}</div>
        </div>
      </div>
      <div class="team-label">Pimpinan</div>
    </div>
    @endif

    @if($wakil)
    <div class="team-card">
      <div class="team-card-head">
        <span class="dot"></span>
        <span class="tag">{{ $wakil['nim'] ?? 'N/A' }}</span>
        <span class="rule"></span>
      </div>
      <div class="team-card-title">{{ $wakil['jabatan'] }}</div>
      <div class="team-photos single">
        <div class="avatar-tile">
          @if($wakil['foto']) <img src="{{ isset($wakil['is_db']) ? $wakil['foto'] : asset('images/'.$wakil['foto']) }}" alt="{{ $wakil['nama'] }}"> @else <div class="init">{{ getInitials($wakil['nama']) }}</div> @endif
          <div class="name-strip">{{ $wakil['nama'] }}</div>
        </div>
      </div>
      <div class="team-label">Pimpinan</div>
    </div>
    @endif
  </div>

  @if(isset($isBPH) && $isBPH && (isset($sekretarisUmum) || isset($bendaharaUmum)))
  <div class="team-top-row" style="display: flex; justify-content: center; gap: 34px; flex-wrap: wrap; margin-bottom: {{ ($sekretarisLain->count() > 0 || $bendaharaLain->count() > 0) ? '34px' : '70px' }};">
    @foreach([$sekretarisUmum, $bendaharaUmum] as $l3)
    @if($l3)
    <div class="team-card">
      <div class="team-card-head">
        <span class="dot"></span>
        <span class="tag">{{ $l3['nim'] ?? 'N/A' }}</span>
        <span class="rule"></span>
      </div>
      <div class="team-card-title">{{ $l3['jabatan'] }}</div>
      <div class="team-photos single">
        <div class="avatar-tile">
          @if($l3['foto']) <img src="{{ isset($l3['is_db']) ? $l3['foto'] : asset('images/'.$l3['foto']) }}" alt="{{ $l3['nama'] }}"> @else <div class="init">{{ getInitials($l3['nama']) }}</div> @endif
          <div class="name-strip">{{ $l3['nama'] }}</div>
        </div>
      </div>
      <div class="team-label">Pimpinan</div>
    </div>
    @endif
    @endforeach
  </div>
  @endif

  @if(isset($isBPH) && $isBPH && ($sekretarisLain->count() > 0 || $bendaharaLain->count() > 0))
  <div class="team-top-row" style="display: flex; justify-content: center; gap: 34px; flex-wrap: wrap; margin-bottom: 70px;">
    @foreach($sekretarisLain->merge($bendaharaLain) as $l4)
    <div class="team-card">
      <div class="team-card-head">
        <span class="dot"></span>
        <span class="tag">{{ $l4['nim'] ?? 'N/A' }}</span>
        <span class="rule"></span>
      </div>
      <div class="team-card-title">{{ $l4['jabatan'] }}</div>
      <div class="team-photos single">
        <div class="avatar-tile">
          @if($l4['foto']) <img src="{{ isset($l4['is_db']) ? $l4['foto'] : asset('images/'.$l4['foto']) }}" alt="{{ $l4['nama'] }}"> @else <div class="init">{{ getInitials($l4['nama']) }}</div> @endif
          <div class="name-strip">{{ $l4['nama'] }}</div>
        </div>
      </div>
      <div class="team-label">Pimpinan</div>
    </div>
    @endforeach
  </div>
  @endif

  <div class="team-grid">
    @foreach($others as $s)
    <div class="team-card">
      <div class="team-card-head">
        <span class="dot"></span>
        <span class="tag">{{ $s['nim'] ?? 'N/A' }}</span>
        <span class="rule"></span>
      </div>
      <div class="team-card-title">{{ $s['jabatan'] }}</div>
      <div class="team-photos single">
        <div class="avatar-tile">
          @if($s['foto']) <img src="{{ isset($s['is_db']) ? $s['foto'] : asset('images/'.$s['foto']) }}" alt="{{ $s['nama'] }}"> @else <div class="init">{{ getInitials($s['nama']) }}</div> @endif
          <div class="name-strip">{{ $s['nama'] }}</div>
        </div>
      </div>
      <div class="team-label">Staf</div>
    </div>
    @endforeach
  </div>
</section>

@endsection

@section('footer-custom')
  <div class="script" style="font-size: 2.2rem; color: var(--gold);">Format R</div>
  <p>DEPARTEMEN {{ strtoupper($dept['nama']) }} · UNIVERSITAS NEGERI SURABAYA</p>
@endsection