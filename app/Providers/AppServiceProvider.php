<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use App\Events\ActivityLogged;
use App\Listeners\LogActivity;
use App\Listeners\LogAuthActivity;
use App\Observers\ItemObserver;
use App\Observers\ItemUnitObserver;
use App\Observers\CategoryObserver;
use App\Models\Item;
use App\Models\ItemUnit;
use App\Models\Category;
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
        static $observerRegistered = false;
        if (!$observerRegistered) {
            Item::observe(ItemObserver::class);
            ItemUnit::observe(ItemUnitObserver::class);
            Category::observe(CategoryObserver::class);
            $observerRegistered = true;
        }
    }
}
