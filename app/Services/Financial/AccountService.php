<?php

namespace App\Services\Financial;

use App\Models\Account;
use App\Repositories\Financial\AccountRepository;

class AccountService
{
    public static function checkAccess(string $id, string $userID): bool
    {
        return Account::query()->where('user_id', $userID)->where('id', $id)->count() == 1;
    }

    public static function changeBalance(Account &$account, int $amount): int
    {
        return AccountRepository::sumBalance($account, $amount);
    }
}
