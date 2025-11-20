<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PoinActivity;
use Illuminate\Auth\Access\HandlesAuthorization;

class PoinActivityPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PoinActivity');
    }

    public function view(AuthUser $authUser, PoinActivity $poinActivity): bool
    {
        return $authUser->can('View:PoinActivity');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PoinActivity');
    }

    public function update(AuthUser $authUser, PoinActivity $poinActivity): bool
    {
        return $authUser->can('Update:PoinActivity');
    }

    public function delete(AuthUser $authUser, PoinActivity $poinActivity): bool
    {
        return $authUser->can('Delete:PoinActivity');
    }

    public function restore(AuthUser $authUser, PoinActivity $poinActivity): bool
    {
        return $authUser->can('Restore:PoinActivity');
    }

    public function forceDelete(AuthUser $authUser, PoinActivity $poinActivity): bool
    {
        return $authUser->can('ForceDelete:PoinActivity');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PoinActivity');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PoinActivity');
    }

    public function replicate(AuthUser $authUser, PoinActivity $poinActivity): bool
    {
        return $authUser->can('Replicate:PoinActivity');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PoinActivity');
    }

}