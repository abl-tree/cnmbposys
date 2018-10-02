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
            $taken_email = User::pluck('email')->toArray();
            $duplicate_counter = 0;
            $saved_counter=0;
            $error_counter=0;
            $error_rows=[];
            
            for($r=2;$r<=$rows;$r++){
                
                $fname = $handler->getCellByColumnAndRow(1, $r)->getValue();
                $mname = $handler->getCellByColumnAndRow(2, $r)->getValue();
                $lname = $handler->getCellByColumnAndRow(3, $r)->getValue();
                $birthdate = $handler->getCellByColumnAndRow(5, $r)->getFormattedValue();
                $position = $handler->getCellByColumnAndRow(17, $r)->getOldCalculatedValue();
                $email = $handler->getCellByColumnAndRow(7, $r)->getValue();
                $contact_number=$handler->getCellByColumnAndRow(8, $r)->getValue();
                $hired_date=$handler->getCellByColumnAndRow(16, $r)->getFormattedValue();
                $designation=$handler->getCellByColumnAndRow(19, $r)->getOldCalculatedValue();
                $address=$handler->getCellByColumnAndRow(6, $r)->getValue();
                $gender=$handler->getCellByColumnAndRow(4, $r)->getValue();
                $salary_rate=$handler->getCellByColumnAndRow(15, $r)->getValue();
                $sss=$handler->getCellByColumnAndRow(9, $r)->getValue();
                $philhealth=$handler->getCellByColumnAndRow(10, $r)->getValue();
                $pagibig=$handler->getCellByColumnAndRow(11, $r)->getValue();
                $tin=$handler->getCellByColumnAndRow(12, $r)->getValue();
                $concat = $fname.$mname.$lname;
                $errorlog = 0;
                //Manual error Filter
                //email
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errorlog++;
                }
                if(in_array($email,$taken_email)){
                    $errorlog++;
                }
                if($fname==""||$mname==""||$lname==""||$birthdate==""||$position==""||$email==""||$contact_number==""||$hired_date==""||$designation==""||$address==""||$gender==""||$salary_rate==""){
                    $errorlog++;
                }
                if(!is_numeric($contact_number)||!is_numeric($sss)||!is_numeric($philhealth)||!is_numeric($pagibig)||!is_numeric($tin)||!is_numeric($salary_rate)){
                    $errorlog++;
                }

                if($errorlog==0){
                    if($position>1){
                        if(in_array($concat,$hash)){
                            $duplicate_counter++;
                        }else{
                            
                            $userinfo = new UserInfo;
                            $userinfo->firstname=$fname;
                            $userinfo->lastname=$lname;
                            $userinfo->middlename=$mname;
                            $userinfo->address=$address;
                            $userinfo->birthdate=$birthdate;
                            $userinfo->gender=$gender;
                            $userinfo->salary_rate=$salary_rate;
                            $userinfo->status="Active";
                            $userinfo->contact_number=$contact_number;
                            $userinfo->hired_date=$hired_date;
                            $userinfo->save();
                            $user = new User;
                            $user->uid= $userinfo->id;
                            $user->email = $email;
                            $user->password = $fname.$lname;
                            $user->access_id = $position;
                            $user->save();
            
                            $obj_benefit=[];
                            for($l=0;$l<4;$l++){
                                $obj_benefit[]=['user_info_id'=>$userinfo->id,'benefit_id'=>$l+1,'id_number'=>$handler->getCellByColumnAndRow($l+9, $r)->getValue(),];
                            }
                            UserBenefit::insert($obj_benefit);

                            $access_level_hierarchy = new AccessLevelHierarchy;
                            $access_level_hierarchy->child_id = $userinfo->id;
                            $access_level_hierarchy->parent_id = $designation;
                            $check = $access_level_hierarchy->save();
                            if($check){
                                $saved_counter++;
                                $userinfo->excel_hash = $fname.$mname.$lname;
                                $userinfo->save();
                            }
                        } 
                    }
                }else{
                    $error_rows[]=$r;
                }
            }
            $return_data[]=[
                'saved_counter' => $saved_counter,
                'duplicate_counter' => $duplicate_counter,
                'error_rows'=>$error_rows,
                'error_counter'=>count($error_rows),
            ];
            return response()->json($return_data);
        }
    }
}
