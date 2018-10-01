<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\AccessLevel;

class employeePositionExport implements FromQuery,ShouldAutoSize,WithHeadings,WithTitle
{
    use Exportable;

    
    public function query()
    {
        $employeePosition = new AccessLevel;
        return $employeePosition->getAllParentEmployee();
    }

    public function headings(): array
    {
        return [
            'id',
            'admin',
            'id',
            'hrm',
            'id',
            'om',
            'id',
            'tl',
            'id',
            'accm',
            'id',
            'rtam',
            'id',
            'tqm',
        ];
    }
    public function title(): string
    {
        return 'Parents';
    }

}