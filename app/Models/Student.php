<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    const SEX = [
        1 => 'MALE',
        2 => 'FEMALE'
    ];

    const SEX_MALE = 1;
    const SEX_FEMALE = 2;

    const STUDENT_CLASSIFICATION = [
        1 => 'NEW STUDENT',
        2 => 'TRANSFEREE',
        3 => 'GRADUATED (New Program)'
    ];

    const APPLI_STATUS = [
        1 => 'UNPROCESSED',
        2 => 'ACCEPTED',
        3 => 'REJECTED'
    ];

    const APPLI_NEW = 1;
    const APPLI_ACCEPTED = 2;
    const APPLI_REJECTED = 3;

    const SACRAMENTS = [
        1 => 'YES',
        2 => 'NO',
        3 => 'N/A'
    ];

    const RELIGIONS = [
        17 => 'Roman Catholic',
        1 => 'Anglican',
        2 => 'Aglipayan',
        3 => 'Assembly of God',
        4 => 'Baptist',
        5 => 'Born Again Christian',
        6 => 'Church of Latter-Day Saints (Mormons)',
        7 => 'Crusaders of the Divine Church of Christ',
        8 => 'Iglesia Filipina Independiente',
        9 => 'Iglesia Ni Cristo',
        10 => 'Islam',
        11 => 'Jehovah\'s Witness',
        12 => 'Lutheran',
        13 => 'Methodist',
        15 => 'Pentecost',
        16 => 'Protestant',
        18 => 'Seventh Day Adventist',
        19 => 'Sikh',
        20 => 'UCCP',
        14 => 'Others',     
    ];

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

    public function assessedby()
    {
        return $this->belongsTo(User::class, 'assessed_by', 'id');
    }

    public function documents_submitted()
    {
        return $this->hasMany(DocumentSubmitted::class);
    }

    public function online_documents_submitted()
    {
        return $this->hasMany(OnlineSubmittedDocument::class);
    }

}
