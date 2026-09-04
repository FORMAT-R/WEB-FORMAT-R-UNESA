@extends('admin.layouts.app')

@section('title', 'Tambah Ulang Tahun - FORMAT-R UNESA')

@section('content')
<div class="space-y-8" x-data="{
    form: {
        photoPreview: ''
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
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Data Ulang Tahun</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Tambahkan informasi tanggal lahir pengurus.</p>
        </div>
        <a href="{{ route('admin.ultah.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('admin.ultah.store') }}" method="POST" enctype="multipart/form-data" @submit="isSubmitting = true" class="space-y-8">
        @csrf
        
        <section class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Detail Ulang Tahun</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Foto -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload Gambar (Rasio 4:5) <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 overflow-hidden flex-shrink-0">
                            <template x-if="form.photoPreview">
                                <img :src="form.photoPreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!form.photoPreview">
                                <svg class="w-8 h-8 text-gray-400 mx-auto mt-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                            </template>
                        </div>
                        <input type="file" name="photo" accept="image/*" @change="handlePhotoUpload" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-400">
                    </div>
                    @error('photo') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Pengurus <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                        placeholder="Nama lengkap pengurus">
                    @error('name') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" name="birth_date" required value="{{ old('birth_date') }}"
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    @error('birth_date') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Departemen -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Departemen/Biro <span class="text-red-500">*</span></label>
                    <input type="text" name="department" required value="{{ old('department') }}" class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white" placeholder="Cth: PSDM">
                    @error('department') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Jabatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jabatan</label>
                    <input type="text" name="position" value="{{ old('position') }}"
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                        placeholder="Cth: Kepala Departemen">
                    @error('position') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Pesan/Ucapan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pesan / Ucapan Ultah</label>
                    <textarea name="message" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Selamat ulang tahun!...">{{ old('message') }}</textarea>
                    @error('message') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Status Perayaan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status Perayaan (Tahun Ini)</label>
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="celebration_status" value="belum_dirayakan" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Belum Dirayakan</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="celebration_status" value="sudah_dirayakan" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Sudah Dirayakan</span>
                        </label>
                    </div>
                </div>
            </div>
        </section>

        <div class="flex items-center justify-end gap-4">
            <button type="button" @click="window.location.href = '{{ route('admin.ultah.index') }}'" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700 transition-all">
                Batal
            </button>
            <button type="submit" :disabled="isSubmitting" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-xl hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Data'"></span>
            </button>
        </div>
    </form>
</div>
@endsection
