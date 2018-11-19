/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.$ = jQuery;

require("./bootstrap");
window.swal = require("sweetalert2");

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
function initialize_employee_table(url) {
    if (url == "/terminatedView") {
        image = "image";
        position = "user.access.name";
        company_id = "user.company_id";
        birthdate = "birthdate";
        gender = "gender";
        contact_number = "contact_number";
        address = "address";
    } else {
        image = "child_info.image";
        company_id = "child_info.user.company_id";
        position = "child_info.user.access.name";
        birthdate = "child_info.birthdate";
        gender = "child_info.gender";
        contact_number = "child_info.contact_number";
        address = "child_info.address";
    }

    employeetable = $("#employee").DataTable({
        destroy: true,
        processing: true,
        columnDefs: [
            {
                targets: "_all", // your case first column
                className: "text-center"
            }
        ],
        ajax: url,
        autoWidth: false,
        order: [1],
        columns: [
            { data: "company_id", name: "company_id" },
            {
                width: "10%",
                data: image,
                name: "image",
                render: function(data, type, row, meta) {
                    // var data = btoa(data.substr(data.indexOf(',')));
                    if (data) {
                        // console.log(data);
                        return (
                            '<div class="table-image-cover bdrs-50p" style="background-image:url(' +
                            data +
                            ')"></div>'
                        );
                    } else {
                        return '<div class="table-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg)"></div>';
                    }
                }
            },
            { data: "name", name: "name" },
            { data: position, name: "position" },
            { data: birthdate, name: "birthdate" },
            { data: gender, name: "gender" },
            { data: contact_number, name: "contact_number" },
            { data: address, name: "address" },
            { data: "employee_status" },
            { data: "action", orderable: false, searchable: false }
        ],
        createdRow: function(row, data, index) {
            if (!data["parent_id"]) {
                // $('td', row).eq(2).css("background-color", "#FF9999");
                // $('td', row).eq(3).css("background-color", "#FF9999");
            }
        }
    });
}

initialize_employee_table("/refreshEmployeeList");

$(document).on("click", ".passChange", function() {
    if ($("#pass").val() != "") {
        var input = $(this);
        var button = this;
        button.disabled = true;
        input.text("Saving");
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            url: "/updatePass",
            method: "POST",
            dataType: "text",
            data: $("#update_password_form").serialize(),
            success: function(data) {
                button.disabled = false;
                input.html("Confirm");
                swal("Success!", "Password Changed.", "success");
                $("#showAll").prop("disabled", false);
                $("#showChild").prop("disabled", false);
                $("#showTerminated").prop("disabled", false);
                $("#import").prop("disabled", false);
                $("#export").prop("disabled", false);
                $("#addflag").prop("disabled", false);
                window.location.replace("/");
            },
            error: function(data) {
                swal("Oh no!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html("Save");
            }
        });
    } else {
        swal("Oh no!", "Please provide a valid password.", "error");
    }
});
//PROFILE EMPLOYEE LIST -- END

//DYNAMIC PROFILE -- START

//store history of profiles
var prevProfiles = [];
var prevButton = $("#prevProfile");
var currentTab;
var showBenefits;

//get id of current profile
function getCurrentProfile() {
    $.ajax({
        url: "/getCurrentProfile",
        method: "get",
        success: function(data) {
            prevProfiles.push(data); //data of current user considered as first profile in history
        }
    });
}

function getCurrentTab() {
    $.ajax({
        url: "/getCurrentTab",
        method: "get",
        success: function(data) {
            currentTab = data;
        }
    });
}

getCurrentProfile();
getCurrentTab();

