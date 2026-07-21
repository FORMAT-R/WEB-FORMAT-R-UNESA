@extends('admin.layouts.app')

@section('page-title', 'Tulis Berita')

@section('content')
@push('styles')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,900;1,600&family=Source+Serif+4:ital,wght@0,400;0,600;1,400&family=Inter:wght@400;500;600;700&display=swap');

  /* Newspaper Variables */
  .preview-wrapper {
    --paper:#faf7ef;
    --paper-dim:#f2ede0;
    --ink:#1c1a16;
    --ink-soft:#4a463c;
    --rule:#c9c1ac;
    --rule-dark:#1c1a16;
    --red:#a3272f;
    --blue:#5c4632;
    --gold:#b8935a;
    --serif-display:'Playfair Display', 'Times New Roman', serif;
    --serif-body:'Source Serif 4', 'Georgia', serif;
    --sans:'Inter', 'Helvetica Neue', sans-serif;
    
    background:var(--paper-dim); color:var(--ink); font-family:var(--serif-body); line-height:1.5;
    padding:20px;
    border-radius: 12px;
    border: 1px solid var(--rule);
    max-height: 800px;
    overflow-y: auto;
  }

  .preview-page {
    background:var(--paper);
    box-shadow:0 0 0 1px var(--rule);
    padding:22px 30px 0;
  }

  .preview-wrapper * { box-sizing:border-box; margin:0; padding:0; }
  
  .preview-masthead { text-align:center; padding:16px 0; border-bottom:3px double var(--rule-dark); margin-bottom: 16px; }
  .preview-masthead h1 { font-family:var(--serif-display); font-weight:900; font-size:32px; letter-spacing:.01em; color:var(--ink); }
  .preview-masthead p { font-family:var(--sans); font-size:10px; letter-spacing:.18em; text-transform:uppercase; color:var(--ink-soft); margin-top:4px; }
  
  .preview-hero h2 { font-family:var(--serif-display); font-weight:900; font-size:24px; line-height:1.2; margin-bottom:14px; }
  .preview-byline { font-family:var(--sans); font-size:10px; letter-spacing:.05em; text-transform:uppercase; color:var(--ink-soft); margin-bottom:12px; padding-bottom:10px; border-bottom:1px solid var(--rule); }
  .preview-byline b { color:var(--ink); }
  
  .preview-body { font-size:13px; line-height:1.62; color:var(--ink); }
  .preview-body p { margin-bottom: 12px; }
  .preview-body p:first-of-type::first-letter { font-family:var(--serif-display); font-size:38px; font-weight:900; float:left; line-height:.8; padding:4px 6px 0 0; color:var(--red); }
  
  .preview-meta { margin-top: 20px; padding-top: 10px; border-top: 1px solid var(--rule); font-family:var(--sans); font-size: 11px; display: flex; justify-content: space-between; }
  .preview-tag { color: var(--red); font-weight: bold; text-transform: uppercase; letter-spacing: .08em; }
</style>
@endpush

