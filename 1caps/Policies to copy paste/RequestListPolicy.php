<?php

namespace App\Policies;

use App\Models\User;
use App\Models\RequestList;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequestListPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //return $user->can('view_any_request::list');
        return $user->hasRole(['super_admin','admin','staff','faculty']);    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RequestList $requestList): bool
    {
        //return $user->can('view_request::list');
        return $user->hasRole(['super_admin','admin','staff','faculty']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['super_admin','admin','staff','faculty']);    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RequestList $requestList): bool
    {
        return $user->hasRole(['super_admin','admin','staff','faculty']);    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RequestList $requestList): bool
    {
        //return $user->can('delete_request::list');
        return $user->hasRole(['super_admin','admin','staff','faculty']);    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        //return $user->can('delete_any_request::list');
        return $user->hasRole(['super_admin','admin','staff']);
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, RequestList $requestList): bool
    {
        //return $user->can('force_delete_request::list');
        return $user->hasRole(['super_admin','admin','staff']);
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        //return $user->can('force_delete_any_request::list');
        return $user->hasRole(['super_admin','admin','staff']);
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, RequestList $requestList): bool
    {
        return $user->hasRole(['super_admin','admin','staff','faculty']);    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->hasRole(['super_admin','admin','staff','faculty']);    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, RequestList $requestList): bool
    {
        return $user->hasRole(['super_admin','admin','staff','faculty']);    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->hasRole(['super_admin','admin','staff','faculty']);    }
}