function replaceProfile(data) {
    $("#name_P").html(
        data.profile.firstname +
            " " +
            data.profile.middlename +
            " " +
            data.profile.lastname
    );
    $("#role_P").html(data.role.name);
    $("#company_id_P").html(data.user.company_id);
    $("#gender_P").html(data.profile.gender);
    $("#contact_P").html(data.profile.contact_number);
    $("#address_P").html(data.profile.address);
    $("#email_P").html(data.user.email);
    $("#hired_P").html(data.profile.hired_date);

    console.log(data.viewer);

    if (data.viewer == 1 || data.viewer == 2 || showBenefits == 0) {
        $("#sss_P").html(
            !!data.profile.benefits[0].id_number
                ? data.profile.benefits[0].id_number
                : "N/A"
        );
        $("#philhealth_P").html(
            !!data.profile.benefits[1].id_number
                ? data.profile.benefits[1].id_number
                : "N/A"
        );
        $("#pagibig_P").html(
            !!data.profile.benefits[2].id_number
                ? data.profile.benefits[2].id_number
                : "N/A"
        );
        $("#tin_P").html(
            !!data.profile.benefits[3].id_number
                ? data.profile.benefits[3].id_number
                : "N/A"
        );
        $("#profile-image-display").prop("src", data.profile.image);
        $("#birth_P").html(data.profile.birthdate);
    } else {
        $("#sss_P").html("••••••");
        $("#philhealth_P").html("••••••");
        $("#pagibig_P").html("••••••");
        $("#tin_P").html("••••••");
        $("#birth_P").html("••••••");
        $("#profile-image-display").prop("src", data.profile.image);
    }

    if (data.profile.image != null) {
        $("#profile-image-display").prop("src", data.profile.image);
    } else {
        $("#profile-image-display").prop("src", "/images/nobody.jpg");
    }
}

$(document).on("click", ".view-employee", function() {
    id = $(this).attr("id");
    $("#profile-edit-button").attr("data-id", id);
    prevProfiles.push(id);
    showBenefits = prevProfiles.length;
    $.ajax({
        url: "/viewProfile",
        method: "get",
        dataType: "json",
        data: { id: id },
        success: function(data) {
            replaceProfile(data);

            initialize_employee_table("/updateEmployeeList/" + id);
            prevButton.prop("disabled", false);
        }
    });
});

$(document).on("click", "#prevProfile", function() {
    id = prevProfiles[prevProfiles.length - 2];
    $("#profile-edit-button").attr("data-id", id);
    if (prevProfiles.length > 1) {
        $.ajax({
            url: "/viewProfile",
            method: "get",
            dataType: "json",
            data: { id: id },
            success: function(data) {
                showBenefits = prevProfiles.length - 2;
                replaceProfile(data);
                if (prevProfiles.length - 2 == 0 && currentTab == "showAll") {
                    initialize_employee_table("/refreshEmployeeList");
                } else {
                    //childView
                    initialize_employee_table("/updateEmployeeList/" + id);
                }

                prevProfiles.pop();
                if (prevProfiles.length == 1) {
                    prevButton.prop("disabled", true);
                }
            }
        });
    }
});

function backToProfile() {
    $.ajax({
        url: "/backToProfile",
        method: "get",
        dataType: "json",
        success: function(data) {
            showBenefits = 0;
            replaceProfile(data);
        }
    });
}

$(document).on("click", "#showAll", function() {
    resetPrevButton();
    backToProfile();

    initialize_employee_table("/refreshEmployeeList");
    currentTab = "showAll";
});

$(document).on("click", "#showChild", function() {
    resetPrevButton();
    backToProfile();

    initialize_employee_table("/childView");

    currentTab = "childView";
});

$(document).on("click", "#showTerminated", function() {
    resetPrevButton();
    backToProfile();

    initialize_employee_table("/terminatedView");

    currentTab = "showTerminated";
});

function resetPrevButton() {
    showBenefits = 0;
    prevProfiles = [];
    prevButton.prop("disabled", true);
    getCurrentProfile();
}

//DYNAMIC PROFILE -- END

////////////////////////////////////////////////////////////////////////////////////
//TOKEN HEADER FOR HTTP REQUESTS
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    }
});

////////////////////////////////////////////////////////////////////////////////////
//EVENT

