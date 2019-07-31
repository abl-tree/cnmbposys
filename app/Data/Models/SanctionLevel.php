<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Data\Models\BaseModel;
use Illuminate\Support\Facades\DB;

class SanctionLevel extends BaseModel
{
    protected $primaryKey = 'id';
    protected $table = 'sanction_level';
    protected $appends = ['value','text'];
    protected $fillable = [
       'level_number', 'level_description','created_at','updated_at'
    ];
    protected $hidden = [
        'created_at','updated_at','deleted_at','id','level_number', 'level_description'
    ];
    protected $searchable = [
        'level_description'
    ];
    public function getValueAttribute(){
        $value = null;
        $value = $this->id;
        
        return $value;
    }
    public function getTextAttribute(){
        $value = null;
        $value = $this->level_description;
        
        return $value;
    }

}
