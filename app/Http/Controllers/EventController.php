<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{

    public function index()
    {
        $events = \App\Models\Event::latest('start_date')->get();
        return view('event.index', compact('events'));
    }

    public function show(string $slug)
    {
        $event = \App\Models\Event::where('slug', $slug)->firstOrFail();
        $lainnya = \App\Models\Event::where('slug', '!=', $slug)
            ->latest('start_date')
            ->take(4)
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
