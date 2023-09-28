<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    use HasFactory;
    
    protected $table = 'survey_answers';
    protected $fillable = ['faculty_evaluation_id', 'question_id', 'answer'];

    public function facultyevaluation()
    {
        return $this->belongsTo(FacultyEvaluation::class, 'id', 'faculty_evaluation_id');
    }
}
