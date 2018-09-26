@extends('admin.default')

@section('content')
<!-- ### $App Screen Content ### --> 
<div class="full-container">
    <div class="email-app">
        <div class="email-side-nav remain-height ov-h">
            <div class="h-100 layers">
                <div class="p-20 bgc-grey-100 layer w-100">
                    <div class="card">
                        <div class="box">
                            <!-- an ID that has _P at the end means that it is for the profile of a specific person being outputted on the left -->
                            <div class="img">
                                <img src="images/user.png">
                            </div>
                            <h2><span id="name_P">{{ $profile[0]->info->firstname." ".$profile[0]->info->middlename." ".$profile[0]->info->lastname }}</span><br>
                            <span id="role_P">{{ $role->name }}</span>
                            </h2>
                            Birthday:&nbsp;<span id="birth_P"> {{ $profile[0]->info->birthdate }}</span>
                            <br>
                            Gender:&nbsp;<span id="gender_P">{{ $profile[0]->info->gender }}</span>
                            <br>
                            Contact:&nbsp;<span id="contact_P">{{ $profile[0]->info->contact_number}}</span>
                            <br>
                            <br>
                            SSS:&nbsp;<span id="sss_P">{{ !empty($profile[0]->id_number) ? $profile[0]->id_number : 'N/A' }}</span>
                            <br>
                            Philhealth:&nbsp;<span id="philhealth_P">{{ !empty($profile[1]->id_number) ? $profile[1]->id_number : 'N/A' }}</span>
                            <br>
                            Pag-ibig:&nbsp;<span id="pagibig_P">{{ !empty($profile[2]->id_number) ? $profile[2]->id_number : 'N/A' }}</span>
                            <br>
                            TIN:&nbsp;<span id="tin_P">{{ !empty($profile[3]->id_number) ? $profile[3]->id_number : 'N/A' }}</span>
                            <br>
                            <br>
                            <button type="submit" class="btn cur-p btn-dark"><span class="ti-pencil-alt"></span> Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="email-wrapper ">
            <!-- Content -->
            
            <div class="bdT pX-40 pY-30 col-md-12">
                <h4 class="c-grey-900 mB-20">Employee list</h4>
             
                <button type="submit" class="btn cur-p btn-dark reposition form-action-button" data-action="add" data-url="/employee"><span class="ti-pencil-alt"></span> Add</button>
               
                <table id="employee" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th >User ID No.</th>
                            <th >Name</th>
                            <th>Birthday</th>
                            <th>Gender</th>
                            <th >Contact No.</th>
                            <th >Address</th>
                            <th>Rate</th>
                            <th>Status</th>
                            <th >Action</th> 
                        </tr>
                    </thead>
                </table>
            
        </div>
    </div>
</div>


                @include('admin.dashboard.include.employee_form_modal');  

                
@endsection
