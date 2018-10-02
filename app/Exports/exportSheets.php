<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class exportSheets implements WithMultipleSheets
{
    use Exportable;
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new templateExport();
        $sheets[] = new positionExport();
        $sheets[] = new employeePositionExport();

        return $sheets;
    }
 

}