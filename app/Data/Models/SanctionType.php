<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Data\Models\BaseModel;
use Illuminate\Support\Facades\DB;

class SanctionType extends BaseModel
{   
    protected $primaryKey = 'id';
    protected $table = 'sanction_type';
    protected $appends = ['value','text'];
    protected $fillable = [
        'type_number', 'type_description','created_at','updated_at'
    ];  


    protected $hidden = [
        'created_at','updated_at','deleted_at','id','type_number', 'type_description'
    ];
    public function getValueAttribute(){
        $value = null;
        $value = $this->id;
        
        return $value;
    }
    public function getTextAttribute(){
        $value = null;
        $value = $this->type_description;
        
        return $value;
    }



}
