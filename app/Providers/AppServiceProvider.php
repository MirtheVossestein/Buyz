<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Message;

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
    public function boot()
{
    View::composer('*', function ($view) {
        if (auth()->check()) {
            $unreadCount = Message::where('receiver_id', auth()->id())
                ->where('is_read', false)
                ->count();

            $view->with('unreadCount', $unreadCount);
        }
    });
}


    protected $policies = [
        \App\Models\Ad::class => \App\Policies\AdPolicy::class,
    ];

    
}
