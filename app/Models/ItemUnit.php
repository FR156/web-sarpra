<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemUnit extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'item_id', 
        'unit_code', 
        'sort_order',
        'condition', 
        'status'
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function loanItems()
    {
        return $this->hasMany(LoanItem::class);
    }

    public function loans()
    {
        return $this->belongsToMany(
            Loan::class,
            'loan_items'
        );
    }

    public function activeLoan()
    {
        return $this->loanItems()
            ->whereHas('loan', function ($q) {
                $q->whereIn('status', ['approved', 'on_going']);
            });
    }

     protected static function booted()
    {
        static::saving(function ($unit) {
            if (!$unit->isDirty('item_id') && $unit->exists) {
                return;
            }

            $lastOrder = static::withTrashed()
                ->where('item_id', $unit->item_id)
                ->max('sort_order') ?? 0;

            $prefix = str($unit->item->prefix)->trim('-')->upper();
            
            $unit->sort_order = $lastOrder + 1;
            $unit->unit_code = $prefix . '-' . $unit->sort_order;
        });
    }
}