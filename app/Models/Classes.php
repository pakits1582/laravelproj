<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';
    protected $fillable = ['code', 'period_id', 'section_id', 'curiculum_subject_id', 'units', 'tfunits', 'loadunits', 'lecunits', 'labunits', 'hours', 'instructor_id', 'schedule_id', 'slots', 'tutorial', 'dissolved', 'f2f', 'merge', 'ismother', 'isprof', 'evaluation', 'evaluated_by', 'evaluation_status'];
    
    use HasFactory;
}
