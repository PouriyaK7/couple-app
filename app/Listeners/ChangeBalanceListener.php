<?php

namespace App\Listeners;

use App\Models\Account;
use App\Services\Financial\AccountService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChangeBalanceListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $transaction = $event->transaction;
        $amount = $transaction->amount;
        $account = Account::query()->find($transaction->account_id);

        AccountService::changeBalance($account, $amount);
    }
}
