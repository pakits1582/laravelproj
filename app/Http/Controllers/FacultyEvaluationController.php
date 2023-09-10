<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use Illuminate\Http\Request;
use App\Services\FacultyEvaluationService;

class FacultyEvaluationController extends Controller
{
    protected $facultyEvaluationService;

    public function __construct(FacultyEvaluationService $facultyEvaluationService)
    {
        $this->facultyEvaluationService = $facultyEvaluationService;
        Helpers::setLoad(['jquery_facultyevaluation.js', 'select2.full.min.js']);
    }

    public function index()
    {

        return view('facultyevaluation.index');
    }
}
