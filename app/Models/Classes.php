<?php

namespace App\Models;


use App\Models\Schedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classes extends Model
{
    protected $table = 'classes';
    protected $fillable = ['code', 'period_id', 'section_id', 'curiculum_subject_id', 'units', 'tfunits', 'loadunits', 'lecunits', 'labunits', 'hours', 'instructor_id', 'schedule_id', 'slots', 'tutorial', 'dissolved', 'f2f', 'merge', 'ismother', 'isprof', 'evaluation', 'evaluated_by', 'evaluation_status'];
    
    use HasFactory;

    public function sectioninfo()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function curriculumsubject()
    {
        return $this->belongsTo(CurriculumSubjects::class, 'curriculum_subject_id', 'id');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id', 'id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id')->withDefault(['schedule' => '']);
    }

    public function classschedules(){
        return $this->hasMany(ClassesSchedule::class, 'classes_id', 'id');
    }
}
