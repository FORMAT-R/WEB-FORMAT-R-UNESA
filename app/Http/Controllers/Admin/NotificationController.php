<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventNotificationMail;

class NotificationController extends Controller
{
    public function send()
    {
        $notifications = collect();
        $now = \Carbon\Carbon::now();

        // Upcoming events that should be ongoing or completed
        $overdueEvents = Event::where('status', 'upcoming')
            ->where('start_date', '<=', $now)
            ->get();
        foreach($overdueEvents as $event) {
            $notifications->push([
                'title' => 'Ubah Status: ' . $event->title,
                'desc' => 'Event sudah dimulai, harap ubah status.',
                'time' => $event->start_date->diffForHumans(),
            ]);
        }

        // Ongoing events that should be completed
        $endedEvents = Event::where('status', 'ongoing')
            ->where('end_date', '<=', $now)
            ->get();
        foreach($endedEvents as $event) {
            $notifications->push([
                'title' => 'Selesaikan: ' . $event->title,
                'desc' => 'Event sudah berakhir, harap ubah status menjadi Selesai.',
                'time' => $event->end_date->diffForHumans(),
            ]);
        }

        // Upcoming events in next 7 days
        $upcomingEvents = Event::where('status', 'upcoming')
            ->where('start_date', '>', $now)
            ->where('start_date', '<=', $now->copy()->addDays(7))
            ->get();
        foreach($upcomingEvents as $event) {
            $notifications->push([
                'title' => 'Akan Datang: ' . $event->title,
                'desc' => 'Event dimulai dalam ' . $event->start_date->diffForHumans(null, true) . '.',
                'time' => $event->start_date->diffForHumans(),
            ]);
        }

        if ($notifications->isEmpty()) {
            return redirect()->back()->with('success', 'Tidak ada event yang perlu diingatkan saat ini.');
        }

        $users = User::all();
        foreach ($users as $user) {
            Mail::to($user->email)->send(new EventNotificationMail($user, $notifications));
        }

        return redirect()->back()->with('success', 'Notifikasi berhasil dikirimkan ke seluruh alamat email pengguna!');
    }
}
