<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Data\Models\BaseModel;

class UserReport extends BaseModel
{
 	protected $table = 'user_reports';

 	protected $fillable = [
        'user_reports_id', 'description','filed_by'
    ];

    public function reportDetails() {
        $query = DB::table('user_reports')
        ->join('users','users.id','=','user_reports.user_reports_id')
        ->join('user_infos','user_infos.id','=','user_reports.filed_by')
        ->select()->all();
        return $query;
    }
}

