<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'address', 'added_by'];

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

    public function address(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['address']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function addedby()
    {
        return $this->hasOne(Userinfo::class, 'user_id', 'added_by');
    }
}
