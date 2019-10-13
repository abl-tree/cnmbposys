<?php

namespace App\Data\Models;

use App\Data\Models\BaseModel;
use Carbon\Carbon;

class Attendance extends BaseModel
{
    protected $fillable = [
        'schedule_id',
        'time_in',
        'time_out',
        'time_out_by',
        'is_leave',
    ];

    protected $appends = [
        'rendered_time',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function getTimeInAttribute($value) {
        return Carbon::parse($value);
    }

    public function getTimeOutAttribute($value) {
        return $value ? Carbon::parse($value) : null;
    }

    public function getRenderedTimeAttribute() {
        $start = $this->time_in;
        $end = $this->time_out ? $this->time_out : Carbon::now();
        $difference  = $end->diffInSeconds($start);

        return $difference;
    }

    public function schedule()
    {
        return $this->hasOne('App\Data\Models\AgentSchedule', 'id', 'schedule_id');
    }
}
