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

class parentList implements FromQuery,ShouldAutoSize,WithHeadings,WithTitle
{
    use Exportable;

    
    public function query()
    {
        $userInfo = new UserInfo;
        return $userInfo->getParentsWithId();
    }

    public function headings(): array
    {
        return [
            'SID',
            'NAME',
        ];
    }
    public function title(): string
    {
        return 'ParentList';
    }

}