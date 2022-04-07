<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;

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

Route::redirect('/', 'login');

Auth::routes([
    'register' => false
]);

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [AttendanceController::class, 'index'])->name('home');    
    Route::get('/generateurl', [AttendanceController::class, 'generateUrl'])->name('generateurl');
    Route::get('/attendance/{id}', [AttendanceController::class, 'attendance'])->name('attendance');
    Route::post('/attendance/{id}', [AttendanceController::class, 'attendanceStore']);
    
    Route::post('/delete', [AttendanceController::class, 'fileDelete'])->name('delete');
});
