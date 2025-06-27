<?php

namespace App\Policies;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Ad $ad)
    {
        return $user->id === $ad->user_id;
    }

    public function delete(User $user, Ad $ad)
{
    return $user->id === $ad->user_id;
}


}
