<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fee extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code', 'fee_type_id', 'iscompound', 'default_value', 'colindex'];

    public function code(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['code']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function name(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['name']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function feetype()
    {
        return $this->belongsTo(FeeType::class, 'fee_type_id', 'id');
    }

    public function scopeAdditionalFees(Builder $query)
    {
        return $query->with('feetype')
            ->whereHas('feetype', function($query){
                $query->where('type', 'Additional Fees');
            });
    }
}
