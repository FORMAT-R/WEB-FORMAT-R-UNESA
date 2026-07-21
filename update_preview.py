import re
import os

css_content = '''
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
  body{background:var(--paper-dim); color:var(--ink); font-family:var(--serif-body); line-height:1.5; overflow-x:hidden;}
  a{color:inherit; text-decoration:none;}
  a:focus-visible, button:focus-visible{outline:2px solid var(--red); outline-offset:2px;}

  .page{
    max-width:1180px; margin:0 auto; background:var(--paper);
    box-shadow:0 0 0 1px var(--rule);
    padding:22px clamp(16px,3vw,48px) 0;
  }

  .util-nav{
    display:flex; justify-content:space-between; align-items:center;
    padding-bottom:16px; border-bottom:3px double var(--rule-dark); font-family:var(--sans);
  }
  .util-item .eyebrow{
    font-size:10px; letter-spacing:.12em; font-weight:700; text-transform:uppercase;
    color:var(--red); display:block; margin-bottom:3px;
  }
  .util-item p{font-size:11.5px; color:var(--ink-soft); font-family:var(--serif-body);}
  
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

  .main-grid{display:grid; grid-template-columns:235px 1fr 265px; gap:26px; padding:26px 0;}

  .rail-title{
    font-family:var(--sans); font-size:11px; font-weight:700; letter-spacing:.1em; text-transform:uppercase;
    color:var(--red); border-bottom:2px solid var(--rule-dark); padding-bottom:6px; margin-bottom:14px;
  }

  .aside-feature{margin-top:24px;}
  .aside-feature figure{margin-bottom:8px;}
  .aside-feature h4{font-family:var(--serif-display); font-size:19px; font-weight:700; line-height:1.22; margin-bottom:6px;}
  .aside-feature p{font-size:11.5px; color:var(--ink-soft); line-height:1.55; margin-bottom:7px;}
  .aside-feature .byline{font-family:var(--sans); font-size:10px; color:var(--ink-soft); text-transform:uppercase; letter-spacing:.04em; margin-bottom:8px;}

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

  .latest-box{margin-bottom:16px;}
  .latest-box h4{
    font-family:var(--sans); font-size:12px; font-weight:700; letter-spacing:.08em; text-transform:uppercase;
    margin-bottom:10px; color:var(--ink);
  }
  .latest-box ul{list-style:none; font-family:var(--sans); font-size:11.5px; color:var(--ink-soft);}
  .latest-box li{display:flex; gap:8px; padding:6px 0; border-bottom:1px dotted var(--rule);}
  .latest-box li::before{content:'●'; color:var(--red); font-size:8px; margin-top:4px;}

  @media (max-width:960px){
    .main-grid{grid-template-columns:1fr;}
    .body-columns{columns:1;}
  }
'''

