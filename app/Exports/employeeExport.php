<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithCustomQuerySize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use App\UserInfo;

class employeeExport implements FromQuery,ShouldAutoSize,WithHeadings
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
            'SID',
            'First Name',
            'Middle Name',
            'Last Name',
            'Status',
            'Gender',
            'Birth Date',
            'Address',
            'Email',
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
}