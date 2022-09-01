<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'program', 'year', 'minenrollee'];

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

    public function programinfo()
    {
        return $this->belongsTo(Program::class, 'program', 'id');
    }

    
}
