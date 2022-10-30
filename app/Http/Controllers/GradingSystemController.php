<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGradingSystemRequest;
use App\Http\Requests\UpdateGradingSystemRequest;
use App\Libs\Helpers;
use App\Models\Fee;
use App\Models\Remark;
use Illuminate\Http\Request;
use App\Models\GradingSystem;
use App\Services\GradingSystemService;

class GradingSystemController extends Controller
{
    protected $gradingsystemService;

    public function __construct(GradingSystemService $gradingsystemService)
    {
        $this->gradingsystemService = $gradingsystemService;
        Helpers::setLoad(['jquery_gradingsystem.js', 'select2.full.min.js']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $query = GradingSystem::with(['level', 'remark'])->orderBy('code');

        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where(function($query) use($request){
                $query->where('code', 'like', '%'.$request->keyword.'%')
                ->orWhere('value', 'like', '%'.$request->keyword.'%');
            });
        }
        
        if($request->has('educational_level_id') && !empty($request->educational_level_id)) {
            $query->where('educational_level_id', $request->educational_level_id);
        }

        $gradingsystems =  $query->paginate(10); 

        if($request->ajax())
        {
            return view('gradingsystem.return_gradingsystems', compact('gradingsystems'));
        }

        return view('gradingsystem.index', compact('gradingsystems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $remarks = Remark::all();
        
        return view('gradingsystem.create', compact('remarks'));
    }

    public function storeremark(Request $request)
    {
        $validated = $request->validate([
            'remark' => 'required|unique:remarks|max:255',
        ]);

        $insert = Remark::firstOrCreate(['remark' => $request->remark], $validated);

        if ($insert->wasRecentlyCreated) {
            return response()->json([
                'success' => true,
                'message' => 'Remark successfully added!',
                'alert' => 'alert-success',
                'values' => $insert
            ], 200);
        }

        return response()->json(['success' => false, 'alert' => 'alert-danger', 'message' => 'Duplicate entry, remark type already exists!']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGradingSystemRequest $request)
    {
        $insert = GradingSystem::firstOrCreate([
            'educational_level_id' => $request->educational_level_id,
            'code' => $request->code,
            'value' => $request->value
        ], $request->validated());

        if ($insert->wasRecentlyCreated) {
            return back()->with(['alert-class' => 'alert-success', 'message' => 'Grade sucessfully added!'])->withInput();
        }

        return back()->with(['alert-class' => 'alert-danger', 'message' => 'Duplicate entry, grade already exists!'])->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GradingSystem  $gradingSystem
     * @return \Illuminate\Http\Response
     */
    public function show(GradingSystem $gradingSystem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GradingSystem  $gradingSystem
     * @return \Illuminate\Http\Response
     */
    public function edit(GradingSystem $gradingsystem)
    {
        $remarks = Remark::all();

        return view('gradingsystem.edit', compact('gradingsystem', 'remarks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GradingSystem  $gradingSystem
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGradingSystemRequest $request, GradingSystem $gradingsystem)
    {
        $gradingsystem->update($request->validated());

        return back()->with(['alert-class' => 'alert-success', 'message' => 'Grade sucessfully updated!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GradingSystem  $gradingSystem
     * @return \Illuminate\Http\Response
     */
    public function destroy(GradingSystem $gradingSystem)
    {
        //
    }
}
