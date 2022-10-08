<?php

namespace App\Http\Controllers\User;

use App\Events\UpdateTransactionEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Repositories\Financial\TransactionRepository;
use App\Services\Financial\AccountService;
use App\Services\Financial\TransactionService;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function store(TransactionRequest $request)
    {
        # TODO check if can we put this in validator file
        # Check if user has access to bank account
        if (!AccountService::checkAccess($request->account_id, Auth::id())) {
            return 'access denied';
        }

        # Generate store data
        $data = $request->validated() + ['user_id' => Auth::id()];
        # Create transaction
        $transaction = TransactionRepository::store($data);

        # Return error on store failure
        if (empty($transaction)) {
            return 'error';
        }

        # Trigger create transaction event
        event(new UpdateTransactionEvent($transaction));

        # Return the whole model for now
        return $transaction;
    }

    public function update(TransactionRequest $request, string $id)
    {
        # Get transaction and return error on not found
        $transaction = Transaction::query()->find($id);
        if (empty($transaction)) {
            return 'not found';
        }

        # Return error on access denial
        if (!TransactionService::checkAccess($id, Auth::id())) {
            return 'access denied';
        }

        # Get old transaction amount
        $amount = $transaction->amount;

        # Update transaction
        $data = $request->validated();
        if (!TransactionRepository::update($transaction, $data)) {
            return 'something went wrong please try again later';
        }

        # TODO check this
        # Trigger update event
        $amount -= $data['amount'];
        $transaction->amount = -$amount;
        event(new UpdateTransactionEvent($transaction));

        return $transaction;
    }

    public function delete(string $id)
    {
        # Get transaction and return error on not found
        $transaction = Transaction::query()->find($id);
        if (empty($transaction)) {
            return 'not found';
        }

        # Return error on access denial
        if (!TransactionService::checkAccess($id, Auth::id())) {
            return 'access denied';
        }

        # Reverse transaction amount
        $transaction->amount = -$transaction->amount;
        # Trigger update transaction event
        event(new UpdateTransactionEvent($transaction));

        # Delete transaction
        TransactionRepository::delete($id);

        return true;
    }
}
