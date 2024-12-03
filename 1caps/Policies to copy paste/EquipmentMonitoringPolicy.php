<?php

namespace App\Policies;

use App\Models\EquipmentMonitoring;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EquipmentMonitoringPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super_admin','admin','staff']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EquipmentMonitoring $equipmentMonitoring): bool
    {
        return $user->hasRole(['super_admin','admin','staff']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['super_admin','admin','staff']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EquipmentMonitoring $equipmentMonitoring): bool
    {
       // return $user->hasRole(['super_admin','admin','staff']);
       return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EquipmentMonitoring $equipmentMonitoring): bool
    {
        return $user->hasRole(['super_admin','admin','staff']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EquipmentMonitoring $equipmentMonitoring): bool
    {
        return $user->hasRole(['super_admin','admin','staff']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EquipmentMonitoring $equipmentMonitoring): bool
    {
        return $user->hasRole(['super_admin','admin','staff']);
    }
}
