<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\News;
use App\Models\Member;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_events' => Event::count(),
            'total_berita' => News::count(),
            'total_anggota' => Member::count(),
        ];

        $upcomingEvents = Event::where('status', 'upcoming')
            ->orderBy('start_date', 'asc')
            ->take(4)
            ->get();

        // Prepare line chart data (e.g., events per month this year)
        $months = [];
        $eventCounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create()->month($i)->translatedFormat('M');
            $eventCounts[] = Event::whereYear('start_date', date('Y'))
                ->whereMonth('start_date', $i)
                ->count();
        }

        // Recent activity can be latest news and events combined
        $recentNews = News::latest()->take(3)->get()->map(function($item) {
            return [
                'action' => 'Menambahkan berita',
                'description' => $item->title,
                'time' => $item->created_at->diffForHumans()
            ];
        });
        $recentEvents = Event::latest()->take(3)->get()->map(function($item) {
            return [
                'action' => 'Membuat event',
                'description' => $item->title,
                'time' => $item->created_at->diffForHumans()
            ];
        });
        $recentActivity = $recentNews->concat($recentEvents)->sortByDesc('time')->take(5)->values();

        return view('admin.dashboard.index', compact('stats', 'upcomingEvents', 'recentActivity', 'months', 'eventCounts'));
    }
}
