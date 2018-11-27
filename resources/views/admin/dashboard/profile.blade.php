@extends('admin.default')

@section('content')
<div class="full-container bgc-grey-100">
<div class="row">
<!-- style='position:fixed' -->
    <div class="col-md-2 mL-5 p-0 bd bgc-white db" >  
    <!-- profile -->
        <div class="layers bgc-white">
            @php
                if(!empty($profile->image)){
                    echo '<div id="profile-image-display" class="profile-image-cover bdrs-50p" style="background-image:url('.$profile->image.');width:150px;height:150px;position:absolute; top:50px;-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777;"></div>';
                }else{
                    echo '<div id="profile-image-display" class="profile-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg);width:150px;height:150px;position:absolute; top:50px;-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777;"></div>';
                }
            @endphp
            <div class="layer mB-10 profile-bg" style='background-image:url("/images/bg.jpg");height:150px;width:100%;background-size:cover;background-repeat:no-repeat;'></div>
            <div class="layer bgc-white w-100p p-10" style="height:calc(100vh - 287px);">
                <div class="min-content mT-40" style="text-align:center;">
                    <h6 style='padding:0;margin:0;' id='name_P'>{{$profile->firstname." ".$profile->middlename." ".$profile->lastname}}</h6>
                    <small style="font-size:.75em" id='email_P'>{{$user->email}}</small></h5>
                    <div class="bdc-red-a200 bgc-red-a100 c-white p-5 m-10" style="text-align:center;border:2px solid">
                        <span id='role_P'>{{ $role->name }}</span>
                    </div>
                    <div class="layer pX-30">
                        @if(isAdmin())
                        <button type="submit" class="btn cur-p btn-dark form-action-button form-control" id="profile-edit-button" data-portion="profile" data-action="edit" data-id="{{$profile->id}}"><span class="ti-pencil-alt" style='pointer:none;'></span> Edit</button>
                        @endif
                    </div>
                    <div class="info-container mT-20">
                        <div class="layer">
                            <input type="radio" class="info-radio" name="info-nav" id="tab1" style='display:none;' checked> 
                            <label for="tab1" class="info-button-label" style="font-size:.8em;margin:0px -5px 0px 0px;border-radius:5px 0px 0px 5px;font-weight:700">Info</label>
                            <input type="radio" class="info-radio" name="info-nav" id="tab2" style='display:none;'> 
                            <label for="tab2" class="info-button-label" style="font-size:.8em;margin:0px -5px 0px 0px;font-weight:700">Identification</label>
                            <input type="radio" class="info-radio" name="info-nav" id="tab3" style='display:none;'> 
                            <label for="tab3" class="info-button-label" style="font-size:.8em;margin:0px -5px 0px 0px;border-radius:0px 5px 5px 0px;font-weight:700">Contact</label>
                            <div class="layer p-20 tab-content-wrapper">
                                <div id="tab-content1" class="tab-content">
                                    <table style='width:100%'>
                                        <tbody>
                                            <tr class='m-0 p-0'>
                                                <td class='m-0 p-0' style='font-weight:600;text-align:left'>Gender:</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td class='m-0 p-0' style='text-align:right;' id="gender_P">{{$profile->gender}}</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td style='font-weight:600;text-align:left'>Birth Date:</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td class='m-0 p-0' style='text-align:right' id="birth_P">{{$profile->birthdate}}</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td style='font-weight:600;text-align:left'>Hired Date:</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td class='m-0 p-0' style='text-align:right' id="hired_P">{{$profile->hired_date}}</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td style='font-weight:600;text-align:left'>Status:</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td class='m-0 p-0' style='text-align:right'>{{$profile->status}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            <div id="tab-content2" class="tab-content">
                                    <table style='width:100%'>
                                        <tbody>
                                            <tr class='m-0 p-0'>
                                                <td class='m-0 p-0' style='font-weight:600;text-align:left'>CNM:</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td class='m-0 p-0' style='text-align:right;' id='company_id_P'>{{$profile->company_id}}</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td style='font-weight:600;text-align:left'>SSS:</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td class='m-0 p-0' style='text-align:right' id="sss_P">{{$profile->benefits[0]->id_number}}</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td style='font-weight:600;text-align:left'>PhilHealth:</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td class='m-0 p-0' style='text-align:right' id="philhealth_P">{{$profile->benefits[1]->id_number}}</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td style='font-weight:600;text-align:left'>PagIbig:</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td class='m-0 p-0' style='text-align:right' id="pagibig_P">{{$profile->benefits[2]->id_number}}</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td style='font-weight:600;text-align:left'>TIN:</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td class='m-0 p-0' style='text-align:right' id="tin_P">{{$profile->benefits[3]->id_number}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div id="tab-content3" class="tab-content">
                                    <table style='width:100%'>
                                        <tbody>
                                            <tr class='m-0 p-0'>
                                                <td class='m-0 p-0' style='font-weight:600;text-align:left'>Number:</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td class='m-0 p-0' style='text-align:right;' id='contact_P'>{{$profile->contact_number}}</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td class='m-0 p-0' style='font-weight:600;text-align:left'>Address:</td>
                                            </tr>
                                            <tr class='m-0 p-0'>
                                                <td class='m-0 p-0' style='text-align:right;' id='address_P'>{{$profile->address}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="col-md-2 mL-5 p-0"></div> -->
    <div class="col">
        <div class="bdT pX-40 pY-30 col-md-12">
                <h4 class="c-grey-900 mB-20 taytol">Employee list</h4> 

                <div class="form-group reposition">
                <div class="btn-group mR-2" role="group" aria-label="First group">
                    <div class="btn-group">
                        <button type="button cur-p ti-arrow-left" class="btn cur-p btn-primary" id="prevProfile" disabled>← Prev</button>
                    </div>

                    <div class="btn-group">
                        @if(isAdminHRM())
                        <button class="btn cur-p btn-primary" id="showAll" data-toggle="tooltip" data-placement="top" title="Employee"><span class="ti-menu"></span></button>
                        @endif
                        <button class="btn cur-p btn-primary" id="showChild" data-toggle="tooltip" data-placement="top" title="Staff"><span class="ti-menu-alt"></span></button>
                        <button class="btn cur-p btn-primary" id="showTerminated"  data-toggle="tooltip" data-placement="top" title="Inactive"><span class="ti-na"></span></button>
                    </div>

                    @if(isAdminHR())
                    <div class="btn-group">
                        <button type="input" class="btn cur-p btn-info excel-action-button"  id ="import" data-action="import" data-toggle="tooltip" data-placement="top" title="Import Excel"><span class="ti-upload"></span></button>
                        <button type="input" class="btn cur-p btn-info excel-action-button"  id ="export" data-action="export"  data-toggle="tooltip" data-placement="top" title="Export Excel Templates"><span class="ti-download"></span></button>
                    </div>

                    <button type="submit" class="btn cur-p btn-secondary form-action-button" id="addflag" data-action="add" data-url="/employee"  data-toggle="tooltip" data-placement="top" title="Add Employee"><span class="ti-pencil-alt"></span></button>
                    @endif

                    @if(isAdminHRM())
                    <button type="submit" class="btn cur-p btn-secondary add-position-button" id="add_position" data-action="add" data-url="/position"  data-toggle="tooltip" data-placement="top" title="Add Position"><span class="ti-user"></span></button>
                    @endif
                </div>
                </div>
                <br>
                <br>
                <table id="employee" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Company ID</th>
                            <th>Picture</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Birthday</th>
                            <th>Gender</th>
                            <th>Contact No.</th>
                            <th>Address</th>
                            <th>Contract</th>
                            <th>Status</th>
                            <th>Action</th> 
                        </tr>
                    </thead>
                </table>
                
            <footer class="bdT  bgc-white  ta-c p-30 lh-0 fsz-sm c-grey-600" style='visibility:hidden;position:relative'>
                <span>Copyright © 2018 Designed by
                    <a href="https://colorlib.com" target='_blank' title="Colorlib">Colorlib</a> Powered by Solid Script Web Systems. All rights reserved.</span>
            </footer>
            </div>
        
    </div>
    <!-- <div class="col bd bgc-white">
        <div class="layer db">
            
        </div>
    </div> -->
</div>

</div>


    @include('admin.dashboard.include.employee_form_modal');  
@endsection