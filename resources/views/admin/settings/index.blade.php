@extends('admin.layouts.app')

@section('title', 'Pengaturan Website - FORMAT-R UNESA')

@section('content')
<div class="space-y-8" x-data="{
    activeTab: '{{ auth()->user()->role === 'superadmin' ? 'general' : 'social' }}'
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengaturan Website</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Konfigurasi umum informasi dan tampilan website.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/50 text-emerald-600 dark:text-emerald-400 text-sm p-4 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabs -->
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
            @can('is-superadmin')
            <button @click="activeTab = 'general'"
                :class="activeTab === 'general' ? 'border-blue-500 text-blue-600 dark:text-blue-500' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Informasi Umum
            </button>
            <button @click="activeTab = 'tentang'"
                :class="activeTab === 'tentang' ? 'border-blue-500 text-blue-600 dark:text-blue-500' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Tentang FORMAT
            </button>
            @endcan
            
            <button @click="activeTab = 'social'"
                :class="activeTab === 'social' ? 'border-blue-500 text-blue-600 dark:text-blue-500' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Sosial Media & Kontak
            </button>

            @can('is-superadmin')
            <button @click="activeTab = 'system'"
                :class="activeTab === 'system' ? 'border-blue-500 text-blue-600 dark:text-blue-500' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Sistem
            </button>
            @endcan
        </nav>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf

        @can('is-superadmin')
        <!-- Tab: Informasi Umum -->
        <section x-show="activeTab === 'general'" x-cloak class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Website</label>
                    <input type="text" name="siteName" value="{{ $settings['siteName'] ?? 'FORMAT-R UNESA' }}" required
                        class="w-full md:w-1/2 rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi Singkat (SEO Meta Description)</label>
                    <textarea rows="3" name="siteDescription"
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">{{ $settings['siteDescription'] ?? '' }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo Website Utama</label>
                    <div class="mt-1 flex items-center gap-4">
                        <div class="h-16 w-16 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center border border-gray-200 dark:border-gray-600 overflow-hidden">
                            @if(isset($settings['siteLogo']))
                                <img src="{{ Storage::url($settings['siteLogo']) }}" alt="Logo" class="w-full h-full object-contain">
                            @else
                                <span class="text-xs text-gray-500">Logo</span>
                            @endif
                        </div>
                        <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                    </div>
                </div>
            </div>
        </section>

        <!-- Tab: Tentang FORMAT (Visi Misi & Kabinet) -->
        <section x-show="activeTab === 'tentang'" x-cloak class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tentang FORMAT (Deskripsi Singkat)</label>
                    <textarea rows="4" name="aboutFormat"
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">{{ $settings['aboutFormat'] ?? 'FORMAT-R adalah organisasi mahasiswa tingkat program studi yang menaungi seluruh mahasiswa di lingkup jurusan.' }}</textarea>
                </div>
                <hr class="border-gray-200 dark:border-gray-700">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Kabinet</label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Nama kabinet ini akan muncul di halaman Homepage dan Departemen.</p>
                    <input type="text" name="cabinetName" value="{{ $settings['cabinetName'] ?? 'Kolaborasi Asa' }}"
                        class="w-full md:w-1/2 rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo Kabinet</label>
                    <div class="mt-1 flex items-center gap-4">
                        <div class="h-20 w-20 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center border border-gray-200 dark:border-gray-600 overflow-hidden">
                            @if(isset($settings['cabinetLogo']))
                                <img src="{{ Storage::url($settings['cabinetLogo']) }}" alt="Logo Kabinet" class="w-full h-full object-contain">
                            @else
                                <span class="text-xs text-gray-500 text-center">Logo Kabinet</span>
                            @endif
                        </div>
                        <input type="file" name="cabinet_logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Visi Kabinet</label>
                    <textarea rows="3" name="cabinetVision"
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">{{ $settings['cabinetVision'] ?? '' }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Misi Kabinet (Pisahkan dengan baris baru / enter)</label>
                    <textarea rows="5" name="cabinetMission"
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">{{ $settings['cabinetMission'] ?? '' }}</textarea>
                </div>
            </div>
        </section>
        @endcan

        <!-- Tab: Sosial Media & Kontak -->
        <section x-show="activeTab === 'social'" x-cloak class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Kontak</label>
                    <input type="email" name="contactEmail" value="{{ $settings['contactEmail'] ?? 'info@formatrunesa.com' }}"
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nomor Telepon/WhatsApp</label>
                    <input type="text" name="contactPhone" value="{{ $settings['contactPhone'] ?? '+62 812-3456-7890' }}"
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Link Instagram</label>
                    <input type="url" name="instagram" value="{{ $settings['instagram'] ?? 'https://instagram.com/formatr_unesa' }}"
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Link YouTube</label>
                    <input type="url" name="youtube" value="{{ $settings['youtube'] ?? 'https://youtube.com/@formatrunesa' }}"
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat Sekretariat</label>
                    <textarea rows="2" name="address"
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">{{ $settings['address'] ?? 'Ketintang, Surabaya' }}</textarea>
                </div>
            </div>
        </section>

        @can('is-superadmin')
        <!-- Tab: Sistem -->
        <section x-show="activeTab === 'system'" x-cloak class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <div class="space-y-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Mode Pemeliharaan (Maintenance Mode)</h3>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="hidden" name="maintenanceMode" value="0">
                        <input type="checkbox" name="maintenanceMode" value="1" class="sr-only peer" {{ ($settings['maintenanceMode'] ?? '0') === '1' ? 'checked' : '' }}>
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Aktifkan Maintenance Mode</span>
                    </label>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Aktifkan ini jika Anda sedang melakukan perbaikan besar pada website.</p>
                </div>
            </div>
        </section>
        @endcan

        <div class="flex items-center justify-end">
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-xl hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition-all">
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection
