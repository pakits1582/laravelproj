<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    use HasFactory;

    protected $table    = 'suggestions';
    protected $fillable = ['faculty_evaluation_id', 'comment'];

    public function facultyevaluation()
    {
        return $this->belongsTo(FacultyEvaluation::class, 'id', 'faculty_evaluation_id');
    }
}
