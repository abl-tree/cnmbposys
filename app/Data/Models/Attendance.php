<?php

namespace App\Data\Models;

use App\Data\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends BaseModel
{
    protected $fillable = [
        'schedule_id',
        'time_in',
        'time_out'
    ];

    protected $appends = [
        'rendered_time'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function getTimeInAttribute($value) {
        return $value;
    }

    public function getTimeOutAttribute($value) {
        return $value;
    }

    public function getRenderedTimeAttribute() {
        $start = Carbon::parse($this->time_in);
        $end = ($this->time_out) ? Carbon::parse($this->time_out) : Carbon::now();
        $difference  = $end->diffInSeconds($start);

        return $difference;
    }

    public function schedule(){
        return $this->hasOne('App\Data\Models\AgentSchedule', 'id','schedule_id');
    }
}