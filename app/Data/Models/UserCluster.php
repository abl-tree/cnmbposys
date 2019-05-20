<?php

namespace App\Data\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Data\Models\UserInfo;
use App\Data\Models\AccessLevelHierarchy;
use App\Data\Models\BaseModel;

class UserCluster extends BaseModel
{
    use Notifiable;
    protected $primaryKey = 'id';
    protected $table = 'users';
    protected $appends = [
       'value','fullname','mail','image','position','position_code','hired_date','access','first_name','last_name'
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
        'password', 'remember_token', 'hierarchy','user_info',
        'accesslevel','accesslevelhierarchy','uid','email', 'password', 
        'access_id','loginFlag','company_id','created_at','updated_at','deleted_at','contract','id'
    ];

  
   

    public function user_info() {
        return $this->hasOne('\App\Data\Models\UserInfo', 'id', 'uid');
    }
    
    public function accesslevel(){
       return $this->hasOne('\App\Data\Models\AccessLevel', 'id', 'access_id');
    }
    public function accesslevelhierarchy(){
        return $this->hasOne('\App\Data\Models\AccessLevelHierarchy', 'child_id', 'id');
     }
    public function getFullnameAttribute(){
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
    public function getMailAttribute(){
        $name = null;
        if(isset($this->user_info)){
            $name = $this->email;
        }
        
        return $name; 
    }
    public function getImageAttribute(){
        $name = null;
        if(isset($this->user_info)){
            $name = $this->user_info->image;
        }
        
        return $name; 
    }
    public function getPositionAttribute(){
        $name = null;
        if(isset($this->user_info)){
            $name = $this->accesslevel->name;
        }
        
        return $name; 
    }
    public function getPositioncodeAttribute(){
        $name = null;
        if(isset($this->user_info)){
            $name = $this->accesslevel->code;
        }
        
        return $name; 
    }
    public function getHireddateAttribute(){
        $name = null;
        if(isset($this->user_info)){
            $name = $this->user_info->hired_date;
        }
        return $name; 
    }
    
    public function getAccessAttribute(){
        $name = null;
        if(isset($this->user_info)){
            $name = $this->access_id;
        }
        return $name; 
    }
    public function getFirstNameAttribute(){
        $name = null;
        if(isset($this->user_info)){
            $name = $this->user_info->firstname;
        }
        return $name; 
    }
    public function getLastNameAttribute(){
        $name = null;
        if(isset($this->user_info)){
            $name = $this->user_info->lastname;
        }
        return $name; 
    }
    



    

}