<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentUploadController;

Route::post('/upload', [StudentUploadController::class, 'upload']);
