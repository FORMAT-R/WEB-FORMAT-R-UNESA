@extends('admin.layouts.app')

@section('title', 'Edit Penghargaan - FORMAT-R UNESA')

@section('content')
<div class="space-y-8" x-data="{
    form: {
        photoPreview: '{{ $award->photo ? Storage::url($award->photo) : '' }}'
    },
    isSubmitting: false,
    handlePhotoUpload(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.form.photoPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Fungsionaris Terbaik</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Perbarui data fungsionaris terbaik (Fungter).</p>
        </div>
        <a href="{{ route('admin.penghargaan.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.penghargaan.update', $award->id) }}" method="POST" enctype="multipart/form-data" @submit="isSubmitting = true" class="space-y-8">
        @csrf
        @method('PUT')
        
        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-xl">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        

        <section class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Detail Fungsionaris Terbaik (Manual)</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Foto -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Fungsionaris (Opsional)</label>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 overflow-hidden flex-shrink-0">
                            <template x-if="form.photoPreview">
                                <img :src="form.photoPreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!form.photoPreview">
                                <svg class="w-8 h-8 text-gray-400 mx-auto mt-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                            </template>
                        </div>
                        <input type="file" name="photo" accept="image/*" @change="handlePhotoUpload" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-400">
                    </div>
                </div>

                <!-- Penerima -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Fungsionaris <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $award->name) }}"
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>

                <!-- Departemen/Biro -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Departemen/Biro <span class="text-red-500">*</span></label>
                    <input type="text" name="department" value="{{ old('department', $award->department) }}" class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white" placeholder="Cth: PSDM">
                </div>

                <!-- Bulan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bulan <span class="text-red-500">*</span></label>
                    <select name="month" required class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        <option value="">Pilih Bulan</option>
                        @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $month)
                            <option value="{{ $month }}" {{ old('month', $award->month) == $month ? 'selected' : '' }}>{{ $month }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tahun -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tahun <span class="text-red-500">*</span></label>
                    <input type="number" name="year" required min="2000" max="2100" value="{{ old('year', $award->year) }}"
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>

            </div>
        </section>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4">
            <button type="button" @click="window.location.href = '{{ route('admin.penghargaan.index') }}'"
                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700 transition-all">
                Batal
            </button>
            <button type="submit" :disabled="isSubmitting"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-xl hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                <svg x-show="isSubmitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Fungsionaris'"></span>
            </button>
        </div>
    </form>
</div>
@endsection
