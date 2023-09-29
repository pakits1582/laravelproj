<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacultyEvaluation extends Model
{
    use HasFactory;

    const FACULTY_EVAL_UNSTARTED = NULL;
    const FACULTY_EVAL_STARTED   = 1;
    const FACULTY_EVAL_FINISHED  = 2;

    const FACULTY_EVAL_STATUS = [
        NULL => 'NOT STARTED',
        0 => 'NOT STARTED',
        1 => 'STARTED',
        2 => 'EVALUATED'
    ];

    const CLASS_FOR_EVALUATION_FALSE = 0;
    const CLASS_FOR_EVALUATION_TRUE  = 1;

    protected $table    = 'faculty_evaluations';
    protected $fillable = ['enrollment_id', 'class_id', 'date_taken', 'status'];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }

    public function survey_answers()
    {
        return $this->hasMany(SurveyAnswer::class, 'faculty_evaluation_id', 'id');
        
    }

    public function overall_rates()
    {
        return $this->hasMany(OverallRate::class, 'faculty_evaluation_id', 'id');
        
    }

    public function strongpoints()
    {
        return $this->hasMany(StrongPoint::class, 'faculty_evaluation_id', 'id');
        
    }

    public function weakpoints()
    {
        return $this->hasMany(WeakPoint::class, 'faculty_evaluation_id', 'id');
        
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class, 'faculty_evaluation_id', 'id');
        
    }

    public function student_services()
    {
        return $this->hasMany(StudentService::class, 'faculty_evaluation_id', 'id');
        
    }
}
