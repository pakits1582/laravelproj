<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\ConfigurationController;

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

    Route::group(['middleware' => ['inaccess:students']], function () {
        // Route::view('/students/import', 'student.import')->name('students.import');
        // Route::post('/students/import', [StudentController::class, 'import'])->name('students.uploadimport');
        // Route::post('/students/export', [StudentController::class, 'export'])->name('students.downloadexcel');
        // Route::post('/students/generatepdf', [StudentController::class, 'generatepdf'])->name('students.generatepdf');
        Route::resource('students', StudentController::class)->except(['show', 'destroy'])->missing(function (Request $request) {
            return Redirect::route('students.index');
        });
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
        
        Route::resource('programs', ProgramController::class)->except(['show', 'destroy'])->missing(function (Request $request) {
            return Redirect::route('programs.index');
        });
    });

    Route::resource('rooms', RoomController::class)->except(['show', 'destroy'])->middleware(['inaccess:rooms'])
            ->missing(function (Request $request) {
                return Redirect::route('rooms.index');
            });

    Route::resource('sections', SectionController::class)->except(['show', 'destroy'])->middleware(['inaccess:sections'])
            ->missing(function (Request $request) {
                return Redirect::route('sections.index');
            });
    Route::post('/sections/getsections', [SectionController::class, 'getsections']);


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
        
        Route::post('/classes/storeclasssubject', [ClassesController::class, 'storeclasssubject'])->name('classes.storeclasssubject');
        Route::post('/classes/filtercurriculumsubjects', [ClassesController::class, 'filtercurriculumsubjects']);
        Route::post('/classes/sectionclasssubjects', [ClassesController::class, 'sectionclasssubjects']);
        Route::post('/classes/checkroomschedule', [ClassesController::class, 'checkroomschedule']);
        Route::post('/classes/checkconflicts', [ClassesController::class, 'checkconflicts']);
        Route::post('/classes/saveupdatedclasssubject/{class}', [ClassesController::class, 'saveupdatedclasssubject']);

        // });
       
        // Route::get('/curriculum/{program}/curriculum/{curriculum}', [CurriculumController::class, 'viewcurriculum'])->middleware(['readability:curriculum'])->name('curriculum.viewcurriculum');
        Route::get('/classes/{section}/addclassoffering', [ClassesController::class, 'addclassoffering']);
        Route::resource('classes', ClassesController::class)->missing(function (Request $request) {
            return Redirect::route('classes.index');
        });
    });

    Route::get('/home', [LoginController::class, 'home'])->name('home');
});
