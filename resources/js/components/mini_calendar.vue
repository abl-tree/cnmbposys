<template>
  <div class="mini-calendar">
    <full-calendar
      :events="[
        {title:'Work',color:'red',start:'2019/04/10 00:00:00',end:'2019/04/10 10:00:00',tip:'Work'},
        {title:'Work',color:'red',start:'2019/04/12 00:00:00',end:'2019/04/12 10:00:00'},
        {title:'Work',color:'red',start:'2019/04/13 00:00:00',end:'2019/04/13 10:00:00'},
        {title:'Work',color:'red',start:'2019/04/14 00:00:00',end:'2019/04/14 10:00:00'},
        {title:'Vacation Leave',color:'yellow',start:'2019/04/15 00:00:00',end:'2019/04/23 10:00:00'},
    ]"
      :config="config"
    ></full-calendar>
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
  padding-left: 5px;
  padding-right: 5px;
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
export default {
  props: ["userId"],
  data() {
    return {
      config: {
        defaultView: "month",
        header: {
          left: "title",
          center: "",
          right: "today,prev,next"
        },
        views: {
          month: {
            titleFormat: "MMM YYYY"
          }
        },
        buttonText: {
          today: "T"
        },
        eventMouseover: event => {
          alert(event.title);
        }
      }
    };
  },
  mounted() {},
  methods: {}
};
</script>
