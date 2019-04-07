<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'schedule_id',
        'time_in',
        'time_out'
    ];

    protected $appends = [
        'rendered_time'
    ];

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
}
