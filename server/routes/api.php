<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentUploadController;
use App\Http\Controllers\AuthenticatedController;

Route::post('login', [AuthenticatedController::class, 'login']);
Route::post('/upload', [StudentUploadController::class, 'upload']);
Route::get('/students', [StudentUploadController::class, 'index']);
Route::get('/classes', [StudentUploadController::class, 'getAllClasses']);
Route::get('/students/class/{class}', [StudentUploadController::class, 'getStudentsByClass']);
