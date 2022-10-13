<?php

namespace App\Services\Financial;

use App\Repositories\Financial\AccountRepository;

class AccountService
{
    public static function checkAccess(string $id, string $userID): bool
    {
        return AccountRepository::checkAccess($id, $userID);
    }
}
