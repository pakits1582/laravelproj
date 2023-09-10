<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacultyEvaluation extends Model
{
    use HasFactory;

    protected $table = 'faculty_evaluations';
    protected $fillable = ['enrollment_id', 'class_id', 'date_taken', 'status'];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }
}
