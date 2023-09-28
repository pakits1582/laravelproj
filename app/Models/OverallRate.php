<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverallRate extends Model
{
    use HasFactory;

    protected $table    = 'overall_rates';
    protected $fillable = ['faculty_evaluation_id', 'answer'];

    public function facultyevaluation()
    {
        return $this->belongsTo(FacultyEvaluation::class, 'id', 'faculty_evaluation_id');
    }
}
