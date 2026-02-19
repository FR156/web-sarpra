<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanItem extends Model
{
    protected $fillable = [
        'loan_id',
        'item_id',
        'quantity',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function loanItemUnits()
    {
        return $this->hasMany(LoanItemUnit::class);
    }
}