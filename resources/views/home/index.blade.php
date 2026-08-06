@extends('layouts.app')

@section('title', 'FORMAT-R UNESA — Forum Mahasiswa')

@push('styles')
<style>
  /* ===== HERO ===== */
  .hero{padding-top:70px;padding-bottom:60px;overflow:visible;}
  .hero-logo{
    display:inline-flex;align-items:center;justify-content:center;
    width:64px;height:64px;border-radius:50%;
    background:conic-gradient(from 200deg, var(--blue), var(--navy) 40%, var(--yellow) 75%, var(--blue));
    box-shadow:inset 0 0 0 4px var(--cream), 0 10px 24px rgba(11,37,69,0.18);
    margin-bottom:18px;position:relative;
  }
  .hero-logo::before{
    content:"";position:absolute;inset:-8px;border-radius:50%;border:1.5px dashed var(--line);
  }
  .hero-logo svg{position:relative;z-index:2;}
  .hero-grid{display:flex;flex-direction:column;align-items:center;text-align:center;}
  .hero h1{font-size:3.4rem;line-height:1.06;margin:18px 0 22px;color:var(--navy);}
  body.dark .hero h1{color:#fff;}
  .hero h1 .hl{color:var(--yellow-deep);position:relative;}
  body.dark .hero h1 .hl{color:var(--yellow);}
  .hero p.lede{font-size:1.08rem;color:var(--ink-soft);max-width:600px;margin-bottom:32px;}

  /* ===== 3 LOGO HERO ===== */
  .hero-logos {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 28px;
    margin-bottom: 20px;
  }
  .hero-logo-item {
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .hero-logo-frame {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .hero-logo-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
    pointer-events: none;
    user-select: none;
  }
  .hero-logo-frame--format img {
    object-fit: contain;
    padding: 6px;
    background: var(--blue-pale);
  }
  .hero-logo-frame--kabinet {
    background: #EAF1FC;
  }
  body.dark .hero-logo-frame--kabinet { background: #0F2545; }
  .kabinet-placeholder-svg { width: 52px; height: 52px; }

  @media(max-width:560px){
    .hero-logos { gap: 20px; }
    .hero-logo-frame { width: 52px; height: 52px; }
    .kabinet-placeholder-svg { width: 40px; height: 40px; }
  }
  /* ======================= */

  /* ===== UNESA BADGE ===== */
  .hero-unesa-badge{
    display:inline-flex;align-items:center;gap:12px;
    margin-bottom:32px;
  }
  .hub-line{
    flex:1;width:48px;height:1.5px;
    background:linear-gradient(90deg, transparent, var(--blue-light));
    display:block;
  }
  .hub-line:last-child{
    background:linear-gradient(90deg, var(--blue-light), transparent);
  }
  .hub-icon{
    width:30px;height:30px;border-radius:50%;
    background:var(--navy);color:var(--yellow);
    display:flex;align-items:center;justify-content:center;
    flex-shrink:0;
    box-shadow:0 4px 12px rgba(11,37,69,0.22);
  }
  body.dark .hub-icon{background:var(--blue);color:var(--yellow);}
  .hub-text{
    font-family:'Sora',sans-serif;
    font-size:clamp(0.92rem,2vw,1.12rem);
    font-weight:600;
    letter-spacing:0.08em;
    color:var(--navy);
    white-space:nowrap;
    position:relative;
    padding:6px 18px;
    background:var(--blue-pale);
    border-radius:100px;
    border:1.5px solid rgba(29,93,191,0.18);
  }
  body.dark .hub-text{
    color:#fff;
    background:rgba(29,93,191,0.18);
    border-color:rgba(78,143,224,0.25);
  }
  .btn-row{display:flex;gap:14px;flex-wrap:wrap;}

  .stat-row{display:flex;gap:34px;margin-top:46px;}
  .stat-row .stat b{font-family:'Sora',sans-serif;font-size:1.7rem;color:var(--navy);display:block;}
  body.dark .stat-row .stat b{color:#fff;}
  .stat-row .stat span{font-size:0.8rem;color:var(--ink-soft);}
  .count{display:inline-block;}

  /* hero emblem */
  .emblem-wrap{position:relative;aspect-ratio:1/1;display:flex;align-items:center;justify-content:center;}
  .ring{position:absolute;border-radius:50%;border:1.5px dashed var(--line);}
  .ring.r1{width:100%;height:100%;animation:spin 60s linear infinite;}
  .ring.r2{width:78%;height:78%;animation:spin 40s linear infinite reverse;}
  @keyframes spin{to{transform:rotate(360deg);}}
  .core{
    width:58%;aspect-ratio:1/1;border-radius:50%;
    background:conic-gradient(from 220deg, var(--blue), var(--navy) 35%, var(--yellow) 70%, var(--blue-light) 100%);
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 30px 60px rgba(11,37,69,0.28), inset 0 0 0 10px var(--cream);
    position:relative;z-index:2;
  }
  .core-inner{
    width:74%;aspect-ratio:1/1;border-radius:50%;background:var(--cream);
    display:flex;flex-direction:column;align-items:center;justify-content:center;gap:2px;overflow:hidden;
  }
  .core-inner img{width:100%;height:100%;object-fit:cover;border-radius:50%;}
  .core-inner .big{font-family:'Sora',sans-serif;font-weight:800;font-size:1.75rem;color:var(--navy);}
  body.dark .core-inner{background:var(--navy);}
  body.dark .core-inner .big{color:#fff;}
  .core-inner .small{font-family:'JetBrains Mono',monospace;font-size:0.65rem;letter-spacing:0.1em;color:var(--ink-soft);text-transform:uppercase;}
  .orbit-node{
    position:absolute;width:44px;height:44px;border-radius:50%;background:var(--cream);
    border:1px solid var(--line);display:flex;align-items:center;justify-content:center;
    font-family:'JetBrains Mono',monospace;font-size:0.62rem;font-weight:600;color:var(--navy);
    box-shadow:0 8px 18px rgba(11,37,69,0.14);z-index:3;text-align:center;
  }
  body.dark .orbit-node{color:#fff;}
  .n1{top:0%;left:44%;}
  .n2{top:20%;right:-2%;background:var(--yellow);border-color:var(--yellow-deep);color:var(--navy);}
  .n3{bottom:22%;right:-6%;}
  .n4{bottom:-2%;left:36%;background:var(--blue);color:#fff;border-color:var(--blue);}
  .n5{bottom:20%;left:-8%;}
  .n6{top:22%;left:-6%;background:var(--yellow);border-color:var(--yellow-deep);color:var(--navy);}

  /* ===== TENTANG ===== */
  .tentang{background:var(--blue-pale);border-radius:40px;margin:0 20px;}
  body.dark .tentang{background:#0F2545;}
  .tentang-grid{display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;}
  .tentang-copy p{color:var(--ink-soft);margin-bottom:16px;}
  .tentang-badge{
    display:flex;align-items:center;justify-content:center;
    aspect-ratio:1/1;border-radius:32px;
    background:var(--blue-pale);border:1px solid var(--line);
    overflow:hidden;position:relative;
  }
  body.dark .tentang-badge{background:#0F2545;}

  /* ===== VISI MISI ===== */
  .vm-layout{display:grid;grid-template-columns:0.85fr 1.15fr;gap:50px;align-items:center;margin-bottom:52px;}
  .vm-badge{
    display:flex;align-items:center;justify-content:center;
    aspect-ratio:1/1;border-radius:32px;
    background:var(--blue-pale);border:1px solid var(--line);
    position:relative;
  }
  body.dark .vm-badge{background:#0F2545;}
  .vm-badge.vm-badge--kabinet {
    background: #fff;
    border: 1px solid var(--line);
    padding: 20px;
    box-shadow: 0 16px 48px rgba(11,37,69,0.1);
  }
  .vm-badge.vm-badge--kabinet .kabinet-logo-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 20px;
    box-shadow: 0 8px 24px rgba(11,37,69,0.12);
  }
  .vm-badge.vm-badge--format {
    background: #fff;
    border: 1px solid var(--line);
    padding: 20px;
    box-shadow: 0 16px 48px rgba(11,37,69,0.1);
  }
  .vm-badge.vm-badge--format .format-logo-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 20px;
    box-shadow: 0 8px 24px rgba(11,37,69,0.12);
  }
  

  .vm-badge .badge-ring{
    width:70%;aspect-ratio:1/1;border-radius:50%;
    background:conic-gradient(from 210deg, var(--blue), var(--navy) 40%, var(--yellow) 75%, var(--blue));
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 24px 48px rgba(11,37,69,0.2);
  }
  .vm-badge .badge-ring-inner{
    width:76%;aspect-ratio:1/1;border-radius:50%;background:var(--cream);
    display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;
  }
  body.dark .vm-badge .badge-ring-inner{background:var(--navy);}
  .vm-badge .badge-ring-inner .big{font-family:'Sora',sans-serif;font-weight:800;font-size:1.9rem;color:var(--navy);}
  body.dark .vm-badge .badge-ring-inner .big{color:#fff;}
  .vm-badge .badge-ring-inner .small{font-family:'JetBrains Mono',monospace;font-size:0.65rem;letter-spacing:0.1em;color:var(--ink-soft);text-transform:uppercase;}
  .vm-content {
    display:flex;flex-direction:column;
  }
  .vm-text-group {
    background:var(--cream);border:1px solid var(--line);border-radius:20px;
    padding:28px 26px;margin-bottom:20px;
    box-shadow:0 12px 24px rgba(11,37,69,0.06);
  }
  body.dark .vm-text-group { background:var(--navy); }
  .vm-text-group:last-child { margin-bottom:0; }
  .vm-text-group h3 {
    font-size:1.4rem;color:var(--navy);margin-bottom:12px;
    display:inline-flex;align-items:center;gap:10px;
  }
  body.dark .vm-text-group h3 { color:#fff; }
  .vm-text-group h3::before {
    content:"";width:12px;height:12px;border-radius:50%;background:var(--yellow-deep);
  }
  .vm-text-group p, .vm-text-group ul {
    font-size:0.95rem;color:var(--ink-soft);line-height:1.7;
  }
  .vm-text-group ul { list-style:disc;padding-left:20px; }
  .vm-text-group ul li { margin-bottom:8px; }
  .vm-text-group ul li strong { color:var(--navy); }
  body.dark .vm-text-group ul li strong { color:#fff; }
  
  /* Scroll animation for long text */
  .vm-scroll-text {
    max-height: 200px;
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 8px;
    scrollbar-width: thin;
    scrollbar-color: var(--blue) transparent;
  }
  .vm-scroll-text::-webkit-scrollbar {
    width: 6px;
  }
  .vm-scroll-text::-webkit-scrollbar-track {
    background: transparent;
  }
  .vm-scroll-text::-webkit-scrollbar-thumb {
    background: var(--blue);
    border-radius: 3px;
  }
  .vm-scroll-text::-webkit-scrollbar-thumb:hover {
    background: var(--blue-dark);
  }
  
  /* Smooth scroll animation */
  .vm-scroll-text p,
  .vm-scroll-text li {
    animation: fadeInUp 0.6s ease-out forwards;
    opacity: 0;
  }
  .vm-scroll-text p:nth-child(1),
  .vm-scroll-text li:nth-child(1) { animation-delay: 0.1s; }
  .vm-scroll-text li:nth-child(2) { animation-delay: 0.2s; }
  .vm-scroll-text li:nth-child(3) { animation-delay: 0.3s; }
  .vm-scroll-text li:nth-child(4) { animation-delay: 0.4s; }
  .vm-scroll-text li:nth-child(5) { animation-delay: 0.5s; }
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* ===== DEPARTEMEN (section di beranda) ===== */
  .dept-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;}
  .dept-card{
    border:1px solid var(--line);border-radius:20px;padding:28px 22px;
    transition:all .25s ease;background:var(--cream);display:block;
  }
  .dept-card:hover{transform:translateY(-6px);box-shadow:0 20px 40px rgba(11,37,69,0.12);border-color:transparent;}
  .dept-more{
    display:inline-block;margin-top:14px;font-size:0.8rem;font-weight:600;color:var(--blue);
    opacity:0;transform:translateX(-4px);transition:all .2s ease;
  }
  body.dark .dept-more{color:var(--yellow);}
  .dept-card:hover .dept-more{opacity:1;transform:translateX(0);}
  .dept-icon{
    width:46px;height:46px;border-radius:12px;background:var(--navy);
    display:flex;align-items:center;justify-content:center;margin-bottom:20px;color:var(--yellow);
  }
  .dept-card .abbr{font-family:'JetBrains Mono',monospace;font-size:0.7rem;color:var(--blue);letter-spacing:0.06em;}
  body.dark .dept-card .abbr{color:var(--yellow);}
  .dept-card h4{font-size:1rem;color:var(--navy);margin:6px 0 10px;line-height:1.3;}
  body.dark .dept-card h4{color:#fff;}
  .dept-card p{font-size:0.85rem;color:var(--ink-soft);}

/* ===== PENGHARGAAN ===== */
  .spotlight-card{
    display:grid;grid-template-columns:1fr 1.6fr;gap:0;
    background:#fff;border:1px solid var(--line);border-radius:28px;
    overflow:hidden;box-shadow:0 24px 54px rgba(11,37,69,0.08);
    max-width: 760px; margin: 0 auto;
    transition: transform .3s ease, box-shadow .3s ease;
  }
  .spotlight-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 32px 64px rgba(11,37,69,0.12);
  }
  .spotlight-photo{
    position:relative;background:linear-gradient(135deg,var(--blue),var(--navy));
    height: 100%; display: flex; align-items: center; justify-content: center;
  }
  .spotlight-photo img{width:100%;height:100%;object-fit:cover;display:block;position:absolute;inset:0;}
  .spotlight-body{padding:32px 36px;display:flex;flex-direction:column;justify-content:center;gap:6px;}
  .spotlight-icon{font-size:1.6rem;margin-bottom:4px;}
  .spotlight-body .abbr{font-family:'JetBrains Mono',monospace;font-size:0.68rem;color:var(--blue);letter-spacing:0.08em;text-transform:uppercase;}
  body.dark .spotlight-body .abbr{color:var(--yellow);}
  .spotlight-body h3{font-size:1.35rem;color:var(--navy);margin:6px 0 2px;}
  body.dark .spotlight-body h3{color:#fff;}
  .spotlight-dept{
    display:inline-block;font-size:0.75rem;font-weight:600;color:var(--yellow-deep);
    background:rgba(255,199,48,0.16);padding:4px 12px;border-radius:100px;width:fit-content;margin-bottom:10px;
  }
  .spotlight-body p{font-size:0.9rem;color:var(--ink-soft);max-width:380px;}
  .spotlight-history{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:16px;}
  .history-item{
    background:var(--cream);border:1px solid var(--line);border-radius:14px;
    padding:14px 16px;display:flex;flex-direction:column;gap:3px;
  }
  .history_month{font-family:'JetBrains Mono',monospace;font-size:0.65rem;color:var(--blue);letter-spacing:0.06em;text-transform:uppercase;}
  body.dark .history_month{color:var(--yellow);}
  .history_name{font-weight:600;color:var(--navy);font-size:0.9rem;}
  body.dark .history_name{color:#fff;}

  /* ===== ULTAH CARD STYLES ===== */
  .ultah-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 18px;
  }
  .ultah-card {
    background: #fff;
    border-radius: 18px;
    padding: 20px;
    display: flex;
    gap: 16px;
    align-items: flex-start;
    box-shadow: 0 6px 20px rgba(11,37,69,0.05);
    border: 1px solid var(--line);
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    min-height: 150px;
  }
  .ultah-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(11,37,69,0.1);
  }
  .ultah-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--yellow), var(--yellow-deep), var(--accent-red));
  }
  .ultah-avatar {
    width: 72px;
    height: 72px;
    border-radius: 14px;
    background: linear-gradient(135deg, var(--yellow), var(--yellow-deep));
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    font-size: 1.4rem;
    color: var(--navy);
    flex-shrink: 0;
    overflow: hidden;
    position: relative;
    z-index: 1;
    box-shadow: 0 4px 12px rgba(232, 164, 0, 0.3);
  }
  .ultah-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 14px;
  }
  .ultah-avatar .init {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    font-size: 1.6rem;
    color: var(--navy);
  }
  .ultah-info { flex: 1; min-width: 0; }
  .ultah-date {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.68rem;
    font-weight: 700;
    color: var(--blue);
    letter-spacing: 0.1em;
    text-transform: uppercase;
    margin-bottom: 4px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }
  .ultah-name {
    font-size: 1.1rem;
    color: var(--navy);
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .ultah-dept {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--ink-soft);
    display: block;
    margin-bottom: 10px;
  }
  .ultah-message {
    font-size: 0.88rem;
    color: var(--ink-soft);
    font-style: italic;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  .ultah-card .ultah-photo-placeholder {
    position: absolute;
    bottom: 12px;
    right: 12px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: rgba(232, 164, 0, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--yellow-deep);
    font-size: 12px;
    opacity: 0.7;
    transition: opacity 0.2s, background 0.2s, color 0.2s;
  }
  .ultah-card:hover .ultah-photo-placeholder {
    opacity: 1;
    background: var(--yellow);
    color: var(--navy);
  }

  /* ===== BERITA ===== */
  .art-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;}
  .art-card{border-radius:22px;overflow:hidden;border:1px solid var(--line);background:var(--cream);transition:transform .25s ease, box-shadow .25s ease;}
  .art-card:hover{transform:translateY(-6px);box-shadow:0 20px 40px rgba(11,37,69,0.12);}
  .art-thumb{height:170px;background:linear-gradient(135deg,var(--blue),var(--navy));position:relative;}
  .art-thumb::after{
    content:"";position:absolute;inset:0;
    background:repeating-linear-gradient(45deg, rgba(255,199,48,0.14) 0 2px, transparent 2px 22px);
  }
  .art-body{padding:22px;}
  .art-tag{font-family:'JetBrains Mono',monospace;font-size:0.68rem;color:var(--yellow-deep);letter-spacing:0.08em;text-transform:uppercase;}
  .art-body h4{margin:10px 0 8px;font-size:1.02rem;color:var(--navy);}
  body.dark .art-body h4{color:#fff;}
  .art-body p{font-size:0.86rem;color:var(--ink-soft);}
  .art-meta{margin-top:16px;font-size:0.78rem;color:var(--ink-soft);display:flex;justify-content:space-between;}

  /* ===== ARSIP ===== */
  .archive-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;}
  .archive-card{
    background:var(--cream);border:1px solid var(--line);border-radius:20px;overflow:hidden;
    display:flex;flex-direction:column;transition:transform .25s ease, box-shadow .25s ease;position:relative;
  }
  .archive-card:hover{transform:translateY(-5px);box-shadow:0 18px 36px rgba(11,37,69,0.12);}
  .archive-thumb{height:140px;background:linear-gradient(135deg,var(--blue-light),var(--blue));position:relative;}
  .archive-thumb::after{
    content:"";position:absolute;inset:0;
    background:repeating-linear-gradient(-45deg, rgba(255,199,48,0.1) 0 2px, transparent 2px 20px);
  }
  .archive-badge{
    position:absolute;top:14px;right:14px;
    background:var(--navy);color:#fff;font-size:0.7rem;font-weight:600;
    padding:4px 12px;border-radius:100px;font-family:'JetBrains Mono',monospace;letter-spacing:0.06em;
  }
  .archive-body{padding:20px 22px;}
  .tl-date{font-family:'JetBrains Mono',monospace;font-size:0.68rem;color:var(--blue);letter-spacing:0.08em;text-transform:uppercase;}
  body.dark .tl-date{color:var(--yellow);}
  .archive-body h4{font-size:1rem;color:var(--navy);margin:8px 0 6px;}
  body.dark .archive-body h4{color:#fff;}
  .archive-body p{font-size:0.85rem;color:var(--ink-soft);}

  /* ===== FAQ ===== */
  .faq-item{border-bottom:1px solid var(--line);}
  .faq-q{
    padding:22px 4px;display:flex;justify-content:space-between;align-items:center;
    cursor:pointer;font-weight:600;color:var(--navy);font-size:1rem;
  }
  body.dark .faq-q{color:#fff;}
  .faq-q .plus{
    width:26px;height:26px;border-radius:50%;border:1px solid var(--line);
    display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:transform .25s ease;
    font-size:1rem;color:var(--blue);
  }
  .faq-item.open .plus{transform:rotate(45deg);background:var(--yellow);border-color:var(--yellow);color:var(--navy);}
  .faq-a{max-height:0;overflow:hidden;transition:max-height .3s ease;}
  .faq-item.open .faq-a{max-height:200px;}
  .faq-a p{padding:0 4px 22px;color:var(--ink-soft);font-size:0.92rem;max-width:640px;}

  /* ===== KONTAK ===== */
  .kontak{background:var(--navy);border-radius:40px;margin:0 20px;color:#fff;}
  .kontak .eyebrow{color:var(--yellow);}
  .kontak h2{color:#fff;}
  .kontak-grid{display:grid;grid-template-columns:1fr 1fr;gap:60px;}
  .kontak-list{margin-top:24px;display:grid;gap:18px;}
  .kontak-list li{display:flex;gap:14px;align-items:flex-start;font-size:0.92rem;color:#D9E3F5;}
  .kontak-list .kicon{
    width:38px;height:38px;border-radius:10px;background:rgba(255,199,48,0.15);
    display:flex;align-items:center;justify-content:center;color:var(--yellow);flex-shrink:0;
  }
  .form-field{margin-bottom:16px;}
  .form-field label{display:block;font-size:0.78rem;color:#AFC0DA;margin-bottom:6px;font-family:'JetBrains Mono',monospace;letter-spacing:0.05em;}
  .form-field input,.form-field textarea{
    width:100%;padding:13px 16px;border-radius:12px;border:1px solid rgba(255,255,255,0.18);
    background:rgba(255,255,255,0.06);color:#fff;font-family:'Inter',sans-serif;font-size:0.92rem;
  }
  .form-field input::placeholder,.form-field textarea::placeholder{color:#7C8CA8;}
  .form-field input:focus,.form-field textarea:focus{outline:2px solid var(--yellow);}
  .form-field.error input,.form-field.error textarea{outline:2px solid #ff6b6b;}
  .field-msg{font-size:0.74rem;color:#ff9a9a;margin-top:5px;display:none;}
  .form-field.error .field-msg{display:block;}
  .btn-submit{
    width:100%;padding:15px;border-radius:12px;background:var(--yellow);color:var(--navy);
    font-weight:700;font-size:0.94rem;margin-top:6px;transition:transform .15s ease, background .2s ease;
  }
  .btn-submit:hover{transform:translateY(-2px);}
  .btn-submit.sent{background:#4CD787;}
  .form-note{font-size:0.8rem;color:#AFC0DA;margin-top:10px;text-align:center;min-height:1em;}

  /* History Card Hover Effects */
  .history-card {
    background: #fff;
    border: 1px solid rgba(11,37,69,0.08);
    border-radius: 20px;
    padding: 24px 20px;
    text-align: center;
    transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
    box-shadow: 0 8px 24px rgba(11,37,69,0.03);
  }
  .history-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 32px rgba(11,37,69,0.1);
    border-color: rgba(11,37,69,0.15);
  }

  /* ===== GSAP STACK SCROLL ===== */
  .stack-section {
    box-sizing: border-box; /* WAJIB: Agar padding-top tidak menambah tinggi section menjadi 100vh + 85px */
    padding-top: 85px; /* Added to push content safely below the sticky navbar when scrolled to top */
    height: 100svh;
    min-height: 100svh;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    overflow-x: hidden;
    box-shadow: 0 -24px 60px rgba(11,37,69,0.16);
    will-change: transform, opacity;
    position: relative;
    z-index: 1;
    -webkit-overflow-scrolling: touch;
  }
  .stack-section::-webkit-scrollbar { width: 5px; }
  .stack-section::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.15); border-radius: 4px; }
  body.dark .stack-section::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); }

  .stack-section > .container {
    margin-top: auto;
    margin-bottom: auto;
  }
  /* Explicit z-index per section (bottom to top) */
  #home { z-index: 1; }       /* bottom - hero */
  #tentang { z-index: 2; }
  #visimisi { z-index: 3; }
  #berita { z-index: 4; }
  #apresiasi { z-index: 5; }
  #faq { z-index: 6; }
  #kontak { z-index: 7; }     /* top */

  body.dark .stack-section { box-shadow: 0 -24px 60px rgba(0,0,0,0.45); }

  /* Section backgrounds - ensure full coverage WITHOUT changing original colors */
  .stack-section { background: var(--cream) !important; }
  #tentang { background: var(--blue-pale) !important; }
  .kontak { background: var(--navy) !important; color: #fff !important; }
  #apresiasi { background: var(--blue-pale) !important; }
  #visimisi { background: var(--cream) !important; }
  #berita { background: var(--cream) !important; }
  #faq { background: var(--cream) !important; }

  /* ===== RESPONSIVE ===== */
  @media(max-width:980px){
    .hero-grid{grid-template-columns:1fr;}
    .emblem-wrap{max-width:340px;margin:40px auto 0;}
    .tentang-grid{grid-template-columns:1fr;}
    .tentang-badge{max-width:280px;margin:0 auto;}
    .vm-layout{grid-template-columns:1fr;gap:28px;}
    .vm-badge{max-width:340px;margin:0 auto;}
    .dept-grid{grid-template-columns:repeat(2,1fr);}
    .spotlight-card{grid-template-columns:1fr;}
    .spotlight-photo{aspect-ratio:16/9;}
    .spotlight-body{padding:32px 28px;}
    .spotlight-history{grid-template-columns:1fr;}
    .art-grid{grid-template-columns:1fr;}
    .archive-grid{grid-template-columns:1fr;}
    .kontak-grid{grid-template-columns:1fr;}
    .hero h1{font-size:2.4rem;}
  }
  @media(max-width:560px){
    .dept-grid{grid-template-columns:1fr;}
    .spotlight-body{padding:26px 22px;}
    .stat-row{gap:20px;flex-wrap:wrap;}
  }

  /* ===== MOBILE: DISABLE STACK SCROLL ANIMATIONS ===== */
  @media(max-width:767px){
    .stack-section { position: relative !important; height: auto !important; min-height: auto !important; }
    .stack-section[style*="transform"] { transform: none !important; opacity: 1 !important; }
  }
</style>
@endpush

@section('content')

{{-- ===== HERO ===== --}}
<section class="hero" id="home">
    <div class="container hero-grid">
        <div>
            {{-- ===== 3 LOGO HERO ===== --}}
            <div class="hero-logos">

                {{-- Logo UNESA (kiri) --}}
                <div class="hero-logo-item">
                    <div class="hero-logo-frame" data-parallax="0.3">
                        <img src="{{ asset('images/logo_unesa.jpg') }}" alt="Logo Universitas Negeri Surabaya">
                    </div>
                </div>

                {{-- Logo FORMAT-R (tengah) --}}
                <div class="hero-logo-item">
                    <div class="hero-logo-frame hero-logo-frame--format" data-parallax="0.3">
                        <img src="{{ asset('images/logo_format.png') }}" alt="Logo FORMAT-R UNESA">
                    </div>
                </div>

                {{-- Logo Kabinet (kanan) --}}
                <div class="hero-logo-item">
                    <div class="hero-logo-frame hero-logo-frame--kabinet" data-parallax="0.3">
                        <img src="{{ get_setting('cabinetLogo') ? Storage::url(get_setting('cabinetLogo')) : asset('images/logo_kabinet.jpeg') }}" alt="Logo Kabinet FORMAT-R" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                    </div>
                </div>
            </div>
            {{-- ======================== --}}
            <br>
            <h1 data-reveal>Forum Mahasiswa <span class="hl">Tuban Ronggolawe</span></h1>
            <h1 data-reveal>Universitas Negeri Surabaya</h1>
            <br>
            <span class="eyebrow">Periode 2026/2027</span>
            <br>
            <br>
            <div class="btn-row" style="justify-content:center;">
                <a href="#tentang" class="btn btn-primary">Kenali Kami →</a>
                <a href="#arsip" class="btn btn-ghost">Lihat Kegiatan</a>
            </div>
            <div class="stat-row" style="justify-content:center;" data-reveal>
                @foreach($stats as $s)
                <div class="stat">
                    <b class="count" data-target="{{ $s['value'] }}" data-suffix="{{ $s['suffix'] ?? '' }}">0</b>
                    <span>{{ $s['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ===== TENTANG ===== --}}
<section class="tentang stack-section" id="tentang" data-reveal>
    <div class="container tentang-grid" style="padding:70px 40px;">
        <div class="tentang-copy" data-stagger-child>
            <span class="eyebrow">Profil Organisasi</span>
            <h2>Tentang FORMAT-R UNESA</h2>
            <div class="vm-scroll-text" style="max-height: 240px; margin-bottom: 20px;">
                <p>{!! nl2br(e(get_setting('aboutFormat', 'FORMAT-R UNESA berdiri sebagai wadah komunikasi dan pengembangan diri mahasiswa di lingkungan Universitas Negeri Surabaya, merangkul beragam minat mulai dari akademik, sosial, hingga kerohanian.'))) !!}</p>
            </div>
        </div>
        <div class="tentang-badge" data-stagger-child data-parallax="0.3">
            <img
                src="{{ asset('images/logo_format.png') }}"
                alt="Logo FORMAT-R UNESA"
                style="width:100%;height:100%;object-fit:contain;padding:24px;"
            >
        </div>
    </div>
</section>

{{-- ===== VISI MISI ===== --}}
<section class="stack-section" id="visimisi" data-reveal>
    <div class="container vm-layout">
        {{-- Logo Kabinet (Kiri) --}}
        <div class="vm-badge vm-badge--kabinet" data-stagger-child data-parallax="0.2">
            <img src="{{ get_setting('cabinetLogo') ? Storage::url(get_setting('cabinetLogo')) : asset('images/logo_kabinet.jpeg') }}" alt="Logo Kabinet FORMAT-R" class="kabinet-logo-img">
        </div>

        {{-- Teks Visi & Misi (Kanan) --}}
        <div class="vm-content" data-stagger>
            <h2 style="margin-bottom:24px;">Visi & Misi</h2>
            
            <div class="vm-text-group" data-stagger-child>
                <h3>Visi</h3>
                <div class="vm-scroll-text">
                    <p>{{ get_setting('cabinetVision', 'Menjadi forum yang solid, inklusif, dan berdaya guna bagi mahasiswa UNESA.') }}</p>
                </div>
            </div>
            <div class="vm-text-group" data-stagger-child>
                <h3>Misi</h3>
                <div class="vm-scroll-text">
                    <ul>
                        @php
                            $misiText = get_setting('cabinetMission', "Misi Pemberdayaan: Menyediakan ruang belajar.\nMisi Kekeluargaan: Merawat solidaritas.\nMisi Advokasi: Menjadi suara mahasiswa.\nMisi Kolaborasi: Membangun jejaring.");
                            $misiArray = array_filter(explode("\n", $misiText));
                        @endphp
                        @foreach($misiArray as $misi)
                            <li>{{ trim($misi) }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

{{-- ===== BERITA ===== --}}
<section class="stack-section" id="berita" data-reveal>
    <div class="container">
        <div class="sec-head" data-stagger-child>
            <span class="eyebrow">Ruang Baca</span>
            <h2>Berita Terbaru</h2>
            <p>Cerita, tips, dan informasi seputar dunia perkuliahan dan kegiatan FORMAT-R.</p>
        </div>
        <div class="art-grid" id="beritaGrid" data-stagger>
            @forelse($berita as $b)
            <a href="{{ route('berita.show', $b->slug) }}" style="text-decoration:none; color:inherit; display:block; height:100%;">
                <article class="art-card" data-stagger-child style="display:flex; flex-direction:column; height:100%;">
                    <div class="art-thumb" style="height:200px; {{ $b->image ? 'background-image:url('.Storage::url($b->image).'); background-size:cover; background-position:center;' : 'background-color: var(--surface-alt); display: flex; align-items: center; justify-content: center;' }}">
                        @if(!$b->image)
                        <svg style="width: 48px; height: 48px; color: var(--ink-soft); opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        @endif
                    </div>
                    <div class="art-body" style="padding:20px; flex:1; display:flex; flex-direction:column;">
                        <div style="font-size: 0.8rem; color: var(--ink-soft); margin-bottom: 8px; font-weight: 500; letter-spacing: 0.5px;">
                            {{ $b->published_at ? \Carbon\Carbon::parse($b->published_at)->translatedFormat('d M Y') : $b->created_at->translatedFormat('d M Y') }}
                        </div>
                        <h4 style="margin:0 0 10px 0; font-size:1.15rem; line-height: 1.4;">{{ $b->title }}</h4>
                        <p style="font-size: 0.9rem; color: var(--ink-soft); margin-bottom: 0; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; flex: 1;">
                            {{ Str::limit(strip_tags($b->content), 120) }}
                        </p>
                        <div style="margin-top: 16px; font-size: 0.85rem; font-weight: 600; color: var(--blue); display: flex; align-items: center; gap: 4px;">
                            Baca Selengkapnya
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                        </div>
                    </div>
                </article>
            </a>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--ink-soft); border: 1px dashed var(--line); border-radius: 20px;" id="noBeritaMsg">
                <p>Belum ada berita yang dipublikasikan.</p>
            </div>
            @endforelse
        </div>
        
        @if($berita->lastPage() > 1)
        <div style="display: flex; justify-content: center; gap: 16px; margin-top: 40px;" data-reveal id="beritaPaginationControls">
            <button id="btnPrevBerita" disabled class="btn" style="background: var(--surface); color: var(--ink); border: 1px solid var(--line); display: inline-flex; align-items: center; justify-content:center; gap: 8px; cursor: not-allowed; opacity: 0.5; width: 48px; padding: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <a href="{{ route('berita.index') }}" class="btn" style="background: var(--surface); color: var(--ink); border: 1px solid var(--line); display: inline-flex; align-items: center; gap: 8px;">
                Lihat Semua Berita
            </a>
            <button id="btnNextBerita" data-page="2" class="btn" style="background: var(--surface); color: var(--ink); border: 1px solid var(--line); display: inline-flex; align-items: center; justify-content:center; gap: 8px; width: 48px; padding: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </button>
        </div>
        @else
        @if(count($berita) > 0)
        <div style="text-align: center; margin-top: 40px;" data-reveal>
            <a href="{{ route('berita.index') }}" class="btn" style="background: var(--surface); color: var(--ink); border: 1px solid var(--line); display: inline-flex; align-items: center; gap: 8px;">
                Lihat Semua Berita
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
            </a>
        </div>
        @endif
        @endif
    </div>
</section>

{{-- ===== APRESIASI (Penghargaan & Ultah) ===== --}}
<section class="stack-section" id="apresiasi" data-reveal>
    <div class="container" style="padding:70px 40px;">
        <div class="sec-head" data-stagger-child style="text-align:center; margin:0 auto 40px; max-width:100%;">
            <span class="eyebrow">Apresiasi & Kebersamaan</span>
            <h2>Keluarga FORMAT-R</h2>
            
            {{-- Switcher --}}
            <div class="apresiasi-tabs" style="display:inline-flex; background:#fff; padding:6px; border-radius:100px; margin-top:20px; box-shadow:0 4px 12px rgba(11,37,69,0.05);">
                <button class="apresiasi-tab active" data-target="penghargaan-content" style="padding:10px 24px; border-radius:100px; border:none; font-weight:700; font-family:'Inter', sans-serif; cursor:pointer; background:var(--navy); color:#fff; transition:0.3s;">Fungsionaris Terbaik</button>
                <button class="apresiasi-tab" data-target="ultah-content" style="padding:10px 24px; border-radius:100px; border:none; font-weight:700; font-family:'Inter', sans-serif; cursor:pointer; background:transparent; color:var(--ink-soft); transition:0.3s;">Ultah Hari Ini</button>
            </div>
        </div>
        {{-- TAB: PENGHARGAAN --}}
        <div id="penghargaan-content" class="apresiasi-pane" style="display:block;">
            @if($penghargaan['bulan_ini']->count() > 0)
            <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:32px; margin: 0 auto;">
                @foreach($penghargaan['bulan_ini'] as $best)
                <div style="width: 260px; aspect-ratio: 9/16; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 40px rgba(11,37,69,0.08); background: linear-gradient(135deg,var(--blue),var(--navy)); position: relative; display:flex; align-items:center; justify-content:center; font-family:'Sora', sans-serif; font-size:4rem; color:rgba(255,255,255,0.8);">
                    @if($best->photo)
                        <img src="{{ Storage::url($best->photo) }}" alt="Fungsionaris Terbaik" style="width:100%; height:100%; object-fit:cover; position:absolute; inset:0;">
                    @else
                        {{ strtoupper(substr($best->name, 0, 2)) }}
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div style="text-align: center; color: var(--ink-soft); padding: 40px;">
                Belum ada Fungsionaris Terbaik bulan ini.
            </div>
            @endif
        </div>

        {{-- TAB: ULANG TAHUN HARI INI --}}
        <div id="ultah-content" class="apresiasi-pane" style="display:none;">
            @if($ultahHariIni->count() > 0)
            <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:32px; margin: 0 auto;">
                @foreach($ultahHariIni as $ultah)
                <div style="width: 280px; background:#fff; border:1px solid var(--line); border-radius:24px; overflow:hidden; box-shadow:0 20px 40px rgba(11,37,69,0.08); display:flex; flex-direction:column;">
                    <div style="aspect-ratio: 9/16; background:linear-gradient(135deg, var(--yellow), var(--yellow-deep)); position: relative; display:flex; align-items:center; justify-content:center; font-family:'Sora', sans-serif; font-size:4rem; color:rgba(255,255,255,0.8);">
                        @if($ultah->photo)
                            <img src="{{ Storage::url($ultah->photo) }}" alt="{{ $ultah->name }}" style="width:100%; height:100%; object-fit:cover; position:absolute; inset:0;">
                        @else
                            {{ strtoupper(substr($ultah->name, 0, 2)) }}
                        @endif
                    </div>
                    <div style="padding:20px; display:flex; flex-direction:column; gap:6px; text-align:center;">
                        <span class="spotlight-icon" style="font-size:1.4rem;">🎂</span>
                        <p style="font-size:1rem; color:var(--navy); font-weight: 500; font-style:italic;">"{{ $ultah->message ? $ultah->message : 'Selamat bertambah usia!' }}"</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div style="text-align: center; color: var(--ink-soft); padding: 40px;">
                Belum ada yang berulang tahun hari ini.
            </div>
            @endif
        </div>

    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="stack-section" id="faq" data-reveal>
    <div class="container">
        <div class="sec-head" data-stagger-child>
            <span class="eyebrow">Pertanyaan Umum</span>
            <h2>FAQ</h2>
        </div>
        <div class="faq-list" data-stagger>
            @foreach($faq as $f)
            <div class="faq-item {{ $f['open'] ? 'open' : '' }}" data-stagger-child>
                <div class="faq-q">
                    <span>{{ $f['pertanyaan'] }}</span>
                    <span class="plus">+</span>
                </div>
                <div class="faq-a"><p>{{ $f['jawaban'] }}</p></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== KONTAK ===== --}}
<section class="kontak stack-section" id="kontak" data-reveal>
    <div class="container kontak-grid" style="padding:70px 40px;">
        <div data-stagger-child>
            <span class="eyebrow">Hubungi Kami</span>
            <h2>Mari Terhubung dengan FORMAT-R UNESA</h2>
            <p style="color:#AFC0DA;margin-top:12px;max-width:420px;">Punya pertanyaan, ide kolaborasi, atau ingin bergabung? Sampaikan pesanmu, kami akan segera merespons.</p>
            <ul class="kontak-list" data-stagger>
                <li data-stagger-child><span class="kicon">📍</span> Kampus Ketintang, Universitas Negeri Surabaya, Jawa Timur 60231</li>
                <li data-stagger-child><span class="kicon">✉️</span> formatr@unesa.ac.id</li>
                <li data-stagger-child><span class="kicon">📷</span> @formatr_unesa</li>
            </ul>
        </div>
        <form id="kontakForm" novalidate>
            <div class="form-field" id="fieldNama">
                <label>Nama</label>
                <input type="text" id="inputNama" placeholder="Nama lengkap kamu">
                <span class="field-msg">Nama wajib diisi.</span>
            </div>
            <div class="form-field" id="fieldEmail">
                <label>Email</label>
                <input type="email" id="inputEmail" placeholder="nama@email.com">
                <span class="field-msg">Masukkan email yang valid.</span>
            </div>
            <div class="form-field" id="fieldPesan">
                <label>Pesan</label>
                <textarea rows="4" id="inputPesan" placeholder="Tulis pesanmu di sini..."></textarea>
                <span class="field-msg">Pesan wajib diisi.</span>
            </div>
            <button class="btn-submit" type="submit" id="submitBtn">Kirim Pesan</button>
            <p class="form-note" id="formNote"></p>
        </form>
    </div>
</section>

@push('scripts')
<script>
    // AJAX Pagination untuk Berita
    const btnPrevBerita = document.getElementById('btnPrevBerita');
    const btnNextBerita = document.getElementById('btnNextBerita');
    const beritaGrid = document.getElementById('beritaGrid');
    
    if (btnNextBerita) {
        btnNextBerita.addEventListener('click', function() {
            const page = this.getAttribute('data-page');
            loadBerita(page);
        });
    }

    if (btnPrevBerita) {
        btnPrevBerita.addEventListener('click', function() {
            const page = this.getAttribute('data-page');
            if (page && page > 0) {
                loadBerita(page);
            }
        });
    }

    function loadBerita(page) {
        // Tampilkan loading state sederhana
        beritaGrid.style.opacity = '0.5';
        
        fetch(`{{ route('api.berita.paginate') }}?page=${page}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                beritaGrid.innerHTML = data.html;
                
                // Trigger animasi masuk
                setTimeout(() => {
                    beritaGrid.style.opacity = '1';
                    const newCards = beritaGrid.querySelectorAll('.ajax-art-card');
                    newCards.forEach((card, index) => {
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, index * 100);
                    });
                }, 50);

                // Update tombol pagination
                if (data.current_page > 1) {
                    btnPrevBerita.removeAttribute('disabled');
                    btnPrevBerita.style.cursor = 'pointer';
                    btnPrevBerita.style.opacity = '1';
                    btnPrevBerita.setAttribute('data-page', data.current_page - 1);
                } else {
                    btnPrevBerita.setAttribute('disabled', 'disabled');
                    btnPrevBerita.style.cursor = 'not-allowed';
                    btnPrevBerita.style.opacity = '0.5';
                }

                if (data.has_more) {
                    btnNextBerita.removeAttribute('disabled');
                    btnNextBerita.style.cursor = 'pointer';
                    btnNextBerita.style.opacity = '1';
                    btnNextBerita.setAttribute('data-page', data.current_page + 1);
                } else {
                    btnNextBerita.setAttribute('disabled', 'disabled');
                    btnNextBerita.style.cursor = 'not-allowed';
                    btnNextBerita.style.opacity = '0.5';
                }
            }
        })
        .catch(error => {
            console.error('Error fetching berita:', error);
            beritaGrid.style.opacity = '1';
        });
    }

    // Tab Apresiasi (Penghargaan vs Ultah)
    const apreTabs = document.querySelectorAll('.apresiasi-tab');
    const aprePanes = document.querySelectorAll('.apresiasi-pane');

    apreTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            apreTabs.forEach(t => {
                t.style.background = 'transparent';
                t.style.color = 'var(--ink-soft)';
            });
            this.style.background = 'var(--navy)';
            this.style.color = '#fff';

            aprePanes.forEach(p => p.style.display = 'none');
            const targetId = this.getAttribute('data-target');
            document.getElementById(targetId).style.display = 'block';
        });
    });
</script>
@endpush

@endsection

