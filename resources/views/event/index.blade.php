@extends('layouts.app')

@section('title', 'Event & Kegiatan | FORMAT-R UNESA')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Nunito+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  /* Menggunakan estetika dari program-kerja.html */
  .event-page {
    background: #EEF3FB;
    font-family: 'Nunito Sans', sans-serif;
    color: #0E2340;
    min-height: 100vh;
    padding-bottom: 80px;
  }
  
  .event-hero {
    padding: 80px 0 60px;
    background:
      radial-gradient(ellipse 900px 500px at 15% 0%, rgba(42,92,219,0.16), transparent 60%),
      radial-gradient(ellipse 700px 500px at 100% 10%, rgba(27,62,158,0.14), transparent 55%),
      #EEF3FB;
    text-align: center;
  }
  .event-hero h1 {
    font-family: 'Fraunces', serif;
    font-size: clamp(36px, 5vw, 56px);
    font-weight: 700;
    color: #0E2340;
    margin-bottom: 16px;
    letter-spacing: -0.01em;
  }
  .event-hero p {
    font-size: 1.1rem;
    color: #3C5372;
    max-width: 600px;
    margin: 0 auto;
  }

  /* TABS FILTER */
  .filter-tabs {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-bottom: 50px;
    flex-wrap: wrap;
  }
  .filter-tab {
    padding: 10px 24px;
    border-radius: 100px;
    font-weight: 700;
    font-size: 0.95rem;
    color: #3C5372;
    background: #fff;
    border: 1px solid rgba(14,35,64,0.1);
    cursor: pointer;
    transition: all .2s ease;
  }
  .filter-tab:hover {
    background: #D9E5FA;
    color: #1B3E9E;
  }
  .filter-tab.active {
    background: #2A5CDB;
    color: #fff;
    border-color: #2A5CDB;
    box-shadow: 0 4px 12px rgba(42,92,219,0.25);
  }

  /* EVENT GRID */
  .event-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
  }

  .event-card {
    background: #fff;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 10px 30px -10px rgba(14,35,64,0.08);
    border: 1px solid rgba(14,35,64,0.05);
    transition: transform .3s ease, box-shadow .3s ease;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
  }
  .event-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px -15px rgba(14,35,64,0.15);
  }

  .event-img {
    height: 220px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .event-img svg.placeholder {
    width: 80px; height: 80px;
    color: rgba(255,255,255,0.2);
  }

  .badge-status {
    position: absolute;
    top: 20px; right: 20px;
    display: inline-flex; align-items: center; gap: 6px;
    background: #0E2340; color: #EAF1FE;
    padding: 8px 16px; border-radius: 100px;
    font-size: 0.8rem; font-weight: 700;
    box-shadow: 0 8px 16px -6px rgba(0,0,0,0.4);
  }
  .badge-status svg { width: 14px; height: 14px; }
  
  /* Status Colors */
  .status-coming_soon { background: #E8A400; color: #fff; } /* Kuning */
  .status-started { background: #1F8A5F; color: #fff; }     /* Hijau */
  .status-finished { background: #0E2340; color: #fff; }    /* Navy */

  .event-body {
    padding: 30px 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }
  .event-meta {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.75rem;
    color: #2A5CDB;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 10px;
    font-weight: 600;
  }
  .event-body h3 {
    font-family: 'Fraunces', serif;
    font-size: 1.6rem;
    line-height: 1.2;
    margin-bottom: 12px;
    color: #0E2340;
  }
  .event-body p {
    color: #3C5372;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 24px;
    flex: 1;
  }

  .event-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px dashed rgba(14,35,64,0.1);
    padding-top: 20px;
  }
  .event-date-loc {
    display: flex;
    flex-direction: column;
    gap: 4px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #0E2340;
  }
  .event-date-loc span {
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .event-date-loc svg { width: 14px; height: 14px; color: #2A5CDB; }

  /* Rating khusus finished */
  .event-rating {
    display: flex; align-items: center; gap: 4px;
    font-weight: 800; color: #2A5CDB; font-family: 'Fraunces', serif; font-size: 1.2rem;
  }
  .event-rating svg { width: 16px; height: 16px; color: #E8A400; fill: #E8A400; }
  .rating-btn {
    font-size: 0.8rem; font-weight: 700; color: #2A5CDB;
    background: #EAF1FE; padding: 6px 14px; border-radius: 100px;
    border: 1px solid #A9C2EF; transition: 0.2s;
  }
  .rating-btn:hover { background: #2A5CDB; color: #fff; border-color: #2A5CDB; }

  @media(max-width: 800px) {
    .event-grid { grid-template-columns: 1fr; }
  }
</style>
@endpush

@section('content')
<div class="event-page">
    <section class="event-hero">
        <div class="container">
            <h1>Agenda Kegiatan</h1>
            <p>Ikuti terus berbagai acara, pelatihan, dan program kerja unggulan dari seluruh departemen FORMAT-R UNESA.</p>
        </div>
    </section>

    <div class="container">
        {{-- FILTER TABS --}}
        <div class="filter-tabs">
            <button class="filter-tab active" data-filter="all">Semua Event</button>
            <button class="filter-tab" data-filter="upcoming">Akan Datang</button>
            <button class="filter-tab" data-filter="ongoing">Sedang Berlangsung</button>
            <button class="filter-tab" data-filter="completed">Selesai</button>
        </div>

        {{-- GRID --}}
        <div class="event-grid">
            @foreach($events as $ev)
            <div class="event-card event-item" data-status="{{ $ev->status }}" style="cursor:pointer;" onclick="window.location='{{ route('event.show', $ev->slug) }}'">
                <div class="event-img" style="{{ $ev->image ? 'background-image:url('.Storage::url($ev->image).'); background-size:cover; background-position:center;' : 'background: linear-gradient(135deg, #0E2340, #2A5CDB);' }}">
                    @if(!$ev->image)
                    <svg class="placeholder" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    @endif
                    
                    {{-- Badge Status --}}
                    @if($ev->status == 'upcoming')
                        <div class="badge-status status-coming_soon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Akan Datang
                        </div>
                    @elseif($ev->status == 'ongoing')
                        <div class="badge-status status-started">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            Sedang Berlangsung
                        </div>
                    @elseif($ev->status == 'completed')
                        <div class="badge-status status-finished">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            Selesai
                        </div>
                    @endif
                </div>

                <div class="event-body">
                    <div class="event-meta">FORMAT-R UNESA</div>
                    <h3>{{ $ev->title }}</h3>
                    <p>{!! Str::limit(strip_tags($ev->description), 100) !!}</p>

                    <div class="event-footer">
                        <div class="event-date-loc">
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                {{ \Carbon\Carbon::parse($ev->start_date)->isoFormat('D MMMM Y') }}
                            </span>
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 12-9 12s-9-5-9-12a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ $ev->location }}
                            </span>
                        </div>
                        
                        @if($ev->status == 'completed')
                            <div class="rating-btn">Selesai</div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.filter-tab');
    const items = document.querySelectorAll('.event-item');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');

            items.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-status') === filter) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endpush