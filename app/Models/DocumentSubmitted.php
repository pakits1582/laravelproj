<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentSubmitted extends Model
{
    use HasFactory;
    protected $table = 'documents_submitted';
    protected $fillable = ['student_id', 'admission_document_id'];

    public function document()
    {
        return $this->belongsTo(AdmissionDocument::class, 'admission_document_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