// reset modal on cancel button click
$(document).on("click", "#employee-modal-cancel", function(e) {
    e.preventDefault();
    employee_form_reset();
});

//get group leaders on position change
$(document).on("change", "#position", function() {
    var a_position = $(this).val(); //applicant positon a_position
    var u_position = $("#user-access-level").val(); //logged position
    var eid = $("#employee-id").val();
    fetch(a_position, u_position, eid);
});

//display input file image before upload on change
$("#photo").change(function() {
    readURL(this);
    // console.log(this);
});
$(document).on("change", "#excel_file", function() {
    if (
        $(this)
            .val()
            .split(".")
            .pop()
            .toLowerCase() == "xlsx"
    ) {
        $("#excel-file-label")
            .removeClass("btn-secondary")
            .addClass("btn-info");
        $("#excel-file-label").html("File Selected");
        $("#excel-form-submit")[0].disabled = false;
    } else {
        $("#excel-file-label")
            .removeClass("btn-info")
            .addClass("btn-secondary");
        $("#excel-file-label").html("Invalid File.");
        $("#excel-form-submit")[0].disabled = true;
    }
});

document.getElementById("photo").onchange = function(evt) {};
//ajax request for CU action on from submission
$(document).on("click", "#employee-form-submit", function(e) {
    e.preventDefault();
    var input = $(this);
    var button = input[0];
    button.disabled = true;
    input.html("Saving...");
    var formData = new FormData($("#employee-form")[0]);
    $("#employee-form input").removeClass("is-invalid");
    $.ajax({
        url: "/employee",
        method: "post",
        dataType: "json",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            if (result.errors) {
                $(".alert-danger").html("");
                // console.log(result.errors);
                var compact_req_msg = "false"; //compact required message
                var unique_email_msg = "false";
                var unique_name_msg = "false";
                var email_error = "false";
                var image_file = "false";
                $.each(result.errors, function(key, value) {
                    $("#" + key).addClass("is-invalid");
                    value = "" + value;
                    console.log(value.indexOf("is required."));
                    if (value.indexOf("is required.") != -1) {
                        compact_req_msg = "true";
                    }

                    if (key == "email") {
                        if (value.indexOf("is already used.") != -1) {
                            unique_email_msg = "true";
                        } else {
                            email_error = "true";
                        }
                    }
                    if (key == "p_email") {
                        if (value.indexOf("is already used.") != -1) {
                            unique_pemail_msg = "true";
                        } else {
                            email_error = "true";
                        }
                    }
                    if (key == "first_name") {
                        if (value == "Name Already Exist.") {
                            unique_name_msg = "true";
                        }
                    }
                    if (key == "photo") {
                        image_file = "true";
                    }
                });

                if (compact_req_msg == "true") {
                    $(".alert-danger").append(
                        '<li style="font-size:0.8em"> Please fill the required fields. </li>'
                    );
                }
                if (unique_email_msg == "true") {
                    $(".alert-danger").append(
                        '<li style="font-size:0.8em"> Your email is already in use. </li>'
                    );
                }
                if (email_error == "true") {
                    $(".alert-danger").append(
                        '<li style="font-size:0.8em"> Email address already taken or invalid in format.</li>'
                    );
                }
                if (image_file == "true") {
                    $(".alert-danger").append(
                        '<li style="font-size:0.8em"> Please upload valid image file with 2MB max size. </li>'
                    );
                    $("#upload-image-display")
                        .attr("src", "/images/nobody.jpg")
                        .css("border", "3px solid red");
                }
                if (unique_name_msg == "true") {
                    $(".alert-danger").append(
                        '<li style="font-size:0.8em"> Applicant name already Exists. </li>'
                    );
                    // $.each(result.errors, function(key, value){
                    //     $('#'+key).addClass("is-invalid");
                    // });
                }

                $(".alert-danger").show();
                button.disabled = false;
                input.html("Confirm");
            } else {
                swal("Success!", "Your work has been saved", "success");

                button.disabled = false;
                input.html("Confirm");
                if ($("#action").val() == "edit") {
                    if ($("#portion").val() == "profile") {
                        if ($("#employee-id").val() == 1) {
                            var data = result.info.image;
                            $("#top-image-display").css(
                                "background-image",
                                "url(" + data + ")"
                            );
                            $("#top-bar-name").html(
                                result.info.firstname +
                                    " " +
                                    result.info.middlename +
                                    " " +
                                    result.info.lastname
                            );
                        }
                        if (result.info.image) {
                            $("#profile-image-display").attr(
                                "src",
                                result.info.image
                            );
                        } else {
                            $("#profile-image-display").attr(
                                "src",
                                "/images/nobody.jpg"
                            );
                        }
                        console.log(result.user);
                        $("#company_id_P").html(result.user.company_id);
                        $("#contact_P").html(result.info.contact_number);
                        $("#email_P").html(result.user.email);
                        $("#address_P").html(result.info.address);
                        $("#sss_P").html(result.benefit[0].id_number);
                        $("#philhealth_P").html(result.benefit[1].id_number);
                        $("#pagibig_P").html(result.benefit[2].id_number);
                        $("#tin_P").html(result.benefit[3].id_number);
                        $("#birth_P").html(result.info.birthdate);
                        $("#hired_P").html(result.info.hired_date);
                        $("#name_P").html(
                            result.info.firstname +
                                " " +
                                result.info.middlename +
                                " " +
                                result.info.lastname
                        );

                        // $('#profile-image-display').attr('src',result.image);
                    }
                }
                refresh_employee_table(); // ben
                employee_form_reset();
            }
        }
    });
});

