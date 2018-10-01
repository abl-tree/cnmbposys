
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.$ = jQuery;

require('./bootstrap');
window.swal = require('sweetalert2');

// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

// const app = new Vue({
//     el: '#app'
// });




// include CU employee form JS by EJEL 
// -- START


// -- END
//global variables
 var ir_id;
 var description;
 //end of global variables

//PROFILE EMPLOYEE LIST -- START

var employeetable = $('#employee').DataTable({
    processing: true,
    columnDefs: [{
        "targets": "_all", // your case first column
        "className": "text-center",

    }],
    ajax: "/refreshEmployeeList",
    columns: [
        {data: 'child_info.id', name: 'id'},
        {data: 'name', name: 'name'},
        {data: 'child_info.birthdate', name: 'birthdate'},
        {data: 'child_info.gender', name: 'gender'},
        {data: 'child_info.contact_number', name: 'contact_number'},
        {data: 'child_info.address', name: 'address'},
        {data: 'child_info.salary_rate', name: 'salary_rate'},
        {data: "employee_status"},
        {data: "action", orderable:false,searchable:false}
    ]
});

//PROFILE EMPLOYEE LIST -- END

//DYNAMIC PROFILE -- START

//store history of profiles
var prevProfiles = [];
var prevButton = $("#PrevProfile");
var currentTab;

$.ajax({
    url: "/getCurrentTab",
    method: 'get',
    success:function(data){
        if(data == 'showAll'){
            currentTab = 'showAll';
        }
        else{
            currentTab = 'childView';
        }
    }
});


//get id of current profile
function getCurrentProfile(){
    $.ajax({
        url: "/getCurrentProfile",
        method: 'get',
        success:function(data){
            prevProfiles.push(data);
        }
    });
}

getCurrentProfile();

