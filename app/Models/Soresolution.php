<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Soresolution extends Model
{
    use HasFactory;

    protected $table = 'soresolutions';
    protected $fillable = ['conjunction', 'title'];

    public function conjunction(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['conjunction']) ?? '',
            set: fn ($value) => strtoupper($value) ?? ''
        );
    }

    public function title(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['title']) ?? '',
            set: fn ($value) => strtoupper($value) ?? ''
        );
    }
}
