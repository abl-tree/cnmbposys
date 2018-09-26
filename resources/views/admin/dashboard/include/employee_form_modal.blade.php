Modal  -->
<!-- emman update -->
<div id="employee-form-modal" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><small><span id="employee-form-modal-header-title"></span> Employee</small></h4>
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
                                    <label class="col-sm-4 col-form-label font-xs pad0">Middle Name</label>
                                    <div class="col-sm-8">
                                        <input name="middle_name" id="middle_name" type="text" class="form-control font-xs" placeholder="Middle Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs pad0">Last Name</label>
                                    <div class="col-sm-8">
                                        <input name="last_name" id="last_name" id="last_name" type="text" class="form-control font-xs" placeholder="Last Name">
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
                                        <input name="id_number[]" id="sss" type="text" class="form-control font-xs" placeholder="SSS">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">PhilHealth</label>
                                    <div class="col-sm-8">
                                        <input name="id_number[]" id="phil_health" type="text" class="form-control font-xs" placeholder="PhilHealth">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">Pag-Ibig</label>
                                    <div class="col-sm-8">
                                        <input name="id_number[]" id="pag_ibig" type="text" class="form-control font-xs" placeholder="Pag-Ibig">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">TIN</label>
                                    <div class="col-sm-8">
                                        <input name="id_number[]" id="tin" type="text" class="form-control font-xs" placeholder="TIN">
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
                                        <label id="form-image-container" for="photo" style="cursor:pointer">
                                            
                                                <img src="/images/nobody.jpg" alt="profile Pic" id="upload-image-display" width="100px"/>
                                            
                                        </label>
                                        <input name="photo" id="photo" type="file" style="display:none"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" id="employee-id">
                        <input type="hidden" name="action" id="action">
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
<!-- Add Incident Report MODAL  -->
<div id="nod_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><small><span id="nod-form-modal-header-title"></span>Add Incident Report</small></h4>
            </div>
            <div class="modal-body">
            <div class="alert alert-danger" style="display:none"></div>
                <form method="POST"  id='add_IR_form'  enctype="multipart/form-data">
                     {{ csrf_field()}}
                    <div class="row" style='padding:10px'>
                        <div class="col-md-12">
                            <h6 class="c-grey-900">Incident Report</h6>
                            <div class="mT-30">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label ">Report Description: </label>
                                    <div class="col-sm-8">
                                       <textarea rows="5" cols="60" name="description" id="description" type="text" class="form-control" placeholder="File Here"></textarea>
                                        <!-- <button  type="button" id="IR_email" class=" btn btn-info ti-email pull-right"></button> -->
                                    </div>
                                     
                                </div>

                                 
                            </div>
                        </div>
                        <input type="hidden" name="ir_id" id="ir_id" value="">
                        <input type="hidden" name="button_action" id="button_action" value="">
                    </div>           
                <form>
            </div>
            <div class="modal-footer">
                <div class="col-md-4">
                     <div class="pull-right">
                        <button type="button" data-dismiss="modal" class="btn btn-danger" >Cancel</button>
                        <button type="button" id="add_IR" class="btn btn-success push-right">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--  end of IR modal -->

<!-- START UPDATE STATUS MODAL  -->
<div id="update_status_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="employee_status_name"><small></small></h4>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" id='update_status_form'>
                {{ csrf_field()}}
                <input type="hidden" name="status_id" id="status_id" value="">
                    <div class="row" style='padding:10px'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="status_data"><h4 class="c-grey-900">Update Employee Status</h4></label>
                                <select class="form-control" id="status_data" name="status_data" required>
                                    <option value="Active">Active</option>
                                    <option value="Terminated">Terminated</option>
                                </select>
                            </div>
                        </div>
                    </div>           
                <form>
            </div>
            <div class="modal-footer">
                <div class="col-md-4">
                     <div class="pull-right">
                        <button type="button" data-dismiss="modal" class="btn btn-danger btn-sm" >Cancel</button>
                        <button type="button" id="submit_status" class="btn btn-success btn-sm">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
   <!-- END UPDATE STATUS MODAL -->