//preload data on employee form on action add/update button click
$(document).on("click", ".form-action-button", function() {
    var action = $(this).attr("data-action");
    $("#action").val(action);
    var access_id = $("#logged-position").val();
    var id = $(this).attr("data-id");
    $("#role").val(access_id);
    $(".admin-hidden-field").show();
    if (action == "edit") {
        var portion = $(this).attr("data-portion");
        $("#portion").val(portion);
        $("#employee-form-submit")[0].disabled = true;
        $("#employee-form-submit").html("Loading...");
        $("#employee-id").val(id);

        fetch_edit_data(id);
        if (portion == "profile") {
            if (id == 1) {
                $(".admin-hidden-field").hide();
            }
        }
    } else if (action == "add") {
        // console.log($('#position').val());
        fetch($("#position").val());
    }
    $("#employee-form-modal-header-title").html(ucword(action));
    $("#employee-form-modal").modal("show");
});

/////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////// START ADD POSITION
/////////////////////////////////////////////////////////////

$(document).on("click", ".add-position-button", function() {
    var action = $(this).attr("data-action");
    $("#action").val(action);
    if (action == "add") {
        // console.log($('#position').val());
    }
    $("#position-modal-header").html(ucword(action));

    function ajax1() {
        return $.ajax({
            url: "/get_position",
            method: "GET",
            dataType: "json",
            success: function(data) {
                $("#position_designation")
                    .find("option")
                    .remove();
                for (x = 0; x < data.length; x++) {
                    $("#position_designation").append(
                        '<option value="' +
                            data[x].id +
                            '">' +
                            data[x].name +
                            "</option>"
                    );
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

    $.when(ajax1()).done(function(a1) {
        $("#position-modal-form").modal("show");
    });
});

$(document).on("click", "#position-modal-cancel", function() {
    $("#position-modal-form").modal("hide");
});

$(document).on("click", "#position-form-submit", function(event) {
    event.preventDefault();
    var input = $(this);
    var button = this;
    button.disabled = true;
    input.html("Saving...");
    // $('#add_IR_form').serialize();
    description = $("#description").val();
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        url: "/add_position",
        method: "POST",
        dataType: "json",
        data: $("#add-position-form").serialize(),
        success: function(data) {
            swal("Done!", "New position successfully added.", "success");
            button.disabled = false;
            input.html("Save");
            $("#position-modal-form").modal("hide");
            $("#add-position-form")[0].reset();
        },
        error: function(data) {
            swal("Oh no!", "Something went wrong, try again.", "error");
            button.disabled = false;
            input.html("Save");
        }
    });
});

/////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////// END ADD POSITION
/////////////////////////////////////////////////////////////

var ir_list;

$("#nod_modal").on("hidden.bs.modal", function(e) {
    ir_list.destroy();
});

//OPEN MODAL for Incident Report
$(document).on("click", ".add_nod", function(event) {
    event.preventDefault();
    $('.nav-tabs a[href="#ir_list"]').tab("show");
    ir_id = $(this).attr("id");
    $("#button_action").val("add");
    $("#ir_id").val(ir_id);
    $("#nod_modal").modal("show");
    $.ajax({
        url: "/get_ir",
        method: "get",
        data: { id: ir_id },
        dataType: "json",
        success: function(data) {
            //Incident Repots View List Datatable
            ir_list = $("#ir_table_list").DataTable({
                processing: true,
                columnDefs: [
                    {
                        targets: "_all", // your case first column
                        className: "text-center"
                    }
                ],
                serverside: true,
                data: data.data,
                columns: [
                    { data: "description", name: "description" },
                    { data: "date_filed", name: "date_filed" },
                    {
                        data: "filed_by",
                        render: function(data, type, full, meta) {
                            return (
                                full.firstname +
                                " " +
                                full.middlename +
                                " " +
                                full.lastname
                            );
                        }
                    }
                ]
            });
        },
        error: function(data) {
            swal("Oh no!", "Something went wrong, try again.", "error");
            button.disabled = false;
            input.html("SAVE CHANGES");
        }
    });
}); //end for OPEN MODAL for Incident Report

//ADD Incident Report
$(document).on("click", "#add_IR", function(event) {
    event.preventDefault();
    var input = $(this);
    var button = this;
    button.disabled = true;
    input.html("Saving...");
    // $('#add_IR_form').serialize();
    description = $("#description").val();
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        url: "/add_IR",
        method: "POST",
        dataType: "json",
        data: { id: ir_id, description: description },
        success: function(data) {
            if (data == "Error") {
                swal("Input Missing!", "Please Enter Description.", "error");
                button.disabled = false;
                input.html("Save");
            } else {
                button.disabled = false;
                input.html("Save");
                $("#button_action")
                    .val("")
                    .trigger("change");
                $("#ir_id")
                    .val("")
                    .trigger("change");
                $("#description")
                    .val("")
                    .trigger("change");
                swal("Success!", "Report has been added", "success");
                $("#nod_modal").modal("hide");
                refresh_employee_table();
            }
        },
        error: function(data) {
            swal("Oh no!", "Something went wrong, try again.", "error");
            button.disabled = false;
            input.html("Save");
        }
    });
});

//UPDATE EMPLOYEE STATUS
$(document).on("click", "#submit_status", function(event) {
    event.preventDefault();

    var status_id = $("#status_id").val();
    var status_data = $("#status_data").val();
    var input = $(this);
    var button = this;
    button.disabled = true;
    input.html("Saving..");

    $.ajax({
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        url: "/update_status",
        dataType: "text",
        data: { status_id: status_id, status_data: status_data },
        success: function(data) {
            $("#update_status_modal").modal("hide");
            refresh_employee_table();
            swal("Success!", "Status has been updated", "success");
            button.disabled = false;
            input.html("Confirm");
        },
        error: function(data) {
            swal("Oh no!", "Something went wrong, try again.", "error");
            // console.log(data)
            button.disabled = false;
            input.html("Confirm");
        }
    });
});

$(document).on("click", ".update_status", function(event) {
    event.preventDefault();
    var id = $(this).attr("id");
    $.ajax({
        method: "GET",
        url: "/get_status",
        dataType: "text",
        data: { id: id },
        success: function(value) {
            var data = JSON.parse(value);
            $("#update_status_modal").modal("show");
            $("#status_id").val(data[0].id);
            $("#status_data").val(data[0].status);
            $("#employee_status_name").html(
                data[0].firstname +
                    " " +
                    data[0].middlename +
                    " " +
                    data[0].lastname
            );
        },
        error: function(data) {
            swal("Oh no!", "Something went wrong, try again.", "error");
        }
    });
});

$(document).on("click", ".excel-action-button", function(e) {
    $("#action-import").show();
    $("#action-export").show();
    $("#excel-form-submit").show();

    $("#excel-modal").modal("show");
    if ($(this).attr("data-action") == "import") {
        $("#action-export").hide();
        $("#excel-modal-header").html("Import");
        $("#excel-file-label").html("Select Excel File.");
        $("#excel_file").val("");
        $("#excel-file-label")
            .removeClass("btn-info")
            .addClass("btn-secondary");
    } else {
        $("#action-import").hide();
        $("#excel-form-submit").hide();
        $("#excel-modal-header").html("Export");
    }
});

$(document).on("click", "#excel-form-submit", function(e) {
    e.preventDefault();
    var formData = new FormData($("#import-excel-form")[0]);
    var btn = $("#excel-form-submit");
    btn[0].disabled = true;
    btn.html("Loading...");
    $.ajax({
        url: "/profile/excel_import",
        method: "POST",
        data: formData,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            btn.html("Confirm");
            btn[0].disabled = false;
            // alert(result+"success");
            // var reassign = '<li><strong>' + result[0].reassign_counter+'</strong> employee/s reassigned.</li>';
            var title = "";
            var htmlcontent = "";

            if (result[0].outdated == true) {
                title = "<strong>Outdated Template</strong>";
                htmlcontent =
                    '<div class="alert alert-info"> Please download new template.</div>';
            } else if (result[0].outdated == false) {
                if (result[0].action == "Add") {
                    var err = 0;
                    if (result[0].error_rows.length > 0) {
                        err = result[0].error_rows;
                    } else {
                        err = 0;
                    }
                    title = "<strong>Add Employee Report</strong>";
                    htmlcontent =
                        '<div class="alert alert-success"><strong>' +
                        result[0].saved_counter +
                        "</strong> record/s added.</div>";
                    htmlcontent +=
                        '<div class="alert alert-info"><strong>' +
                        result[0].duplicate_counter +
                        "</strong> duplicate/s found.</div>";
                    htmlcontent +=
                        '<div class="alert alert-warning">Error rows: ' +
                        err.toString() +
                        "</div>";
                } else if (result[0].action == "Reassign") {
                    title = "<strong>Reassign Employee Report</strong>";
                    htmlcontent =
                        '<div class="alert alert-success"><strong>' +
                        result[0].reassign_counter +
                        "</strong> reassigned employee/s.</div>";
                }
            }

            if (result == "File not valid.") {
                title = "<strong>Invalid</strong>";
                htmlcontent =
                    "<div class='alert alert-danger'>Please upload a <strong>.xlsx</strong> file.</div>";
            }

            swal({
                title: title,
                html: htmlcontent,
                focusConfirm: false,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Noted!',
                confirmButtonAriaLabel: "Thumbs up, great!"
            });

            $("#excel-modal").modal("hide");
            refresh_employee_table();
        },
        timeout: 10000,
        async:false
    });
});

