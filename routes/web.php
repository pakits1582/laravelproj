<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\{
    LoginController,
    PeriodController,
    SchoolController,
    CollegeController,
    ProgramController,
    SectionController,
    SubjectController,
    DepartmentController,
    InstructorController,
    RoomController,
    UserController,
    ConfigurationController,
};

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

Route::group(['middleware' => ['auth']], function () 
{
    Route::resource('schools', SchoolController::class)->except(['show', 'destroy'])->middleware(['inaccess:schools'])
        ->missing(function (Request $request) {
            return Redirect::route('schools.index');
    });

    Route::resource('users', UserController::class)->except(['show', 'destroy'])->middleware(['inaccess:users'])
        ->missing(function (Request $request) {
            return Redirect::route('users.index');
    });

    Route::resource('instructors', InstructorController::class)->except(['show', 'destroy'])->middleware(['inaccess:instructors'])
        ->missing(function (Request $request) {
            return Redirect::route('instructors.index');
    });

    Route::resource('colleges', CollegeController::class)->except(['show', 'destroy'])->middleware(['inaccess:colleges'])
        ->missing(function (Request $request) {
            return Redirect::route('colleges.index');
    });

    Route::resource('departments', DepartmentController::class)->except(['show', 'destroy'])->middleware(['inaccess:departments'])
        ->missing(function (Request $request) {
            return Redirect::route('departments.index');
    });
    
    Route::view('/periods/addterm', 'period.addterm')->middleware(['inaccess:periods']);
    Route::post('/periods/saveterm', [PeriodController::class, 'storeterm'])->name('saveterm')->middleware(['inaccess:periods']);
    Route::resource('periods', PeriodController::class)->except(['show', 'destroy'])->middleware(['inaccess:periods'])
        ->missing(function (Request $request) {
            return Redirect::route('periods.index');
    });
    
    Route::group(['middleware' => ['inaccess:programs']], function () 
    {
        Route::view('/programs/addnewlevel', 'program.addnewlevel');
        Route::post('/programs/savelevel', [ProgramController::class, 'storelevel'])->name('savelevel');
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
    
    Route::resource('subjects', SubjectController::class)->except(['show', 'destroy'])->middleware(['inaccess:subjects'])
            ->missing(function (Request $request) {
                return Redirect::route('subjects.index');
            });
    Route::group(['middleware' => ['inaccess:configurations']], function () 
    {
        Route::get('/configurations', [ConfigurationController::class, 'index'])->name('configurations.index');
        Route::put('/configurations/{configuration?}', [ConfigurationController::class, 'update'])->name('configurations.update');
        Route::post('/configurations/{configuration}/applicationaction/{action}', [ConfigurationController::class, 'applicationaction']);
        Route::post('/configurations', [ConfigurationController::class, 'store'])->name('configurations.store');

        Route::delete('/configurations/{configsched}', [ConfigurationController::class, 'destroy']);
    });

    Route::get('/home', [LoginController::class, 'home'])->name('home');
});

