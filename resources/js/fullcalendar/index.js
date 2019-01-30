import * as $ from "jquery";
import "fullcalendar/dist/fullcalendar.min.js";
import "fullcalendar/dist/fullcalendar.min.css";

export default (function () {
        const date = new Date();
        const d = date.getDate();
        const m = date.getMonth();
        const y = date.getFullYear();

        // fetchAgentList();

        var events = [{
                title: "All Day Event",
                start: new Date(y, m, 1),
                desc: "Meetings",
                bullet: "success"
            },
            {
                title: "Long Event",
                start: new Date(y, m, d - 5),
                end: new Date(y, m, d - 2),
                desc: "Hangouts",
                bullet: "success"
            },
            {
                title: "Repeating Event",
                start: new Date(y, m, d - 3, 16, 0),
                allDay: false,
                desc: "Product Checkup",
                bullet: "warning"
            },
            {
                title: "Birthday Party",
                start: new Date(y, m, d + 1, 19, 0),
                end: new Date(y, m, d + 1, 22, 30),
                color: 'yellow'
            },
            {
                title: "Birthday Party",
                start: new Date(y, m, d + 1, 19, 0),
                end: new Date(y, m, d + 1, 22, 30),
                color: 'yellow'
            },
            {
                title: "Click for Google",
                start: new Date(y, m, 28),
                end: new Date(y, m, 29),
                url: "http ://google.com/",
                desc: "Google",
                bullet: "success"
            }
        ];

        $("#full-calendar").fullCalendar({
            events,
            handleWindowResize: true,
            height: 800,
            editable: true,
            themeSystem: "standard",
            header: {
                left: "title",
                right: "today,listMonth prev,next"
            },
            defaultView: 'listMonth'
        });

        function fetchAgentList() {
            const limit = 10;
            const column = 'email';
            const order = 'asc';
            $.ajax({
                url: "/api/agents?limit=" + limit + "&sort=" + column + "&order=" + order,
                method: "get",
                dataType: "json",
            }).done(function (data) {
                createAgentList(data.meta.agents)
            }).fail(function (error) {
                console.log(error)
            });
        }

        function createAgentList(datum) {
            var data = datum;
            var path = window.location.pathname;
            var user_access_id = $('#logged-position').val();

            if (path == '/schedule' && (user_access_id > 11 && user_access_id < 15)) {
                var list = '';
                $.each(data, function (k, v) {
                    if (k == 0) {
                        setCalendarHeader(v)
                    }
                    list += agentListTemplate(v);
                })
                $('#agent-lists').html(list);
            }
        }

        function agentListTemplate(datum) {
            var data = datum;
            console.log(data);
            return '<div id="agent-' + data.uid + '" class="email-list-item peers fxw-nw p-20 bdB bgcH-grey-100 cur-p">' +
                '<div class="peer mR-10">' +
                '<div class="checkbox checkbox-circle checkbox-info peers ai-c">' +
                '<input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">' +
                '<label for="inputCall1" class=" peers peer-greed js-sb ai-c"></label>' +
                '</div>' +
                '</div>' +
                '<div class="peer peer-greed ov-h">' +
                '<div class="peers ai-c">' +
                '<div class="peer peer-greed">' +
                '<h6>' + data.full_name + '</h6>' +
                '</div>' +
                '<div class="peer">' +
                '<small class="badge badge-pill badge-danger">' + data.company_id + '</small>' +
                '</div>' +
                '</div>' +
                // '<h5 class="fsz-def tt-c c-grey-900">title goes here</h5>'+
                '<span class="whs-nw w-100 ov-h tov-e d-b">' + data.email + '</span>' +
                '</div>' +
                '</div>';
        }

        function setCalendarHeader(datum) {
            var data = datum;
            var prefix = '#calendar-header-';
            var image = "images/nobody.jpg";
            if (data.info.image != null) {
                image = data.info.image;
            }
            $(prefix + 'image').prop('src', image);
            $(prefix + 'fullname').html(data.full_name);
            $(prefix + 'email').html(data.email);
            $(prefix + 'tl').html(data.team_leader);
            $(prefix + 'om').html(data.operations_manager);
            $('.email-list-item').removeClass('bgc-red-100');
            $('#agent-' + data.uid).addClass('bgc-red-100');
        }

        function setCalendarSchedule(datum) {
            var data = datum;
        }
    }

)();
