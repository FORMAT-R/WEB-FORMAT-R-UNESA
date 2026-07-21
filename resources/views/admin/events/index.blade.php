@extends('admin.layouts.app')

@section('page-title', 'Manajemen Event')
@section('content')
@push('styles')
<style>
    .event-card {
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        background: white;
    }
    .dark .event-card {
        background: #1E293B;
    }
    .event-card:hover {
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
        border-color: #3B82F6;
    }
    .dark .event-card {
        border-color: #334155;
    }
    .dark .event-card:hover {
        border-color: #3B82F6;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-coming { background: #FEF3C7; color: #92400E; }
    .status-active { background: #D1FAE5; color: #065F46; }
    .status-finished { background: #E0E7FF; color: #3730A3; }
    
    .event-img {
        position: relative;
        height: 180px;
        display: flex; align-items: center; justify-content: center;
    }
    .event-img .icon-sym {
        width: 72px; height: 72px;
        color: rgba(255,255,255,0.2);
    }
    .badge-status {
        position: absolute;
        top: 12px; right: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
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
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Event</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Kelola semua event dan kegiatan FORMAT-R UNESA</p>
        </div>
        <a href="{{ route('admin.events.create') }}" class="btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Event</span>
        </a>
    </div>

    {{-- Filter Tabs --}}
    <div class="filter-tabs flex flex-wrap gap-2 mb-6" role="tablist">
        <button class="filter-tab active" data-filter="all" role="tab">Semua Event</button>
        <button class="filter-tab" data-filter="upcoming" role="tab">Akan Datang</button>
        <button class="filter-tab" data-filter="ongoing" role="tab">Sedang Berlangsung</button>
        <button class="filter-tab" data-filter="completed" role="tab">Selesai</button>
    </div>

    {{-- Event Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="eventsGrid">
        @foreach($events as $event)
        <div class="event-card event-item" data-status="{{ $event->status }}">
            <div class="event-img" style="{{ $event->image ? 'background-image:url('.Storage::url($event->image).'); background-size:cover; background-position:center;' : 'background: linear-gradient(135deg, #3b82f6, #8b5cf6);' }}">
                @if(!$event->image)
                    <svg class="icon-sym" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                @endif
                
                {{-- Status Badge --}}
                @if($event->status == 'upcoming')
                <div class="badge-status status-coming_soon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Akan Datang
                </div>
                @elseif($event->status == 'ongoing')
                <div class="badge-status status-started">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Sedang Berlangsung
                </div>
                @else
                <div class="badge-status status-finished">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Selesai
                </div>
                @endif
            </div>

            <div class="p-6">
                <div class="event-meta">
                    <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Acara FORMAT-R</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-2 mb-2 line-clamp-2">{{ $event->title }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2 mb-4">{{ $event->description }}</p>
                
                <div class="flex flex-wrap gap-3 text-sm text-gray-500 dark:text-gray-400">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        {{ $event->start_date ? $event->start_date->format('d M Y') : '' }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 10c0 7-9 12-9 12s-9-5-9-12a9 9 0 0118 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $event->location }}
                    </span>
                    <span class="flex items-center gap-1.5" title="Jumlah Peserta">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                        {{ $event->participant_count ? $event->participant_count . ' Orang' : '-' }}
                    </span>
                </div>
            </div>
            
            <div class="p-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between mt-auto bg-gray-50 dark:bg-slate-800">
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm">Hapus</button>
                    </form>
                </div>
                <a href="{{ route('admin.events.edit', $event->id) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    Edit Event
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Empty State --}}
<div id="emptyState" class="hidden text-center py-16">
    <svg class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 17a2 2 0 01-2 2H9a2 2 0 01-2-2v-1a2 2 0 00-2-2H5a2 2 0 01-2-2V7a2 2 0 012-2h1m8 0a2 2 0 012 2H3"/></svg>
    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Belum ada event</h3>
    <p class="mt-2 text-gray-500 dark:text-gray-400">Mulai buat event pertama Anda</p>
    <a href="{{ route('admin.events.create') }}" class="btn-primary mt-4 inline-flex">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        <span>Buat Event Pertama</span>
    </a>
</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.filter-tab');
    const items = document.querySelectorAll('.event-item');
    const emptyState = document.getElementById('emptyState');
    const eventsGrid = document.getElementById('eventsGrid');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Update active tab
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');
            let visibleCount = 0;

            document.querySelectorAll('.event-item').forEach(item => {
                if (filter === 'all' || item.getAttribute('data-status') === filter) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Toggle empty state
            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
            }
            if (eventsGrid) {
                eventsGrid.style.display = visibleCount === 0 ? 'none' : 'grid';
            }
        });
    });
});
</script>
@endpush
