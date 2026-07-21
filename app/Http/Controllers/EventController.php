<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    private function getSemuaEvent(): array
    {
        return [
            [
                'slug'          => 'diklat-kepemimpinan-2026',
                'status'        => 'coming_soon',
                'judul'         => 'Diklat Kepemimpinan FORMAT-R 2026',
                'tanggal'       => 'Sabtu, 15 Agustus 2026',
                'lokasi'        => 'Villa Kampus Ketintang, Surabaya',
                'penyelenggara' => 'Departemen POSDM',
                'peserta'       => '60 Fungsionaris',
                'status_daftar' => 'Ditutup',
                'deskripsi'     => 'Latihan dasar kepemimpinan dan manajemen organisasi untuk seluruh fungsionaris baru FORMAT-R UNESA.',
                'deskripsi_panjang' => 'Program Diklat Kepemimpinan merupakan salah satu program kerja unggulan POSDM yang dirancang untuk membentuk karakter pemimpin sejati di kalangan fungsionaris muda FORMAT-R. Kegiatan ini mencakup pelatihan manajemen diri, komunikasi efektif, pengambilan keputusan berbasis data, dan simulasi kepemimpinan langsung yang dipandu oleh mentor berpengalaman.',
                'image_bg'      => 'linear-gradient(135deg, #0B2545, #1D5DBF)',
                'panitia'       => [
                    ['inisial' => 'KP', 'nama' => 'Ketua Pelaksana',    'jabatan' => 'Pimpinan'],
                    ['inisial' => 'WK', 'nama' => 'Wakil Ketua',        'jabatan' => 'Co-Lead'],
                    ['inisial' => 'SK', 'nama' => 'Sie Acara',          'jabatan' => 'Seksi'],
                    ['inisial' => 'SP', 'nama' => 'Sie Perlengkapan',   'jabatan' => 'Seksi'],
                    ['inisial' => 'PD', 'nama' => 'Sie PDD',            'jabatan' => 'Seksi'],
                ],
                'rating'    => null,
                'reviews'   => 0,
            ],
            [
                'slug'          => 'bazar-kreatif-mahasiswa',
                'status'        => 'started',
                'judul'         => 'Bazar Kreatif Mahasiswa',
                'tanggal'       => '25 - 28 Juli 2026',
                'lokasi'        => 'Plaza UNESA, Gedung A',
                'penyelenggara' => 'Departemen Kewirausahaan',
                'peserta'       => '50+ Tenant',
                'status_daftar' => 'Sedang Berlangsung',
                'deskripsi'     => 'Pameran produk wirausaha mahasiswa dengan lebih dari 50 tenant, live music, dan talkshow bisnis.',
                'deskripsi_panjang' => 'Bazar Kreatif Mahasiswa adalah gelaran tahunan yang mempertemukan para wirausahawan muda mahasiswa UNESA dengan pembeli potensial. Tersedia ratusan produk mulai dari kuliner, fashion, kerajinan tangan, hingga layanan digital. Dilengkapi dengan panggung hiburan live music setiap malam dan talkshow inspiratif bersama pengusaha sukses.',
                'image_bg'      => 'linear-gradient(135deg, #1A7A4A, #2e592e)',
                'panitia'       => [
                    ['inisial' => 'KP', 'nama' => 'Nabila Ramadhani',  'jabatan' => 'Ketua Pelaksana'],
                    ['inisial' => 'WK', 'nama' => 'Putri Ayu Lestari', 'jabatan' => 'Wakil Ketua'],
                    ['inisial' => 'SD', 'nama' => 'Sie Desain',        'jabatan' => 'Seksi'],
                    ['inisial' => 'SP', 'nama' => 'Sie Promosi',       'jabatan' => 'Seksi'],
                    ['inisial' => 'SK', 'nama' => 'Sie Keamanan',      'jabatan' => 'Seksi'],
                ],
                'rating'    => null,
                'reviews'   => 0,
            ],
            [
                'slug'          => 'festival-seni-formatr',
                'status'        => 'finished',
                'judul'         => 'Festival Seni Budaya FORMAT-R',
                'tanggal'       => 'Sabtu, 10 Juni 2026',
                'lokasi'        => 'Gedung Serbaguna UNESA Lt. 5',
                'penyelenggara' => 'Departemen Minat Bakat',
                'peserta'       => '200+ Penonton',
                'status_daftar' => 'Selesai',
                'deskripsi'     => 'Malam puncak apresiasi seni mahasiswa menampilkan teater, tari tradisional, dan band akustik.',
                'deskripsi_panjang' => 'Festival Seni Budaya FORMAT-R adalah ajang puncak kreativitas mahasiswa yang menampilkan beragam karya seni dari seluruh departemen. Pada edisi tahun ini, lebih dari 120 mahasiswa tampil sebagai performer membawakan pertunjukan teater kontemporer, tari tradisional Jawa, hingga penampilan band akustik yang memukau lebih dari 200 penonton.',
                'image_bg'      => 'linear-gradient(135deg, #5c35a0, #341f57)',
                'dokumentasi'   => [
                    ['judul' => 'Pertunjukan Teater Pembuka',  'deskripsi' => 'Penampilan teatrikal yang menggugah.'],
                    ['judul' => 'Tari Tradisional Jawa',       'deskripsi' => 'Keindahan budaya nusantara berpadu modern.'],
                    ['judul' => 'Band Akustik Penutup',        'deskripsi' => 'Malam yang berakhir merdu dan penuh kenangan.'],
                    ['judul' => 'Sesi Foto Bersama',           'deskripsi' => 'Kebersamaan yang abadi dalam bingkai foto.'],
                    ['judul' => 'Pameran Karya Seni',          'deskripsi' => 'Ratusan karya terpajang memperindah venue.'],
                    ['judul' => 'Awarding & Penutupan',        'deskripsi' => 'Apresiasi terbaik untuk performer terbaik.'],
                ],
                'panitia'       => [
                    ['inisial' => 'KP', 'nama' => 'Aulia Rahma',    'jabatan' => 'Ketua Pelaksana'],
                    ['inisial' => 'WK', 'nama' => 'Hana Salsabila', 'jabatan' => 'Wakil Ketua'],
                    ['inisial' => 'SA', 'nama' => 'Sie Acara',      'jabatan' => 'Seksi'],
                    ['inisial' => 'SD', 'nama' => 'Sie Dekorasi',   'jabatan' => 'Seksi'],
                    ['inisial' => 'PD', 'nama' => 'Sie PDD',        'jabatan' => 'Seksi'],
                ],
                'rating'    => 4.8,
                'reviews'   => 124,
                'rating_dist' => [5 => 89, 4 => 22, 3 => 10, 2 => 2, 1 => 1],
            ],
            [
                'slug'          => 'seminar-nasional-teknologi',
                'status'        => 'finished',
                'judul'         => 'Seminar Nasional: AI & Masa Depan Pendidikan',
                'tanggal'       => 'Kamis, 15 Mei 2026',
                'lokasi'        => 'Auditorium Lt. 9, Gedung Pusat UNESA',
                'penyelenggara' => 'Departemen KOMINFO & PENLAR',
                'peserta'       => '300+ Peserta',
                'status_daftar' => 'Selesai',
                'deskripsi'     => 'Seminar nasional membahas implementasi Kecerdasan Buatan dalam dunia pendidikan tinggi.',
                'deskripsi_panjang' => 'Seminar Nasional ini menghadirkan tiga pembicara utama dari industri teknologi dan akademisi nasional untuk membahas bagaimana kecerdasan buatan (AI) akan membentuk ulang landscape pendidikan tinggi di Indonesia. Lebih dari 300 peserta dari berbagai perguruan tinggi hadir untuk berinteraksi langsung dengan para ahli di bidang ini.',
                'image_bg'      => 'linear-gradient(135deg, #1b2a47, #1f7d82)',
                'dokumentasi'   => [
                    ['judul' => 'Pembukaan & Sambutan Rektor',  'deskripsi' => 'Seremonial pembuka yang inspiratif.'],
                    ['judul' => 'Keynote Speaker: Dr. Ahmad',   'deskripsi' => 'Paparan visioner tentang AI di kampus.'],
                    ['judul' => 'Panel Diskusi Interaktif',     'deskripsi' => 'Sesi tanya-jawab yang antusias.'],
                    ['judul' => 'Workshop Mini: Prompt AI',     'deskripsi' => 'Praktik langsung dengan tools AI terkini.'],
                ],
                'panitia'       => [
                    ['inisial' => 'KP', 'nama' => 'Hana Salsabila', 'jabatan' => 'Ketua Pelaksana'],
                    ['inisial' => 'WK', 'nama' => 'Tri Wulandari',  'jabatan' => 'Wakil Ketua'],
                    ['inisial' => 'SA', 'nama' => 'Sie Acara',      'jabatan' => 'Seksi'],
                    ['inisial' => 'SK', 'nama' => 'Sie Konsumsi',   'jabatan' => 'Seksi'],
                ],
                'rating'    => 4.5,
                'reviews'   => 89,
                'rating_dist' => [5 => 55, 4 => 20, 3 => 10, 2 => 3, 1 => 1],
            ],
        ];
    }

    public function index()
    {
        $events = \App\Models\Event::latest('start_date')->get();
        return view('event.index', compact('events'));
    }

    public function show(string $slug)
    {
        $event = \App\Models\Event::where('slug', $slug)->firstOrFail();
        $lainnya = \App\Models\Event::where('slug', '!=', $slug)
            ->whereIn('status', ['upcoming', 'ongoing'])
            ->latest('start_date')
            ->take(3)
            ->get();

        return view('event.show', compact('event', 'lainnya'));
    }

    public function rate(Request $request, string $slug)
    {
        $event = \App\Models\Event::where('slug', $slug)->firstOrFail();

        // Check if event is finished
        if ($event->status !== 'completed') {
            return response()->json(['success' => false, 'message' => 'Hanya event yang sudah selesai yang dapat diberi rating.'], 403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $ip = $request->ip();

        \App\Models\EventRating::updateOrCreate(
            ['event_id' => $event->id, 'ip_address' => $ip],
            ['rating' => $request->rating]
        );

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih atas penilaian Anda!',
            'new_average' => round($event->average_rating, 1),
            'new_count' => $event->rating_count
        ]);
    }
}
