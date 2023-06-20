<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PeriodService;

class GeneralScheduleController extends Controller
{
    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);

        return view('generalschedule.index', compact('periods'));
    }
}
