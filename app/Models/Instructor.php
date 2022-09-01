<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name_prefix',
        'last_name',
        'first_name',
        'middle_name',
        'name_suffix',
        'designation',
        'educational_level',
        'college',
        'department',
    ];

    public function namePrefix(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['name_prefix']),
            set: fn($value) => strtoupper($value)
        );
    }

    public function firstName(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['first_name']),
            set: fn($value) => strtoupper($value)
        );
    }

    public function middleName(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['middle_name']),
            set: fn($value) => strtoupper($value)
        );
    }

    public function lastName(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['last_name']),
            set: fn($value) => strtoupper($value)
        );
    }

    public function nameSuffix(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['name_suffix']),
            set: fn($value) => strtoupper($value)
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['last_name'].', '.$attributes['first_name'].' '.$attributes['name_suffix'].' '.$attributes['middle_name']),
            //set: fn($value) => strtoupper($value)
        );
    }

    public function deptcode(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => (is_null($this->deptinfo)) ? '' : $this->deptinfo->code,
            //set: fn($value) => strtoupper($value)
        );
    }

    public function collegeinfo()
    {
        return $this->belongsTo(College::class, 'college', 'id');
    }

    public function deptinfo()
    {
        return $this->belongsTo(Department::class, 'department', 'id');
    }

    public function educlevel()
    {
        return $this->belongsTo(Educationallevel::class, 'educational_level', 'id');
    }
}