$(document).on("click", "#excel-modal-cancel", function() {
    $("#excel-modal").modal("hide");
});

$(document).on("click", "#import", function() {
    $("#excel-form-submit")[0].disabled = false;
});
////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////
//FUNCTIONS
//reset employee form
function employee_form_reset() {
    $("#employee-form")[0].reset();
    $(".alert-danger").html("");
    $("#employee-form-modal").modal("hide");
    $("#designation").html("");
    $(".is-invalid").removeClass("is-invalid");
    $(".alert-danger").hide();
    $("#upload-image-display")
        .attr("src", "/images/nobody.jpg")
        .css("border", "");
}
//display photo before upload
function readURL(input) {
    // console.log(input);

    var reader = new FileReader();
    if (input.files && input.files[0]) {
        reader.onload = function(e) {
            $("#upload-image-display").attr("src", e.target.result);
            //  console.log($('#photo').attr());
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        $("#upload-image-display").attr("src", input);
        $("#captured_photo").val(input);
    }
    // console.log($('#captured_photo').val());
}

//ucword
function ucword(str) {
    return (str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
    }));
}

//fetch
function fetch(a_position, u_position, eid) {
    $.ajax({
        url: "employee/fetch",
        method: "POST",
        data: {
            applicant_position: a_position,
            user_position: u_position,
            employee_id: eid
        },
        success: function(result) {
            $("#designation").html(result);
            //    console.log(eid);
        }
    });
}

