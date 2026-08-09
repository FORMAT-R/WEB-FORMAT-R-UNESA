@extends('admin.layouts.app')

@section('title', 'Riwayat Pembina')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Pembina</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola data pembina yang menjabat di FORMAT-R UNESA</p>
        </div>
        <a href="{{ route('admin.pembinas.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm shadow-blue-600/20 gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Pembina
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
        @forelse($pembinas as $p)
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col h-full">
                <div class="relative w-full aspect-[4/5] bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-700 overflow-hidden">
                    @if($p->photo)
                        <img src="{{ Storage::url($p->photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10zm-7 14a7 7 0 1114 0H5z" clip-rule="evenodd" /></svg>
                        </div>
                    @endif
                    
                    @if($p->is_active)
                        <div class="absolute top-4 right-4 z-10">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800 shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                Aktif
                            </span>
                        </div>
                    @endif
                </div>

                <div class="p-4 flex-1 flex flex-col">
                    <div class="mb-auto">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ $p->name }}</h3>
                        <p class="text-xs font-medium text-blue-600 dark:text-blue-400 mb-3">{{ $p->term_period }}</p>
                        
                        <div class="text-xs text-gray-600 dark:text-gray-400 line-clamp-3 leading-relaxed">
                            {!! strip_tags($p->biography) !!}
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        @if(!$p->is_active)
                            <form action="{{ route('admin.pembinas.toggle', $p->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline transition-colors" onclick="return confirm('Jadikan Pembina ini sebagai yang aktif di halaman depan?');">
                                    Jadikan Aktif
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.pembinas.edit', $p->id) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </a>
                        <form action="{{ route('admin.pembinas.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pembina ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-8 text-center text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <p>Belum ada data pembina.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection