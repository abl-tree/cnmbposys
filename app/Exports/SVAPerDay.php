<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;
use App\Data\Models\UserInfo;

class SVAPerDay implements FromView, WithTitle, ShouldAutoSize
{
    protected $date;

    public function __construct($date) {
        $this->date = $date;
    }

    public function view(): View
    {
        $start = $this->date;
        
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
        // ->when($request->om_id, function($q) use ($request) {
        //     $q->whereHas('schedule', function($q) use ($request){
        //         $q->whereIn('om_id', $request->om_id);
        //     });
        // })
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
}
