<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = ['period_id', 'student_id', 'program_id', 'curriculum_id', 'section_id', 'year_level', 'new', 'old', 'transferee', 'cross_enrollee', 'foreigner', 'returnee', 'probationary', 'acctok', 'assessed', 'validated', 'cancelled', 'withdrawn', 'enrolled_units', 'user_id'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class)->withDefault(['code' => '', 'name' => '']);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAssessed($query)
    {
        return $query->where('assessed', 1);
    }

    public function scopeValidated($query)
    {
        return $query->where('validated', 1);
    }

    public function scopeCancelled($query)
    {
        return $query->where('cancelled', 1);
    }

    public function scopeWithdrawn($query)
    {
        return $query->where('withdrawn', 1);
    }

    public function enrolled_classes()
    {
        return $this->hasMany(EnrolledClass::class, 'enrollment_id', 'id');
    }

    public function enrolled_class_schedules()
    {
        return $this->hasMany(EnrolledClassSchedule::class, 'enrollment_id', 'id');
    }

    public function assessment()
    {
        return $this->hasOne(Assessment::class, 'enrollment_id', 'id');
    }

    public function grade()
    {
        return $this->hasOne(Grade::class, 'enrollment_id', 'id');
    }

    public function studentledgers()
    {
        return $this->hasMany(Studentledger::class, 'enrollment_id', 'id');
    }

    public function studentledger_assessment()
    {
        return $this->hasOne(Studentledger::class, 'enrollment_id', 'id')->where('type', 'A');
    }

    public function postcharges()
    {
        return $this->hasMany(Postcharge::class, 'enrollment_id', 'id');
    }


}
