<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Data\Models\AccessLevel;

class positionExport implements FromQuery,ShouldAutoSize,WithHeadings,WithTitle
{
    use Exportable;

    
    public function query()
    {
        return AccessLevel::find(1);
    }

    public function headings(): array
    {
        return [
            'PID',
            'Code',
            'Name',
            'Parent',
        ];
    }
    public function title(): string
    {
        return 'Position';
    }

}