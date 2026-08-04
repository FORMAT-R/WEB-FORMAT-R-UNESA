<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventNotificationMail;
use Carbon\Carbon;

class SendEventNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automatic event notifications and reminders to all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $notifications = collect();
        $now = Carbon::now();

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
            $this->info('No event notifications to send.');
            return;
        }

        $users = User::all();
        $count = 0;
        foreach ($users as $user) {
            Mail::to($user->email)->send(new EventNotificationMail($user, $notifications));
            $count++;
        }

        $this->info("Successfully sent event notifications to $count users.");
    }
}
