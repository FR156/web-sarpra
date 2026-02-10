<?php

namespace App\Listeners;

use App\Events\ActivityLogged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

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
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $event->action,
            'model_type' => $event->model ? get_class($event->model) : null,
            'model_id' => $event->model ? $event->model->id : null,
            'description' => $event->description,
            'ip_address' => Request::ip(),
        ]);
    }
}
