<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Member;
use App\Models\Event;
use App\Models\News;
use App\Models\BestOfficer;
use App\Models\Birthday;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            ['value' => Department::count(),   'label' => 'Departemen Aktif', 'suffix' => ''],
            ['value' => Member::count(), 'label' => 'Anggota Terdaftar', 'suffix' => '+'],
            ['value' => 30,  'label' => 'Program Kerja', 'suffix' => '+'],
        ];

        $berita = News::with('author')->where('status', 'published')->latest()->take(3)->get();

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
                'jawaban'    => 'Tidak ada iuran wajib. Pendanaan kegiatan berasal dari kas organisasi dan dana kemahasiswaan.',
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

        // Ulang Tahun
        $currentMonth = Carbon::now()->month;
        $ultahData = Birthday::whereMonth('birth_date', $currentMonth)
            ->orderByRaw('DAY(birth_date) ASC')
            ->get();
            
        $todayStr = Carbon::now()->format('m-d');
        // Hanya menampilkan yang benar-benar berulang tahun HARI INI
        $ultahHariIni = Birthday::whereRaw("DATE_FORMAT(birth_date, '%m-%d') = ?", [$todayStr])
            ->get();

        $events = Event::whereIn('status', ['upcoming', 'ongoing'])->orderBy('start_date', 'asc')->take(3)->get();

        return view('home.index', compact(
            'stats', 'berita', 'arsip', 'faq', 'penghargaan', 'ultahData', 'ultahHariIni', 'events'
        ));
    }

    public function arsip()
    {
        $arsip = Event::where('status', 'completed')->latest('end_date')->get();
        return view('arsip', compact('arsip'));
    }
}