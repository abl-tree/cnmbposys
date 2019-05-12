<template>
  <div class="mini-calendar">
    <full-calendar :events="local.calendar.events" :config="config"></full-calendar>
  </div>
</template>
<style>
.mini-calendar {
  width: 100%;
  margin: 0 auto;
  font-size: 0.9em;
}

.mini-calendar .fc-toolbar {
  font-size: 0.9em;
}

.mini-calendar .fc-header-toolbar {
  padding-left: 20px;
  padding-right: 20px;
}

.mini-calendar .fc-toolbar h2 {
  font-size: 0.9em !important;
  white-space: normal !important;
}

/* click +2 more for popup */
.mini-calendar .fc-more-cell a {
  display: block;
  width: 85%;
  margin: 1px auto 0 auto;
  border-radius: 3px;
  background: grey;
  color: transparent;
  overflow: hidden;
  height: 4px;
}

.mini-calendar .fc-more-popover {
  width: 100px;
}

.mini-calendar .fc-view-month .fc-event,
.mini-calendar .fc-view-agendaWeek .fc-event,
.mini-calendar .fc-content {
  font-size: 0;
  overflow: hidden;
  height: 2px;
}

.mini-calendar .fc-view-agendaWeek .fc-event-vert {
  font-size: 0;
  overflow: hidden;
  width: 2px !important;
}

.mini-calendar .fc-agenda-axis {
  width: 20px !important;
  font-size: 0.7em;
}

.mini-calendar .fc-button-content {
  padding: 0;
}

.mini-calendar .fc-day-grid-container {
  height: 100% !important;
  overflow: hidden !important;
}
</style>
<script>
import tooltip from "tooltip-js";
import moment from "moment";
export default {
  props: ["userId"],
  data() {
    return {
      local: {
        // temp_id: 119,
        calendar: {
          events: []
        }
      },
      config: {
        eventRender(event, element) {
          if (event != null) {
            var etitle = event.title,
              start = moment(event.start._i.split(" ")[1], "HH:mm:ss").format(
                "hh:mm a"
              ),
              end = moment(event.end._i.split(" ")[1], "HH:mm:ss").format(
                "hh:mm a"
              );

            element.attr({
              "data-toggle": "tooltip",
              "data-placement": "top",
              title: etitle + " " + start + " to " + end
            });
          }
        },
        defaultView: "month",
        header: {
          left: "title",
          center: "",
          right: "today,prev,next"
        },
        views: {
          month: {
            titleFormat: "MMMM D"
          }
        },
        buttonText: {
          today: "T"
        },
        disableDragging: true,
        editable: false
      }
    };
  },
  mounted() {
    this.fetchAgentSched();
  },
  methods: {
    fetchAgentSched: function(id) {
      this.local.calendar.events = [];
      let pageurl = "/api/v1/schedules/agents/" + this.userId;
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          if (!this.isEmpty(res.meta.agent.calendar.events)) {
            this.local.calendar.events = res.meta.agent.calendar.events;
          }
        })
        .catch(err => console.log(err));
    }
  }
};
</script>