//fetchdata for edit form
function fetch_edit_data(id) {
    var input = $("#employee-form-submit");
    input.disabled = true;
    input.html("Loading...");
    $.ajax({
        url: "employee/fetch_employee_data",
        method: "POST",
        dataType: "json",
        data: { id: id },
        success: function(result) {
            $("#first_name").val(result.userinfo.firstname);
            $("#middle_name").val(result.userinfo.middlename);
            $("#last_name").val(result.userinfo.lastname);
            $("#address").val(result.userinfo.address);
            $("#birthdate").val(result.userinfo.birthdate);
            $('#gender option[value="' + result.userinfo.gender + '"]').prop(
                "selected",
                true
            );
            $("#sss").val(result.userbenefit[0].id_number);
            $("#phil_health").val(result.userbenefit[1].id_number);
            $("#pag_ibig").val(result.userbenefit[2].id_number);
            $("#tin").val(result.userbenefit[3].id_number);
            $("#contact").val(result.userinfo.contact_number);
            $("#email").val(result.user[0].email);
            $(
                '#position option[value="' + result.user[0].access_id + '"]'
            )[0].selected = true;
            $("#salary").val(result.userinfo.salary_rate);
            $("#p_email").val(result.userinfo.p_email);
            $("#company_id").val(result.user[0].company_id);
            $("#hired_date").val(result.userinfo.hired_date);
            fetch(result.user[0].access_id, "", "");
            if (result.userinfo.image != null) {
                $("#upload-image-display").attr("src", result.userinfo.image);
            } else {
                $("#upload-image-display").attr("src", "/images/nobody.jpg");
            }

            var designation = function() {
                $(
                    '#designation option[value="' +
                        result.accesslevelhierarchy[0].parent_id +
                        '"]'
                ).prop("selected", true);
                $("#employee-form-submit")[0].disabled = false;
                $("#employee-form-submit").html("Confirm");
            };
            setTimeout(designation, 1000);
        }
    });
}

