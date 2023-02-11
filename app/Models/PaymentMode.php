<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMode extends Model
{
    use HasFactory;

    protected $fillable = ['mode'];

    public function mode(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['mode']),
            set: fn ($value) => strtoupper($value)
        );
    }

}
