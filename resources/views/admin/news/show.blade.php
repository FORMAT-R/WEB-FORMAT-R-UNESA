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
  .story-list p{font-size:11.5px; color:var(--ink-soft); line-height:1.48;}
  .story-list .jump{font-family:var(--sans); font-size:10px; font-weight:700; color:var(--red); text-transform:uppercase; letter-spacing:.04em;}

  /* left column solar feature */
  .aside-feature{margin-top:24px;}
  .aside-feature figure{margin-bottom:8px;}
  .aside-feature h4{font-family:var(--serif-display); font-size:19px; font-weight:700; line-height:1.22; margin-bottom:6px;}
  .aside-feature p{font-size:11.5px; color:var(--ink-soft); line-height:1.55; margin-bottom:7px;}
  .aside-feature .byline{font-family:var(--sans); font-size:10px; color:var(--ink-soft); text-transform:uppercase; letter-spacing:.04em; margin-bottom:8px;}

  /* center hero */
  .hero h2{
    font-family:var(--serif-display); font-weight:900; font-size:clamp(26px,3vw,34px); line-height:1.12; margin-bottom:14px;
    overflow-wrap:break-word; word-break:break-word;
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
  }
  .body-columns p{
    margin-bottom:10px;
    overflow-wrap:break-word; word-break:break-word; word-wrap:break-word;
    -webkit-hyphens:auto; hyphens:auto;
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
  .side-story h4{font-family:var(--serif-display); font-size:18px; font-weight:700; line-height:1.24; margin-bottom:5px;}
  .side-story .kicker{
    font-family:var(--sans); font-size:10px; font-weight:700; letter-spacing:.08em; text-transform:uppercase;
    color:var(--blue); display:block; margin-bottom:4px;
  }
  .side-story p{font-size:11.5px; color:var(--ink-soft); line-height:1.5; margin-bottom:6px;}
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
  .weekly-card figcaption{
    font-family:var(--sans); font-size:10px; color:var(--ink-soft); margin-top:5px; font-style:italic;
  }
  .weekly-card h4{
    font-family:var(--serif-display); font-size:17px; font-weight:700; line-height:1.26; margin-bottom:6px;
  }
  .weekly-card p{font-size:12px; color:var(--ink-soft); line-height:1.52; margin-bottom:7px;}
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
    <span>FORMAT-R UNESA</span>
    <span>ADMIN PREVIEW</span>
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
</body>
</html>
