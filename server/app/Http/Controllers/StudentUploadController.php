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

        try {
            // Import the Excel data
            Excel::import(new StudentsImport, $request->file('file'));
            return response()->json(['message' => 'Upload successful'], 200);
        } catch (\Exception $e) {
            // Handle the exception
            return response()->json(['message' => 'Upload failed: ' . $e->getMessage()], 500);
        }
    }

    public function index()
    {
        $students = Student::all(); 
        return response()->json($students, 200);
    }

    public function getAllClasses()
    {
        $classes = Student::distinct()->pluck('class');
        return response()->json($classes, 200);
    }

    public function getStudentsByClass($class)
    {
        if ($class == 'all') {
            $students = Student::all();
        } else {
            $students = Student::where('class', $class)->get();
        }
        return response()->json($students, 200);
    }
}
