<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['period_id', 'educational_level_id', 'year_level', 'payment_mode_id', 'description', 'tuition', 'miscellaneous', 'others', 'payment_type'];

    public function description(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['description']),
            set: fn ($value) => strtoupper($value)
        );
    }

    // public function name(): Attribute
    // {
    //     return new Attribute(
    //         get: fn ($value, $attributes) => strtoupper($attributes['name']),
    //         set: fn ($value) => strtoupper($value)
    //     );
    // }

    public function educlevel()
    {
        return $this->belongsTo(Educationallevel::class, 'educational_level_id', 'id')->withDefault(['level' => '']);
    }

    public function paymentmode()
    {
        return $this->belongsTo(PaymentMode::class, 'payment_mode_id', 'id')->withDefault(['mode' => '']);
    }
}
