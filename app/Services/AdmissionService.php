<?php

namespace App\Services;

use App\Models\AdmissionDocument;

class AdmissionService
{
    public function admissionDocuments()
    {
        $documents = AdmissionDocument::select('admission_documents.*', 'educational_levels.code AS educlevel', 'programs.code AS program')
        ->join('educational_levels', 'educational_levels.id', '=', 'admission_documents.educational_level_id')
        ->leftJoin('programs', 'programs.id', '=', 'admission_documents.program_id')
        ->orderBy('educational_levels.id')
        ->get();
        
        return $documents;
    }
}