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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


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
            'SID',
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
        ->where([['user_infos.status','=','active'],['access_levels.id','>',1]])
        ->get();
        foreach($employee as $k => $datum){
            $tmp = DB::table('user_infos')
                ->join('users','user_infos.id','=','users.uid')
                ->join('access_levels','users.access_id','=','access_levels.id')
                ->select(DB::raw('concat_ws(" ",user_infos.firstname,user_infos.middlename, user_infos.lastname) as fullname'),'access_levels.code as code')
                ->where([['user_infos.id','=',$datum->parent_id],['user_infos.status','!=','inactive']])
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
        ->where('user_infos.status','!=','inactive')
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
            ->where([['users.access_id','=',$datum],['user_infos.status','!=','inactive'],['user_infos.id','!=',3]])
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



    function importToArray(Request $request){
        if($request->hasFile('excel_file')){
            $object=[];
            $path = $request->file('excel_file')->getRealPath();
            $extension =  phpSpreadSheet::identify($path);
            $reader = phpSpreadSheet::createReader($extension);
            $spreadsheet = $reader->load($path);
            $object['outdated'] = false;
            $sheets = $spreadsheet->getSheetNames();
            if(!in_array('config',$sheets)){
                echo json_encode('Excel Not Recognized.');
                exit;
            }
            $handler = $spreadsheet->setActiveSheetIndexByName('config');
            $object['action'] = $handler->getCellByColumnAndRow(1, 1)->getValue();
            $file_token = $handler->getCellByColumnAndRow(2, 1)->getValue();
            $token = DB::table('excel_template_validators')->where('template',$object['action'])->pluck('token');
            if(in_array($file_token, $token)){
                if($object['action']=='Add'){
                    $handler = $spreadsheet->setActiveSheetIndexByName($object['action']);
                    $object['arr'] = $handler->toArray();
                    $object['arr'] = array_chunk($object['arr'],1);
                    $object['saved'] = 0;
                    $object['error'] = 0;
                    $object['duplicate'] = 0;
                }else if($object['action']=='Reassign'){
                    $object['reassign'] = 0;
                    $handler = $spreadsheet->setActiveSheetIndexByName($object['action']);
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
                            $object['reassign']++;
                        }
                    }
                    if($object['reassign']>0){
                        $etv = new ExcelTemplateValidator;
                        $etv = $etv->updateExcelToken("Reassign");
                    }
                }
                
            }else{
                echo json_encode('Template is outdated.');
                exit;
            }
            echo json_encode($object);
            exit;
        }else{
            echo json_encode('File not valid.');
        }
    }

    function importStoreAdd(Request $request){
        $concat = str_replace(' ','',strtolower($request->obj[0].$request->obj[1].$request->obj[2]));
        $hash = UserInfo::pluck('excel_hash')->toArray();
        $email = str_replace(' ','',strtolower($request->obj[7]));
        $taken_email = User::pluck('email')->toArray();
        $position_id = new AccessLevel;
        $position = $position_id->getIdByPositionName($request->obj[13]);
        $sys_position = AccessLevel::pluck('name')->toArray();
        $error=0;
        $duplicate=0;
        $insertstatus=0; //0=saved, 1=duplicate, 2=error

        
        if(in_array($concat,$hash)){
            $duplicate=1;
        }

        if(in_array($email,$taken_email)){
            $error++;
        }
        if(in_array($position,$sys_position)){
            $error++;
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error++;
        }

        if(($request->obj[0]==""&&$request->obj[1]==""&&$request->obj[2]=="")||$request->obj[13]==""){
            $error++;
        }
        if(($error==0) && ($duplicate==0)){
            if($position>1){
                $userinfo = new UserInfo;
                $userinfo->firstname= $request->obj[0];
                $userinfo->lastname= $request->obj[1];
                $userinfo->middlename= $request->obj[2];
                $userinfo->address= $request->obj[5];
                $userinfo->birthdate= $request->obj[4];
                $userinfo->gender= $request->obj[3];
                $userinfo->salary_rate= $request->obj[15];
                $userinfo->status= $request->obj[17];
                $userinfo->contact_number= $request->obj[8];
                $userinfo->hired_date= $request->obj[16];
                $userinfo->p_email= $request->obj[6];
                $userinfo->status_reason= $request->obj[19];
                $userinfo->separation_date= $request->obj[20];
                $userinfo->excel_hash = $concat;
                $s_userinfo=$userinfo->save();

                $user = new User;
                $user->uid =  $userinfo->id;
                $user->company_id =  $request->obj[14];
                $user->access_id =  $position;
                $user->email =  strtolower($request->obj[7]);
                $user->password =  str_replace(' ','', strtolower($request->obj[0].$request->obj[2]));
                $user->contract =  $request->obj[18];
                $user->save();

                $benefit = new UserBenefit;
                $benefit->user_info_id = $userinfo->id;
                $benefit->benefit_id = 1;
                $benefit->id_number = $request->obj[9];
                $benefit->save();

                $benefit = new UserBenefit;
                $benefit->user_info_id = $userinfo->id;
                $benefit->benefit_id = 2;
                $benefit->id_number = $request->obj[10];
                $benefit->save();

                $benefit = new UserBenefit;
                $benefit->user_info_id = $userinfo->id;
                $benefit->benefit_id = 3;
                $benefit->id_number = $request->obj[11];
                $benefit->save();

                $benefit = new UserBenefit;
                $benefit->user_info_id = $userinfo->id;
                $benefit->benefit_id = 4;
                $benefit->id_number = $request->obj[12];
                $benefit->save();

                $alh = new AccessLevelHierarchy;
                $alh->child_id = $userinfo->id;
                $alh->save();
                $insertstatus = 0;
            }
        }

        if($duplicate>0){
            $insertstatus=1;
        }
        
        if($error>0){
            $insertstatus=2;
        }

        if($duplicate>0&&$error>0){
            $insertstatus=1;
        }

        if(($insertstatus>0)){
            $etv = new ExcelTemplateValidator;
            $etv = $etv->updateExcelToken("Reassign");
        }
        
        echo json_encode(['status'=>$insertstatus,'row'=>$request->obj]);
    }

}
