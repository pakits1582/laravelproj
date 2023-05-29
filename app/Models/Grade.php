<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'period_id', 'school_id', 'program_id', 'origin'];

    const ORIGIN_INTERNAL = 0;
    const ORIGIN_EXTERNAL = 1;

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id', 'id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id');
    }

    public function scopeOfOrigin($query, $origin)
    {
        return $query->where('origin', '=', $origin);
    }

    public function internalgrades()
    {
        return $this->hasMany(InternalGrade::class);
    }

    public function externalgrades()
    {
        return $this->hasMany(ExternalGrade::class);
    }

    public function grade_info()
    {
        return $this->hasOne(GradeInformation::class, 'grade_id', 'id');
    }

    public function grade_remarks()
    {
        return $this->hasMany(GradeRemark::class, 'grade_id', 'id');
    }
    





}
