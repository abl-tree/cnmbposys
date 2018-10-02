<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use DateTime;
use phpSpreadSheet;
use App\Exports\exportTemplateSheet;
use App\Exports\exportReportSheet;
use App\Imports\importEmployee;
use App\UserInfo;
use App\User;
use App\UserBenefit;
use App\AccessLevelHierarchy;

class excelController extends Controller
{
    function template(){
        $name = "Employee-Report-";
        $name.= now();
        return (new exportTemplateSheet)->download($name.'.xlsx');
    }


    function report(){
        $name = "Import-Template-";
        $name.= now();
        return (new exportReportSheet)->download($name.'.xlsx');
    }



    function import(Request $request){
        if($request->hasFile('excel_file')){
            $sheetname="Template";
            $path = $request->file('excel_file')->getRealPath();
            $extension =  phpSpreadSheet::identify($path);
            
            $reader = phpSpreadSheet::createReader($extension);
            $reader->setLoadSheetsOnly($sheetname);
            $spreadsheet = $reader->load($path);
            $handler = $spreadsheet->getActiveSheet();
            $rows = $handler->getHighestDataRow();
            $data =[];
            $hash = UserInfo::pluck('excel_hash')->toArray();
            $duplicate_counter = 0;
            $saved_counter=0;
            $error_counter=0;
            
            for($r=2;$r<=$rows;$r++){
                
                $fname = $handler->getCellByColumnAndRow(1, $r)->getValue();
                $mname = $handler->getCellByColumnAndRow(2, $r)->getValue();
                $lname = $handler->getCellByColumnAndRow(3, $r)->getValue();
                $birthdate = $handler->getCellByColumnAndRow(5, $r)->getFormattedValue();
                $position = $handler->getCellByColumnAndRow(17, $r)->getOldCalculatedValue();
                $concat = $fname.$mname.$lname;

                // $ch_email="";
                // $ch_sss="";
                // $ch_ph="";
                // $ch_pagibig="";
                // $ch_tin="";

                // if (filter_var($handler->getCellByColumnAndRow(7, $r)->getValue(), FILTER_VALIDATE_EMAIL)) {
                //     $ch_email = true;
                // }else{
                //     $ch_email = false;
                // }


                if($position>1){
                    if(in_array($concat,$hash)){
                        $duplicate_counter++;
                        // return json_encode("error catched");
                    }else{
                        
                        $userinfo = new UserInfo;
                        $userinfo->firstname=$fname;
                        $userinfo->lastname=$lname;
                        $userinfo->middlename=$mname;
                        $userinfo->address=$handler->getCellByColumnAndRow(6, $r)->getValue();
                        $userinfo->birthdate=$birthdate;
                        $userinfo->gender=$handler->getCellByColumnAndRow(4, $r)->getValue();
                        $userinfo->salary_rate=$handler->getCellByColumnAndRow(15, $r)->getValue();
                        $userinfo->status="Active";
                        $userinfo->contact_number=$handler->getCellByColumnAndRow(8, $r)->getValue();
                        $userinfo->hired_date=$handler->getCellByColumnAndRow(16, $r)->getFormattedValue();
                        $userinfo->save();
                        $user = new User;
                        $user->uid= $userinfo->id;
                        $user->email = $handler->getCellByColumnAndRow(7, $r)->getValue();
                        $user->password = $fname.$lname;
                        $user->access_id = $handler->getCellByColumnAndRow(17, $r)->getOldCalculatedValue();
                        $user->save();
        
                        $obj_benefit=[];
                        for($l=0;$l<4;$l++){
                            $obj_benefit[]=['user_info_id'=>$userinfo->id,'benefit_id'=>$l+1,'id_number'=>$handler->getCellByColumnAndRow($l+9, $r)->getValue(),];
                        }
                        UserBenefit::insert($obj_benefit);

                        $access_level_hierarchy = new AccessLevelHierarchy;
                        $access_level_hierarchy->child_id = $userinfo->id;
                        $access_level_hierarchy->parent_id = $handler->getCellByColumnAndRow(19, $r)->getOldCalculatedValue();
                        $check = $access_level_hierarchy->save();
                        if($check){
                            $saved_counter++;
                            $userinfo->excel_hash = $fname.$mname.$lname;
                            $userinfo->save();
                        }
                    } 
                }
            }
            $return_data[]=[
                'saved_count' => $saved_counter,
                'duplicate_counter' => $duplicate_counter,
            ];
            return response()->json($return_data);
        }
    }
}
