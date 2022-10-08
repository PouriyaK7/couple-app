<?php

namespace App\Repositories\Financial;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TransactionRepository
{
    public static function store(array $data): ?Model
    {
        return $transaction = Transaction::query()->create([
            'id' => Str::uuid()->toString(),
            'title' => $data['title'],
            'amount' => $data['amount'],
            'account_id' => $data['account_id'],
            'description' => $data['description'] ?? null,
            'date' => $data['date'] ?? null,
            'user_id' => $data['user_id'],
            'transaction_category_id' => $data['category_id']
        ]);
    }

    public static function update(Transaction &$transaction, array $data): bool
    {
        foreach ($data as $key => $value) {
            $transaction->{$key} = $value;
        }
        return $transaction->save();
    }

    public static function delete(int $id): bool
    {
        return Transaction::query()->where('id', $id)->delete();
    }
}
