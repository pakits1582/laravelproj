<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionSubcategory extends Model
{
    use HasFactory;
    protected $table = 'question_subcategories';
    protected $fillable = ['name'];
}
