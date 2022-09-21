<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Instructor;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class InstructorsImport implements 
    ToCollection,
    WithHeadingRow, 
    WithValidation,
    SkipsOnError, 
    SkipsOnFailure, 
    WithChunkReading
{
    use Importable, SkipsFailures, SkipsErrors;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $user = User::create([
                'idno' => $row['idno'],
                'password' => Hash::make('password'),
                'utype' => 1,
                'is_active' => $row['is_active']
            ]);

            Instructor::create([
                'user_id' => $user->id,
                'name_prefix' => $row['name_prefix'],
                'last_name' => $row['last_name'],
                'first_name' => $row['first_name'],
                'middle_name' => $row['middle_name'],
                'name_suffix' => $row['name_suffix'],
                'designation' => $row['designation'],
                'educational_level_id' => $row['educational_level'],
                'college_id' => $row['college']
            ]);
        }
    }

    public function rules(): array
    {
        return [
            '*.idno' => 'unique:users,idno',
            // '*.name' => 'unique:programs,name|unique:programs,code',
        ];
    }


    public function chunkSize(): int
    {
        return 1000;
    }
    
}
