<?php

namespace App\Exports;  

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Data\Models\UserInfo;
use Carbon\Carbon;

class SVASummary implements FromView, WithTitle, ShouldAutoSize
{
    protected $start;

    protected $end;

    public function __construct($start, $end) {

        $this->start = $start;

        $this->end = $end;

    }

    public function view(): View
    {
        $summary = [];

        $start = Carbon::parse($this->start);

        $end = Carbon::parse($this->end);

        $agents = UserInfo::select('id')
        ->whereHas('user', function($q) {
            $q->whereHas('accesslevel', function($q) {
                $q->where('code', 'representative_op');
            });
        })
        ->get();

        $agent_ids = array_column($agents->toArray(), 'id');

        while($start->lte($end)) {

            $agents = UserInfo::with(['schedule' => function($q) use ($start){
                $q->where(function($q) use ($start) {
                    $q->whereDate('start_event', $start->copy()->format('Y-m-d'));
                    $q->whereDoesntHave('overtime_schedule');
                });
                $q->with('om_info', 'tl_info');
            }])
            ->whereIn('id', $agent_ids)
            // ->when($request->om_id, function($q) use ($request) {
            //     $q->whereHas('schedule', function($q) use ($request){
            //         $q->whereIn('om_id', $request->om_id);
            //     });
            // })
            ->get();

            array_push($summary, [
                'date' => $start->copy()->format('l, F d'),
                'data' => $agents
            ]);

            $start = $start->addDay();
        }

        return view('exports.sva_summary_report', [
            'summaries' => $summary
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Summary';
    }
}
