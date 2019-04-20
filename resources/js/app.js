/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.$ = jQuery;
window.swal = require("sweetalert2");
window.Vue = require("vue");
require("./bootstrap");
require("datatables.net-fixedcolumns");
// require("./page/dashboard");
import VPopover from "vue-js-popover";
Vue.use(VPopover, {
    tooltip: true
});
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
Vue.mixin({
    data() {
        return {
            profile: {
                id: "",
                name: {
                    first: "",
                    middle: "",
                    last: ""
                },
                gender: "",
                birth: "",
                address: "",
                mobile: "",
                email: "",
                position: "",
                benefit: {
                    sss: "",
                    tin: "",
                    ph: "",
                    pibg: ""
                },
                image: "",
            },
            endpoints: {
                tmp: {
                    update: {
                        event: "/api/v1/events/update/",
                        incident_report_response: '/api/v1/update_response/',
                        sanction_level: "/api/v1/sanction_level/update/",
                        sanction_type: "/api/v1/sanction_type/update/",
                        issued_incident_report: '',
                    },
                    delete: {
                        event: "/api/v1/events/delete/",
                        sanction_level: "/api/v1/sanction_level/delete/",
                        sanction_type: "/api/v1/sanction_type/delete/",
                        issued_incident_report: '',

                    },
                },
                update: {
                    event: "",
                    incident_report_response: '',
                    sanction_level: "",
                    sanction_type: "",
                    issued_incident_report: '',
                    schedule: '',
                },
                delete: {
                    event: "",
                    sanction_level: "",
                    sanction_type: "",
                    issued_incident_report: '',
                    schedule: ""
                },
                create: {
                    sanction_level: "/api/v1/sanction_level/create",
                    sanction_type: "/api/v1/sanction_type/create",
                    issued_incident_report: "/api/v1/reports/create",
                    event: "/api/v1/events/create",
                    incident_report_response: '/api/v1/user_response',
                    schedule: ''
                },
                table: {
                    sanction_level: "/api/v1/sanction_level/sanction_levels",
                    sanction_type: "/api/v1/sanction_type/sanction_types",
                    received_incident_report: "/api/v1/reports/user/",
                    issued_incident_report: "/api/v1/reports/user_filed_ir/",
                    event: "/api/v1/events",
                    agent: "/api/v1/agents",
                    agent_search: "/api/v1/agents/search",
                    rta_scheduler: '/api/schedules/agents/'
                },
                select: {
                    sanction_level: "/api/v1/sanction_level/select_sanction_levels",
                    sanction_type: "/api/v1/sanction_type/select_sanction_types",
                    child_list: "/api/v1/reports/select_all_users/1", //TEMPORARY ID PARAM
                    schedule_title: "/api/v1/events/select", //TEMPORARY ID PARAM
                }
            },
            form: {
                issued_incident_report: {
                    select_option: {
                        sanction_level: [],
                        sanction_type: [],
                        child_list: []
                    },
                    selected: {
                        sanction_level: {
                            value: "",
                            text: ""
                        },
                        sanction_type: {
                            value: "",
                            text: ""
                        },
                        child_list: {
                            value: "",
                            text: ""
                        }
                    },
                    textarea: "",
                    action: "create"
                },
                sanction_level: {
                    level: "",
                    description: "",
                    action: "create"
                },
                sanction_type: {
                    type: "",
                    description: "",
                    action: "create"
                },
                incident_report_response: {
                    sanction: {
                        type: "",
                        level: ""
                    },
                    ir_description: "",
                    ir_date: "",
                    received_by: "",
                    received_by_position: "",
                    filed_by: "",
                    filed_by_position: "",
                    response: ""
                },
                event: {
                    color: {
                        hex: "#000000"
                    },
                    title: "",
                    action: "create",
                    id: "",
                },
                schedule: {
                    action: 'create',
                    id: '',
                    schedule_id: '', //FOR UPDATE
                    user: '',
                    title: '',
                    event: {
                        start: '',
                        end: ''
                    },
                    time_in: '',
                    hours: '',
                    select_option: {
                        title: []
                    }
                }
            },
            table: {
                sanction_level: {
                    data: [],
                    fetch_status: 'fetching'
                },
                sanction_type: {
                    data: [],
                    fetch_status: 'fetching'
                },
                received_incident_report: {
                    data: [],
                    fetch_status: 'fetching'
                },
                issued_incident_report: {
                    data: [],
                    fetch_status: 'fetching'
                },
                event: {
                    data: [],
                    fetch_status: 'fetching'
                },
                agent: {
                    data: [],
                    fetch_status: 'fetching'
                },
                agent_search: {
                    data: [],
                    fetch_status: 'fetching'
                },
                calendar: {
                    data: [],
                    fetch_status: 'fetching'
                }
            },
            stats: {
                today: {
                    scheduled: "",
                    day_off: "",
                    on_break: "",
                    working: "",
                    absent: ""
                }
            }
        };
    },
    methods: {
        //fetch
        fetchProfile: function () {
            this.showModal("profile_preview");
        },
        //modal toggler
        showModal: function (modalName) {
            this.$modal.show(modalName);
        },
        hideModal: function (modalName) {
            this.$modal.hide(modalName);
        },

        fetchTableObject: function (tableName) {
            let pageurl = this.endpoints.table[tableName];
            this.table[tableName].fetch_status = 'fetching';
            fetch(pageurl)
                .then(res => res.json())
                .then(res => {
                    console.log(res);
                    this.table[tableName].fetch_status = 'fetched';
                    if (res.code == 200) {
                        this.table[tableName].data = res.meta;
                    }
                })
                .catch(err => {
                    console.log(err);
                    this.table[tableName].fetch_status = 'fetched';

                });
        },


        store: function (obj, action, formName) {
            let pageurl = this.endpoints[action][formName];
            fetch(pageurl, {
                    method: "post",
                    body: JSON.stringify(obj),
                    headers: {
                        "content-type": "application/json"
                    }
                })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    if (data.code == 500) {
                        console.log("error");
                        this.notify("error", action);
                    } else {
                        console.log(data);
                        this.fetchTableObject(formName);
                        this.hideModal(formName);
                        this.notify("success", action);
                        // this.saveLog('succuss', formName, action, data);
                    }
                })
                .catch(err => console.log(err));
        },
        notify: function (status, action) {
            let dtitle = "";
            let dtext = "";
            let dtype = "";
            switch (status) {
                case "success":
                    dtitle = "Success Notification";
                    dtext = "You have successfully " + action + "d a record.";
                    dtype = "success";
                    break;
                case "error":
                    dtitle = "Error Notification";
                    dtitle = "Error " + action + "ing a record.";
                    dtype = "warning";
                    break;
            }
            this.$notify({
                group: "foo",
                title: dtitle,
                text: dtext + "<br/><small>CNM Solutions WebApp</small>",
                type: dtype
            });
        },
        formValidationError: function () {
            this.$notify({
                group: "foo",
                title: 'Form Validation',
                text: "Please fill all fields.<br/><small>CNM Solutions WebApp</small>",
                type: "info"
            });
        },

        isEmpty: function (obj) {
            for (var key in obj) {
                if (obj.hasOwnProperty(key)) return false;
            }
            return true;
        },
        fetchSelectOptions: function (url, formName, element) {
            let pageurl = url;
            fetch(pageurl)
                .then(res => res.json())
                .then(res => {
                    if (res.code == 200) {
                        this.form[formName].select_option[element] = res.meta.options;
                    }
                    // console.log(res.meta.options);
                })
                .catch(err => console.log(err));
        },
        loadIRResponseForm: function (report_id) {
            let obj = [];
            this.table.received_incident_report.data.reports_data[0].reports.forEach(
                function (v, i) {
                    if (v.id == report_id) {
                        obj = v;
                    }
                }
            );
            this.form.incident_report_response.sanction.level =
                obj.sanction_level.text;
            this.form.incident_report_response.sanction.type = obj.sanction_type.text;
            this.form.incident_report_response.ir_description = obj.description;
            this.form.incident_report_response.ir_date = obj.created_at;
            this.form.incident_report_response.received_by = this.table.received_incident_report.data.reports_data[0].full_name;
            // this.form.incident_report_response.received_by_position = this.table.received_incident_report.data.reports_data[0].full_name;
            this.form.incident_report_response.filed_by = obj.filedby.full_name;
            // this.form.incident_report_response.filed_by_position = obj.filedby.full_name;
        },
        split: function (str, sep, index) {
            return str.split(sep)[index];
        }
    }
});


