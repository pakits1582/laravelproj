<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Models\SectionMonitoring;
use Illuminate\Support\Facades\DB;
use App\Services\SlotMonitoringService;

class ClassesSlotsMonitoringController extends Controller
{
    protected $slotmonitoringService;

    public function __construct(SlotMonitoringService $slotmonitoringService)
    {
        $this->slotmonitoringService = $slotmonitoringService;
        Helpers::setLoad(['jquery_slotsmonitoring.js', 'select2.full.min.js']);
    }

    public function index()
    {
        $classes = $this->slotmonitoringService->slotmonitoring(session('current_period'));
        $classeswithslots = $this->slotmonitoringService->getClassesSlots($classes);
        $sections_offered = SectionMonitoring::with(['section'])->where('period_id', session('current_period'))->get();

        return view('class.slotsmonitoring.index', compact('classeswithslots', 'sections_offered'));
    }

    public function filterslotmonitoring(Request $request)
    {
        $classes = $this->slotmonitoringService->slotmonitoring(session('current_period'), $request->educational_level, $request->college, $request->section_id, $request->keyword);
        $classeswithslots = $this->slotmonitoringService->getClassesSlots($classes);

        return view('class.slotsmonitoring.return_slotmonitoring', compact('classeswithslots'));
    }
}
