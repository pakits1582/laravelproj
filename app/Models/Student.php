<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    const SEX_MALE = 1;
    const SEX_FEMALE = 2;

    protected $fillable = ['id', 'user_id', 'picture', 'last_name', 'first_name', 'middle_name', 'name_suffix', 'sex', 'program_id', 'year_level', 'curriculum_id', 'academic_status', 'report_card', 'als_certificate', 'classification', 'application_no', 'application_status', 'admission_status','admission_date', 'entry_date', 'entry_data', 'entry_period', 'assessed_by', 'assessed_date'];

    public function firstName(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['first_name'] !== null ? strtoupper($attributes['first_name']) : null,
            set: function ($value) {
                // Check if the value is not NULL before converting to uppercase
                return $value !== null ? strtoupper($value) : null;
            }       
         );
    }

    public function middleName(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['middle_name'] !== null ? strtoupper($attributes['middle_name']) : null,
            set: function ($value) {
                // Check if the value is not NULL before converting to uppercase
                return $value !== null ? strtoupper($value) : null;
            }        
        );
    }

    public function lastName(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['last_name'] !== null ? strtoupper($attributes['last_name']) : null,
            set: function ($value) {
                // Check if the value is not NULL before converting to uppercase
                return $value !== null ? strtoupper($value) : null;
            }        
        );
    }

    public function nameSuffix(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['name_suffix'] !== null ? strtoupper($attributes['name_suffix']) : null,
            set: function ($value) {
                // Check if the value is not NULL before converting to uppercase
                return $value !== null ? strtoupper($value) : null;
            }        
        );
    }

    public function name(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => 
                strtoupper(
                    ($attributes['last_name'] ?? '').
                    ', '.($attributes['first_name'] ?? '').
                    ($attributes['name_suffix'] ? ' '.$attributes['name_suffix'] : '').
                    ($attributes['middle_name'] ? ' '.$attributes['middle_name'] : '')
                ),
            set: fn ($value) => strtoupper($value)
        );
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class)->withDefault(['id' => '', 'curriculum' => '']);
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id')->withDefault(['code' => '', 'name' => '']);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function academic_info()
    {
        return $this->hasOne(StudentAcademicInformationModel::class, 'student_id', 'id');
    }

    public function contact_info()
    {
        return $this->hasOne(StudentContactInformationModel::class, 'student_id', 'id');
    }

    public function personal_info()
    {
        return $this->hasOne(StudentPersonalInformationModel::class, 'student_id', 'id');
    }

    public function entryperiod()
    {
        return $this->hasOne(Period::class, 'id', 'entry_period');
    }

    
}
