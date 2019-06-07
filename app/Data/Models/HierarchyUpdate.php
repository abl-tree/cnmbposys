<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Data\Models\BaseModel;
use Illuminate\Support\Facades\DB;

class HierarchyUpdate extends BaseModel
{   
    protected $primaryKey = 'child_id';
    protected $table = 'access_level_hierarchies';
    protected $fillable = [
        'parent_id',
    ];  


    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];
}
