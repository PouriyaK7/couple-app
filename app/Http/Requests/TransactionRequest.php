<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:1'],
            'amount' => ['required', 'integer', 'min:0'],
            'account_id' => ['required', 'integer', 'exists:accounts,id'],
            'description' => ['nullable', 'string', 'min:2'],
            'date' => ['required', 'date'],
            'transaction_category_id' => ['required', 'integer', 'exists:transaction_categories,id']
        ];
    }
}
