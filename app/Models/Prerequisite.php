<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prerequisite extends Model
{
    protected $fillable = ['curriculum_subject_id', 'prerequisite'];

    use HasFactory;

    public function curriculumsubject()
    {
        return $this->belongsTo(CurriculumSubjects::class, 'prerequisite', 'id');
    }
}
