@extends('admin.layouts.app')

@section('page-title', 'Detail Departemen')

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
        padding-top: 100%; /* 100% aspect ratio untuk square/portrait */
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
    
    .member-actions {
        display: flex;
        gap: 8px;
        margin-top: auto;
        padding-top: 12px;
        border-top: 1px solid #E2E8F0;
    }
    .dark .member-actions { border-color: #334155; }
    
    .btn-action {
        flex: 1;
        padding: 6px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-align: center;
        transition: all 0.2s;
    }
    .btn-edit { background: #F1F5F9; color: #475569; }
    .btn-edit:hover { background: #E2E8F0; color: #1E293B; }
    .dark .btn-edit { background: #334155; color: #CBD5E1; }
    .dark .btn-edit:hover { background: #475569; color: #F8FAFC; }
    
    .btn-delete { background: #FEF2F2; color: #DC2626; }
    .btn-delete:hover { background: #FEE2E2; color: #B91C1C; }
    .dark .btn-delete { background: rgba(220, 38, 38, 0.1); color: #F87171; }
    .dark .btn-delete:hover { background: rgba(220, 38, 38, 0.2); }
</style>
@endpush

<div class="space-y-8" x-data="{
    showModal: false,
    editingId: null,
    sorotanStatus: '',
    sorotanFaceCropped: false,
    form: {
        name: '',
        position: '',
        cabinet_id: '{{ $selectedCabinetId }}',
        photo: ''
    },
    
    openAddModal() {
        this.editingId = null;
        this.sorotanStatus = '';
        this.sorotanFaceCropped = false;
        this.form = { name: '', position: 'Staff Muda', cabinet_id: '{{ $selectedCabinetId }}', photo: '', photo_sorotan: '' };
        this.showModal = true;
    },
    
    openEditModal(member) {
        this.editingId = member.id;
        this.sorotanStatus = member.photo_sorotan_url ? 'Foto sorotan sudah ada — upload baru untuk mengganti.' : '';
        this.sorotanFaceCropped = false;
        this.form = { ...member, photo: member.photo_url, photo_sorotan: member.photo_sorotan_url };
        this.showModal = true;
    },
    
    handlePhotoUpload(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (ev) => {
                this.form.photo = ev.target.result;
            };
            reader.readAsDataURL(file);
        }
    },

    async handleSorotanUpload(e) {
        const file = e.target.files[0];
        if (!file) return;

        this.sorotanStatus = '⏳ Mendeteksi wajah...';
        this.sorotanFaceCropped = false;

        // Baca file sebagai data URL untuk preview & deteksi
        const dataUrl = await new Promise(res => {
            const r = new FileReader();
            r.onload = ev => res(ev.target.result);
            r.readAsDataURL(file);
        });

        // Coba face detection via face-api.js
        const cropped = await window.detectAndCropFace(dataUrl);

        if (cropped) {
            this.form.photo_sorotan = cropped;
            this.sorotanFaceCropped = true;
            this.sorotanStatus = '✅ Wajah terdeteksi — foto otomatis disesuaikan!';
        } else {
            this.form.photo_sorotan = dataUrl;
            this.sorotanFaceCropped = false;
            this.sorotanStatus = '⚠️ Wajah tidak terdeteksi — menggunakan foto asli (fallback).';
        }
    },

    deleteId: null,
    deleteMember(id) {
        if(confirm('Apakah Anda yakin ingin menghapus anggota ini?')) {
            this.deleteId = id;
            this.$nextTick(() => {
                this.$refs.deleteForm.submit();
            });
        }
    }
}">
    <!-- Hidden form for deleting member -->
    <form x-ref="deleteForm" :action="'/admin/members/' + deleteId" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    {{-- Page Header & Department Info --}}
    {{-- Page Header & Department Info --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        <div class="h-48 md:h-64 relative bg-gray-200 dark:bg-gray-900 overflow-hidden">
            <img src="{{ $department->image ? Storage::url($department->image) : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1200&q=80' }}" alt="Cover Departemen" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 w-full p-6 sm:p-8 flex items-end gap-6">
                <div class="w-20 h-20 sm:w-24 sm:h-24 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg flex-shrink-0 border-4 border-white dark:border-gray-800">
                    <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
                <div class="text-white pb-2">
                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/30 text-blue-100 border border-blue-400/30 mb-2">
                        {{ preg_replace('/[^A-Z]/', '', ucwords($department->name)) ?: 'DEPT' }}
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold">{{ $department->name }}</h1>
                    <p class="text-blue-100/80 mt-1 max-w-2xl text-sm sm:text-base hidden sm:block">{{ $department->description ?? 'Tidak ada deskripsi' }}</p>
                </div>
            </div>
            
            <a href="{{ route('admin.departemen.index') }}" class="absolute top-6 left-6 text-white/80 hover:text-white bg-black/20 hover:bg-black/40 backdrop-blur px-3 py-1.5 rounded-lg text-sm font-medium transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            
            <a href="{{ route('admin.departemen.edit', $department->id) }}" class="absolute top-6 right-6 text-white/80 hover:text-white bg-blue-600/80 hover:bg-blue-600 backdrop-blur px-4 py-1.5 rounded-lg text-sm font-medium transition-all shadow-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Edit Departemen
            </a>
        </div>
    </div>

    {{-- Members Section --}}
    <div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Daftar Anggota Departemen
                </h2>
                
                <form method="GET" action="{{ route('admin.departemen.show', $department->id) }}" class="flex items-center">
                    <select name="cabinet_id" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm py-1.5 px-3 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                        @foreach($cabinets as $cabinet)
                            <option value="{{ $cabinet->id }}" {{ $selectedCabinetId == $cabinet->id ? 'selected' : '' }}>
                                {{ $cabinet->name }} ({{ $cabinet->period }}) {{ $cabinet->is_active ? '- Aktif' : '' }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <button class="btn-primary" @click="openAddModal()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Anggota</span>
            </button>
        </div>

        {{-- Members Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
            @foreach($department->members as $member)
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
                        <div class="member-actions">
                            <button type="button" class="btn-action btn-edit" @click="openEditModal({ id: {{ $member->id }}, name: '{{ addslashes($member->name) }}', position: '{{ addslashes($member->position) }}', cabinet_id: '{{ $member->cabinet_id }}', photo_url: '{{ $member->photo ? Storage::url($member->photo) : '' }}', photo_sorotan_url: '{{ $member->photo_sorotan ? Storage::url($member->photo_sorotan) : '' }}' })">Edit</button>
                            <button type="button" class="btn-action btn-delete" @click="deleteMember({{ $member->id }})">Hapus</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Modal Form Anggota --}}
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm" @click="showModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="showModal" 
                x-transition:enter="ease-out duration-300" 
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                x-transition:leave="ease-in duration-200" 
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl dark:bg-gray-800 sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 border border-gray-200 dark:border-gray-700">
                
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white" x-text="editingId ? 'Edit Anggota' : 'Tambah Anggota'"></h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form :action="editingId ? '/admin/members/' + editingId : '{{ route('admin.members.store') }}'" method="POST" enctype="multipart/form-data" class="space-y-4" id="memberForm" @submit="handleFormSubmit">
                    @csrf
                    <template x-if="editingId">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <input type="hidden" name="department_id" value="{{ $department->id }}">
                    <input type="hidden" name="cabinet_id" x-model="form.cabinet_id">
                    <!-- Flag: apakah foto sorotan sudah di-crop face di browser -->
                    <input type="hidden" name="face_cropped" :value="sorotanFaceCropped ? '1' : '0'">
                    <!-- Input tersembunyi pembawa data foto sorotan hasil face-crop (base64) -->
                    <input type="hidden" name="photo_sorotan_cropped" x-ref="sorotanCroppedInput" :value="sorotanFaceCropped ? form.photo_sorotan : ''">

                    <!-- Foto Profil -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Profil (Card Tim Kami) <span class="text-xs text-gray-500 font-normal ml-1">Sebaiknya Pasfoto/Formal</span></label>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 overflow-hidden flex-shrink-0">
                                <template x-if="form.photo">
                                    <img :src="form.photo" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!form.photo">
                                    <svg class="w-8 h-8 text-gray-400 mx-auto mt-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                                </template>
                            </div>
                            <input type="file" name="photo" accept="image/*" @change="handlePhotoUpload" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>

                    <!-- Foto Sorotan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Foto Sorotan Pengurus
                            <span class="text-xs text-gray-500 font-normal ml-1">Otomatis Remove BG + Standarisasi Wajah</span>
                        </label>
                        <div class="flex items-start gap-4">
                            <!-- Preview area lebih besar agar proporsi portrait terlihat -->
                            <div class="w-20 h-28 rounded-xl bg-gray-100 dark:bg-gray-700 border-2 border-dashed border-gray-300 dark:border-gray-600 overflow-hidden flex-shrink-0 relative">
                                <template x-if="form.photo_sorotan">
                                    <img :src="form.photo_sorotan" class="w-full h-full object-cover object-top">
                                </template>
                                <template x-if="!form.photo_sorotan">
                                    <div class="flex flex-col items-center justify-center h-full">
                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                </template>
                                <!-- Badge face detected -->
                                <template x-if="sorotanFaceCropped">
                                    <div class="absolute bottom-0 left-0 right-0 bg-green-500 text-white text-center" style="font-size:8px; padding:2px;">✓ AI Crop</div>
                                </template>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="photo_sorotan" accept="image/*" @change="handleSorotanUpload" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                <!-- Status deteksi wajah -->
                                <p x-show="sorotanStatus" x-text="sorotanStatus"
                                   :class="sorotanFaceCropped ? 'text-green-600 dark:text-green-400' : 'text-amber-600 dark:text-amber-400'"
                                   class="text-xs mt-2 font-medium"></p>
                                <p class="text-xs text-gray-400 mt-1">Disarankan: foto setengah badan, pencahayaan cukup, wajah terlihat jelas.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" x-model="form.name" required class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white" placeholder="Masukkan nama...">
                    </div>

                    <!-- Jabatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jabatan</label>
                        <select name="position" x-model="form.position" required class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                            <option value="">Pilih Jabatan...</option>
                            @if(strtolower($department->slug) === 'bph' || strtolower($department->name) === 'bph')
                            @else
                            <option value="Ketua Umum">Ketua Umum</option>
                                <option value="Wakil Ketua Umum">Wakil Ketua Umum</option>
                                <option value="Bendahara Umum">Bendahara Umum</option>
                                <option value="Bendahara 1">Bendahara 1</option>
                                <option value="Bendahara 2">Bendahara 2</option>
                                <option value="Sekretaris Umum">Sekretaris Umum</option>
                                <option value="Sekretaris 1">Sekretaris 1</option>
                                <option value="Sekretaris 2">Sekretaris 2</option>
                                <option value="Ketua Departemen">Ketua Departemen</option>
                                <option value="Wakil Departemen">Wakil Departemen</option>
                                <option value="Staf Ahli">Staf Ahli</option>
                                <option value="Staf">Staf</option>
                            @endif
                        </select>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-xl hover:bg-blue-700">Simpan Anggota</button>
                    </div>
                </form>
            </div>
        </div>
</div>

@push('scripts')
{{-- face-api.js: face detection di browser, tidak perlu install apapun di server --}}
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
(function() {
    // ========================================================
    // KONSTANTA KONFIGURASI FACE-CROP
    // Target rasio output: portrait setengah badan (1 : 1.5)
    // Kepala akan diposisikan ~15% dari atas frame
    // ========================================================
    const TARGET_W = 600;        // lebar output (px)
    const TARGET_H = 900;        // tinggi output = rasio 1:1.5
    const HEAD_TOP_RATIO = 0.10; // kepala mulai dari 10% dari atas
    const HEAD_SIZE_RATIO = 0.35; // lebar kepala ~35% dari lebar frame

    let modelsLoaded = false;
    let modelsLoading = false;

    async function loadModels() {
        if (modelsLoaded) return true;
        if (modelsLoading) {
            // Tunggu hingga loading selesai
            while (modelsLoading) await new Promise(r => setTimeout(r, 100));
            return modelsLoaded;
        }
        modelsLoading = true;
        try {
            // Model kecil: hanya untuk deteksi lokasi wajah (bukan landmark/recognition)
            const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model';
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68TinyNet.loadFromUri(MODEL_URL),
            ]);
            modelsLoaded = true;
        } catch (err) {
            console.warn('[FaceAPI] Gagal load model:', err);
            modelsLoaded = false;
        }
        modelsLoading = false;
        return modelsLoaded;
    }

    /**
     * Deteksi wajah pada gambar (dataUrl), lakukan smart crop
     * sehingga posisi & ukuran kepala seragam di semua foto.
     * 
     * @param {string} dataUrl - Data URL gambar asli
     * @returns {string|null}  - Data URL hasil crop, atau null jika gagal
     */
    window.detectAndCropFace = async function(dataUrl) {
        try {
            const ok = await loadModels();
            if (!ok) return null;

            // Buat elemen img dari dataUrl
            const img = await new Promise((res, rej) => {
                const el = new Image();
                el.onload = () => res(el);
                el.onerror = rej;
                el.src = dataUrl;
            });

            // Deteksi wajah dengan landmark (untuk dapat bounding box presisi)
            const detection = await faceapi
                .detectSingleFace(img, new faceapi.TinyFaceDetectorOptions({ scoreThreshold: 0.4 }))
                .withFaceLandmarks(true);

            if (!detection) {
                console.warn('[FaceAPI] Tidak ada wajah terdeteksi.');
                return null;
            }

            const box  = detection.detection.box; // { x, y, width, height }
            const iW   = img.naturalWidth;
            const iH   = img.naturalHeight;

            // ── Hitung area crop di foto asli ──────────────────────────────
            // Kita ingin: kepala memiliki lebar = HEAD_SIZE_RATIO * TARGET_W
            // di output canvas. Maka scale factor:
            const faceTargetW = TARGET_W * HEAD_SIZE_RATIO;         // px di output
            const scale       = faceTargetW / box.width;             // faktor zoom

            // Ukuran crop di foto asli
            const cropW = TARGET_W / scale;
            const cropH = TARGET_H / scale;

            // Posisi horizontal: pusatkan wajah
            const faceCenterX = box.x + box.width / 2;
            let cropX = faceCenterX - cropW / 2;

            // Posisi vertikal: kepala dimulai HEAD_TOP_RATIO dari atas output
            // Jadi di foto asli: bagian atas kepala ada di (cropH * HEAD_TOP_RATIO) dari atas crop
            let cropY = box.y - (cropH * HEAD_TOP_RATIO);

            // Clamp agar tidak keluar batas foto
            cropX = Math.max(0, Math.min(cropX, iW - cropW));
            cropY = Math.max(0, Math.min(cropY, iH - cropH));

            // Jika cropW atau cropH melebihi foto asli, sesuaikan
            const actualCropW = Math.min(cropW, iW);
            const actualCropH = Math.min(cropH, iH);

            // ── Gambar ke canvas output ────────────────────────────────────
            const canvas  = document.createElement('canvas');
            canvas.width  = TARGET_W;
            canvas.height = TARGET_H;
            const ctx = canvas.getContext('2d');

            // Background transparan (agar remove.bg nanti dapat area bersih)
            ctx.clearRect(0, 0, TARGET_W, TARGET_H);

            // Gambar potongan foto ke canvas dengan resampling
            ctx.drawImage(
                img,
                cropX, cropY, actualCropW, actualCropH,  // source rect
                0, 0, TARGET_W, TARGET_H                  // dest rect
            );

            return canvas.toDataURL('image/jpeg', 0.92);
        } catch (err) {
            console.error('[FaceAPI] Error:', err);
            return null;
        }
    };

    // Pre-load model di background saat halaman siap
    // (agar saat pengguna upload, model sudah tersedia)
    if (document.readyState === 'complete') {
        loadModels();
    } else {
        window.addEventListener('load', loadModels);
    }
})();
</script>
@endpush

@endsection
