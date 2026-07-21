@extends('layouts.app')

@section('title', 'Arsip Kegiatan - FORMAT-R UNESA')

@section('content')
{{-- ===== ARSIP KEGIATAN ===== --}}
<section style="padding-top: 80px; padding-bottom: 80px;">
    <div class="container">
        <div class="sec-head reveal" style="text-align:center; margin-bottom:48px; max-width:720px; margin:0 auto 48px;">
            <span class="eyebrow">Dokumentasi</span>
            <h2>Arsip Kegiatan</h2>
            <p>Jejak kegiatan yang telah selesai dilaksanakan oleh FORMAT-R UNESA pada periode sebelumnya.</p>
        </div>

        <div class="archive-grid reveal-stagger reveal">
            @forelse($arsip as $a)
            <div class="archive-card stagger-child">
                <div class="archive-thumb" style="{{ $a->image ? 'background-image:url('.Storage::url($a->image).'); background-size:cover; background-position:center;' : '' }}"></div>
                <span class="archive-badge">Selesai</span>
                <div class="archive-body">
                    <span class="tl-date">{{ $a->end_date ? $a->end_date->format('M Y') : '' }}</span>
                    <h4>{{ $a->title }}</h4>
                    <p>{{ $a->description }}</p>
                </div>
            </div>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: var(--ink-soft); border: 1px dashed var(--line); border-radius: 20px;">
                <p>Belum ada arsip kegiatan yang selesai.</p>
            </div>
            @endforelse
        </div>

        <div style="text-align:center;margin-top:36px;">
            <a href="#kontak" class="btn btn-ghost">Lihat Semua Arsip</a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* ===== ARSIP PAGE ===== */
    .archive-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }
    .archive-card {
        background: var(--cream);
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 28px 24px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(11,37,69,0.05);
        transition: transform .28s ease, box-shadow .28s ease, border-color .28s ease;
    }
    .archive-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(11,37,69,0.1);
        border-color: transparent;
    }
    .archive-card::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--blue-pale, #EAF1FC), var(--cream));
        opacity: 0;
        transition: opacity .28s ease;
    }
    .archive-card:hover::before {
        opacity: 1;
    }
    .archive-thumb {
        aspect-ratio: 16/9;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--blue), var(--navy));
        position: relative;
    }
    .archive-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--yellow-deep);
        background: rgba(255,199,48,0.18);
        padding: 4px 12px;
        border-radius: 100px;
        width: fit-content;
    }
    .archive-body {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .tl-date {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.7rem;
        color: var(--blue);
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }
    body.dark .tl-date { color: var(--yellow); }
    .archive-body h4 {
        font-size: 1.05rem;
        color: var(--navy);
        margin: 0;
        line-height: 1.4;
    }
    body.dark .archive-body h4 { color: #fff; }
    .archive-body p {
        font-size: 0.88rem;
        color: var(--ink-soft);
        margin: 0;
        line-height: 1.6;
    }
    .reveal { opacity: 0; transform: translateY(24px); transition: opacity .6s ease, transform .6s ease; }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .reveal-stagger.visible .stagger-child { opacity: 1; transform: translateY(0); }
    .stagger-child { opacity: 0; transform: translateY(20px); transition: opacity .5s ease, transform .5s ease; }
    .stagger-child:nth-child(1) { transition-delay: 0s; }
    .stagger-child:nth-child(2) { transition-delay: 0.08s; }
    .stagger-child:nth-child(3) { transition-delay: 0.16s; }
    .stagger-child:nth-child(4) { transition-delay: 0.24s; }
    .stagger-child:nth-child(5) { transition-delay: 0.32s; }
    .stagger-child:nth-child(6) { transition-delay: 0.40s; }
    .stagger-child:nth-child(7) { transition-delay: 0.48s; }
    .stagger-child:nth-child(8) { transition-delay: 0.56s; }
    .stagger-child:nth-child(9) { transition-delay: 0.56s; }
    .stagger-child:nth-child(10) { transition-delay: 0.64s; }
    .stagger-child:nth-child(11) { transition-delay: 0.72s; }
    .stagger-child:nth-child(12) { transition-delay: 0.80s; }
    @media (max-width: 980px) {
        .archive-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 560px) {
        .archive-grid { grid-template-columns: 1fr; }
    }
</style>
</push>