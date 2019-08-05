<?php

namespace App\Data\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Data\Models\UserInfo;
use App\Data\Models\AccessLevelHierarchy;
use App\Data\Models\BaseModel;
use Illuminate\Support\Facades\Crypt;

class SelectUsers extends BaseModel
{
    use Notifiable;
    protected $primaryKey = 'id';
    protected $table = 'user_infos';
    protected $appends = [
       'value','text','parent_id','report_count'
    ];

    public $status_color = [
        'active' => 'bg-primary',
        'new_hired' => 'bg-success',
        'inactive' => 'bg-danger',
        'terminated' => 'bg-danger'
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname','middlename', 'lastname','suffix',
        'birthdate', 'gender', 'contact_number',
        'address', 'image', 'salary_rate','image_url',
        'status','type','hired_date', 'separation_date', 'excel_hash',
        'p_email','created_at','updated_at'
    ];

    protected $searchable = [
        'firstname',
        'middlename',
        'lastname',
        'p_email',
        'id',
        'user_info.uid',
        'user_info.email', 
        'user_info.access_id',
        'user_info.loginFlag',
        'user_info.company_id',
        'user_info.contract',
        'accesslevel.name',
        'gender',
        'status',
        'type',
        'address',
        'birthdate',
        'hired_date',
        'separation_date',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'users','user_info','password','deleted_at', 'remember_token', 'hierarchy','accesslevel','accesslevelhierarchy','loginFlag','created_at','updated_at','contact_number','firstname','middlename', 'lastname','suffix',
        'birthdate', 'gender', 'contact_number',
        'address', 'image', 'salary_rate','image_url',
        'status','type','hired_date', 'separation_date', 'excel_hash',
        'p_email','created_at','updated_at','status_reason','id'
    ];

  
    public function user_logs() {
        return $this->hasMany('\App\Data\Models\ActionLogs', 'user_id', 'id');
    }

    public function user_info() {
        return $this->hasOne('\App\Data\Models\Users', 'uid', 'id')->with('position');
    }
    public function benefits() {
        return $this->hasMany('\App\Data\Models\UserBenefit', 'user_info_id', 'id');
    }
    
    public function accesslevel(){
       return $this->hasOne('\App\Data\Models\AccessLevel','id','id');
    }
    public function accesslevelhierarchy(){
        return $this->hasOne('\App\Data\Models\AccessLevelHierarchy', 'child_id', 'id');
     }
     public function getReportcountAttribute(){
        return $this->hasMany('\App\Data\Models\UserReport' ,'user_reports_id', 'id')->count();
    }

    // public function getFnameAttribute(){
    //     $name = null;
    //     if(isset($this->user_info)){
    //         $name = $this->user_info->firstname;
    //     }
        
    //     return $name;
    // }
    // public function getlnameAttribute(){
    //     $name = null;
    //     if(isset($this->user_info)){
    //         $name = $this->user_info->lastname;
    //     }
        
    //     return $name;
    // }
    // public function getExcelhashAttribute(){
    //     $name = null;
    //     if(isset($this->user_info)){
    //         $name = $this->user_info->excel_hash;
    //     }
        
    //     return $name;
    // }
    // public function getMnameAttribute(){
    //     $name = null;
    //     if(isset($this->user_info)){
    //         $name = $this->user_info->middlename;
    //     }
        
    //     return $name;
    // }
    // // public function getSuffixAttribute(){
    // //     $name = null;
    // //     if(isset($this->user_info)){
    // //         $name = $this->user_info->suffix;
    // //     }
        
    // //     return $name;
    // // }
    public function getAccessidAttribute(){
        $name = null;
        if(isset($this->user_info)){
            $name = $this->user_info->access_id;
        }
        
        return $name;
    }
      public function getValueAttribute(){
        $name = null;
        if(isset($this->id)){
            $name = $this->id;
        }
        return $name;
    }
    
    public function getPositionAttribute(){
        $name = null;
        if(isset($this->user_info)){
            $name = $this->user_info->position->name;
        }
        
        return $name;
    }
    public function getParentidAttribute(){
        $name = null;
        if(isset($this->accesslevelhierarchy)){
            $name = $this->accesslevelhierarchy->parent_id;
        }
        
        return $name;
    }
    public function getHeadNameAttribute(){
        $name = null;
        if(isset($this->accesslevelhierarchy)){
            if($this->accesslevelhierarchy->parent_id){
                $head_details = UserInfo::find($this->accesslevelhierarchy->parent_id);
                $name = $head_details->firstname." ".$head_details->lastname;
            }else{
                $name = null;
            }
        }
        
        return $name;
    }
    public function getChildidAttribute(){
        $name = null;
        if(isset($this->accesslevelhierarchy)){
            $name = $this->accesslevelhierarchy->child_id;
        }
        
        return $name;
    }
    // public function getAddressAttribute(){
    //     $name = null;
    //     if(isset($this->user_info)){
    //         $name = $this->address;
    //     }
        
    //     return $name;
    // }
    public function getContactAttribute(){
       $name = $this->contact_number;
    
        
        return $name;
    }
    // public function getGenderAttribute(){
    //     $name = null;
    //     if(isset($this->user_info)){
    //         $name = $this->user_info->gender;
    //     }
        
