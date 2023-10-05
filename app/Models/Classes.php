<?php

namespace App\Models;


use App\Models\Schedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classes extends Model
{
    protected $table = 'classes';
    protected $fillable = ['code', 'period_id', 'section_id', 'curiculum_subject_id', 'units', 'tfunits', 'loadunits', 'lecunits', 'labunits', 'hours', 'instructor_id', 'schedule_id', 'slots', 'tutorial', 'dissolved', 'f2f', 'merge', 'ismother', 'isprof', 'evaluation', 'evaluated_by', 'evaluation_status'];
    
    use HasFactory;

    // public function instructoName(): Attribute
    // {
    //     return new Attribute(
    //         get: fn ($value, $attributes) => strtoupper($attributes['last_name'].', '.$attributes['first_name'].' '.$attributes['name_suffix'].' '.$attributes['middle_name']),
    //         set: fn($value) => strtoupper($value)
    //     );
    // }

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

    public function classschedules()
    {
        return $this->hasMany(ClassesSchedule::class, 'class_id', 'id');
    }

    public function enrolledstudents()
    {
        return $this->hasMany(EnrolledClass::class, 'class_id', 'id');
    }

    public function mergedenrolledstudents()
    {
        return $this->hasManyThrough(EnrolledClass::class, Classes::class,'merge', 'class_id');
    }

    public function getEnrolledAndMergedStudentCountAttribute()
    {
        //enrolled_and_merged_student_count
        $enrolledCount = $this->enrolledstudents()->count();
        $mergedCount   = $this->mergedenrolledstudents()->count();

        return $enrolledCount + $mergedCount;
    }

    public function enrolledstudentsvalidated()
    {
        return $this->hasMany(EnrolledClass::class, 'class_id', 'id')
            ->whereHas('enrollment', function ($query) {
                $query->where('validated', 1);
            });
    }

    public function mergedenrolledstudentsvalidated()
    {
        return $this->hasManyThrough(EnrolledClass::class, Classes::class, 'merge', 'class_id')
            ->whereHas('enrollment', function ($query) {
                $query->where('validated', 1);
            });
    }

    public function getEnrolledAndMergedStudentCountValidatedAttribute()
    {
        //enrolled_and_merged_student_count_validated
        $enrolledCount = $this->enrolledstudentsvalidated()->count();
        $mergedCount   = $this->mergedenrolledstudentsvalidated()->count();

        return $enrolledCount + $mergedCount;
    }

    public function mergetomotherclass()
    {
        return $this->belongsTo(Classes::class, 'merge', 'id');
    }

    public function merged()
    {
        return $this->hasMany(Classes::class, 'merge', 'id');
    }

    public function facultyevaluations()
    {
        return $this->hasMany(FacultyEvaluation::class, 'class_id', 'id');
    }
    
}
