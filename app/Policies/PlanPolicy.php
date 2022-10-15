<?php

namespace App\Policies;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlanPolicy
{
    use HandlesAuthorization;

    /**
     * Check user access to a plan
     * @param User $user
     * @param Plan $plan
     * @return bool
     */
    public function access(User $user, Plan $plan): bool
    {
        return $plan->user_id == $user->id;
    }
}
