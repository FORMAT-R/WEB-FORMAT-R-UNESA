@extends('layouts.app')

@section('title', $event->title . ' | Event FORMAT-R UNESA')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Nunito+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  /* ===== EXACT COPY FROM program-kerja.html ===== */
  :root{
    --bg: #EEF3FB;
    --bg-card: #FFFFFF;
    --navy: #0E2340;
    --navy-soft: #3C5372;
    --blue: #2A5CDB;
    --blue-dark: #1B3E9E;
    --sky: #D9E5FA;
    --sky-line: #A9C2EF;
    --line: rgba(14,35,64,0.12);
  }
  *{margin:0;padding:0;box-sizing:border-box;}
  html{scroll-behavior:smooth;}
  body{
    background:var(--bg);
    color:var(--navy);
    font-family:'Nunito Sans', sans-serif;
    line-height:1.6;
    overflow-x:hidden;
  }
  h1,h2,h3,.display{
    font-family:'Fraunces', serif;
    font-weight:600;
    color:var(--navy);
    letter-spacing:-0.01em;
  }
  .wrap{max-width:1180px;margin:0 auto;padding:0 32px;}
  a{text-decoration:none;color:inherit;}

  /* ===== decorative divider (signature element) ===== */
  .wheat-divider{
    display:flex;align-items:center;justify-content:center;gap:14px;
    margin:0 auto 18px;
  }
  .wheat-divider svg{width:34px;height:34px;color:var(--blue);}
  .wheat-divider .stem{width:64px;height:1px;background:var(--line);}

  /* ===== EVENT HEADER (paling atas, menyatu dengan hero) ===== */
  .event-hero{
    padding:36px 0 90px;
    background:
      radial-gradient(ellipse 900px 500px at 15% 0%, rgba(42,92,219,0.16), transparent 60%),
      radial-gradient(ellipse 700px 500px at 100% 10%, rgba(27,62,158,0.14), transparent 55%),
      var(--bg);
  }
  .breadcrumb{
    font-size:13px;color:var(--navy-soft);margin-bottom:22px;
  }
  .breadcrumb a{color:var(--blue-dark);font-weight:600;}
  .breadcrumb .sep{margin:0 6px;color:var(--sky-line);}

  .event-image{
    position:relative;
    height:360px;
    border-radius:26px;
    overflow:hidden;
    margin-bottom:40px;
    background:
      radial-gradient(circle at 25% 20%, rgba(255,255,255,0.25), transparent 45%),
      radial-gradient(circle at 80% 80%, rgba(13,28,58,0.35), transparent 55%),
      linear-gradient(150deg, #4C7EF0 0%, #1B3E9E 55%, #0E2340 100%);
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 30px 60px -28px rgba(14,35,64,0.4);
  }
  .event-image .icon-sym{
    width:110px;height:110px;color:rgba(255,255,255,0.28);
  }
  .badge-pill{
    position:absolute;top:22px;right:22px;
    display:inline-flex;align-items:center;gap:7px;
    background:#0E2340;color:#EAF1FE;
    padding:9px 16px 9px 12px;
    border-radius:999px;
    font-size:12.5px;font-weight:700;
    box-shadow:0 12px 22px -10px rgba(6,15,28,0.5);
  }
  .badge-pill svg{width:15px;height:15px;}
  .event-image .cap{
    position:absolute;left:0;right:0;bottom:0;
    padding:22px 26px 24px;
    background:linear-gradient(0deg, rgba(6,15,28,0.72), transparent);
    color:#F4F8FF;
  }
  .event-image .cap .eb{
    font-size:11px;letter-spacing:0.16em;text-transform:uppercase;
    color:#BFD3F7;font-weight:700;margin-bottom:6px;
  }
  .event-image .cap .ti{
    font-family:'Fraunces',serif;font-weight:600;font-size:22px;
  }

  .event-content{
    display:grid;grid-template-columns:1.1fr 1fr;
    gap:56px;align-items:start;
  }
  .event-content h1{
    font-size:clamp(30px,4vw,42px);
    line-height:1.08;margin-bottom:18px;
  }
  .event-content h1 em{font-style:italic;color:var(--blue);}
  .event-content .lede{
    color:var(--navy-soft);font-size:15.5px;max-width:440px;margin-bottom:28px;
  }

  .info-list{display:flex;flex-direction:column;}
  .info-item{
    display:flex;align-items:center;gap:14px;
    padding:15px 0;
    border-bottom:1px solid var(--line);
  }
  .info-item:first-child{padding-top:0;}
  .info-item:last-child{border-bottom:none;}
  .info-icon{
    width:20px;height:20px;flex:none;
    display:flex;align-items:center;justify-content:center;
    color:var(--blue-dark);
  }
  .info-icon svg{width:20px;height:20px;}
  .info-icon.status-closed{color:#C43B2F;}
  .info-icon.status-open{color:#1F8A5F;}
  .info-text{
    display:flex;align-items:baseline;gap:8px;flex-wrap:wrap;
  }
  .info-text .lbl{
    font-size:11px;letter-spacing:0.08em;text-transform:uppercase;
    color:var(--navy-soft);font-weight:700;
    min-width:150px;
  }
  .info-text .val{
    font-weight:700;font-size:14.5px;color:var(--blue-dark);
  }
  .info-text .val.muted{color:var(--navy);}
  .info-text .val.closed{color:#C43B2F;}
  .info-text .val.open{color:#1F8A5F;}

  @media(max-width:900px){
    .event-content{grid-template-columns:1fr;gap:34px;}
  }
  @media(max-width:600px){
    .event-image{height:220px;margin-bottom:30px;}
    .info-text{flex-direction:column;gap:2px;}
    .info-text .lbl{min-width:0;}
  }

  /* ===== OUTPUT PROKER ===== */
  .stat-grid{
    display:grid;grid-template-columns:repeat(4,1fr);gap:24px;
  }
  .stat-card{
    background:var(--bg-card);
    border:1px solid var(--line);
    border-radius:22px;
    padding:30px 26px;
    transition:transform .3s ease, box-shadow .3s ease;
  }
  .stat-card:hover{transform:translateY(-5px);box-shadow:0 24px 36px -24px rgba(14,35,64,0.3);}
  .stat-card .stat-num{
    font-family:'Fraunces',serif;font-weight:700;
    font-size:38px;color:var(--blue-dark);line-height:1;margin-bottom:10px;
  }
  .stat-card .stat-lbl{
    font-weight:700;font-size:14.5px;margin-bottom:6px;color:var(--navy);
  }
  .stat-card .stat-desc{
    font-size:13px;color:var(--navy-soft);
  }

  /* ===== WHY / PILLARS ===== */
  .section{padding:96px 0;}
  .section-head{text-align:center;max-width:600px;margin:0 auto 56px;}
  .section-head .eyebrow{justify-content:center;}
  .section-head .eyebrow::before{display:none;}
  .section-head h2{font-size:clamp(30px,3.8vw,42px);margin-bottom:14px;}
  .section-head p{color:var(--navy-soft);font-size:16px;}

  .cards{
    display:grid;grid-template-columns:repeat(3,1fr);gap:28px;
  }
  .card{
    background:var(--bg-card);
    border:1px solid var(--line);
    border-radius:22px;
    padding:36px 30px 32px;
    transition:transform .3s ease, box-shadow .3s ease;
  }
  .card:hover{transform:translateY(-6px);box-shadow:0 26px 40px -26px rgba(14,35,64,0.28);}
  .card-icon{
    width:56px;height:56px;border-radius:16px;
    background:linear-gradient(150deg, #6E93E8, var(--blue));
    display:flex;align-items:center;justify-content:center;
    margin-bottom:22px;
    color:#fff;
  }
  .card h3{font-size:21px;margin-bottom:10px;}
  .card p{color:var(--navy-soft);font-size:14.5px;margin-bottom:22px;}
  .card .link{
    font-weight:700;font-size:13.5px;color:var(--blue-dark);
    display:inline-flex;align-items:center;gap:6px;
  }
  .card .link svg{width:14px;height:14px;transition:transform .25s;}
  .card:hover .link svg{transform:translateX(4px);}

  /* ===== IKUT SERTA ===== */
  .visit{
    background:var(--sky);
    border-radius:32px;
    padding:64px;
    display:grid;grid-template-columns:1fr 1fr;
    gap:48px;
    align-items:center;
  }
  .visit-copy .eyebrow::before{display:none;}
  .visit-copy h2{font-size:clamp(28px,3.4vw,38px);margin-bottom:16px;font-style:italic;}
  .visit-copy p{color:var(--navy-soft);margin-bottom:14px;max-width:460px;font-size:15px;}
  .hours{
    list-style:none;margin:22px 0 30px;
    font-size:14.5px;
  }
  .hours li{
    display:flex;justify-content:space-between;
    padding:8px 0;border-bottom:1px dashed var(--sky-line);
    max-width:340px;
  }
  .hours li span:last-child{font-weight:700;color:var(--blue-dark);}

  .visit-art{
    display:grid;grid-template-columns:1fr 1fr;gap:16px;
  }
  .swatch{
    border-radius:18px;
    aspect-ratio:1/1;
    box-shadow:0 18px 32px -18px rgba(14,35,64,0.35);
  }
  .swatch.a{background:radial-gradient(circle at 30% 25%, #4C7EF0, #1B3E9E 75%);}
  .swatch.b{background:radial-gradient(circle at 65% 35%, #C4D7F8, #4C7EF0 75%);align-self:end;}
  .swatch.c{background:radial-gradient(circle at 40% 30%, #EAF1FE, #6E93E8 80%);}
  .swatch.d{background:radial-gradient(circle at 60% 40%, #8FB2F5, #123071 80%);align-self:end;}

  /* ===== RATING PROKER ===== */
  .rate-select{
    width:100%;max-width:400px;
    padding:13px 16px;border-radius:12px;
    border:1.5px solid var(--sky-line);
    background:#fff;color:var(--navy);
    font-family:'Nunito Sans',sans-serif;font-size:14.5px;font-weight:600;
    margin-bottom:20px;cursor:pointer;
  }
  .star-input{display:flex;gap:8px;margin-bottom:22px;}
  .star-input button{
    background:none;border:none;cursor:pointer;padding:0;line-height:0;
  }
  .star-input svg{width:34px;height:34px;color:#B9CAEA;transition:color .15s, transform .15s;}
  .star-input button:hover svg,.star-input svg.active{color:var(--blue);}
  .star-input button:hover svg{transform:scale(1.1);}
  .rate-note{font-size:13px;color:var(--navy-soft);margin-top:-8px;margin-bottom:20px;}

  .rating-card{
    background:#fff;border-radius:22px;
    padding:30px 28px 26px;
    box-shadow:0 20px 40px -26px rgba(14,35,64,0.3);
  }
  .rating-card .r-title{
    font-size:12px;letter-spacing:0.08em;text-transform:uppercase;
    color:var(--navy-soft);font-weight:700;margin-bottom:12px;
  }
  .rating-top{display:flex;align-items:flex-end;gap:14px;margin-bottom:6px;}
  .rating-score{
    font-family:'Fraunces',serif;font-weight:700;font-size:46px;color:var(--blue-dark);line-height:1;
  }
  .stars-display{display:flex;gap:3px;margin-bottom:4px;}
  .stars-display svg{width:18px;height:18px;color:var(--blue);}
  .rating-count{font-size:13px;color:var(--navy-soft);margin-bottom:20px;}
  .bar-row{display:flex;align-items:center;gap:10px;font-size:12.5px;margin-bottom:8px;color:var(--navy-soft);}
  .bar-row .bl{width:38px;flex:none;font-weight:700;color:var(--navy);}
  .bar-track{flex:1;height:8px;background:var(--sky);border-radius:999px;overflow:hidden;}
  .bar-fill{height:100%;background:var(--blue);border-radius:999px;transition:width .4s ease;}
  .bar-row .bc{width:28px;text-align:right;flex:none;}

  /* ===== SUSUNAN PANITIA ===== */
  .team-grid{
    display:grid;grid-template-columns:repeat(4,1fr);gap:26px;
  }
  .team-card{
    background:var(--bg-card);
    border:1px solid var(--line);
    border-radius:24px;
    overflow:hidden;
    text-align:center;
    transition:transform .3s ease, box-shadow .3s ease;
    display:flex;flex-direction:column;
  }
  .team-card:hover{transform:translateY(-5px);box-shadow:0 22px 34px -24px rgba(14,35,64,0.3);}
  .team-card.lead{
    background:linear-gradient(160deg, var(--blue-dark), var(--navy));
    border-color:transparent;
  }
  .team-card.lead .team-role{color:#BFD3F7;}
  .team-card.lead .team-name{color:#F4F8FF;}
  .avatar{
    width:100%;aspect-ratio:4/5;
    display:flex;align-items:center;justify-content:center;
    background:linear-gradient(150deg, #8FB2F5, var(--blue));
    color:#fff;font-family:'Fraunces',serif;font-weight:700;font-size:36px;
    overflow:hidden;
  }
  .avatar img{width:100%;height:100%;object-fit:cover;}
  .team-card.lead .avatar{background:rgba(255,255,255,0.14);color:#F4F8FF;}
  .team-info{
    padding:24px 16px;
    flex:1;
    display:flex;flex-direction:column;justify-content:center;
  }
  .team-name{
    font-family:'Fraunces',serif;font-size:18px;font-weight:600;color:var(--navy);
    margin-bottom:6px;
  }
  .team-role{
    font-size:12px;letter-spacing:0.08em;text-transform:uppercase;
    color:var(--blue-dark);font-weight:700;
  }

  /* ===== GALLERY / DOKUMENTASI ===== */
.gallery-head{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:40px;flex-wrap:wrap;gap:20px;}
.gallery-head h2{font-size:clamp(28px,3.4vw,38px);}

.gallery{
    column-count:4;
    column-gap:16px;
}
.g{
    break-inside:avoid;
    -webkit-column-break-inside:avoid;
    margin-bottom:16px;
    border-radius:18px;
    position:relative;
    overflow:hidden;
    display:block;
    background:var(--blue-pale);
    box-shadow:0 10px 24px rgba(14,35,64,0.08);
    transition:transform .3s ease, box-shadow .3s ease;
}
.g img{
    width:100%;
    height:auto;
    display:block;
    transition:transform .5s ease;
}
.g::after{
    content:"";
    position:absolute;inset:0;
    background:linear-gradient(0deg, rgba(6,15,28,0.45), transparent 55%);
    opacity:0;
    transition:opacity .3s ease;
}
.g:hover{
    transform:translateY(-5px);
    box-shadow:0 20px 36px -16px rgba(14,35,64,0.3);
}
.g:hover img{
  transform:scale(1.05);
}
.g:hover::after{
  opacity:1;
}

@media(max-width:1100px){
  .gallery{ column-count:3; }
}
@media(max-width:600px){
  .gallery{ column-count:2; column-gap:10px; }
  .g{ margin-bottom:10px; border-radius:14px; }
}
  /* ===== CTA STRIP ===== */
  .cta-strip{
    background:var(--navy);
    color:#EAF1FE;
    border-radius:32px;
    padding:56px;
    text-align:center;
    margin-bottom:80px;
  }
  .cta-strip h2{color:#F4F8FF;font-size:clamp(26px,3.4vw,36px);margin-bottom:14px;}
  .cta-strip p{color:#B9C9E6;max-width:480px;margin:0 auto 28px;font-size:15px;}
  .cta-strip .btn{background:var(--blue);}

  footer{
    text-align:center;padding:30px 0 50px;
    font-size:13px;color:var(--navy-soft);
  }

    @media(max-width:900px){
      .hero-grid{grid-template-columns:1fr;}
      .hero-art{height:340px;margin-top:20px;}
      .cards{grid-template-columns:1fr;}
      .visit{grid-template-columns:1fr;padding:40px 26px;border-radius:24px;}
      .cta-strip{padding:40px 24px;border-radius:24px;}
      .team-grid{grid-template-columns:repeat(2,1fr);}
      .stat-grid{grid-template-columns:repeat(2,1fr);}
    }
  @media(min-width:901px) and (max-width:1150px){
    .team-grid{grid-template-columns:repeat(3,1fr);}
  }
  @media(max-width:900px){
    .event-content{grid-template-columns:1fr;gap:34px;}
  }
  @media(max-width:600px){
    .event-image{height:220px;margin-bottom:30px;}
    .info-text{flex-direction:column;gap:2px;}
    .info-text .lbl{min-width:0;}
  }
  @media(prefers-reduced-motion: reduce){
    .float-badge{animation:none;}
  }
</style>
@endpush

@section('content')

<div class="event-detail-page">

    {{-- ===== EVENT HEADER + HERO (menyatu, paling atas) ===== --}}
    <section class="event-hero">
        <div class="wrap">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Beranda</a><span class="sep">/</span><a href="{{ route('event.index') }}">Event</a><span class="sep">/</span>{{ $event->title }}
            </div>

            <div class="event-image">
                @if($event->image)
                    <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0;">
                @else
                    <svg class="icon-sym" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3"><path d="M17 20v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2M9 10a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM23 20v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                @endif

                @if($event->status == 'upcoming')
                <div class="badge-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    Akan Datang
                </div>
                @elseif($event->status == 'ongoing')
                <div class="badge-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Sedang Berlangsung
                </div>
                @else
                <div class="badge-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Selesai
                </div>
                @endif

                <div class="cap">
                    <div class="eb">{{ 'FORMAT-R UNESA' }}</div>
                    <div class="ti">{{ $event->title }}</div>
                </div>
            </div>

            <div class="event-content">
                    <div>
                        <h1>{{ $event->title }}</h1>
                        <p class="lede">{!! nl2br(e($event->description)) !!}</p>

                          <div style="display:flex;gap:14px;flex-wrap:wrap;">
                            @if($event->status == 'upcoming')
                                @if($event->registration_link)
                                    <a href="{{ $event->registration_link }}" target="_blank" class="btn">Daftar Sekarang</a>
                                @else
                                    <button onclick="alert('Link pendaftaran belum tersedia')" class="btn" style="opacity: 0.8; cursor: not-allowed;">Daftar Sekarang</button>
                                @endif
                            <a href="{{ route('event.index') }}" class="btn ghost">Lihat Semua Event</a>
                            @elseif($event->status == 'ongoing')
                                @if($event->registration_link)
                                    <a href="{{ $event->registration_link }}" target="_blank" class="btn">Bergabung Sekarang</a>
                                @else
                                    <button onclick="alert('Link pendaftaran belum tersedia')" class="btn" style="opacity: 0.8; cursor: not-allowed;">Bergabung Sekarang</button>
                                @endif
                            @endif
                        </div>
                    </div>

                <div class="info-list">
                    <div class="info-item">
                        <div class="info-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        </div>
                        <div class="info-text">
                            <span class="lbl">Tanggal</span>
                            <span class="val">{{ \Carbon\Carbon::parse($event->start_date)->isoFormat('D MMMM Y') }}</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 12-9 12s-9-5-9-12a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div class="info-text">
                            <span class="lbl">Lokasi</span>
                            <span class="val">{{ $event->location }}</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 20v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2M9 10a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM23 20v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div class="info-text">
                            <span class="lbl">Penyelenggara</span>
                            <span class="val">{{ $event->organizer ?? 'FORMAT-R UNESA' }}</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                        </div>
                        <div class="info-text">
                            <span class="lbl">Status Event</span>
                            @if($event->status == 'upcoming')
                            <span class="val">Segera Hadir</span>
                            @elseif($event->status == 'ongoing')
                            <span class="val open">Sedang Berlangsung</span>
                            @else
                            <span class="val closed">Selesai</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon status-closed">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                        </div>
                        <div class="info-text">
                            <span class="lbl">Status Pendaftaran</span>
                            @if($event->status == 'upcoming')
                            <span class="val">Segera Dibuka</span>
                            @elseif($event->status == 'ongoing')
                            <span class="val open">Berlangsung</span>
                            @else
                            <span class="val closed">Ditutup</span>
                            @endif
                        </div>
                    </div>

                      <div class="info-item">
                          <div class="info-icon">
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                          </div>
                          <div class="info-text">
                              <span class="lbl">Peserta</span>
                              <span class="val muted">{{ $event->participant_count ? $event->participant_count . ' Orang' : '-' }}</span>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </section>
  
@if(!empty($event->output))
<section class="section" style="padding-top:0;">
    <div class="wrap">
        <div class="gallery-head" style="margin-bottom: 20px;">
            <div>
                <div class="eyebrow">Pencapaian</div>
                <h2>Output Kegiatan</h2>
            </div>
        </div>
        <div style="background: var(--bg-card); border: 1px solid var(--line); border-radius: 20px; padding: 24px; box-shadow: 0 10px 24px rgba(14,35,64,0.04);">
            <div style="color: var(--ink-soft); font-size: 0.95rem; line-height: 1.7;">
                {!! nl2br(e($event->output)) !!}
            </div>
        </div>
    </div>
</section>
@endif

@if($event->speakers->count() > 0)
<section class="section" style="padding-top:0;">
    <div class="wrap">
        <div class="section-head">
            <div class="wheat-divider">
                <div class="stem"></div>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                <div class="stem"></div>
            </div>
            <h2>Pemateri</h2>
        </div>

        <div class="team-grid">
            @foreach($event->speakers()->orderBy('sort_order')->get() as $s)
            <div class="team-card lead" style="background: linear-gradient(160deg, #1B3E9E, #0E2340);">
                <div class="avatar" style="border-color: #2A5CDB;">
                    @if($s->photo)
                        <img src="{{ Storage::url($s->photo) }}" alt="{{ $s->name }}">
                    @else
                        {{ substr($s->name, 0, 2) }}
                    @endif
                </div>
                <div class="team-info">
                    <div class="team-name" style="color: #fff;">{{ $s->name }}</div>
                    <div class="team-role" style="color: #A9C2EF;">{{ $s->role ?? 'Narasumber' }}</div>
                    @if($s->topic)
                    <div style="font-size: 11px; margin-top: 6px; padding-top: 6px; border-top: 1px solid rgba(255,255,255,0.1); color: #fff; font-style: italic;">
                        "{{ $s->topic }}"
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($event->status == 'completed' && $event->documentations->count() > 0)
<section class="section" style="padding-top:0;">
    <div class="wrap">
        <div class="gallery-head">
            <div>
                <div class="eyebrow">Rekam Jejak</div>
                <h2>Dokumentasi Kegiatan</h2>
                <p>Momen-momen berharga yang terekam sepanjang pelaksanaan kegiatan.</p>
            </div>
        </div>
        <div class="gallery">
            @foreach($event->documentations as $doc)
            <div class="g">
                <img src="{{ Storage::url($doc->photo) }}" alt="Dokumentasi {{ $event->title }}" loading="lazy">
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===== SUSUNAN PANITIA ===== --}}
@if($event->committees->count() > 0)
<section class="section" style="padding-top:0;">
    <div class="wrap">
        <div class="section-head">
            <div class="wheat-divider">
                <div class="stem"></div>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 20v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2M9 10a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM23 20v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <div class="stem"></div>
            </div>
            <div class="eyebrow">Di Balik Layar</div>
            <h2>Susunan Panitia</h2>
        </div>

        <div class="team-grid">
            @foreach($event->committees()->orderBy('sort_order')->get() as $i => $p)
            <div class="team-card {{ $i < 2 ? 'lead' : '' }}">
                <div class="avatar">
                    @if($p->photo)
                        <img src="{{ Storage::url($p->photo) }}" alt="{{ $p->name }}">
                    @else
                        {{ substr($p->name, 0, 2) }}
                    @endif
                </div>
                <div class="team-info">
                    <div class="team-name">{{ $p->name }}</div>
                    <div class="team-role">{{ $p->role }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===== RATING PROKER ===== --}}
@if($event->status == 'completed')
<section class="section" id="rating" style="padding-top:0;">
    <div class="wrap">
        <div class="visit">
            <div class="visit-copy">
                <div class="eyebrow">Beri Penilaian</div>
                <h2>Rating Kegiatan</h2>
                <p>Panitia maupun peserta bisa memberi rating untuk setiap program kerja yang sudah terlaksana, sebagai bahan evaluasi ke depan.</p>

                <select class="rate-select" id="programSelect">
                    <option value="{{ $event->slug }}">{{ $event->title }}</option>
                </select>

                <div class="star-input" id="starInput">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button" data-star="{{ $i }}" aria-label="{{ $i }} bintang">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </button>
                    @endfor
                </div>
                <p class="rate-note" id="rateNote">Pilih bintang sesuai penilaian Anda, lalu kirim.</p>
                <a href="#" class="btn" id="submitRating">Kirim Rating</a>
            </div>

            <div class="rating-card">
                <div class="r-title">Ringkasan Rating</div>
                <div class="rating-top">
                    <div class="rating-score" id="avgScore">{{ number_format($event->average_rating, 1) }}</div>
                    <div>
                        <div class="stars-display" id="avgStars">
                            @for($i = 1; $i <= 5; $i++)
                            <svg viewBox="0 0 24 24" {{ $i <= round($event->average_rating) ? 'fill="currentColor"' : 'fill="none" stroke="currentColor" stroke-width="1.5"' }}><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            @endfor
                        </div>
                        <div class="rating-count" id="ratingCount" style="margin-bottom:0;">{{ $event->rating_count }} penilaian</div>
                    </div>
                </div>
                <div style="margin-top:20px;">
                    @php
                        $total = $event->rating_count;
                        $dist = [];
                        for($i=1; $i<=5; $i++) {
                            $dist[$i] = $event->ratings()->where('rating', $i)->count();
                        }
                    @endphp
                    @for($s = 5; $s >= 1; $s--)
                    @php $cnt = $dist[$s]; $pct = $total > 0 ? round(($cnt / $total) * 100) : 0; @endphp
                    <div class="bar-row">
                        <span class="bl">{{ $s }} ★</span>
                        <div class="bar-track"><div class="bar-fill" id="bar{{$s}}" style="width:{{ $pct }}%;"></div></div>
                        <span class="bc" id="cnt{{$s}}">{{ $cnt }}</span>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ===== EVENT LAINNYA ===== --}}
@if($lainnya->count() > 0)
<div class="event-section" style="padding-top: 0;">
    <div class="wrap">
        <h3 style="font-family: 'Fraunces', serif; text-align: center; margin-bottom: 30px; font-size: 2rem; color: #0E2340;">Event Lainnya</h3>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:24px; margin-top:24px;">
            @foreach($lainnya as $ev)
            <a href="{{ route('event.show', $ev->slug) }}" style="background:#fff; border-radius:16px; overflow:hidden; border:1px solid rgba(14,35,64,0.06); box-shadow:0 6px 18px rgba(14,35,64,0.05); text-decoration:none; color:inherit; transition:0.3s; display:block;">
                <div style="height:120px; {{ $ev->image ? 'background-image:url('.Storage::url($ev->image).'); background-size:cover; background-position:center;' : 'background: linear-gradient(135deg, #0E2340, #2A5CDB);' }}"></div>
                <div style="padding:18px 20px;">
                    <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#2A5CDB; margin-bottom:6px;">{{ $ev->organizer ?? 'FORMAT-R UNESA' }}</div>
                    <h4 style="font-family:'Fraunces', serif; font-size:1.1rem; color:#0E2340; line-height:1.3;">{{ $ev->title }}</h4>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif
<br>
@endsection

@push('scripts')
<script>
(function() {
    var starButtons = document.querySelectorAll('#starInput button');
    var note = document.getElementById('rateNote');
    var submitBtn = document.getElementById('submitRating');
    var selectedStar = 0;
    
    // Check if user has already rated in this browser
    var STORAGE_KEY = 'eventRated_{{ $event->slug }}';
    if(localStorage.getItem(STORAGE_KEY)) {
        note.textContent = 'Anda telah memberikan rating pada event ini.';
        submitBtn.style.display = 'none';
        starButtons.forEach(function(b) {
            b.style.pointerEvents = 'none';
            b.style.opacity = '0.6';
        });
    }

    starButtons.forEach(function(btn){
        btn.addEventListener('click', function(){
            selectedStar = parseInt(btn.getAttribute('data-star'), 10);
            starButtons.forEach(function(b){
                var val = parseInt(b.getAttribute('data-star'), 10);
                b.querySelector('svg').classList.toggle('active', val <= selectedStar);
            });
            note.textContent = 'Anda memilih ' + selectedStar + ' bintang. Klik "Kirim Rating" untuk mengirim.';
        });
    });

    submitBtn.addEventListener('click', function(e){
        e.preventDefault();
        if(!selectedStar){
            note.textContent = 'Silakan pilih bintang terlebih dahulu.';
            return;
        }
        
        submitBtn.textContent = 'Mengirim...';
        submitBtn.style.pointerEvents = 'none';

        fetch('{{ route("event.rate", $event->slug) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ rating: selectedStar })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                note.textContent = data.message;
                localStorage.setItem(STORAGE_KEY, 'true');
                submitBtn.style.display = 'none';
                starButtons.forEach(function(b) {
                    b.style.pointerEvents = 'none';
                });
                // Reload the page smoothly to update the summary chart
                setTimeout(() => window.location.reload(), 1500);
            } else {
                note.textContent = data.message || 'Terjadi kesalahan.';
                submitBtn.textContent = 'Kirim Rating';
                submitBtn.style.pointerEvents = 'auto';
            }
        })
        .catch(err => {
            note.textContent = 'Gagal mengirim rating. Silakan coba lagi.';
            submitBtn.textContent = 'Kirim Rating';
            submitBtn.style.pointerEvents = 'auto';
        });
    });
})();
</script>
@endpush
