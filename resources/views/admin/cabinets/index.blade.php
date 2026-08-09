@extends('admin.layouts.app')

@section('page-title', 'Riwayat Kabinet')

@section('content')
<div class="space-y-6" x-data="{ showAddModal: false, showEditModal: false, editForm: { id: '', name: '', period: '', vision: '', mission: '' } }">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Kabinet & Periode</h2>
        <button @click="showAddModal = true" class="btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Periode
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
        @foreach($cabinets as $cabinet)
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col h-full">
            <div class="relative w-full aspect-[5/4] bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-700 overflow-hidden">
                @if($cabinet->logo)
                    <img src="{{ Storage::url($cabinet->logo) }}" alt="{{ $cabinet->name }}" class="w-full h-full object-contain p-4">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="text-4xl text-gray-400 font-bold opacity-50">{{ substr($cabinet->name, 0, 1) }}</span>
                    </div>
                @endif
                
                <div class="absolute top-3 right-3 z-10">
                    @if($cabinet->is_active)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600 shadow-sm">
                            Arsip
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-4 flex-1 flex flex-col">
                <div class="mb-auto">
                    <a href="{{ route('admin.cabinets.show', $cabinet->id) }}" class="text-lg font-bold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors block mb-1 leading-tight">
                        {{ $cabinet->name }}
                    </a>
                    <p class="text-xs font-medium text-blue-600 dark:text-blue-400 mb-3">{{ $cabinet->period }}</p>

                    @if($cabinet->vision)
                        <div>
                            <h4 class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Visi</h4>
                            <p class="text-xs text-gray-700 dark:text-gray-300 line-clamp-3 leading-relaxed">{{ $cabinet->vision }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <form action="{{ route('admin.cabinets.toggle', $cabinet->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-[10px] font-medium hover:underline text-{{ $cabinet->is_active ? 'yellow' : 'green' }}-600 dark:text-{{ $cabinet->is_active ? 'yellow' : 'green' }}-400 transition-colors">
                            {{ $cabinet->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
                <div class="flex items-center gap-1.5">
                    <a href="{{ route('admin.cabinets.show', $cabinet->id) }}" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Lihat Anggota">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </a>
                    <button @click='editForm = { id: "{{ $cabinet->id }}", name: @json($cabinet->name), period: @json($cabinet->period), vision: @json($cabinet->vision ?? ""), mission: @json($cabinet->mission ?? "") }; showEditModal = true' class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </button>
                    <form action="{{ route('admin.cabinets.destroy', $cabinet->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kabinet ini beserta semua anggotanya?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
        
        @if($cabinets->isEmpty())
        <div class="col-span-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-8 text-center text-gray-500 dark:text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <p>Belum ada data kabinet.</p>
        </div>
        @endif
    </div>

    <!-- Add Modal -->
    <div x-show="showAddModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-900 opacity-50" @click="showAddModal = false"></div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full relative z-10">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Tambah Kabinet / Periode</h3>
                <form action="{{ route('admin.cabinets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Kabinet</label>
                        <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Periode (contoh: 2026/2027)</label>
                        <input type="text" name="period" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo Kabinet</label>
                        <input type="file" name="logo" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Visi Kabinet</label>
                        <textarea name="vision" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Misi Kabinet</label>
                        <textarea name="mission" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showAddModal = false" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-900 opacity-50" @click="showEditModal = false"></div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full relative z-10">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Edit Kabinet</h3>
                <form :action="`/admin/cabinets/${editForm.id}`" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Kabinet</label>
                        <input type="text" name="name" x-model="editForm.name" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Periode</label>
                        <input type="text" name="period" x-model="editForm.period" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo Kabinet (Kosongkan jika tidak diubah)</label>
                        <input type="file" name="logo" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Visi Kabinet</label>
                        <textarea name="vision" x-model="editForm.vision" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Misi Kabinet</label>
                        <textarea name="mission" x-model="editForm.mission" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showEditModal = false" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
