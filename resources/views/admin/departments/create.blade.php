@extends('admin.layouts.app')

@section('title', 'Tambah Departemen - FORMAT-R UNESA')

@section('content')
<div class="space-y-8" x-data="{
    form: {
        fotoPreview: ''
    },
    handlePhotoUpload(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.form.fotoPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    },
    isSubmitting: false
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Departemen</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Tambahkan departemen atau biro baru dalam organisasi.</p>
        </div>
        <a href="{{ route('admin.departemen.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.departemen.store') }}" method="POST" enctype="multipart/form-data" @submit="isSubmitting = true" class="space-y-8">
        @csrf
        
        <!-- Informasi Dasar -->
        <section class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Informasi Dasar</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Departemen -->
                <div class="md:col-span-1">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Departemen <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" required
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                        placeholder="Contoh: Komunikasi dan Informasi">
                </div>

                <!-- Singkatan Departemen -->
                <div class="md:col-span-1">
                    <label for="abbreviation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Singkatan</label>
                    <input type="text" id="abbreviation" name="abbreviation"
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                        placeholder="Contoh: KOMINFO">
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                        placeholder="Deskripsi singkat mengenai departemen ini..."></textarea>
                </div>
                
                <!-- Foto Departemen -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo / Gambar Utama Departemen</label>
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-32 h-32 bg-gray-100 dark:bg-gray-700 rounded-xl overflow-hidden border border-gray-300 dark:border-gray-600 flex items-center justify-center">
                            <template x-if="form.fotoPreview">
                                <img :src="form.fotoPreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!form.fotoPreview">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </template>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image" @change="handlePhotoUpload" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Unggah logo atau gambar utama departemen. Format: JPG, PNG. Maksimal 10MB.</p>
                        </div>
                    </div>
                </div>

                <!-- Foto Dokumentasi 1 -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Dokumentasi 1 (Polaroid Kiri)</label>
                    <input type="file" name="doc_image_1" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <!-- Foto Dokumentasi 2 -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Dokumentasi 2 (Polaroid Kanan)</label>
                    <input type="file" name="doc_image_2" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>
        </section>

        <!-- Program Kerja Departemen -->
        <section class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8" x-data="{
            prokers: [{ id: Date.now(), no: '01', name: '', description: '' }],
            addProker() {
                const newNo = (this.prokers.length + 1).toString().padStart(2, '0');
                this.prokers.push({ id: Date.now(), no: newNo, name: '', description: '' });
            },
            removeProker(index) {
                if (this.prokers.length > 1) {
                    this.prokers.splice(index, 1);
                    // Re-number
                    this.prokers.forEach((p, i) => {
                        p.no = (i + 1).toString().padStart(2, '0');
                    });
                }
            }
        }">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Program Kerja Departemen</h2>
                <button type="button" @click="addProker" class="inline-flex items-center justify-center gap-2 px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-50 border border-transparent rounded-lg hover:bg-blue-100 dark:text-blue-300 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Proker
                </button>
            </div>
            
            <div class="space-y-4">
                <template x-for="(proker, index) in prokers" :key="proker.id">
                    <div class="p-4 rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 relative">
                        <div class="absolute top-4 right-4">
                            <button type="button" @click="removeProker(index)" x-show="prokers.length > 1" class="text-red-500 hover:text-red-700 p-1 rounded-md hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors" title="Hapus Proker">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-4 pr-8">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-10 flex items-center justify-center bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-bold text-gray-500 dark:text-gray-400" x-text="proker.no"></div>
                                <input type="hidden" :name="`prokers[${index}][no]`" :value="proker.no">
                                
                                <div class="flex-1">
                                    <input type="text" :name="`prokers[${index}][name]`" x-model="proker.name" required
                                        class="w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                                        placeholder="Nama Program Kerja">
                                </div>
                            </div>
                            
                            <div>
                                <textarea :name="`prokers[${index}][description]`" x-model="proker.description" rows="2"
                                    class="w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                                    placeholder="Deskripsi singkat..."></textarea>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </section>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4">
            <button type="button" @click="window.location.href = '{{ route('admin.departemen.index') }}'"
                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700 transition-all">
                Batal
            </button>
            <button type="submit" :disabled="isSubmitting"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-xl hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                <svg x-show="isSubmitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Departemen'"></span>
            </button>
        </div>
    </form>
</div>
@endsection
