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
    protected $fillable = [
        'user_id', 'action', 'affected_data','created_at','updated_at'
    ];

    protected $hidden = [
        'user','accesslevelhierarchy','password','deleted_at','updated_at'
         
    ];   
     public function user() {
        return $this->hasOne('\App\User', 'uid', 'user_id');
    }
   	public function accesslevel(){
       return $this->hasOne('\App\Data\Models\AccessLevel', 'id', 'access_id');
    }
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
        if(isset($this->user)){
            $name = $this->user->full_name;
        }
        
        return $name;
    }
    public function getImageurlAttribute(){
        $name = null;
        if(isset($this->user)){
            $name = $this->user->info->image_url;
        }
        
        return $name;
    }
    public function getPositionAttribute(){
        $name = null;
        if(isset($this->user)){
            $name = $this->user->access->name;
        }
        
        return $name;
    }
    public function getFirstnameAttribute(){
        $name = null;
        if(isset($this->user)){
            $name = $this->user->info->firstname;
        }
        
        return $name;
    }
    public function getLastnameAttribute(){
        $name = null;
        if(isset($this->user)){
            $name = $this->user->info->lastname;
        }
        
        return $name;
    }

}
