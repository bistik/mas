<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLoan extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => ['required', 'regex:/^\d*(\.\d{1,2})?$/'],
            'duration' => ['required', 'numeric', 'min:1'],
            'repayment_frequency' => ['required', 'numeric', 'min:1'],
            'interest_rate' => ['required', 'regex:/^\d*(\.\d{1,2})?$/', 'min:1'],
            'arrangement_fee' => ['regex:/^\d*(\.\d{1,2})?$/'],
            'currency' => ['regex:[a-zA-Z]{3}']
        ];
    }
}
