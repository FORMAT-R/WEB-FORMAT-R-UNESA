const fs = require('fs');
let file = fs.readFileSync('D:/Dokumen/WEB FORMAT R UNESA/resources/views/admin/news/index.blade.php', 'utf8');

// 1. Wrap with Layout
file = file.replace(/<!DOCTYPE html>[\s\S]*?<style>/, `@extends('admin.layouts.app')
@section('page-title', 'Manajemen Berita')

@section('content')
@push('styles')
<style>`);

file = file.replace(/<\/style>[\s\S]*?<body>/, `
  /* Hover admin controls */
  .admin-controls {
    display: none; position: absolute; top: 10px; right: 10px; gap: 8px; z-index: 10;
  }
  .article-block { position: relative; }
  .article-block:hover .admin-controls { display: flex; }
  .btn-admin { padding: 4px 8px; font-family: var(--sans); font-size: 10px; font-weight: bold; border-radius: 4px; color: white; background: var(--ink); text-decoration: none; }
  .btn-admin.edit { background: #2563eb; }
  .btn-admin.delete { background: var(--red); border: none; cursor: pointer; }
</style>
@endpush

<div class="space-y-6">
  <div class="flex justify-between items-center mb-6">
      <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Berita</h1>
          <p class="text-gray-500 dark:text-gray-400 mt-1">Kelola berita Anda dari halaman redaksi koran ini.</p>
      </div>
      <a href="{{ route('admin.berita.create') }}" class="btn-primary" style="background:var(--ink); color:white; padding:10px 16px; border-radius:8px; font-family:var(--sans); font-weight:600; display:flex; align-items:center; gap:8px;">
          Tambah Berita
      </a>
  </div>

  @php
      $berita = [
          ['judul' => 'Pelatihan Desain Grafis Sukses Digelar', 'tag' => 'Kabar FORMAT', 'konten' => 'Kegiatan pelatihan desain grafis untuk anggota baru berjalan dengan sangat sukses dan antusias.', 'penulis' => 'Divisi Media', 'menit' => 4, 'status' => 'published', 'slug' => 'pelatihan-desain'],
          ['judul' => 'Rapat Kerja Tahunan 2024', 'tag' => 'Agenda', 'konten' => 'Membahas program kerja yang akan dilaksanakan pada tahun 2024 mendatang.', 'penulis' => 'BPH', 'menit' => 3, 'status' => 'draft', 'slug' => 'raker-2024'],
          ['judul' => 'Selamat Datang Mahasiswa Baru', 'tag' => 'Berita', 'konten' => 'Ucapan selamat datang dari ketua FORMAT-R untuk seluruh mahasiswa baru angkatan 2023.', 'penulis' => 'Ketua Umum', 'menit' => 2, 'status' => 'archived', 'slug' => 'selamat-datang']
      ];
  @endphp
`);

// Replace the end tags
file = file.replace(/<\/body>[\s\S]*?<\/html>/, `
</div>
@endsection
`);

// Replace Utility Nav links
file = file.replace(/<nav class="util-nav">[\s\S]*?<\/nav>/, `<nav class="util-nav">
    <div class="util-item"></div>
    <div class="util-item"></div>
    <div class="util-item"></div>
    <div class="weather">
      <div class="temps"><b>{{ date('d') }}</b> {{ date('F Y') }}</div>
    </div>
  </nav>`);

// Replace Title
file = file.replace('<h1>THE NEWSPAPER</h1>', '<h1>FORMAT NEWS</h1>');
file = file.replace('Sumber Berita dan Informasi Tepercaya Sejak 1980', 'Manajemen Berita FORMAT-R UNESA');
file = file.replace('VOL. CXLIV . . . No. 59,203', 'SEJAK 2019');
file = file.replace('KAMIS, 16 NOVEMBER 2026', 'FORMAT-R UNESA');
file = file.replace('HARGA Rp5.000', 'EDISI DARING');

// Left Rail
file = file.replace(/<div class="rail-title">Semburat Terbit<\/div>[\s\S]*?<\/ul>/, `<div class="rail-title">Manajemen Kategori</div>
      <ul class="story-list">
        <li class="article-block">
          <h4>Kabar FORMAT</h4>
          <p>1 Artikel Dipublikasikan</p>
        </li>
        <li class="article-block">
          <h4>Agenda</h4>
          <p>1 Draft Artikel</p>
        </li>
      </ul>`);

// Hero
file = file.replace(/<article class="hero">[\s\S]*?<div class="second-story">/, `<article class="hero article-block">
      <div class="admin-controls">
        <a href="{{ route('admin.berita.edit', $berita[0]['slug']) }}" class="btn-admin edit">Edit</a>
        <button class="btn-admin delete">Hapus</button>
      </div>
      <h2>{{ $berita[0]['judul'] }}</h2>
      <div class="byline">Oleh <b>{{ $berita[0]['penulis'] }}</b> &middot; Redaksi FORMAT-R</div>
      <div class="body-columns" style="columns:1;">
        <p>{{ $berita[0]['konten'] }}</p>
      </div>
      
      <div class="second-story article-block">`);

// Second Story
file = file.replace(/<div class="second-story article-block">[\s\S]*?<\/article>/, `<div class="second-story article-block">
        <div class="admin-controls">
          <a href="{{ route('admin.berita.edit', $berita[1]['slug']) }}" class="btn-admin edit">Edit</a>
          <button class="btn-admin delete">Hapus</button>
        </div>
        <h3>{{ $berita[1]['judul'] }}</h3>
        <div class="byline">Oleh <b>{{ $berita[1]['penulis'] }}</b> &middot; {{ $berita[1]['tag'] }}</div>
        <div class="body-columns" style="columns:1;">
          <p>{{ $berita[1]['konten'] }}</p>
        </div>
      </div>
    </article>`);

// Right Rail - Latest Box
file = file.replace(/<div class="latest-box">[\s\S]*?<\/ul>/, `<div class="latest-box">
        <h4>Berita Lainnya</h4>
        <ul>
          @foreach($berita as $item)
          <li class="article-block" style="padding-right:20px;">
            <div class="admin-controls" style="top:5px; right:0;">
              <a href="{{ route('admin.berita.edit', $item['slug']) }}" class="btn-admin edit" style="padding:2px 4px; font-size:8px;">E</a>
            </div>
            <a href="#">{{ $item['judul'] }}</a>
          </li>
          @endforeach
        </ul>`);

file = file.replace('TheNews<span>APLIKASI ANDA</span>', 'FormatR<span>ORGANISASI MAHASISWA</span>');
file = file.replace('&#9679; Realestate', '&#9679; FORMAT-R UNESA');
file = file.replace('<span class="tag">Properti &amp; Perkotaan</span>', '<span class="tag">Organisasi Mahasiswa</span>');
file = file.replace('<h5>Hidup di Kota, Dengan Caramu</h5>', '<h5>Bergerak Bersama, Berkarya Nyata</h5>');

fs.writeFileSync('D:/Dokumen/WEB FORMAT R UNESA/resources/views/admin/news/index.blade.php', file);
