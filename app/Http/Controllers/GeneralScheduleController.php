<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Classes;
use App\Services\GeneralScheduleService;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use Illuminate\Support\Facades\DB;

class GeneralScheduleController extends Controller
{
    protected $generalScheduleService;

    public function __construct(GeneralScheduleService $generalScheduleService)
    {
        $this->generalScheduleService = $generalScheduleService;
        Helpers::setLoad(['jquery_generalschedule.js', 'select2.full.min.js']);
    }

    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $generalschedules = $this->generalScheduleService->generalschedules(session('current_period'));
        $gensched_classes = $this->generalScheduleService->getGeneralSchedules($generalschedules);

        return view('generalschedule.index', compact('periods', 'gensched_classes'));
    }

    public function filtergeneralschedule(Request $request)
    {
        $generalschedules = $this->generalScheduleService->generalschedules($request->period_id, $request->educational_level, $request->college, $request->display);
        $gensched_classes = $this->generalScheduleService->getGeneralSchedules($generalschedules, $request->display);

        return view('generalschedule.return_generalschedule', compact('gensched_classes'));
    }
}
