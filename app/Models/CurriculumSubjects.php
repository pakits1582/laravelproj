<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumSubjects extends Model
{
    protected $fillable = ['curriculum_id', 'subject_id', 'term_id', 'year_level', 'quota'];
    use HasFactory;


    public function subjectinfo()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function terminfo()
    {
        return $this->belongsTo(Term::class, 'term_id', 'id');
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id', 'id');
    }

    public function prerequisites()
    {
        return $this->hasMany(Prerequisite::class, 'curriculum_subject_id', 'id');
    }

    public function corequisites()
    {
        return $this->hasMany(Corequisite::class, 'curriculum_subject_id', 'id');
    }

    public function equivalents()
    {
        return $this->hasMany(Equivalent::class, 'curriculum_subject_id', 'id');
    }

}
