<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', ($dept['singkatan'] ?? 'Departemen') . ' — ' . ($dept['nama'] ?? 'FORMAT-R UNESA'))</title>

{{-- Fonts yang selalu dipakai --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

{{-- Theme-specific fonts (seperti Caveat untuk klasik, dsb) akan di-yield di sini --}}
@yield('theme-fonts')

<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  html { scroll-behavior:smooth; }
  a { text-decoration: none; color: inherit; }
  
  /* Reset CSS Variables untuk fallback */
  :root {
    --navy: #1d2a44;
    --blue: #3b6fd1;
    --gold: #e3bd5d;
    --cream: #f4ecd8;
    --cream-dark: #e5d9b8;
    --white-tape: #f7f3e8;
    --line: rgba(0,0,0,0.1);
  }

  /* Navigasi Bawah Umum */
  .dept-nav-section {
    padding: 60px 20px 80px;
    background: var(--cream-dark); 
    border-top: 2px dashed rgba(0,0,0,0.1);
    text-align: center;
  }
  .dept-nav-title {
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 700;
    font-size: 1.8rem;
    color: var(--navy);
    margin-bottom: 30px;
  }
  .dept-nav-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 16px;
    max-width: 1000px;
    margin: 0 auto;
  }
  .dept-nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 24px;
    border-radius: 100px;
    background: var(--white-tape);
    border: 1px solid rgba(0,0,0,0.1);
    color: var(--navy);
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all .2s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  }
  .dept-nav-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
  }
  .dept-nav-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    color: #fff;
  }
  .dept-nav-icon svg { width: 16px; height: 16px; }

  /* Tombol Kembali (Bisa ditimpa oleh tema) */
  .back-link {
    position: absolute; top: 30px; left: 30px; z-index: 50;
    display: inline-flex; align-items: center; gap: 8px;
    color: var(--gold); font-family: 'JetBrains Mono', monospace; font-size: 0.8rem;
    text-transform: uppercase; letter-spacing: 0.1em;
    background: rgba(0,0,0,0.25); padding: 8px 16px; border-radius: 100px;
    backdrop-filter: blur(4px); border: 1px dashed rgba(227,189,93,0.4);
    transition: all .2s ease;
  }
  .back-link:hover { background: rgba(0,0,0,0.4); gap: 12px; }

  /* Footer */
  footer { 
      padding: 60px 6vw 50px; text-align: center; border-top: 2px dashed rgba(0,0,0,0.1);
      background: var(--cream); color: var(--navy);
  }
  footer p { margin-top: 10px; font-size: 0.8rem; font-family: 'JetBrains Mono', monospace; opacity: 0.7; }

</style>

@yield('theme-styles')

</head>
<body class="@yield('body-class')" style="position: relative; min-height: 100vh;">

<!-- Placeholder for Ornaments to attach to body height -->
@yield('body-ornaments')

<a href="{{ route('departemen.index') }}" class="back-link" id="globalBackLink">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
  Kembali ke Departemen
</a>

@yield('content')

{{-- ===== NAVIGASI ANTAR DEPARTEMEN ===== --}}
@php
    $currentSlug = $dept['slug'] ?? '';
    $otherDepartments = \App\Models\Department::where('slug', '!=', $currentSlug)->get();
    
    $navIcons = [
        'bph' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M6 21V8l6-5 6 5v13M9 21v-6h6v6"/></svg>',
        'kominfo' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.15 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.07 2H6a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
        'penlar' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>',
        'kwu' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>',
        'kerohanian' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
        'minba' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
        'posdm' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
    ];
    $navColors = [
        'bph' => '#1d2a44',
        'kominfo' => '#3b6fd1',
        'penlar' => '#e3bd5d',
        'kwu' => '#1a7a4a',
        'kerohanian' => '#0d7377',
        'minba' => '#5c35a0',
        'posdm' => '#1d2a44',
    ];
@endphp

@if($otherDepartments->isNotEmpty())
<section class="dept-nav-section" id="globalNavSection">
    <h2 class="dept-nav-title" id="globalNavTitle">Departemen Lainnya</h2>
    <div class="dept-nav-grid">
        @foreach($otherDepartments as $other)
            <a href="{{ route('departemen.show', $other->slug) }}" class="dept-nav-item">
                @if($other->image)
                    <span class="dept-nav-icon" style="background: transparent;">
                        <img src="{{ Storage::url($other->image) }}" alt="{{ $other->name }}" style="width: 100%; height: 100%; object-fit: contain; border-radius: inherit;">
                    </span>
                @else
                    <span class="dept-nav-icon" style="background: {{ $navColors[$other->slug] ?? '#4a5568' }};">
                        {!! $navIcons[$other->slug] ?? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h14a2 2 0 0 0 2-2V7.5L14.5 2H6a2 2 0 0 0-2 2v4"/><polyline points="14 2 14 8 20 8"/><path d="M2 15h10"/><path d="M9 18l3-3-3-3"/></svg>' !!}
                    </span>
                @endif
                {{ strtoupper($other->abbreviation ?? $other->slug) }}
            </a>
        @endforeach
    </div>
</section>
@endif

<footer id="globalFooter">
  @yield('footer-custom')
</footer>

</body>
</html>