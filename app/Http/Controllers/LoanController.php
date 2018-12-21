<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateLoan;
use App\Loan;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function store(CreateLoan $request)
    {
        $loan = new Loan;
        $loan->amount =  $request->amount;
        $loan->user_id = Auth::id();
        $loan->duration = $request->duration;
        $loan->interest_rate = $request->interest_rate;
        $loan->repayment_frequency = $request->repayment_frequency;
        if ($request->arrangement_fee) {
           $loan->arrangement_fee = $request->arrangement_fee; 
        }
        if ($request->currency) {
            $loan->currency = $request->currency;
        }
        $loan->monthly_repayment = compute_monthly_repayment($request->amount, $request->interest_rate, $request->duration);
        $loan->save();

        return response()->json(['message' => 'Loan successfully created!'], 200);
    }

    /**
     * get all users loans
     */
    public function all()
    {
        $loans = Loan::whereUserId(Auth::id())->get()->toArray();

        return response()->json(compact('loans'));
    }
}
