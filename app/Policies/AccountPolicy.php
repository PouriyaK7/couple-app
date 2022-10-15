<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization;

    public function access(User $user, Account $account): bool
    {
        return $user->id == $account->user_id;
    }
}
