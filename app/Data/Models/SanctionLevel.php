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
    protected $fillable = [
       'level_number', 'level_description','created_at','updated_at'
    ];

}
