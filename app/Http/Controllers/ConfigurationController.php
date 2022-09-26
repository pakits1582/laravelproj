<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConfigScheduleRequest;
use App\Http\Requests\UpdateConfigurationRequest;
use App\Libs\Helpers;
use App\Models\Configuration;
use App\Models\ConfigurationSchedule;
use App\Models\Period;
use App\Services\ConfigurationService;

class ConfigurationController extends Controller
{
    protected $configService;

    public function __construct(ConfigurationService $configService)
    {
        $this->configService = $configService;
        Helpers::setLoad(['jquery_configuration.js']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $periods = Period::with('terminfo')->where('display', 1)->orderBy('year', 'DESC')->get();
        $configuration = Configuration::take(1)->first();
        $configgrouped = ConfigurationSchedule::with(['collegeinfo', 'level'])->where('period_id', session('current_period'))->get()->groupBy('type');

        return view('configuration.index', compact('periods', 'configuration', 'configgrouped'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreConfigScheduleRequest $request)
    {
        $insert = ConfigurationSchedule::firstOrCreate([
            'type' => $request->type,
            'educational_level_id' => $request->educational_level,
            'college_id' => $request->college,
            'year' => $request->year,
            'period_id' => session('current_period'), //change to session setup
        ], $request->validated());

        if ($insert->wasRecentlyCreated) {
            return back()->with(['alert-class' => 'alert-success', 'sched_message' => 'Configuration schedule sucessfully added!']);
        }

        return back()->with(['alert-class' => 'alert-danger', 'sched_message' => 'Duplicate entry, configuration schedule already exists!'])->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Configuration  $configuration
     * @return \Illuminate\Http\Response
     */
    public function show(Configuration $configuration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Configuration  $configuration
     * @return \Illuminate\Http\Response
     */
    public function edit(Configuration $configuration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Configuration  $configuration
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateConfigurationRequest $request, $configuration = '')
    {
        $this->configService->updateConfiguration($request, $configuration);

        return back()->with(['alert-class' => 'alert-success', 'message' => 'Configuration sucessfully updated!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Configuration  $configuration
     * @return \Illuminate\Http\Response
     */
    public function applicationaction(Configuration $configuration, $action)
    {
        $status = ($action == 'open') ? 1 : 0;
        $configuration->update(['status' => $status]);

        return response()->json(['success' => true, 'alert' => 'alert-success', 'message' => 'Application action successfully excuted!']);
    }

    public function destroy(ConfigurationSchedule $configsched)
    {
        $configsched->delete();

        return response()->json(['success' => true, 'alert' => 'alert-success', 'message' => 'Configuration schedule successfully deleted!']);
    }
}
