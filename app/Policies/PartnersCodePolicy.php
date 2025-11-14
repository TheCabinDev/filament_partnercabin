<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PartnersCode;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartnersCodePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PartnersCode');
    }

    public function view(AuthUser $authUser, PartnersCode $partnersCode): bool
    {
        return $authUser->can('View:PartnersCode');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PartnersCode');
    }

    public function update(AuthUser $authUser, PartnersCode $partnersCode): bool
    {
        return $authUser->can('Update:PartnersCode');
    }

    public function delete(AuthUser $authUser, PartnersCode $partnersCode): bool
    {
        return $authUser->can('Delete:PartnersCode');
    }

    public function restore(AuthUser $authUser, PartnersCode $partnersCode): bool
    {
        return $authUser->can('Restore:PartnersCode');
    }

    public function forceDelete(AuthUser $authUser, PartnersCode $partnersCode): bool
    {
        return $authUser->can('ForceDelete:PartnersCode');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PartnersCode');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PartnersCode');
    }

    public function replicate(AuthUser $authUser, PartnersCode $partnersCode): bool
    {
        return $authUser->can('Replicate:PartnersCode');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PartnersCode');
    }

}