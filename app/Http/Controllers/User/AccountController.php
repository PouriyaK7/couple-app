<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Models\Account;
use App\Repositories\Financial\AccountRepository;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Get all accounts
     * @return \App\Models\Account[]
     */
    public function index()
    {
        return Auth::user()->accounts;
    }

    /**
     * Show account
     * @param string $id
     * @return Account
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(string $id): Account
    {
        # Fetch account from db and check if exists
        $account = Account::query()->find($id);
        if (empty($account)) {
            abort(404);
        }

        # Check if user has access to account
        $this->authorize('access', $account);

        return $account;
    }

    /**
     * @param AccountRequest $request
     * @param string $id
     * @return Account
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(AccountRequest $request, string $id): Account
    {
        # Fill data var with validated data from request
        $data = $request->validated();

        # Fetch account from db and check if exists
        $account = Account::query()->find($id);
        if (empty($account)) {
            abort(404);
        }

        # Check if user has access to account
        $this->authorize('access', $account);

        # Update account data in db
        AccountRepository::update($account, $data['title'], $data['bank'], $data['description']);

        return $account;
    }

    /**
     * Store account data in database
     * @param AccountRequest $request
     * @return Account
     */
    public function store(AccountRequest $request): Account
    {
        # Fill data var with validated data from request
        $data = $request->validated();

        # Store account data in db
        return AccountRepository::store($data['title'], $data['bank'], Auth::id(), $data['description']);
    }
}
