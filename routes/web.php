<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

//Route::resource('schools', SchoolController::class);
Route::get('/schools', [SchoolController::class, 'index'])->name('schoolindex');
Route::get('/schools/create', [SchoolController::class, 'create'])->name('addschool');
Route::post('/schools', [SchoolController::class, 'store'])->name('saveschool');
Route::get('/schools/{school}/edit', [SchoolController::class, 'edit'])->name('editschool');
Route::put('/schools/{school}', [SchoolController::class, 'update'])->name('updateschool');
Route::delete('/schools/{school}', [SchoolController::class, 'destroy'])->name('deleteschool');

Route::get('/users', [UserController::class, 'index'])->name('userindex');
Route::get('/users/create', [UserController::class, 'create'])->name('adduser');
Route::post('/users', [UserController::class, 'store'])->name('saveuser');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('edituser');
Route::put('/users/{user}', [UserController::class, 'update'])->name('updateuser');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('deleteuser');

Route::get('/home', function () {
    return view('home');
});
