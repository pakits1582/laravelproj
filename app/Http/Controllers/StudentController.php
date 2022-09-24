<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Libs\Helpers;
use App\Models\Instructor;
use App\Models\Useraccess;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\StudentService;
use App\Exports\InstructorsExport;
use App\Imports\InstructorsImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Http\Requests\StoreInstructorRequest;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateInstructorRequest;
use App\Models\Student;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;

        Helpers::setLoad(['jquery_student.js']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $students = $this->studentService->returnStudents($request);

        if($request->ajax()){
            return view('student.return_students', compact('students'));
        }
        return view('student.index', compact('students'));
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('student.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentRequest $request)
    {
        $alertCLass = 'alert-success';
        $alertMessage = 'Student sucessfully added!';

        try {
            DB::beginTransaction();

            //INSERT TO USERS TABLE
            $user = User::create([
                'idno' => $request->idno,
                'password' => Hash::make('password'),
                'utype' => 2,
            ]);
            //INSERT TO INSTRUCTOR TABLE
            Student::firstOrCreate([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'name_suffix' => $request->name_suffix,
            ], array_merge($request->validated(), ['user_id' => $user->id])
            );
            
            $accesses = $this->studentService->returnStudentAccesses();
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

    public function import(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            try {
                $import = new InstructorsImport;
                $import->import($file);

                $errors = $import->failures();
                return back()->with(['alert-class' => 'alert-success', 'message' => 'Sucessfully imported!', 'errors' => $errors]);
    
            } catch (\Exception $e) {
                return back()->with(['alert-class' => 'alert-danger', 'message' => 'Something went wrong! Import unsuccessful!']);
            }
        }
    }

    public function export(Request $request)
    {
        $import = new InstructorsExport();
        
        return $import->download('instructors.xlsx');
    }

    public function generatepdf(Request $request)
    {
        $instructors = $this->instructorService->returnInstructors($request, true);

        $pdf = PDF::loadView('instructor.generatepdf', ['instructors' => $instructors]);
        return $pdf->stream('instructors.pdf');
    }

}
