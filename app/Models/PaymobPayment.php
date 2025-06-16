<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymobPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'method_id',
        'amount',
        'amount_type',
        'reference_id',
        'type',
        'payment_url',
        'payment_status'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id','id');
    }
}
