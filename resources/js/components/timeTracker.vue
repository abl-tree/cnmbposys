<template>
  <div class="layers">
    <div class="layer bd p-20 w-100">
      <div class="row">
        <div class="col-md-3 text-center">
          <div>
            <small>{{ timeTracker.today.schedule.label }}</small>
          </div>
          <div>
            <button
              class="btn form-control"
              :class="timeTracker.tracker.button.class"
              :disabled="timeTracker.tracker.button.disabled"
              @click="clickTracker"
            >{{ timeTracker.tracker.button.label }}</button>
          </div>
        </div>
        <div class="col">
          <div class="row">
            <div class="col text-center">
              <div class="fsz-xs">
                <span
                  class="badge bgc-green-400 w-100 c-white"
                >{{ timeTracker.form.schedule.attendance.time_in }}</span>
              </div>
              <!-- <div class="fsz-xs">00:00:00 to 00:00:00</div>
              <div class="fsz-xs">00:00:00 to 00:00:00</div>
              <div class="fsz-xs">00:00:00 to 00:00:00</div>-->
            </div>
            <div class="col text-center">
              <div class="fsz-xs">Rendered Hours</div>
              <div class="fsz-lg">{{ timeTracker.tracker.rendered_hours.value }}</div>
            </div>
            <div class="col text-center">
              <div class="fsz-xs">Remaining Break</div>
              <div class="fsz-lg">00:00:00</div>
            </div>
            <div class="col text-center">
              <div class="fsz-xs">Break Times</div>
              <div class="fsz-lg">3</div>
            </div>
            <div class="col text-right">
              <div class="fsz-lg fw-300">{{ timeTracker.today.date }}</div>
              <div>{{ timeTracker.today.time }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import moment from "moment";
export default {
  props: ["userId"],
  data() {
    return {
      timeTracker: {
        today: {
          date: null,
          time: null,
          schedule: {
            id: null,
            label: "No Schedule"
          }
        },
        tracker: {
          button: {
            label: "Start Work",
            class: "btn-danger",
            disabled: true
          },
          rendered_hours: {
            tick: false,
            value: "00:00:00"
          }
        },
        form: {
          schedule: {
            id: null,
            rendered_hours: {
              time: null,
              seconds: null
            },
            break: {
              remaining: {
                times: null,
                time: null
              }
            },
            attendance: {
              id: null,
              time_in: null,
              time_out: null,
              action: null
            }
          }
        }
      }
    };
  },
  mounted() {
    this.fetchTodaysSchedule();
  },
  created() {
    this.timeTracker.today.time = moment().format("LTS");
    this.timeTracker.today.date = moment().format("MMM Do YYYY");
    setInterval(() => {
      this.updateTime();
      if (this.timeTracker.tracker.rendered_hours.tick == true) {
        this.updateRenderedHours();
      }
    }, 1 * 1000);
  },
  methods: {
    updateTime: function() {
      this.timeTracker.today.time = moment().format("LTS");
    },
    fetchTodaysSchedule: function() {
      let pageurl = "/api/v1/schedules/work/today";
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          //   console.log(res);
          let sched = res.meta.agent_schedules.filter(this.agentSchedule)[0];
          sched.schedule = sched.schedule[0];
          this.timeTracker.today.schedule.label =
            this.calendarFormat(sched.schedule.start_event) +
            " - " +
            this.calendarFormat(sched.schedule.end_event);
          this.timeTracker.form.schedule.id = sched.schedule.id;
          let latestAttendance =
            sched.schedule.attendances[sched.schedule.attendances.length - 1];
          if (sched.schedule.title_id < 3) {
            //   console.log(sched);
            if (!this.isEmpty(sched.schedule.attendances)) {
              if (
                latestAttendance.time_out == null &&
                latestAttendance.time_in != null
              ) {
                //timeout
                console.log("Enable timeout");
                this.timeTracker.tracker.button.disabled = false;
                this.timeTracker.tracker.rendered_hours.tick = true;
                this.timeTracker.form.schedule.attendance.action = "update";
                console.log(latestAttendance);
                this.timeTracker.form.schedule.attendance.id =
                  latestAttendance.id;
                this.timeTracker.form.schedule.attendance.time_in =
                  latestAttendance.time_in;
                this.updateRenderedHours();
                if (sched.schedule.break.remaining > 0) {
                  this.timeTracker.tracker.button.label = "Take a break";
                  this.timeTracker.tracker.button.class = "btn-secondary";
                } else {
                  this.timeTracker.tracker.button.label = "End Work";
                  this.timeTracker.tracker.button.class = "btn-dark";
                }
              } else if (
                latestAttendance.time_out != null &&
                latestAttendance.time_in != null
              ) {
                //enable store new attendance
                this.timeTracker.tracker.button.disabled = false;
                this.timeTracker.tracker.rendered_hours.tick = false;
                this.timeTracker.tracker.button.label = "Start Work";
                this.timeTracker.tracker.button.class = "btn-danger";
                this.timeTracker.form.schedule.attendance.action = "create";
                console.log("Enable attendance insert");

                // if (sched.schedule.break.remaining > 0) {
                //   this.timeTracker.tracker.button.disabled = false;
                // } else {
                //   this.timeTracker.tracker.button.disabled = true;
                // }
              }
            } else {
              this.timeTracker.tracker.button.disabled = false;
              this.timeTracker.tracker.rendered_hours.tick = false;
              this.timeTracker.tracker.button.label = "Start Work";
              this.timeTracker.tracker.button.class = "btn-danger";
              this.timeTracker.form.schedule.attendance.action = "create";
              console.log("Empty Attendance");
            }
          } else {
            this.timeTracker.today.schedule.label = "No Schedule";
            this.timeTracker.tracker.button.disabled = true;
            this.timeTracker.tracker.button.class = "btn-danger";
            this.timeTracker.tracker.button.label = "Start Work";
          }
          this.broadcastListerner();
        })
        .catch(err => {
          console.log(err);
        });
    },
    addAttendance: function() {
      let pageurl = "/api/v1/attendance/create";
      let schedule = this.timeTracker.form.schedule;
      console.log({
        auth_id: this.userId,
        user_id: this.userId,
        schedule_id: schedule.id,
        // id: schedule.attendance.id,
        time_in: moment().format("YYYY-MM-DD HH:mm:ss")
        // time_out: null
      });
      fetch(pageurl, {
        method: "post",
        body: JSON.stringify({
          auth_id: this.userId,
          user_id: this.userId,
          schedule_id: schedule.id,
          id: schedule.attendance.id,
          time_in: moment().format("YYYY-MM-DD HH:mm:ss"),
          time_out: null
        }),
        headers: {
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          console.log(data);
          this.fetchTodaysSchedule();
          if (data.code == 500) {
            alert(
              "Something's wrong, the system can't process your request. Please check connection or call for IT support."
            );
          } else {
            console.log(data);
          }
        })
        .catch(err => console.log(err));
    },
    updateAttendance: function() {
      let schedule = this.timeTracker.form.schedule;
      let pageurl = "/api/v1/attendance/update/" + schedule.attendance.id;
      console.log({
        auth_id: this.userId,
        user_id: this.userId,
        schedule_id: schedule.id,
        time_out: moment().format("YYYY-MM-DD HH:mm:ss"),
        time_in: moment(schedule.attendance.time_in).format(
          "YYYY-MM-DD HH:mm:ss"
        )
      });
      fetch(pageurl, {
        method: "post",
        body: JSON.stringify({
          auth_id: this.userId,
          user_id: this.userId,
          schedule_id: schedule.id,
          time_out: moment().format("YYYY-MM-DD HH:mm:ss"),
          time_in: moment(schedule.attendance.time_in).format(
            "YYYY-MM-DD HH:mm:ss"
          )
        }),
        headers: {
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          console.log(data);
          this.fetchTodaysSchedule();
          if (data.code == 500) {
            alert(
              "Something's wrong, the system can't process your request. Please check connection or call for IT support."
            );
          } else {
            console.log(data);
          }
        })
        .catch(err => console.log(err));
    },
    clickTracker: function() {
      this.timeTracker.tracker.button.disabled = true;
      if (this.timeTracker.form.schedule.attendance.action == "create") {
        this.addAttendance();
      } else if (this.timeTracker.form.schedule.attendance.action == "update") {
        this.updateAttendance();
      }
    },
    agentSchedule: function(index) {
      return index.id == this.userId;
    },
    updateRenderedHours: function() {
      let diff = moment().diff(
        moment(this.timeTracker.form.schedule.attendance.time_in),
        "seconds"
      );

      this.timeTracker.tracker.rendered_hours.value = moment
        .utc(moment.duration(diff, "seconds").asMilliseconds())
        .format("HH:mm:ss");

      console.log(diff);
    },
    broadcastListerner: function() {
      Echo.private("workingAgent." + this.timeTracker.form.schedule.id).listen(
        "StartWork",
        e => {
          console.log(e);
        }
      );
    }
  }
};
</script>
