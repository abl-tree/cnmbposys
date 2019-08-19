<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use App\Data\Models\BaseModel;
use Illuminate\Support\Facades\DB;

class ActionLogs extends BaseModel
{
    protected $primaryKey = 'id';
    protected $table = 'action_logs';
    protected $appends = [
        'firstname','lastname','email','full_name','image_url','position',
     ];
     protected $searchable = [
        'userinfo.firstname','userinfo.middlename','userinfo.lastname','created_at'
    ];
    protected $fillable = [
        'user_id', 'action', 'affected_data','created_at','updated_at'
    ];

    protected $hidden = [
        'userinfo','user','accesslevelhierarchy','password','deleted_at','updated_at'
         
    ];  
    
   



    public function userinfo() {
        return $this->hasOne('\App\Data\Models\UserInfo', 'id', 'user_id');
    }
    public function user() {
        return $this->hasOne('\App\Data\Models\Users', 'id', 'user_id')->with('position');
    }
   	// public function accesslevel(){
    //    return $this->hasOne('\App\Data\Models\AccessLevel', 'id', 'access_id');
    // }
    public function accesslevelhierarchy(){
        return $this->hasOne('\App\Data\Models\AccessLevelHierarchy', 'id', 'user_id');
    }


    public function getEmailAttribute(){
        $name = null;
        if(isset($this->user)){
            $name = $this->user->email;
        }
        
        return $name;
    }
    public function getFullnameAttribute(){
        $name = null;
        if(isset($this->userinfo)){
            $name = $this->userinfo->full_name;
        }
        
        return $name;
    }
    public function getImageurlAttribute(){
        $name = null;
        if(isset($this->userinfo)){
            $name = $this->userinfo->image_url;
        }
        
        return $name;
    }
    public function getPositionAttribute(){
        $name = null;
        if(isset($this->user)){
            $name = $this->user->position->name;
        }
        
        return $name;
    }
    public function getFirstnameAttribute(){
        $name = null;
        if(isset($this->userinfo)){
            $name = $this->userinfo->firstname;
        }
        
        return $name;
    }
    public function getLastnameAttribute(){
        $name = null;
        if(isset($this->userinfo)){
            $name = $this->userinfo->lastname;
        }
        
        return $name;
    }

}
