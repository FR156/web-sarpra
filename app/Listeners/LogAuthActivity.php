<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Request;

class LogAuthActivity
{
    public function handle(object $event): void
    {
        $ip = Request::ip();
        
        if ($event instanceof Failed) {
            ActivityLog::create([
                // 'user_id' => $event->user ? $event->user->id : null,
                'action' => 'login_failed',
                'description' => "Percobaan login gagal menggunakan email: " . ($event->credentials['email'] ?? 'Unknown'),
                'ip_address' => $ip,
            ]);
            return;
        }

        $user = $event->user;
        if (!$user) return;

        $role = ucfirst($user->role ?? 'User');
        $action = ($event instanceof Login) ? 'login' : 'logout';
        $desc = ($event instanceof Login) ? "berhasil masuk ke sistem" : "keluar dari sistem";

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'description' => "[{$role}] {$user->name} {$desc}.",
            'ip_address' => $ip,
        ]);
    }
}
