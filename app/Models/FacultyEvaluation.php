<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacultyEvaluation extends Model
{
    use HasFactory;

    const FACULTY_EVAL_UNSTARTED = 0;
    const FACULTY_EVAL_STARTED   = 1;
    const FACULTY_EVAL_FINISHED  = 2;

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
}
