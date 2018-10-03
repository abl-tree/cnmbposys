<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class exportReportSheet implements WithMultipleSheets
{
    use Exportable;
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new employeeExport();

        return $sheets;
    }
 

}