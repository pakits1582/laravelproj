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
        'educational_level_id',
        'college_id',
        'department_id',
    ];

    const TYPE_TEACHER = 1;
    const TYPE_PROGRAM_HEAD = 2;
    const TYPE_DEPARTMENT_HEAD = 3;
    const TYPE_DEAN = 4;
    const TYPE_PROFESSOR = 5;
    const TYPE_OTHERS = 6;

    public function namePrefix(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['name_prefix']),
            set: fn ($value) => strtoupper($value)
        );
    }

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

    public function deptcode(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => (is_null($this->deptinfo)) ? '' : $this->deptinfo->code,
            //set: fn($value) => strtoupper($value)
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function collegeinfo()
    {
        return $this->belongsTo(College::class, 'college_id', 'id')->withDefault(['code' => '', 'name' => '']);
    }

    public function deptinfo()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id')->withDefault(['code' => '', 'name' => '']);
    }

    public function educlevel()
    {
        return $this->belongsTo(Educationallevel::class, 'educational_level_id', 'id')->withDefault(['code' => '', 'name' => '']);
    }

    public function other_assignments()
    {
        return $this->hasMany(OtherAssignment::class, 'instructor_id', 'id');
    }

}
