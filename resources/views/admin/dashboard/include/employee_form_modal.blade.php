
<!-- emman update -->
<div id="employee-form-modal" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-xl">
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
                        <div class="col-md-3">
                            <div class="row">
                                <div class="image-upload">
                                    <label id="form-image-container" for="photo" style="cursor:pointer">
                                        <img src="/images/nobody.jpg" alt="profile Pic" id="upload-image-display"
                                            width="100%" />
                                    </label>
                                    <input name="photo" id="photo"  type="file" style="display:none" />
                                    <input name="captured_photo" id="captured_photo" type="text" value="" style="display:none" />
                                    
                                </div>
                            </div>
                            <div class="row">
                                <button id="start-camera" class="btn btn-primary form-control"><span class="ti-camera"></span>&nbsp; Take a photo</button>
                            </div>
                        </div>
                        <!-- //basic info -->
                        <div class="col-md-3">
                            <h6 class="c-grey-900">Basic Info</h6>
                            <div class="mT-30">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs pad0">First Name</label>
                                    <div class="col-sm-8">
                                        <input name="first_name" id="first_name" type="text" class="form-control font-xs"
                                            placeholder="First Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs pad0">Middle Name</label>
                                    <div class="col-sm-8">
                                        <input name="middle_name" id="middle_name" type="text" class="form-control font-xs"
                                            placeholder="Middle Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs pad0">Last Name</label>
                                    <div class="col-sm-8">
                                        <input name="last_name" id="last_name" id="last_name" type="text" class="form-control font-xs"
                                            placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">Address</label>
                                    <div class="col-sm-8">
                                        <input name="address" id="address" type="text" class="form-control font-xs"
                                            placeholder="Address">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">Contact</label>
                                    <div class="col-sm-8">
                                        <input name="contact" id="contact" type="text" class="form-control font-xs"
                                            placeholder="Contact">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs pad0">Personal Email</label>
                                    <div class="col-sm-8">
                                        <input name="p_email" id="p_email" type="text" class="form-control font-xs"
                                            placeholder="Personal Email">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- //additional info -->
                        <div class="col-md-3" style='border-left:1px solid #ccc'>
                            <h6 class="c-grey-900">Additional Information</h6>
                            <div class="mT-30">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">SSS</label>
                                    <div class="col-sm-8">
                                        <input name="id_number[]" id="sss" type="number" class="form-control font-xs"
                                            placeholder="SSS">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">PhilHealth</label>
                                    <div class="col-sm-8">
                                        <input name="id_number[]" id="phil_health" type="number" class="form-control font-xs"
                                            placeholder="PhilHealth">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">Pag-Ibig</label>
                                    <div class="col-sm-8">
                                        <input name="id_number[]" id="pag_ibig" type="number" class="form-control font-xs"
                                            placeholder="Pag-Ibig">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">TIN</label>
                                    <div class="col-sm-8">
                                        <input name="id_number[]" id="tin" type="number" class="form-control font-xs"
                                            placeholder="TIN">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">Birthdate</label>
                                    <div class="timepicker-input col-sm-8">
                                        <input name="birthdate" id="birthdate" type="text" class="form-control bdc-black start-date font-xs"
                                            placeholder="MM/DD/YYYY" data-provide="datepicker">
                                    </div>
                                </div>
                                <div class="form-group row" style="border-top:3px;">
                                    <label class="col-sm-4 col-form-label font-xs">Gender</label>
                                    <div class="col-sm-8">
                                        <select name="gender" id="gender" class="form-control font-xs">
                                            <option value="Male" selected>Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">Contact</label>
                                    <div class="col-sm-8">
                                        <input name="contact" id="contact" type="text" class="form-control font-xs"
                                            placeholder="Contact">
                                    </div>
                                </div> -->
                                
                            </div>
                        </div>
                        <!-- //company info -->
                        <div class="col-md-3" style='border-left:1px solid #ccc'>
                            <h6 class="c-grey-900">Company Details</h6>
                            <div class="mT-30">
                                <div class="form-group row admin-hidden-field">
                                    <label class="col-sm-4 col-form-label font-xs" >Position</label>
                                    <div class="col-sm-8">
                                        <select name="position" id="position" class="form-control font-xs" >
                                            @foreach($userInfo as $datum)
                                                @if($role->id==1)
                                                    <option value="{{$datum->id}}">{{$datum->name}}</option>
                                                @elseif($role->id==2||$role->id==3)
                                                    @if($datum->id>2)
                                                    <option value="{{$datum->id}}">{{$datum->name}}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row admin-hidden-field">
                                    <label class="col-sm-4 col-form-label font-xs" style="padding=0px;">Designation</label>
                                    <div class="col-sm-8">
                                        <select name="designation" class="form-control font-xs" id="designation">

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">Email</label>
                                    <div class="col-sm-8">
                                        <input name="email" id="email" type="email" class="form-control font-xs"
                                            placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">Company ID</label>
                                    <div class="col-sm-8">
                                        <input name="company_id" id="company_id" type="number" step='1' class="form-control font-xs"
                                            placeholder="Company ID">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">Salary</label>
                                    <div class="col-sm-8">
                                        <input name="salary" id="salary" type="number" class="form-control font-xs"
                                            placeholder="Salary">
                                    </div>
                                </div>
                                 <div class="form-group row" style="border-top:3px;">
                                    <label class="col-sm-4 col-form-label font-xs">Contract</label>
                                    <div class="col-sm-8">
                                    <input name="contract" id="contract" type="text" class="form-control font-xs"
                                            placeholder="Contract">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-xs">Date Hired</label>
                                    <div class="timepicker-input col-sm-8">
                                        <input name="hired_date" id='hired_date' type="text" class="form-control bdc-black start-date font-xs"
                                            placeholder="MM/DD/YYYY" data-provide="datepicker">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="id" id="employee-id">
                        <input type="hidden" name="action" id="action">
                        <input type="hidden" name="portion" id="portion">
                        <input type="hidden" name="role" id="role">
                    </div>           
                </form>
            </div>
            <div id="show_camera" hidden="" class="modal-footer" align="center">
                    <div class="image-camera col-md-12">                    
                        <div class="col-sm-12" id="camera_kuha">
                            <video muted id="camera-stream"></video>
                            <img id="snap">

                        <p id="error-message"></p>

                        <div class="controls">
                            <button class="btn ti-loop disabled" id="delete-photo" title="Take Again"></button>
                            <button class="btn ti-camera di" id="take-photo" title="Take Photo"></button>
                            <a href="#" id="download-photo" download="selfie.png" target="_blank" title="Save Photo" class="btn ti-download disabled"></a>  
                            <button class="btn ti-check disabled" id="done" title="Done"></button>
                        </div>

                        <!-- Hidden canvas element. Used for taking snapshot of video. -->
                        <canvas hidden="" style="height:200%; width: 180%;"></canvas>
                    </div>
                </div>
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
                <div class="container-fluid">
                    <h4 class="modal-title">
                        <ul class="nav nav-tabs">
                            <li>
                                <a href="#file_ir" data-toggle="tab">
                                    <div class="block-header" style="color: black;">
                                        <h6>File Incident Reports</h6>
                                    </div>
                                </a>
                            </li>&nbsp&nbsp
                            <li class="active">
                                <a href="#ir_list" data-toggle="tab">
                                    <div class="block-header">
                                        <h6>Incident Reports List</h6>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </h4>
                </div>
            </div>
            <div class="tab-content">
                <div id="file_ir" class="tab-pane fade in">
                    <div class="modal-body">
                        <div class="alert alert-danger" style="display:none"></div>
                        <form method="POST"  id='add_IR_form'  enctype="multipart/form-data">
                            {{ csrf_field()}}
                            <div class="row" style='padding-left:10px;padding-right:10px'>
                                <div class="col-md-12">
                                    <h6 class="c-grey-900">Incident Report</h6>
                                    <div class="mT-30">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label ">Report Description: </label>
                                            <div class="col-sm-9">
                                                <textarea rows="5" cols="60" name="description" id="description" type="text" class="form-control" placeholder="File Here"></textarea>
                                                <!-- <button  type="button" id="IR_email" class=" btn btn-info ti-email pull-right"></button> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="ir_id" id="ir_id" value="">
                                <input type="hidden" name="button_action" id="button_action" value="">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-4">
                            <div class="pull-right">
                                <button type="button" data-dismiss="modal" class="btn btn-danger">Cancel</button>
                                <button type="button" id="add_IR" class="btn btn-success push-right">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="ir_list" class="tab-pane fade in active">
                    <div class="row" style='padding-left:10px;padding-right:10px'>
                        <div class="col-md-12">
                            <h6 class="c-grey-900">Incident Report</h6>
                            <table id="ir_table_list" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Date Filed</th>
                                        <th>Filed By</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-4">
                            <div class="pull-right">
                                <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                            </div>
                        </div>
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
                                <label for="status_data">
                                    <h5 class="c-grey-900">Update Employee Status</h5>
                                </label>
                                <select class="form-control" id="status_data" name="status_data" required>
                                    <option value="new_hired">Newly Hired</option>
                                    <option value="active">Active</option>
                                    <option value="inactive" id="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group" style="display:none" id="reason">
                                <label for="status_data">
                                    <h5 class="c-grey-900">Reason</h5>
                                </label>
                                <textarea  class="form-control" id="status_reason" name="status_reason" ></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="col-md-4">
                    <div class="pull-right">
                        <button type="button" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>
                        <button type="button" id="submit_status" class="btn btn-success btn-sm">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END UPDATE STATUS MODAL -->