import "fullcalendar/dist/fullcalendar.min.css";
import FullCalendar from "vue-full-calendar";
Vue.use(FullCalendar);

import VueCtkDateTimePicker from "vue-ctk-date-time-picker";
import "vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css";
Vue.component("date-time-picker", VueCtkDateTimePicker);

import rtaVue from "./components/rtaSchedPage.vue";
Vue.component("rta-sched-section", rtaVue);

import IrResponseModal from "./components/IrResponseModal.vue";
Vue.component("ir-response-modal", IrResponseModal);

import rtaDashboard from "./components/rtaDashboardPage.vue";
Vue.component("rta-dashboard-section", rtaDashboard);

import profilePreview from "./components/profilePreview.vue";
Vue.component("profile-preview-modal", profilePreview);

import stats_component1 from "./components/statsComponent1.vue";
Vue.component("stats-component-1", stats_component1);

import rtaReports from "./components/RTA/reports/rtaReports";
Vue.component("rta-reports", rtaReports);
import sanction_level from "./components/table/sanction_level.vue";
Vue.component("sanction-level", sanction_level);

import sanction_type from "./components/table/sanction_type.vue";
Vue.component("sanction-type", sanction_type);

import incident_report from "./components/table/incident_report.vue";
Vue.component("incident-report", incident_report);

