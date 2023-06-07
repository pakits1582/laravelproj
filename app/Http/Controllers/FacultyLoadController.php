<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use Illuminate\Http\Request;
use App\Services\PeriodService;

class FacultyLoadController extends Controller
{
    public function __construct()
    {
        Helpers::setLoad(['jquery_facultyload.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }

    
    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);

        return view('facultyload.index', compact('periods'));
    }
}
