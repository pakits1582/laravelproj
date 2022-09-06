<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Period;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Services\ConfigurationService;
use App\Http\Requests\UpdateConfigurationRequest;

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
        $periods = Period::where('display', 1)->orderBy('year', 'DESC')->get();
        $configuration = Configuration::take(1)->first();

        return view('configuration.index', compact('periods', 'configuration'));
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
    public function store(Request $request)
    {
        //
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
    public function update(UpdateConfigurationRequest $request, $configuration='')
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
}
