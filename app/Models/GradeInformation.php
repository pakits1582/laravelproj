<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GradeInformation extends Model
{
    use HasFactory;

    protected $table = 'grade_informations';
    protected $fillable = ['grade_id', 'school_id', 'program_id', 'thesis_title', 'graduation_date', 'graduation_award', 'soresolution_id', 'soresolution_no', 'soresolution_series', 'issueing_office_id', 'issued_date', 'remark'];

    public function thesisTitle(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['thesis_title']) ?? '',
            set: fn ($value) => strtoupper($value) ?? ''
        );
    }

    public function remark(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['remark']) ?? '',
            set: fn ($value) => strtoupper($value) ?? ''
        );
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id', 'id');
    }
}
