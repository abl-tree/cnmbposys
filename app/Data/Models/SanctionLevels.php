<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Data\Models\BaseModel;
use Illuminate\Support\Facades\DB;

class SanctionLevels extends BaseModel
{   
    protected $primaryKey = 'id';
    protected $table = 'sanction_level';
    protected $fillable = [
        'level_number', 'level_description','created_at','updated_at'
    ];  

    protected $searchable = [
        'level_description'
    ];
    public function report() {
       return $this->belongsTo('\App\Data\Models\UserReport','id','sanction_type_id');
    }

}
