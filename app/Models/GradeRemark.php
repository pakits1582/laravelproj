<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GradeRemark extends Model
{
    use HasFactory;

    protected $table = 'grade_remarks';
    protected $fillable = ['grade_id', 'display', 'remark', 'underlined'];

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
