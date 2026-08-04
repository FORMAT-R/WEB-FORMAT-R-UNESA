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

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Kabinet</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Periode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($cabinets as $cabinet)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('admin.cabinets.show', $cabinet->id) }}" class="flex items-center gap-3 group">
                            <div class="h-10 w-10 flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center overflow-hidden">
                                @if($cabinet->logo)
                                    <img src="{{ Storage::url($cabinet->logo) }}" alt="" class="h-full w-full object-contain">
                                @else
                                    <span class="text-xs text-gray-500 font-bold">{{ substr($cabinet->name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="text-sm font-medium text-blue-600 group-hover:underline dark:text-blue-400">
                                {{ $cabinet->name }}
                            </div>
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        {{ $cabinet->period }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($cabinet->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Arsip / Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <form action="{{ route('admin.cabinets.toggle', $cabinet->id) }}" method="POST" class="inline-block mr-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-{{ $cabinet->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $cabinet->is_active ? 'yellow' : 'green' }}-900">
                                {{ $cabinet->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        
                        <button @click='editForm = { id: "{{ $cabinet->id }}", name: @json($cabinet->name), period: @json($cabinet->period), vision: @json($cabinet->vision ?? ""), mission: @json($cabinet->mission ?? "") }; showEditModal = true' class="text-blue-600 hover:text-blue-900 mr-2">Edit</button>
                        
                        <form action="{{ route('admin.cabinets.destroy', $cabinet->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus kabinet ini beserta semua anggotanya?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
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
