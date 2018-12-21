<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateRepayment;
use Illuminate\Support\Facades\Auth;
use App\Repayment;
use App\Loan;

class RepaymentController extends Controller
{
    public function store(CreateRepayment $request)
    {
        $lastRepayment = Repayment::whereLoanId($request->loan_id)
                                ->orderBy('id', 'desc')
                                ->limit(1)
                                ->first();

        $loan = Loan::whereId($request->loan_id)->first();
        
        if ($lastRepayment) {
            $startBalance = $lastRepayment->end_balance;
        } else {
            $startBalance = $loan->amount;
        }
        $startBalance = $startBalance * 100; // cents maybe?
        $amount = $request->amount * 100;
        $interest = round(compute_interest($loan->interest_rate, $startBalance / 100) * 100, 2);
        $principal = round($amount - $interest, 2);
        $balance = max(0.00, round($startBalance - $principal, 2));

        // payment does not meet minimum
        if (round($startBalance / 100, 2) >= $loan->monthly_repayment && $request->amount < $loan->monthly_repayment) {
            return response()->json(['error' => 'Insufficient repayment'], 400);
        }
        if ($startBalance <= 0.00) {
            return response()->json(['error' => 'Repayment no longer accepted'], 400);
        }
        if ($balance < 0.00) {
            return response()->json(['error' => 'Payment is over the limit', 'balance' => $balance], 400);
        }

        $repayment = new Repayment;
        $repayment->payment = $request->amount;
        $repayment->user_id = Auth::id();
        $repayment->loan_id = $request->loan_id;
        $repayment->interest = round($interest / 100, 2);
        $repayment->start_balance = round($startBalance / 100, 2);
        $repayment->principal = round($principal / 100, 2);
        $repayment->end_balance = round($balance / 100, 2);
        $repayment->save();

        return response()->json(['message' => 'Repayment successful!', 'balance' => round($balance / 100, 2)]);
    }

    public function repayments($id)
    {
        $repayments = Repayment::where(['user_id' => Auth::id(), 'loan_id' => $id])->get()->toArray();

        return response()->json(compact('repayments'));
    }
}
