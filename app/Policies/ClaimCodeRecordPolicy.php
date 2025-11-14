<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ClaimCodeRecord;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClaimCodeRecordPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ClaimCodeRecord');
    }

    public function view(AuthUser $authUser, ClaimCodeRecord $claimCodeRecord): bool
    {
        return $authUser->can('View:ClaimCodeRecord');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ClaimCodeRecord');
    }

    public function update(AuthUser $authUser, ClaimCodeRecord $claimCodeRecord): bool
    {
        return $authUser->can('Update:ClaimCodeRecord');
    }

    public function delete(AuthUser $authUser, ClaimCodeRecord $claimCodeRecord): bool
    {
        return $authUser->can('Delete:ClaimCodeRecord');
    }

    public function restore(AuthUser $authUser, ClaimCodeRecord $claimCodeRecord): bool
    {
        return $authUser->can('Restore:ClaimCodeRecord');
    }

    public function forceDelete(AuthUser $authUser, ClaimCodeRecord $claimCodeRecord): bool
    {
        return $authUser->can('ForceDelete:ClaimCodeRecord');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ClaimCodeRecord');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ClaimCodeRecord');
    }

    public function replicate(AuthUser $authUser, ClaimCodeRecord $claimCodeRecord): bool
    {
        return $authUser->can('Replicate:ClaimCodeRecord');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ClaimCodeRecord');
    }

}