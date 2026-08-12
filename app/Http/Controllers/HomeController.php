<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Member;
use App\Models\Event;
use App\Models\News;
use App\Models\BestOfficer;
use App\Models\Birthday;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function struktur()
    {
        $pembina = \App\Models\Pembina::where('is_active', true)->first();
        
        $activeCabinet = get_active_cabinet();
        $members = collect();
        $departments = collect();

        if ($activeCabinet) {
            // Ambil semua member dari kabinet aktif
            $members = \App\Models\Member::with('department')->where('cabinet_id', $activeCabinet->id)->get();
            
            // Ambil semua departemen (selain BPH)
            $departments = \App\Models\Department::where('slug', '!=', 'bph')
                ->where('slug', '!=', 'badan-pengurus-harian')
                ->whereRaw('LOWER(name) != ?', ['bph'])
                ->get();
        }

        // Filter BPH (menggunakan slug 'badan-pengurus-harian' sesuai data db)
        $bphMembers = $members->filter(function($m) {
            $slug = strtolower($m->department->slug ?? '');
            $name = strtolower($m->department->name ?? '');
            return $slug === 'bph' || $name === 'bph' || strpos($slug, 'badan-pengurus-harian') !== false;
        });

        $ketum = $bphMembers->first(function($m) {
            return stripos(strtolower($m->position), 'ketua umum') !== false && stripos(strtolower($m->position), 'wakil') === false;
        });
        $waketum = $bphMembers->first(function($m) {
            return stripos(strtolower($m->position), 'wakil ketua umum') !== false;
        });
        
        $sekretaris = [
            'umum' => $bphMembers->firstWhere('position', 'Sekretaris Umum'),
            'satu' => $bphMembers->firstWhere('position', 'Sekretaris 1'),
            'dua' => $bphMembers->firstWhere('position', 'Sekretaris 2'),
        ];
        
        $bendahara = [
            'umum' => $bphMembers->firstWhere('position', 'Bendahara Umum'),
            'satu' => $bphMembers->firstWhere('position', 'Bendahara 1'),
            'dua' => $bphMembers->firstWhere('position', 'Bendahara 2'),
        ];

        return view('struktur', compact('pembina', 'ketum', 'waketum', 'sekretaris', 'bendahara', 'departments', 'members'));
    }

    public function index()
    {
        $stats = [
            ['value' => Department::count(),   'label' => 'Departemen Aktif', 'suffix' => ''],
            ['value' => Member::count(), 'label' => 'Fungsionaris Aktif', 'suffix' => ''],
            ['value' => 2000, 'label' => 'Anggota Format-R', 'suffix' => '+'],
            ['value' => 30,  'label' => 'Program Kerja', 'suffix' => '+'],
        ];

        $berita = News::with('author')->where('status', 'published')->latest()->paginate(3);

        $arsip = Event::where('status', 'completed')->latest('end_date')->take(4)->get();

        $faq = [
            [
                'pertanyaan' => 'Siapa saja yang boleh bergabung dengan FORMAT-R UNESA?',
                'jawaban'    => 'Seluruh mahasiswa aktif UNESA dari semua fakultas dan angkatan yang ingin berkembang bersama.',
                'open'       => true,
            ],
            [
                'pertanyaan' => 'Bagaimana cara mendaftar menjadi anggota?',
                'jawaban'    => 'Pendaftaran dibuka setiap awal semester melalui formulir online dan diumumkan lewat Instagram FORMAT-R.',
                'open'       => false,
            ],
            [
                'pertanyaan' => 'Apakah ada iuran keanggotaan?',
                'jawaban'    => 'Ada iuran uang kas setiap sebulan sekali dan ketentuannya disepakati oleh periode yang sedang berjalan.',
                'open'       => false,
            ],
            [
                'pertanyaan' => 'Boleh bergabung lebih dari satu departemen?',
                'jawaban'    => 'Anggota difokuskan pada satu departemen utama agar kontribusinya lebih maksimal, namun tetap boleh terlibat di kegiatan departemen lain.',
                'open'       => false,
            ],
        ];

        // Penghargaan
        $bestOfficers = BestOfficer::orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        $latestBestOfficers = collect();
        $historyBestOfficers = collect();

        if ($bestOfficers->count() > 0) {
            $first = $bestOfficers->first();
            $latestYear = $first->year;
            $latestMonth = $first->month;
            
            $latestGroup = $bestOfficers->where('year', $latestYear)->where('month', $latestMonth);
            $historyGroup = $bestOfficers->filter(function($item) use ($latestYear, $latestMonth) {
                return $item->year != $latestYear || $item->month != $latestMonth;
            });

            // Akan hilang dari homepage setelah 30 hari sejak diupdate
            if ($first->updated_at && $first->updated_at->diffInDays(Carbon::now()) <= 30) {
                $latestBestOfficers = $latestGroup->take(3);
                $historyBestOfficers = $historyGroup->take(3);
            } else {
                $historyBestOfficers = $bestOfficers->take(3);
            }
        }
        
        $penghargaan = [
            'bulan_ini' => $latestBestOfficers,
            'riwayat' => $historyBestOfficers,
        ];

        // Ulang Tahun — tampilkan 3 tanggal terbaru di bulan ini s/d hari ini
        $currentMonth = Carbon::now()->month;
        $currentDay   = Carbon::now()->day;

        // Ambil 3 tanggal TERBARU (terbesar) yang sudah lewat/hari ini di bulan berjalan
        $latest3Days = Birthday::whereMonth('birth_date', $currentMonth)
            ->whereDay('birth_date', '<=', $currentDay)
            ->selectRaw('DAY(birth_date) as birth_day')
            ->distinct()
            ->orderByRaw('DAY(birth_date) DESC')
            ->limit(3)
            ->pluck('birth_day');

        // Ambil SEMUA orang yang ultahnya jatuh di 3 tanggal tersebut, urut ASC
        $ultahBulanIni = Birthday::whereMonth('birth_date', $currentMonth)
            ->whereIn(DB::raw('DAY(birth_date)'), $latest3Days)
            ->orderByRaw('DAY(birth_date) ASC')
            ->get();

        // Variabel lama dipertahankan agar tidak ada breaking change di tempat lain
        $ultahData    = $ultahBulanIni;
        $ultahHariIni = $ultahBulanIni;

        $events = Event::whereIn('status', ['upcoming', 'ongoing'])->orderBy('start_date', 'asc')->take(3)->get();

        $pembina = \App\Models\Pembina::where('is_active', true)->first();

        return view('home.index', compact(
            'stats', 'berita', 'arsip', 'faq', 'penghargaan', 'ultahData', 'ultahHariIni', 'ultahBulanIni', 'events', 'pembina'
        ));
    }

    public function kirimPesan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'pesan' => 'required|string',
        ]);

        $emailTujuan = get_setting('contactEmail', 'formatr@unesa.ac.id');

        try {
            Mail::raw("Nama: {$request->nama}\nEmail: {$request->email}\n\nPesan:\n{$request->pesan}", function ($message) use ($emailTujuan, $request) {
                $message->to($emailTujuan)
                        ->subject('Pesan Baru dari Website FORMAT-R UNESA')
                        ->replyTo($request->email, $request->nama);
            });

            return response()->json(['success' => true, 'message' => 'Pesan berhasil dikirim! Kami akan segera merespons.']);
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email kontak: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengirim pesan. Silakan coba beberapa saat lagi.'], 500);
        }
    }

    public function arsip(Request $request)
    {
        $kategori = $request->query('kategori', 'semua');
        
        $query = Event::where('status', 'completed');

        if ($kategori === 'minggu_lalu') {
            $query->whereBetween('end_date', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
        } elseif ($kategori === 'bulan_lalu') {
            $query->whereMonth('end_date', Carbon::now()->subMonth()->month)
                  ->whereYear('end_date', Carbon::now()->subMonth()->year);
        } elseif ($kategori === 'tahun_sebelumnya') {
            $query->whereYear('end_date', '<', Carbon::now()->year);
        }

        $arsip = $query->latest('end_date')->get();
        return view('arsip', compact('arsip', 'kategori'));
    }

    public function apiBeritaPaginate(Request $request)
    {
        $berita = News::with('author')
            ->where('status', 'published')
            ->latest()
            ->paginate(3);

        $html = '';
        foreach ($berita as $b) {
            $date = $b->published_at ? \Carbon\Carbon::parse($b->published_at)->translatedFormat('d M Y') : $b->created_at->translatedFormat('d M Y');
            $imageHtml = '';
            
            if ($b->image) {
                $imageUrl = \Storage::url($b->image);
                $imageHtml = '<div class="art-thumb" style="height:200px; background-image:url('.$imageUrl.'); background-size:cover; background-position:center;"></div>';
            } else {
                $imageHtml = '<div class="art-thumb" style="height:200px; background-color: var(--surface-alt); display: flex; align-items: center; justify-content: center;">
                                <svg style="width: 48px; height: 48px; color: var(--ink-soft); opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                              </div>';
            }

            $excerpt = \Str::limit(strip_tags($b->content), 120);
            $url = route('berita.show', $b->slug);

            $html .= '
            <a href="'.$url.'" style="text-decoration:none; color:inherit; display:block; height:100%; opacity:0; transform:translateY(20px); transition:all 0.5s ease;" class="ajax-art-card">
                <article class="art-card" style="display:flex; flex-direction:column; height:100%;">
                    '.$imageHtml.'
                    <div class="art-body" style="padding:20px; flex:1; display:flex; flex-direction:column;">
                        <div style="font-size: 0.8rem; color: var(--ink-soft); margin-bottom: 8px; font-weight: 500; letter-spacing: 0.5px;">
                            '.$date.'
                        </div>
                        <h4 style="margin:0 0 10px 0; font-size:1.15rem; line-height: 1.4;">'.$b->title.'</h4>
                        <p style="font-size: 0.9rem; color: var(--ink-soft); margin-bottom: 0; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; flex: 1;">
                            '.$excerpt.'
                        </p>
                        <div style="margin-top: 16px; font-size: 0.85rem; font-weight: 600; color: var(--blue); display: flex; align-items: center; gap: 4px;">
                            Baca Selengkapnya
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                        </div>
                    </div>
                </article>
            </a>';
        }

        return response()->json([
            'html' => $html,
            'has_more' => $berita->hasMorePages(),
            'current_page' => $berita->currentPage(),
            'last_page' => $berita->lastPage(),
        ]);
    }
}