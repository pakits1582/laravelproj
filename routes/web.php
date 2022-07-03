<?php

use App\Http\Controllers\SchoolController;
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
    return view('index');
});

//Route::resource('schools', SchoolController::class);
Route::get('/schools', [SchoolController::class, 'index'])->name('schoolindex');

Route::get('/schools/create', [SchoolController::class, 'create'])->name('addnewschool');

Route::post('/schools', [SchoolController::class, 'store'])->name('saveschool');

Route::get('/schools/{school}/edit', [SchoolController::class, 'edit'])->name('editschool');

Route::put('/schools/{school}', [SchoolController::class, 'update'])->name('updateschool');

Route::delete('/schools/{school}', [SchoolController::class, 'destroy'])->name('deleteschool');

