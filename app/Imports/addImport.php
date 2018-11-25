<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use App\UserInfo;
use App\User;
use App\UserBenefit;
use App\AccessLevelHierarchy;
use App\AccessLevel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class addImport implements ToModel,WithHeadingRow,WithChunkReading,WithBatchInserts
{

    /**
    * @param Collection $collection
    */
    use Importable;
    // protected $user, $userbenefit,$userhierarchy;
    // public function _construct(){
    //     $this->user=[];
    //     $this->userbenefit=[];
    //     $this->userhierarchy=[];
    // }

    public function model(array $row)
    {
        
        
        $email = User::pluck('email')->toArray();
        if(in_array($row['companyemail'],$email)){
            return null;
        }

        $userinfo =  UserInfo::create([
        'firstname' => $row['first_name'],
        'middlename' => $row['middle_name'],
        'lastname' => $row['last_name'],
        'gender' => $row['gender'],
        'birthdate' => $row['birth_date'],
        'address' => $row['address'],
        'p_email' => $row['personalemail'],
        'contact_number' => $row['contact_no'],
        'excel_hash' => str_replace(' ', '', strtolower($row['first_name'].$row['middle_name'].$row['last_name'])),
        // 'status' => $row['status'],
        'status' => 'Active',
        'salary_rate' => $row['salary'],
        'hired_date' => $row['hired_date'],
        ]);
        $userinfo->user()->create([
            'company_id' => $row['companyid'],
            'email' => $row['companyemail'],
            'password' => str_replace(' ', '', strtolower($row['first_name'].$row['last_name'])),
            'access_id' => 13,
        ]);
        $userinfo->benefits()->createMany([
            ['benefit_id' =>1,'id_number'=>$row['sss']],
            ['benefit_id' =>2,'id_number'=>$row['philhealth']],
            ['benefit_id' =>3,'id_number'=>$row['pagibig']],
            ['benefit_id' =>4,'id_number'=>$row['tin']],
        ]);
        AccessLevelHierarchy::create(['child_id'=>$userinfo->id]);
    }

     public function headingRow(): int
    {
        return 1;
    }
    public function batchSize(): int
    {
        return 100;
    }
    
    public function chunkSize(): int
    {
        return 100;
    }
}
