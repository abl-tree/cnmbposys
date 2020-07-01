<?php

namespace App\Data\Models;

use App\Data\Models\BaseModel;

class ScheduleLogStatus extends BaseModel
{
    protected $fillable = [
        'schedule_id',
        'status',
    ];

    protected $rules = [
        'status' => 'required|max:100',
    ];

    public function agent_schedule()
    {
        return $this->belongsTo('\App\Data\Models\AgentSchedule', 'schedule_id', 'id');
    }
}
