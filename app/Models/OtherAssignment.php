<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OtherAssignment extends Model
{
    use HasFactory;

    protected $table = 'other_assignments';
    protected $fillable = ['period_id', 'instructor_id', 'assignment', 'units', 'user_id'];

    public function assignment(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['assignment'] ?? ''),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function instrcutor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id', 'id');
    }
}
