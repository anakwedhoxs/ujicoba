<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Pic;
use Illuminate\Auth\Access\HandlesAuthorization;

class PicPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true; // semua user bisa melihat daftar PIC
    }

    public function view(User $user, Pic $pic): bool
    {
        return true; // semua user bisa melihat detail PIC
    }

    public function create(User $user): bool
    {
        return $user->hasRole('super_admin'); // hanya super_admin bisa tambah
    }

    public function update(User $user, Pic $pic): bool
    {
        return $user->hasRole('super_admin'); // hanya super_admin bisa edit
    }

    public function delete(User $user, Pic $pic): bool
    {
        return $user->hasRole('super_admin'); // hanya super_admin bisa hapus
    }

    public function restore(User $user, Pic $pic): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, Pic $pic): bool
    {
        return $user->hasRole('super_admin');
    }
}
