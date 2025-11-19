<?php

namespace App\Policies;

use App\Models\Taller;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TallerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver talleres');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Taller $taller): bool
    {
        return $user->can('ver talleres');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear talleres');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Taller $taller): bool
    {
        return $user->can('editar talleres');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Taller $taller): bool
    {
        return $user->can('eliminar talleres');
    }

    /**
     * Restore disabled.
     */
    public function restore(User $user, Taller $taller): bool
    {
        return false;
    }

    /**
     * Force delete disabled.
     */
    public function forceDelete(User $user, Taller $taller): bool
    {
        return false;
    }
}
