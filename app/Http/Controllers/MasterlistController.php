<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Program;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use App\Services\ProgramService;
use App\Services\MasterlistService;

class MasterlistController extends Controller
{
    protected $masterlistService;

    public function __construct(MasterlistService $masterlistService)
    {
        $this->masterlistService = $masterlistService;

        Helpers::setLoad(['jquery_masterlist.js']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $periods = (new PeriodService())->returnAllPeriods(0, true, 1);
        $programs = Program::orderBy('code')->get();
        $masterlist = $this->masterlistService->masterList(session('current_period'));

        return view('masterlist.index', compact('periods', 'programs', 'masterlist'));
    }

    public function filtermasterlist(Request $request)
    {
        $masterlist = $this->masterlistService->masterList($request->period_id, $request->educational_level, $request->college, $request->program_id, $request->year_level, $request->status);

        return view('masterlist.return_masterlist', compact('masterlist'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
