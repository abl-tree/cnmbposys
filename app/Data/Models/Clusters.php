<?php

namespace App\Data\Models;

use App\Data\Models\BaseModel;

class Clusters extends BaseModel
{
    protected $fillable = [
        'om_id', 'tl_id', 'agent_id', 'created_at', 'updated_at',
    ];

    public function omInfo()
    {
        return $this->hasOne('\App\Data\Models\UserInfo', 'id', 'om_id');
    }

    public function tlInfo()
    {
        return $this->hasOne('\App\Data\Models\UserInfo', 'id', 'tl_id');
    }
    public function agentInfo()
    {
        return $this->hasOne('\App\Data\Models\UserInfo', 'id', 'agent_id');
    }
}
