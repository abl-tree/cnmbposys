<?php

namespace App\Data\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Data\Models\UserInfo;
use App\Data\Models\AccessLevelHierarchy;
use App\Data\Models\BaseModel;

class UpdateStatus extends BaseModel
{
    use Notifiable;
    protected $primaryKey = 'id';
    protected $table = 'user_status_logs';
    // protected $appends = [
    //    'value','name'
    // ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','status', 'reason', 'hired_date','separation_date','created_at','updated_at','deleted_at'
    ];

    protected $searchable = [
        'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'hierarchy','user_info','accesslevel','accesslevelhierarchy'
    ];

  
   

    public function user_info() {
        return $this->hasOne('\App\Data\Models\UserInfo', 'id', 'uid');
    }
   
    public function getValueAttribute(){
        $name = null;
        if(isset($this->user_info)){
            $name = $this->user_info->id;
        }
        
        return $name;
    }



    

}
