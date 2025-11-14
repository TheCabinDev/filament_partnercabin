<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Partners;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartnersPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Partners');
    }

    public function view(AuthUser $authUser, Partners $partners): bool
    {
        return $authUser->can('View:Partners');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Partners');
    }

    public function update(AuthUser $authUser, Partners $partners): bool
    {
        return $authUser->can('Update:Partners');
    }

    public function delete(AuthUser $authUser, Partners $partners): bool
    {
        return $authUser->can('Delete:Partners');
    }

    public function restore(AuthUser $authUser, Partners $partners): bool
    {
        return $authUser->can('Restore:Partners');
    }

    public function forceDelete(AuthUser $authUser, Partners $partners): bool
    {
        return $authUser->can('ForceDelete:Partners');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Partners');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Partners');
    }

    public function replicate(AuthUser $authUser, Partners $partners): bool
    {
        return $authUser->can('Replicate:Partners');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Partners');
    }

}