import received_ir from "./components/table/received_ir.vue";
Vue.component("received-ir", received_ir);

import issued_ir from "./components/table/issued_ir.vue";
Vue.component("issued-ir", issued_ir);


import mini_calendar from "./components/mini_calendar.vue";
Vue.component("mini-calendar", mini_calendar);

import trackerGraph from "./components/trackerGraph.vue";
Vue.component("work-graph", trackerGraph);


import wr_modal from "./components/dailyWorkReportModal.vue";
Vue.component("daily-work-report-modal", wr_modal);

import today_activity from "./components/table/today_activity.vue";
Vue.component("today-activity", today_activity);

// import ZpUI from 'zp-crm-ui'
import Sparkline from 'vue-sparklines'
// Vue.use(ZpUI)
Vue.use(Sparkline)

import VModal from "vue-js-modal";
Vue.use(VModal);

import Notifications from "vue-notification";
import velocity from "velocity-animate";

////// VUEINIT


Vue.use(Notifications, {
    velocity
});

// import {
//     VSelect
// } from 'vue-search-select';
// Vue.use(VSelect);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
//GLOBAL VUE FUNCTIONS

const app = new Vue({
    el: "#app"
});

//vue init vairables

// app.endpoints.table.issued_incident_report = app.endpoints.table.issued_incident_report + $('#uid').val()
//native trigger to vue component
$(document).on("click", "#loadProfilePreview", function (e) {
    app.fetchProfile();
});

///////// DASHBOARD
$(document).on("click", ".passChange", function (e) {
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
            success: function (data) {
                button.disabled = false;
                input.html("Confirm");
                swal("Success!", "Password Changed.", "success");
                $("#showAll").prop("disabloginled", false);
                $("#showChild").prop("disabled", false);
                $("#showTerminated").prop("disabled", false);
                $("#import").prop("disabled", false);
                $("#export").prop("disabled", false);
                $("#addflag").prop("disabled", false);
                window.location.replace("/");
            },
            error: function (data) {
                swal("Oh no!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html("Save");
            }
        });
    } else {
        e.preventDefault();
        swal("Oh no!", "Please provide a valid password.", "error");
    }
});
var curpage = "dashboad";


