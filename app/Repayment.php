<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    protected $table = "repayments";

    protected $fillable = ['payment', 'interest', 'principal', 'start_balance', 'end_balance'];

    public function user()
    {
        $this->belongsTo('App\User');
    }

    public function loan()
    {
        $this->belongsTo('App\Loan');
    }
}
