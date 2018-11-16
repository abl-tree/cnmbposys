<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithCustomQuerySize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\UserInfo;

class employeeExport implements FromQuery,ShouldAutoSize,WithHeadings,WithTitle
{
    use Exportable;

    
    public function query()
    {
        $userInfo = new UserInfo;
        return $userInfo->getAllEmployee();
    }

    public function headings(): array
    {
        return [
            'CID',
            'First Name',
            'Middle Name',
            'Last Name',
            'Status',
            'Gender',
            'Birth Date',
            'Address',
            'PersonalEmail',
            'CompanyEmail',
            'Contact No.',
            'SSS',
            'Philhealth',
            'PagIbig',
            'TIN',
            'Position',
            'Hired Date',
            'Separation Date',
        ];
    }
    public function title(): string
    {
        return 'Employee';
    }

}