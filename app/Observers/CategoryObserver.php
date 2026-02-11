<?php

namespace App\Observers;

use App\Models\Category;
use App\Events\ActivityLogged;

class CategoryObserver
{
    public function created(Category $category)
    {
        $time = now()->format('H:i:s.u');
        ActivityLogged::dispatch('created', "[{$time}] Menambah kategori baru: {$category->name}", $category);
    }

    public function updated(Category $category)
    {
        // Kita bisa tau field apa yang diubah
        $changes = implode(', ', array_keys($category->getChanges()));
        ActivityLogged::dispatch('updated', "Mengubah data kategori {$category->name} (Kolom: {$changes})", $category);
    }

    public function deleted(Category $category)
    {
        $time = now()->format('H:i:s.u');
        ActivityLogged::dispatch('deleted', "[{$time}] Menghapus kategori: {$category->name}", $category);
    }
}