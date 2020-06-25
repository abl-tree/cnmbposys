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

    protected $om_id;

    public function __construct($start, $end, $om_id = null) {

        $this->start = $start;

        $this->end = $end;

        $this->om_id = $om_id;

    }

    public function view(): View
    {
        $summary = []; 

        $start = Carbon::parse($this->start);

        $end = Carbon::parse($this->end);

        $om_id = $this->om_id;

        $agents = UserInfo::select('id')
        ->whereHas('user', function($q) {
            $q->whereHas('accesslevel', function($q) {
                $q->where('code', 'representative_op');
            });
        })
        ->when($om_id, function($q) use ($om_id, $start, $end) {
            $q->where(function($q) use ($om_id, $start, $end) {
                $q->whereHas('schedule', function($q) use ($om_id, $start, $end){
                    $q->whereIn('om_id', $om_id);
                    $q->whereDate('start_event', '>=', $start->copy()->format('Y-m-d'));
                    $q->whereDate('start_event', '<=', $end->copy()->format('Y-m-d'));
                });
                $q->orWhereDoesntHave('schedule', function($q) use ($start, $end, $om_id) {
                    $q->whereDate('start_event', '>=', $start->copy()->format('Y-m-d'));
                    $q->whereDate('start_event', '<=', $end->copy()->format('Y-m-d'));
                    $q->whereHas('user_info', function($q) use ($om_id) {
                        $q->whereHas('accesslevelhierarchy', function($q) use ($om_id) {
                            $q->whereHas('parentInfo', function($q) use ($om_id) {
                                $q->whereHas('accesslevelhierarchy', function($q) use ($om_id) {
                                    $q->whereHas('parentInfo', function($q) use ($om_id) {
                                        $q->whereIn('id', $om_id);
                                    });
                                });
                            });
                        });
                    });
                    // $tmpUser->orWhereHas('accesslevelhierarchy', function($q) use ($om_id) {
                    //     $q->whereHas('parentInfo', function($q) use ($om_id) {
                    //         $q->whereHas('accesslevelhierarchy', function($q) use ($om_id) {
                    //             $q->whereHas('parentInfo', function($q) use ($om_id) {
                    //                 $q->whereIn('id', $om_id);
                    //             });
                    //         });
                    //     });
                    // });
                });
            });
        })
        ->get();

        $agent_ids = array_column($agents->toArray(), 'id');

        while($start->lte($end)) {

            $agents = UserInfo::with(['schedule' => function($q) use ($start, $om_id){
                $q->where(function($q) use ($start) {
                    $q->whereDate('start_event', $start->copy()->format('Y-m-d'));
                    $q->whereDoesntHave('overtime_schedule');
                });
                $q->when($om_id, function($q) use ($om_id) {
                    $q->whereIn('om_id', $om_id);
                });
                $q->with('om_info', 'tl_info');
            }])
            ->whereIn('id', $agent_ids)
            ->orderBy('firstname')
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
