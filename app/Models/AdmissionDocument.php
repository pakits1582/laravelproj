<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdmissionDocument extends Model
{
    use HasFactory;
    protected $table = 'admission_documents';
    protected $fillable = ['educational_level_id', 'program_id', 'classification', 'description', 'display', 'is_required'];

    public function description(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['description']),
            set: fn ($value) => strtoupper($value)
        );
    }

    public function educlevel()
    {
        return $this->belongsTo(Educationallevel::class, 'educational_level_id', 'id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }
}
