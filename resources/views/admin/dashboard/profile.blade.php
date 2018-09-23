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
        <div class="email-wrapper row remain-height bgc-white ov-h">
            <!-- Content -->
            <div class="bdT pX-40 pY-30 col-md-12">
                <h4 class="c-grey-900 mB-20">Employee list</h4>
             
                <button type="submit" class="btn cur-p btn-dark reposition" data-toggle="modal" data-backdrop="static" data-target="#employee-form-modal"><span class="ti-pencil-alt"></span> Add</button>
               
                <table id="employee" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>User ID No.</th>
                            <th>Name</th>
                            <th>Birthday</th>
                            <th>Gender</th>
                            <th>Contact No.</th>
                            <th>Address</th>
                            <th>Rate</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal  -->
<!-- emman update -->
<div id="employee-form-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><small>Add Employee</small></h4>
            </div>
            <div class="modal-body">
            <div class="alert alert-danger" style="display:none"></div>
                <form method="POST" id='employee-form' class="needs-validation" enctype="multipart/form-data">
                {{ csrf_field()}}
                    <div class="row" style='padding:10px'>
                        <div class="col-md-4">
                            <h6 class="c-grey-900">Basic Info</h6>
                            <div class="mT-30">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs pad0">First Name</label>
                                    <div class="col-sm-8">
                                        <input name="first_name" id="first_name" type="text" class="form-control font-xs" placeholder="First Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs pad0">Last Name</label>
                                    <div class="col-sm-8">
                                        <input name="last_name" id="last_name" id="last_name" type="text" class="form-control font-xs" placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs pad0">Middle Name</label>
                                    <div class="col-sm-8">
                                        <input name="middle_name" id="middle_name" type="text" class="form-control font-xs" placeholder="Middle Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">Address</label>
                                    <div class="col-sm-8">
                                        <input name="address" id="address" type="text" class="form-control font-xs" placeholder="Address">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">Birthdate</label>
                                    <div class="timepicker-input col-sm-8">
                                        <input name="birthdate" id="birthdate" type="text" class="form-control bdc-black start-date font-xs" placeholder="MM/DD/YY" data-provide="datepicker">
                                    </div>
                                </div>
                                <div class="form-group row" style="border-top:3px;">
                                    <label class="col-sm-4 col-form-label font-xs">Gender</label>
                                    <div class="col-sm-8">
                                        <select name="gender" id="gender" class="form-control font-xs">
                                            <option selected>Male</option>
                                            <option>Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4" style='border-left:1px solid #ccc'>
                            <h6 class="c-grey-900">Additional Information</h6>
                            <div class="mT-30">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">SSS</label>
                                    <div class="col-sm-8">
                                        <input name="sss" id="sss" type="text" class="form-control font-xs" placeholder="SSS">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">PhilHealth</label>
                                    <div class="col-sm-8">
                                        <input name="phil_health" id="phil_health" type="text" class="form-control font-xs" placeholder="PhilHealth">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">Pag-Ibig</label>
                                    <div class="col-sm-8">
                                        <input name="pag_ibig" id="pag_ibig" type="text" class="form-control font-xs" placeholder="Pag-Ibig">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">TIN</label>
                                    <div class="col-sm-8">
                                        <input name="tin" id="tin" type="text" class="form-control font-xs" placeholder="TIN">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">Contact</label>
                                    <div class="col-sm-8">
                                        <input name="contact" id="contact" type="text" class="form-control font-xs" placeholder="Contact">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">Email</label>
                                    <div class="col-sm-8">
                                        <input name="email" id="email" type="email" class="form-control font-xs" placeholder="Email">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4" style='border-left:1px solid #ccc'>
                            <h6 class="c-grey-900">Company Details</h6>
                            <div class="mT-30">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs" >Position</label>
                                    <div class="col-sm-8">
                                        <select name="position" id="position" class="form-control font-xs">
                                            @foreach($userInfo as $datum)
                                                @if($datum->id>1)
                                                    <option value="{{$datum->id}}">{{$datum->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs" style="padding=0px;">Designation</label>
                                    <div class="col-sm-8">
                                        <select name="designation" class="form-control font-xs" id="designation">
                                            <option value="">Select HR Manager</option>
                                            @foreach($hr as $datum)
                                                <option value="{{$datum->id}}">{{$datum->lastname.", ".$datum->firstname." ".$datum->middlename}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs" >Salary</label>
                                    <div class="col-sm-8">
                                        <input name="salary" id="salary" type="number" class="form-control font-xs" placeholder="Salary">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="image-upload" style="height:137px;margin:0 auto;">
                                        <label for="photo" style="cursor:pointer">
                                            
                                                <img src="/images/12.jpg" alt="profile Pic" id="upload-image-display" width="100px"/>
                                            
                                        </label>
                                        <input name="photo" id="photo" type="file" style="display:none"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>           
                <form>
            </div>
            <div class="modal-footer">
                <div class="col-md-4">
                     <div class="pull-right">
                        <a class="btn btn-default" id="employee-modal-cancel">Cancel</a>
                        <button id="employee-form-submit" class="btn btn-danger push-right" style="color:white">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
