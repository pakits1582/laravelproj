<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use Illuminate\Http\Request;

class StudentScheduleController extends Controller
{

    public function __construct()
    {
        Helpers::setLoad(['jquery_studentschedule.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }

    public function index()
    {
        return view('studentschedule.index');
    }
}
