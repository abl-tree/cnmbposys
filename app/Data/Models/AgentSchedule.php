<?php

namespace App\Data\Models;

use App\Data\Models\BaseModel;
use Carbon\Carbon;

class AgentSchedule extends BaseModel
{
    protected $primaryKey = 'id';
    protected $table = 'agent_schedules';
    protected $fillable = [
        'user_id',
        'title_id',
        'start_event',
        'end_event',
        'overtime'
    ];

    protected $appends = [
        'regular_hours', 
        'date', 
        'rendered_hours', 
        'is_working',
        'is_present',
        'break',
        'time_in',
        'time_out',
        'log_status'
    ];

    protected $searchable = [
        'user_id',
        'title_id',
        'start_event',
        'end_event', 
    ];

    public $timestamps = true;

    protected $rules = [
        'user_id' => 'sometimes|required|numeric',
        'title_id' => 'sometimes|required|numeric',
        'start_event' => 'sometimes|required|date',
        'end_event' => 'sometimes|required|date',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function getRegularHoursAttribute() { 
        $sched_start = Carbon::parse($this->start_event);
        $sched_end = Carbon::parse($this->end_event);
        $sched_hours = $sched_end->diffInSeconds($sched_start);
        $day = "";

        if($sched_hours > 86400) {
            $day = (int) ($sched_hours/86400) . 'd ';
        }

        return array(
            'time' => $day.gmdate('H:i:s', $sched_hours), 
            'second' => $sched_hours
        );
    }

    public function getDateAttribute() {
        return array(
            'ymd' => Carbon::parse($this->start_event)->format('Y-m-d'), 
            'day' => Carbon::parse($this->start_event)->format('l'), 
        );
    }

    public function getRenderedHoursAttribute() {
        $rendered_time = 0;
        $rendered_time_nonbillable = 0;
        $rendered_time_billable = 0;
        $regular_hours = $this->regular_hours['second'];
        $overtime_nonbillable = $this->overtime['nonbillable']['second'];
        $day = "";
        $day_billable = "";
        $day_nonbillable = "";

        if($this->attendances->count()) {
            foreach ($this->attendances as $key => $value) {
                $rendered_time += $value->rendered_time;
            }
        }

        if($rendered_time === $regular_hours) {
            $rendered_time_billable = $rendered_time;
        } else if($rendered_time > $regular_hours) {
            $rendered_time_billable = $regular_hours;
            $rendered_time_nonbillable = $rendered_time_billable - $regular_hours + $overtime_nonbillable;
        } else {
            $rendered_time_billable = $rendered_time;
        }

        if($rendered_time > 86400) {
            $day = (int) ($rendered_time/86400) . 'd ';
        }

        if($rendered_time_billable > 86400) {
            $day_billable = (int) ($rendered_time_billable/86400) . 'd ';
        }

        if($rendered_time_nonbillable > 86400) {
            $day_nonbillable = (int) ($rendered_time_nonbillable/86400) . 'd ';
        }

        return array(
            'billable' => array(
                'time' => $day_billable.gmdate('H:i:s', $rendered_time_billable), 
                'second' => $rendered_time_billable, 
            ), 
            'nonbillable' => array(
                'time' => $day_nonbillable.gmdate('H:i:s', $rendered_time_nonbillable), 
                'second' => $rendered_time_nonbillable, 
            ),
            'time' => $day.gmdate('H:i:s', $rendered_time), 
            'second' => $rendered_time
        );
    }

    public function getIsWorkingAttribute() {
        if($this->attendances->count()) {
            foreach ($this->attendances as $key => $value) {
                if($value->time_out == null) {
                    return 1;
                }
            }
        }

        return 0;
    }

    public function getIsPresentAttribute() {
        if($this->attendances->count()) {
            foreach ($this->attendances as $key => $value) {
                if(Carbon::parse($value->time_in)->isToday() || Carbon::parse($value->time_out)->isToday()) {
                    return 1;
                }
            }
        }

        return 0;
    }

    public function getTimeInAttribute() {
        if($this->attendances->count()) {
            return $this->attendances[0]->time_in;
        }

        return null;
    }

    public function getTimeOutAttribute() {
        if($this->attendances->count()) {
            return $this->attendances[$this->attendances->count() - 1]->time_out;
        }

        return null;
    }

    public function getLogStatusAttribute() {
        $remarks = array();
        $rendered_time = ($this->rendered_hours) ? $this->rendered_hours['second'] : null;

        if($rendered_time) {
            $sched_start = Carbon::parse($this->start_event);
            $time_in = Carbon::parse($this->time_in);
            $total_hrs  = Carbon::parse($this->end_event)->diffInSeconds($sched_start);

            $remarks[0] = ($time_in->lte($sched_start) ? 'Punctual' : 'Tardy');
            $remarks[1] = ($rendered_time - $total_hrs >= 0) ? 'Overtime' : 'Undertime';

            return $remarks;
        }

        return $remarks;
    }

    public function getBreakAttribute() {
        $data = array(
            'remaining' => 3, 
            'spent' => array(
                    'description' => 'Time spent for 3 breaks.', 
                    'time' => 0,
                    'second' => 0
                ),
            'total' => 0,
            'second' => 0
        );

        if($this->attendances->count()) {
            $break_duration = 0;
            $day = "";

            foreach ($this->attendances as $key => $value) {
                if($value->time_out == null) {
                    break;
                }

                if($this->attendances->count() - 1 > $key) {
                    $out = Carbon::parse($value->time_out);
                    $in = Carbon::parse($this->attendances[$key + 1]->time_in);
                    
                    $break_duration += $in->diffInSeconds($out);
                }

                if($key + 1 >= 3) {
                    if($break_duration > 86400) {
                        $day = (int) ($break_duration/86400) . 'd ';
                    }
                    
                    $data['spent']['time'] = $day.gmdate('H:i:s', $break_duration);
                    $data['spent']['second'] = $break_duration;
                }
            }
            
            $data['remaining'] -= ($this->attendances->count() - 1);
            
            if($break_duration > 86400) {
                $day = (int) ($break_duration/86400) . 'd ';
            }
            
            $data['total'] = $day.gmdate('H:i:s', $break_duration);

            $data['second'] = $break_duration;
        } else {
            return null;
        }

        return $data;
    }

    public function getOvertimeAttribute($value) {
        $overtime = $value;

        $regular_hours = $this->regular_hours['second'];

        $rendered_hours = 0;

        if($this->attendances->count()) {
            foreach ($this->attendances as $key => $value) {
                $rendered_hours += $value->rendered_time;
            }
        }

        $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $overtime);

        sscanf($overtime, "%d:%d:%d", $hours, $minutes, $seconds);

        $overtime = $hours * 3600 + $minutes * 60 + $seconds;

        $overtime_nb = $rendered_hours - ($regular_hours + $overtime);

        $overtime_nb = ($overtime_nb > 0) ? $overtime_nb : 0;

        $day = "";

        if($overtime_nb > 86400) {
            $day = (int) ($overtime_nb/86400) . 'd ';
        }

        return array(
            'nonbillable' => array(
                'time' => gmdate('H:i:s', $overtime_nb), 
                'second' => $overtime_nb   
            ),
            'billable' => array(
                'time' => gmdate('H:i:s', $overtime), 
                'second' => $overtime   
            )
        );
    }

    public function attendances(){
        return $this->hasMany('App\Data\Models\Attendance', 'schedule_id');
    }

    public function agentschedule(){
        return $this->belongsTo('App\Data\Models\User');
    }

    public function user_info(){
        return $this->hasOne('App\Data\Models\UserInfo',"id", "user_id" );
    }

    public function title(){
        return $this->hasOne('App\Data\Models\EventTitle',"id", "title_id" );
    }
}
