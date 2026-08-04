<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('is-superadmin', function (User $user) {
            return $user->role === 'superadmin';
        });

        \Illuminate\Auth\Notifications\ResetPassword::createUrlUsing(function (User $user, string $token) {
            return route('admin.password.reset', ['token' => $token, 'email' => $user->email]);
        });

        \Illuminate\Support\Facades\View::composer('admin.layouts.app', function ($view) {
            $notifications = collect();
            $now = \Carbon\Carbon::now();

            // Upcoming events that should be ongoing or completed
            $overdueEvents = \App\Models\Event::where('status', 'upcoming')
                ->where('start_date', '<=', $now)
                ->get();
            foreach($overdueEvents as $event) {
                $notifications->push([
                    'title' => 'Ubah Status: ' . $event->title,
                    'desc' => 'Event sudah dimulai, harap ubah status.',
                    'time' => $event->start_date->diffForHumans(),
                    'link' => route('admin.events.edit', $event->id)
                ]);
            }

            // Ongoing events that should be completed
            $endedEvents = \App\Models\Event::where('status', 'ongoing')
                ->where('end_date', '<=', $now)
                ->get();
            foreach($endedEvents as $event) {
                $notifications->push([
                    'title' => 'Selesaikan: ' . $event->title,
                    'desc' => 'Event sudah berakhir, harap ubah status menjadi Selesai.',
                    'time' => $event->end_date->diffForHumans(),
                    'link' => route('admin.events.edit', $event->id)
                ]);
            }

            // Upcoming events in next 7 days
            $upcomingEvents = \App\Models\Event::where('status', 'upcoming')
                ->where('start_date', '>', $now)
                ->where('start_date', '<=', $now->copy()->addDays(7))
                ->get();
            foreach($upcomingEvents as $event) {
                $notifications->push([
                    'title' => 'Akan Datang: ' . $event->title,
                    'desc' => 'Event dimulai dalam ' . $event->start_date->diffForHumans(null, true) . '.',
                    'time' => $event->start_date->diffForHumans(),
                    'link' => route('admin.events.edit', $event->id)
                ]);
            }

            $view->with('headerNotifications', $notifications);
        });
    }
}
