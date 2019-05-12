<template>
  <div class="layers">
    <div class="layer w-100 p-20">
      <div class="container">
        <div class="row bdB">
          <div class="col-md-8">
            <div class="row pB-5 bdB">
              <div class="col">
                <div class="peers ai-sb fxw-nw">
                  <div class="peer mR-10">
                    <img
                      v-if="agent_widget.config.profile.image==null || agent_widget.config.profile.image==''"
                      class="bdrs-50p w-3r h-3r"
                      src="/images/nobody.jpg"
                    >
                    <img v-else class="bdrs-50p w-3r h-3r" :src="agent_widget.config.profile.image">
                  </div>
                  <div class="peer peer-greed">
                    <div class="container">
                      <div class="row peers m-0 p-0">
                        <div class="peer peer-greed">
                          <span>
                            <div
                              class="fw-500 c-blue-500 p-0 m0"
                            >{{ agent_widget.config.profile.full_name }}</div>
                            <span style="font-size:0.8em">{{ agent_widget.config.profile.email }}</span>
                            <span class="mX-5 bgc-grey" style="font-size:0.7em">&#9679;</span>
                            <span style="font-size:0.8em">{{ agent_widget.config.profile.position }}</span>
                          </span>
                        </div>
                        <div class="peer"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row pY-10 bdB">
              <div class="col-md-3">
                <div class="layer w-100 bdL bdR p-20">
                  <div class="layer w-100 text-center">
                    <small>Days under CNM</small>
                  </div>
                  <div class="layer w-100 text-center">
                    <h1>{{ agent_widget.config.stats.days }}</h1>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="layer w-100 bdL bdR p-20">
                  <div class="layer w-100 text-center">
                    <small>Total Work Schedule</small>
                  </div>
                  <div class="layer w-100 text-center">
                    <h1>{{ agent_widget.config.stats.work_days }}</h1>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="layer w-100 bdL bdR p-20">
                  <div class="layer w-100 text-center">
                    <small>Present Days</small>
                  </div>
                  <div class="layer w-100 text-center">
                    <h1>{{ agent_widget.config.stats.present_days }}</h1>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="layer w-100 bdL bdR p-20">
                  <div class="layer w-100 text-center">
                    <small>Leave Days</small>
                  </div>
                  <div class="layer w-100 text-center">
                    <h1>{{ agent_widget.config.stats.leaved_days }}</h1>
                  </div>
                </div>
              </div>
            </div>
            <div class="row pY-10 bdB">
              <div class="col-md-6 peers pR-30">
                <div class="peer mL-20 mR-15">
                  <button
                    class="btn btn-primary"
                    :disabled="agent_widget.config.button.start_work"
                    @click="startWork"
                  >START WORK</button>
                </div>
                <div class="peer peer-greed h-100 text-center" style="display:table">
                  <div style="display:table-cell;vertical-align:middle">
                    <h6>
                      <small class="c-grey-800">WORK DURATION:</small>
                    </h6>
                  </div>
                </div>
                <div class="peer">
                  <h3>3.5</h3>
                </div>
              </div>
              <div class="col-md-6 peers pR-30">
                <div class="peer mL-20 mR-15">
                  <button class="btn btn-primary">BREAK</button>
                </div>
                <div class="peer peer-greed h-100 text-center" style="display:table">
                  <div style="display:table-cell;vertical-align:middle">
                    <h6>
                      <small class="c-grey-800">BREAK DURATION:</small>
                    </h6>
                  </div>
                </div>
                <div class="peer">
                  <h3>0.5</h3>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <mini-calendar :user-id="userId"></mini-calendar>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Moment from "moment/moment";
import { extendMoment } from "moment-range";
const moment = extendMoment(Moment);

