<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalGrade extends Model
{
    use HasFactory;

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

    
}
