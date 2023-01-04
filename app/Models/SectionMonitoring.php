<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionMonitoring extends Model
{
    protected $table = 'section_monitorings';
    protected $fillable = ['section_id', 'minimum_enrollees', 'allowed_units', 'status', 'period_id'];
    
    use HasFactory;

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
