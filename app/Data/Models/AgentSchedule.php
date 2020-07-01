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
        'tl_id',
        'om_id',
        'title_id',
        'start_event',
        'end_event',
        'overtime_id',
        'approved_by',
        'conformance',
        'leave_id',
        'vto_at',
        'remarks',
    ];

    protected $appends = [
        'date',
        'user_status',
        'regular_hours',
        'rendered_hours',
        'vto_hours',
        'overtime',
        'time_in',
        'time_out',
        'time_out_origin',
        'log_status',
        'is_working',
        'break',
        'remaining_time',
        'leave',
        'om',
        'tl',
    ];

    protected $searchable = [
        'overtime_schedule.id',
        'user_info.firstname',
        'user_info.middlename',
        'user_info.lastname',
        'user_id',
        'tl_id',
        'om_id',
        'title_id',
        'start_event',
        'end_event',
        'overtime_id',
        'leave_id',
        'vto_at',
    ];

    public $timestamps = true;

    protected $rules = [
        'user_id' => 'sometimes|required|numeric',
        'tl_id' => 'sometimes|required|numeric',
        'om_id' => 'sometimes|required|numeric',
        'title_id' => 'sometimes|required|numeric',
        'start_event' => 'sometimes|required|date',
        'end_event' => 'sometimes|required|date',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'user'];

    public function getStartEventAttribute($value)
    {
        return Carbon::parse($this->overtime_schedule ? $this->overtime_schedule->start_event : $value);
    }

    public function getEndEventAttribute($value)
    {
        return Carbon::parse($this->overtime_schedule ? $this->overtime_schedule->end_event : $value);
    }

    public function getVtoAtAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getUserstatusAttribute()
    {
        $data = null;
        $dates = [];
        $interval = [];
        foreach ($this->user as $key => $value) {
            if ($value->hired_date != null) {
                array_push($dates, date("Y-m-d", strtotime($value->hired_date)));
            } else {
                array_push($dates, date("Y-m-d", strtotime($value->separation_date)));
            }
        }

        foreach ($dates as $day) {
            if (strtotime($this->date['ymd']) >= strtotime($day)) {
                //$interval[$count] = abs(strtotime($date) - strtotime($day));
                array_push($interval, abs(strtotime($this->date['ymd']) - strtotime($day)));
                //$count++;
            }
        }
        if (count($interval) != 0) {
            asort($interval);
            $closest = key($interval);
            foreach ($this->user as $key => $value) {
                if (date("Y-m-d", strtotime($value->hired_date)) == $dates[$closest]) {
                    return array(
                        'hired_date' => date("Y-m-d", strtotime($value->hired_date)),
                        'status' => $value->status,
                        'type' => $value->type,
                    );
                } else if (date("Y-m-d", strtotime($value->separation_date)) == $dates[$closest]) {
                    return array(
                        'hired_date' => date("Y-m-d", strtotime($value->separation_date)),
                        'status' => $value->status,
                        'type' => $value->type,
                    );
                }
            }
        } else {
            return "Not Yet Hired ";
        }

        //  return $dates[$closest];
        //  return  $this->date['ymd'];

    }

    public function getConformanceAttribute($value)
    {
        if ($this->overtime_id) {
            return number_format($value, 1);
        } else {
            if ($this->remarks == "Present") {

                if ($this->attendances && $this->attendances->first() && !$this->attendances->first()->time_out) {

                    return number_format(0, 1);

                }

                $billableSeconds = $this->vto_at ? $this->rendered_hours['billable']['second'] + $this->vto_hours['second'] : $this->rendered_hours['billable']['second'];
                $regularSeconds = $this->regular_hours['second'];

                $value = ($billableSeconds / $regularSeconds) * 100;

                return number_format($value ? $value : 0, 1);
            } else if ($this->remarks == "On-Leave") {
                if ($this->leave && $this->leave->leave_type == "partial_sick_leave") {
                    $billableSeconds = $this->vto_at ? $this->rendered_hours['billable']['second'] + $this->vto_hours['second'] : $this->rendered_hours['billable']['second'];
                    $regularSeconds = $this->regular_hours['second'];

                    $value = ($billableSeconds / $regularSeconds) * 100;

                    return number_format($value ? $value : 0, 1);
                }
                return number_format($value ? $value : 0, 1);
            } else {
                return number_format(0, 1);
            }
        }
    }

    public function getVtoHoursAttribute()
    {
        $vto_start = Carbon::parse($this->vto_at);
        $vto_end = Carbon::parse($this->end_event);
        $sched_hours = $vto_end->diffInSeconds($vto_start);
        $day = "";

        if ($sched_hours >= 86400) {
            $day = (int) ($sched_hours / 86400) . 'd ';
        }

        return array(
            'start' => $vto_start,
            'end' => $vto_end,
            'time' => $day . gmdate('H:i:s', $sched_hours),
            'second' => $sched_hours,
        );
    }

    public function getRegularHoursAttribute()
    {
        if ($this->overtime_schedule) {
            return array(
                'time' => gmdate('H:i:s', 0),
                'second' => 0,
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
            'day1' => Carbon::parse($this->start_event)->format('D'),
            'd' => Carbon::parse($this->start_event)->format('d'),
            'm' => Carbon::parse($this->start_event)->format('M'),
        );
    }

    public function getRenderedHoursAttribute()
    {
        return !$this->overtime_schedule ? $this->rendered_time() : array(
            'billable' => array(
                'time' => '00:00:00',
                'second' => 0,
                'decimal' => number_format(0, 2),
                'night_difference' => 0,
            ),
            'nonbillable' => array(
                'time' => '00:00:00',
                'second' => 0,
                'decimal' => number_format(0, 2),
                'night_difference' => 0,
            ),
            'time' => '00:00:00',
            'second' => 0,
            'night_difference' => 0,
        );
    }

    public function getOvertimeAttribute()
    {
        return $this->overtime_schedule ? $this->rendered_time() : array(
            'billable' => array(
                'time' => '00:00:00',
                'second' => 0,
                'decimal' => number_format(0, 2),
                'night_difference' => 0,
            ),
            'nonbillable' => array(
                'time' => '00:00:00',
                'second' => 0,
                'decimal' => number_format(0, 2),
                'night_difference' => 0,
            ),
            'time' => '00:00:00',
            'second' => 0,
            'night_difference' => 0,
        );
    }

    public function getRemainingTimeAttribute()
    {
        $total = $this->regular_hours['second'];
        $rendered = $this->rendered_hours['second'];
        $remaining = $total - $rendered;
        $days = '';

        if ($remaining >= 86400) {
            $days = (int) ($remaining / 86400) . 'd ';
        }

        return array(
            'time' => $days . gmdate("H:i:s", $remaining),
            'second' => $remaining,
        );
    }

    public function rendered_time()
    {
        $rendered_time = 0;
        $rendered_time_nonbillable = 0;
        $rendered_time_billable = 0;
        $rendered_time_billable_in_decimal = 0;
        $rendered_time_nonbillable_in_decimal = 0;
        $night_dif_billable = 0;
        $night_dif = 0;
        $day = "";
        $day_billable = "";
        $day_nonbillable = "";
        $start = $this->time_in;
        $end = $this->vto_at && $this->vto_at->lt($this->time_out) ? $this->vto_at : ($this->time_out ? $this->time_out : Carbon::now());
        $start_billable = null;
        $end_billable = null;

        if ($this->attendances->count() && $start->lessThan($this->end_event)) {

            $rendered_time = $end->diffInSeconds($start);

            $start_billable = $start->greaterThan($this->start_event) && $start->lessThan($this->end_event) ? $start : $this->start_event;
            $end_billable = $end->lessThan($this->end_event) ? $end : $this->end_event;
            $rendered_time_billable = $end_billable->diffInSeconds($start_billable);

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

        $rendered_time_billable_in_decimal = (float) ($rendered_time_billable / 3600);
        $rendered_time_nonbillable_in_decimal = (float) ($rendered_time_nonbillable / 3600);

        $tmp = [];

        $time_in = Carbon::parse($start);
        $time_out = Carbon::parse($end);
        $diff_start = $time_in->copy()->subDay();
        $diff_end = $time_out->copy()->addDay();
        $start_billable = Carbon::parse($start_billable);
        $end_billable = Carbon::parse($end_billable);

        while ($diff_start->lte($diff_end)) {

            $tmp_diff_start = $diff_start->copy()->setTime(22, 0, 0);

            $tmp_diff_end = $diff_start->copy()->addDay()->setTime(6, 0, 0);

            if ($time_in->lt($tmp_diff_end) && $time_out->gt($tmp_diff_start)) {

                if (!$time_in->lt($tmp_diff_start)) {
                    $tmp_diff_start = $time_in->copy();
                }

                if ($time_out->lt($tmp_diff_end)) {
                    $tmp_diff_end = $time_out->copy();
                }

                $night_dif += $tmp_diff_end->diffInSeconds($tmp_diff_start);

            }

            if ($start_billable->lt($tmp_diff_end) && $end_billable->gt($tmp_diff_start)) {

                if (!$start_billable->lt($tmp_diff_start)) {
                    $tmp_diff_start = $start_billable->copy();
                }

                if ($end_billable->lt($tmp_diff_end)) {
                    $tmp_diff_end = $end_billable->copy();
                }

                $night_dif_billable += $tmp_diff_end->diffInSeconds($tmp_diff_start);

            }

            $diff_start->addDay();
        }

        $night_dif_nonbillable = number_format((float) (($night_dif - $night_dif_billable) / 3600), 2);
        $night_dif = number_format((float) ($night_dif / 3600), 2);
        $night_dif_billable = number_format((float) ($night_dif_billable / 3600), 2);

        return array(
            'billable' => array(
                'time' => $day_billable . gmdate('H:i:s', $rendered_time_billable),
                'second' => $rendered_time_billable,
                'decimal' => number_format($rendered_time_billable_in_decimal, 2),
                'night_difference' => $night_dif_billable,
            ),
            'nonbillable' => array(
                'time' => $day_nonbillable . gmdate('H:i:s', $rendered_time_nonbillable),
                'second' => $rendered_time_nonbillable,
                'decimal' => number_format($rendered_time_nonbillable_in_decimal, 2),
                'night_difference' => $night_dif_nonbillable,
            ),
            'time' => $day . gmdate('H:i:s', $rendered_time),
            'second' => $rendered_time,
            'night_difference' => $night_dif,
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
        if ($this->start_event->isFuture()) {

            if ($this->leave) {
                // dd($this->leave);
                if ($this->leave->status == "approved") {
                    return "On-Leave";
                }
            }

            return "Upcoming";

        }

        if ($value != 0) {
            return 'Absent';
        }

        if ($this->attendances->where('is_leave', 0)->count()) {

            if ($this->leave) {
                if ($this->leave->status == "approved") {
                    return "On-Leave";
                }
            }
            return 'Present';

        }

        if ($this->leave) {
            if ($this->leave->status == "approved") {
                return "On-Leave";
            }
        }

        return 'NCNS';
    }

    public function getLeaveAttribute()
    {
        return $this->leave()->find($this->leave_id);
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

    public function getTimeOutOriginAttribute()
    {
        if ($this->attendances->count()) {
            return $this->attendances[$this->attendances->count() - 1]->time_out_origin;
        }
    }

    public function getLogStatusAttribute()
    {
        $remarks = array();
        $rendered_time = ($this->rendered_hours) ? $this->rendered_hours['second'] : null;
        $rendered_ot = ($this->overtime) ? $this->overtime['second'] : null;

        if ($rendered_time) {

            $sched_start = Carbon::parse($this->start_event)->addMinutes(5);
            $sched_end = Carbon::parse($this->end_event)->subMinutes(5);
            $time_in = Carbon::parse($this->time_in);
            $time_out = Carbon::parse($this->time_out);
            $total_hrs = Carbon::parse($this->end_event)->subMinutes(5)->diffInSeconds($sched_start);

            $remarks[0] = ($time_in->lte($sched_start) ? 'punctual' : 'tardy');
            // $remarks[1] = ($rendered_time - $total_hrs >= 0) ? 'Overtime' : 'Undertime';
            if ($time_out->gte($sched_end)) {
                if (strtolower($this->time_out_origin) == 'system') {
                    $remarks[1] = 'no_timeout';
                } else {
                    $remarks[1] = ($time_out->lte($sched_end->addMinutes(20))) ? 'timed_out' : 'overtime';
                }
            } else {
                $remarks[1] = 'undertime';
            }
            // $remarks[1] = ($time_out->gte($sched_end) ? (strtolower($this->time_out_origin) == 'system') ? 'no_timeout' : 'overtime' : 'undertime');

        } else if ($rendered_ot) {

            $sched_start = Carbon::parse($this->start_event)->addMinutes(5);
            $time_in = Carbon::parse($this->time_in);
            $total_hrs = Carbon::parse($this->end_event)->subMinutes(5)->diffInSeconds($sched_start);

            $remarks[0] = ($time_in->lte($sched_start) ? 'punctual' : 'tardy');
            $remarks[1] = ($rendered_time - $total_hrs >= 0) ?
            (strtolower($this->time_out_origin) == 'system') ? 'no_timeout' : 'overtime'
            : 'undertime';
        } else {
            // $remarks = ['No_Log', 'No_Log'];
            // if($this->remarks == "NCNS"){
            //     $remarks = ["No_Timein","No_Timeout"];
            // }
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

    public function getOmAttribute()
    {
        return $this->user_info->find($this->om_id);
    }

    public function getTlAttribute()
    {
        return $this->user_info->find($this->tl_id);
    }

    public function approved_by()
    {
        return $this->hasOne('App\Data\Models\UserInfo', 'id', 'approved_by');
    }

    // public function om()
    // {
    //     return $this->hasOne('App\Data\Models\UserInfo','id','om_id');
    // }

    // public function tl()
    // {
    //     return $this->hasOne('App\Data\Models\UserInfo','id','om_id');
    // }

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

    public function user_data()
    {
        return $this->hasOne('App\User', "uid", "user_id");
    }

    public function tl_info()
    {
        return $this->hasOne('App\Data\Models\UserInfo', "id", "tl_id");
    }

    public function om_info()
    {
        return $this->hasOne('App\Data\Models\UserInfo', "id", "om_id");
    }

    public function overtime_schedule()
    {
        return $this->belongsTo('App\Data\Models\OvertimeSchedule', "overtime_id");
    }

    public function title()
    {
        return $this->hasOne('App\Data\Models\EventTitle', "id", "title_id");
    }

    public function leave()
    {
        return $this->hasOne('App\Data\Models\Leave', "id", "leave_id");
    }

    public function user()
    {
        return $this->hasMany('App\Data\Models\UpdateStatus', 'user_id', 'user_id');
    }

    public function coaching()
    {
        return $this->hasOne('App\Data\Models\Coaching', 'sched_id', 'id');
    }

    public function schedule_log_status()
    {
        return $this->hasMany('App\Data\Models\ScheduleLogStatus', 'schedule_id');
    }
}
