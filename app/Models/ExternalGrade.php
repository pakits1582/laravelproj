<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalGrade extends Model
{
    use HasFactory;

    protected $fillable = ['grade_id', 'subject_code', 'subject_description', 'grade', 'completion_grade', 'equivalent_grade', 'units', 'remark_id', 'user_id'];
}
