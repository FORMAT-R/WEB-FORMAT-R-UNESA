@extends('admin.layouts.app')

@section('page-title', 'Edit Berita')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,900;1,600&family=Source+Serif+4:ital,wght@0,400;0,600;1,400&family=Inter:wght@400;500;600;700&display=swap');

  .newspaper-editor {
    --paper:#faf7ef;
    --paper-dim:#f2ede0;
    --ink:#1c1a16;
    --ink-soft:#4a463c;
    --rule:#c9c1ac;
    --rule-dark:#1c1a16;
    --red:#a3272f;
    --blue:#5c4632;
    --serif-display:'Playfair Display', 'Times New Roman', serif;
    --serif-body:'Source Serif 4', 'Georgia', serif;
    --sans:'Inter', 'Helvetica Neue', sans-serif;
    
    background:var(--paper-dim); color:var(--ink); font-family:var(--serif-body); line-height:1.5;
    padding: 2rem;
    min-height: 100vh;
  }

  .newspaper-editor .page {
    max-width:1180px; margin:0 auto; background:var(--paper);
    box-shadow:0 0 0 1px var(--rule), 0 10px 30px rgba(0,0,0,0.1);
    padding:22px clamp(16px,3vw,48px) 40px;
  }

  /* Utilities */
  .newspaper-editor .masthead{text-align:center; padding:26px 0 14px;}
  .newspaper-editor .masthead h1{ font-family:var(--serif-display); font-weight:900; font-size:clamp(42px,7vw,74px); letter-spacing:.01em; color:var(--ink); margin:0; line-height:1;}
  .newspaper-editor .masthead .tagline{ font-family:var(--sans); font-size:11px; letter-spacing:.18em; text-transform:uppercase; color:var(--ink-soft); margin-top:6px;}
  .newspaper-editor .issue-bar{ display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-top:1px solid var(--rule-dark); border-bottom:3px solid var(--rule-dark); font-family:var(--sans); font-size:11px; letter-spacing:.04em; color:var(--ink-soft);}

  /* Grid */
  .newspaper-editor .main-grid{display:grid; grid-template-columns:235px 1fr 265px; gap:26px; padding:26px 0;}
  
  /* Editable elements */
  [contenteditable="true"] { outline: none; transition: background 0.2s, box-shadow 0.2s; border-radius: 4px; }
  [contenteditable="true"]:hover { background: rgba(0,0,0,0.03); box-shadow: 0 0 0 4px rgba(0,0,0,0.03); cursor: text; }
  [contenteditable="true"]:focus { background: rgba(255, 255, 255, 0.7); box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.7), 0 0 0 1px var(--rule); }
  [contenteditable="true"]:empty:before { content: attr(data-placeholder); color: var(--ink-soft); font-style: italic; opacity: 0.6; pointer-events: none; display: block; }

  /* Hero Article */
  .newspaper-editor .hero h2{
    font-family:var(--serif-display); font-weight:900; font-size:clamp(26px,3vw,34px); line-height:1.12; margin-bottom:14px;
    overflow-wrap:break-word; word-break:break-word; text-align:center;
  }
  .newspaper-editor .hero figure{margin-bottom:10px; position: relative;}
  .newspaper-editor .hero .hero-image{ width:100%; aspect-ratio:16/9; overflow:hidden; border-radius:4px; box-shadow:0 4px 12px rgba(0,0,0,0.15); background:#e5e5e5; display:flex; align-items:center; justify-content:center; cursor:pointer; position:relative; transition: filter 0.2s; }
  .newspaper-editor .hero .hero-image:hover { filter: brightness(0.9); }
  .newspaper-editor .hero .hero-image img{ width:100%; height:100%; object-fit:cover; object-position:center; display:block; }
  .newspaper-editor .hero figure figcaption{font-family:var(--sans); font-size:10px; color:var(--ink-soft); margin-top:6px; font-style:italic; display:block;}
  .newspaper-editor .hero .byline{ font-family:var(--sans); font-size:10.5px; letter-spacing:.05em; text-transform:uppercase; color:var(--ink-soft); margin-bottom:12px; padding-bottom:10px; border-bottom:1px solid var(--rule); display:flex; gap:4px; align-items:center;}
  .newspaper-editor .hero .byline b{color:var(--ink); font-weight:bold;}
  
  .newspaper-editor .body-columns{ columns:2; column-gap:22px; font-size:13px; line-height:1.62; color:var(--ink); min-height:200px; padding-bottom: 20px; word-break: break-word; overflow-wrap: break-word; }
  .newspaper-editor .body-columns p{ margin-bottom:10px; }
  .newspaper-editor .body-columns p:first-of-type::first-letter{ font-family:var(--serif-display); font-size:46px; font-weight:900; float:left; line-height:.82; padding:4px 6px 0 0; color:var(--red); }

  /* Sidebars (Dummy) */
  .newspaper-editor .rail-title{ font-family:var(--sans); font-size:11px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--red); border-bottom:2px solid var(--rule-dark); padding-bottom:6px; margin-bottom:14px; }
  .newspaper-editor .aside-feature h4{font-family:var(--serif-display); font-size:19px; font-weight:700; line-height:1.22; margin-bottom:6px; margin-top:10px;}
  .newspaper-editor .aside-feature p{font-size:11.5px; color:var(--ink-soft); line-height:1.55; margin-bottom:7px;}
  
  .newspaper-editor .latest-box h4{ font-family:var(--sans); font-size:12px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; margin-bottom:10px; padding-bottom:8px; border-bottom:2px solid var(--rule-dark); color:var(--ink); }
  .newspaper-editor .latest-box ul{list-style:none; padding:0; margin:0;}
  .newspaper-editor .latest-box li{display:flex; flex-direction:column; gap:6px; padding:12px 0; border-bottom:1px dotted var(--rule);}
  .newspaper-editor .latest-box li a{font-family:var(--sans); font-size:13px; font-weight:600; color:var(--ink); line-height:1.3;}

  @media (max-width:960px){
    .newspaper-editor .main-grid{grid-template-columns:1fr;}
    .newspaper-editor .body-columns{columns:1;}
  }

  /* Floating Toolbar */
  .floating-toolbar {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: white;
    padding: 12px 24px;
    border-radius: 50px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15), 0 0 0 1px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    gap: 20px;
    z-index: 100;
  }
