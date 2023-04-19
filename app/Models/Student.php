<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'last_name', 'first_name', 'middle_name', 'name_suffix', 'sex', 'program_id', 'year_level', 'curriculum_id', 'academic_status'];

    public function firstName(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['first_name']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function middleName(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['middle_name']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function lastName(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['last_name']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function nameSuffix(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['name_suffix']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function name(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['last_name'].', '.$attributes['first_name'].' '.$attributes['name_suffix'].' '.$attributes['middle_name']),
            set: fn($value) => strtoupper($value)
        );
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class)->withDefault(['id' => '', 'curriculum' => '']);
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id')->withDefault(['code' => '', 'name' => '']);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    
}
