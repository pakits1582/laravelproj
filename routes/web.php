<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\PostchargeController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\ExternalGradeController;
use App\Http\Controllers\GradingSystemController;
use App\Http\Controllers\InternalGradeController;
use App\Http\Controllers\StudentledgerController;
use App\Http\Controllers\PaymentScheduleController;
use App\Http\Controllers\StudentadjustmentController;
use App\Http\Controllers\ScholarshipdiscountController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('auth.index');
// });

Route::get('/', [LoginController::class, 'index'])->name('loginindex');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/changepassword', [LoginController::class, 'changepassword'])->name('changepassword');
Route::post('/savechangepassword', [LoginController::class, 'savechangepassword'])->name('savechangepassword');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('schools', SchoolController::class)->except(['show', 'destroy'])->middleware(['inaccess:schools'])
        ->missing(function (Request $request) {
            return Redirect::route('schools.index');
        });

    
    Route::group(['middleware' => ['inaccess:users']], function () {
        // Route::view('/instructors/import', 'instructor.import')->name('instructors.import');
        // Route::post('/instructors/import', [InstructorController::class, 'import'])->name('instructors.uploadimport');
        // Route::post('/instructors/export', [InstructorController::class, 'export'])->name('instructors.downloadexcel');
        // Route::post('/instructors/generatepdf', [InstructorController::class, 'generatepdf'])->name('instructors.generatepdf');
        Route::post('/users/{user}/useraction/{action}', [UserController::class, 'useraction']);
        Route::resource('users', UserController::class)->except(['show', 'destroy'])->missing(function (Request $request) {
            return Redirect::route('users.index');
        });
    });

    Route::group(['middleware' => ['inaccess:instructors']], function () {
        Route::view('/instructors/import', 'instructor.import')->name('instructors.import');
        Route::post('/instructors/import', [InstructorController::class, 'import'])->name('instructors.uploadimport');
        Route::post('/instructors/export', [InstructorController::class, 'export'])->name('instructors.downloadexcel');
        Route::post('/instructors/generatepdf', [InstructorController::class, 'generatepdf'])->name('instructors.generatepdf');
        Route::resource('instructors', InstructorController::class)->except(['show', 'destroy'])->missing(function (Request $request) {
            return Redirect::route('instructors.index');
        });
    });

    Route::get('/students/dropdownselectsearch', [StudentController::class, 'dropdownselectsearch']);
    Route::group(['middleware' => ['inaccess:students']], function () {
        // Route::view('/students/import', 'student.import')->name('students.import');
        // Route::post('/students/import', [StudentController::class, 'import'])->name('students.uploadimport');
        // Route::post('/students/export', [StudentController::class, 'export'])->name('students.downloadexcel');
        // Route::post('/students/generatepdf', [StudentController::class, 'generatepdf'])->name('students.generatepdf');
        Route::resource('students', StudentController::class)->except(['destroy']);
        // ->missing(function (Request $request) {
        //     return Redirect::route('students.index');
        // });
    });

    Route::resource('colleges', CollegeController::class)->except(['show', 'destroy'])->middleware(['inaccess:colleges'])
        ->missing(function (Request $request) {
            return Redirect::route('colleges.index');
        });
    
    Route::resource('departments', DepartmentController::class)->except(['show', 'destroy'])->middleware(['inaccess:departments'])
        ->missing(function (Request $request) {
            return Redirect::route('departments.index');
        });

    Route::get('/periods/changeperiod', [PeriodController::class, 'changeperiod']);
    Route::post('/periods/saveperiod', [PeriodController::class, 'saveperiod'])->name('saveperiod');
    Route::view('/periods/addterm', 'period.addterm')->middleware(['inaccess:periods']);
    Route::post('/periods/saveterm', [PeriodController::class, 'storeterm'])->name('saveterm')->middleware(['inaccess:periods']);
    Route::resource('periods', PeriodController::class)->except(['show', 'destroy'])->middleware(['inaccess:periods'])
        ->missing(function (Request $request) {
            return Redirect::route('periods.index');
        });

    Route::group(['middleware' => ['inaccess:programs']], function () {
        Route::view('/programs/addnewlevel', 'program.addnewlevel');
        Route::post('/programs/savelevel', [ProgramController::class, 'storelevel'])->name('savelevel');
        Route::view('/programs/import', 'program.import')->name('programs.import');
        Route::post('/programs/import', [ProgramController::class, 'import'])->name('programs.uploadimport');
        Route::post('/programs/export', [ProgramController::class, 'export'])->name('programs.downloadexcel');
        Route::post('/programs/generatepdf', [ProgramController::class, 'generatepdf'])->name('programs.generatepdf');
        
        Route::resource('programs', ProgramController::class)->except(['destroy'])->missing(function (Request $request) {
            return Redirect::route('programs.index');
        });
    });

    Route::resource('rooms', RoomController::class)->except(['show', 'destroy'])->middleware(['inaccess:rooms'])
            ->missing(function (Request $request) {
                return Redirect::route('rooms.index');
            });

    Route::resource('sections', SectionController::class)->except(['destroy'])->middleware(['inaccess:sections'])
            ->missing(function (Request $request) {
                return Redirect::route('sections.index');
            });
    Route::post('/sections/getsections', [SectionController::class, 'getsections']);

    Route::get('/subjects/dropdownselectsearch', [SubjectController::class, 'dropdownselectsearch']);
    Route::group(['middleware' => ['inaccess:subjects']], function () {
            Route::view('/subjects/import', 'subject.import')->name('subjects.import');
            Route::post('/subjects/import', [SubjectController::class, 'import'])->name('subjects.uploadimport');
            Route::post('/subjects/export', [SubjectController::class, 'export'])->name('subjects.downloadexcel');
            Route::post('/subjects/generatepdf', [SubjectController::class, 'generatepdf'])->name('subjects.generatepdf');
            Route::resource('subjects', SubjectController::class)->except(['show', 'destroy'])->missing(function (Request $request) {
                return Redirect::route('subjects.index');
            });
    });

    Route::group(['middleware' => ['inaccess:configurations']], function () {
        Route::get('/configurations', [ConfigurationController::class, 'index'])->name('configurations.index');
        Route::put('/configurations/{configuration?}', [ConfigurationController::class, 'update'])->name('configurations.update');
        Route::post('/configurations/{configuration}/applicationaction/{action}', [ConfigurationController::class, 'applicationaction']);
        Route::post('/configurations', [ConfigurationController::class, 'store'])->name('configurations.store');

        Route::delete('/configurations/{configsched}', [ConfigurationController::class, 'destroy']);
    });

    Route::group(['middleware' => ['inaccess:curriculum']], function () {
        Route::group(['middleware' => ['writeability:curriculum']], function () {
            Route::get('/curriculum/{program}/addnewcurriculum', [CurriculumController::class, 'addnewcurriculum']);
            Route::post('/curriculum/savecurriculum', [CurriculumController::class, 'storecurriculum'])->name('curriculum.savecurriculum');
            Route::post('/curriculum/searchsubject', [CurriculumController::class, 'searchsubject']);
            Route::post('/curriculum/storesubjects', [CurriculumController::class, 'storesubjects'])->name('curriculum.storesubjects');
            Route::get('/curriculum/{program}', [CurriculumController::class, 'manage'])->name('curriculum.manage');
            Route::get('/curriculum/managecurriculumsubject/{curriculum_subject}', [CurriculumController::class, 'managecurriculumsubject']);
            Route::post('/curriculum/searchcurriculumsubjects', [CurriculumController::class, 'searchcurriculumsubjects']);
            Route::post('/curriculum/storemanagecurriculumsubject', [CurriculumController::class, 'storemanagecurriculumsubject'])->name('curriculum.storemanagecurriculumsubject');
            Route::delete('/curriculum/deleteitem/{id}/table/{table}', [CurriculumController::class, 'deleteitem']);
            Route::post('/curriculum/returncurriculumsubject', [CurriculumController::class, 'returncurriculumsubject']);
        });

        Route::post('/curriculum/returncurricula', [CurriculumController::class, 'returncurricula']);
        Route::get('/curriculum/{program}/curriculum/{curriculum}', [CurriculumController::class, 'viewcurriculum'])->middleware(['readability:curriculum'])->name('curriculum.viewcurriculum');
        Route::resource('curriculum', CurriculumController::class)->except(['show', 'destroy'])->missing(function (Request $request) {
            return Redirect::route('curriculum.index');
        });
    });

    Route::group(['middleware' => ['inaccess:classes']], function () {
        // Route::group(['middleware' => ['writeability:curriculum']], function () {
        
        Route::post('/classes/filtercurriculumsubjects', [ClassesController::class, 'filtercurriculumsubjects']);
        Route::post('/classes/sectionclasssubjects', [ClassesController::class, 'sectionclasssubjects']);
        Route::post('/classes/checkroomschedule', [ClassesController::class, 'checkroomschedule']);
        Route::post('/classes/checkconflicts', [ClassesController::class, 'checkconflicts']);
        Route::post('/classes/storecopyclass', [ClassesController::class, 'storecopyclass'])->name('classes.storecopyclass');
        Route::get('/classes/generatecode', [ClassesController::class, 'generatecode']);
        Route::post('/classes/merge', [ClassesController::class, 'merge']);
        // });
        Route::post('/classes/searchcodetomerge', [ClassesController::class, 'searchcodetomerge']);
        Route::post('/classes/savemerge', [ClassesController::class, 'savemerge'])->name('classes.savemerge');
        Route::post('/classes/unmergesubject', [ClassesController::class, 'unmergesubject']);
        Route::get('/classes/{class}/viewmergedclasses', [ClassesController::class, 'viewmergedclasses']);
        Route::get('/classes/{class}/displayenrolled', [ClassesController::class, 'displayenrolled']);
        Route::get('/classes/{section}/addclassoffering', [ClassesController::class, 'addclassoffering']);
        Route::get('/classes/{section}/copyclass', [ClassesController::class, 'copyclass']);

        Route::resource('classes', ClassesController::class)->missing(function (Request $request) {
            return Redirect::route('classes.index');
        });

        
    });

    Route::group(['middleware' => ['inaccess:enrolments']], function () {
        // Route::group(['middleware' => ['writeability:curriculum']], function () {
        

        Route::post('/enrolments/studentenrollmentunitsallowed', [EnrollmentController::class, 'studentenrollmentunitsallowed']);
        Route::post('/enrolments/checksectionslot', [EnrollmentController::class, 'checksectionslot']);
        Route::post('/enrolments/enrollsection', [EnrollmentController::class, 'enrollsection']);
        Route::post('/enrolments/enrollclasssubjects', [EnrollmentController::class, 'enrollclasssubjects']);
        Route::post('/enrolments/enrolledclasssubjects', [EnrollmentController::class, 'enrolledclasssubjects']);

        // });
       
        Route::post('/enrolments/getstudent', [EnrollmentController::class, 'getstudent']);
        Route::post('/enrolments/enrolmentinfo', [EnrollmentController::class, 'enrolmentinfo']);
        Route::delete('/enrolments/deleteenrolledsubjects', [EnrollmentController::class, 'deleteenrolledsubjects']);
        Route::get('/enrolments/searchandaddclasses', [EnrollmentController::class, 'searchandaddclasses']);
        Route::post('/enrolments/searchclasssubject', [EnrollmentController::class, 'searchclasssubject']);
        Route::post('/enrolments/searchclasssubjectbysection', [EnrollmentController::class, 'searchclasssubjectbysection']);
        Route::post('/enrolments/addselectedclasses', [EnrollmentController::class, 'addselectedclasses']);
        //Route::post('/enrolments/{enrollment}/saveenrollment', [EnrollmentController::class, 'saveenrollment']);

        Route::get('/enrolments/{student}/{period?}', [EnrollmentController::class, 'show']);


        Route::resource('enrolments', EnrollmentController::class)->missing(function (Request $request) {
            return Redirect::route('enrolments.index');
        });
    });

    Route::group(['middleware' => ['inaccess:assessments']], function () {
        // Route::group(['middleware' => ['writeability:curriculum']], function () {
        

        // Route::post('/enrolments/checksectionslot', [EnrollmentController::class, 'checksectionslot']);
        // Route::post('/enrolments/enrollsection', [EnrollmentController::class, 'enrollsection']);
        // Route::post('/enrolments/enrollclasssubjects', [EnrollmentController::class, 'enrollclasssubjects']);
        // Route::post('/enrolments/enrolledclasssubjects', [EnrollmentController::class, 'enrolledclasssubjects']);

        // // });
       
        // Route::post('/enrolments/getstudent', [EnrollmentController::class, 'getstudent']);
        // Route::post('/enrolments/enrolmentinfo', [EnrollmentController::class, 'enrolmentinfo']);
        // Route::delete('/enrolments/deleteenrolledsubjects', [EnrollmentController::class, 'deleteenrolledsubjects']);
        Route::get('/assessments/printassessment/{assessment}', [AssessmentController::class, 'printassessment']);
        // Route::post('/enrolments/searchclasssubject', [EnrollmentController::class, 'searchclasssubject']);
        // Route::post('/enrolments/searchclasssubjectbysection', [EnrollmentController::class, 'searchclasssubjectbysection']);
        // Route::post('/enrolments/addselectedclasses', [EnrollmentController::class, 'addselectedclasses']);
        // Route::post('/enrolments/{enrollment}/saveenrollment', [EnrollmentController::class, 'saveenrollment']);


        Route::resource('assessments', AssessmentController::class)->missing(function (Request $request) {
            return Redirect::route('assessments.index');
        });
    });
    
    Route::group(['middleware' => ['inaccess:validations']], function () {
        // Route::group(['middleware' => ['writeability:curriculum']], function () {
        
        Route::get('/validations/{enrollment}/unvalidate', [ValidationController::class, 'unvalidate']);
        Route::resource('validations', ValidationController::class)->missing(function (Request $request) {
            return Redirect::route('validations.index');
        });
    });

    Route::group(['middleware' => ['inaccess:studentledgers']], function () {
        // Route::group(['middleware' => ['writeability:curriculum']], function () {
        

        // Route::post('/enrolments/checksectionslot', [EnrollmentController::class, 'checksectionslot']);
        // Route::post('/enrolments/enrollsection', [EnrollmentController::class, 'enrollsection']);
        // Route::post('/enrolments/enrollclasssubjects', [EnrollmentController::class, 'enrollclasssubjects']);
        // Route::post('/enrolments/enrolledclasssubjects', [EnrollmentController::class, 'enrolledclasssubjects']);

        // // });
       
        // Route::post('/enrolments/getstudent', [EnrollmentController::class, 'getstudent']);
        // Route::post('/enrolments/enrolmentinfo', [EnrollmentController::class, 'enrolmentinfo']);
        // Route::delete('/enrolments/deleteenrolledsubjects', [EnrollmentController::class, 'deleteenrolledsubjects']);
        //Route::get('/assessments/printassessment/{assessment}', [AssessmentController::class, 'printassessment']);
        Route::post('/studentledgers/computepaymentsched', [StudentledgerController::class, 'computepaymentsched']);
        Route::post('/studentledgers/defaultpayperiod', [StudentledgerController::class, 'defaultpayperiod']);
        Route::post('/studentledgers/statementofaccounts', [StudentledgerController::class, 'statementofaccounts']);
        Route::post('/studentledgers/previousbalancerefund', [StudentledgerController::class, 'previousbalancerefund']);
        Route::post('/studentledgers/paymentschedules', [StudentledgerController::class, 'paymentschedules']);
        
        //Route::get('/validations/{enrollment}/unvalidate', [ValidationController::class, 'unvalidate']);
        Route::resource('studentledgers', StudentledgerController::class)->missing(function (Request $request) {
            return Redirect::route('studentledgers.index');
        });
    });

    Route::group(['middleware' => ['inaccess:scholarshipdiscounts']], function () {
        // Route::group(['middleware' => ['writeability:curriculum']], function () {
        

        // Route::post('/enrolments/checksectionslot', [EnrollmentController::class, 'checksectionslot']);
        // Route::post('/enrolments/enrollsection', [EnrollmentController::class, 'enrollsection']);
        // Route::post('/enrolments/enrollclasssubjects', [EnrollmentController::class, 'enrollclasssubjects']);
        // Route::post('/enrolments/enrolledclasssubjects', [EnrollmentController::class, 'enrolledclasssubjects']);

        // // });
       
        // Route::post('/enrolments/getstudent', [EnrollmentController::class, 'getstudent']);
        // Route::post('/enrolments/enrolmentinfo', [EnrollmentController::class, 'enrolmentinfo']);
        // Route::delete('/enrolments/deleteenrolledsubjects', [EnrollmentController::class, 'deleteenrolledsubjects']);
        //Route::get('/assessments/printassessment/{assessment}', [AssessmentController::class, 'printassessment']);
        // Route::post('/enrolments/searchclasssubject', [EnrollmentController::class, 'searchclasssubject']);
        // Route::post('/enrolments/searchclasssubjectbysection', [EnrollmentController::class, 'searchclasssubjectbysection']);
        //Route::post('/studentledgers/statementofaccounts', [StudentledgerController::class, 'statementofaccounts']);
        Route::delete('/scholarshipdiscounts/{scholarshipdiscountgrant}/deletegrant', [ScholarshipdiscountController::class, 'deletegrant']);
        Route::get('/scholarshipdiscounts/grant', [ScholarshipdiscountController::class, 'grant']);
        Route::post('/scholarshipdiscounts/scholarshipdiscountgrants', [ScholarshipdiscountController::class, 'scholarshipdiscountgrants']);
        Route::post('/scholarshipdiscounts/savegrant', [ScholarshipdiscountController::class, 'savegrant']);
        Route::resource('scholarshipdiscounts', ScholarshipdiscountController::class)->missing(function (Request $request) {
            return Redirect::route('scholarshipdiscounts.index');
        });
    });

    Route::group(['middleware' => ['inaccess:studentadjustments']], function () {
        // Route::group(['middleware' => ['writeability:curriculum']], function () {
        
        // Route::delete('/studentadjusments/{scholarshipdiscountgrant}/deletegrant', [StudentadjustmentController::class, 'deletegrant']);
        // Route::get('/studentadjusments/grant', [StudentadjustmentController::class, 'grant']);
        Route::post('/studentadjustments/studentadjustments', [StudentadjustmentController::class, 'studentadjustments']);
        // Route::post('/studentadjusments/savegrant', [StudentadjustmentController::class, 'savegrant']);
        Route::resource('studentadjustments', StudentadjustmentController::class)->missing(function (Request $request) {
            return Redirect::route('studentadjusments.index');
        });
    });

    Route::group(['middleware' => ['inaccess:postcharges']], function () {
        // Route::group(['middleware' => ['writeability:curriculum']], function () {
        
        
        // Route::get('/studentadjusments/grant', [StudentadjustmentController::class, 'grant']);
        Route::post('/postcharges/removecharged', [PostchargeController::class, 'destroy']);
        Route::post('/postcharges/filterstudents', [PostchargeController::class, 'filterstudents']);
        Route::post('/postcharges/chargedstudents', [PostchargeController::class, 'chargedstudents']);
        Route::resource('postcharges', PostchargeController::class)->missing(function (Request $request) {
            return Redirect::route('postcharges.index');
        });
    });

    Route::group(['middleware' => ['inaccess:paymentschedules']], function () {
        // Route::group(['middleware' => ['writeability:curriculum']], function () {
        
        Route::view('/paymentschedules/addpaymentmode', 'paymentschedule.addpaymentmode');
        Route::post('/paymentschedules/savepaymentmode', [PaymentScheduleController::class, 'storepaymentmode'])->name('savepaymentmode');
        Route::get('/paymentschedules/{period_id}/returnpaymentschedules', [PaymentScheduleController::class, 'returnpaymentschedules']);


        Route::resource('paymentschedules', PaymentScheduleController::class)->missing(function (Request $request) {
            return Redirect::route('paymentschedules.index');
        });
    });

    Route::group(['middleware' => ['inaccess:fees']], function () {
        // Route::view('/subjects/import', 'subject.import')->name('subjects.import');
        // Route::post('/subjects/import', [SubjectController::class, 'import'])->name('subjects.uploadimport');
        // Route::post('/subjects/export', [SubjectController::class, 'export'])->name('subjects.downloadexcel');
        // Route::post('/subjects/generatepdf', [SubjectController::class, 'generatepdf'])->name('subjects.generatepdf');4
        Route::view('/fees/addnewtype', 'fee.addnewtype');
        Route::get('/fees/compoundfee', [FeeController::class, 'compoundfee']);
        Route::post('/fees/savetype', [FeeController::class, 'storetype'])->name('savetype');
        Route::resource('fees', FeeController::class)->missing(function (Request $request) {
            return Redirect::route('fees.index');
        });
    });

    Route::group(['middleware' => ['inaccess:fees/setup']], function () {
        // Route::view('/subjects/import', 'subject.import')->name('subjects.import');
        // Route::post('/subjects/import', [SubjectController::class, 'import'])->name('subjects.uploadimport');
        // Route::post('/subjects/export', [SubjectController::class, 'export'])->name('subjects.downloadexcel');
        // Route::post('/subjects/generatepdf', [SubjectController::class, 'generatepdf'])->name('subjects.generatepdf');4
        // Route::view('/fees/addnewtype', 'fee.addnewtype');
        // Route::get('/fees/compoundfee', [FeeController::class, 'compoundfee']);
        Route::get('/fees/{period}/editsetupfee', [FeeController::class, 'editsetupfee']);
        Route::delete('/fees/{setupfee}/delete', [FeeController::class, 'deletefeessetup']);
        Route::get('/fees/{period_id}/returnfeessetup', [FeeController::class, 'returnfeessetup']);
        Route::put('/fees/{setupfee}/updatesetupfee', [FeeController::class, 'updatesetupfee']);
        Route::post('/fees/savesetupfee', [FeeController::class, 'storesetupfee'])->name('storesetupfee');
        Route::post('/fees/savecopyfees', [FeeController::class, 'savecopyfees'])->name('savecopyfees');
        Route::get('/fees/{period}/copysetup', [FeeController::class, 'copysetup']);
        Route::get('/fees/setup', [FeeController::class, 'setupfees']);
        
    });

    Route::group(['middleware' => ['inaccess:gradingsystems']], function () {
        // Route::view('/subjects/import', 'subject.import')->name('subjects.import');
        // Route::post('/subjects/import', [SubjectController::class, 'import'])->name('subjects.uploadimport');
        // Route::post('/subjects/export', [SubjectController::class, 'export'])->name('subjects.downloadexcel');
        // Route::post('/subjects/generatepdf', [SubjectController::class, 'generatepdf'])->name('subjects.generatepdf');4
        Route::view('/gradingsystems/addnewremark', 'gradingsystem.addnewremark');
        Route::post('/gradingsystems/saveremark', [GradingSystemController::class, 'storeremark'])->name('saveremark');
        Route::resource('gradingsystems', GradingSystemController::class)->except(['show', 'destroy'])->missing(function (Request $request) {
            return Redirect::route('gradingsystems.index');
        });
    });

    Route::group(['middleware' => ['inaccess:grades']], function () {
        // Route::view('/subjects/import', 'subject.import')->name('subjects.import');
        // Route::post('/subjects/import', [SubjectController::class, 'import'])->name('subjects.uploadimport');
        // Route::post('/subjects/export', [SubjectController::class, 'export'])->name('subjects.downloadexcel');
        // Route::post('/subjects/generatepdf', [SubjectController::class, 'generatepdf'])->name('subjects.generatepdf');4
        //Route::view('/gradeinternals/addnewremark', 'gradingsystem.addnewremark');
        Route::get('/grades/{student}/{origin}', [GradeController::class, 'getgradeinfobystudentandperiod']);
        Route::resource('grades', GradeController::class)->missing(function (Request $request) {
            return Redirect::route('grades.index');
        });
    });

    Route::group(['middleware' => ['inaccess:gradeinternals']], function () {
        // Route::view('/subjects/import', 'subject.import')->name('subjects.import');
        // Route::post('/subjects/import', [SubjectController::class, 'import'])->name('subjects.uploadimport');
        // Route::post('/subjects/export', [SubjectController::class, 'export'])->name('subjects.downloadexcel');
        // Route::post('/subjects/generatepdf', [SubjectController::class, 'generatepdf'])->name('subjects.generatepdf');4
        //Route::view('/gradeinternals/addnewremark', 'gradingsystem.addnewremark');
        //Route::post('/gradeinternals/saveremark', [GradingSystemController::class, 'storeremark'])->name('saveremark');
        Route::resource('gradeinternals', InternalGradeController::class)->missing(function (Request $request) {
            return Redirect::route('gradeinternals.index');
        });
    });

    Route::group(['middleware' => ['inaccess:gradeexternals']], function () {
        // Route::view('/subjects/import', 'subject.import')->name('subjects.import');
        // Route::post('/subjects/import', [SubjectController::class, 'import'])->name('subjects.uploadimport');
        // Route::post('/subjects/export', [SubjectController::class, 'export'])->name('subjects.downloadexcel');
        // Route::post('/subjects/generatepdf', [SubjectController::class, 'generatepdf'])->name('subjects.generatepdf');4
        //Route::view('/gradeinternals/addnewremark', 'gradingsystem.addnewremark');
        //Route::post('/gradeinternals/saveremark', [GradingSystemController::class, 'storeremark'])->name('saveremark');
        Route::get('/gradeexternals/externalgradesubjects/{grade_id}', [ExternalGradeController::class, 'externalgradesubjects']);

        Route::resource('gradeexternals', ExternalGradeController::class)->missing(function (Request $request) {
            return Redirect::route('gradeexternals.index');
        });
        Route::get('/gradeexternals/{student}/{period}', [ExternalGradeController::class, 'getallexternalgrade']);
        
    });

    Route::group(['middleware' => ['inaccess:evaluations']], function () {
        Route::post('/evaluations/taggrade', [EvaluationController::class, 'taggrade']);
        Route::resource('evaluations', EvaluationController::class)->missing(function (Request $request) {
            return Redirect::route('evaluations.index');
        }); 
    });

    Route::group(['middleware' => ['inaccess:receipts']], function () {
        Route::get('/receipts/printreceipt/{receipt_no}', [ReceiptController::class, 'printreceipt']);
        Route::post('/receipts/cancelreceipt', [ReceiptController::class, 'cancelreceipt']);
        Route::get('/receipts/addpaymentfee', [ReceiptController::class, 'addpaymentfee']);
        Route::view('/receipts/addbank', 'receipt.addbank');
        Route::post('/receipts/savebank', [ReceiptController::class, 'storebank'])->name('savebank');
        Route::resource('receipts', ReceiptController::class)->missing(function (Request $request) {
            return Redirect::route('receipts.index');
        }); 
    });

    Route::get('/home', [LoginController::class, 'home'])->name('home');
});