</style>

<div x-data="inlineEditor()" class="-m-6">
  
  
  @if ($errors->any())
      <div class="fixed top-4 right-4 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-lg z-[100] max-w-md">
          <p class="font-bold mb-1">Gagal Menyimpan:</p>
          <ul class="list-disc pl-5 text-sm">
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

  
  <form id="newsForm" action="{{ route('admin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data" class="hidden">
      @csrf
      @method('PUT')
      <input type="hidden" name="title" :value="form.judul">
      <input type="hidden" name="content" :value="form.konten">
      <input type="hidden" name="author" :value="form.penulis">
      <input type="hidden" name="tag" :value="form.tag">
      <input type="hidden" name="caption" :value="form.caption">
      <input type="hidden" name="status" :value="form.status">
      <input type="file" name="image" id="imageInput" accept="image/*" @change="handleImageUpload">
  </form>

  
  <div class="newspaper-editor">
    <div class="page">
      
      <div class="masthead">
        <h1>FORMAT NEWS</h1>
        <p class="tagline">Sumber Berita dan Informasi Mahasiswa UNESA</p>
      </div>

      <div class="issue-bar">
        <span>Forum Mahasiswa Tuban Ronggolawe</span>
        <span class="text-red-600 font-bold tracking-wider">MODE EDITING</span>
        <span>Kabinet Kolaborasi Asa</span>
      </div>

      <div class="main-grid">
        
        <aside class="hidden lg:block opacity-50 select-none pointer-events-none">
          <div class="aside-feature">
            <div class="rail-title">Baca Juga</div>
            <figure class="bg-gray-200 h-32 w-full rounded flex items-center justify-center mb-2"><span class="text-xs text-gray-500">Gambar</span></figure>
            <h4>Contoh Berita Sebelumnya</h4>
            <p>Ini adalah contoh area sidebar untuk memberikan konteks visual bagaimana koran terlihat saat diterbitkan.</p>
          </div>
        </aside>

        
        <article class="hero">
          
          <h2 contenteditable="true" 
              data-placeholder="Ketikkan Judul Berita Di Sini..."
              x-init="$el.innerText = form.judul"
              @input="form.judul = $el.innerText"
              @paste="handlePaste($event)"
              @keydown.enter.prevent="$event.target.blur()"></h2>

          
          <figure>
            <div class="hero-image" @click="document.getElementById('imageInput').click()">
              <template x-if="form.fotoPreview">
                  <img :src="form.fotoPreview" alt="Preview">
              </template>
              <template x-if="!form.fotoPreview">
                  <div class="text-center p-6 flex flex-col items-center justify-center text-gray-400">
                      <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                      <span class="font-sans text-sm tracking-wide">Klik untuk Upload Foto Utama</span>
                  </div>
              </template>
            </div>
            
            <figcaption contenteditable="true"
                        data-placeholder="Tulis keterangan foto (caption) di sini..."
                        x-init="$el.innerText = form.caption"
                        @input="form.caption = $el.innerText"
                        @paste="handlePaste($event)"
                        @keydown.enter.prevent="$event.target.blur()"></figcaption>
          </figure>

          
          <div class="byline">
            <span class="text-gray-400">Oleh</span>
            <b contenteditable="true" 
               data-placeholder="Nama Penulis" 
               x-init="$el.innerText = form.penulis"
               @input="form.penulis = $el.innerText"
               @paste="handlePaste($event)"
               @keydown.enter.prevent="$event.target.blur()"
               class="min-w-[100px] border-b border-dashed border-gray-300"></b> 
            &middot; Koresponden FORMAT-R
          </div>

          
          <div class="body-columns" 
               contenteditable="true"
               data-placeholder="Mulai ketikkan isi berita di sini. Paragraf pertama akan otomatis menggunakan efek Drop Cap khas koran. Tekan Enter dua kali untuk paragraf baru."
               x-init="$el.innerHTML = form.konten"
               @input="form.konten = $el.innerHTML"
               @paste="handleContentPaste($event)">
          </div>
        </article>

        
        <aside class="hidden lg:block opacity-50 select-none pointer-events-none">
          <div class="latest-box">
            <h4>Berita Lainnya</h4>
            <ul>
              <li><a href="#">Judul Berita Lainnya Pertama</a><span class="text-[10px] text-[var(--red)] font-bold tracking-wider">FORMAT NEWS</span></li>
              <li><a href="#">Judul Berita Lainnya Kedua</a><span class="text-[10px] text-[var(--red)] font-bold tracking-wider">FORMAT NEWS</span></li>
              <li><a href="#">Judul Berita Lainnya Ketiga</a><span class="text-[10px] text-[var(--red)] font-bold tracking-wider">FORMAT NEWS</span></li>
            </ul>
          </div>
        </aside>
      </div>

    </div>
  </div>

  
  <div class="floating-toolbar">
      <a href="{{ route('admin.berita.index') }}" class="text-gray-500 hover:text-gray-800 p-2 rounded-full hover:bg-gray-100 transition" title="Batal & Kembali">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7-7-7M14 18a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
      </a>
      
      <div class="h-8 w-px bg-gray-200 mx-2"></div>

      <div class="flex flex-col">
          <label class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Kategori/Tag</label>
          <input type="text" x-model="form.tag" placeholder="Kabar FORMAT" class="text-sm border-0 bg-gray-50 rounded px-3 py-1.5 focus:ring-2 focus:ring-blue-100 w-40 outline-none">
      </div>

      <div class="h-8 w-px bg-gray-200 mx-2"></div>

      <div class="flex items-center gap-3">
          <button type="button" @click="submitForm('draft')" class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-all">
              Simpan Draft
          </button>
          
          <button type="button" @click="submitForm('published')" :disabled="isSubmitting" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md transition-all flex items-center gap-2">
              <span x-show="!isSubmitting">Publikasikan</span>
              <span x-show="isSubmitting">
                  <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
              </span>
          </button>
      </div>
  </div>

