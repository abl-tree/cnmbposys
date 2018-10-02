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
                                <img id="profile-image-display" src="{{ ($profile->image_ext != '')? 'data:image/'.$profile->image_ext.';base64,'.$profile->image : '/images/nobody.jpg'}}">
                                
                            </div>
                            <h2><span id="name_P">{{ $profile->firstname." ".$profile->middlename." ".$profile->lastname }}</span><br>
                            <span id="role_P">{{ $role->name }}</span>
                            </h2>
                            Birthday:&nbsp;<span id="birth_P"> {{ $profile->birthdate }}</span>
                            <br>
                            Gender:&nbsp;<span id="gender_P">{{ $profile->gender }}</span>
                            <br>
                            Contact:&nbsp;<span id="contact_P">{{ $profile->contact_number}}</span>
                            <br>
                            Address:&nbsp;<span id="address_P">{{ $profile->address}}</span>
                            <br>
                            Email:&nbsp;<span id="email_P">{{ $user->email}}</span>
                            <br>
                            <br>
                            SSS:&nbsp;<span id="sss_P">{{ !empty($profile->benefits[0]->id_number) ? $profile->benefits[0]->id_number : 'N/A' }}</span>
                            <br>
                            Philhealth:&nbsp;<span id="philhealth_P">{{ !empty($profile->benefits[1]->id_number) ? $profile->benefits[1]->id_number : 'N/A' }}</span>
                            <br>
                            Pag-ibig:&nbsp;<span id="pagibig_P">{{ !empty($profile->benefits[2]->id_number) ? $profile->benefits[2]->id_number : 'N/A' }}</span>
                            <br>
                            TIN:&nbsp;<span id="tin_P">{{ !empty($profile->benefits[3]->id_number) ? $profile->benefits[3]->id_number : 'N/A' }}</span>
                            <br>
                            <br>
                            
                            @if(isAdminHR())
                            <!-- <button type="submit" class="btn cur-p btn-dark form-action-button" id="profile-edit-button" data-portion="profile" data-action="edit" data-id="{{$profile->id}}"><span class="ti-pencil-alt" style='pointer:none;'></span> Edit</button> -->
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="email-wrapper ">
            <!-- Content -->
            
            <div class="bdT pX-40 pY-30 col-md-12">
                <h4 class="c-grey-900 mB-20">Employee list</h4> 

                <div class="form-group reposition">
                    <div class="btn-group">
                        <button type="button cur-p ti-arrow-left" class="btn cur-p btn-primary" id="PrevProfile" disabled>← Prev</button>
                    </div>

                    <div class="btn-group">
                        @if(isAdminHR())
                        <button class="btn cur-p " id="showAll"><span class="ti-menu"></span></button>
                        @endif
                        <button class="btn cur-p " id="showChild"><span class="ti-menu-alt"></span></button>
                        <button class="btn cur-p " id="showTerminated"><span class="ti-na"></span></button>
                    </div>

                    @if(isAdminHR())
                    <div class="btn-group">
                    <button type="input" class="btn cur-p btn-info excel-action-button" data-action="import"><span class="ti-upload"></span></button>
                    <a href="{{route('excel.export')}}" class="btn cur-p btn-info excel-action-button" data-action="export"><span class="ti-download"></span></a>
                    </div>

                    <button type="submit" class="btn cur-p btn-dark form-action-button" data-action="add" data-url="/employee"><span class="ti-pencil-alt"></span> Add</button>
                    @endif
                </div>

                <table id="employee" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>User ID No.</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Birthday</th>
                            <th>Gender</th>
                            <th>Contact No.</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Action</th> 
                        </tr>
                    </thead>
                </table>
        </div>
    </div>
</div>
    @include('admin.dashboard.include.employee_form_modal');  

                
@endsection
