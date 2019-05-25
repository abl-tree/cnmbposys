@extends('admin.default')

@section('content')
<div class="full-container">
    {{-- <center><h1>{{ $parent['id'] }}</h1></center>
    <center><h1>{{ $parent['access_id'] }}</h1></center>
    <center><h1>{{ $auth['id'] }}</h1></center>
    <center><h1>{{ $auth['access_id'] }}</h1></center> --}}
    <div class="row">
        <div id="hierarchy-profile-preview" class="col-md-3 pX-0 mX-0 bd bgc-white">
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
                        <div>@if($access_id==1 || $access_id==2)
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
        <div class="col">
            <hierarchy-table></hierarchy-table>
        </div>
    </div>
</div>


{{-- @include('admin.dashboard.include.employee_form_modal'); --}}
@endsection