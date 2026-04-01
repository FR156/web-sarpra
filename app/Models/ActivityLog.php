<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 
        'action', 
        'model_type', 
        'model_id', 
        'description', 
        'ip_address'
    ];

    public static function log($action, $description, $model = null)
    {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'description' => $description,
            'ip_address' => Request::ip(),
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}