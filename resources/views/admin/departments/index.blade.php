@extends('admin.layouts.app')

@section('page-title', 'Manajemen Departemen')
@section('content')
@push('styles')
<style>
    .dept-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.2s;
    }
    .dept-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(14, 37, 69, 0.1);
        border-color: #3B82F6;
    }
    .dark .dept-card {
        background: #1E293B;
        border-color: #334155;
    }
    .dark .dept-card:hover {
        border-color: #3B82F6;
    }
    
    .dept-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .color-swatch {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        border: 2px solid transparent;
        transition: all 0.2s;
    }
    .color-swatch.selected {
        border-color: #1E293B;
        transform: scale(1.1);
    }
    .dark .color-swatch.selected {
        border-color: #F8FAFC;
    }
    
    .icon-preview {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #F1F5F9;
        color: #64748B;
    }
    .dark .icon-preview {
        background: #334155;
        color: #94A3B8;
    }
</style>
@endpush

<div class="space-y-8">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Departemen</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Kelola departemen dan anggotanya di FORMAT-R UNESA</p>
        </div>
        <a href="{{ route('admin.departemen.create') }}" class="btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Departemen</span>
        </a>
    </div>

    {{-- Departemen Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="departemenGrid">
        @foreach($departments as $dept)
        <div class="dept-card">
            <div class="flex items-start gap-4">
                @if($dept->image)
                    <img src="{{ Storage::url($dept->image) }}" class="w-14 h-14 rounded-2xl object-cover border border-gray-100 dark:border-gray-700" alt="{{ $dept->name }}">
                @else
                    <div class="dept-icon bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-sm flex-shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 21v-6h6v6M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                        </svg>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    @if($dept->abbreviation)
                        <div class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-0.5">{{ $dept->abbreviation }}</div>
                    @endif
                    <h3 class="font-semibold text-gray-900 dark:text-white truncate" title="{{ $dept->name }}">{{ $dept->name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 truncate" title="{{ $dept->description }}">{{ $dept->description ?? 'Tidak ada deskripsi' }}</p>
                    <div class="flex items-center gap-3 mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                            {{ $dept->abbreviation ?: (preg_replace('/[^A-Z]/', '', ucwords($dept->name)) ?: 'DEPT') }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $dept->members_count }} anggota</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('admin.departemen.show', $dept->id) }}" class="flex-1 btn-secondary text-center text-sm py-2">Lihat Detail</a>
                <a href="{{ route('admin.departemen.edit', $dept->id) }}" class="btn-primary text-center text-sm py-2">Edit</a>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Empty State --}}
    @if($departments->isEmpty())
    <div class="text-center py-16">
        <svg class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v11a2 2 0 01-2 2z"/></svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Belum ada departemen</h3>
        <p class="mt-2 text-gray-500 dark:text-gray-400">Mulai buat departemen pertama Anda</p>
        <a href="{{ route('admin.departemen.create') }}" class="btn-primary mt-4 inline-flex">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            <span>Buat Departemen Pertama</span>
        </a>
    </div>
    @endif
</div>
@endsection
