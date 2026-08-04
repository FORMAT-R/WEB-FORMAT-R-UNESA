@extends('admin.layouts.app')

@section('page-title', 'Detail Kabinet')

@section('content')
@push('styles')
<style>
    .member-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
    }
    .member-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(14, 37, 69, 0.1);
        border-color: #3B82F6;
    }
    .dark .member-card {
        background: #1E293B;
        border-color: #334155;
    }
    .dark .member-card:hover {
        border-color: #3B82F6;
    }
    
    .member-photo {
        width: 100%;
        padding-top: 100%; /* 100% aspect ratio */
        position: relative;
        background: #F1F5F9;
        overflow: hidden;
    }
    .dark .member-photo {
        background: #0F172A;
    }
    .member-photo img {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        object-fit: cover;
        object-position: top center;
    }
    
    .member-info {
        padding: 1rem;
        text-align: center;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .member-name {
        font-weight: 700;
        font-size: 1.05rem;
        color: #1E293B;
        margin-bottom: 0.25rem;
    }
    .dark .member-name { color: #F8FAFC; }
    
    .member-position {
        font-size: 0.85rem;
        color: #2563EB;
        font-weight: 600;
        background: #EFF6FF;
        padding: 4px 12px;
        border-radius: 999px;
        display: inline-block;
        margin: 0 auto 12px;
    }
    .dark .member-position {
        color: #60A5FA;
        background: rgba(59, 130, 246, 0.1);
    }
</style>
@endpush

<div class="space-y-8">
    {{-- Page Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm p-6 sm:p-8 relative">
        <div class="flex items-center gap-6">
            <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center flex-shrink-0 border border-gray-200 dark:border-gray-600 overflow-hidden">
                @if($cabinet->logo)
                    <img src="{{ Storage::url($cabinet->logo) }}" alt="Logo {{ $cabinet->name }}" class="w-full h-full object-contain p-2">
                @else
                    <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                @endif
            </div>
            <div>
                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $cabinet->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border-green-200 dark:border-green-800/50' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600' }} border mb-2">
                    {{ $cabinet->is_active ? 'Periode Aktif' : 'Riwayat / Nonaktif' }}
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $cabinet->name }}</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Periode: {{ $cabinet->period }}</p>
            </div>
        </div>
        
        <a href="{{ route('admin.cabinets.index') }}" class="absolute top-6 right-6 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 px-3 py-1.5 rounded-lg text-sm font-medium transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    {{-- Members Grouped By Department --}}
    @if($groupedMembers->isEmpty())
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak Ada Anggota</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Belum ada anggota yang terdaftar pada periode kabinet ini.</p>
        </div>
    @else
        @foreach($groupedMembers as $departmentId => $members)
            @php
                $department = $members->first()->department;
            @endphp
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-3 border-b border-gray-200 dark:border-gray-700 pb-2">
                    @if($department && $department->image)
                        <img src="{{ Storage::url($department->image) }}" alt="Logo {{ $department->name }}" class="w-8 h-8 rounded-lg object-cover">
                    @else
                        <span class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </span>
                    @endif
                    {{ $department ? $department->name : 'Tanpa Departemen' }}
                </h2>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
                    @foreach($members as $member)
                        <div class="member-card">
                            <div class="member-photo">
                                @if($member->photo)
                                    <img src="{{ Storage::url($member->photo) }}" alt="{{ $member->name }}">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=random" alt="{{ $member->name }}">
                                @endif
                            </div>
                            <div class="member-info">
                                <h3 class="member-name">{{ $member->name }}</h3>
                                <span class="member-position">{{ $member->position }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
