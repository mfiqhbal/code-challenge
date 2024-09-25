<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;

class StudentUploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        // Import the Excel data
        Excel::import(new StudentsImport, $request->file('file'));

        return response()->json(['message' => 'Upload successful'], 200);
    }
}