</div>
@endsection

@push('scripts')
<script>



function inlineEditor() {
    return {
        form: {
            judul: {!! json_encode(old('title', $berita->title)) !!},
            penulis: {!! json_encode(old('author_name', $berita->author_name ?? 'Redaksi FORMAT-R')) !!},
            tag: 'Kabar FORMAT',
            caption: '',
            fotoPreview: {!! json_encode($berita->image ? Storage::url($berita->image) : '') !!},
            konten: {!! json_encode(old('content', $berita->content)) !!} || '<p><br></p>',
            status: {!! json_encode(old('status', $berita->status)) !!}
        },
        isSubmitting: false,

        init() {
            document.execCommand('defaultParagraphSeparator', false, 'p');
        },

        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.form.fotoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        handlePaste(e) {
            e.preventDefault();
            const text = e.clipboardData.getData('text/plain');
            document.execCommand('insertText', false, text);
        },

        handleContentPaste(e) {
            e.preventDefault();
            // Get plain text to avoid weird inline styles and HTML structures
            let text = e.clipboardData.getData('text/plain');
            
            // Convert double newlines to paragraphs
            let paragraphs = text.split(/\n\s*\n/);
            let html = paragraphs.map(p => `<p>${p.replace(/\n/g, '<br>')}</p>`).join('');
            
            document.execCommand('insertHTML', false, html);
        },

        submitForm(status) {
            this.form.status = status;
            
            // Basic validation
            if (!this.form.judul || this.form.judul.trim() === '') {
                alert('Judul berita tidak boleh kosong!');
                return;
            }
            
            if (!this.form.konten || this.form.konten.trim() === '') {
                alert('Konten berita tidak boleh kosong!');
                return;
            }

            this.isSubmitting = true;
            document.getElementById('newsForm').submit();
        }
    }
}
</script>
@endpush
