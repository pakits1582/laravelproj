<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = ['period_id', 'receipt_no', 'fee_id', 'student_id', 'bank_id', 'deposit_date', 'receipt_date', 'amount', 'deffered', 'cancelled', 'cancel_remark', 'in_assess', 'user_id'];

    public function payorName(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['particular']),
            set: fn ($value) => strtoupper($value)
        );
    }
}
