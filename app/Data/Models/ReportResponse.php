<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Data\Models\BaseModel;
use Illuminate\Support\Facades\DB;

class ReportResponse extends BaseModel
{   
    protected $primaryKey = 'id';
    protected $table = 'user_reports_response';
    protected $fillable = [
        'user_response_id', 'commitment','status','created_at','updated_at'
    ];  


    public function report() {
       return $this->belongsTo('\App\Data\Models\UserReport','id','sanction_type_id');
    }

}
