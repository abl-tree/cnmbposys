<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Data\Models\UserInfo;
use App\Data\Models\AccessLevelHierarchy;
use App\BaseAuthModel;

class User extends BaseAuthModel
{
    use Notifiable;
    protected $primaryKey = 'id';
    protected $table = 'users';
    protected $appends = [
        'team_leader',
        'operations_manager',
        'full_name'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid','email', 'password', 'access_id','loginFlag','company_id','created_at','updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'hierarchy'
    ];

    /*
    |------------------------------------------------------------------------------------
    | Validations
    |------------------------------------------------------------------------------------
    */
    public static function rules($update = false, $id = null)
    {
        $commun = [
            'email'    => "required|email|unique:users,email,$id",
            'password' => 'nullable|confirmed',
        ];

        if ($update) {
            return $commun;
        }

        return array_merge($commun, [
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /*
    |------------------------------------------------------------------------------------
    | Attributes
    |------------------------------------------------------------------------------------
    */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }
   /* 
    public function getAvatarAttribute($value)
    {
        if (!$value) {
            return 'http://placehold.it/160x160';
        }
    
        return config('variables.avatar.public').$value;
    }
    public function setAvatarAttribute($photo)
    {
        $this->attributes['avatar'] = move_file($photo, 'avatar');
    }

    
    |------------------------------------------------------------------------------------
    | Boot
    |------------------------------------------------------------------------------------
    */
    public static function boot()
    {
        parent::boot();
        static::updating(function($user)
        {
            $original = $user->getOriginal();
            
            if (\Hash::check('', $user->password)) {
                $user->attributes['password'] = $original['password'];
            }
        });
    }

    public function info() {
        return $this->hasOne('\App\Data\Models\UserInfo', 'id', 'uid');
    }

    public function access() {
        return $this->hasOne('\App\Data\Models\AccessLevel', 'id', 'access_id');
    }

    public function schedule(){
        return $this->hasMany('\App\Data\Models\AgentSchedule','user_id','uid');
    }

    public function hierarchy(){
        return $this->hasOne('\App\Data\Models\AccessLevelHierarchy','child_id','uid');
    }

    public function getFullNameAttribute(){
        $name = null;
        $name = $this->info->firstname . ' ' . $this->info->middlename . ' ' . $this->info->lastname;
        
        return $name;
    }

    public function getTeamLeaderAttribute(){
        $id =  $this->hierarchy->parent_id;
        $info = UserInfo::find($id);
        $name = null;
        if(isset($info)){
            $name = $info->firstname . ' ' . $info->middlename . ' ' . $info->lastname;
        }
        return $name;
    }

    public function getOperationsManagerAttribute(){
        $name = null;
        $tl_id =  $this->hierarchy->parent_id;
        if(isset($tl_id)){
            $om_id = AccessLevelHierarchy::where('child_id', $tl_id)->get()->first()->parent_id;
        }
        if(isset($om_id)){
            $info = UserInfo::find($om_id);
        }
        if(isset($info)){
            $name = $info->firstname . ' ' . $info->middlename . ' ' . $info->lastname;
        }
        return $name;
    }

}
