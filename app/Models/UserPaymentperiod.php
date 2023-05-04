<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPaymentperiod extends Model
{
    use HasFactory;

    protected $table = 'user_paymentperiods';

    protected $fillable = [
        'user_id',
        'pay_period'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
