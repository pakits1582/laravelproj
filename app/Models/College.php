<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'dean', 'class_code'];

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

    public function deanName(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => (is_null($this->deaninfo)) ? '' : strtoupper($this->deaninfo->last_name.', '.$this->deaninfo->first_name.' '.$this->deaninfo->name_suffix.' '.$this->deaninfo->middle_name),
            //set: fn($value) => strtoupper($value)
        );
    }

    public function deaninfo()
    {
        return $this->belongsTo(Instructor::class, 'dean', 'id');
    }

}
