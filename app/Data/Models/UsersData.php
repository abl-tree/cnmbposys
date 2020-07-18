<?php

namespace App\Data\Models;

use App\Data\Models\AccessLevelHierarchy;
use App\Data\Models\HierarchyLog;
use App\Data\Models\BaseModel;
use App\Data\Models\UserInfo;
use App\User;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class UsersData extends BaseModel
{
    use Notifiable;
    protected $primaryKey = 'id';
    protected $table = 'user_infos';
    protected $appends = [
        'full_name', 'email', 'company_id', 'contract', 'contact', 'access_id', 'position', 'parent_id', 'child_id', 'crypted_id', 'head_name','position','current_cluster'
    ];

    // public $status_color = [
    //     'active' => 'bg-success',
    //     //'new_hired' => 'bg-success',
    //     'inactive' => 'bg-danger',
    //     //'terminated' => 'bg-danger'
    // ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'middlename', 'lastname', 'suffix',
        'birthdate', 'gender', 'contact_number',
        'address', 'image', 'salary_rate', 'image_url',
        'status', 'type', 'hired_date', 'separation_date', 'excel_hash',
        'p_email', 'created_at', 'updated_at',
    ];

    protected $searchable = [
        'firstname',
        'middlename',
        'lastname',
        'fullname',
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
        'user_info', 'password', 'deleted_at', 'remember_token', 'hierarchy', 'accesslevel', 'accesslevelhierarchy', 'loginFlag', 'created_at', 'updated_at', 'contact_number',
    ];

    public function user_logs()
    {
        return $this->hasMany('\App\Data\Models\ActionLogs', 'user_id', 'id');
    }

    public function user_info()
    {
        return $this->hasOne('\App\Data\Models\Users', 'uid', 'id')->with('position');
    }
    public function benefits()
    {
        return $this->hasMany('\App\Data\Models\UserBenefit', 'user_info_id', 'id');
    }

    public function accesslevel()
    {
        return $this->hasOne('\App\Data\Models\AccessLevel', 'id', 'id');
    }
    
    public function accesslevelhierarchy()
    {
        return $this->hasOne('\App\Data\Models\AccessLevelHierarchy', 'child_id', 'id');
    }

    public function tl_schedules()
    {
        return $this->hasMany('\App\Data\Models\AgentSchedule', 'tl_id', 'id');
    }

    public function tl_schedule_checker()
    {
        return $this->hasOne('\App\Data\Models\AgentSchedule', 'tl_id', 'id');
    }

    public function om_schedules()
    {
        return $this->hasMany('\App\Data\Models\AgentSchedule', 'om_id', 'id');
    }

    public function om_schedule_checker()
    {
        return $this->hasOne('\App\Data\Models\AgentSchedule', 'om_id', 'id');
    }

    public function leaves()
    {
        return $this->hasMany('\App\Data\Models\Leave', 'user_id', 'id');
    }

    public function leave_credits()
    {
        return $this->hasMany('\App\Data\Models\LeaveCredit', 'user_id', 'id');
    }

    public function leave_slots()
    {
        return $this->hasMany('\App\Data\Models\LeaveSlot', 'user_id', 'id');
    }

    public function leave_checker()
    {
        return $this->hasOne('\App\Data\Models\Leave', 'user_id', 'id');
    }

    public function leave_credit_checker()
    {
        return $this->hasOne('\App\Data\Models\LeaveCredit', 'user_id', 'id');
    }

    public function leave_slot_checker()
    {
        return $this->hasOne('\App\Data\Models\LeaveSlot', 'user_id', 'id');
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
    public function getAccessidAttribute()
    {
        $name = null;
        if (isset($this->user_info)) {
            $name = $this->user_info->access_id;
        }

        return $name;
    }

    public function getPositionAttribute()
    {
        $name = null;
        if (isset($this->user_info)) {
            $name = $this->user_info->position->name;
        }

        return $name;
    }
    public function getParentidAttribute()
    {
        $name = null;
        if (isset($this->accesslevelhierarchy)) {
            $name = $this->accesslevelhierarchy->parent_id;
        }

        return $name;
    }

    public function getHeadNameAttribute(){
        $hl = HierarchyLog::with('parent_details')->where("child_id", $this->id)->whereNull('end_date')->first();
        if(!$hl){
            return null;
        }else{
            return $hl->parent_details->full_name;
        }
    }
    // public function getHeadEmailAttribute(){
    //     $result = HierarchyLog::where("child_id", $this->id)->get();
    //     $result = collect($result)
    //     ->where('start_date',"<=",Carbon::now())
    //     ->where('tmp_end_date',">=",Carbon::now())->values();
    //     if(isset($result[0])){
    //         $result = User::where('uid',$result[0]->parent_id)->first();
    //     }else{
    //         $result = null;
    //     }
    //     $result = $result ? $result->email:"";
    //     return $result;
    // }


    public function getChildidAttribute()
    {
        $name = null;
        if (isset($this->accesslevelhierarchy)) {
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
    public function getContactAttribute()
    {
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
    // public function getStatusColorAttribute(){
    //     $name = null;
    //         if($this->status!=null){
    //             $name = $this->status_color[strtolower($this->status)];
    //         }

    //     return $name;
    // }

    // // public function getBirthdateAttribute(){
    // //     $name = null;
    // //     if(isset($this->user_info)){
    // //         $name = $this->user_info->birthdate;
    // //     }

    // //     return $name;
    // // }
    

    
    public function getCurrentClusterAttribute(){
        $result = null;
        $access = $this->access_id;
        if($access == 17){
            $head = HierarchyLog::where("child_id", $this->id)->get();
            $head = collect($head)
            ->where('start_date',"<=",Carbon::now())
            ->where('tmp_end_date',">=",Carbon::now());
            if(isset($head[0])){
                $head = $head[0]->parent_id;
                $cluster = HierarchyLog::where("child_id", $head)->get();
                $cluster = collect($cluster)
                ->where('start_date',"<=",Carbon::now())
                ->where('tmp_end_date',">=",Carbon::now());
                if(isset($cluster[0])){
                    $result = UserInfo::find($cluster[0]->parent_id);
                }else{
                $result = null;
                }
            }else{
                $result = null;
            }
        }
        return $result;
    }

    public function getCryptedIdAttribute()
    {
        $name = null;
        $name = Crypt::encrypt($this->id);

        return $name;
    }
    public function getFullNameAttribute()
    {
        $name = null;

        $name = ucwords($this->firstname) . " " . ucwords($this->middlename) . " " . ucwords($this->lastname) . " " . ucwords($this->suffix);

        return $name;
    }
    public function getEmailAttribute()
    {
        $name = null;
        if (isset($this->user_info)) {
            $name = $this->user_info->email;
        }

        return $name;
    }
    public function getCompanyidAttribute()
    {
        $name = null;
        if (isset($this->user_info)) {
            $name = $this->user_info->company_id;
        }

        return $name;
    }
    public function getContractAttribute()
    {
        $name = null;
        if (isset($this->user_info)) {
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
}