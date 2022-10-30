<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Remark extends Model
{
    use HasFactory;
    protected $fillable = ['remark'];

    public function remark(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['remark']),
            set: fn ($value) => strtoupper($value)
        );
    }

}
