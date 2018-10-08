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
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class excelController extends Controller
{
    function template(){
        // $name = "Employee-Template-";
        // $name.= now();
        // return (new exportTemplateSheet)->download($name.'.xlsx');
        $filename = "Employee-Template-".now().".xlsx"; //filename
       
        $spreadsheet = new Spreadsheet();
        
        //add template sheet
        $header = [
            'First Name',
            'Middle Name',
            'Last Name',
            'Gender',
            'Birth Date',
            'Address',
            'Email',
            'Contact No.',
            'SSS',
            'PhilHealth',
            'PagIbig',
            'TIN',
            'Position',
            'Parent Name',
            'Salary',
            'Hired Date',
            'Position ID',
            'Parent AID',
            'Parent Code',
            'Parent ID',
        ];
        $worksheet = $spreadsheet->getActiveSheet(0);
        $worksheet->fromArray($header,null,'A1');
        $formula = DB::table('excel_functions')->where('id','=',1)->get();
        $worksheet->setCellValue('Q2',$formula[0]->formula1);
        $worksheet->setCellValue('R2',$formula[0]->formula2);
        $worksheet->setCellValue('S2',$formula[0]->formula3);
        $worksheet->setCellValue('T2',$formula[0]->formula4);
        $worksheet->setTitle("Add");
        //reassign sheet
        $worksheet = new Worksheet($spreadsheet, 'Reassign');
        $header = [
            'ID',
            'Full Name',
            'Position',
            'Current Parent',
            'New Parent',
            'Parent Code',
            'Parent ID',
        ];
        $worksheet->fromArray($header,null,'A1');
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
            $worksheet->setCellValue('A'.($k+2),$datum->id);
            $worksheet->setCellValue('B'.($k+2),$datum->fullname);
            $worksheet->setCellValue('C'.($k+2),$datum->position);
            $worksheet->setCellValue('F'.($k+2),$tmp[0]->code);
            $worksheet->setCellValue('G'.($k+2),'"=IF([New Parent]<>"",INDEX(Employee!A:A,MATCH([New Parent],Employee!B:B,0),0),"")');
            $worksheet->setCellValue('D'.($k+2),$tmp[0]->fullname);
        }
        $spreadsheet->addSheet($worksheet);
        //position sheet
        $worksheet = new Worksheet($spreadsheet, 'Position');
        $worksheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
        $spreadsheet->addSheet($worksheet);
        $access_level = AccessLevel::all()->toArray();
        $worksheet->fromArray($access_level,null,'A1');
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
        foreach($parent_id_array as $datum){
            $tmp[] = DB::table('user_infos')
            ->join('users','users.uid','=','user_infos.id')
            ->select(DB::raw('concat_ws(" ",user_infos.firstname,user_infos.middlename,user_infos.lastname) as fullname'))
            ->where([['users.access_id','=',$datum],['user_infos.status','!=','Terminated'],])
            ->pluck('fullname')->toArray();
        }
        foreach($tmp as $k => $datum){
            $worksheet->fromArray($datum,null,'A'.($k));
        }






        // //defining named range
        $spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('superadmin',$spreadsheet->getSheetByName('Parent'),'1:1'));
        $spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('hrm',$spreadsheet->getSheetByName('Parent'),'2:2'));
        $spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('om',$spreadsheet->getSheetByName('Parent'),'3:3'));
        $spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('rtam',$spreadsheet->getSheetByName('Parent'),'4:4'));
        $spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('tqm',$spreadsheet->getSheetByName('Parent'),'5:5'));
        $spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('tl',$spreadsheet->getSheetByName('Parent'),'6:6'));
        $spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('position',$spreadsheet->getSheetByName('Position'),'C:C'));
        
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->setPreCalculateFormulas(false);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename); 
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }


    function report(){
        $name = "Import-Report-";
        $name.= now();
        return (new exportReportSheet)->download($name.'.xlsx');
    }



    function import(Request $request){
        if($request->hasFile('excel_file')){
            $path = $request->file('excel_file')->getRealPath();
            $extension =  phpSpreadSheet::identify($path);
            $reader = phpSpreadSheet::createReader($extension);
            $spreadsheet = $reader->load($path);
            $handler = $spreadsheet->setActiveSheetIndexByName('Employee');
            $rows = $handler->getHighestDataRow();
            $emp = UserInfo::where('user_infos.status','!=','Terminated')->get();
            if(intval($rows)!=count($emp)){
                return response()->json("outdated");
                exit;
            }
            $handler = $spreadsheet->setActiveSheetIndexByName('Add');
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
                $parent_aid = $handler->getCellByColumnAndRow(18, $r)->getOldCalculatedValue();
                $email = $handler->getCellByColumnAndRow(7, $r)->getValue();
                $contact_number=$handler->getCellByColumnAndRow(8, $r)->getValue();
                $hired_date=$handler->getCellByColumnAndRow(16, $r)->getFormattedValue();
                $designation=$handler->getCellByColumnAndRow(20, $r)->getOldCalculatedValue();
                $address=$handler->getCellByColumnAndRow(6, $r)->getValue();
                $gender=$handler->getCellByColumnAndRow(4, $r)->getValue();
                $salary_rate=$handler->getCellByColumnAndRow(15, $r)->getValue();
                $sss=$handler->getCellByColumnAndRow(9, $r)->getValue();
                $philhealth=$handler->getCellByColumnAndRow(10, $r)->getValue();
                $pagibig=$handler->getCellByColumnAndRow(11, $r)->getValue();
                $tin=$handler->getCellByColumnAndRow(12, $r)->getValue();
                $concat = strtolower($fname.$mname.$lname);
                $errorlog = 0;
                $reassign_counter = 0;
                //Manual error Filter
                //email
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errorlog++;
                }
                if(in_array($email,$taken_email)){
                    $errorlog++;
                }
                //BLANK strings
                if($fname==""||$mname==""||$lname==""||$birthdate==""||$position==""||$email==""||$contact_number==""||$hired_date==""||$designation==""||$address==""||$gender==""||$salary_rate==""){
                    $errorlog++;
                }
                //numeric inputs
                if(!is_numeric($contact_number)||!is_numeric($sss)||!is_numeric($philhealth)||!is_numeric($pagibig)||!is_numeric($tin)||!is_numeric($salary_rate)){
                    $errorlog++;
                }

                $checker = new AccessLevel;
                $checker->getParentLevel($position);
                if(intval($checker->getParentLevel($position)) != intval($parent_aid)){
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
                                $userinfo->excel_hash = $concat;
                                $userinfo->save();
                            }
                        } 
                    }
                }else{
                    $error_rows[]=$r;
                }
            }

            //Reassign

            $handler = $spreadsheet->setActiveSheetIndexByName('Reassign');
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
            $return_data[]=[
                'saved_counter' => $saved_counter,
                'duplicate_counter' => $duplicate_counter,
                'error_rows'=>$error_rows,
                'error_counter'=>count($error_rows),
                'reassign_counter'=>$reassign_counter,
            ];
            return response()->json($return_data);
        }
    }
}
