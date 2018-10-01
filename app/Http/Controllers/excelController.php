<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use App\UserInfo;
use DateTime;
use App\Exports\exportSheets;
class excelController extends Controller
{
    function export(){
        return (new exportSheets)->download('userInfo.xlsx');
    }

    function import(Request $request){
        if($request->hasFile('excel_file')){
            $path = $request->file('excel_file')->getRealPath();
            echo $path;
            
                
            
        }
        
    }

}
