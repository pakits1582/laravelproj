<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corequisite extends Model
{
    protected $fillable = ['curriculum_subject_id', 'corequisite'];
    use HasFactory;

    public function curriculumsubject()
    {
        return $this->belongsTo(CurriculumSubjects::class, 'corequisite', 'id');
    }
}
