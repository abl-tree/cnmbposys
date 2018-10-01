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

class templateExport implements FromQuery,WithHeadings,WithTitle
{
    use Exportable;

    
    public function query()
    {
        $userInfo = new UserInfo;
        return $userInfo->getExcelTemplate();
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Middle Name',
            'Last Name',
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
            'Parent Name',
            'Salary',
            'Hired Date',
            'Position ID',
            'Parent Code',
            'Parent ID',
        ];
    }
    public function title(): string
    {
        return 'Template';
    }

}