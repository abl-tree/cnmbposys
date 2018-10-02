<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class exportTemplateSheet implements WithMultipleSheets
{
    use Exportable;
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new templateExport();
        $sheets[] = new positionExport();
        $sheets[] = new parentList();
        $sheets[] = new employeePositionExport();

        return $sheets;
    }
 

}