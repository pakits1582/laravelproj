<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Studentadjustment extends Model
{
    use HasFactory;

    protected $fillable = ['enrollment_id', 'type', 'particular', 'amount', 'user_id', 'created_at', 'updated_at'];
    public $timestamps = false;
    
    public function particular(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => strtoupper($attributes['particular']),
            set: fn ($value) => strtoupper($value)
        );
    }
    
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);   
    }
}