    //     return $name;
    // }
    // public function getImageAttribute(){
    //     $name = null;
    //     if(isset($this->user_info)){
    //         $name = $this->user_info->image_url;
    //     }
        
    //     return $name;
    // }

    // public function getStatusAttribute(){
    //     $name = null;
    //     if(isset($this->user_info)){
    //         if(strtolower($this->user_info->status) == 'new_hired'){
    //             $name = strtoupper('NEW');
    //         }else{
    //             $name = strtoupper($this->user_info->status);
    //         }
    //     }
        
    //     return $name;
    // }
    // public function getTypeAttribute(){
    //     $name = null;
    //     if(isset($this->user_info)){  
    //             $name = strtoupper($this->user_info->type);
    //     }
        
    //     return $name;
    // }
    public function getStatusColorAttribute(){
        $name = null;   
            if($this->status!=null){
                $name = $this->status_color[strtolower($this->status)];
            }
           
        
        
        return $name;
    }

    // // public function getBirthdateAttribute(){
    // //     $name = null;
    // //     if(isset($this->user_info)){
    // //         $name = $this->user_info->birthdate;
    // //     }
        
    // //     return $name;
    // // }
    public function getCryptedIdAttribute(){
        $name = null;      
            $name = Crypt::encrypt($this->id);
        
        return $name;
    }
    public function getTextAttribute(){
        $name = null;
      
            $name = ucwords($this->firstname)." ".ucwords($this->middlename)." ".ucwords($this->lastname)." ".ucwords($this->suffix);
   
        
        return $name;
    }
    public function getEmailAttribute(){
        $name = null;
        if(isset($this->user_info)){
            $name = $this->user_info->email;
        }
        
        return $name;
    }
    public function getCompanyidAttribute(){
        $name = null;
        if(isset($this->user_info)){
            $name = $this->user_info->company_id;
        }
        
        return $name;
    }
    public function getContractAttribute(){
        $name = null;   
        if(isset($this->user_info)){   
            $name = $this->user_info->contract;
        }
        return $name;
    }


    // public function getHireddateAttribute(){
    //     $name = null;
    //     if(isset($this->user_info)){
    //         $name = $this->user_info->hired_date;
    //     }
        
    //     return $name;
    // }
    // public function getSeparationdateAttribute(){
    //     $name = null;
    //     if(isset($this->user_info)){
    //         $name = $this->user_info->separation_date;
    //     }
        
    //     return $name;
    // }
    // use Notifiable;
    // protected $primaryKey = 'id';
    // protected $table = 'users_inf';
    // protected $appends = [
    //    'value','text','report_count','parent_id','child_id'
    // ];


    // /**
    //  * The attributes that are mass assignable.
    //  *
    //  * @var array
    //  */
    // protected $fillable = [
    //     'uid','email', 'password', 'access_id','loginFlag','company_id','created_at','updated_at'
    // ];

    // protected $searchable = [
    //     'user_info.firstname',
    //     'user_info.middlename',
    //     'user_info.lastname',
    //     'id',
    //     'uid',
    //     'email', 
    //     'password', 
    //     'access_id',
    //     'loginFlag',
    //     'company_id'
    // ];

    // /**
    //  * The attributes that should be hidden for arrays.
    //  *
    //  * @var array
    //  */
    // protected $hidden = [
    //     'password', 'remember_token', 'hierarchy','user_info',
    //     'accesslevel','accesslevelhierarchy','uid','email', 'password', 
    //     'access_id','loginFlag','company_id','created_at','updated_at','deleted_at','contract','id'
    // ];

  
   

    // public function user_info() {
    //     return $this->hasOne('\App\Data\Models\UserInfo', 'id', 'uid');
    // }
    
    // public function accesslevel(){
    //    return $this->hasOne('\App\Data\Models\AccessLevel', 'id', 'access_id');
    // }
    // public function accesslevelhierarchy(){
    //     return $this->hasOne('\App\Data\Models\AccessLevelHierarchy', 'child_id', 'id');
    //  }
    // public function getTextAttribute(){
    //     $name = null;
    //     if(isset($this->user_info)){
    //         $name = $this->user_info->full_name;
    //     }
        
    //     return $name;
    // }
    // public function getParentidAttribute(){
    //     $name = null;
    //     if(isset($this->accesslevelhierarchy)){
    //         $name = $this->accesslevelhierarchy->parent_id;
    //     }
        
    //     return $name;
    // }
    // public function getChildidAttribute(){
    //     $name = null;
    //     if(isset($this->accesslevelhierarchy)){
    //         $name = $this->accesslevelhierarchy->child_id;
    //     }
        
    //     return $name;
    // }
    // public function getValueAttribute(){
    //     $name = null;
    //     if(isset($this->user_info)){
    //         $name = $this->user_info->id;
    //     }
        
    //     return $name; 
    // }
    // public function getReportcountAttribute(){
    //     return $this->hasMany('\App\Data\Models\UserReport' ,'user_reports_id', 'id')->count();
    // }



    

}
