<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use App\Data\Models\BaseModel;

class ActionLogs extends BaseModel
{
    protected $primaryKey = 'id';
    protected $table = 'action_logs';
    protected $fillable = [
        'user_id', 'action', 'affected_data','created_at','updated_at'
    ];

    public function info() {
        return $this->belongsTo('\App\Data\Models\UserInfo', 'id', 'user_info_id')->with('id_number');
    }
}
