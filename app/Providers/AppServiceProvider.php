<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.header', function ($view) {
            $notifications = collect();
            $nonLues = 0;

            if (Auth::check()) {
                $user = Auth::user();
                $notifications = Notification::where('notifiable_type', get_class($user))
                    ->where('notifiable_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get();

                $nonLues = Notification::where('notifiable_type', get_class($user))
                    ->where('notifiable_id', $user->id)
                    ->whereNull('lu_le')
                    ->count();
            }

            $view->with(compact('notifications', 'nonLues'));
        });
    }
}
