<?php

namespace App\Data\Models;

use App\Data\Models\BaseModel;
use Carbon\Carbon;

class OvertimeSchedule extends BaseModel
{
    protected $fillable = [
        'start_event',
        'end_event'
    ];

    protected $searchable = [
        'start_event',
        'end_event'
    ];

    protected $appends = [
        'overtime_hours',
        'display_time',
        "date",
    ];

    protected $cascadeDeletes = ['schedules'];

    public function getOvertimeHoursAttribute() {
        $sched_start = Carbon::parse($this->start_event);
        $sched_end = Carbon::parse($this->end_event);
        $sched_hours = $sched_end->diffInSeconds($sched_start);
        $day = "";

        if($sched_hours >= 86400) {
            $day = (int) ($sched_hours/86400) . 'd ';
        }

        return array(
            'time' => $day.gmdate('H:i:s', $sched_hours),
            'second' => $sched_hours
        );
    }

    public function getDisplayTimeAttribute(){
        return [
            "start" => Carbon::parse($this->start_event)->subMinutes(30),
            "end" => Carbon::parse($this->start_event)->addMinutes(30),
        ];
    }

    public function getDateAttribute(){
        return [
            "ymd" => Carbon::parse($this->start_event)->format("Y-m-d"),
            "day" => Carbon::parse($this->start_event)->format("l"),
        ];
    }

    public function schedules() {
        return $this->hasMany('App\Data\Models\AgentSchedule', 'overtime_id', 'id');
    }
}