function refresh_employee_table() {
    employeetable.ajax.reload(); //reload datatable ajax
}

document.addEventListener("DOMContentLoaded", function() {
    // References to all the element we will need.
    var video = document.querySelector("#camera-stream"),
        image = document.querySelector("#snap"),
        start_camera = document.querySelector("#start-camera"),
        controls = document.querySelector(".controls"),
        take_photo_btn = document.querySelector("#take-photo"),
        delete_photo_btn = document.querySelector("#delete-photo"),
        done = document.querySelector("#done"),
        download_photo_btn = document.querySelector("#download-photo"),
        error_message = document.querySelector("#error-message");

    $(document).on("click", "#done", function(event) {
        event.preventDefault();
        $("#show_camera").attr("hidden", "");
    });

    $("#employee-form-modal").on("hidden.bs.modal", function(e) {
        video.pause();
        $("#show_camera").attr("hidden", "");
    });

    // The getUserMedia interface is used for handling camera input.
    // Some browsers need a prefix so here we're covering all the options
    $(document).on("click", "#start-camera", function() {
        $("#show_camera").removeAttr("hidden");

        navigator.getMedia =
            navigator.getUserMedia ||
            navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia ||
            navigator.msGetUserMedia;

        if (!navigator.getMedia) {
            displayErrorMessage(
                "Your browser doesn't have support for the navigator.getUserMedia interface."
            );
        } else {
            // Request the camera.
            navigator.getMedia(
                {
                    video: true
                },
                // Success Callback
                function(stream) {
                    // Create an object URL for the video stream and
                    // set it as src of our HTLM video element.
                    video.srcObject = stream;

                    // Play the video element to start the stream.
                    video.play();
                    video.onplay = function() {
                        showVideo();
                    };
                    $(document).on("click", "#done", function(event) {
                        event.preventDefault();
                        $("#show_camera").attr("hidden", "");
                        stream.getTracks().forEach(track => track.stop());
                    });

                    $("#employee-form-modal").on("hidden.bs.modal", function(
                        e
                    ) {
                        $("#show_camera").attr("hidden", "");
                        stream.getTracks().forEach(track => track.stop());
                    });
                },
                // Error Callback
                function(err) {
                    displayErrorMessage(
                        "There was an error with accessing the camera stream: " +
                            err.name,
                        err
                    );
                }
            );
        }
    });

    // Mobile browsers cannot play video without user input,
    // so here we're using a button to start it manually.

    start_camera.addEventListener("click", function(e) {
        e.preventDefault();

        // Start video playback manually.
        video.play();
        showVideo();
    });

    take_photo_btn.addEventListener("click", function(e) {
        e.preventDefault();
        readURL(takeSnapshot());
        // var snap = takeSnapshot();

        // video.setAttribute('src', "");
        // video.classList.remove("visible");
        // // Show image.
        // image.setAttribute('src', snap);
        // image.classList.add("visible");

        // Enable delete and save buttons
        delete_photo_btn.classList.remove("disabled");
        download_photo_btn.classList.remove("disabled");
        done.classList.remove("disabled");

        // // Set the href attribute of the download button to the snap url.
        download_photo_btn.href = takeSnapshot();

        // Pause video playback of stream.
        video.pause();
    });

    delete_photo_btn.addEventListener("click", function(e) {
        e.preventDefault();

        // Hide image.
        image.setAttribute("src", "");
        image.classList.remove("visible");

        // Disable delete and save buttons
        delete_photo_btn.classList.add("disabled");
        download_photo_btn.classList.add("disabled");
        done.classList.add("disabled");
        // Resume playback of stream.
        video.play();
    });

    function takeSnapshot() {
        // Here we're using a trick that involves a hidden canvas element.

        var hidden_canvas = document.querySelector("canvas"),
            context = hidden_canvas.getContext("2d");

        var width = video.videoWidth,
            height = video.videoHeight;

        if (width && height) {
            // Setup a canvas with the same dimensions as the video.
            hidden_canvas.width = width;
            hidden_canvas.height = height;

            // Make a copy of the current frame in the video on the canvas.
            context.drawImage(video, 0, 0, width, height);
            // Turn the canvas image into a dataURL that can be used as a src for our photo.
            return hidden_canvas.toDataURL("image/png");
        }
    }

    function showVideo() {
        hideUI();
        video.classList.add("visible");
        controls.classList.add("visible");
    }

    function displayErrorMessage(error_msg, error) {
        error = error || "";
        if (error) {
            // console.error(error);
        }

        error_message.innerText = error_msg;

        hideUI();
        error_message.classList.add("visible");
    }

    function hideUI() {
        // Helper function for clearing the app UI.

        controls.classList.remove("visible");
        start_camera.classList.remove("visible");
        video.classList.remove("visible");
        snap.classList.remove("visible");
        error_message.classList.remove("visible");
    }
    var modal = document.getElementById("full_pic");

    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.getElementById("profile-image-display");
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");
    img.onclick = function() {
        modal.style.display = "block";
        modalImg.src = this.src;
        captionText.innerHTML = this.alt;
    };

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close_pic")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    };
});
