<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'head'];

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

    public function headinfo()
    {
        return $this->hasOne(Instructor::class, 'id', 'head');
    }

    public function headName(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => (is_null($this->headinfo)) ? '' : strtoupper($this->headinfo->last_name.', '.$this->headinfo->first_name.' '.$this->headinfo->name_suffix.' '.$this->headinfo->middle_name),
            //set: fn($value) => strtoupper($value)
        );
    }
}
