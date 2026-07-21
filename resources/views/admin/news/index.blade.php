@extends('admin.layouts.app')

@section('page-title', 'Manajemen Berita')
@section('content')
@push('styles')
<style>
    .news-card {
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.2s;
    }
    .news-card:hover {
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
        border-color: #3B82F6;
    }
    .dark .news-card {
        border-color: #334155;
    }
    .dark .news-card:hover {
        border-color: #3B82F6;
    }
    
    .news-thumb {
        height: 160px;
        background: linear-gradient(135deg, var(--navy), var(--blue));
        position: relative;
    }
    .news-thumb::after {
        content:"";
        position:absolute;inset:0;
        background:repeating-linear-gradient(45deg, rgba(255,199,48,0.14) 0 2px, transparent 2px 22px);
    }
    .news-body{padding:22px;}
    .news-tag{font-family:'JetBrains Mono',monospace;font-size:0.68rem;color:var(--yellow-deep);letter-spacing:0.08em;text-transform:uppercase;}
    .news-body h2{margin:10px 0 8px;font-size:1.02rem;color:var(--navy);}
    .dark .news-body h2{color:#fff;}
    .news-body p{font-size:0.86rem;color:var(--ink-soft);line-height:1.5;}
    .news-meta{margin-top:16px;font-size:0.78rem;color:var(--ink-soft);display:flex;justify-content:space-between;}
    .news-meta span:last-child{font-weight:700;color:var(--blue-dark);}
    
    .filter-tab {
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 0.875rem;
        color: #64748B;
        background: white;
        border: 1px solid #E2E8F0;
        transition: all 0.2s;
    }
    .filter-tab:hover {
        background: #F1F5F9;
        color: #334155;
    }
    .filter-tab.active {
        background: #2563EB;
        color: white;
        border-color: #2563EB;
    }
    .dark .filter-tab {
        background: #1E293B;
        border-color: #334155;
        color: #94A3B8;
    }
    .dark .filter-tab:hover {
        background: #334155;
        color: #E2E8F0;
    }
    .filter-tab.active {
        background: #2563EB;
        color: white;
        border-color: #2563EB;
    }
</style>
@endpush

<div class="space-y-8">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Berita</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Kelola semua artikel dan berita FORMAT NEWS</p>
        </div>
        <a href="{{ route('admin.berita.create') }}" class="btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Berita</span>
        </a>
    </div>

    {{-- Filter Tabs --}}
    <div class="filter-tabs flex flex-wrap gap-2 mb-6" role="tablist">
        <button class="filter-tab active" data-filter="all" role="tab">Semua</button>
        <button class="filter-tab" data-filter="published" role="tab">Dipublikasikan</button>
        <button class="filter-tab" data-filter="draft" role="tab">Draft</button>
        <button class="filter-tab" data-filter="archived" role="tab">Arsip</button>
    </div>

    {{-- News Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="newsGrid">

        @foreach($news as $item)
        <article class="news-card" data-status="{{ $item->status }}">
            <div class="news-thumb" style="{{ $item->image ? 'background-image:url('.Storage::url($item->image).'); background-size:cover; background-position:center;' : '' }}"></div>
            <div class="news-body">
                <span class="news-tag">Kabar FORMAT</span>
                <h2>{{ $item->title }}</h2>
                <p>{{ Str::limit($item->content, 120) }}</p>
                <div class="news-meta">
                    <span>{{ $item->author ? $item->author->name : 'Redaksi' }}</span>
                </div>
            </div>
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <span class="status-badge status-{{ $item->status }}">
                    @if($item->status == 'published')
                        Dipublikasikan
                    @elseif($item->status == 'draft')
                        Draft
                    @else
                        Arsip
                    @endif
                </span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.berita.show', $item->id) }}" target="_blank" class="text-sm text-green-600 hover:text-green-700 font-medium">Preview</a>
                    <a href="{{ route('admin.berita.edit', $item->id) }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Edit</a>
                    <form action="{{ route('admin.berita.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium">Hapus</button>
                    </form>
                </div>
            </div>
        </article>
        @endforeach
    </div>

    {{-- Empty State --}}
    <div id="emptyState" class="hidden text-center py-16">
        <svg class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v11a2 2 0 01-2 2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 17a2 2 0 01-2 2H9a2 2 0 01-2-2v-1a2 2 0 00-2-2H5a2 2 0 01-2-2V7a2 2 0 012-2h2M9 7a2 2 0 012-2h10a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 01-2 2z"/></svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Belum ada berita</h3>
        <p class="mt-2 text-gray-500 dark:text-gray-400">Mulai buat artikel pertama Anda</p>
        <a href="{{ route('admin.berita.create') }}" class="btn-primary mt-4 inline-flex">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            <span>Buat Berita Baru</span>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.filter-tab');
    const items = document.querySelectorAll('.news-card');
    const emptyState = document.getElementById('emptyState');
    const newsGrid = document.getElementById('newsGrid');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');
            let visibleCount = 0;

            document.querySelectorAll('.news-card').forEach(item => {
                if (filter === 'all' || item.getAttribute('data-status') === filter) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
            }
            if (newsGrid) {
                newsGrid.style.display = visibleCount === 0 ? 'none' : 'grid';
            }
        });
    });
});


</script>
@endpush
