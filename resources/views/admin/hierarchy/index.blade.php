@extends('admin.default')

@section('content')
<div class="full-container">
    <div class="row">
        <div class="col-md-3 bd bgc-white">
           <div class="row">
               <div class="col-12 p-15  profile-bg" style="background-image:url('images/bg.jpg');background-size:cover;background-repeat:no-repeat;">
                @php
                    if(!empty($profile->image)){
                    echo '<div id="profile-image-display" class="profile-image-cover bdrs-50p"
                        style="background-image:url('.$profile->image.');width:150px;height:150px;-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777;margin:0 auto;">
                    </div>';
                    }else{
                    echo '<div id="profile-image-display" class="profile-image-cover bdrs-50p"
                        style="background-image:url(/images/nobody.jpg);width:150px;height:150px;-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777;margin:0 auto;">
                    </div>';
                    }
                    @endphp
               </div>
                <div class="col-12 bdB pY-20 text-center">
                    <div class='fw-500 c-blue-500' style="font-size:1em;" id='name_P'>
                        {{$profile->firstname." ".$profile->middlename." ".$profile->lastname}}</div>
                    <div class='c-grey-600' style="font-size:.9em;" id='role_P'>{{ $role->name }}</div>
                </div>
                <div class="col-12 bdB pY-20">
                    <h6 class='text-center'>Contact Details</h6>
                    <dl class="row">
                        <dt class="col-4 text-right fsz-xs fw-500">Email</dt>
                        <dd class="col-8 text-left fsz-xs" id='email_P'>{{$user->email}}</dd>
                        <dt class="col-4 text-right fsz-xs fw-500">Mobile</dt>
                        <dd class="col-8 text-left fsz-xs" id='contact_P'>{{$profile->contact_number}}</dd>
                    </dl>
                </div>
                <div class="col-12 bdB pY-20">
                    <h6 class='text-center'>Company Details</h6>
                    <dl class="row">
                        <dt class="col-4 text-right fsz-xs fw-500">CNM ID</dt>
                        <dd class="col-8 text-left fsz-xs" id='company_id_P'>{{$user->company_id}}</dd>
                        <dt class="col-4 text-right fsz-xs fw-500">Hired Date</dt>
                        <dd class="col-8 text-left fsz-xs" id='hired_P'>{{$profile->hired_date}}</dd>
                        <dt class="col-4 text-right fsz-xs fw-500">Status</dt>
                        <dd class="col-8 text-left fsz-xs" id='status_P'>
                            {{$profile->status=='new_hired'?'Newly Hired': ucwords($profile->status)}}</dd>
                    </dl>
                </div>
                
                @if($auth['access_id']!=1)
                    @if($auth['id'] !== $parent['id'])
                        <div class="col-12 bdB pY-20 pB-70">
                    @else
                        <div class="col-12 bdB pY-20">
                    @endif
                        <h6 class='text-center'>Personal Details</h6>
                        <dl class="row">
                            <dt class="col-4 text-right fsz-xs fw-500">Gender</dt>
                            <dd class="col-8 text-left fsz-xs" id='email_P'>{{$profile->gender}}</dd>
                            <dt class="col-4 text-right fsz-xs fw-500">Birth Date</dt>
                            <dd class="col-8 text-left fsz-xs" id='contact_P'>{{$profile->birthdate}}</dd>
                            <dt class="col-4 text-right fsz-xs fw-500">Address</dt>
                            <dd class="col-8 text-left fsz-xs" id='address_P'>{{$profile->address}}</dd>
                        </dl>
                    </div>
                    @if($auth['id'] === $parent['id'])
                        <div class="col-12 bdB pY-20 pB-70">
                            <h6 class='text-center'>Benefit ID Cards</h6>
                            <dl class="row">
                                <dt class="col-4 text-right fsz-xs fw-500">SSS</dt>
                                <dd class="col-8 text-left fsz-xs" id='sss_P'>{{$profile->benefits[0]->id_number}}</dd>
                                <dt class="col-4 text-right fsz-xs fw-500">PhilHealth</dt>
                                <dd class="col-8 text-left fsz-xs" id='philhealth_P'>{{$profile->benefits[1]->id_number}}</dd>
                                <dt class="col-4 text-right fsz-xs fw-500">PagIbig</dt>
                                <dd class="col-8 text-left fsz-xs" id='pagibig_P'>{{$profile->benefits[2]->id_number}}</dd>
                                <dt class="col-4 text-right fsz-xs fw-500">TIN</dt>
                                <dd class="col-8 text-left fsz-xs" id='tin_P'>{{$profile->benefits[3]->id_number}}</dd>
                            </dl>
                        </div>
                    @endif
                @else
                <div class="col-12 bdB pY-20">
                    <h6 class='text-center'>Personal Details</h6>
                    <dl class="row">
                        <dt class="col-4 text-right fsz-xs fw-500">Gender</dt>
                        <dd class="col-8 text-left fsz-xs" id='email_P'>{{$profile->gender}}</dd>
                        <dt class="col-4 text-right fsz-xs fw-500">Birth Date</dt>
                        <dd class="col-8 text-left fsz-xs" id='contact_P'>{{$profile->birthdate}}</dd>
                        <dt class="col-4 text-right fsz-xs fw-500">Address</dt>
                        <dd class="col-8 text-left fsz-xs" id='address_P'>{{$profile->address}}</dd>
                    </dl>
                </div>
                <div class="col-12 bdB pY-20 pB-70">
                    <h6 class='text-center'>Benefit ID Cards</h6>
                    <dl class="row">
                        <dt class="col-4 text-right fsz-xs fw-500">SSS</dt>
                        <dd class="col-8 text-left fsz-xs" id='sss_P'>{{$profile->benefits[0]->id_number}}</dd>
                        <dt class="col-4 text-right fsz-xs fw-500">PhilHealth</dt>
                        <dd class="col-8 text-left fsz-xs" id='philhealth_P'>{{$profile->benefits[1]->id_number}}</dd>
                        <dt class="col-4 text-right fsz-xs fw-500">PagIbig</dt>
                        <dd class="col-8 text-left fsz-xs" id='pagibig_P'>{{$profile->benefits[2]->id_number}}</dd>
                        <dt class="col-4 text-right fsz-xs fw-500">TIN</dt>
                        <dd class="col-8 text-left fsz-xs" id='tin_P'>{{$profile->benefits[3]->id_number}}</dd>
                    </dl>
                </div>
                @endif
            </div>
        </div>
        <div class="col">
            <hierarchy-table v-bind:auth="{{ json_encode($auth) }}" v-bind:parent="{{ json_encode($parent) }}"></hierarchy-table>
        </div>
    </div>
</div>


{{-- @include('admin.dashboard.include.employee_form_modal'); --}}
@endsection