<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemUnit extends Model
{
    protected $fillable = [
        'item_id', 
        'unit_code', 
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
}