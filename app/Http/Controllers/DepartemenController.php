<?php

namespace App\Http\Controllers;

class DepartemenController extends Controller
{
    /**
     * Data semua departemen (dummy — akan diganti database nanti)
     */
    private function getAllDepartemen(): array
    {
        return [
            [
                'slug'      => 'badan-pengurus-harian',
                'singkatan' => 'BPH',
                'nama'      => 'Badan Pengurus Harian',
                'deskripsi' => 'Koordinator utama seluruh kegiatan, kebijakan, dan koordinasi antar departemen FORMAT-R UNESA.',
                'warna'     => 'navy',
                'tema_visual' => 'klasik',
                'theme'     => [
                    'mat-green' => '#28362b', 'mat-green-line' => '#33452f',
                    'purple' => '#4c2f7a', 'purple-dark' => '#341f57',
                    'blue' => '#3b6fd1', 'blue-dark' => '#1f3f82',
                ],
                'bg_pattern'=> 'linear-gradient(var(--mat-green-line) 1px, transparent 1px), linear-gradient(90deg, var(--mat-green-line) 1px, transparent 1px)',
                'ornaments' => [
                    ['pos' => 'top: 15%; left: 5%;', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>'], // shield
                    ['pos' => 'top: 45%; right: 5%;', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>'], // document
                    ['pos' => 'bottom: 20%; left: 8%;', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.3"/></svg>'], // sync/process
                ],
                'icon'      => 'building',
                'anggota'   => [],
                'proker' => [
                    ['no' => '01', 'nama' => 'Rapat Kerja Rutin',         'desc' => 'Koordinasi mingguan seluruh departemen untuk evaluasi progres kegiatan.'],
                    ['no' => '02', 'nama' => 'Laporan Pertanggungjawaban','desc' => 'Penyusunan LPJ akhir periode seluruh departemen.'],
                    ['no' => '03', 'nama' => 'Pelantikan Anggota Baru',   'desc' => 'Pelantikan resmi anggota baru FORMAT-R setiap periode.'],
                ],
                'divisi' => [],
            ],
            [
                'slug'      => 'komunikasi-dan-informasi',
                'singkatan' => 'KOMINFO',
                'nama'      => 'Komunikasi & Informasi',
                'deskripsi' => 'Mengelola arus informasi, publikasi media sosial, dan dokumentasi seluruh kegiatan FORMAT-R UNESA.',
                'warna'     => 'blue',
                'tema_visual' => 'klasik',
                'theme'     => [
                    'mat-green' => '#1b2a47', 'mat-green-line' => '#24375b',
                    'purple' => '#c27b34', 'purple-dark' => '#9e6226',
                    'blue' => '#2fa3a8', 'blue-dark' => '#1f7d82',
                ],
                'bg_pattern'=> 'radial-gradient(var(--mat-green-line) 2px, transparent 2px)',
                'ornaments' => [
                    ['pos' => 'top: 18%; left: 8%; transform:rotate(-15deg);', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>'], // code <>
                    ['pos' => 'top: 50%; right: 6%; transform:rotate(10deg);', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>'], // camera
                    ['pos' => 'bottom: 15%; left: 5%; transform:rotate(-5deg);', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/></svg>'], // pen tool / design
                ],
                'icon'      => 'signal',
                'anggota'   => [],
                'proker' => [
                    ['no' => '01', 'nama' => 'Publikasi Harian',         'desc' => 'Pembuatan konten dan publikasi rutin di media sosial FORMAT-R.'],
                    ['no' => '02', 'nama' => 'Dokumentasi Kegiatan',     'desc' => 'Peliputan foto dan video setiap kegiatan organisasi.'],
                    ['no' => '03', 'nama' => 'Website & Pengelolaan Data','desc' => 'Pemeliharaan website resmi dan arsip digital FORMAT-R.'],
                ],
                'divisi' => [],
            ],
            [
                'slug'      => 'pendidikan-dan-penalaran',
                'singkatan' => 'PENLAR',
                'nama'      => 'Penalaran & Riset',
                'deskripsi' => 'Memfasilitasi pengembangan kemampuan akademik, penelitian ilmiah, dan kompetisi intelektual mahasiswa UNESA.',
                'warna'     => 'yellow',
                'tema_visual' => 'klasik',
                'theme'     => [
                    'mat-green' => '#472b1b', 'mat-green-line' => '#5c3823',
                    'purple' => '#327057', 'purple-dark' => '#214f3c',
                    'blue' => '#a84136', 'blue-dark' => '#802f26',
                ],
                'bg_pattern'=> 'repeating-linear-gradient(0deg, transparent, transparent 19px, var(--mat-green-line) 19px, var(--mat-green-line) 20px)',
                'ornaments' => [
                    ['pos' => 'top: 15%; right: 10%; transform:rotate(12deg);', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>'], // magnifying glass
                    ['pos' => 'bottom: 25%; right: 5%; transform:rotate(-15deg);', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 3h6a4 4 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>'], // open book
                    ['pos' => 'top: 40%; left: 5%; transform:rotate(-8deg);', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4l2 5h9a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-4"/></svg>'], // folder
                ],
                'icon'      => 'book',
                'anggota'   => [],
                'proker' => [
                    ['no' => '01', 'nama' => 'Pelatihan Karya Tulis Ilmiah', 'desc' => 'Workshop intensif penulisan KTI dan proposal penelitian.'],
                    ['no' => '02', 'nama' => 'Olimpiade Internal FORMAT-R',  'desc' => 'Kompetisi pengetahuan dan logika antar anggota.'],
                    ['no' => '03', 'nama' => 'Bedah Buku & Diskusi Ilmiah',  'desc' => 'Forum diskusi bulanan untuk mengkaji topik akademik terkini.'],
                ],
                'divisi' => [],
            ],
            [
                'slug'      => 'kewirausahaan',
                'singkatan' => 'KWU',
                'nama'      => 'Kewirausahaan',
                'deskripsi' => 'Menumbuhkan jiwa wirausaha, kreativitas bisnis, dan kemandirian ekonomi mahasiswa UNESA.',
                'warna'     => 'green',
                'tema_visual' => 'klasik',
                'theme'     => [
                    'mat-green' => '#403b22', 'mat-green-line' => '#544d2d',
                    'purple' => '#6b364a', 'purple-dark' => '#4d2433',
                    'blue' => '#478248', 'blue-dark' => '#2e592e',
                ],
                'bg_pattern'=> 'repeating-linear-gradient(45deg, transparent, transparent 10px, var(--mat-green-line) 10px, var(--mat-green-line) 11px)',
                'ornaments' => [
                    ['pos' => 'top: 20%; left: 10%; transform:rotate(-15deg);', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>'], // dollar
                    ['pos' => 'top: 50%; right: 5%; transform:rotate(12deg);', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>'], // cart
                    ['pos' => 'bottom: 15%; left: 8%; transform:rotate(5deg);', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>'], // trending up
                ],
                'icon'      => 'chart',
                'anggota'   => [],
                'proker' => [
                    ['no' => '01', 'nama' => 'Bazar Kewirausahaan',     'desc' => 'Event pasar kreatif produk usaha mandiri anggota FORMAT-R.'],
                    ['no' => '02', 'nama' => 'Workshop Bisnis Digital',  'desc' => 'Pelatihan membuat dan mengelola usaha di platform digital.'],
                    ['no' => '03', 'nama' => 'Mentoring UMKM Kampus',   'desc' => 'Pendampingan usaha kecil anggota dengan mentor berpengalaman.'],
                ],
                'divisi' => [],
            ],
            [
                'slug'      => 'kerohanian',
                'singkatan' => 'ROHANI',
                'nama'      => 'Kerohanian',
                'deskripsi' => 'Membangun karakter spiritual, moral, dan nilai keagamaan mahasiswa sebagai landasan pengembangan diri.',
                'warna'     => 'teal',
                'tema_visual' => 'klasik',
                'theme'     => [
                    'mat-green' => '#1d3330', 'mat-green-line' => '#284743',
                    'purple' => '#9e813a', 'purple-dark' => '#755d24',
                    'blue' => '#5e4887', 'blue-dark' => '#423161',
                ],
                'bg_pattern'=> 'radial-gradient(circle at center, transparent 0, var(--mat-green) 100%)',
                'ornaments' => [
                    ['pos' => 'top: 15%; right: 15%; transform:rotate(5deg);', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" opacity="0"/></svg>'], // (kosong, ganti bentuk abstrak)
                    ['pos' => 'top: 10%; right: 8%; transform:rotate(15deg) scale(1.5);', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/><path d="M12 6c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6zm0 10c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z"/></svg>'], // circle mandala
                    ['pos' => 'bottom: 25%; left: 8%; transform:rotate(-10deg) scale(1.2);', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zM12 4a8 8 0 1 0 0 16 8 8 0 0 0 0-16z"/><path d="M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12zM12 6a4 4 0 1 0 0 8 4 4 0 0 0 0-8z"/></svg>'], // inner circles
                ],
                'icon'      => 'heart',
                'anggota'   => [],
                'proker' => [
                    ['no' => '01', 'nama' => 'Kajian Rutin',           'desc' => 'Kajian keagamaan mingguan untuk pembinaan moral anggota.'],
                    ['no' => '02', 'nama' => 'Bakti Sosial & Santunan','desc' => 'Kegiatan sosial dan santunan kepada yang membutuhkan.'],
                    ['no' => '03', 'nama' => 'Peringatan Hari Besar',  'desc' => 'Penyelenggaraan kegiatan peringatan hari-hari besar keagamaan.'],
                ],
                'divisi' => [],
            ],
            [
                'slug'      => 'minat-dan-bakat',
                'singkatan' => 'MINBA',
                'nama'      => 'Minat & Bakat',
                'deskripsi' => 'Mewadahi dan mengembangkan potensi seni, olahraga, dan kreativitas mahasiswa UNESA secara optimal.',
                'warna'     => 'purple',
                'tema_visual' => 'klasik',
                'theme'     => [
                    'mat-green' => '#3a203b', 'mat-green-line' => '#4d2a4d',
                    'purple' => '#3e748f', 'purple-dark' => '#275066',
                    'blue' => '#8f5c32', 'blue-dark' => '#6b411f',
                ],
                'bg_pattern'=> 'repeating-radial-gradient(circle at 50% 50%, transparent, transparent 10px, var(--mat-green-line) 10px, var(--mat-green-line) 11px)',
                'ornaments' => [
                    ['pos' => 'top: 15%; left: 10%; transform:rotate(-15deg);', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>'], // music note
                    ['pos' => 'top: 45%; right: 8%; transform:rotate(12deg);', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/></svg>'], // basketball / globe
                    ['pos' => 'bottom: 20%; left: 5%; transform:rotate(20deg);', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10c0 5.523-4.477 10-10 10zM12 4a8 8 0 1 0 0 16 8 8 0 0 0 0-16z"/><circle cx="8" cy="10" r="1.5" fill="currentColor"/><circle cx="16" cy="10" r="1.5" fill="currentColor"/><path d="M12 16c-1.657 0-3-1.343-3-3h6c0 1.657-1.343 3-3 3z"/></svg>'], // theatre mask (smile)
                ],
                'icon'      => 'star',
                'anggota'   => [],
                'proker' => [
                    ['no' => '01', 'nama' => 'Festival Seni FORMAT-R', 'desc' => 'Pagelaran seni tahunan menampilkan bakat anggota FORMAT-R.'],
                    ['no' => '02', 'nama' => 'Turnamen Olahraga',      'desc' => 'Kompetisi olahraga internal antar departemen dan angkatan.'],
                    ['no' => '03', 'nama' => 'Workshop Kreatif',       'desc' => 'Pelatihan keterampilan seni, musik, desain, dan fotografi.'],
                ],
                'divisi' => [],
            ],
            [
                'slug'      => 'pengembangan-organisasi-dan-sumber-daya-maunusia',
                'singkatan' => 'POSDM',
                'nama'      => 'Pengembangan Organisasi & SDM',
                'deskripsi' => 'Mengembangkan sumber daya manusia dan mengoptimalkan fungsi manajemen keanggotaan FORMAT-R UNESA.',
                'warna'     => 'navy',
                'tema_visual' => 'klasik',
                'theme'     => [
                    'mat-green' => '#212124', 'mat-green-line' => '#333338',
                    'purple' => '#9e464c', 'purple-dark' => '#702e33',
                    'blue' => '#4c6499', 'blue-dark' => '#32446e',
                ],
                'bg_pattern'=> 'linear-gradient(var(--mat-green-line) 2px, transparent 2px), linear-gradient(90deg, var(--mat-green-line) 2px, transparent 2px)',
                'ornaments' => [
                    ['pos' => 'top: 15%; right: 10%; transform:rotate(10deg);', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>'], // nodes network
                    ['pos' => 'bottom: 25%; right: 8%; transform:rotate(-15deg);', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>'], // users
                    ['pos' => 'top: 45%; left: 8%; transform:rotate(5deg);', 'svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>'], // activity pulse
                ],
                'icon'      => 'users',
                'anggota'   => [],
                'proker' => [
                    ['no' => '01', 'nama' => 'Diklat Kepemimpinan', 'desc' => 'Latihan dasar kepemimpinan untuk anggota baru FORMAT-R.'],
                    ['no' => '02', 'nama' => 'Upgrading Pengurus',  'desc' => 'Pelatihan peningkatan kapasitas khusus untuk badan pengurus.'],
                    ['no' => '03', 'nama' => 'Makrab Anggota',      'desc' => 'Malam keakraban untuk mempererat tali persaudaraan antar anggota.'],
                ],
                'divisi' => [],
            ],
        ];
    }

    /**
     * Halaman index: "Kolaborasi Asa" — grid semua departemen
     */
    public function index()
    {
        $staticData = collect($this->getAllDepartemen());
        $dbDeps = \App\Models\Department::all();
        
        $departemen = $dbDeps->map(function ($db) use ($staticData) {
            $static = $staticData->firstWhere('slug', $db->slug) ?? [];
            return array_merge($static, [
                'id' => $db->id,
                'slug' => $db->slug,
                'nama' => $db->name,
                'deskripsi' => $db->description,
                'image' => $db->image ? \Storage::url($db->image) : null,
                'singkatan' => $db->abbreviation ?: ($static['singkatan'] ?? strtoupper($db->slug)),
                'warna' => $static['warna'] ?? 'navy',
            ]);
        })->toArray();

        return view('departemen.index', compact('departemen'));
    }

    public function show($slug)
    {
        $db = \App\Models\Department::with('members')->where('slug', $slug)->firstOrFail();
        $static = collect($this->getAllDepartemen())->firstWhere('slug', $slug) ?? [];
        
        $activeCabinet = \App\Models\Cabinet::where('is_active', true)->first();
        $periodeActive = $activeCabinet ? $activeCabinet->period : '2026/2027';
        
        $dept = array_merge([
            'proker' => [],
            'divisi' => [],
            'anggota' => [],
            'singkatan' => strtoupper($db->slug),
            'periode' => $periodeActive,
        ], $static, [
            'nama' => $db->name,
            'deskripsi' => $db->description,
            'image' => $db->image ? \Storage::url($db->image) : null,
            'doc_image_1' => $db->doc_image_1 ? \Storage::url($db->doc_image_1) : null,
            'doc_image_2' => $db->doc_image_2 ? \Storage::url($db->doc_image_2) : null,
        ]);
        
        if (!empty($db->abbreviation)) {
            $dept['singkatan'] = $db->abbreviation;
        }

        if ($db->members->isNotEmpty()) {
            $dept['anggota'] = $db->members->map(function($m) {
                return [
                    'nama' => $m->name,
                    'jabatan' => $m->position,
                    'foto' => $m->photo ? \Storage::url($m->photo) : null,
                    'is_db' => true,
                ];
            })->toArray();
        } else {
            $dept['anggota'] = [];
        }

        return view('departemen.show', compact('dept'));
    }
}