<div class="space-y-6" x-data="newsForm()">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tulis Berita Baru</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Buat artikel berita dan pratinjau dalam layout koran.</p>
        </div>
        <a href="{{ route('admin.berita.index') }}" class="btn-secondary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7-7-7M14 18a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Editor Form --}}
        <div class="lg:col-span-5 space-y-6">
            <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data" @submit="isSubmitting = true" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <input type="hidden" name="status" x-model="form.status">
                
                <div class="form-group mb-5">
                    <label class="form-label">Judul Berita <span class="text-red-500">*</span></label>
                    <input type="text" name="title" x-model="form.judul" required class="form-input" placeholder="Masukkan judul berita yang menarik" value="{{ old('title') }}">
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div class="form-group">
                        <label class="form-label">Penulis</label>
                        <input type="text" x-model="form.penulis" class="form-input" placeholder="Contoh: Rian Hidayat">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori / Tag</label>
                        <input type="text" x-model="form.tag" class="form-input" placeholder="Contoh: Kabar FORMAT">
                    </div>
                </div>



                <div class="form-group mb-5">
                    <label class="form-label">Foto Utama</label>
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-24 h-16 bg-gray-100 dark:bg-gray-700 rounded overflow-hidden border border-gray-300 dark:border-gray-600 flex items-center justify-center">
                            <template x-if="form.fotoPreview">
                                <img :src="form.fotoPreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!form.fotoPreview">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </template>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image" @change="handleFileUpload" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-5">
                    <label class="form-label">Keterangan Foto (Caption)</label>
                    <input type="text" x-model="form.caption" class="form-input" placeholder="Contoh: Suasana kegiatan pelatihan desain grafis...">
                </div>
                
                <div class="form-group mb-6">
                    <label class="form-label">Konten Artikel <span class="text-red-500">*</span></label>
                    <textarea name="content" x-model="form.konten" required class="form-textarea" rows="12" placeholder="Tuliskan isi berita di sini. Paragraf pertama akan otomatis mendapatkan efek Drop Cap khas koran...">{{ old('content') }}</textarea>
                    <p class="text-xs text-gray-500 mt-2">Gunakan spasi (enter) ganda untuk membuat paragraf baru.</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button type="submit" class="btn-secondary" @click="form.status = 'draft'">Simpan Draft</button>
                    <button type="submit" class="btn-primary" @click="form.status = 'published'">
                        <span x-show="!isSubmitting">Publikasikan Berita</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Live Preview --}}
        <div class="lg:col-span-7">
            <div class="sticky top-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Live Preview (Format Koran)</h3>
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded dark:bg-blue-900/30 dark:text-blue-400">Pembaruan Waktu Nyata</span>
                </div>
                
                <div class="preview-wrapper">
                    <div class="preview-page">
                        <div class="preview-masthead">
                            <h1>FORMAT NEWS</h1>
                            <p>Sumber Berita dan Informasi Mahasiswa UNESA</p>
                        </div>
                        
                        <article class="preview-hero">
                            <h2 x-text="form.judul || 'Judul Berita Akan Tampil Di Sini'"></h2>
                            
                            <template x-if="form.fotoPreview">
                                <figure style="margin-bottom: 14px;">
                                    <img :src="form.fotoPreview" alt="Foto Berita" style="width:100%; display:block; filter:grayscale(1) contrast(1.1) sepia(0.1);">
                                    <figcaption style="font-family:var(--sans); font-size:10px; color:var(--ink-soft); margin-top:6px; font-style:italic;" x-text="form.caption || 'Keterangan foto...'"></figcaption>
                                </figure>
                            </template>
                            
                            <div class="preview-byline">
                                Oleh <b x-text="form.penulis || 'Nama Penulis'"></b> &middot; Jurnalis
                            </div>
                            
                            <div class="preview-body" x-html="formatKonten(form.konten)">
                            </div>
                            
                            <div class="preview-meta pb-6">
                                <span class="preview-tag" x-text="form.tag || 'KATEGORI'"></span>
                                <span>Waktu Baca: <span x-text="form.menit || '0'"></span> Menit</span>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function newsForm() {
    return {
        form: {
            judul: '',
            penulis: 'Redaksi',
            tag: 'Kabar FORMAT',
            menit: 3,
            fotoPreview: '',
            caption: '',
            konten: '',
            status: 'published'
        },
        isSubmitting: false,
        
        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.form.fotoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        
        formatKonten(text) {
            if (!text) return '<p>Mulai ketikkan isi berita di kolom sebelah kiri untuk melihat hasil pratinjaunya di sini.</p>';
            // Simple newline to <p> conversion
            return text.split('\n\n').map(p => `<p>${p.replace(/\n/g, '<br>')}</p>`).join('');
        }
    }
}
</script>
@endpush
