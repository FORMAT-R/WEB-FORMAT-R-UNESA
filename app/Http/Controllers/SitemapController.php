<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Department;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SitemapController extends Controller
{
    public function index()
    {
        $now = Carbon::now()->tz('Asia/Jakarta')->toAtomString();
        
        $urls = [
            ['loc' => url('/'), 'lastmod' => $now, 'priority' => '1.0'],
            ['loc' => url('/departemen'), 'lastmod' => $now, 'priority' => '0.8'],
            ['loc' => url('/berita'), 'lastmod' => $now, 'priority' => '0.8'],
            ['loc' => url('/event'), 'lastmod' => $now, 'priority' => '0.8'],
            ['loc' => url('/arsip'), 'lastmod' => $now, 'priority' => '0.6'],
        ];

        // Tambahkan halaman detail departemen
        $departments = Department::all();
        foreach ($departments as $dept) {
            $urls[] = [
                'loc' => url('/departemen/' . $dept->slug),
                'lastmod' => $dept->updated_at->tz('Asia/Jakarta')->toAtomString(),
                'priority' => '0.7'
            ];
        }

        // Tambahkan halaman berita
        $news = News::where('status', 'published')->get();
        foreach ($news as $item) {
            $urls[] = [
                'loc' => url('/berita/' . $item->slug),
                'lastmod' => $item->updated_at->tz('Asia/Jakarta')->toAtomString(),
                'priority' => '0.7'
            ];
        }

        // Tambahkan halaman event
        $events = Event::all();
        foreach ($events as $event) {
            $urls[] = [
                'loc' => url('/event/' . $event->slug),
                'lastmod' => $event->updated_at->tz('Asia/Jakarta')->toAtomString(),
                'priority' => '0.7'
            ];
        }

        return response()->view('sitemap', [
            'urls' => $urls
        ])->header('Content-Type', 'text/xml');
    }
}