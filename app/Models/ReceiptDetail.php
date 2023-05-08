<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReceiptDetail extends Model
{
    use HasFactory;

    protected $table = 'receipt_details';
    protected $fillable = ['receipt_no', 'source_id', 'type', 'fee_id', 'amount', 'payor_name'];

    public function payorName(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['particular']),
            set: fn ($value) => strtoupper($value)
        );
    }

    
}