// >> FOR ADMIN, HR MANAGER, HR ASSISTANT value = 1,2,3
if ($('#hr-dashboard').length) {
    //global variables
    var ir_id;
    var description;
    //end of global variables

    //PROFILE EMPLOYEE LIST -- START
    function initialize_employee_table(url) {
        let image = "";
        let company_id = "";
        let position = "";
        let birthdate = "";
        let gender = "";
        let contact_number = "";
        let address = "";
        let contract = "";
        let email = "";
        let p_email = "";
        if (url == "/terminatedView") {
            image = "image";
            position = "user.access.name";
            company_id = "user.company_id";
            birthdate = "birthdate";
            gender = "gender";
            contact_number = "contact_number";
            address = "address";
            contract = "user.contract";
            email = "p_email";
            p_email = "user.email";
        } else {
            image = "child_info.image";
            company_id = "child_info.user.company_id";
            position = "child_info.user.access.name";
            birthdate = "child_info.birthdate";
            gender = "child_info.gender";
            contact_number = "child_info.contact_number";
            address = "child_info.address";
            contract = "child_info.user.contract";
            email = "child_info.p_email";
            p_email = "child_info.user.email";
        }

        let employeetable = $("#employee").DataTable({
            destroy: true,
            processing: true,
            blengthChange: false,
            scrollX: true,
            lengthMenu: [5, 10, 25, 50, 100],
            fixedColumns: {
                leftColumns: 4
            },
            columnDefs: [{
                    targets: [5, 6, 7, 8, 9, 10, 11, 12], // your case all column
                    className: ["dt-center", "no-wrap"],
                    autoWidth: true
                },

                {
                    targets: [4],
                    className: ["image-center"]
                },

                {
                    targets: [0, 1, 2, 3],
                    className: "bg-muted"
                }
            ],
            ajax: url,
            order: [1],
            columns: [{
                    data: "company_id",
                    name: "company_id"
                },
                {
                    data: "name",
                    name: "name"
                },

                {
                    data: "employee_status"
                },
                {
                    data: "action",
                    orderable: false,
                    searchable: false
                },
                {
                    data: image,
                    name: "image",
                    render: function (data, type, row, meta) {
                        // var data = btoa(data.substr(data.indexOf(',')));
                        if (data) {
                            // console.log(data);
                            return (
                                '<div class="table-image-cover bdrs-50p" data-img="' +
                                data +
                                '" style="background-image:url(' +
                                data +
                                ')"></div>'
                            );
                        } else {
                            return '<div class="table-image-cover bdrs-50p" data-img="/images/nobody.jpg" style="background-image:url(/images/nobody.jpg)"></div>';
                        }
                    }
                },
                {
                    data: position,
                    name: "position"
                },
                {
                    data: birthdate,
                    name: "birthdate"
                },
                {
                    data: gender,
                    name: "gender"
                },
                {
                    data: contact_number,
                    name: "contact_number"
                },
                {
                    data: address,
                    name: "address"
                },
                {
                    data: contract,
                    name: "contract"
                },
                {
                    data: email,
                    name: "email"
                },
                {
                    data: p_email,
                    name: "p_email"
                }
            ],
            createdRow: function (row, data, index) {
                if (!data["parent_id"]) {
                    // $('td', row).eq(2).css("background-color", "#FF9999");
                    // $('td', row).eq(3).css("background-color", "#FF9999");
                }
            }
        });
    }
    var popOverSettings = {
        placement: "left",
        container: "body",
        trigger: "hover",
        html: true,
        selector: ".table-image-cover", //Sepcify the selector here
        content: function () {
            return '<img src="' + $(this).data("img") + '" width="500"/>';
        }
    };

    $(document).popover(popOverSettings);

    $(document).on('click', '#hierarchy-profile-close', function () {
        $('#hierarchy-profile-preview').css('display', 'none');
    });

    initialize_employee_table("/refreshEmployeeList");

    $(document).on("change", "#status_data", function () {
        if ($(this).val() == "inactive") {
            $("#reason").show();
        } else {
            $("#reason").hide();
            $("#status_reason").val("");
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
            success: function (data) {
                prevProfiles.push(data); //data of current user considered as first profile in history
            }
        });
    }

    function getCurrentTab() {
        $.ajax({
            url: "/getCurrentTab",
            method: "get",
            success: function (data) {
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
        if (data.profile.status == "new_hired") {
            data.profile.status = "Newly Hired";
        } else {
            data.profile.status = ucword(data.profile.status);
        }
        $("#status_P").html(data.profile.status);
        $("#gender_P").html(data.profile.gender);
        $("#contact_P").html(data.profile.contact_number);
        $("#address_P").html(data.profile.address);
        $("#email_P").html(data.user.email);
        $("#hired_P").html(data.profile.hired_date);

        console.log(data.viewer);

        if (
            data.viewer == 1 ||
            data.viewer == 2 ||
            data.viewer == 3 ||
            showBenefits == 0
        ) {
            $("#sss_P").html(
                !!data.profile.benefits[0].id_number ?
                data.profile.benefits[0].id_number :
                "N/A"
            );
            $("#philhealth_P").html(
                !!data.profile.benefits[1].id_number ?
                data.profile.benefits[1].id_number :
                "N/A"
            );
            $("#pagibig_P").html(
                !!data.profile.benefits[2].id_number ?
                data.profile.benefits[2].id_number :
                "N/A"
            );
            $("#tin_P").html(
                !!data.profile.benefits[3].id_number ?
                data.profile.benefits[3].id_number :
                "N/A"
            );
            $("#profile-image-display").css(
                "background-image",
                "url(" + data.profile.image + ")"
            );
            $("#birth_P").html(data.profile.birthdate);
        } else {
            $("#sss_P").html("••••••");
            $("#philhealth_P").html("••••••");
            $("#pagibig_P").html("••••••");
            $("#tin_P").html("••••••");
            $("#birth_P").html("••••••");
            $("#profile-image-display").css(
                "background-image",
                "url(" + data.profile.image + ")"
            );
        }

        if (data.profile.image != null) {
            $("#profile-image-display").css(
                "background-image",
                "url(" + data.profile.image + ")"
            );
        } else {
            $("#profile-image-display").css(
                "background-image",
                "url(/images/nobody.jpg)"
            );
        }
    }

    $(document).on("click", ".view-employee", function () {
        let id = $(this).attr("id");
        $("#profile-edit-button").attr("data-id", id);
        $('#hierarchy-profile-preview').css('display', '');

        prevProfiles.push(id);
        showBenefits = prevProfiles.length;
        $.ajax({
            url: "/viewProfile",
            method: "get",
            dataType: "json",
            data: {
                id: id
            },
            success: function (data) {
                replaceProfile(data);

                initialize_employee_table("/updateEmployeeList/" + id);
                prevButton.prop("disabled", false);

            }
        });
    });

    $(document).on("click", "#prevProfile", function () {
        let id = prevProfiles[prevProfiles.length - 2];
        $("#profile-edit-button").attr("data-id", id);
        if (prevProfiles.length > 1) {
            $.ajax({
                url: "/viewProfile",
                method: "get",
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    showBenefits = prevProfiles.length - 2;
                    replaceProfile(data);
                    if (
                        prevProfiles.length - 2 == 0 &&
                        currentTab == "showAll"
                    ) {
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
            success: function (data) {
                showBenefits = 0;
                replaceProfile(data);
            }
        });
    }

    $(document).on("click", "#showAll", function () {
        resetPrevButton();
        backToProfile();

        initialize_employee_table("/refreshEmployeeList");
        currentTab = "showAll";
    });

    $(document).on("click", "#showChild", function () {
        resetPrevButton();
        backToProfile();

        initialize_employee_table("/childView");

        currentTab = "childView";
    });

    $(document).on("click", "#showTerminated", function () {
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
    $(document).on("click", "#employee-modal-cancel", function (e) {
        e.preventDefault();
        employee_form_reset();
    });

    //get group leaders on position change
    $(document).on("change", "#position", function () {
        var a_position = $(this).val(); //applicant positon a_position
        var u_position = $("#user-access-level").val(); //logged position
        var eid = $("#employee-id").val();
        fetch(a_position, u_position, eid);
    });

    //display input file image before upload on change
    $("#photo").change(function () {
        readURL(this);
        // console.log(this);
    });
    $(document).on("change", "#excel_file", function () {
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

    document.getElementById("photo").onchange = function (evt) {};
    //ajax request for CU action on from submission
    $(document).on("click", "#employee-form-submit", function (e) {
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
            success: function (result) {
                if (result.errors) {
                    $(".alert-danger").html("");
                    // console.log(result.errors);
                    var compact_req_msg = "false"; //compact required message
                    var unique_email_msg = "false";
                    var unique_name_msg = "false";
                    var email_error = "false";
                    var image_file = "false";
                    $.each(result.errors, function (key, value) {
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
                            if ($("#logged-position").val() == 1) {
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
                                $("#profile-image-display").css(
                                    "background-image",
                                    "url(" + result.info.image + ")"
                                );
                            } else {
                                $("#profile-image-display").css(
                                    "background-image",
                                    "url(/images/nobody.jpg)"
                                );
                            }
                            console.log(result.user);
                            $("#company_id_P").html(result.user.company_id);
                            if (result.info.status == "new_hired") {
                                result.info.status = "Newly Hired";
                            } else {
                                result.info.status = ucword(result.info.status);
                            }
                            $("#status_P").html(result.info.status);
                            $("#contact_P").html(result.info.contact_number);
                            $("#email_P").html(result.user.email);
                            $("#address_P").html(result.info.address);
                            $("#sss_P").html(result.benefit[0].id_number);
                            $("#philhealth_P").html(
                                result.benefit[1].id_number
                            );
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
    $(document).on("click", ".form-action-button", function () {
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

    $(document).on("click", ".add-position-button", function () {
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
                success: function (data) {
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
                error: function (data) {
                    console.log(data);
                }
            });
        }

        $.when(ajax1()).done(function (a1) {
            $("#position-modal-form").modal("show");
        });
    });

    $(document).on("click", "#position-modal-cancel", function () {
        $("#position-modal-form").modal("hide");
    });

    $(document).on("click", "#position-form-submit", function (event) {
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
            success: function (data) {
                $(".existing-position")
                    .DataTable()
                    .ajax.reload();
                swal("Done!", "New position successfully added.", "success");
                button.disabled = false;
                input.html("Save");
                $("#position-modal-form").modal("hide");
                $("#add-position-form")[0].reset();
            },
            error: function (data) {
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

    $("#nod_modal").on("hidden.bs.modal", function (e) {
        ir_list.destroy();
    });

    //OPEN MODAL for Incident Report
    $(document).on("click", ".add_nod", function (event) {
        event.preventDefault();
        $('.nav-tabs a[href="#ir_list"]').tab("show");
        ir_id = $(this).attr("id");
        $("#button_action").val("add");
        $("#ir_id").val(ir_id);
        $("#nod_modal").modal("show");
        $.ajax({
            url: "/get_ir",
            method: "get",
            data: {
                id: ir_id
            },
            dataType: "json",
            success: function (data) {
                //Incident Repots View List Datatable
                ir_list = $("#ir_table_list").DataTable({
                    processing: true,
                    columnDefs: [{
                        targets: "_all", // your case first column
                        className: "text-center"
                    }],
                    serverside: true,
                    data: data.data,
                    columns: [{
                            data: "description",
                            name: "description"
                        },
                        {
                            data: "date_filed",
                            name: "date_filed"
                        },
                        {
                            data: "filed_by",
                            render: function (data, type, full, meta) {
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
            error: function (data) {
                swal("Oh no!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html("SAVE CHANGES");
            }
        });
    }); //end for OPEN MODAL for Incident Report

    //ADD Incident Report
    $(document).on("click", "#add_IR", function (event) {
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
            data: {
                id: ir_id,
                description: description
            },
            success: function (data) {
                if (data == "Error") {
                    swal(
                        "Input Missing!",
                        "Please Enter Description.",
                        "error"
                    );
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
            error: function (data) {
                swal("Oh no!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html("Save");
            }
        });
    });

    //UPDATE EMPLOYEE STATUS
    $(document).on("click", "#submit_status", function (event) {
        event.preventDefault();

        var status_id = $("#status_id").val();
        var status_data = $("#status_data").val();
        var status_reason = $("#status_reason").val();
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
            data: {
                status_id: status_id,
                status_data: status_data,
                status_reason: status_reason
            },
            success: function (data) {
                $("#update_status_modal").modal("hide");
                refresh_employee_table();
                swal("Success!", "Status has been updated", "success");
                button.disabled = false;
                input.html("Confirm");
            },
            error: function (data) {
                swal("Oh no!", "Something went wrong, try again.", "error");
                // console.log(data)
                button.disabled = false;
                input.html("Confirm");
            }
        });
    });

    $(document).on("click", ".update_status", function (event) {
        event.preventDefault();
        var id = $(this).attr("id");
        $.ajax({
            method: "GET",
            url: "/get_status",
            dataType: "text",
            data: {
                id: id
            },
            success: function (value) {
                var data = JSON.parse(value);
                $("#update_status_modal").modal("show");
                $("#status_id").val(data[0].id);
                $("#status_data")
                    .val(data[0].status)
                    .trigger("change");
                $("#status_reason").val(data[0].status_reason);
                $("#employee_status_name").html(
                    data[0].firstname +
                    " " +
                    data[0].middlename +
                    " " +
                    data[0].lastname
                );
            },
            error: function (data) {
                swal("Oh no!", "Something went wrong, try again.", "error");
            }
        });
    });

    $(document).on("click", ".excel-action-button", function (e) {
        $("#excel-modal").modal("show");
        if ($(this).attr("data-action") == "import") {
            $("#action-export")[0].hidden = true;
            $("#import-employee-pbar-container")[0].hidden = true;
            $("#excel-modal-header").html("Import");
            $("#excel-file-label").html("Select Excel File.");
            $("#action-import")[0].hidden = false;
            $("#excel_file").val("");
            $("#excel-file-label")
                .removeClass("btn-info")
                .addClass("btn-secondary");
            $("#excel-modal-cancel")[0].hidden = false;
            $("#excel-form-submit")[0].disabled = false;
            $("#excel-form-submit")[0].hidden = false;
        } else {
            $("#action-import")[0].hidden = true;
            $("#excel-form-submit")[0].hidden = true;
            $("#excel-modal-header").html("Export");
            $("#import-employee-pbar-container")[0].hidden = true;
            $("#action-export")[0].hidden = false;
            $("#excel-modal-cancel")[0].hidden = false;
        }
    });
    let leavepagenotif = false;
    $(document).on("click", "#excel-form-submit", function (e) {
        e.preventDefault();
        $("#excel-form-submit")[0].disabled = true;
        var formData = new FormData($("#import-excel-form")[0]);
        leavepagenotif = true;
        window.onbeforeunload = function () {
            return leavepagenotif ?
                "If you leave this page you will lose your unsaved changes." :
                null;
        };
        $.ajax({
            url: "/excel/import/toarray",
            method: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (result) {
                result = JSON.parse(result);
                if (
                    result == "Excel Not Recognized." ||
                    result == "Template is outdated."
                ) {
                    leavepagenotif = false;
                    $("#excel-modal").modal("hide");
                    swal({
                        title: "Error",
                        html: '<div class="alert alert-info"><strong>' +
                            result +
                            "</strong></div>",
                        focusConfirm: false,
                        confirmButtonText: '<i class="fa fa-thumbs-up"></i> Noted!',
                        confirmButtonAriaLabel: "Thumbs up, great!"
                    });
                } else {
                    if (result.action == "Add") {
                        $("#excel-modal-header").html("Importing...");
                        $("#import-employee-pbar-container")[0].hidden = false;
                        // $("#import-employee-pbar-container").css("display", "block");
                        $("#import-employee-p-bar").css("width", 0);
                        $("#excel-form-submit")[0].hidden = true;
                        $("#excel-modal-cancel")[0].hidden = true;
                        $("#action-import")[0].hidden = true;
                        excelstore(1, result);
                    } else if (result.action == "Reassign") {
                        leavepagenotif = false;
                        importResultDisplay(result);
                    }
                }
            },
            error: function (req, status, error) {
                console.log(req);
            }
        });
    });

    let excelstore = function (i, obj) {
        console.log(row);

        //index,obj
        var index = i;
        if (i <= obj.arr.length - 1) {
            var row = obj.arr[i][0];
            $.ajax({
                url: "/excel/import/store/add",
                method: "POST",
                data: {
                    obj: row
                },
                success: function (result) {
                    result = JSON.parse(result);
                    var progress = (100 / (obj.arr.length - 1)) * index;
                    $("#import-employee-p-bar").css("width", progress + "%");
                    console.log(result.status);
                    if (obj.action == "Add") {
                        if (result.status == 0) {
                            obj.saved = obj.saved + 1;
                        } else if (result.status == 1) {
                            obj.duplicate = obj.duplicate + 1;
                        } else if (result.status == 2) {
                            if (obj.error == "") {
                                obj.error = result.eid;
                            } else {
                                obj.error = obj.error + "," + result.eid;
                            }
                        }
                    } else if (obj.action == "Reassign") {
                        importResultDisplay(obj);
                    }
                    excelstore(++i, obj);
                },
                error: function (request) {
                    console.log(request);
                }
            });
        } else {
            leavepagenotif = false;
            importResultDisplay(obj);
        }
    };

    let importResultDisplay = function (obj) {
        setTimeout(function () {
            $("#excel-modal").modal("hide");
        }, 3000);
        if (obj.outdated == true) {
            title = "<strong>Outdated Template</strong>";
            htmlcontent =
                '<div class="alert alert-info"> Please download new template.</div>';
        } else if (obj.outdated == false) {
            if (obj.action == "Add") {
                var err = 0;
                if (obj.error.length > 0) {
                    err = obj.error;
                } else {
                    err = 0;
                }
                title = "<strong>Add Employee Report</strong>";
                htmlcontent =
                    '<div class="alert alert-success"><strong>' +
                    obj.saved +
                    "</strong> record/s added.</div>";
                htmlcontent +=
                    '<div class="alert alert-info"><strong>' +
                    obj.duplicate +
                    "</strong> duplicate/s found.</div>";
                htmlcontent +=
                    '<div class="alert alert-warning">Error rows: ' +
                    err.toString() +
                    "</div>";
            } else if (obj.action == "Reassign") {
                title = "<strong>Reassign Employee Report</strong>";
                htmlcontent =
                    '<div class="alert alert-success"><strong>' +
                    obj.reassign +
                    "</strong> reassigned employee/s.</div>";
            }
        }
        swal({
            title: title,
            html: htmlcontent,
            focusConfirm: false,
            confirmButtonText: '<i class="fa fa-thumbs-up"></i> Noted!',
            confirmButtonAriaLabel: "Thumbs up, great!"
        });
        refresh_employee_table();
    };

    $(document).on("click", "#excel-modal-cancel", function () {
        $("#excel-modal").modal("hide");
    });

    $(document).on("click", "#import", function () {
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
            reader.onload = function (e) {
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
        return (str = str.toLowerCase().replace(/\b[a-z]/g, function (letter) {
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
            success: function (result) {
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
            data: {
                id: id
            },
            success: function (result) {
                console.log(result);
                $("#first_name").val(result.userinfo.firstname);
                $("#middle_name").val(result.userinfo.middlename);
                $("#last_name").val(result.userinfo.lastname);
                $("#address").val(result.userinfo.address);
                $("#birthdate").val(result.userinfo.birthdate);
                $(
                    '#gender option[value="' + result.userinfo.gender + '"]'
                ).prop("selected", true);
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
                $("#contract").val(result.user[0].contract);
                $("#hired_date").val(result.userinfo.hired_date);
                fetch(result.user[0].access_id, "", "");
                if (result.userinfo.image != null) {
                    $("#upload-image-display").attr(
                        "src",
                        result.userinfo.image
                    );
                } else {
                    $("#upload-image-display").attr(
                        "src",
                        "/images/nobody.jpg"
                    );
                }

                var designation = function () {
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

    document.addEventListener("DOMContentLoaded", function () {
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

        $(document).on("click", "#done", function (event) {
            event.preventDefault();
            $("#show_camera").attr("hidden", "");
        });

        $("#employee-form-modal").on("hidden.bs.modal", function (e) {
            video.pause();
            $("#show_camera").attr("hidden", "");
        });

        // The getUserMedia interface is used for handling camera input.
        // Some browsers need a prefix so here we're covering all the options
        $(document).on("click", "#start-camera", function () {
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
                navigator.getMedia({
                        video: true
                    },
                    // Success Callback
                    function (stream) {
                        // Create an object URL for the video stream and
                        // set it as src of our HTLM video element.
                        video.srcObject = stream;

                        // Play the video element to start the stream.
                        video.play();
                        video.onplay = function () {
                            showVideo();
                        };
                        $(document).on("click", "#done", function (event) {
                            event.preventDefault();
                            $("#show_camera").attr("hidden", "");
                            stream.getTracks().forEach(track => track.stop());
                        });

                        $("#employee-form-modal").on(
                            "hidden.bs.modal",
                            function (e) {
                                $("#show_camera").attr("hidden", "");
                                stream
                                    .getTracks()
                                    .forEach(track => track.stop());
                            }
                        );
                    },
                    // Error Callback
                    function (err) {
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

        start_camera.addEventListener("click", function (e) {
            e.preventDefault();

            // Start video playback manually.
            video.play();
            showVideo();
        });

        take_photo_btn.addEventListener("click", function (e) {
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

        delete_photo_btn.addEventListener("click", function (e) {
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
        img.onclick = function () {
            modal.style.display = "block";
            modalImg.src = this.src;
            captionText.innerHTML = this.alt;
        };

        // Get the <span> element that closes the modal
        let span = document.getElementsByClassName("close_pic")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        };
    });
}
