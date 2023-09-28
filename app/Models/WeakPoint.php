<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeakPoint extends Model
{
    use HasFactory;

    protected $table    = 'weakpoints';
    protected $fillable = ['faculty_evaluation_id', 'comment'];

    public function facultyevaluation()
    {
        return $this->belongsTo(FacultyEvaluation::class, 'id', 'faculty_evaluation_id');
    }
}
