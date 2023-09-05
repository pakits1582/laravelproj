<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineSubmittedDocument extends Model
{
    use HasFactory;
    
    protected $table = 'online_submitted_documents';
    protected $fillable = ['student_id', 'admission_document_id', 'path'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function document()
    {
        return $this->belongsTo(AdmissionDocument::class, 'admission_document_id', 'id');
    }

}
