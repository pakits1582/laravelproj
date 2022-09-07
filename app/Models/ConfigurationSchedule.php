<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigurationSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['educational_level', 'college', 'year', 'date_from', 'date_to', 'period', 'type'];

    public function level()
    {
        return $this->belongsTo(Educationallevel::class, 'educational_level', 'id')->withDefault(['level' => '']);
    }

    public function collegeinfo()
    {
        return $this->belongsTo(College::class, 'college', 'id')->withDefault(['code' => '']);
    }
}
