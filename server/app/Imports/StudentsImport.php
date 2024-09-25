<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentsImport implements ToModel, WithStartRow
{

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        // Skip the row if the name field is empty or null
        if (empty($row[0]) || is_null($row[0])) {
            return null;
        }

        // Skip if the record already exists
        if (Student::where('name', $row[0])->exists()) {
            return null;
        }

        return new Student([
            'name' => $row[0],
            'class' => $row[1],
            'level' => (int)$row[2],  // Cast level to integer
            'parent_contact' => $row[3],
        ]);
    }
}
