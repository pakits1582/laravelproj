<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaggedGrades extends Model
{
    use HasFactory;

    public function curriculumsubject()
    {
        return $this->belongsTo(CurriculumSubjects::class, 'curriculum_subject_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