export default {
  props: ["userId"],
  mounted() {
    this.fetchStats();
    this.fetchTodaysSchedule();
  },
  data() {
    return {
      agent_widget: {
        config: {
          form: {
            schedule: {
              id: "",
              attendance: {
                id: "",
                action: "create",
                time_in: "",
                time_out: ""
              }
            }
          },
          button: {
            start_work: "true"
          },
          stats: {
            days: "",
            work_days: "",
            present_days: "",
            leaved_days: ""
          },
          profile: {
            id: "",
            full_name: "",
            email: "",
            image: "",
            date_hired: "",
            position: ""
          }
        },
        data: {
          agent_reports: []
        }
      }
    };
  },
  methods: {
    getStats: function() {
      pageurl = "/api/v1/schedules/reports/" + this.user_id;
    },
    fetchStats: function() {
      let startDate = moment("2018-01-01").format("YYYY-MM-DD"),
        endDate = moment().format("YYYY-MM-DD");
      let pageurl =
        "/api/v1/schedules/work/report?start=" +
        startDate +
        "&end=" +
        endDate +
        "&userid=" +
        this.userId;
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          //   console.log(res);
          this.agent_widget.config.profile.full_name =
            res.meta.agent_schedules[0].full_name;
          this.agent_widget.config.profile.image =
            res.meta.agent_schedules[0].info.image;
          this.agent_widget.config.profile.email =
            res.meta.agent_schedules[0].email;
          this.agent_widget.config.profile.id = res.meta.agent_schedules[0].id;
          this.agent_widget.config.profile.date_hired =
            res.meta.agent_schedules[0].info.hired_date;
          this.agent_widget.config.profile.position =
            res.meta.agent_schedules[0].access.name;
          var obj = [];
          res.meta.agent_schedules[0].schedule.forEach(function(v, i) {
            obj.push({
              info: {
                full_name: res.meta.agent_schedules[0].full_name,
                image: res.meta.agent_schedules[0].info.image,
                email: res.meta.agent_schedules[0].email,
                id: res.meta.agent_schedules[0].id,
                tl: res.meta.agent_schedules[0].team_leader,
                om: res.meta.agent_schedules[0].operations_manager
              },
              schedule: v
            });
          });
          this.agent_widget.data.agent_reports = obj;
          this.agent_widget.config.stats.days = moment().diff(
            moment(res.meta.agent_schedules[0].info.hired_date).format(
              "YYYY-MM-DD"
            ),
            "days"
          );
          this.agent_widget.config.stats.work_days = obj.filter(
            this.workSchedules
          ).length;
          this.agent_widget.config.stats.present_days = obj.filter(
            this.workedSchedules
          ).length;
          this.agent_widget.config.stats.leaved_days = obj.filter(
            this.leavedSchedules
          ).length;
        })
        .catch(err => {
          console.log(err);
        });
    },
    fetchTodaysSchedule: function() {
      let pageurl = "/api/v1/schedules/work/today";
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          //   console.log(res);
          let sched = res.meta.agent_schedules.filter(this.agentSchedule)[0];
          sched.schedule = sched.schedule[0];
          this.agent_widget.config.form.schedule.id = sched.schedule.id;

          //   console.log(sched);
          if (!this.isEmpty(sched.schedule.attendances)) {
            let latestAttendance =
              sched.schedule.attendances[sched.schedule.attendances.length - 1];
            if (
              latestAttendance.time_out == null &&
              latestAttendance.time_in != null
            ) {
              //timeout
              console.log("Enable timeout");
              this.agent_widget.config.button.start_work = false;
              this.agent_widget.config.form.schedule.attendance.action =
                "update";
              console.log(latestAttendance);
              this.agent_widget.config.form.schedule.attendance.id =
                latestAttendance.id;
              this.agent_widget.config.form.schedule.attendance.time_in =
                latestAttendance.time_in;
            } else if (
              latestAttendance.time_out != null &&
              latestAttendance.time_in != null
            ) {
              //enable store new attendance
              this.agent_widget.config.button.start_work = false;
              this.agent_widget.config.form.schedule.attendance.action =
                "create";
              console.log("Enable attendance insert");
            }
          } else {
            this.agent_widget.config.button.start_work = false;
            this.agent_widget.config.form.schedule.attendance.action = "create";
            console.log("Empty Attendance");
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    agentSchedule: function(index) {
      return index.id == this.userId;
    },
    workSchedules: function(index) {
      return index.schedule.title_id < 3;
    },
    workedSchedules: function(index) {
      return index.schedule.title_id < 3 && index.schedule.is_present == 1;
    },
    leavedSchedules: function(index) {
      return index.schedule.title_id > 2 && index.schedule.title_id < 9;
    },
    addAttendance: function() {
      let pageurl = "/api/v1/attendance/create";
      let schedule = this.agent_widget.config.form.schedule;
      fetch(pageurl, {
        method: "post",
        body: JSON.stringify({
          auth_id: this.userId,
          schedule_id: schedule.id,
          id: schedule.attendance.id,
          // time_in: moment().format("YYYY-MM-DD HH:mm:ss"),
          time_out: null
        }),
        headers: {
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          console.log(data);
          this.agent_widget.config.button.start_work = false;
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
      let schedule = this.agent_widget.config.form.schedule;
      let pageurl = "/api/v1/attendance/update/" + schedule.attendance.id;
      fetch(pageurl, {
        method: "post",
        body: JSON.stringify({
          auth_id: this.userId,
          schedule_id: schedule.id,
          id: schedule.attendance.id,
          time_in: schedule.attendance.time_in,
          time_out: moment().format("YYYY-MM-DD HH:mm:ss")
        }),
        headers: {
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          console.log(data);
          this.agent_widget.config.button.start_work = false;
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
    startWork: function() {
      this.agent_widget.config.button.start_work = true;
      if (
        this.agent_widget.config.form.schedule.attendance.action == "create"
      ) {
        this.addAttendance();
      } else if (
        this.agent_widget.config.form.schedule.attendance.action == "update"
      ) {
        this.updateAttendance();
      }
    }
  }
};
</script>
