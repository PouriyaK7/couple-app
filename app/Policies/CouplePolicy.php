<?php

namespace App\Policies;

use App\Models\Couple;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CouplePolicy
{
    use HandlesAuthorization;

    public function access(User $user, Couple $couple): bool
    {
        return $couple->user_id == $user->id;
    }
}
