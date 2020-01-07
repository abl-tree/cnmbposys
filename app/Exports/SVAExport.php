<?php

namespace App\Exports;

use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Exports\SVAPerDay;
use App\Exports\SVASummary;
use App\Data\Models\UserInfo;
use Carbon\Carbon;

class SVAExport implements WithMultipleSheets
{
    use Exportable;

    protected $start;

    protected $end;

    protected $data;
    
    /**
    * It's required to define the fileName within
    * the export class when making use of Responsable.
    */
    // private $fileName = 'invoices.xlsx';
    
    // /**
    // * Optional Writer Type
    // */
    // private $writerType = Excel::XLSX;
    
    // /**
    // * Optional headers
    // */
    // private $headers = [
    //     'Content-Type' => 'text/csv',
    // ];

    public function __construct($start, $end) {
        $this->start = $start;

        $this->end = $end;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $summaryData = [];

        $sheets = [];

        $start = Carbon::parse($this->start);

        $end = Carbon::parse($this->end);

        $summary = new SVASummary($start->format('Y-m-d'), $end->format('Y-m-d'));

        $sheets[] = $summary;

        while($start->lte($end)) {

            $tmp = new SVAPerDay($start->format('Y-m-d'));

            $sheets[] = $tmp;

            $start = $start->addDay();

        }
 
        return $sheets;
    }
}
