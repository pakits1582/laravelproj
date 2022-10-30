<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GradingSystem extends Model
{
    use HasFactory;

    protected $fillable = ['educational_level_id', 'code', 'value', 'remark_id'];

    public function code(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['code']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function value(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['value']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function remark()
    {
        return $this->belongsTo(Remark::class, 'remark_id', 'id');
    }

    public function level()
    {
        return $this->belongsTo(Educationallevel::class, 'educational_level_id', 'id');
    }
}
