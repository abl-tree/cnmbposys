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
        'timeout_origin',
        'system_timeout',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'raw_time_out'];

    public function getTimeInAttribute($value)
    {
        return Carbon::parse($value);
    }

    
    public function getTimeoutOriginAttribute(){
        
        $schedule = $this->with("schedule")->find($this->id)->schedule;
        $delay = Carbon::parse($schedule->end_event)->addMinutes(15);
        $now = Carbon::now();
        $result =null;

        // return 
        if($this->time_in){
            if(!$this->time_out){
                if($now->isAfter($delay)){
                    $result = "system";
                }else{
                    $result = null;

                }
            }else{
                $result = "user";
            }
        }else{
            $result = null;

        }
    
        return $result;
    }
    
    public function getTimeOutAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getSystemTimeoutAttribute(){
        $schedule = $this->with("schedule")->find($this->id)->schedule;
        $delay = Carbon::parse($schedule->end_event)->addMinutes(15);
        $now = Carbon::now();
        $result =null;

        // return 
        if($this->time_in){
            if(!$this->time_out){
                if($now->isAfter($delay)){
                    $result = Carbon::parse($schedule->end_event);
                }else{
                    $result = null;

                }
            }else{
                $result = Carbon::parse($schedule->end_event);
            }
        }else{
            $result = null;

        }
        return $result;
    }


    public function getRenderedTimeAttribute()
    {
        $start = $this->time_in;
        $end = $this->time_out ? $this->time_out : Carbon::now();
        $difference = $end->diffInSeconds($start);

        return $difference;
    }


    public function schedule()
    {
        return $this->hasOne('App\Data\Models\AgentSchedule', 'id', 'schedule_id');
    }
}