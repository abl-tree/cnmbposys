<?php

namespace App\Data\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Data\Models\UserInfo;
use App\Data\Models\AccessLevelHierarchy;
use App\Data\Models\BaseModel;

class Users extends BaseModel
{
    use Notifiable;
    protected $primaryKey = 'id';
    protected $table = 'users';
    protected $appends = [
       'value','name'
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid','email', 'password', 'access_id','loginFlag','company_id','created_at','updated_at'
    ];

    protected $searchable = [
        'user_info.firstname',
        'user_info.middlename',
        'user_info.lastname',
        'id',
        'uid',
        'email', 
        'password', 
        'access_id',
        'loginFlag',
        'company_id'
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
    public function user_logs() {
        return $this->hasMany('\App\Data\Models\ActionLogs', 'user_id', 'id');
    }

    
    public function accesslevel(){
       return $this->hasOne('\App\Data\Models\AccessLevel', 'id', 'access_id');
    }
    public function position(){
        return $this->belongsTo('\App\Data\Models\AccessLevel', 'access_id', 'id');
     }
     public function userdata() {
        return $this->belongsTo('\App\Data\Models\UserInfo', 'uid', 'id');
    }
    public function accesslevelhierarchy(){
        return $this->hasOne('\App\Data\Models\AccessLevelHierarchy', 'child_id', 'id');
     }
    public function getNameAttribute(){
        $name = null;
        if(isset($this->user_info)){
            $name = $this->user_info->full_name;
        }
        
        return $name;
    }
    public function getValueAttribute(){
        $name = null;
        if(isset($this->user_info)){
            $name = $this->user_info->id;
        }
        
        return $name;
    }



    

}
