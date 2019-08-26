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
        'overtime_id',
        'approved_by',
        'conformance',
        'remarks',
    ];

    protected $appends = [
        'regular_hours',
        'date',
        'rendered_hours',
        'overtime',
        'time_in',
        'time_out',
        'log_status',
        'is_working',
        'break',
        'remaining_time'
    ];

    protected $searchable = [
        'user_info.firstname',
        'user_info.middlename',
        'user_info.lastname',
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

    public function getStartEventAttribute($value)
    {
        return Carbon::parse($this->overtime_schedule ? $this->overtime_schedule->start_event : $value);
    }

    public function getEndEventAttribute($value)
    {
        return Carbon::parse($this->overtime_schedule ? $this->overtime_schedule->end_event : $value);
    }

    public function getConformanceAttribute($value)
    {
        if ($this->overtime_schedule) {
            return number_format($value, 1);
        } else {
            $value = ($this->rendered_hours['billable']['second'] / $this->regular_hours['second']) * 100;

            return number_format($value ? $value : 0, 1);
        }
    }

    public function getRegularHoursAttribute()
    {
        if ($this->overtime_schedule) {
            return array(
                'time' => gmdate('H:i:s', 0),
                'second' => 0
            );
        }

        $sched_start = Carbon::parse($this->start_event);
        $sched_end = Carbon::parse($this->end_event);
        $sched_hours = $sched_end->diffInSeconds($sched_start);
        $day = "";

        if ($sched_hours >= 86400) {
            $day = (int) ($sched_hours / 86400) . 'd ';
        }

        return array(
            'time' => $day . gmdate('H:i:s', $sched_hours),
            'second' => $sched_hours,
        );
    }

    public function getDateAttribute()
    {
        return array(
            'ymd' => Carbon::parse($this->start_event)->format('Y-m-d'),
            'day' => Carbon::parse($this->start_event)->format('l'),
        );
    }

    public function getRenderedHoursAttribute()
    {
        return !$this->overtime_schedule ? $this->rendered_time() : array(
            'billable' => array(
                'time' => '00:00:00',
                'second' => 0,
            ),
            'nonbillable' => array(
                'time' => '00:00:00',
                'second' => 0,
            ),
            'time' => '00:00:00',
            'second' => 0,
        );
    }

    public function getOvertimeAttribute()
    {
        return $this->overtime_schedule ? $this->rendered_time() : array(
            'billable' => array(
                'time' => '00:00:00',
                'second' => 0,
            ),
            'nonbillable' => array(
                'time' => '00:00:00',
                'second' => 0,
            ),
            'time' => '00:00:00',
            'second' => 0,
        );
    }

    public function getRemainingTimeAttribute() {
        $total = $this->regular_hours['second'];
        $rendered = $this->rendered_hours['second'];
        $remaining = $total - $rendered;
        $days = '';
        
        if ($remaining >= 86400) {
            $days = (int) ($remaining / 86400) . 'd ';
        }

        return array(
            'time' => $days . gmdate("H:i:s", $remaining), 
            'second' => $remaining
        );
    }

    public function rendered_time()
    {
        $rendered_time = 0;
        $rendered_time_nonbillable = 0;
        $rendered_time_billable = 0;
        $day = "";
        $day_billable = "";
        $day_nonbillable = "";
        $start = $this->time_in;
        $end = $this->time_out ? $this->time_out : Carbon::now();

        if ($this->attendances->count() && $start->lessThan($this->end_event)) {

            $rendered_time = $end->diffInSeconds($start);

            $start = $start->greaterThan($this->start_event) && $start->lessThan($this->end_event) ? $start : $this->start_event;
            $end = $end->lessThan($this->end_event) ? $end : $this->end_event;
            $rendered_time_billable = $end->diffInSeconds($start);

        }

        if ($rendered_time > $rendered_time_billable) {
            $rendered_time_nonbillable = $rendered_time - $rendered_time_billable;
        }

        if ($rendered_time >= 86400) {
            $day = (int) ($rendered_time / 86400) . 'd ';
        }

        if ($rendered_time_billable >= 86400) {
            $day_billable = (int) ($rendered_time_billable / 86400) . 'd ';
        }

        if ($rendered_time_nonbillable >= 86400) {
            $day_nonbillable = (int) ($rendered_time_nonbillable / 86400) . 'd ';
        }

        return array(
            'billable' => array(
                'time' => $day_billable . gmdate('H:i:s', $rendered_time_billable),
                'second' => $rendered_time_billable,
            ),
            'nonbillable' => array(
                'time' => $day_nonbillable . gmdate('H:i:s', $rendered_time_nonbillable),
                'second' => $rendered_time_nonbillable,
            ),
            'time' => $day . gmdate('H:i:s', $rendered_time),
            'second' => $rendered_time,
        );
    }

    public function getIsWorkingAttribute()
    {
        if ($this->attendances->count()) {
            foreach ($this->attendances as $key => $value) {
                if ($value->time_out == null) {
                    return 1;
                }
            }
        }

        return 0;
    }

    public function getRemarksAttribute($value)
    {
        if ($value != 0) {
            return 'Absent';
        }

        if ($this->attendances->count()) {
            return 'Present';
        }

        return 'NCNS';
    }

    public function getTimeInAttribute()
    {
        if ($this->attendances->count()) {
            return $this->attendances[0]->time_in;
        }
    }

    public function getTimeOutAttribute()
    {
        if ($this->attendances->count()) {
            return $this->attendances[$this->attendances->count() - 1]->time_out;
        }
    }

    public function getLogStatusAttribute()
    {
        $remarks = array();
        $rendered_time = ($this->rendered_hours) ? $this->rendered_hours['second'] : null;
        $rendered_ot = ($this->overtime) ? $this->overtime['second'] : null;

        if ($rendered_time) {

            $sched_start = Carbon::parse($this->start_event);
            $time_in = Carbon::parse($this->time_in);
            $total_hrs = Carbon::parse($this->end_event)->diffInSeconds($sched_start);

            $remarks[0] = ($time_in->lte($sched_start) ? 'Punctual' : 'Tardy');
            $remarks[1] = ($rendered_time - $total_hrs >= 0) ? 'Overtime' : 'Undertime';

        } else if ($rendered_ot) {

            $sched_start = Carbon::parse($this->start_event);
            $time_in = Carbon::parse($this->time_in);
            $total_hrs = Carbon::parse($this->end_event)->diffInSeconds($sched_start);

            $remarks[0] = ($time_in->lte($sched_start) ? 'Punctual' : 'Tardy');
            $remarks[1] = 'Overtime';

        }

        return $remarks;
    }

    public function getBreakAttribute()
    {
        $data = array(
            'remaining' => 3,
            'spent' => array(
                'description' => 'Time spent for 3 breaks.',
                'time' => 0,
                'second' => 0,
            ),
            'total' => 0,
            'second' => 0,
        );

        if ($this->attendances->count()) {
            $break_duration = 0;
            $day = "";

            foreach ($this->attendances as $key => $value) {
                if ($value->time_out == null) {
                    break;
                }

                if ($this->attendances->count() - 1 > $key) {
                    $out = $value->time_out;
                    $in = Carbon::parse($this->attendances[$key + 1]->time_in);

                    $break_duration += $in->diffInSeconds($out);
                }

                if ($key + 1 >= 3) {
                    if ($break_duration >= 86400) {
                        $day = (int) ($break_duration / 86400) . 'd ';
                    }

                    $data['spent']['time'] = $day . gmdate('H:i:s', $break_duration);
                    $data['spent']['second'] = $break_duration;
                }
            }

            $data['remaining'] -= ($this->attendances->count() - 1);

            if ($break_duration >= 86400) {
                $day = (int) ($break_duration / 86400) . 'd ';
            }

            $data['total'] = $day . gmdate('H:i:s', $break_duration);

            $data['second'] = $break_duration;
        } else {
            return null;
        }

        return $data;
    }

    public function approved_by()
    {
        return $this->hasOne('App\Data\Models\UserInfo', 'id', 'approved_by');
    }

    public function attendances()
    {
        return $this->hasMany('App\Data\Models\Attendance', 'schedule_id');
    }

    public function agentschedule()
    {
        return $this->belongsTo('App\Data\Models\UserInfo');
    }

    public function user_info()
    {
        return $this->hasOne('App\Data\Models\UserInfo', "id", "user_id");
    }

    public function overtime_schedule()
    {
        return $this->belongsTo('App\Data\Models\OvertimeSchedule', "overtime_id");
    }

    public function title()
    {
        return $this->hasOne('App\Data\Models\EventTitle', "id", "title_id");
    }
}
