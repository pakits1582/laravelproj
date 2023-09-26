<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Educationallevel extends Model
{
    use HasFactory;

    protected $table = 'educational_levels';

    protected $fillable = ['code', 'level'];

    const DEFAULT_EDUCATIONAL_LEVEL = 1; //COLLEGE
}
