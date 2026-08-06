@extends('layouts.app')

@section('title', 'Berita FORMAT-R UNESA')

@push('styles')
<style>
  /* ===== BERITA ===== */
  .art-grid{display:grid;grid-template-columns:repeat(1,1fr);gap:24px;}
  @media(min-width:768px){ .art-grid{grid-template-columns:repeat(2,1fr);} }
  @media(min-width:1024px){ .art-grid{grid-template-columns:repeat(3,1fr);} }
  
  .art-card{border-radius:22px;overflow:hidden;border:1px solid var(--line);background:var(--cream);transition:transform .25s ease, box-shadow .25s ease;}
  .art-card:hover{transform:translateY(-6px);box-shadow:0 20px 40px rgba(11,37,69,0.12);}
  
  body.dark .art-card {
    background: var(--cream);
    border-color: rgba(255,255,255,0.1);
  }
</style>
@endpush

@section('content')
<div class="pt-32 pb-20 bg-gray-50 min-h-screen" style="background-color: var(--surface);">
    <div class="container mx-auto px-4 max-w-7xl" style="max-width: 1200px; margin: 0 auto;">
        <div class="text-center mb-12" data-reveal style="text-align: center; margin-bottom: 50px;">
            <span class="text-blue-600 font-semibold tracking-wider uppercase text-sm mb-2 block" style="font-family:'JetBrains Mono',monospace;font-size:0.68rem;color:var(--yellow-deep);letter-spacing:0.08em;text-transform:uppercase; margin-bottom: 10px; display: block;">Kumpulan Berita</span>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 font-['Space_Grotesk'] mb-4" style="font-size:3.4rem;line-height:1.06;margin:18px 0 22px;color:var(--navy);">Ruang Baca & Informasi</h1>
            <p class="text-gray-600 max-w-2xl mx-auto" style="font-size:1.08rem;color:var(--ink-soft);max-width:600px;margin: 0 auto 32px;">Kumpulan artikel, tips, ulasan kegiatan, dan informasi terkini dari seluruh departemen FORMAT-R UNESA.</p>
        </div>

        <div class="art-grid" data-stagger>
            @forelse($semuaBerita as $b)
            <a href="{{ route('berita.show', $b->slug) }}" style="text-decoration:none; color:inherit; display:block; height:100%;">
                <article class="art-card" data-stagger-child style="display:flex; flex-direction:column; height:100%;">
                    <div class="art-thumb" style="height:200px; position:relative; {{ $b->image ? 'background-image:url('.Storage::url($b->image).'); background-size:cover; background-position:center;' : 'background-color: var(--surface-alt); display: flex; align-items: center; justify-content: center;' }}">
                        @if(!$b->image)
                        <svg style="width: 48px; height: 48px; color: var(--ink-soft); opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        @endif
                    </div>
                    
                    <div class="art-body" style="padding:20px; flex:1; display:flex; flex-direction:column;">
                        <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <div style="font-size: 0.8rem; color: var(--ink-soft); font-weight: 500; letter-spacing: 0.5px;">
                                {{ $b->published_at ? \Carbon\Carbon::parse($b->published_at)->translatedFormat('d M Y') : $b->created_at->translatedFormat('d M Y') }}
                            </div>
                            <span style="font-size: 0.75rem; color: var(--ink-soft); font-weight: 500; opacity: 0.8;">{{ $b->author->name ?? 'Admin' }}</span>
                        </div>
                        
                        <h4 style="margin:0 0 10px 0; font-size:1.15rem; line-height: 1.4; color:var(--navy);">{{ $b->title }}</h4>
                        
                        <p style="font-size: 0.9rem; color: var(--ink-soft); margin-bottom: 0; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; flex: 1;">
                            {{ Str::limit(strip_tags($b->content), 120) }}
                        </p>
                        
                        <div style="margin-top: 16px; font-size: 0.85rem; font-weight: 600; color: var(--blue); display: flex; align-items: center; gap: 4px;">
                            Baca Selengkapnya
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                        </div>
                    </div>
                </article>
            </a>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 40px; color: var(--ink-soft); border: 1px dashed var(--line); border-radius: 22px;">
                <svg style="margin: 0 auto 16px; width: 64px; height: 64px; opacity: 0.5;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                <h3 style="margin:0 0 8px 0; font-size:1.4rem; color:var(--navy); font-weight:600;">Belum Ada Berita</h3>
                <p style="margin:0;">Belum ada artikel atau berita yang dipublikasikan saat ini.</p>
            </div>
            @endforelse
        </div>

        @if($semuaBerita->hasPages())
        <div style="margin-top: 50px; display: flex; justify-content: center;">
            {{ $semuaBerita->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Simple reveal animation
    const reveals = document.querySelectorAll('[data-reveal]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('opacity-100', 'translate-y-0');
                entry.target.classList.remove('opacity-0', 'translate-y-8');
            }
        });
    }, { threshold: 0.1 });

    reveals.forEach(el => {
        el.classList.add('transition-all', 'duration-700', 'opacity-0', 'translate-y-8');
        observer.observe(el);
    });

    // Staggered children animation
    const staggerContainers = document.querySelectorAll('[data-stagger]');
    staggerContainers.forEach(container => {
        const children = container.querySelectorAll('[data-stagger-child]');
        const containerObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    children.forEach((child, index) => {
                        setTimeout(() => {
                            child.classList.add('opacity-100', 'translate-y-0');
                            child.classList.remove('opacity-0', 'translate-y-8');
                        }, index * 100);
                    });
                    containerObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        children.forEach(child => child.classList.add('transition-all', 'duration-500', 'opacity-0', 'translate-y-8'));
        containerObserver.observe(container);
    });
});
</script>
@endpush
@endsection