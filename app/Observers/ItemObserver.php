<?php

namespace App\Observers;

use App\Models\Item;
use App\Events\ActivityLogged;

class ItemObserver
{
    public function created(Item $item)
    {
        ActivityLogged::dispatch('created', "Menambah barang baru: {$item->name}", $item);
    }

    public function updated(Item $item)
    {
        // Kita bisa tau field apa yang diubah
        $changes = implode(', ', array_keys($item->getChanges()));
        ActivityLogged::dispatch('updated', "Mengubah data barang {$item->name} (Kolom: {$changes})", $item);
    }

    public function deleted(Item $item)
    {
        ActivityLogged::dispatch('deleted', "Menghapus barang: {$item->name}", $item);
    }
}