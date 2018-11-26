<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use DateTime;
use phpSpreadSheet;
use Xlsx;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Exports\exportTemplateSheet;
use App\Exports\exportReportSheet;
use App\Imports\importEmployee;
use App\UserInfo;
use App\User;
use App\UserBenefit;
use App\AccessLevel;
use App\AccessLevelHierarchy;
use App\ExcelTemplateValidator;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class excelController extends Controller
{
    function Addtemplate(){
        $filename = "Add-Template-".now().".xlsx"; //filename
        $spreadsheet = new Spreadsheet();
        //add template sheet
        $header = [
            'First Name',
            'Middle Name',
            'Last Name',
            'Gender',
            'Birth Date',
            'Address',
            'PersonalEmail',
            'CompanyEmail',
            'Contact No.',
            'SSS',
            'PhilHealth',
            'PagIbig',
            'TIN',
            'Position',
            'CompanyID',
            'Salary',
            'Hired Date',
            'Status',
            'Contract',
            'Status Reason',
            'Separation Date',
        ];
        $worksheet = $spreadsheet->getActiveSheet(0);
        $worksheet->fromArray($header,null,'A1');
        $worksheet->setTitle("Add");
        
        //position sheet
        $worksheet = new Worksheet($spreadsheet, 'Position');
        $worksheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
        $spreadsheet->addSheet($worksheet);
        $access_level = AccessLevel::all()->toArray();
        $worksheet->fromArray($access_level,null,'A1');

        //Gender worksheet
        $worksheet = new Worksheet($spreadsheet, 'Gender');
        $worksheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
        $spreadsheet->addSheet($worksheet);
        $tmp=['Male','Female'];
        $worksheet->fromArray($tmp,null,'A1');

        //Gender worksheet
        $worksheet = new Worksheet($spreadsheet, 'Status');
        $worksheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
        $spreadsheet->addSheet($worksheet);
        $tmp=['New_Hired','Active','Inactive'];
        $worksheet->fromArray($tmp,null,'A1');


        //config sheet
        $token = DB::table('excel_template_validators')->where('template','Add')->pluck('token');
        $config=[
            'Add',
            $token[0],
        ];
        $worksheet = new Worksheet($spreadsheet, 'config');
        $worksheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
        $spreadsheet->addSheet($worksheet);
        $worksheet->fromArray($config,null,'A1');

        // //defining named range
        $spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('gender',$spreadsheet->getSheetByName('Gender'),'1:1'));
        $spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('status',$spreadsheet->getSheetByName('Status'),'1:1'));
        $spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('position',$spreadsheet->getSheetByName('Position'),'C:C'));
        
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->setPreCalculateFormulas(false);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename); 
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    function Reassigntemplate(){
        $filename = "Reassign-Template-".now().".xlsx"; //filename
        $spreadsheet = new Spreadsheet();
        //reassign sheet
        $header = [
            'ID',
            'Full Name',
            'Position',
            'Current Parent',
            'New Parent',
            'Parent Code',
            'Parent ID',
        ];
        $worksheet = $spreadsheet->getActiveSheet(0);
        $worksheet->fromArray($header,null,'A1');
        $worksheet->setTitle("Reassign");
        $employee = DB::table('user_infos')
        ->join('users','user_infos.id','=','users.uid')
        ->join('access_levels','users.access_id','=','access_levels.id')
        ->join('access_level_hierarchies','user_infos.id','=','access_level_hierarchies.child_id')
        ->select('user_infos.id as id',DB::raw('concat_ws(" ",user_infos.firstname,user_infos.middlename,user_infos.lastname) as fullname'),'access_levels.name as position','access_level_hierarchies.parent_id as parent_id')
        ->where([['user_infos.status','=','Active'],['access_levels.id','>',1]])
        ->get();
        foreach($employee as $k => $datum){
            $tmp = DB::table('user_infos')
                ->join('users','user_infos.id','=','users.uid')
                ->join('access_levels','users.access_id','=','access_levels.id')
                ->select(DB::raw('concat_ws(" ",user_infos.firstname,user_infos.middlename, user_infos.lastname) as fullname'),'access_levels.code as code')
                ->where([['user_infos.id','=',$datum->parent_id],['user_infos.status','!=','Terminated']])
                ->get();
            
                $id = $datum->id;
                $emp_name = $datum->fullname;
                $emp_pos = $datum->position;
                $pa_pcode = '';
                $pa_name = '';

            if($datum->parent_id!=null){
                $pa_pcode = $tmp[0]->code;
                $pa_name = $tmp[0]->fullname;
            }else{
                $alfunction = new AccessLevel;
                $pa_pcode = $alfunction->getParentCodeByPositionName($datum->position);
                $pa_name ="";
            }

            $worksheet->setCellValue('A'.($k+2),$id);
            $worksheet->setCellValue('B'.($k+2),$emp_name);
            $worksheet->setCellValue('C'.($k+2),$emp_pos);
            $worksheet->setCellValue('D'.($k+2),$pa_name);
            $worksheet->setCellValue('F'.($k+2),$pa_pcode);
            if($k==0){
            $worksheet->setCellValue('G'.($k+2),'"=IF([New Parent]<>"",INDEX(Employee!A:A,MATCH([New Parent],Employee!B:B,0),0),"")');
            }
        }

        //employee sheet
        $worksheet = new Worksheet($spreadsheet, 'Employee');
        $worksheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
        $spreadsheet->addSheet($worksheet);
        $employee = DB::table('user_infos')
        ->join('users','user_infos.id','=','users.uid')
        ->select('user_infos.id as id',DB::raw('concat_ws(" ",user_infos.firstname,user_infos.middlename,user_infos.lastname) as fullname'),'users.access_id as access_id')
        ->where('user_infos.status','!=','Terminated')
        ->get();

        foreach($employee as $k => $datum){
            $worksheet->setCellValue('A'.($k+1),$datum->id);
            $worksheet->setCellValue('B'.($k+1),$datum->fullname);
            $worksheet->setCellValue('C'.($k+1),$datum->access_id);
        // $worksheet->fromArray($employee,null,'A1');
        }
        //parent worksheet
        $worksheet = new Worksheet($spreadsheet, 'Parent');
        $worksheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
        $spreadsheet->addSheet($worksheet);
        $parent_id_array = AccessLevel::groupBy('parent')->pluck('parent')->toArray();
        $tmp=[];
        $tmp1=[];
        foreach($parent_id_array as $k => $datum){
            $tmp[] = DB::table('user_infos')
            ->join('users','users.uid','=','user_infos.id')
            ->select(DB::raw('concat_ws(" ",user_infos.firstname,user_infos.middlename,user_infos.lastname) as fullname'))
            ->where([['users.access_id','=',$datum],['user_infos.status','!=','Terminated'],])
            ->pluck('fullname')->toArray();
            if(empty($datum)){
                $tmp1[] = 'superadmin';
            }else{
                $dt = AccessLevel::find($datum);
                $tmp1[] = $dt->code;
            }
        }
        foreach($tmp as $k => $datum){
            $worksheet->fromArray($datum,null,'A'.($k));
        }
        //get code
        foreach($tmp1 as $k => $datum){
            $spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange($datum,$spreadsheet->getSheetByName('Parent'),($k).':'.($k)));
        }
        //config sheet
        $token = DB::table('excel_template_validators')->where('template','Reassign')->pluck('token');
        $config=[
            'Reassign',
            $token[0],
        ];
        $worksheet = new Worksheet($spreadsheet, 'config');
        $worksheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
        $spreadsheet->addSheet($worksheet);
        $worksheet->fromArray($config,null,'A1');

        // //defining named range
        
        // $spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('hrm',$spreadsheet->getSheetByName('Parent'),'2:2'));
        // $spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('om',$spreadsheet->getSheetByName('Parent'),'3:3'));
        // $spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('rtam',$spreadsheet->getSheetByName('Parent'),'4:4'));
        // $spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('tqm',$spreadsheet->getSheetByName('Parent'),'5:5'));
        // $spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('tl',$spreadsheet->getSheetByName('Parent'),'6:6'));
        
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->setPreCalculateFormulas(false);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename); 
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    function report(){
        // $name = "Export-Report-";
        // $name.= now();
        // return (new exportReportSheet)->download($name.'.xlsx');
        $filename = "Report-Template-".now().".xlsx"; //filename
        $spreadsheet = new Spreadsheet();
        //add template sheet
        $header = [
            'CID',
            'First Name',
            'Middle Name',
            'Last Name',
            'Status',
            'Gender',
            'Birth Date',
            'Address',
            'PersonalEmail',
            'CompanyEmail',
            'Contact No.',
            'SSS',
            'Philhealth',
            'PagIbig',
            'TIN',
            'Position',
            'Hired Date',
            'Separation Date',
        ];
        $worksheet = $spreadsheet->getActiveSheet(0);
        $worksheet->fromArray($header,null,'A1');
        $userInfo = new UserInfo;
        $employee = $userInfo->getAllEmployee();
        $worksheet->setTitle("Report");
        foreach($employee as $k => $datum){
            $worksheet->setCellValue('A'.($k+2),$datum->company_id);
            $worksheet->setCellValue('B'.($k+2),$datum->firstname);
            $worksheet->setCellValue('C'.($k+2),$datum->middlename);
            $worksheet->setCellValue('D'.($k+2),$datum->lastname);
            $worksheet->setCellValue('E'.($k+2),$datum->status);
            $worksheet->setCellValue('F'.($k+2),$datum->gender);
            $worksheet->setCellValue('G'.($k+2),$datum->birthdate);
            $worksheet->setCellValue('H'.($k+2),$datum->address);
            $worksheet->setCellValue('I'.($k+2),$datum->p_email);
            $worksheet->setCellValue('J'.($k+2),$datum->email);
            $worksheet->setCellValue('K'.($k+2),$datum->contact_number);
            $worksheet->setCellValue('L'.($k+2),$datum->col1);
            $worksheet->setCellValue('M'.($k+2),$datum->col2);
            $worksheet->setCellValue('N'.($k+2),$datum->col3);
            $worksheet->setCellValue('O'.($k+2),$datum->col4);
            $worksheet->setCellValue('P'.($k+2),$datum->name);
            $worksheet->setCellValue('Q'.($k+2),$datum->hired_date);
            $worksheet->setCellValue('R'.($k+2),$datum->separation_date);
        }
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->setPreCalculateFormulas(false);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename); 
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }



    function import(Request $request){
        if($request->hasFile('excel_file')){
            $path = $request->file('excel_file')->getRealPath();
            $extension =  phpSpreadSheet::identify($path);
            $reader = phpSpreadSheet::createReader($extension);
            $spreadsheet = $reader->load($path);
            $action = "";
            $outdated = false;
            $handler = $spreadsheet->setActiveSheetIndexByName('config');
            $action = $handler->getCellByColumnAndRow(1, 1)->getValue();
            $file_token = $handler->getCellByColumnAndRow(2, 1)->getValue();
            $token = DB::table('excel_template_validators')->where('template',$action)->pluck('token');
            $data =[];
            $duplicate_counter = 0;
            $saved_counter=0;
            $error_counter=0;
            $reassign_counter = 0;
            $error_rows=[];
            $limit = 100;
            $lesslimit = 0;

            if($action=='Add'){
                //Add
                // if($file_token==$token[0]){
                    $handler = $spreadsheet->setActiveSheetIndexByName($action);
                    $rows = $handler->getHighestDataRow();    
                    for($r=2;$r<=$rows;$r++){
                        $taken_email = User::pluck('email')->toArray();
                        $taken_pemail = UserInfo::pluck('p_email')->toArray();
                        $hash = UserInfo::pluck('excel_hash')->toArray();
                        $fname = $handler->getCellByColumnAndRow(1, $r)->getValue();
                        $mname = $handler->getCellByColumnAndRow(2, $r)->getValue();
                        $lname = $handler->getCellByColumnAndRow(3, $r)->getValue();
                        $gender=$handler->getCellByColumnAndRow(4, $r)->getValue();
                        $birthdate = $handler->getCellByColumnAndRow(5, $r)->getFormattedValue();
                        $address=$handler->getCellByColumnAndRow(6, $r)->getValue();
                        $p_email = $handler->getCellByColumnAndRow(7, $r)->getValue();
                        $email = $handler->getCellByColumnAndRow(8, $r)->getValue();
                        $contact_number=$handler->getCellByColumnAndRow(9, $r)->getValue();
                        $sss=$handler->getCellByColumnAndRow(10, $r)->getValue();
                        $philhealth=$handler->getCellByColumnAndRow(11, $r)->getValue();
                        $pagibig=$handler->getCellByColumnAndRow(12, $r)->getValue();
                        $tin=$handler->getCellByColumnAndRow(13, $r)->getValue();
                        $position_id = new AccessLevel;
                        $position = $position_id->getIdByPositionName($handler->getCellByColumnAndRow(14, $r)->getValue());
                        $company_id = $handler->getCellByColumnAndRow(15, $r)->getValue();
                        $salary_rate=(int)$handler->getCellByColumnAndRow(16, $r)->getValue();
                        $hired_date=$handler->getCellByColumnAndRow(17, $r)->getFormattedValue();
                        $status = $handler->getCellByColumnAndRow(18, $r)->getValue();
                        $contract = $handler->getCellByColumnAndRow(19, $r)->getValue();
                        $s_reason = $handler->getCellByColumnAndRow(20, $r)->getValue();
                        $s_date = $handler->getCellByColumnAndRow(21, $r)->getFormattedValue();
                        $concat = str_replace(' ', '', strtolower($fname.$mname.$lname));
                        $errorlog = 0;
                        $this_duplicate = 0;
                        //Manual error Filter
                        //email
        
                        if(in_array($concat,$hash)){
                            $duplicate_counter++;
                            $this_duplicate=1;
                        }else{
                            if(in_array(strtolower($email),$taken_email)){
                                $errorlog++;
                            }
                            // if(in_array(strtolower($p_email),$taken_pemail)){
                            //     $errorlog++;
                            // }
                        }

                        // if (!filter_var($email, FILTER_VALIDATE_EMAIL)||!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        //     $errorlog++;
                        // }
                        // BLANK strings
                        if(($fname==""&&$mname==""&&$lname=="")||$position==""){
                            $errorlog++;
                        }
                        //numeric inputs
                        // if(!is_numeric($contact_number)||!is_numeric($sss)||!is_numeric($phil
                        //     health)||!is_numeric($pagibig)||!is_numeric($tin)||!is_numeric($salary_rate)){
                        //     $errorlog++;
                        // }
        
                        if($errorlog==0&&$this_duplicate==0){
                            if($position>1){
                                $userinfo = new UserInfo;
                                $userinfo->firstname=$fname;
                                $userinfo->lastname=$lname;
                                $userinfo->middlename=$mname;
                                $userinfo->address=$address;
                                $userinfo->birthdate=$birthdate;
                                $userinfo->gender=$gender;
                                $userinfo->salary_rate=$salary_rate;
                                $userinfo->status=$status;
                                $userinfo->contact_number=$contact_number;
                                $userinfo->hired_date=$hired_date;
                                $userinfo->p_email=$p_email;
                                $userinfo->status_reason=$s_reason;
                                $userinfo->separation_date=$s_date;
                                $s_userinfo=$userinfo->save();
                                if(!$s_userinfo){
                                    $error_rows[]=$r;
                                }
                                $user = new User;
                                $user->uid= $userinfo->id;
                                $user->email = strtolower($email);
                                $user->password = str_replace(' ', '', strtolower($fname.$lname));
                                $user->access_id = $position;
                                $user->company_id = $company_id;
                                $user->contract = $contract;
                                if(!$user->save()){
                                    $error_rows[]=$r;
                                    UserInfo::find($userinfo->id)->delete();
                                }
                
                                $obj_benefit=[];
                                for($l=0;$l<4;$l++){
                                    $obj_benefit[]=['user_info_id'=>$userinfo->id,'benefit_id'=>$l+1,'id_number'=>$handler->getCellByColumnAndRow($l+10, $r)->getValue(),];
                                }
                                UserBenefit::insert($obj_benefit);
        
                                $access_level_hierarchy = new AccessLevelHierarchy;
                                $access_level_hierarchy->child_id = $userinfo->id;
                                $access_level_hierarchy->parent_id = null;
                                $check = $access_level_hierarchy->save();
                                if($check){
                                    $saved_counter++;
                                    $userinfo->excel_hash = $concat;
                                    $userinfo->save();
                                }
                            }

                        }else if($errorlog>0){
                            $error_rows[]=$r;
                        }

                        if($rows>($r+$limit)){
                            if($saved_counter==$limit){
                                break;
                            }
                        }else{
                            $lesslimit++; 
                        }

                         
                    }
                // }else{
                //     $outdated = true;
                // }   
            }else if($action=="Reassign"){
                //Reassign
                if($file_token == $token[0]){
                    $handler = $spreadsheet->setActiveSheetIndexByName($action);
                    $rows = $handler->getHighestDataRow();
                    for($r=2;$r<=$rows;$r++){
                        $id = $handler->getCellByColumnAndRow(1, $r)->getValue();
                        $parent_id = $handler->getCellByColumnAndRow(7, $r)->getOldCalculatedValue();
                        if($parent_id!=""){
                            //delete records
                            $del = AccessLevelHierarchy::where('child_id','=',$id)->delete();
                            //create new record
                            $new = new AccessLevelHierarchy;
                            $new->child_id = $id;
                            $new->parent_id = $parent_id;
                            $new->save();
                            $reassign_counter++;
                        }
                        
                    }
                }else{
                    $outdated = true;
                }
                
            }


            if(($saved_counter>0)||($reassign_counter>0)){
                $etv = new ExcelTemplateValidator;
                $etv = $etv->updateExcelToken("Reassign");
            }

            //return values

            $return_data[]=[
                'saved_counter' => $saved_counter,
                'duplicate_counter' => $duplicate_counter,
                'error_rows'=>$error_rows,
                'reassign_counter'=>$reassign_counter,
                'outdated' => $outdated,
                'action'=>$action,
            ];

            echo json_encode($return_data);
            exit;
        }else{
            echo json_encode('File not valid.');
        }
    }
}
