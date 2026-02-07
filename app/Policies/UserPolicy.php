<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Tentukan ID Admin Utama yang tidak boleh diganggu gugat.
     */
    protected int $superAdminId = 1;

    /**
     * Siapa yang bisa lihat menu User Management?
     */
    public function viewAny(User $user): bool
    {
        // Cuma Admin (yang aktif) yang bisa lihat daftar user
        return $user->role === 'admin' && $user->is_active;
    }

    /**
     * Siapa yang bisa lihat detail profil user tertentu?
     */
    public function view(User $user, User $model): bool
    {
        // Admin bisa lihat siapa saja, Staff/Borrower cuma bisa lihat diri sendiri
        return $user->role === 'admin' || $user->id === $model->id;
    }

    /**
     * Siapa yang bisa tambah user baru?
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin' && $user->is_active;
    }

    /**
     * Siapa yang bisa edit user?
     */
    public function update(User $user, User $model): bool
    {
        // 1. Jika yang diedit adalah Admin Utama, hanya Admin Utama itu sendiri yang bisa edit
        if ($model->id === $this->superAdminId) {
            return $user->id === $this->superAdminId;
        }

        // 2. Admin bisa edit siapa saja (selain admin utama)
        // 3. Staff/Borrower harus bisa update dirinya sendiri (PENTING buat login/session)
        return $user->role === 'admin' || $user->id === $model->id;
    }

    /**
     * Siapa yang bisa hapus (atau nonaktifkan) user?
     */
    public function delete(User $user, User $model): bool
    {
        // 1. Admin Utama (ID 1) TIDAK BOLEH dihapus oleh siapapun
        if ($model->id === $this->superAdminId) {
            return false;
        }

        // 2. User tidak boleh menghapus dirinya sendiri
        if ($user->id === $model->id) {
            return false;
        }

        // 3. Hanya Admin yang bisa hapus
        return $user->role === 'admin';
    }
}