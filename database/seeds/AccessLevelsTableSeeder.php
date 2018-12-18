<?php

use Illuminate\Database\Seeder;
use App\Data\Models\AccessLevel;

class AccessLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array(
                'code' => 'superadmin', 
                'name' => 'Admin', 
                'parent'=>null,
            ),
            array(
                'code' => 'hrm', 
                'name' => 'HR Manager',
                'parent' => 1,
            ),
            array(
                'code' => 'hrmassistant', 
                'name' => 'HR Assistant',
                'parent' => 2,
            ),
            array(
                'code' => 'itsupervisor', 
                'name' => 'IT Supervisor',
                'parent' => 1,
            ),
            array(
                'code' => 'itspecialist', 
                'name' => 'IT Specialist',
                'parent' => 4,
            ),
            array(
                'code' => 'itsupport', 
                'name' => 'IT Support',
                'parent' => 4,
            ),
            array(
                'code' => 'maintenancestaff', 
                'name' => 'Maintenance Staff',
                'parent' => 2,
            ),
            array(
                'code' => 'tqmanager', 
                'name' => 'T & Q Manager',
                'parent' => 1,
            ),
            array(
                'code' => 'producttrainer', 
                'name' => 'Product Trainer',
                'parent' => 8,
            ),
            array(
                'code' => 'qasupervisor', 
                'name' => 'QA Supervisor',
                'parent' => 8,
            ),
            array(
                'code' => 'qaanalyst', 
                'name' => 'Quality Assurance Analyst',
                'parent' => 10,
            ),
            array(//12
                'code' => 'rtamanager', 
                'name' => 'RTA Manager',
                'parent' => 1,
            ),
            array(//13
                'code' => 'rtasupervisor', 
                'name' => 'RTA Supervisor',
                'parent' => 12,
            ),
            array(//14
                'code' => 'rtaanalyst', 
                'name' => 'RTA Analyst',
                'parent' => 13,
            ),
            array(//15
                'code' => 'operationsmanager', 
                'name' => 'Operations Manager',
                'parent' => 1,
            ),
            array(//16
                'code' => 'teamleader', 
                'name' => 'Team Leader',
                'parent' => 15,
            ),
            array(//17
                'code' => 'representative_op', 
                'name' => 'Representative - Order Placer',
                'parent' => 16,
            ),
            array(//18
                'code' => 'accountant', 
                'name' => 'Accountant',
                'parent' => 2,
            ),
            array(//19
                'code' => 'financeofficer', 
                'name' => 'Finance Officer',
                'parent' => 1,
            ),
            array(//20
                'code' => 'payrollassistant', 
                'name' => 'Payroll Assistant',
                'parent' => 19,
            ),
            
        );

        AccessLevel::insert($data);
    }
}
