<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\News;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with('author')->where('status', 'published');

        if ($request->has('filter')) {
            $filter = $request->filter;
            $now = \Carbon\Carbon::now();
            
            if ($filter == 'baru') {
                $query->where('published_at', '>=', $now->subDays(3));
            } elseif ($filter == 'minggu_lalu') {
                $query->whereBetween('published_at', [
                    $now->copy()->subWeeks(1)->startOfWeek(), 
                    $now->copy()->subWeeks(1)->endOfWeek()
                ]);
            } elseif ($filter == 'bulan_lalu') {
                $query->whereMonth('published_at', $now->copy()->subMonth()->month)
                      ->whereYear('published_at', $now->copy()->subMonth()->year);
            } elseif ($filter == 'tahun_lalu') {
                $query->whereYear('published_at', $now->copy()->subYear()->year);
            }
        }

        $semuaBerita = $query->latest('published_at')->latest('id')->paginate(9);

        // Jika filter digunakan, pertahankan query string pada pagination
        if ($request->has('filter')) {
            $semuaBerita->appends(['filter' => $request->filter]);
        }

        return view('berita.index', compact('semuaBerita'));
    }

    public function show($slug)
    {
        $berita = News::with('author')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Ambil berita terbaru untuk ditampilkan di sidebar
        $latestBerita = News::where('slug', '!=', $slug)
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        // Ambil berita utama pekan ini (7 hari terakhir)
        $weeklyBerita = News::where('slug', '!=', $slug)
            ->where('status', 'published')
            ->where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->take(3)
            ->get();

        // Ambil berita untuk preview sebelah kiri (Baca Juga) - ambil 2
        $nextBerita = News::where('slug', '!=', $slug)
            ->where('status', 'published')
            ->inRandomOrder()
            ->take(2)
            ->get();

        // Ambil berita untuk bagian bawah sidebar kanan
        $rightFeatured = News::where('slug', '!=', $slug)
            ->where('status', 'published')
            ->whereNotIn('id', $nextBerita->pluck('id'))
            ->inRandomOrder()
            ->first();

        return view('berita.show', compact('berita', 'latestBerita', 'weeklyBerita', 'nextBerita', 'rightFeatured'));
    }
}