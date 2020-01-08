<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Carbon\Carbon;
use App\Data\Models\UserInfo;

class SVAPerDay implements FromView, WithTitle, ShouldAutoSize, WithColumnFormatting
{
    protected $date;

    protected $om_id;

    public function __construct($date, $om_id) {
        $this->date = $date;

        $this->om_id = $om_id;
    }

    public function view(): View
    {
        $start = $this->date;

        $om_id = $this->om_id;
        
        $agents = UserInfo::with(['schedule' => function($q) use ($start){
            $q->where(function($q) use ($start) {
                $q->whereDate('start_event', $start);
                $q->whereDoesntHave('overtime_schedule');
            });
            $q->with('om_info', 'tl_info');
        }])
        ->whereHas('user', function($q) {
            $q->whereHas('accesslevel', function($q) {
                $q->where('code', 'representative_op');
            });
        })
        ->when($om_id, function($q) use ($om_id) {
            $q->whereHas('schedule', function($q) use ($om_id){
                $q->whereIn('om_id', $om_id);
            });
        })
        ->orderBy('firstname')
        ->get();

        return view('exports.sva_report', [
            'agents' => $agents
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->date;
    }
    
    public function columnFormats(): array
    {
        return [
            'K' => NumberFormat::FORMAT_PERCENTAGE_00
        ];
    }
}
