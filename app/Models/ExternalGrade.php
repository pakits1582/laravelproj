<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExternalGrade extends Model
{
    use HasFactory;

    protected $fillable = ['grade_id', 'subject_code', 'subject_description', 'grade', 'completion_grade', 'equivalent_grade', 'units', 'remark_id', 'user_id'];

    public function subjectCode(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['subject_code']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function subjectDescription(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['subject_description']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function grade(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['grade']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function gradeinfo()
    {
        return $this->belongsTo(Grade::class, 'grade_id', 'id');
    }

    public function remark()
    {
        return $this->belongsTo(Remark::class, 'remark_id', 'id');
    }
}
