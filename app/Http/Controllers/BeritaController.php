<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\News;

class BeritaController extends Controller
{
    public function index()
    {
        $semuaBerita = News::with('author')
            ->where('status', 'published')
            ->latest()
            ->paginate(9);

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