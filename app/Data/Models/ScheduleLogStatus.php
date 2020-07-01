<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleLogStatus extends Model
{
    protected $fillable = [
        'schedule_id',
        'status',
    ];

    protected $rules = [
        'status' => 'required|in:punctual,tardy,no_timeout,timed_out,undertime,overtime',
    ];

    public function agent_schedule()
    {
        return $this->belongsTo('\App\Data\Models\AgentSchedule', 'schedule_id', 'id');
    }
}
