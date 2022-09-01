<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'educational_level', 'college', 'head', 'years', 'source', 'active'];


    public function code(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['code']),
            set: fn($value) => strtoupper($value)
        );
    }

    public function name(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['name']),
            set: fn($value) => strtoupper($value)
        );
    }

    public function level()
    {
        return $this->belongsTo(Educationallevel::class, 'educational_level', 'id');
    }

    public function collegeinfo()
    {
        return $this->belongsTo(College::class, 'college', 'id');
    }

    public function headinfo()
    {
        return $this->belongsTo(Instructor::class, 'head', 'id');
    }

    public function headName(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => (is_null($this->headinfo)) ? '' : strtoupper($this->headinfo->last_name.', '.$this->headinfo->first_name.' '.$this->headinfo->name_suffix.' '.$this->headinfo->middle_name),
            //set: fn($value) => strtoupper($value)
        );
    }
}
