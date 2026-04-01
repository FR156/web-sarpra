<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'loan_code',
        'reason',
        'item_id',
        'start_date',
        'due_date',
        'returned_at',
        'fine_amount',
        'fine_reason',
        'fine_status',
        'status',
        'approver_id',
    ];

    protected $casts = [
        'start' => 'datetime',
        'due_date' => 'datetime',
        'returned_at' => 'datetime',
    ];

    // public function getDisplayStatusAttribute()
    // {
    //     if (
    //         $this->status === 'on_going' &&
    //         now()->gt($this->end_date) &&
    //         !$this->returned_at
    //     ) {
    //         return 'overdue';
    //     }

    //     return $this->status;
    // }
    
    // public function item()
    // {
    //     return $this->belongsTo(Item::class);
    // }

    // public function itemUnits()
    // {
    //     return $this->belongsToMany(ItemUnit::class, 'loan_items');
    // }

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

    public function assignedUnits()
    {
        return $this->hasManyThrough(
            LoanItemUnit::class,
            LoanItem::class
        );
    }


    public function isOverdue()
    {
        return $this->status === 'approved' && now()->gt($this->due_date);
    }

    protected static function booted(): void
    {
        static::creating(function ($loan) {
            DB::transaction(function () use ($loan) {

                $year = now()->year;

                $lastSequence = self::where('year', $year)
                    ->lockForUpdate()
                    ->max('sequence_number') ?? 0;

                $nextSequence = $lastSequence + 1;

                $loan->year = $year;
                $loan->sequence_number = $nextSequence;
                $loan->loan_code = "LOAN-{$year}-" . ($nextSequence === 1 ? '1' : str_pad($nextSequence, 0, '0', STR_PAD_LEFT));
            });
        });

        static::deleting(function (Loan $loan) {
            // Eager load the relationships to avoid N+1 queries
            $loan->load('loanItems.loanItemUnits.itemUnit');
            
            foreach ($loan->loanItems as $loanItem) {
                foreach ($loanItem->loanItemUnits as $loanItemUnit) {
                    if ($loanItemUnit->itemUnit) {
                        $loanItemUnit->itemUnit->update(['status' => 'available']);
                    }
                }
            }
        });
    }
}