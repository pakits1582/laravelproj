<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Libs\Helpers;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Services\StudentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;

        Helpers::setLoad(['jquery_student.js', 'select2.full.min.js']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $students = $this->studentService->returnStudents($request);

        if($request->ajax())
        {
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
        try {
           $this->studentService->insertStudent($request);
           
        } catch (\Exception $e) {
            Log::error(get_called_class(), [
                //'createdBy' => $user->userLoggedinName(),
                'body' => $request->all(),
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);
        }

        return back()->with(['alert-class' => 'alert-success', 'message' => 'Student sucessfully added!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $data = $this->studentService->returnStudentInfo($request->student);
        
        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        try {
            $student->load('user');

            return view('student.edit', compact('student'));
        } catch (ModelNotFoundException $e) {

            return redirect()->route('studentindex');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->user->idno = $request->idno;
        $student->user->save();

        $student->update($request->validated());

        return back()->with(['alert-class' => 'alert-success', 'message' => 'Student sucessfully updated!']);
    }

    public function dropdownselectsearch(Request $request)
    {
        $data = $this->studentService->dropdownSelectSearch($request);

        return response()->json($data);
    }

    public function studentfullinfo(Request $request)
    {
        $student = $this->studentService->studentFullInformation($request);

        return response()->json($student);
    }

    public function generateidno(Request $request)
    {
        $generated_idno = $this->studentService->generateIdno($request);

        return response()->json($generated_idno);
    }

    public function studentswithnoaccess()
    {
        $result = $this->studentService->studentsWithNoAccess();

        return response()->json($result);
    }

    public function profile()
    {
        $student = (new StudentService)->studentInformationByUserId(Auth::id());
        $student->load(['academic_info', 'contact_info', 'personal_info']);
        $regions = json_decode(File::get(public_path('json/region.json')), true);
        $provinces = json_decode(File::get(public_path('json/province.json')), true);
        $cities = json_decode(File::get(public_path('json/city.json')), true);
        $barangays = json_decode(File::get(public_path('json/barangay.json')), true);

        return view('student.profile.index', compact('student', 'regions', 'provinces', 'cities', 'barangays'));
    }
}
