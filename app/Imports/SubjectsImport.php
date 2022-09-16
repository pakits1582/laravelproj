<?php

namespace App\Imports;

use App\Models\Subject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SubjectsImport implements
    ToModel, 
    WithHeadingRow, 
    WithValidation, 
    SkipsOnFailure, 
    WithBatchInserts, 
    WithChunkReading
{
    use Importable, SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Subject([
            'id' => $row['id'],
            'code' => $row['code'],
            'name' => $row['name'],
            'units' => $row['units'],
            'tfunits' => $row['tfunits'],
            'loadunits' => $row['loadunits'],
            'lecunits' => $row['lecunits'],
            'labunits' => $row['labunits'],
            'hours' => $row['hours'],
            'educational_level_id' => $row['level'],
            'college_id' => $row['college'],
            'professional' => $row['professional'],
            'laboratory' => $row['laboratory'],
            'gwa' => $row['gwa'],
            'nograde' => $row['nograde'],
            'notuition' => $row['notuition'],
            'exclusive' => $row['exclusive'],
        ]);
    }

    public function rules(): array
    {
        return [
            // '*.code' => 'unique:subjects,code|unique:programs,name',
            // '*.name' => 'unique:programs,name|unique:programs,code',
        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