<!-- UPLOAD EXCEL MODAL START -->
<div id="excel-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><small id="excel-modal-header">Import<span></span> Excel</small></h4>
            </div>
            <div class="modal-body">
                <form id='import-excel-form' method = "get">
                    {{ csrf_field()}}
                    <center>
                        <div id="action-import">
                            <label for="excel_file" id="excel-file-label" class="btn btn-default">Select excel file.</label>
                            <input type="file" name='excel_file' id="excel_file" style="display:none;">
                        </div>

                        <div  id='import-employee-pbar-container'>
                            <div class="alert alert-warning">
                                <h5><strong class='text-danger'>Please do not exit or leave the page...</strong></h5>
                            </div>
                            <div class="progress" style="height:30px;">
                                <div id='import-employee-p-bar' class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        
                        <div id="action-export">

                            <a href="/profile/excel_export_report" class="btn btn-sq-lg btn-outline-primary"><br><i
                                    class="fa fa-address-book-o fa-5x"></i><br />
                                Report File </a>
                            <a href="/profile/excel_export_add_template" class="btn btn-sq-lg btn-outline-primary"><br><i
                                    class="fa fa-newspaper-o fa-5x"></i><br />
                                Add Template</a>
                                <a href="/profile/excel_export_reassign_template" class="btn btn-sq-lg btn-outline-primary"><br><i class="fa fa-list-ol fa-5x"></i><br />
                                Reassign Template</a>
                        </div>

                    </center>
                </form>
            </div>
            <div class="modal-footer">
                <div class="col-md-6">
                    <div class="pull-right">
                        <a class="btn btn-default" id="excel-modal-cancel">Cancel</a>
                        <button id="excel-form-submit" class="btn btn-danger push-right" style="color:white">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- UPLOAD EXCEL MODAL END -->

<!-- START ADD POSITION MODAL -->
<div id="position-modal-form" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="position-modal-header"></span><span> Position</span></h4>
            </div>
            <div class="modal-body">
                <form id='add-position-form' method="post">
                    {{ csrf_field()}}
                    <center>
                        <div  id="add-position">
                            <div class="form-group row">
                                <label for="position_name" class="col-md-4 col-form-label">Position Name: </label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name='position_name' id="position_name">
                                </div>  
                            </div>
                            <div class="form-group row">
                                <label for="position_designation" class="col-md-4 col-form-label">Designation: </label>
                                <div class="col-md-8">
                                    <select type="text" class="form-control" name='position_designation' id="position_designation">
                                    </select>
                                </div> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-hover existing-position">
                                    <thead>
                                        <th>Existing Positions</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </center>
                </form>
            </div>
            <div class="modal-footer">
                <div class="col-md-6">
                    <div class="pull-right">
                        <a class="btn btn-default" id="position-modal-cancel">Cancel</a>
                        <button id="position-form-submit" class="btn btn-danger push-right" style="color:white">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END ADD POSITION MODAL -->
