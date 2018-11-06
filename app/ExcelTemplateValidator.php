<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ExcelTemplateValidator extends Model
{
    protected $fillable = ['template','token'];

    public function updateExcelToken($template){
        $update = ExcelTemplateValidator::where('template',$template)->first();
        $update->token = Str::random(20);
        $update->save();
    }
}
