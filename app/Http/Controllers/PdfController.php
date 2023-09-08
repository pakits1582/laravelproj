<?php

namespace App\Http\Controllers;

use App\Models\OnlineSubmittedDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
   public function onlinedocumentsubmitted(OnlineSubmittedDocument $document)
   {
        $filepath = $document->path;

        $path = storage_path('app/public/' . $filepath);

        if (!Storage::exists('public/' . $filepath)) {
            abort(404);
        }

        return response()->file($path);
   }
}
