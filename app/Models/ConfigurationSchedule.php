<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConfigurationSchedule extends Model
{
    use HasFactory;

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
