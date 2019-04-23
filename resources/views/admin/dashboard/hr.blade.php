@extends('admin.default')

@section('content')
<div id="hr-dashboard" class="full-container bgc-white">
    <div class="row">
        <div id="hierarchy-profile-preview" class="col-md-3 pX-0 mX-0 bd">
            <div class="row w-100 pX-0 mX-0 ">
                <div class="col pL-30 pT-5 pB-20 mR-0 pR-5 w-100 h-200">
                    @php
                    if(!empty($profile->image)){
                    echo '<div id="profile-image-display" class="profile-image-cover bdrs-50p"
                        style="background-image:url('.$profile->image.');width:150px;height:150px;-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777;">
                    </div>';
                    }else{
                    echo '<div id="profile-image-display" class="profile-image-cover bdrs-50p"
                        style="background-image:url(/images/nobody.jpg);width:150px;height:150px;-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777;">
                    </div>';
                    }
                    @endphp
                </div>
                <div class="col mX-0 pX-0 pL-0 align-self-center">
                    <div class='mX-0 pX-0 w-100'>
                        <div class='fw-500 mL-0 mX-0 pL-0 pR-20 c-blue-500' style="font-size:1em;" id='name_P'>
                            {{$profile->firstname." ".$profile->middlename." ".$profile->lastname}}</div>
                        <div class='mL-0 c-grey-600' style="font-size:.9em;" id='role_P'>{{ $role->name }}</div>
                        <div>@if(isAdmin())
                            <span class="ti-pencil-alt form-action-button c-blue-300" id="profile-edit-button"
                                data-portion="profile" data-action="edit" data-id="{{$profile->id}}"
                                style='cursor:pointer'></span>
                            @endif</div>
                    </div>
                </div>
            </div>
            <div class="container  w-100 pX-0 mX-0" style='height:auto;overflow:hidden'>
                <div class="row pY-15 bdT bdB">
                    <div class="col-md-12 mX-0 pX-0">
                        <div class="row">
                            <div class="col">
                                <h6 class='text-center'>Contact Details</h6>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-4 " style='line-height:30px;'>
                                <div class="text-right fsz-xs fw-500">Email</div>
                                <div class="text-right fsz-xs fw-500">Mobile</div>
                            </div>
                            <div class="col mX-0 pX-0" style='line-height:30px;'>
                                <div class="text-left fsz-xs" id='email_P'>{{$user->email}}</div>
                                <div class="text-left fsz-xs" id='contact_P'>{{$profile->contact_number}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row pY-15  bdT bdB">
                    <div class="col-md-12 mX-0 pX-0">
                        <div class="row">
                            <div class="col">
                                <h6 class='text-center'>Company Details</h6>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-4 " style='line-height:30px;'>
                                <div class="text-right fsz-xs fw-500">CNM ID</div>
                                <div class="text-right fsz-xs fw-500">Hired Date</div>
                                <div class="text-right fsz-xs fw-500">Status</div>
                            </div>
                            <div class="col mX-0 pX-0" style='line-height:30px;'>
                                <div class="text-left fsz-xs" id='company_id_P'>{{$user->company_id}}</div>
                                <div class="text-left fsz-xs" id='hired_P'>{{$profile->hired_date}}</div>
                                <div class="text-left fsz-xs" id='status_P'>
                                    {{$profile->status=='new_hired'?'Newly Hired': ucwords($profile->status)}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row pY-15  bdT bdB">
                    <div class="col-md-12 mX-0 pX-0">
                        <div class="row">
                            <div class="col">
                                <h6 class='text-center'>Personal Details</h6>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-4 " style='line-height:30px;'>
                                <div class="text-right fsz-xs fw-500">Gender</div>
                                <div class="text-right fsz-xs fw-500">Birth Date</div>
                                <div class="text-right fsz-xs fw-500">Address</div>
                            </div>
                            <div class="col mX-0 pX-0" style='line-height:30px;'>
                                <div class="text-left fsz-xs" id='email_P'>{{$profile->gender}}</div>
                                <div class="text-left fsz-xs" id='contact_P'>{{$profile->birthdate}}</div>
                                <div class="text-left fsz-xs" id='address_P'>{{$profile->address}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row pY-15  bdT bdB pB-70">
                    <div class="col-md-12 mX-0 pX-0">
                        <div class="row">
                            <div class="col">
                                <h6 class='text-center'>Benefit ID Cards</h6>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-4 " style='line-height:30px;'>
                                <div class="text-right fsz-xs fw-500">SSS</div>
                                <div class="text-right fsz-xs fw-500">PhilHealth</div>
                                <div class="text-right fsz-xs fw-500">PagIbig</div>
                                <div class="text-right fsz-xs fw-500">TIN</div>
                            </div>
                            <div class="col mX-0 pX-0" style='line-height:30px;'>
                                <div class="text-left fsz-xs" id='sss_P'>{{$profile->benefits[0]->id_number}}</div>
                                <div class="text-left fsz-xs" id='philhealth_P'>{{$profile->benefits[1]->id_number}}
                                </div>
                                <div class="text-left fsz-xs" id='pagibig_P'>{{$profile->benefits[2]->id_number}}</div>
                                <div class="text-left fsz-xs" id='tin_P'>{{$profile->benefits[3]->id_number}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col pX-30" style='overflow-y:auto'>
            <div class="row">
                <div class="col p-0 m-0 w-100">
                </div>
            </div>
            <div class="row">


                <div class="btn bd pX-10 pY-5" id='hierarchy-profile-toggle' state="open"
                    style="position:absolute;top:0px;left:0px;cursor:pointer;z-index:9">
                    <span class="ti-angle-left fw-900 fsz-xs" aria-hidden='true' style='pointer:none'></span>
                </div>
                <div class="col-md-12">
                    <div class="bdT pY-30">
                        <h4 class="c-grey-900 mB-20 taytol">Employee list</h4>

                        <div class="form-group reposition">
                            <div class="btn-group mR-2" role="group" aria-label="First group">
                                <div class="btn-group">
                                    <button type="button cur-p ti-arrow-left" class="btn cur-p btn-primary"
                                        id="prevProfile" disabled>← Prev</button>
                                </div>
                                <div class="btn-group">
                                    @if(isAdminHRM())
                                    <button class="btn cur-p btn-primary" id="showAll" data-toggle="tooltip"
                                        data-placement="top" title="Employee"><span class="ti-menu"></span></button>
                                    @endif
                                    <button class="btn cur-p btn-primary" id="showChild" data-toggle="tooltip"
                                        data-placement="top" title="Staff"><span class="ti-menu-alt"></span></button>
                                    <button class="btn cur-p btn-primary" id="showTerminated" data-toggle="tooltip"
                                        data-placement="top" title="Inactive"><span class="ti-na"></span></button>
                                </div>

                                @if(isAdminHR())
                                <div class="btn-group">
                                    <button type="input" class="btn cur-p btn-info excel-action-button" id="import"
                                        data-action="import" data-toggle="tooltip" data-placement="top"
                                        title="Import Excel"><span class="ti-upload"></span></button>
                                    <button type="input" class="btn cur-p btn-info excel-action-button" id="export"
                                        data-action="export" data-toggle="tooltip" data-placement="top"
                                        title="Export Excel Templates"><span class="ti-download"></span></button>
                                </div>

                                <button type="submit" class="btn cur-p btn-secondary form-action-button" id="addflag"
                                    data-action="add" data-url="/employee" data-toggle="tooltip" data-placement="top"
                                    title="Add Employee"><span class="ti-pencil-alt"></span></button>
                                @endif

                                @if(isAdminHRM())
                                <button type="submit" class="btn cur-p btn-secondary add-position-button"
                                    id="add_position" data-action="add" data-url="/position" data-toggle="tooltip"
                                    data-placement="top" title="Add Position"><span class="ti-user"></span></button>
                                @endif
                            </div>
                        </div>
                        <br>
                        <br>
                        <table id="employee" class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="bg-muted">ID</th>
                                    <th class="bg-muted">Name</th>
                                    <th class="bg-muted">Status</th>
                                    <th class="bg-muted">Action</th>
                                    <th>Picture</th>
                                    <th>Position</th>
                                    <th>Birthday</th>
                                    <th>Gender</th>
                                    <th class='no-wrap'>Contact No.</th>
                                    <th>Address</th>
                                    <th>Contract</th>
                                    <th>Personal Email</th>
                                    <th>Company Email</th>
                                </tr>
                            </thead>
                        </table>

                        <footer class="bdT  bgc-white  ta-c p-30 lh-0 fsz-sm c-grey-600"
                            style='visibility:hidden;position:relative'>
                            <span>Copyright © 2018 Designed by
                                <a href="https://colorlib.com" target='_blank' title="Colorlib">Colorlib</a> Powered by
                                Solid Script Web Systems. All rights reserved.</span>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@include('admin.dashboard.include.employee_form_modal');
@endsection