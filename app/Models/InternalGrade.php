<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalGrade extends Model
{
    use HasFactory;

    protected $table = 'internal_grades';
    protected $fillable = ['grade_id', 'class_id', 'grading_system_id', 'completion_grade', 'units', 'final', 'user_id'];

    public function gradeinfo()
    {
        return $this->belongsTo(Grade::class, 'grade_id', 'id');
    }

    public function classinfo()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }

    public function gradesystem()
    {
        return $this->belongsTo(GradingSystem::class, 'grading_system_id', 'id');
    }

    public function completion_grade()
    {
        return $this->belongsTo(GradingSystem::class, 'completion_grade', 'id');
    }
}
