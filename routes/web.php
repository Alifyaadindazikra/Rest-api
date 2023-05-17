<?php

use App\Http\Controllers\PatientController;
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
    return view('welcome');
});

Route::get('/patients', [PatientController::class, 'index']);
Route::post('/patients/tambah-data', [PatientController::class, 'store']);
Route::get('/generate-token', [PatientController::class, 'createToken']);
Route::get('/patients/{id}', [PatientController::class, 'show']);
Route::patch('/patients/update/{id}', [PatientController::class, 'update']);
Route::delete('/patients/delete/{id}', [PatientController::class, 'destroy']);
Route::get('/patients/show/trash',[PatientController::class,'trash']);
Route::get('/patients/trash/restore/{id}', [PatientController::class, 'restore']);
Route::get('/patients/trash/delete/permanent/{id}',[PatientController::class, 'permanentDelete']);