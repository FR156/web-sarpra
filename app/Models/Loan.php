<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
        'start_date',
        'due_date',
        'returned_at',
        'status',
        'approver_id',
    ];

    protected $casts = [
        'start' => 'datetime',
        'due_date' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function getDisplayStatusAttribute()
    {
        if (
            $this->status === 'on_going' &&
            now()->gt($this->end_date) &&
            !$this->returned_at
        ) {
            return 'overdue';
        }

        return $this->status;
    }
    
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function loanItems()
    {
        return $this->hasMany(LoanItem::class);
    }

    public function itemUnits()
    {
        return $this->belongsToMany(ItemUnit::class, 'loan_items');
    }

    public function isOverdue()
    {
        return $this->status === 'approved' && now()->gt($this->due_date);
    }

    protected static function booted(): void
    {
        static::deleting(function (Loan $loan) {
            $loan->itemUnits()->update(['status' => 'available']);
        });
    }
}