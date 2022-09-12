<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstructorRequest;
use App\Http\Requests\UpdateInstructorRequest;
use App\Libs\Helpers;
use App\Models\Instructor;
use App\Models\User;
use App\Models\Useraccess;
use App\Services\InstructorService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class InstructorController extends Controller
{
    // protected $instructorService;

    // public function __construct(InstructorService $instructorService)
    // {
    //     $this->instructorService = $instructorService;
    //     Helpers::setLoad(['jquery_instructor.js']);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$instructors = Instructor::all();
        // $instructors->load('user', 'collegeinfo', 'deptinfo');
        $instructors = Instructor::with(['user', 'collegeinfo', 'deptinfo'])->get();

        //dd($instructors);

        return view('instructor.index', compact('instructors'));
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('instructor.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInstructorRequest $request)
    {
        $alertCLass = 'alert-success';
        $alertMessage = 'Instructor sucessfully added!';

        try {
            DB::beginTransaction();

            //INSERT TO USERS TABLE
            $user = User::create([
                'idno' => $request->idno,
                'password' => Hash::make('password'),
                'utype' => 1,
            ]);
            //INSERT TO INSTRUCTOR TABLE
            Instructor::firstOrCreate([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'name_suffix' => $request->name_suffix,
            ], array_merge($request->validated(), ['user_id' => $user->id])
            );
            //INSERT INSTRUCTOR'S DEFAULT ACCESS
            $instructorAccesses = [
                ['access' => 'facultyloads/facultyload', 'title' => 'Faculty Load', 'category' => 'Faculty Menu'],
                ['access' => 'classlist/facultyclasslist', 'title' => 'Faculty Class List', 'category' => 'Faculty Menu'],
                ['access' => 'instructors/profile', 'title' => 'Faculty Profile', 'category' => 'Faculty Menu'],
                ['access' => 'gradingsheets', 'title' => 'Grading Sheet', 'category' => 'Faculty Menu'],
                ['access' => 'gradegenerator', 'title' => 'Grade Generator', 'category' => 'Faculty Menu'],
            ];

            // //LOOP THROUGH ALL USERACCESS AND ADD TO USERS_ACCESS TABLE
            foreach ($instructorAccesses as $key => $access) {
                $accesses[] = new Useraccess([
                    'access' => $access['access'],
                    'title' => $access['title'],
                    'category' => $access['category'],
                ]);
            }
            $user->access()->saveMany($accesses);

            DB::commit();
        } catch (\Exception $e) {
            Log::error(get_called_class(), [
                //'createdBy' => $user->userLoggedinName(),
                'body' => $request->all(),
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);
        }

        return back()->with(['alert-class' => $alertCLass, 'message' => $alertMessage]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function show(Instructor $instructor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function edit(Instructor $instructor)
    {
        try {
            $instructor->load('user');

            return view('instructor.edit', compact('instructor'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('instructorindex');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInstructorRequest $request, Instructor $instructor)
    {
        $instructor->user->idno = $request->idno;
        $instructor->user->save();

        $instructor->update($request->validated());

        return back()->with(['alert-class' => 'alert-success', 'message' => 'Instructor sucessfully updated!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Instructor $instructor)
    {
        //
    }
}
