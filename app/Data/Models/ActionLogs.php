<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use App\Data\Models\BaseModel;
use Illuminate\Support\Facades\DB;

class ActionLogs extends BaseModel
{
    protected $primaryKey = 'id';
    protected $table = 'action_logs';
    protected $fillable = [
        'user_id', 'action', 'affected_data','created_at','updated_at'
    ];

    
     public function user() {
        return $this->hasOne('\App\User', 'uid', 'user_id');
    }
   	public function accesslevel(){
       return $this->hasOne('\App\Data\Models\AccessLevel', 'id', 'user_id');
    }

}
