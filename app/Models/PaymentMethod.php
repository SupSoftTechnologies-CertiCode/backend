<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'payment_method',
        'other_payment_method',
        'account_name',
        'account_number',
        'qr_code',
    ];
}
