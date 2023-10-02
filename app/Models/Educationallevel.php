<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Educationallevel extends Model
{
    use HasFactory;

    protected $table = 'educational_levels';

    protected $fillable = ['code', 'level'];

    const DEFAULT_EDUCATIONAL_LEVEL = 1; //COLLEGE

    public function code(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['code']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function level(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['level']),
            set: fn ($value) => strtoupper($value)
        );
    }

}
