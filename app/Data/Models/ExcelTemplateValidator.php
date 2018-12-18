<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Data\Models\BaseModel;

class ExcelTemplateValidator extends BaseModel
{
    protected $fillable = ['template','token'];

    public function updateExcelToken($template){
        $update = ExcelTemplateValidator::where('template',$template)->first();
        $update->token = Str::random(20);
        $update->save();
    }
}
