<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumSubjects extends Model
{
    protected $fillable = ['program_id', 'curriculum_id', 'subject_id', 'term_id', 'year_level', 'quota'];
    use HasFactory;
}
