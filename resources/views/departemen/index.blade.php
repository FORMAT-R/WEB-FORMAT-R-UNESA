@extends('layouts.app')

@section('title', 'Departemen - FORMAT-R UNESA')

@push('styles')
<style>
  .dept-hero {
    padding: 60px 0 60px;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  .dept-hero::before {
    content: "";
    position: absolute; inset: 0;
    background:
      radial-gradient(ellipse 700px 400px at 20% 50%, rgba(29,93,191,0.10), transparent 65%),
      radial-gradient(ellipse 600px 400px at 80% 30%, rgba(255,199,48,0.07), transparent 60%);
    pointer-events: none;
  }
  .dept-hero h1 {
    font-size: clamp(2.8rem, 6vw, 4.4rem);
    color: var(--navy);
    line-height: 1.06;
    margin-bottom: 20px;
    position: relative;
    font-family: 'Sora', sans-serif;
  }
  body.dark .dept-hero h1 { color: #fff; }
  .dept-hero h1 .hl { color: var(--yellow-deep); position: relative; }
  body.dark .dept-hero h1 .hl { color: var(--yellow); }
  .dept-hero h1 .hl::after {
    content: ""; position: absolute; left: 0; right: 0; bottom: -4px; height: 3px;
    background: var(--yellow); border-radius: 2px;
  }
  .dept-hero .tagline { font-size: 1.08rem; color: var(--ink-soft); max-width: 520px; margin: 0 auto; }

  .dept-section { padding: 0 0 96px; }
  .dept-main-grid {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;
    max-width: 1180px; margin: 0 auto; padding: 0 28px;
  }
  .dept-main-card {
    position: relative; background: var(--cream); border: 1px solid var(--line);
    border-radius: 24px; padding: 40px 28px;
    display: flex; flex-direction: column; align-items: center; text-align: center;
    transition: transform .28s ease, box-shadow .28s ease, border-color .28s ease; overflow: hidden;
  }
  .dept-main-card::before { content: ""; position: absolute; inset: 0; border-radius: 24px; opacity: 0; transition: opacity .28s ease; }
  .dept-main-card:hover { transform: translateY(-8px); box-shadow: 0 24px 48px rgba(11,37,69,0.13); border-color: transparent; }
  .dept-main-card:hover::before { opacity: 1; }
  .dept-main-card[data-color="navy"]   { --card-accent: var(--navy); --card-bg: #EAF1FC; }
  .dept-main-card[data-color="blue"]   { --card-accent: var(--blue); --card-bg: #ddeeff; }
  .dept-main-card[data-color="yellow"] { --card-accent: var(--yellow-deep); --card-bg: #FFF8E0; }
  .dept-main-card[data-color="green"]  { --card-accent: #1a7a4a; --card-bg: #e0f5ea; }
  .dept-main-card[data-color="teal"]   { --card-accent: #0d7377; --card-bg: #e0f4f5; }
  .dept-main-card[data-color="purple"] { --card-accent: #5c35a0; --card-bg: #ede8f8; }
  .dept-main-card::before { background: linear-gradient(135deg, var(--card-bg, #EAF1FC), var(--cream)); }
  .dept-main-icon { position: relative; z-index: 1; width: 68px; height: 68px; border-radius: 20px; background: var(--card-accent, var(--navy)); display: flex; align-items: center; justify-content: center; margin-bottom: 20px; box-shadow: 0 8px 18px rgba(0,0,0,0.15); transition: transform .25s ease; flex-shrink: 0; }
  .dept-main-card:hover .dept-main-icon { transform: scale(1.08) rotate(-3deg); }
  .dept-main-icon svg { color: #fff; width: 32px; height: 32px; }
  .dept-main-abbr { position: relative; z-index: 1; font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; color: var(--card-accent, var(--blue)); margin-bottom: 6px; display: block; font-weight: 600; }
  .dept-main-name { position: relative; z-index: 1; font-family: 'Sora', sans-serif; font-weight: 700; font-size: 1.15rem; color: var(--navy); line-height: 1.3; }
  body.dark .dept-main-name { color: #fff; }
  .dept-main-card::before { background: linear-gradient(135deg, var(--card-bg, #EAF1FC), var(--cream)); }
  .dept-main-card::before { opacity: 0; transition: opacity .28s ease; }
  .dept-main-card:hover::before { opacity: 1; }
  .dept-main-icon { position: relative; z-index: 1; width: 68px; height: 68px; border-radius: 20px; background: var(--card-accent, var(--navy)); display: flex; align-items: center; justify-content: center; margin-bottom: 20px; box-shadow: 0 8px 18px rgba(0,0,0,0.15); transition: transform .25s ease; flex-shrink: 0; }
  .dept-main-card:hover .dept-main-icon { transform: scale(1.08) rotate(-3deg); }
  .dept-main-icon svg { color: #fff; width: 32px; height: 32px; }
  .dept-main-abbr { position: relative; z-index: 1; font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; color: var(--card-accent, var(--blue)); margin-bottom: 6px; display: block; font-weight: 600; }
  .dept-main-name { position: relative; z-index: 1; font-family: 'Sora', sans-serif; font-weight: 700; font-size: 1.15rem; color: var(--navy); line-height: 1.3; }
  body.dark .dept-main-name { color: #fff; }
  .dept-main-card::before { background: linear-gradient(135deg, var(--card-bg, #EAF1FC), var(--cream)); }
  .reveal { opacity: 0; transform: translateY(28px); transition: opacity .7s ease, transform .7s ease; }
  .reveal.visible { opacity: 1; transform: translateY(0); }
  .reveal-stagger.visible .stagger-child { opacity: 1; transform: translateY(0); }
  .stagger-child { opacity: 0; transform: translateY(20px); transition: opacity .5s ease, transform .5s ease; }
  .stagger-child:nth-child(1) { transition-delay: 0s; }
  .stagger-child:nth-child(2) { transition-delay: 0.08s; }
  .stagger-child:nth-child(3) { transition-delay: 0.16s; }
  .stagger-child:nth-child(4) { transition-delay: 0.24s; }
  .stagger-child:nth-child(5) { transition-delay: 0.32s; }
  .stagger-child:nth-child(6) { transition-delay: 0.40s; }
  .stagger-child:nth-child(6) { transition-delay: 0.48s; }
  @media (max-width: 980px) { .dept-main-grid { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 560px) { .dept-main-grid { grid-template-columns: 1fr; } .dept-hero h1 { font-size: 2.2rem; } }
</style>
@endpush

@section('content')
{{-- ===== HERO ===== --}}
<section class="dept-hero" id="home">
    <div class="container">
        @php
            $cabinetName = get_setting('cabinetName', 'Kolaborasi Asa');
            $words = explode(' ', $cabinetName);
            $firstWord = array_shift($words);
            $rest = implode(' ', $words);
        @endphp
        <h1>{{ $firstWord }} @if($rest)<span class="hl">{{ $rest }}</span>@endif</h1>
        <p class="tagline">
            BPH dan enam departemen, satu tujuan. Bersama kami membangun mahasiswa UNESA yang berdaya, berkarakter, dan berdampak.
        </p>
    </div>
</section>

{{-- ===== GRID DEPARTEMEN ===== --}}
<section class="dept-section">
    <div class="dept-main-grid reveal-stagger reveal">
        @php
        $icons = [
            'bph'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M6 21V8l6-5 6 5v13M9 21v-6h6v6"/></svg>',
            'kominfo'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.15 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.07 2H6a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
            'penlar'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>',
            'kwu'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>',
            'kerohanian' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
            'minba'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
            'posdm'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        ];
        @endphp

        @foreach($departemen as $dept)
        <a href="{{ route('departemen.show', $dept['slug']) }}"
           class="dept-main-card stagger-child"
           data-color="{{ $dept['warna'] }}">
            {{-- Ikon --}}
            <div class="dept-main-icon" style="padding: {{ !empty($dept['image']) ? '0' : '15px' }}; overflow: hidden;">
                @if(!empty($dept['image']))
                    <img src="{{ $dept['image'] }}" alt="{{ $dept['nama'] }}" style="width:100%;height:100%;object-fit:cover;border-radius:18px;">
                @else
                    {!! $icons[$dept['slug']] ?? $icons['bph'] !!}
                @endif
            </div>
            {{-- Konten --}}
            <span class="dept-main-abbr">{{ $dept['singkatan'] }}</span>
            <div class="dept-main-name">{{ $dept['nama'] }}</div>
        </a>
        @endforeach
    </div>
</section>
@endsection