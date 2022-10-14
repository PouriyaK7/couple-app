<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    public function access(User $user, Transaction $transaction): bool
    {
        return $transaction->user_id == $user->id;
    }
}
