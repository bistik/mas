<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $table = 'loans';

    protected $fillable = ['amount', 'monthly_repayment', 'interest_rate', 'duration', 'repayment_frequency', 'arrangement_fee', 'currency'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function repayments()
    {
        return $this->hasMany('App\Repayment');
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = number_format($value, 2, '.', '');
    }

    public function setInterestRateAttribute($value)
    {
        $this->attributes['interest_rate'] = number_format($value, 2, '.', '');
    }

    public function setArrangementFeeAttribute($value)
    {
        $this->attributes['arrangement_fee'] = number_format($value, 2, '.', '');
    }

    public function setCurrencyAttribute($value)
    {
        $this->attributes['currency'] = strtoupper($value);
    }
}
