<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use App\Events\ActivityLogged;
use App\Listeners\LogActivity;
use App\Listeners\LogAuthActivity;
use Illuminate\Support\Facades\Event;


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
        // Auth
        Event::listen(Login::class, LogAuthActivity::class);
        Event::listen(Logout::class, LogAuthActivity::class);
        Event::listen(Failed::class, LogAuthActivity::class);

        // Activity
        Event::listen(ActivityLogged::class, LogActivity::class);

        // Observer
        \App\Models\Item::observe(\App\Observers\ItemObserver::class);
        \App\Models\ItemUnit::observe(\App\Observers\ItemUnitObserver::class);
        \App\Models\Category::observe(\App\Observers\CategoryObserver::class);
    }
}
