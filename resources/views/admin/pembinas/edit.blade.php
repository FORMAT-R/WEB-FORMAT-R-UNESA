@extends('admin.layouts.app')

@section('title', 'Edit Pembina')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.pembinas.index') }}" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Pembina</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Perbarui data riwayat pembina.</p>
        </div>
    </div>

    <form id="pembinaForm" action="{{ route('admin.pembinas.update', $pembina->id) }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        @csrf
        @method('PUT')
        
        <div class="p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap & Gelar <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $pembina->name) }}" required
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Masa Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" name="term_period" value="{{ old('term_period', $pembina->term_period) }}" required placeholder="Misal: 2020 - Sekarang"
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Profil Pembina</label>
                @if($pembina->photo)
                    <div class="mb-3">
                        <img src="{{ Storage::url($pembina->photo) }}" class="w-24 h-24 rounded-lg object-cover border border-gray-200 dark:border-gray-700">
                    </div>
                @endif
                <input type="file" name="photo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Biografi / Profil Singkat <span class="text-red-500">*</span></label>
                <div id="biography-editor" class="w-full rounded-b-xl border-gray-300 bg-white text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-900 dark:text-white">{!! old('biography', $pembina->biography) !!}</div>
            </div>
        </div>

        <div class="bg-gray-50 dark:bg-gray-800/50 px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
            <a href="{{ route('admin.pembinas.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl text-sm transition-colors shadow-sm shadow-blue-600/20">Simpan Perubahan</button>
        </div>
    </form>
</div>

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    // Hapus baris alpine:init ini
    // document.addEventListener('alpine:init', () => {

        const quill = new Quill('#biography-editor', {
            theme: 'snow',
            placeholder: 'Tuliskan biografi atau profil singkat pembina...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });

        const form = document.getElementById('pembinaForm');
        form.addEventListener('submit', function(e) {
            // Cek apakah input hidden sudah ada agar tidak menggandakan saat submit ulang (jika ada error)
            let hiddenInput = document.querySelector('input[name="biography"]');
            
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'biography';
                form.appendChild(hiddenInput);
            }
            
            const html = quill.root.innerHTML;
            hiddenInput.value = html === '<p><br></p>' ? '' : html;
        });
        
    // });
</script>
<style>
    .ql-toolbar.ql-snow { border-radius: 0.75rem 0.75rem 0 0; border-color: #e5e7eb; background: #f9fafb; }
    .ql-container.ql-snow { border-radius: 0 0 0.75rem 0.75rem; border-color: #e5e7eb; background: #fff; min-height: 200px; font-family: 'Inter', sans-serif; font-size: 0.875rem; }
    .dark .ql-toolbar.ql-snow { border-color: #374151; background: #1f2937; }
    .dark .ql-container.ql-snow { border-color: #374151; background: #111827; color: #fff; }
    .dark .ql-stroke { stroke: #9ca3af; }
    .dark .ql-fill { fill: #9ca3af; }
    .dark .ql-picker { color: #9ca3af; }
</style>
@endpush
@endsection