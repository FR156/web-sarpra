<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Item extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 
        'prefix',
        'category_id', 
        'image_path',
        'description'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function itemUnits(): HasMany
    {
        return $this->hasMany(ItemUnit::class);
    }

    // Fungsi tambahan buat cek stok agregat di Filament nanti
    public function getAvailableStockAttribute(): int
    {
        return $this->itemUnits()->where('status', 'available')->count();
    }

    protected static function booted()
    {
        static::saved(function ($item) {
            if (!$item->wasChanged('prefix')) {
                return;
            }

            $prefix = str($item->prefix)
                ->trim('-')
                ->upper()
                ->replaceMatches('/[^A-Z0-9-]/', '')
                ->replaceMatches('/-+/', '-')
                ->value();

            $item->itemUnits()
                ->withTrashed()
                ->get()
                ->each(fn ($itemUnit) => 
                    $itemUnit->update([
                        'unit_code' => "{$prefix}-{$itemUnit->sort_order}",
                        'updated_at' => now(),
                    ])
                );  
        });
    }
}