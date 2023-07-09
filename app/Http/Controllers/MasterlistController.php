<?php

namespace App\Http\Controllers;

use FPDF;
use App\Libs\Helpers;
use App\Models\Period;
use App\Models\Program;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Services\PeriodService;
use App\Services\ProgramService;
use App\Services\MasterlistService;


class MasterlistController extends Controller
{
    protected $masterlistService;

    public function __construct(MasterlistService $masterlistService)
    {
        $this->masterlistService = $masterlistService;

        Helpers::setLoad([
            'jquery_masterlist.js', 
            'select2.full.min.js',
            'datatables/dataTables.buttons.min.js',
            'datatables/pdfmake.min.js',
            'datatables/buttons.html5.min.js',
            'datatables/buttons.print.min.js',
            'datatables/jszip.min.js',
            'datatables/vfs_fonts.js',
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $periods = (new PeriodService())->returnAllPeriods(0, true, 1);
        $programs = (new ProgramService)->returnAllPrograms(0, true, true);
        $masterlist = $this->masterlistService->masterList(session('current_period'));

        return view('masterlist.index', compact('periods', 'programs', 'masterlist'));
    }

    public function filtermasterlist(Request $request)
    {
        $masterlist = $this->masterlistService->masterList($request->period_id, $request->educational_level, $request->college, $request->program_id, $request->year_level, $request->status);

        return view('masterlist.return_masterlist', compact('masterlist'));
    }

    public function printmasterlist(Request $request)
    {
        $masterlist = $this->masterlistService->masterList($request->period_id, $request->educational_level, $request->college, $request->program_id, $request->year_level, $request->status);
        $configuration = Configuration::take(1)->first();
        $period = Period::select('name')->find($request->period_id);
       
        switch ($request->status) {
			case '2':
				$display = 'All Students';
				break;
			case '1':
				$display = 'Validated/Paid';
				break;
			case '0':
				$display = 'Unpaid';
				break;
		}

        return view('masterlist.print_masterlist', compact('masterlist', 'configuration', 'period', 'display'));
    }
}
