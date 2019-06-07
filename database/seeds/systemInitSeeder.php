<?php

use Illuminate\Database\Seeder;
use App\Data\Models\EventTitle;
use App\Data\Models\AccessLevel;
use App\Data\Models\Benefit;
use App\Data\Repositories\UsersInfoRepository;

class systemInitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // AccessLevels
        $this->accessLevels();

        // Benefits
        $this->benefits();

        // Calendar Default Events
        $this->calendarEvents();
        
    }

    // AccessLevels
    public function accessLevels(){
        $data = [
            [
                'code' => 'superadmin', 
                'name' => 'Admin', 
                'parent'=>null,
            ],
            [
                'code' => 'hrm', 
                'name' => 'HR Manager',
                'parent' => 1,
            ],
            [
                'code' => 'hrmassistant', 
                'name' => 'HR Assistant',
                'parent' => 2,
            ],
            [
                'code' => 'itsupervisor', 
                'name' => 'IT Supervisor',
                'parent' => 1,
            ],
            [
                'code' => 'itspecialist', 
                'name' => 'IT Specialist',
                'parent' => 4,
            ],
            [
                'code' => 'itsupport', 
                'name' => 'IT Support',
                'parent' => 4,
            ],
            [
                'code' => 'maintenancestaff', 
                'name' => 'Maintenance Staff',
                'parent' => 2,
            ],
            [
                'code' => 'tqmanager', 
                'name' => 'T & Q Manager',
                'parent' => 1,
            ],
            [
                'code' => 'producttrainer', 
                'name' => 'Product Trainer',
                'parent' => 8,
            ],
            [
                'code' => 'qasupervisor', 
                'name' => 'QA Supervisor',
                'parent' => 8,
            ],
            [
                'code' => 'qaanalyst', 
                'name' => 'Quality Assurance Analyst',
                'parent' => 10,
            ],
            [//12
                'code' => 'rtamanager', 
                'name' => 'RTA Manager',
                'parent' => 1,
            ],
            [//13
                'code' => 'rtasupervisor', 
                'name' => 'RTA Supervisor',
                'parent' => 12,
            ],
            [//14
                'code' => 'rtaanalyst', 
                'name' => 'RTA Analyst',
                'parent' => 13,
            ],
            [//15
                'code' => 'operationsmanager', 
                'name' => 'Operations Manager',
                'parent' => 1,
            ],
            [//16
                'code' => 'teamleader', 
                'name' => 'Team Leader',
                'parent' => 15,
            ],
            [//17
                'code' => 'representative_op', 
                'name' => 'Representative - Order Placer',
                'parent' => 16,
            ],
            [//18
                'code' => 'accountant', 
                'name' => 'Accountant',
                'parent' => 2,
            ],
            [//19
                'code' => 'financeofficer', 
                'name' => 'Finance Officer',
                'parent' => 1,
            ],
            [//20
                'code' => 'payrollassistant', 
                'name' => 'Payroll Assistant',
                'parent' => 19,
            ],
        ];
        foreach ($data as $key => $value) {
            AccessLevel::create($value);
        }
    }
    // Benefit types
    public function benefits(){
        $data = [
            [
                'name' => 'SSS', 
            ], 
            [
                'name' => 'Philhealth', 
            ], 
            [
                'name' => 'Pag-ibig Funds', 
            ], 
            [
                'name' => 'TIN', 
            ], 
        ];

        foreach ($data as $key => $value) {
            Benefit::create($value);
        }
    }
    // Calendar Default Events
    public function calendarEvents(){
        $data = [
            [
                // 1
                'title' =>'work (REG)',
                'color'=>'',
                // 'type'  =>'work',
                // 'generated_by' => 'rta_personnel',
                // 'max_days'=>0,
            ],
            [
                // 2
                'title' => 'work (OT)',
                'color'=>'',
                // 'type'  =>'work',
                // 'generated_by' => 'rta_personnel',
                // 'max_days'=>0,
            ],
            [
                // 3
                'title' => 'vacation leave',
                'color'=>'',
                // 'type' => 'leave',
                // 'generated_by' => 'operations personnel',
                // 'max_days'=>0,
            ],
            [
                // 4
                'title' => 'leave of absence',
                'color'=>'',
                // 'type' => 'leave',
                // 'generated_by' => 'operations personnel',
                // 'max_days'=>0,
            ],
            [
                // 5
                'title' => 'sick leave',
                'color'=>'',
                // 'type' => 'leave',
                // 'generated_by' => 'nurse',
                // 'max_days'=>0,
            ],
            [
                // 6
                'title' => 'maternity leave',
                'color'=>'',
                // 'type' => 'leave',
                // 'generated_by' => 'hr personnel',
                // 'max_days'=>0,
            ],
            [
                // 7
                'title' => 'bereavement leave',
                'color'=>'',
                // 'type' => 'leave',
                // 'generated_by' => 'hr personnel',
                // 'max_days'=>0,
            ],
            [
                // 8
                'title' => 'paternity leave',
                'color'=>'',
                // 'type' => 'leave',
                // 'generated_by' => 'hr personnel',
                // 'max_days'=>0,
            ],
            [
                // 9
                'title' => 'solo parent leave',
                'color'=>'',
                // 'type' => 'leave',
                // 'generated_by' => 'hr personnel',
                // 'max_days'=>0,
            ],
            [
                // 10
                'title' => 'magna carta leave',
                'color'=>'',
                // 'type' => 'leave',
                // 'generated_by' => 'hr personnel',
                // 'max_days'=>0,
            ],
            [
                // 11
                'title' => 'VAWC',
                'color'=>'',
                // 'type' => 'leave',
                // 'generated_by' => 'hr personnel',
                // 'max_days'=>0,
            ],
            [
                // 12
                'title' => 'absent',
                'color'=>'',
                // 'type' => 'leave',
                // 'generated_by' => 'rta personnel',
                // 'max_days'=>0,
            ]
        ];
        foreach ($data as $key => $value) {
            EventTitle::create($value);
        }
    }
    public function initUsers(){
        $data=[
            [
            'firstname' => 'Maricel',
            'middlename' => 'Ramales', 
            'lastname' => 'Obsiana', 
            'birthdate' => '12/9/1986', 
            'address' => 'Carnation Street, Buhangin, Davao City',
            'gender' => 'female', 
            'status' => 'active',
            'type' => 'regular',
            'hired_date' => '2/9/2015',
            'excel_hash' =>strtolower('maricelramalesobsiana'),
            ]
        ];



        foreach($data as $datum){
            $user_info = new UsersInfoRepository();
            $this->absorb($this->user_info->addUser($datum));
        }
    }
}