<?php

namespace App\Policies;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdPolicy
{
    use HandlesAuthorization;

    protected function isOwnerOrAdmin(User $user, Ad $ad)
    {
        return $user->id === $ad->user_id || $user->is_admin;
    }

    public function update(User $user, Ad $ad)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $ad->user_id;
    }

    public function delete(User $user, Ad $ad)
    {
        return $this->isOwnerOrAdmin($user, $ad);
    }


}
