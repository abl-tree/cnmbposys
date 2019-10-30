<?php

namespace App\Data\Models;

use App\Data\Models\BaseModel;
use App\Data\Models\Attendance;
use Carbon\Carbon;

class Attendance extends BaseModel
{
    protected $fillable = [
        'schedule_id',
        'time_in',
        'time_out',
        'time_out_by',
        'is_leave',
        'raw_time_out',
    ];

    protected $appends = [
        'rendered_time', 
        'timeout_origin'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'raw_time_out'];

    public function getTimeInAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function getTimeOutAttribute($value)
    {
        // return $value ? Carbon::parse($value) : null;
        
        $schedule = $this->with("schedule")->find($this->id)->schedule;
        $delay = Carbon::parse($schedule->end_event)->addHour();
        $now = Carbon::now();

        // return 
        if($this->time_in){
            if(!$value){
                if($now->isAfter($delay)){
                    return Carbon::parse($schedule->end_event);
                }
                    return null;
            }
            return Carbon::parse($value);
        }else{
            return null;
        }

    }

    public function getRenderedTimeAttribute()
    {
        $start = $this->time_in;
        $end = $this->time_out ? $this->time_out : Carbon::now();
        $difference = $end->diffInSeconds($start);

        return $difference;
    }

    public function getTimeoutOriginAttribute(){
        $schedule = $this->with("schedule")->find($this->id)->schedule;
        $delay = Carbon::parse($schedule->end_event)->addHour();
        $now = Carbon::now();

        // return 
        if($this->time_in){
            if(!$this->time_out){
                if($now->isAfter($delay)){
                    return "system";
                }
                    return null;
            }
            return "user";
        }else{
            return null;
        }
    }

    public function schedule()
    {
        return $this->hasOne('App\Data\Models\AgentSchedule', 'id', 'schedule_id');
    }
}