$(document).on("click", ".view-employee", function(){
    id = $(this).attr("id");
    $('#profile-edit-button').attr('data-id',id);
    prevProfiles.push(id);
    $.ajax({
        url: "/viewProfile",
        method: 'get',
        dataType: 'json',
        data:{id:id},
        success:function(data){
            $("#name_P").html(data.profile.firstname + " " + data.profile.middlename + " " + data.profile.lastname)
            $("#role_P").html(data.role.name);
            $("#birth_P").html(data.profile.birthdate);
            $("#gender_P").html(data.profile.gender);
            $("#contact_P").html(data.profile.contact_number);
            $("#sss_P").html(!!data.profile.benefits[0].id_number ? data.profile.benefits[0].id_number : 'N/A');
            $("#philhealth_P").html(!!data.profile.benefits[1].id_number ? data.profile.benefits[1].id_number : 'N/A');
            $("#pagibig_P").html(!!data.profile.benefits[2].id_number ? data.profile.benefits[2].id_number : 'N/A');
            $("#tin_P").html(!!data.profile.benefits[3].id_number ? data.profile.benefits[3].id_number : 'N/A');
           
            fetch_blob_image(data.profile.id,'profile-image-display');
            
            employeetable = $('#employee').DataTable({
                destroy: true,
                processing: true,
                columnDefs: [{
                    "targets": "_all", // your case first column
                    "className": "text-center",
        
                }],
                ajax: {
                    "url": "/updateEmployeeList",
                    "data": function(d){
                        d.id = id;	
                    }
                },
                columns: [
                    {data: 'child_info.id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'child_info.birthdate', name: 'birthdate'},
                    {data: 'child_info.gender', name: 'gender'},
                    {data: 'child_info.contact_number', name: 'contact_number'},
                    {data: 'child_info.address', name: 'address'},
                    {data: 'child_info.salary_rate', name: 'salary_rate'},
                    {data: "employee_status"},
                    {data: "action", orderable:false,searchable:false}
                ]
            });
            prevButton.prop('disabled', false);
        }
    })
}) 

$(document).on("click", "#PrevProfile", function(){
    id = prevProfiles[prevProfiles.length-2];
    if(prevProfiles.length > 1){
        $.ajax({
            url: "/viewProfile",
            method: 'get',
            dataType: 'json',
            data:{id:id},
            success:function(data){
                $("#name_P").html(data.profile.firstname + " " + data.profile.middlename + " " + data.profile.lastname)
                $("#role_P").html(data.role.name);
                $("#birth_P").html(data.profile.birthdate);
                $("#gender_P").html(data.profile.gender);
                $("#contact_P").html(data.profile.contact_number);
                $("#contact_P").html(data.profile.address);
                $("#sss_P").html(!!data.profile.benefits[0].id_number ? data.profile.benefits[0].id_number : 'N/A');
                $("#philhealth_P").html(!!data.profile.benefits[1].id_number ? data.profile.benefits[1].id_number : 'N/A');
                $("#pagibig_P").html(!!data.profile.benefits[2].id_number ? data.profile.benefits[2].id_number : 'N/A');
                $("#tin_P").html(!!data.profile.benefits[3].id_number ? data.profile.benefits[3].id_number : 'N/A');
                
                fetch_blob_image(data.profile.id,'profile-image-display');
                if(prevProfiles.length-2 == 0 && currentTab == 'showAll'){
                    employeetable = $('#employee').DataTable({
                        destroy: true,
                        processing: true,
                        columnDefs: [{
                            "targets": "_all", // your case first column
                            "className": "text-center",
                
                        }],
                        ajax: {
                            "url": "/refreshEmployeeList",
                            "data": function(d){
                                d.id = id;	
                            }
                        },
                        columns: [
                            {data: 'child_info.id', name: 'id'},
                            {data: 'name', name: 'name'},
                            {data: 'child_info.birthdate', name: 'birthdate'},
                            {data: 'child_info.gender', name: 'gender'},
                            {data: 'child_info.contact_number', name: 'contact_number'},
                            {data: 'child_info.address', name: 'address'},
                            {data: 'child_info.salary_rate', name: 'salary_rate'},
                            {data: "employee_status"},
                            {data: "action", orderable:false,searchable:false}
                        ]
                    });
                }
                else{//childView
                    employeetable = $('#employee').DataTable({
                        destroy: true,
                        processing: true,
                        columnDefs: [{
                            "targets": "_all", // your case first column
                            "className": "text-center",
                
                        }],
                        ajax: {
                            "url": "/updateEmployeeList",
                            "data": function(d){
                                d.id = id;	
                            }
                        },
                        columns: [
                            {data: 'child_info.id', name: 'id'},
                            {data: 'name', name: 'name'},
                            {data: 'child_info.birthdate', name: 'birthdate'},
                            {data: 'child_info.gender', name: 'gender'},
                            {data: 'child_info.contact_number', name: 'contact_number'},
                            {data: 'child_info.address', name: 'address'},
                            {data: 'child_info.salary_rate', name: 'salary_rate'},
                            {data: "employee_status"},
                            {data: "action", orderable:false,searchable:false}
                        ]
                    });
                }
                
                prevProfiles.pop();
                if(prevProfiles.length == 1){
                    prevButton.prop('disabled', true);
                }
            }
        })
    }
})

$(document).on("click", "#showAll", function(){
    $('#employee').DataTable({
        destroy: true,
        processing: true,
        columnDefs: [{
            "targets": "_all", // your case first column
            "className": "text-center",
        }],
        ajax: "/refreshEmployeeList",
        columns: [
            {data: 'child_info.id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'child_info.birthdate', name: 'birthdate'},
            {data: 'child_info.gender', name: 'gender'},
            {data: 'child_info.contact_number', name: 'contact_number'},
            {data: 'child_info.address', name: 'address'},
            {data: 'child_info.salary_rate', name: 'salary_rate'},
            {data: "employee_status"},
            {data: "action", orderable:false,searchable:false}
        ]
    });

    currentTab = 'showAll';
    resetPrevButton();
})

$(document).on("click", "#showChild", function(){
    $('#employee').DataTable({
        destroy: true,
        processing: true,
        columnDefs: [{
            "targets": "_all", // your case first column
            "className": "text-center",
        }],
        ajax: "/childView",
        columns: [
            {data: 'child_info.id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'child_info.birthdate', name: 'birthdate'},
            {data: 'child_info.gender', name: 'gender'},
            {data: 'child_info.contact_number', name: 'contact_number'},
            {data: 'child_info.address', name: 'address'},
            {data: 'child_info.salary_rate', name: 'salary_rate'},
            {data: "employee_status"},
            {data: "action", orderable:false,searchable:false}
        ]
    });

    currentTab = 'childView';
    resetPrevButton();
})

$(document).on("click", "#showTerminated", function(){
    $('#employee').DataTable({
        destroy: true,
        processing: true,
        columnDefs: [{
            "targets": "_all", // your case first column
            "className": "text-center",
        }],
        ajax: "/terminatedView",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'birthdate', name: 'birthdate'},
            {data: 'gender', name: 'gender'},
            {data: 'contact_number', name: 'contact_number'},
            {data: 'address', name: 'address'},
            {data: 'salary_rate', name: 'salary_rate'},
            {data: "employee_status", name: 'status'},
            {data: "action", orderable:false,searchable:false}
        ]
    });

    currentTab = 'childView';
    resetPrevButton();
})


function resetPrevButton(){
    prevProfiles = [];
    prevButton.prop('disabled', true);
    getCurrentProfile();
}

//DYNAMIC PROFILE -- END

////////////////////////////////////////////////////////////////////////////////////
//TOKEN HEADER FOR HTTP REQUESTS
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

////////////////////////////////////////////////////////////////////////////////////
//EVENT

// reset modal on cancel button click
$(document).on('click','#employee-modal-cancel',function(e){
    e.preventDefault();
    employee_form_reset();
});


//get group leaders on position change
$(document).on('change','#position',function(){
        var a_position = $(this).val(); //applicant positon a_position
        var u_position = $('#user-access-level').val(); //logged position
        // var u_id = 
        fetch(a_position,u_position,);
});

//display input file image before upload on change
$("#photo").change(function() {
    readURL(this);
});

//ajax request for CU action on from submission
$(document).on("click","#employee-form-submit", function(e) {
    e.preventDefault();
    var input = $(this);
    var button = input[0];
    button.disabled = true;
    input.html('Saving...'); 
        var formData = new FormData($('#employee-form')[0]);
        $("#employee-form input").removeClass('is-invalid');
            $.ajax({
                url: '/employee',
                method: 'post',
                dataType:'json',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
            success: function(result){
                if(result.errors){
                    $('.alert-danger').html('');
                    console.log(result.errors);
                    var compact_req_msg='false'; //compact required message
                    var unique_email_msg='false';
                    var email_error='false';
                    var image_file='false';
                    $.each(result.errors, function(key, value){
                        $('#'+key).addClass("is-invalid");
                        value = ""+value;
                        console.log(value.indexOf("is required."));
                        if(value.indexOf("is required.") != -1){
                            compact_req_msg='true';
                        }

                        if(key=='email'){
                            if(value.indexOf("is already used.")!= -1){
                                unique_email_msg='true';
                            }else{
                                email_error='true';
                            }
                        }
                        if(key=='photo'){
                            image_file='true';
                        }
                    });

                    if(compact_req_msg=='true'){
                        $('.alert-danger').append('<li style="font-size:0.8em"> Please fill the required fields. </li>');
                    }
                    if(unique_email_msg=='true'){
                        $('.alert-danger').append('<li style="font-size:0.8em"> Your email is already in use. </li>');
                    }
                    if(email_error=='true'){
                        $('.alert-danger').append('<li style="font-size:0.8em"> Please enter valid email. </li>');
                    }
                    if(image_file=='true'){
                        $('.alert-danger').append('<li style="font-size:0.8em"> Please upload valid image file with 2MB max size. </li>');
                        $('#upload-image-display').attr('src','/images/nobody.jpg').css('border','3px solid red');

                    }
                    
                    $('.alert-danger').show();
                    button.disabled=false;
                    input.html('Confirm'); 
                }else{
                    swal({
                        type: 'success',
                        title: 'Your work has been saved',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    
                    employee_form_reset();
                    refresh_employee_table(); // ben
                    button.disabled=false;
                    input.html('Confirm'); 
                }
            }
        });
});


//preload data on employee form on action add/update button click
$(document).on('click','.form-action-button',function(){
    $('#action').val($(this).data('action'));
    if($(this).data('action')=='edit'){
        var id = $(this).attr('data-id');
        $('#employee-form-submit')[0].disabled=true;
        $('#employee-form-submit').html('Loading...');
        $('#employee-id').val(id);
        fetch_edit_data(id);
        if($(this).data('portion')=='table'){
            $('#position option[value="1"]').css('display','none');
        }else if($(this).data('portion')=='profile'){
            if($('#logged-access-id').val()>1){
                $('#position option[value="1"]').css('display','none');
            }
        }
    }else if($(this).data('action')=='add'){
        console.log($('#position').val());
        fetch($('#position').val());
    }
    $('#employee-form-modal-header-title').html(ucword($(this).data('action')));
    $('#employee-form-modal').modal('show');
});

//OPEN MODAL for Incident Report
$(document).on('click','.add_nod',function(event){
    event.preventDefault();
    ir_id = $(this).attr("id");
    $('#button_action').val('add');
    $('#ir_id').val(ir_id);
    $('#nod_modal').modal('show');
});//end for OPEN MODAL for Incident Report

//ADD Incident Report
$(document).on('click','#add_IR',function(event){
    event.preventDefault();
        var input = $(this);
        var button =this;
        button.disabled = true;
        input.html('Saving...'); 
       // $('#add_IR_form').serialize();
       description = $('#description').val();
       console.log(description);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"/add_IR",
            method: 'POST',
            dataType:'json',
            data: {id:ir_id,description:description},
            success:function(data){
                if(data=="Error"){
                    swal("Input Missing!", "Please Enter Description.", "error")
                    button.disabled = false;
                    input.html('Save'); 
                }else{
                    button.disabled = false;
                    input.html('Save');
                    $("#button_action").val('').trigger('change');
                    $("#ir_id").val('').trigger('change');
                    $("#description").val('').trigger('change');
                    swal("Success!", "Report has been added", "success")
                    $('#nod_modal').modal('hide');
                    refresh_employee_table();  
                }
                
            },
            error: function(data){
                swal("Oh no!", "Something went wrong, try again.", "error")
                button.disabled = false;
                input.html('Save');
            }
        })
});

//UPDATE EMPLOYEE STATUS
$(document).on('click','#submit_status',function(event){
    event.preventDefault();

    var status_id = $('#status_id').val();
    var status_data = $('#status_data').val();
    var input = $(this);
    var button =this;
    button.disabled = true;
    input.html('Saving..'); 
    
    $.ajax({
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/update_status",
        dataType: "text",
        data: {status_id:status_id,status_data:status_data},
        success: function (data) {
            $('#update_status_modal').modal('hide');
            refresh_employee_table();
            swal("Success!", "Status has been updated", "success");
            button.disabled = false;
            input.html('Confirm');
        },
        error: function (data) {
            swal("Oh no!", "Something went wrong, try again.", "error");
            console.log(data)
            button.disabled = false;
            input.html('Confirm');
        }
    });
});

$(document).on('click','.update_status',function(event){
    event.preventDefault();
    var id = $(this).attr('id');
    $.ajax({
        method: "GET",
        url: "/get_status",
        dataType: "text",
        data: {id:id},
        success: function (value) {
            var data = JSON.parse(value);
            $('#update_status_modal').modal('show');
            $('#status_id').val(data[0].id);
            $('#status_data').val(data[0].status);
            $('#employee_status_name').html(data[0].firstname + ' ' + data[0].middlename + ' ' + data[0].lastname);
            console.log(data);
        },
        error: function (data) {
            swal("Oh no!", "Something went wrong, try again.", "error")
        }
    });
});

$(document).on('click','.excel-action-button', function(){
    if($(this).attr('data-action')=='import'){
        $('#import-excel-modal').modal('show');
    }
});

$(document).on('click','#excel-form-submit',function(e){
    e.preventDefault();
    var formData = new FormData($('#import-excel-form')[0]);
    $.ajax({
        url:"/profile/excel_import",
        method:"POST",
        data:formData,
        cache: false,
        contentType: false,
        processData: false,
        success:function(result)
        {
            console.log(result);
            swal({
                type: 'success',
                title: 'Your work has been saved',
                showConfirmButton: false,
                timer: 2000
            });
        }
    })
});

$(document).on('click','#excel-modal-cancel',function(){
    $('#import-excel-modal').modal('hide');
});
////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////
//FUNCTIONS
//reset employee form
function employee_form_reset(){
    $('#employee-form')[0].reset();
    $('.alert-danger').html('');
    $('#employee-form-modal').modal('hide');
    $('#designation').html('');
    $('.is-invalid').removeClass('is-invalid');
    $('.alert-danger').hide();
    $('#upload-image-display').attr('src','/images/nobody.jpg').css('border','');
}
//display photo before upload
function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#upload-image-display').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
}
  
