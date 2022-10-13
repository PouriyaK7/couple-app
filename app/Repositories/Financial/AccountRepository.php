<?php

namespace App\Repositories\Financial;

use App\Models\Account;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class AccountRepository
{
    public static function store(string $title, string $bank, string $userID, string $description = null): Account
    {
        return Account::query()->create([
            'id' => Str::uuid()->toString(),
            'title' => $title,
            'bank' => $bank,
            'user_id' => $userID,
            'description' => $description
        ]);
    }

    public static function update(Account &$account, string $title, string $bank, string $description = null): void
    {
        $account->title = $title;
        $account->bank = $bank;
        $account->description = $description;
        $account->save();
    }

    public static function delete(string $id): bool
    {
        return Account::query()->where('id', $id)->delete();
    }

    public static function sumBalance(Account &$account, int $amount): int
    {
        $account->balance += $amount;
        $account->save();
        return $account->balance;
    }

    public static function transactions(string $id): Collection
    {
        return Account::query()->where('id', $id)->transactions;
    }

    public static function checkAccess(string $id, string $userID): bool
    {
        return Account::query()->where('user_id', $userID)->where('id', $id)->count() == 1;
    }
}
