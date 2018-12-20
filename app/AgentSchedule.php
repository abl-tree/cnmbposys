<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentSchedule extends Model
{
    protected $fillable = ['user_id','title','event_start','event_end'];

    public function agentschedule(){
        return $this->belongsTo('App/User');
    }
}
