<?php

namespace App\Observers;

use App\Models\ItemUnit;
use App\Events\ActivityLogged;

class ItemUnitObserver
{
    public function created(ItemUnit $itemUnit)
    {
        ActivityLogged::dispatch('added', "Menambah unit baru: {$itemUnit->unit_code} dari barang {$itemUnit->item->name}", $itemUnit);
    }

    public function updated(ItemUnit $itemUnit)
    {
        // Kita bisa tau field apa yang diubah
        $changes = implode(', ', array_keys($itemUnit->getChanges()));
        ActivityLogged::dispatch('updated', "Mengubah data unit {$itemUnit->unit_code} (Kolom: {$changes})", $itemUnit);
    }

    public function deleted(ItemUnit $itemUnit)
    {
        ActivityLogged::dispatch('deleted', "Menghapus unit: {$itemUnit->unit_code} dari barang {$itemUnit->item->name}", $itemUnit);
    }
}