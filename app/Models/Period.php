<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'term', 'year', 'class_start', 'class_end', 'class_ext', 'enroll_start', 'enroll_end', 'enroll_ext', 'adddrop_start', 'adddrop_end', 'adddrop_ext', 'idmask', 'source', 'display', 'priority_lvl'];

    public function terminfo()
    {
        return $this->belongsTo(Term::class, 'term', 'id');
    }
}
