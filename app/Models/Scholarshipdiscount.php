<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scholarshipdiscount extends Model
{
    use HasFactory;

    protected $table = 'scholarshipdiscounts';
    protected $fillable = ['code', 'description', 'tuition', 'tuition_type', 'miscellaneous', 'miscellaneous_type', 'othermisc', 'othermisc_type', 'laboratory', 'laboratory_type', 'totalassessment', 'totalassessment_type', 'type'];

    public function code(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['code']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function description(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['description']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function grants()
    {
        return $this->hasMany(ScholarshipdiscountGrant::class);
    }
}
