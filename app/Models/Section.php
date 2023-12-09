<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'program_id', 'year', 'minenrollee'];

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

    public function programinfo()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id')->withDefault(['code' => '', 'name' => '']);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'section_id', 'id');
    }

    public function section_monitorings()
    {
        return $this->hasMany(SectionMonitoring::class);
    }
}