def update_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Remove old push styles
    content = re.sub(r"@push\('styles'\)\s*<style>.*?</style>\s*@endpush", "", content, flags=re.DOTALL)
    
    # Change grid layout to full width sections
    content = content.replace('<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">', '<div class="flex flex-col gap-8">')
    content = content.replace('<div class="lg:col-span-5 space-y-6">', '<div class="w-full space-y-6">')
    content = content.replace('<div class="lg:col-span-7">', '<div class="w-full">')
    
    # Replace preview wrapper with iframe
    preview_wrapper_pattern = r"<div class=\"preview-wrapper\">.*?</div>\s*</div>\s*</div>\s*</div>"
    
    iframe_html = '''
                <div class="border-2 border-gray-300 dark:border-gray-600 rounded-xl overflow-hidden shadow-lg bg-gray-50" style="height: 800px;">
                    <iframe :srcdoc="getIframeContent()" class="w-full h-full border-0"></iframe>
                </div>
            </div>
        </div>
    </div>
'''
    content = re.sub(preview_wrapper_pattern, iframe_html, content, flags=re.DOTALL)

    # Add getIframeContent to Alpine component
    alpine_script_pattern = r"formatKonten\(text\) \{.*?\n        \}"
    
    get_iframe_func = f'''formatKonten(text) {{
            if (!text) return '<p>Mulai ketikkan isi berita di kolom sebelah kiri untuk melihat hasil pratinjaunya di sini.</p>';
            return text.split('\\n\\n').map(p => `<p>${{p.replace(/\\n/g, '<br>')}}</p>`).join('');
        }},
        getIframeContent() {{
            const judul = this.form.judul || 'Judul Berita Akan Tampil Di Sini';
            const konten = this.formatKonten(this.form.konten);
            const penulis = this.form.penulis || 'Redaksi FORMAT-R';
            const caption = this.form.caption || 'Keterangan foto...';
            
            let imgHtml = '';
            if (this.form.fotoPreview) {{
                imgHtml = `
                <figure>
                    <div class="hero-image">
                        <img src="${{this.form.fotoPreview}}" alt="Foto Berita" loading="lazy">
                    </div>
                    <figcaption>${{caption}}</figcaption>
                </figure>
                `;
            }}
            
            return `
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
{css_content}
</style>
</head>
<body>
<div class="page">
  <nav class="util-nav">
    <div class="util-item" style="display:flex; align-items:center;">
      <span style="font-weight:700; color:var(--red); text-transform:uppercase; letter-spacing:1px; font-size:12px;">LIVE PREVIEW</span>
    </div>
  </nav>

  <div class="masthead">
    <h1>FORMAT NEWS</h1>
    <p class="tagline">Sumber Berita dan Informasi Mahasiswa UNESA</p>
  </div>

  <div class="issue-bar">
    <span>FORMAT-R UNESA</span>
    <span>EDISI SPESIAL</span>
  </div>

  <div class="main-grid">
    <aside>
      <div class="aside-feature" style="margin-top:0;">
        <div class="rail-title">Baca Juga</div>
        <figure>
            <svg viewBox="0 0 230 140" width="100%" height="auto">
              <rect width="230" height="140" fill="#ece4d1"/>
              <text x="50%" y="50%" font-family="var(--sans)" font-size="12" fill="#8f978f" text-anchor="middle" dy=".3em">Format News</text>
            </svg>
        </figure>
        <h4><a href="#" style="color:var(--ink); text-decoration:none;">Contoh Berita Sebelumnya</a></h4>
        <p>Ini adalah contoh teks pratinjau berita yang akan ditampilkan pada sidebar sebelah kiri.</p>
        <div class="byline">Redaksi &middot; FORMAT NEWS</div>
      </div>
    </aside>

    <article class="hero">
      <h2>${{judul}}</h2>
      ${{imgHtml}}
      <div class="byline"><b>${{penulis}}</b> &middot; Koresponden FORMAT-R</div>
      <div class="body-columns">
        ${{konten}}
      </div>
    </article>

    <aside>
      <div class="latest-box">
        <h4 style="margin-bottom:12px; border-bottom:2px solid var(--rule-dark); padding-bottom:8px;">Berita Lainnya</h4>
        <ul style="display:flex; flex-direction:column; gap:16px;">
          <li style="border:none; padding:0;">
            <div style="display:flex; flex-direction:column; gap:6px;">
              <a href="#" style="font-weight:600; color:var(--ink); line-height:1.3; font-size:13px;">Judul Berita Lainnya Pertama</a>
              <span style="font-size:10px; color:var(--red); font-weight:700; letter-spacing:0.06em; text-transform:uppercase;">FORMAT NEWS</span>
            </div>
          </li>
          <li style="border:none; padding:0;">
            <div style="display:flex; flex-direction:column; gap:6px;">
              <a href="#" style="font-weight:600; color:var(--ink); line-height:1.3; font-size:13px;">Judul Berita Lainnya Kedua</a>
              <span style="font-size:10px; color:var(--red); font-weight:700; letter-spacing:0.06em; text-transform:uppercase;">FORMAT NEWS</span>
            </div>
          </li>
        </ul>
      </div>
    </aside>
  </div>
</div>
</body>
</html>
`;
        }}'''
    content = re.sub(alpine_script_pattern, get_iframe_func, content, flags=re.DOTALL)
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

update_file(r"D:\Dokumen\WEB FORMAT R UNESA\resources\views\admin\news\create.blade.php")
update_file(r"D:\Dokumen\WEB FORMAT R UNESA\resources\views\admin\news\edit.blade.php")
print('Update complete')
