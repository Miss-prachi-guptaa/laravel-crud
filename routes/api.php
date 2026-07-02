<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;


    Route::post('/me/register-face', [AttendanceController::class, 'registerFace']);
    Route::get('/attendance/today', [AttendanceController::class, 'today']);
    Route::post('/attendance/mark-from-image', [AttendanceController::class, 'markAttendance']);

