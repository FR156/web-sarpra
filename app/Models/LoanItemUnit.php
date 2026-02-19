<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanItemUnit extends Model
{
    protected $fillable = [
        'loan_item_id',
        'item_unit_id',
    ];

    public function loanItem()
    {
        return $this->belongsTo(LoanItem::class);
    }

    public function itemUnit()
    {
        return $this->belongsTo(ItemUnit::class);
    }
}