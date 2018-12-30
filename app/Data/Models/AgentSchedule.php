<?php

namespace App\Data\Models;

use App\Data\Models\BaseModel;

class AgentSchedule extends BaseModel
{
    protected $primaryKey = 'id';
    protected $table = 'agent_schedules';
    protected $fillable = [
        'user_id',
        'title',
        'event_start',
        'event_end',
    ];

    public $timestamps = true;

    public function agentschedule(){
        return $this->belongsTo('App/User');
    }
}
