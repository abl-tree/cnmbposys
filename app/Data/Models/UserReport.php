<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Data\Models\BaseModel;
use Illuminate\Support\Facades\DB;

class UserReport extends BaseModel
{
 	protected $table = 'user_reports';
    protected $primaryKey = 'id';
 	protected $fillable = [
       'user_reports_id', 'description','filed_by','sanction_type_id','sanction_level_id','status'
    ];

    protected $hidden = [
        'filedby','user','id','user_reports_id',
        'filed_by','description','deleted_at',
        'created_at','updated_at','sanction_type_id',
        'sanction_level_id','status','SanctionLevel','SanctionType','agentResponse'
    ];
    protected $appends = [
        'issued_to','issued_by','report_details'
    ];

    protected $searchable = [
        'user.firstname','user.middlename', 'user.lastname',
        'filedby.firstname','filedby.middlename', 'filedby.lastname',
    ];

 
    public function user() {
        return $this->hasOne('\App\Data\Models\UserInfo', 'id', 'user_reports_id')->with('accesslevelhierarchy','user');
    }
    public function SanctionType(){
        return $this->belongsTo('\App\Data\Models\SanctionTypes','sanction_type_id','id');
    }
    public function SanctionLevel(){
        return $this->belongsTo('\App\Data\Models\SanctionLevels','sanction_level_id','id');
    }
    public function filedby() {
        return $this->hasOne('\App\Data\Models\UserInfo', 'id', 'filed_by')->with('accesslevel','user');
    }
     public function agentResponse(){
        return $this->belongsTo('\App\Data\Models\ReportResponse','id','user_response_id');
    }
     

    public function reportDetails() {
        $query = DB::table('user_reports')
        ->join('users','users.id','=','user_reports.user_reports_id')
        ->join('sanction_type','sanction_type.id','=','user_reports.sanction_type_id')
        ->join('sanction_level','sanction_level.id','=','user_reports.sanction_level_id')
        ->join('user_infos','user_infos.id','=','user_reports.filed_by')
        ->select()->all();
        return $query;
    }
    public function getIssuedtoAttribute(){
        $obj = (object) array('id' => $this->user->id,
        'image' => $this->user->image_url, 
        'fname'=> $this->user->firstname,
        'lname'=> $this->user->lastname,
        'full_name' => $this->user->full_name, 
        'position' => $this->user->user->access->name, 
        'email' => $this->user->user->email);
        return $obj;
    }

    public function getIssuedbyAttribute(){
        $obj = (object) array('id' => $this->filedby->id,
        'image' => $this->filedby->image_url, 
        'fname'=> $this->filedby->firstname,
        'lname'=> $this->filedby->lastname,
        'full_name' => $this->filedby->full_name, 
        'position' => $this->filedby->user->access->name, 
        'email' => $this->filedby->user->email);
        return $obj;
    }
    public function getReportdetailsAttribute(){
        $obj = (object) array('id' => $this->id,
        'description' => $this->description, 
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at, 
        'status' => $this->status, 
        'sanction_type' => $this->SanctionType, 
        'sanction_level' => $this->SanctionLevel,
        'agent_response' => $this->agentResponse);
        return $obj;
    }
}