//ucword
function ucword(str){
    return str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
    });
}
  
//fetch
function fetch(a_position,u_position,uid){
    $.ajax({
        url:"employee/fetch",
        method:"POST",
        data:{applicant_position:a_position,user_position:u_position,user_id:uid},
        success:function(result)
        {
            $('#designation').html(result);
        }
    })
}

//fetchdata for edit form
function fetch_edit_data(id){
    var input = $('#employee-form-submit');
    input.disabled = true;
    input.html('Loading...');
    $.ajax({
        url:"employee/fetch_employee_data",
        method:"POST",
        dataType:'json',
        data:{id:id},
        success:function(result)
        {
             $('#first_name').val(result.userinfo.firstname);
             $('#middle_name').val(result.userinfo.middlename);
             $('#last_name').val(result.userinfo.lastname);
             $('#address').val(result.userinfo.address);
             $('#birthdate').val(result.userinfo.birthdate);
             $('#gender option[value="'+result.userinfo.gender+'"]').prop('selected',true);
             $('#sss').val(result.userbenefit[0].id_number);
             $('#phil_health').val(result.userbenefit[1].id_number);
             $('#pag_ibig').val(result.userbenefit[2].id_number);
             $('#tin').val(result.userbenefit[3].id_number);
             $('#contact').val(result.userinfo.contact_number);
             $('#email').val(result.user[0].email);
             $('#position option[value="'+result.user[0].access_id+'"]')[0].selected=true;
             $('#salary').val(result.userinfo.salary_rate);
             $('#hired_date').val(result.userinfo.hired_date);
             fetch(result.user[0].access_id,"","");
            console.log(result.user[0].access_id);
            var designation = function(){
                fetch_blob_image(result.userinfo.id,'upload-image-display');
                $('#designation option[value="'+result.accesslevelhierarchy[0].parent_id+'"]').prop('selected',true);
                $('#employee-form-submit')[0].disabled=false;
                $('#employee-form-submit').html('Confirm');
            };
            setTimeout(designation,1000);
        }
    })
}

function refresh_employee_table(){
    employeetable.ajax.reload(); //reload datatable ajax
} 

function fetch_blob_image(id,element){
    $.ajax({
        url:"employee/fetch_blob_image",
        method:"POST",
        data:{id:id},
        success:function(result)
        {
            console.log(result);
            var img ="/images/nobody.jpg";
            if(result!='no result'){
                img = result;
            }
            $('#'+element).attr('src',img);
        }
    })
}

