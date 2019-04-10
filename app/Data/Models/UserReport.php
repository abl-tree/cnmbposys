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
       'user_reports_id', 'description','filed_by','sanction_type_id','sanction_level_id'
    ];

    protected $hidden = [
        'user_info','user'
    ];
    protected $appends = [
        'full_name',
    ];



     public function user() {
        return $this->hasOne('\App\User', 'uid', 'user_reports_id');
    }
    public function SanctionType(){
        return $this->belongsTo('\App\Data\Models\SanctionType','sanction_type_id','id');
    }
    public function SanctionLevel(){
        return $this->belongsTo('\App\Data\Models\SanctionLevel','sanction_level_id','id');
    }
     public function filedby(){
        return $this->belongsTo('\App\Data\Models\UserInfo','filed_by','id');
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
    public function getFullNameAttribute(){
        $name = null;
        $name = $this->user->fullname;
        
        return $name;
    }

}

