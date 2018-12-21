<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Loan;

class CreateRepayment extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->loan_id && is_numeric($this->loan_id) && $loan = Loan::whereId($this->loan_id)->first()) {
            // loan_id belongs to requesting user
            return $loan && $this->user()->id == $loan->user_id;
        }
        return true; // let rules handle it
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'loan_id' => ['required', 'numeric', 'exists:loans,id'],
            'amount' => ['required', ''],
        ];
    }
}
