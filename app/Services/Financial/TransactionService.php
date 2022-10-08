<?php

namespace App\Services\Financial;

use App\Models\Transaction;

class TransactionService
{
    public static function checkAccess(string $id, string $userID): bool
    {
        return Transaction::query()->where('id', $id)->where('user_id', $userID)->count();
    }
}
