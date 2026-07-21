@extends('admin.layouts.app')

@section('page-title', 'Tambah Event Baru')

@push('styles')
<style>
    .editor-toolbar {
        border: 1px solid #E2E8F0;
        border-bottom: none;
        border-radius: 8px 8px 0 0;
        padding: 0.5rem;
        background: #F8FAFC;
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    .dark .editor-toolbar {
        background: #1E293B;
        border-color: #334155;
    }
    .editor-content {
        border: 1px solid #E2E8F0;
        border-radius: 0 0 8px 8px;
        min-height: 300px;
        padding: 1rem;
    }
    .dark .editor-content {
        background: #1E293B;
        border-color: #334155;
    }
    .editor-content .ProseMirror {
        outline: none;
        min-height: 280px;
    }
    .editor-content .ProseMirror p.is-editor-empty:first-child::before {
        color: #94A3B8;
        content: attr(data-placeholder);
        float: left;
        height: 0;
        pointer-events: none;
    }
    .editor-content .ProseMirror h1 { font-size: 2rem; font-weight: 700; margin: 1rem 0; }
    .editor-content .editor-content h2 { font-size: 1.5rem; font-weight: 600; margin: 1rem 0 0.5rem; }
    .editor-content .ProseMirror h3 { font-size: 1.25rem; font-weight: 600; margin: 1rem 0 0.5rem; }
    .editor-content .ProseMirror p { margin: 0.5rem 0; line-height: 1.7; }
    .editor-content .ProseMirror ul { list-style: disc; padding-left: 1.5rem; margin: 0.5rem 0; }
    .editor-content .ProseMirror ol { list-style: decimal; padding-left: 1.5rem; margin: 0.5rem 0; }
    .editor-content .ProseMirror blockquote { border-left: 4px solid #3B82F6; padding-left: 1rem; margin: 1rem 0; color: #64748B; font-style: italic; }
    .editor-content .ProseMirror code { background: #F1F5F9; padding: 0.125rem 0.375rem; border-radius: 4px; font-size: 0.875em; }
    .editor-content .ProseMirror pre { background: #1E293B; color: #E2E8F0; padding: 1rem; border-radius: 8px; overflow-x: auto; margin: 1rem 0; }
    .editor-content .ProseMirror pre code { background: none; padding: 0; font-size: 0.875rem; }
    .dark .editor-content .ProseMirror code { background: #334155; }
    
    .image-preview {
        position: relative;
        width: 100%;
        max-width: 300px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #E2E8F0;
    }
    .dark .image-preview {
        border-color: #334155;
    }
    .image-preview img {
        width: 100%;
        height: auto;
        display: block;
    }
    .image-preview .remove-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(220, 38, 38, 0.9);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .image-preview:hover .remove-btn {
        opacity: 1;
    }
    
    .gallery-item {
        position: relative;
        aspect-ratio: 4/3;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #E2E8F0;
    }
    .dark .gallery-item {
        border-color: #334155.
    }
    .gallery-item img {
        width: 100%.
        height: 100%.
        object-fit: cover.
    }
    .gallery-item .remove-btn {
        position: absolute.
        top: 8px.
        right: 8px.
        width: 28px.
        height: 28px.
        border-radius: 50%.
        background: rgba(220, 38, 38, 0.9).
        color: white.
        display: flex.
        align-items: center.
        justify-content: center.
        cursor: pointer.
        opacity: 0.
        transition: opacity 0.2s.
    }
    .gallery-item:hover .remove-btn {
        opacity: 1.
    }
    
    .panitia-item {
        background: white.
        border: 1px solid #E2E8F0.
        border-radius: 12px.
        padding: 1.5rem.
        transition: all 0.2s.
    }
    .dark .panitia-item {
        background: #1E293B.
        border-color: #334155.
    }
    .panitia-item:hover {
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1).
        border-color: #3B82F6.
    }
</style>
@endsection

@section('content')
<div class="space-y-8">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Event Baru</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Buat event/kegiatan baru untuk FORMAT-R UNESA</p>
        </div>
        <a href="{{ route('admin.events.index') }}" class="btn-secondary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7-7-7M14 18a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <form @submit.prevent="handleSubmit" class="space-y-8" enctype="multipart/form-data">
        {{-- Basic Info Section --}}
        <section class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Informasi Dasar
            </h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="judul" class="form-label">Judul Event <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        id="judul" 
                        name="judul" 
                        class="form-input" 
                        placeholder="Contoh: Diklat Kepemimpinan FORMAT-R 2026"
                        x-model="form.judul"
                        required
                    >
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Maksimal 120 karakter</div>
                    <div x-show="errors.judul" class="text-red-500 text-sm mt-1" x-text="errors.judul"></div>
                </div>
                
                <div class="form-group">
                    <label for="slug" class="form-label">Slug (URL)</label>
                    <div class="input-wrapper">
                        <span class="input-prefix">/event/</span>
                        <input 
                            type="text" 
                            id="slug" 
                            name="slug" 
                            class="form-input pl-10" 
                            x-model="form.slug"
                            placeholder="auto-generated-from-judul"
                        >
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Akan di-generate otomatis dari judul jika dikosongkan</div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <div class="form-group">
                    <label for="status" class="form-label">Status Event <span class="text-red-500">*</span></label>
                    <select id="status" name="status" class="form-select" x-model="form.status" required>
                        <option value="coming_soon">Akan Datang</option>
                        <option value="started">Sedang Berlangsung</option>
                        <option value="finished">Selesai</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="penyelenggara" class="form-label">Penyelenggara <span class="text-red-500">*</span></label>
                    <select id="penyelenggara" name="penyelenggara" class="form-select" x-model="form.penyelenggara" required>
                        <option value="">Pilih Departemen</option>
                        @foreach($departemenList as $dept)
                        <option value="{{ $dept['slug'] }}">{{ $dept['nama'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                <div class="form-group">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input 
                        type="date" 
                        id="tanggal_mulai" 
                        name="tanggal_mulai" 
                        class="form-input" 
                        x-model="form.tanggal_mulai"
                        required
                    >
                </div>
                <div class="form-group">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input 
                        type="date" 
                        id="tanggal_selesai" 
                        name="tanggal_selesai" 
                        class="form-input" 
                        x-model="form.tanggal_selesai"
                    >
                </div>
                <div class="form-group">
                    <label for="lokasi" class="form-label">Lokasi <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        id="lokasi" 
                        name="lokasi" 
                        class="form-input" 
                        placeholder="Contoh: Gedung Serbaguna UNESA, Kampus Ketintang"
                        x-model="form.lokasi"
                        required
                    >
                </div>
            </div>
            
            <div class="form-group mt-6">
                <label for="penyelenggara_detail" class="form-label">Detail Penyelenggara</label>
                <input 
                    type="text" 
                    id="penyelenggara_detail" 
                    name="penyelenggara_detail" 
                    class="form-input" 
                    placeholder="Contoh: Departemen POSDM, KOMINFO, PENLAR"
                    x-model="form.penyelenggara_detail"
                >
            </div>
            
            <div class="form-group mt-6">
                <label for="peserta" class="form-label">Jumlah Peserta (Estimasi)</label>
                <input 
                    type="number" 
                    id="peserta" 
                    name="peserta" 
                    class="form-input" 
                    placeholder="Contoh: 100"
                    x-model="form.peserta"
                    min="0"
                >
            </div>
        </section>

        {{-- Deskripsi Section --}}
        <section class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 20h16M6 4h12v12H4z" />
                </svg>
                Deskripsi Event
            </h2>
            
            <div class="form-group">
                <label for="deskripsi" class="form-label">Deskripsi Singkat</label>
                <textarea 
                    id="deskripsi" 
                    name="deskripsi" 
                    class="form-textarea" 
                    rows="3" 
                    placeholder="Deskripsi singkat untuk preview card (maks 200 karakter)"
                    x-model="form.deskripsi"
                    maxlength="300"
                ></textarea>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Maksimal 300 karakter</div>
            </div>
            
            <div class="form-group mt-6">
                <label for="deskripsi_panjang" class="form-label">Deskripsi Lengkap</label>
                <div id="editor-deskripsi" class="editor-content" x-data="tiptapEditor('deskripsi_panjang')">
                    <div class="editor-toolbar" role="toolbar"></div>
                    <div class="editor-content" contenteditable="true" x-ref="editor"></div>
                </div>
                <input type="hidden" name="deskripsi_panjang" x-ref="deskripsi_panjang_input">
            </div>
        </section>

        {{-- Media Section --}}
        <section class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 14l-2.828-2.828a2 2 0 012.828 0L12 14.172V21h2v-6h4v6h-2v-6h4v-4H8v4l-4 4H4v-4z" />
                </svg>
                Media & Dokumentasi
            </h2>
            
            <div class="space-y-6">
                {{-- Cover Image --}}
                <div class="form-group">
                    <label class="form-label">Cover Event (Banner)</label>
                    <div class="image-preview" x-data="{ image: null }" x-ref="coverPreview">
                        <template x-if="image">
                            <img :src="image" alt="Cover Event">
                            <button type="button" class="remove-btn" @click="image = null">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </template>
                        <div x-show="!image" class="flex flex-col items-center justify-center h-64 bg-gray-50 dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl cursor-pointer" @click="$refs.coverInput.click()">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 12l-2.83-2.83a2 2 0 012.828 0L21 12l-4 4-4-4-4 4z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 15a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v1.5"/></svg>
                            <p class="mt-2 text-gray-500 dark:text-gray-400">Klik atau drag & drop untuk upload cover</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Rekomendasi: 1200x600px, max 10MB</p>
                        </div>
                        <input type="file" x-ref="coverInput" accept="image/*" class="hidden" @change="handleCoverUpload($event)">
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Rekomendasi: 1200x600px, max 10MB, format JPG/PNG</div>
                </div>
                
                {{-- Gallery --}}
                <div class="form-group">
                    <div class="flex items-center justify-between mb-4">
                        <label class="form-label">Galeri Dokumentasi</label>
                        <label class="cursor-pointer">
                            <input type="file" multiple accept="image/*" class="hidden" x-ref="galleryInput" @change="handleGalleryUpload($event)">
                            <button type="button" class="btn-secondary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 14l-2.83-2.83a2 2 0 012.828 0L16 11.172V21h-2v-6h-4v6h-4v-6h-4v-4z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 15a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v3.5"/></svg>
                                Tambah Foto
                            </button>
                        </label>
                    </div>
                    
                    <div class="gallery-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" x-ref="galleryContainer">
                        <template x-for="(image, index) in galleryImages" :key="index">
                            <div class="gallery-item">
                                <img :src="image.url" :alt="image.caption">
                                <button type="button" class="remove-btn" @click="galleryImages.splice(index, 1)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                                <input type="hidden" name="gallery[]" :value="image.url">
                                <input type="hidden" name="gallery_caption[]" :value="image.caption">
                                <input type="text" name="gallery_caption[]" :value="image.caption" class="mt-2 w-full text-xs px-2 py-1 border border-gray-200 dark:border-gray-600 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400" placeholder="Caption (opsional)">
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Panitia Section --}}
    @if($event['status'] != 'coming_soon')
    <section class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h2v-2a2 2 0 00-2-2H7a2 2 0 00-2-2V7a2 2 0 012-2h2l2-2h4l2 2h2a2 2 0 012 2v2h2a2 2 0 01-2 2h-2v2a2 2 0 01-2 2h-2v2a2 2 0 01-2 2h-2v2a2 2 0 01-2 2H3a2 2 0 01-2-2V9a2 2 0 00-2-2h2.586a1 1 0 01.707-.293l3.5-3.5a1 1 0 011.414 0l2.5 2.5a1 1 0 010 1.414l2 2a1 1 0 010 1.414z" />
            </svg>
            Susunan Panitia
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($event['panitia'] as $i => $p)
            <div class="panitia-item">
                <div class="text-center mb-4">
                    <div class="w-20 h-20 mx-auto mb-3 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-content-center text-white font-bold text-xl">
                        {{ $p['inisial'] }}
                    </div>
                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $p['nama'] }}</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $p['jabatan'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
@endif
