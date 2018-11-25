<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Importable;
use App\Imports\addImport;
class employeeImport implements WithMultipleSheets
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use Importable;

    public function sheets(): array
    {
        return [
            // Select by sheet index
            'Add' => new addImport(),
            
            // Select by sheet name
            // 'Other sheet' => new SecondSheetImport
        ];
    }

        
    // public function model(array $row)
    // {
    //     foreach($row as $datum){
    //         if(!isset($datum)){
    //             return null;
    //         }
    //     }

        
    //     return new UserInfo([
    //         'firstname' => $row['first_name'],
    //     ]);
        
    // }

    
}
