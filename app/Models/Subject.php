<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'units', 'tfunits', 'loadunits', 'lecunits', 'labunits', 'hours', 'educational_level_id', 'college_id', 'professional', 'laboratory', 'gwa', 'nograde', 'notuition', 'exclusive'];

    public function code(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['code']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function name(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['name']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function collegeinfo()
    {
        return $this->belongsTo(College::class, 'college_id', 'id')->withDefault(['code' => '', 'name' => '']);
    }

    public function educlevel()
    {
        return $this->belongsTo(Educationallevel::class, 'educational_level_id', 'id')->withDefault(['code' => '', 'level' => '']);
    }

    public function collegecode(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => (is_null($this->collegeinfo)) ? '' : $this->collegeinfo->code,
            //set: fn($value) => strtoupper($value)
        );
    }
}
