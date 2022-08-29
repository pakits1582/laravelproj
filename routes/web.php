<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\InstructorController;

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

Route::get('/', function () {
    return view('auth.index');
});

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/changepassword', [LoginController::class, 'changepassword'])->name('changepassword');
Route::post('/savechangepassword', [LoginController::class, 'savechangepassword'])->name('savechangepassword');

Route::controller(SchoolController::class)->group(function () {
    Route::get('/schools', 'index')->name('schoolindex');
    Route::get('/schools/create', 'create')->name('addschool');
    Route::post('/schools', 'store')->name('saveschool');
    Route::get('/schools/{school}/edit', 'edit')->name('editschool');
    Route::put('/schools/{school}', 'update')->name('updateschool');
    Route::delete('/schools/{school}', 'destroy')->name('deleteschool');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index')->name('userindex');
    Route::get('/users/create', 'create')->name('adduser');
    Route::post('/users', 'store')->name('saveuser');
    Route::get('/users/{user}/edit', 'edit')->name('edituser');
    Route::put('/users/{user}', 'update')->name('updateuser');
    Route::delete('/users/{user}', 'destroy')->name('deleteuser');
});

Route::controller(InstructorController::class)->group(function () {
    Route::get('/instructors', 'index')->name('instructorindex');
    Route::get('/instructors/create', 'create')->name('addinstructor');
    Route::post('/instructors', 'store')->name('saveinstructor');
    Route::get('/instructors/{instructor}/edit', 'edit')->name('editinstructor');
    Route::put('/instructors/{instructor}', 'update')->name('updateinstructor');
    Route::delete('/instructors/{instructor}', 'destroy')->name('deleteinstructor');
});

Route::controller(CollegeController::class)->group(function () {
    Route::get('/colleges', 'index')->name('collegeindex');
    Route::get('/colleges/create', 'create')->name('addcollege');
    Route::post('/colleges', 'store')->name('savecollege');
    Route::get('/colleges/{college}/edit', 'edit')->name('editcollege');
    Route::put('/colleges/{college}', 'update')->name('updatecollege');
    Route::delete('/colleges/{college}', 'destroy')->name('deletecollege');
});

Route::controller(DepartmentController::class)->group(function () {
    Route::get('/departments', 'index')->name('departmentindex');
    Route::get('/departments/create', 'create')->name('adddepartment');
    Route::post('/departments', 'store')->name('savedepartment');
    Route::get('/departments/{department}/edit', 'edit')->name('editdepartment');
    Route::put('/departments/{department}', 'update')->name('updatedepartment');
    Route::delete('/departments/{department}', 'destroy')->name('deletedepartment');
});

Route::controller(PeriodController::class)->group(function () {
    Route::get('/periods', 'index')->name('periodindex');
    Route::get('/periods/create', 'create')->name('addperiod');
    Route::post('/periods', 'store')->name('saveperiod');
    Route::get('/periods/{period}/edit', 'edit')->name('editperiod');
    Route::put('/periods/{period}', 'update')->name('updateperiod');
    Route::delete('/periods/{period}', 'destroy')->name('deleteperiod');
    Route::view('/periods/addterm', 'period.addterm');
    Route::post('/periods/saveterm', 'storeterm')->name('saveterm');
});

Route::get('/home', function () {
    return view('home');
});
