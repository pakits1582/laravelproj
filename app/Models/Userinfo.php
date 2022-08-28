<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userinfo extends Model
{
    use HasFactory;

    protected $table = 'userinfo';

    protected $fillable = [
        'user_id',
        'name',
    ];

    public function name(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['name']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
