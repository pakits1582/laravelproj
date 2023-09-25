<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConfigurationSchedule extends Model
{
    use HasFactory;

    const SCHEDULE_ENROLMENT = 'enrolment';
    const SCHEDULE_ADD_DROP = 'addingdropping';
    const SCHEDULE_STUDENT_REGISTRATION = 'student_registration';
    const SCHEDULE_GRADE_POSTING = 'grade_posting';
    const SCHEDULE_FINAL_GRADE_SUBMISSION = 'final_grade_submission';
    const SCHEDULE_FACULTYLOAD_POSTING = 'facultyload_posting';
    const SCHEDULE_CLASS_SCHEDULING = 'class_scheduling';
    const SCHEDULE_FACULTY_EVALUATION = 'faculty_evaluation';

    protected $fillable = ['educational_level_id', 'college_id', 'year', 'date_from', 'date_to', 'period_id', 'type'];

    public function level()
    {
        return $this->belongsTo(Educationallevel::class, 'educational_level_id', 'id')->withDefault(['level' => 'ALL']);
    }

    public function collegeinfo()
    {
        return $this->belongsTo(College::class, 'college_id', 'id')->withDefault(['code' => 'ALL', 'name' => '']);
    }

    public function year(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => (is_null($attributes['year'])) ? 'ALL' : $attributes['year'],
            //set: fn ($value) => strtoupper($value)
        );
    }
    
}
