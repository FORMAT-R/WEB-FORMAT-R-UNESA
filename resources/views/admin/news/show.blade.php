<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $berita->title ?? 'FORMAT NEWS' }} | FORMAT NEWS</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,900;1,600&family=Source+Serif+4:ital,wght@0,400;0,600;1,400&family=Inter:wght@400;500;600;700&display=swap');

  :root{
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
  }

  *{box-sizing:border-box; margin:0; padding:0;}
  body{background:var(--paper-dim); color:var(--ink); font-family:var(--serif-body); line-height:1.5;}
  a{color:inherit; text-decoration:none;}
  a:focus-visible, button:focus-visible{outline:2px solid var(--red); outline-offset:2px;}

  .page{
    max-width:1180px; margin:0 auto; background:var(--paper);
    box-shadow:0 0 0 1px var(--rule);
    padding:22px clamp(16px,3vw,48px) 0;
  }

  /* ===== UTILITY NAV ===== */
  .util-nav{
    display:flex; justify-content:space-between; align-items:center;
    padding-bottom:16px; border-bottom:3px double var(--rule-dark); font-family:var(--sans);
  }
  .util-item .eyebrow{
    font-size:10px; letter-spacing:.12em; font-weight:700; text-transform:uppercase;
    color:var(--red); display:block; margin-bottom:3px;
  }
  .util-item p{font-size:11.5px; color:var(--ink-soft); font-family:var(--serif-body);}
  .weather{display:flex; align-items:center; gap:8px; white-space:nowrap; font-size:11.5px;}
  .weather svg{width:22px; height:22px;}
  .weather .temps{line-height:1.3;}
  .weather .temps b{font-size:13px;}

  /* ===== MASTHEAD ===== */
  .masthead{text-align:center; padding:26px 0 14px;}
  .masthead h1{
    font-family:var(--serif-display); font-weight:900; font-size:clamp(42px,7vw,74px);
    letter-spacing:.01em; color:var(--ink);
  }
  .masthead .tagline{
    font-family:var(--sans); font-size:11px; letter-spacing:.18em; text-transform:uppercase;
    color:var(--ink-soft); margin-top:6px;
  }

  .issue-bar{
    display:flex; justify-content:space-between; align-items:center; padding:10px 0;
    border-top:1px solid var(--rule-dark); border-bottom:3px solid var(--rule-dark);
    font-family:var(--sans); font-size:11px; letter-spacing:.04em; color:var(--ink-soft);
  }
  .issue-bar .center{font-weight:700; letter-spacing:.08em; color:var(--ink);}

  /* ===== MAIN GRID ===== */
  .main-grid{display:grid; grid-template-columns:235px 1fr 265px; gap:26px; padding:26px 0;}

  .rail-title{
    font-family:var(--sans); font-size:11px; font-weight:700; letter-spacing:.1em; text-transform:uppercase;
    color:var(--red); border-bottom:2px solid var(--rule-dark); padding-bottom:6px; margin-bottom:14px;
  }

  .story-list{list-style:none;}
  .story-list li{padding:12px 0; border-bottom:1px solid var(--rule);}
  .story-list li:first-child{padding-top:0;}
  .story-list h4{font-family:var(--serif-display); font-size:15.5px; font-weight:700; line-height:1.28; margin-bottom:5px;}
  .story-list p{font-size:11.5px; color:var(--ink-soft); line-height:1.48; text-align:justify; text-justify:inter-word;}
  .story-list .jump{font-family:var(--sans); font-size:10px; font-weight:700; color:var(--red); text-transform:uppercase; letter-spacing:.04em;}

  /* left column solar feature */
  .aside-feature{margin-top:24px;}
  .aside-feature figure{margin-bottom:8px;}
  .aside-feature figure img{width:100%; aspect-ratio:16/9; object-fit:cover; display:block; border-radius:4px;}
  .aside-feature h4{font-family:var(--serif-display); font-size:19px; font-weight:700; line-height:1.22; margin-bottom:6px;}
  .aside-feature p{font-size:11.5px; color:var(--ink-soft); line-height:1.55; margin-bottom:7px; text-align:justify; text-justify:inter-word;}
  .aside-feature .byline{font-family:var(--sans); font-size:10px; color:var(--ink-soft); text-transform:uppercase; letter-spacing:.04em; margin-bottom:8px;}

  /* center hero */
  .hero h2{
    font-family:var(--serif-display); font-weight:900; font-size:clamp(26px,3vw,34px); line-height:1.12; margin-bottom:14px;
    overflow-wrap:break-word; word-break:break-word; text-align:center;
  }
  .hero figure{margin-bottom:10px;}
  .hero .hero-image{
    width:100%;
    aspect-ratio:16/9;
    overflow:hidden;
    border-radius:4px;
    box-shadow:0 4px 12px rgba(0,0,0,0.15);
    background:var(--paper-dim);
  }
  .hero .hero-image img{
    width:100%;
    height:100%;
    object-fit:cover;
    object-position:center;
    display:block;
  }
  .hero figure figcaption{font-family:var(--sans); font-size:10px; color:var(--ink-soft); margin-top:6px; font-style:italic;}
  .hero .byline{
    font-family:var(--sans); font-size:10.5px; letter-spacing:.05em; text-transform:uppercase; color:var(--ink-soft);
    margin-bottom:12px; padding-bottom:10px; border-bottom:1px solid var(--rule);
  }
  .hero .byline b{color:var(--ink);}
  .body-columns{
    columns:2; column-gap:22px; font-size:13px; line-height:1.62; color:var(--ink);
    overflow-wrap:break-word; word-break:break-word; word-wrap:break-word;
    -webkit-hyphens:auto; hyphens:auto;
    text-align:justify; text-justify:inter-word;
  }
  .body-columns p{
    margin-bottom:10px;
    overflow-wrap:break-word; word-break:break-word; word-wrap:break-word;
    -webkit-hyphens:auto; hyphens:auto;
    text-align:justify; text-justify:inter-word;
  }
  .body-columns p:first-of-type::first-letter{
    font-family:var(--serif-display); font-size:46px; font-weight:900; float:left; line-height:.82;
    padding:4px 6px 0 0; color:var(--red);
  }
  .body-columns img{
    max-width:100%;
    height:auto;
    display:block;
    margin:8px 0;
    border-radius:4px;
  }

  .second-story{margin-top:28px; padding-top:22px; border-top:3px double var(--rule-dark);}
  .second-story h3{font-family:var(--serif-display); font-weight:800; font-size:24px; line-height:1.16; margin-bottom:10px;}
  .second-story .byline{
    font-family:var(--sans); font-size:10.5px; text-transform:uppercase; letter-spacing:.05em; color:var(--ink-soft);
    margin-bottom:12px; padding-bottom:10px; border-bottom:1px solid var(--rule);
  }
  .second-story .body-columns{font-size:12.5px;}

  /* right rail */
  .side-story{padding-bottom:16px; margin-bottom:16px; border-bottom:1px solid var(--rule);}
  .side-story figure{margin-bottom:8px;}
  .side-story figure img{width:100%; aspect-ratio:16/9; object-fit:cover; display:block; border-radius:4px;}
  .side-story h4{font-family:var(--serif-display); font-size:18px; font-weight:700; line-height:1.24; margin-bottom:5px;}
  .side-story .kicker{
    font-family:var(--sans); font-size:10px; font-weight:700; letter-spacing:.08em; text-transform:uppercase;
    color:var(--blue); display:block; margin-bottom:4px;
  }
  .side-story p{font-size:11.5px; color:var(--ink-soft); line-height:1.5; margin-bottom:6px; text-align:justify; text-justify:inter-word;}
  .side-story .byline{font-family:var(--sans); font-size:10px; color:var(--ink-soft);}
  .side-story .jump{font-family:var(--sans); font-size:10px; font-weight:700; color:var(--red); text-transform:uppercase;}

  .contents-box{border:1px solid var(--rule-dark); padding:16px; margin-bottom:16px;}
  .contents-box h4{
    font-family:var(--sans); font-size:12px; font-weight:700; letter-spacing:.08em; text-transform:uppercase;
    margin-bottom:10px; padding-bottom:8px; border-bottom:2px solid var(--rule-dark);
  }
  .contents-box ul{list-style:none; font-family:var(--sans); font-size:11.5px;}
  .contents-box li{
    display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px dotted var(--rule); color:var(--ink-soft);
  }
  .contents-box li span:last-child{color:var(--ink);}

  .latest-box{margin-bottom:16px;}
  .latest-box h4{
    font-family:var(--sans); font-size:12px; font-weight:700; letter-spacing:.08em; text-transform:uppercase;
    margin-bottom:10px; color:var(--ink);
  }
  .latest-box ul{list-style:none; font-family:var(--sans); font-size:11.5px; color:var(--ink-soft);}
  .latest-box li{display:flex; gap:8px; padding:6px 0; border-bottom:1px dotted var(--rule);}
  .latest-box li::before{content:'●'; color:var(--red); font-size:8px; margin-top:4px;}

  .sub-note{font-family:var(--sans); font-size:10.5px; color:var(--ink-soft); line-height:1.5; margin-bottom:14px;}
  .sub-note b{color:var(--ink);}

  .brand-strip{display:flex; align-items:center; justify-content:space-between; padding-top:12px; border-top:1px solid var(--rule);}
  .brand-strip .logo{font-family:var(--serif-display); font-weight:800; font-size:13px;}
  .brand-strip .logo span{display:block; font-family:var(--sans); font-size:8px; letter-spacing:.1em; font-weight:400; color:var(--ink-soft);}
  .barcode{display:flex; gap:1.5px; align-items:flex-end; height:26px;}
  .barcode i{display:block; width:2px; background:var(--ink);}

  /* ===== WEEKLY TOP STORIES (bottom) ===== */
  .weekly-section{
    padding-top:26px; margin-top:6px; border-top:3px double var(--rule-dark);
  }
  .weekly-grid{display:grid; grid-template-columns:repeat(3,1fr); gap:24px;}
  .weekly-card figure{margin-bottom:8px;}
  .weekly-card figure img{width:100%; aspect-ratio:16/9; object-fit:cover; display:block; border-radius:4px;}
  .weekly-card figcaption{
    font-family:var(--sans); font-size:10px; color:var(--ink-soft); margin-top:5px; font-style:italic;
  }
  .weekly-card h4{
    font-family:var(--serif-display); font-size:17px; font-weight:700; line-height:1.26; margin-bottom:6px;
  }
  .weekly-card p{font-size:12px; color:var(--ink-soft); line-height:1.52; margin-bottom:7px; text-align:justify; text-justify:inter-word;}
  .weekly-card .jump{
    font-family:var(--sans); font-size:10px; font-weight:700; color:var(--red); text-transform:uppercase; letter-spacing:.04em;
  }
  @media (max-width:900px){ .weekly-grid{grid-template-columns:1fr;} }

  /* ===== BOTTOM BANNER ===== */
  .banner{position:relative; height:190px; margin:24px 0 0; overflow:hidden; border-top:3px double var(--rule-dark);}
  .banner svg{width:100%; height:100%; display:block;}
  .banner .banner-caption{position:absolute; left:20px; bottom:16px; color:#fff; font-family:var(--sans);}
  .banner .banner-caption .tag{font-size:10px; letter-spacing:.1em; text-transform:uppercase; opacity:.85;}
  .banner .banner-caption h5{font-family:var(--serif-display); font-size:22px;}
  .banner .banner-logo{
    position:absolute; left:20px; top:16px; display:flex; align-items:center; gap:6px;
    font-family:var(--sans); font-size:11px; color:#fff; font-weight:600;
  }

  footer{font-family:var(--sans); font-size:10.5px; color:var(--ink-soft); text-align:center; padding:16px 0 26px; border-top:1px solid var(--rule);}

  @media (max-width:960px){
    .main-grid{grid-template-columns:1fr;}
    .body-columns{columns:1;}
  }

  /* ===== DOWNLOAD TOOLBAR ===== */
  .download-toolbar {
    position: fixed;
    bottom: 28px;
    right: 28px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    z-index: 999;
  }
  .dl-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border: none;
    border-radius: 8px;
    font-family: var(--sans);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    cursor: pointer;
    box-shadow: 0 4px 16px rgba(0,0,0,0.18);
    transition: transform .15s, box-shadow .15s, opacity .15s;
  }
  .dl-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.22);
  }
  .dl-btn:active { transform: translateY(0); }
  .dl-btn-png { background: var(--ink); color: #fff; }
  .dl-btn-pdf { background: var(--red); color: #fff; }
  .dl-btn svg { flex-shrink: 0; }
  .dl-btn.loading { opacity: .6; pointer-events: none; }
  .dl-btn .spinner {
    width: 14px; height: 14px;
    border: 2px solid rgba(255,255,255,.4);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .7s linear infinite;
    display: none;
  }
  .dl-btn.loading .spinner { display: block; }
  .dl-btn.loading .btn-icon { display: none; }
  @keyframes spin { to { transform: rotate(360deg); } }
</style>
</head>
<body>
<div class="page">

  <!-- UTILITY NAV -->
  <nav class="util-nav">
    <div class="util-item" style="display:flex; align-items:center;">
      <a href="{{ route('admin.berita.index') }}" style="font-weight:700; color:var(--red); text-transform:uppercase; letter-spacing:1px; font-size:12px;">&larr; Kembali ke Admin Berita</a>
    </div>
    <div class="util-item" style="font-family:var(--sans); font-size:11px; font-weight:700; letter-spacing:0.04em; color:var(--ink);">
      {{ strtoupper(date('l, d F Y')) }}
    </div>
  </nav>

  <!-- MASTHEAD -->
  <div class="masthead">
    <h1>FORMAT NEWS</h1>
    <p class="tagline">Pratinjau Artikel Admin (Admin Preview)</p>
  </div>

  <div class="issue-bar" style="display:flex; justify-content:space-between;">
    <span>Forum Mahasiswa Tuban Ronggolawe</span>
    <span>Kolaborasi Asa</span>
  </div>

  <!-- MAIN GRID -->
  <div class="main-grid">
    <!-- LEFT COLUMN -->
    <aside>
      @if(isset($nextBerita) && $nextBerita)
      <div class="aside-feature" style="margin-top:0;">
        <div class="rail-title">Baca Juga</div>
        <figure>
          @if($nextBerita->image)
            <img src="{{ Storage::url($nextBerita->image) }}" alt="{{ $nextBerita->title }}" style="width:100%; object-fit:cover; border:1px solid var(--rule-light); border-radius:4px;">
          @else
            <svg viewBox="0 0 230 140" width="100%" height="auto">
              <rect width="230" height="140" fill="#ece4d1"/>
              <text x="50%" y="50%" font-family="var(--sans)" font-size="12" fill="#8f978f" text-anchor="middle" dy=".3em">Format News</text>
            </svg>
          @endif
        </figure>
        <h4><a href="{{ route('admin.berita.show', $nextBerita->id) }}" style="color:var(--ink); text-decoration:none;">{{ $nextBerita->title }}</a></h4>
        <p>{{ Str::limit(strip_tags($nextBerita->content), 120) }}</p>
        <div class="byline">{{ $nextBerita->author ? $nextBerita->author->name : 'Redaksi' }} &middot; FORMAT NEWS</div>
      </div>
      @endif
    </aside>

    <!-- CENTER HERO -->
    <article class="hero" id="biz">
      <h2>{{ $berita->title }}</h2>
      @if($berita->image)
      <figure>
        <div class="hero-image">
          <img src="{{ Storage::url($berita->image) }}" alt="{{ $berita->title }}" loading="lazy">
        </div>
        <figcaption>{{ $berita->title }}</figcaption>
      </figure>
      @endif
      <div class="byline"><b>{{ $berita->author ? $berita->author->name : 'Redaksi FORMAT-R' }}</b> &middot; Koresponden FORMAT-R</div>
      <div class="body-columns">
        {!! $berita->content !!}
      </div>


    </article>

    <!-- RIGHT COLUMN -->
    <aside>
      <div class="latest-box">
        <h4 style="margin-bottom:12px; border-bottom:2px solid var(--rule-dark); padding-bottom:8px;">Berita Lainnya</h4>
        <ul style="display:flex; flex-direction:column; gap:16px;">
          @foreach($latestBerita as $lb)
          <li style="border:none; padding:0;">
            <div style="display:flex; flex-direction:column; gap:6px;">
              <a href="{{ route('admin.berita.show', $lb->id) }}" style="font-weight:600; color:var(--ink); line-height:1.3; font-size:13px;">{{ $lb->title }}</a>
              <span style="font-size:10px; color:var(--red); font-weight:700; letter-spacing:0.06em; text-transform:uppercase;">FORMAT NEWS</span>
            </div>
          </li>
          @endforeach
        </ul>
      </div>
    </aside>
  </div>


  <!-- BERITA UTAMA PEKAN INI (bawah, dengan gambar) -->
  <section class="weekly-section">
    <div class="rail-title" style="font-size:12px;">Berita Utama Pekan Ini</div>
    <div class="weekly-grid">
      @if(isset($weeklyBerita) && $weeklyBerita->count() > 0)
        @foreach($weeklyBerita as $wb)
        <article class="weekly-card">
          <figure>
            @if($wb->image)
            <img src="{{ Storage::url($wb->image) }}" alt="{{ $wb->title }}" style="width:100%; height:190px; object-fit:cover; border:1px solid var(--rule-light);">
            @else
            <svg viewBox="0 0 300 190" preserveAspectRatio="xMidYMid slice" width="100%" height="auto">
              <rect width="300" height="190" fill="#f2ede0"/>
              <text x="50%" y="50%" font-family="var(--sans)" font-size="12" fill="#8f978f" text-anchor="middle" dy=".3em">Format News</text>
            </svg>
            @endif
          </figure>
          <h4>{{ $wb->title }}</h4>
          <p>{{ Str::limit(strip_tags($wb->content), 120) }}</p>
          <a class="jump" href="{{ route('admin.berita.show', $wb->id) }}">Lanjut Baca &rarr;</a>
        </article>
        @endforeach
      @else
        <div style="grid-column:1/-1; text-align:center; padding:40px; color:var(--ink-soft); font-family:var(--sans); font-size:14px; border-top:1px solid var(--rule-light);">
          Belum ada berita.
        </div>
      @endif
    </div>
  </section>

  <footer>
    &copy; {{ date('Y') }} Format R UNESA. Seluruh hak cipta dilindungi.
  </footer>

</div>

<!-- ===== DOWNLOAD TOOLBAR ===== -->
<div class="download-toolbar" id="downloadToolbar">
  <button class="dl-btn dl-btn-png" id="btnPng" onclick="downloadAsPng()" title="Download sebagai PNG">
    <span class="btn-icon">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="18" height="18" rx="2"/>
        <circle cx="8.5" cy="8.5" r="1.5"/>
        <polyline points="21 15 16 10 5 21"/>
      </svg>
    </span>
    <span class="spinner"></span>
    Download PNG
  </button>
  <button class="dl-btn dl-btn-pdf" id="btnPdf" onclick="downloadAsPdf()" title="Download sebagai PDF">
    <span class="btn-icon">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
        <line x1="16" y1="13" x2="8" y2="13"/>
        <line x1="16" y1="17" x2="8" y2="17"/>
        <polyline points="10 9 9 9 8 9"/>
      </svg>
    </span>
    <span class="spinner"></span>
    Download PDF
  </button>
</div>

<!-- html2canvas + jsPDF CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
  const FILENAME = '{{ Str::slug($berita->title ?? "berita-format") }}';

  function getTarget() {
    return document.querySelector('.page');
  }

  function setLoading(btnId, state) {
    const btn = document.getElementById(btnId);
    if (state) btn.classList.add('loading');
    else btn.classList.remove('loading');
  }

  async function captureCanvas() {
    const toolbar = document.getElementById('downloadToolbar');
    toolbar.style.display = 'none';

    // ── Drop-cap fix ──────────────────────────────────────────────────────────
    // html2canvas tidak mendukung ::first-letter pseudo-element.
    // Kita inject <span> nyata dengan inline styles sebelum capture,
    // lalu restore innerHTML asli setelah selesai.
    const firstPara = document.querySelector('.body-columns p:first-of-type');
    let savedParaHTML = null;
    let suppressStyle = null;

    if (firstPara) {
      savedParaHTML = firstPara.innerHTML;

      // Cari posisi huruf pertama (lewati tag HTML pembuka jika ada)
      const raw = firstPara.innerHTML;
      const m = raw.match(/^(\s*(?:<[^>]+>\s*)*)([^\s<])/);
      if (m) {
        const prefix  = m[1];                   // tag-tag pembuka sebelum teks
        const firstCh = m[2];                   // huruf pertama
        const rest    = raw.slice(m[0].length); // sisa konten
        const dropSpan =
          '<span style="font-family:\'Playfair Display\',\'Times New Roman\',serif;' +
          'font-size:46px;font-weight:900;float:left;line-height:0.82;' +
          'padding:4px 6px 0 0;color:#a3272f;">' + firstCh + '</span>';
        firstPara.innerHTML = prefix + dropSpan + rest;
      }

      // Matikan ::first-letter sementara agar tidak terjadi double-styling
      suppressStyle = document.createElement('style');
      suppressStyle.textContent =
        '.body-columns p:first-of-type::first-letter{' +
        'font-size:inherit!important;font-weight:inherit!important;' +
        'float:none!important;line-height:inherit!important;' +
        'padding:0!important;color:inherit!important;font-family:inherit!important}';
      document.head.appendChild(suppressStyle);
    }
    // ─────────────────────────────────────────────────────────────────────────

    const target = getTarget();
    const canvas = await html2canvas(target, {
      scale: 2,
      useCORS: true,
      allowTaint: true,
      backgroundColor: '#faf7ef',
      logging: false,
      scrollX: 0,
      scrollY: 0,
      windowWidth: target.scrollWidth,
      windowHeight: target.scrollHeight,
      onclone: (clonedDoc) => {
        const clonedImages = Array.from(clonedDoc.querySelectorAll('img'));
        const realImages = Array.from(target.querySelectorAll('img'));

        clonedImages.forEach((clonedImg, idx) => {
          const realImg = realImages[idx];
          if (!realImg || !realImg.complete || realImg.naturalWidth === 0 || realImg.naturalHeight === 0) return;

          const rect = realImg.getBoundingClientRect();
          if (rect.width <= 0 || rect.height <= 0) return;

          const offCanvas = clonedDoc.createElement('canvas');
          const dpr = 2;
          offCanvas.width = rect.width * dpr;
          offCanvas.height = rect.height * dpr;
          offCanvas.style.width = rect.width + 'px';
          offCanvas.style.height = rect.height + 'px';

          const style = window.getComputedStyle(realImg);
          if (style.borderRadius) offCanvas.style.borderRadius = style.borderRadius;
          if (style.border) offCanvas.style.border = style.border;
          if (style.boxShadow) offCanvas.style.boxShadow = style.boxShadow;
          offCanvas.style.display = 'block';

          const ctx = offCanvas.getContext('2d');
          const imgRatio = realImg.naturalWidth / realImg.naturalHeight;
          const targetRatio = rect.width / rect.height;

          let sw, sh, sx, sy;
          if (imgRatio > targetRatio) {
            sh = realImg.naturalHeight;
            sw = sh * targetRatio;
            sx = (realImg.naturalWidth - sw) / 2;
            sy = 0;
          } else {
            sw = realImg.naturalWidth;
            sh = sw / targetRatio;
            sx = 0;
            sy = (realImg.naturalHeight - sh) / 2;
          }

          try {
            ctx.drawImage(realImg, sx, sy, sw, sh, 0, 0, offCanvas.width, offCanvas.height);
            if (clonedImg.parentNode) {
              clonedImg.parentNode.replaceChild(offCanvas, clonedImg);
            }
          } catch (e) {
            console.warn('Canvas conversion skipped:', e);
          }
        });
      }
    });

    // Restore drop-cap ke CSS pseudo-element (balikin tampilan browser normal)
    if (firstPara && savedParaHTML !== null) {
      firstPara.innerHTML = savedParaHTML;
    }
    if (suppressStyle) {
      document.head.removeChild(suppressStyle);
    }

    toolbar.style.display = '';
    return canvas;
  }

  async function downloadAsPng() {
    setLoading('btnPng', true);
    try {
      const canvas = await captureCanvas();
      const link = document.createElement('a');
      link.download = FILENAME + '.png';
      link.href = canvas.toDataURL('image/png');
      link.click();
    } catch(e) {
      alert('Gagal mengunduh PNG. Coba lagi.');
      console.error(e);
    } finally {
      setLoading('btnPng', false);
    }
  }

  async function downloadAsPdf() {
    setLoading('btnPdf', true);
    try {
      const canvas = await captureCanvas();
      const imgData = canvas.toDataURL('image/png');
      const { jsPDF } = window.jspdf;
      const pxWidth  = canvas.width;
      const pxHeight = canvas.height;
      const mmWidth  = 210;
      const mmHeight = (pxHeight / pxWidth) * mmWidth;
      const pdf = new jsPDF({
        orientation: mmHeight > mmWidth ? 'portrait' : 'landscape',
        unit: 'mm',
        format: [mmWidth, mmHeight],
      });
      pdf.addImage(imgData, 'PNG', 0, 0, mmWidth, mmHeight);
      pdf.save(FILENAME + '.pdf');
    } catch(e) {
      alert('Gagal mengunduh PDF. Coba lagi.');
      console.error(e);
    } finally {
      setLoading('btnPdf', false);
    }
  }
</script>
</body>
</html>
