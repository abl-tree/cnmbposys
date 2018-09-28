<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use App\UserInfo;
use DateTime;

class excelController extends Controller
{
    function export(){
        $data = UserInfo::all();
        $data_array[] = [
            'First Name',
            'Middle Name',
            'Last Name',
            'Address',
            'Birth Date',
            'Gender',
        ];

         foreach($data as $datum){
            $data_array[] = [
            'First Name'=>$datum->firstname,
            'Middle Name'=>$datum->middlename,
            'Last Name'=>$datum->lastname,
            'Address'=>$datum->address,
            'Birth Date'=>$datum->birthdate,
            'Gender'=>$datum->gender,
            ];
         }
         Excel::create('UserInfo',function($excel)use($data_array){
            $excel->setTitle('UserInfo');
            $excel->sheet('UserInfo',function($sheet) use($data_array){
                $sheet->fromArray($data_array,null,'A1',false,false);
            });
         })->download('xlsx');
    }

    function import(Request $request){
        if($request->hasFile('excel_file')){
            $path = $request->file('excel_file')->getRealPath();
            $data = Excel::load($path, function($reader){})->get();
            if(!empty($data) && $data->count()){
                
                foreach($data as $datum){
                    $date = new DateTime($datum->date);
                    if($datum->firstname!=""){
                    $userinfo = new UserInfo;
                    $userinfo->firstname = $datum->firstname;
                    $userinfo->middlename = $datum->middlename;
                    $userinfo->lastname = $datum->lastname;
                    $userinfo->address = $datum->address;
                    $userinfo->birthdate = $date->format('m/d/Y');
                    $userinfo->gender = $datum->gender;
                    $userinfo->contact_number = $datum->contact_number;
                    $userinfo->salary_rate = $datum->salary_rate;
                    $userinfo->status = $datum->status;
                    $userinfo->save();
                    }
                }

                // UserInfo::insert($obj);
                
            }
            
        }
        
    }

}
