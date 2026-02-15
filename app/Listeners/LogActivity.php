<?php

namespace App\Listeners;

use App\Events\ActivityLogged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;
use Illuminate\Auth\Events\Login;
use SebastianBergmann\CodeCoverage\Test\TestSize\Unknown;

class LogActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ActivityLogged $event): void
    {
        $user = auth()->user();
        $roleName = ucfirst($user->role); 
        $detailedDescription = "[{$roleName}] {$user->name}: {$event->description}";
        
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => $event->action,
            'model_type' => $event->model ? get_class($event->model) : null,
            'model_id' => $event->model ? $event->model->id : null,
            'description' => $detailedDescription,
            'ip_address' => request()->ip(),
        ]);
    }
}

