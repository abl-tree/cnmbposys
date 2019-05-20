<template>
  <div class="container-fluid">
    <div class="w-100 mB-10">
      <div class="bd bgc-white">
        <!-- {{-- <agent-widget v-bind:user-id="{{ json_encode($pageOnload->id) }}"></agent-widget> --}} -->

        <div class="layers">
          <div class="layer bd p-0 w-100">
            <!-- <div class="row bdB">
              <div class="col">
                <div class="fsz-sm"></div>
              </div>
            </div>-->
            <div class="row pR-0">
              <div class="col-md-5 align-self-center text-center pL-30">
                <div class="mB-10 mT-40">
                  <button
                    class="btn bdrs-50p p-20 fsz-lg lh-0"
                    :class="timeTracker.tracker.button.class"
                    :disabled="timeTracker.tracker.button.disabled"
                    @click="clickTracker()"
                  >
                    <i :class="timeTracker.tracker.button.label"></i>
                  </button>
                </div>
                <div
                  class="fsz-md"
                >{{ !timeTracker.buttontrigger? timeTracker.tracker.rendered_hours.value: timeTracker.tracker.timer.value }}</div>
                <span class="fsz-xs">{{ timeTracker.today.schedule.label }}</span>
              </div>

              <div class="col-md-3">
                <div class="row pT-10">
                  <div class="col-2 text-left">
                    <span class="fw-900 fsz-xs">LOGS</span>
                  </div>
                  <div class="col text-right">
                    <span class="pX-5 fsz-xs" data-toggle="tooltip" title="Billed Hours">
                      <small class="fw-600">BH:</small>
                      <span
                        class="c-green-300 fsz-xs"
                      >{{ !isEmpty(trackerGraph.processed.data.filtered.schedules)?getDurationBySeconds(trackerGraph.processed.data.filtered.schedules[0].rendered_hours.billable.second):'00:00:00' }}</span>
                    </span>
                  </div>
                </div>
                <template v-if="!isEmpty(trackerGraph.processed.data.filtered.schedules)">
                  <div
                    v-for="(datum,index) in trackerGraph.processed.data.filtered.schedules[0].attendances.slice().reverse()"
                    :key="datum.id"
                    class="row pY-5"
                    v-if="index<4"
                  >
                    <div class="col-8 text-left fsz-xs p-0">
                      <span class="fsz-xs">
                        <i
                          class="ti-calendar cur-p c-grey-500 mR-5"
                          data-toggle="tooltip"
                          :title="datum.time_in+' to '+datum.time_out"
                        ></i>
                        {{ dtFormat(datum.time_in,'LT') +" - "}}{{ datum.time_out?dtFormat(datum.time_out,'LT'):'00:00:00' }}
                      </span>
                    </div>
                    <div class="col text-right p-0">
                      <span class="fsz-xs">
                        <i class="ti-alarm-clock cur-p c-grey-500 mR-5"></i>
                        {{ getDurationBySeconds(datum.rendered_time) }}
                      </span>
                    </div>
                  </div>
                </template>
                <template v-else>
                  <div v-for="(datum,index) in 4" :key="datum.id" class="row pY-5">
                    <div class="col-8 text-left fsz-xs p-0">
                      <span class="fsz-xs">
                        <i class="ti-calendar cur-p c-grey-500 mR-5" data-toggle="tooltip"></i>
                        00:00 AM - 00:00 AM
                      </span>
                    </div>
                    <div class="col text-right p-0">
                      <span class="fsz-xs">
                        <i class="ti-alarm-clock cur-p c-grey-500 mR-5"></i>
                        00:00:00
                      </span>
                    </div>
                  </div>
                </template>
              </div>
              <div class="col-md-3">
                <div class="row pT-10">
                  <div class="col-2 text-left">
                    <span class="fw-900 fsz-xs">BREAK</span>
                  </div>
                </div>
                <div class="row pY-5">
                  <div class="col">
                    <div
                      class="fsz-lg"
                      data-toggle="tooltip"
                      title="Remaining Times"
                      style="font-weight:lighter"
                    >
                      Remaining Times
                      <span
                        class="fsz-lg mL-5"
                        style="font-weight:lighter"
                      >{{ !isEmpty(trackerGraph.processed.data.filtered.schedules)?trackerGraph.processed.data.filtered.schedules[0].break!=null? trackerGraph.processed.data.filtered.schedules[0].break.remaining:'0':'0' }}</span>
                    </div>
                  </div>
                </div>
                <div class="row pY-5">
                  <div class="col">
                    <div class="fsz-xs" style="font-weight:lighter">CONSUMED</div>
                    <div
                      class="fsz-md text-center"
                      style="font-weight:lighter"
                    >{{ !isEmpty(trackerGraph.processed.data.filtered.schedules)?trackerGraph.processed.data.filtered.schedules[0].break!==null?trackerGraph.processed.data.filtered.schedules[0].break.total:'00:00:00':'00:00:00' }}</div>
                  </div>
                  <div class="col">
                    <div class="fsz-xs" style="font-weight:lighter">REMAINING</div>
                    <div
                      class="fsz-md text-center"
                      style="font-weight:lighter"
                    >{{ !isEmpty(trackerGraph.processed.data.filtered.schedules)?trackerGraph.processed.data.filtered.schedules[0].break!==null? getRemainingBreakDuration(trackerGraph.processed.data.filtered.schedules[0].break.second): '00:00:00':'00:00:00' }}</div>
                  </div>
                </div>
              </div>
              <div class="col text-right align-self-center bgc-red-500">
                <div class="fsz-lg fw-300 c-white">{{ timeTracker.today.date }}</div>
                <div class="c-white">{{ timeTracker.today.time }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <hr>
    <div class="w-100">
      <div class="bd bgc-white">
        <div class="layer w-100 pX-20 pY-30 pB-20">
          <!-- filter -->
          <div class="layer pB-20 w-100">
            <div class="row">
              <div class="col">
                <h6>Work Report</h6>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="input-group">
                  <div class="input-group-prepend mR-5">
                    <date-time-picker
                      v-if="trackerGraph.config.filter.date.option==1"
                      class="s-modal"
                      :style="'width:300px'"
                      v-model="trackerGraph.config.filter.date.value"
                      range-mode
                      :no-label="true"
                      overlay-background
                      color="red"
                      format="YYYY-MM-DD"
                      formatted="ddd D MMM YYYY"
                      @input="fetchAgentReports"
                    />
                    <select
                      v-else-if="trackerGraph.config.filter.date.option==2"
                      class="p-10"
                      style="width:300px;border-radius:5px;border:1px solid #ccc"
                    >
                      <option>Cutoff List..</option>
                      <option value="1">2019-10-05 to 2019-11-05</option>
                    </select>
                  </div>
                  <div class="input-group-append">
                    <select
                      class="p-10"
                      style="border-radius:5px;border:1px solid #ccc"
                      v-model="trackerGraph.config.filter.date.option"
                    >
                      <option value="1">Range</option>
                      <option value="2" disabled>Cutoff</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6 text-right">
                <span class="pX-10 bdR fw-600 fsz-lg" data-toggle="tooltip" title="Total Work Days">
                  {{ trackerGraph.processed.data.filtered.totalworkdays}}
                  <span
                    class="fw-300 fsz-lg c-grey-500"
                  >DAY/S</span>
                </span>

                <span
                  class="pX-10 c-green-300 fw-600 fsz-lg cur-p"
                  data-toggle="tooltip"
                  title="Total Billable in Days:Hours:Minute:Seconds"
                >{{ trackerGraph.processed.data.filtered.overallbillable }}</span>
                <span
                  class="p-15 fw-600 fsz-lg"
                  :class="trackerGraph.config.chart.show? 'c-grey-700':'c-grey-500'"
                  data-toggle="tooltip"
                  title="Toggle Graph"
                  @click="trackerGraph.config.chart.show = !trackerGraph.config.chart.show"
                >
                  <i class="ti-bar-chart"></i>
                </span>
              </div>
            </div>
          </div>
          <!-- chart -->
          <highchart v-show="trackerGraph.config.chart.show" :options="trackerGraph.chartOptions"/>
          <!-- reports table -->
          <div class="layer pB-20 w-100">
            <div
              v-for="datum in trackerGraph.processed.data.filtered.schedules"
              :key="datum.id"
              v-if="datum.is_present==1"
              class="row"
            >
              <div class="col">
                <div class="custom-block w-100">
                  <div class="block-header p-10 w-100 bd fsz-xs" style="background-color:#EFF4F7">
                    <div class="row">
                      <div
                        class="col"
                      >{{ dtFormat(datum.start_event,"MMM Do YY HH:mm")+" - "+ dtFormat(datum.end_event,"MMM Do YY HH:mm") }}</div>
                      <div class="col text-right">
                        <span class="pX-5 bdR" data-toggle="tooltip" title="Break Duration">
                          <small class="fw-900">BD:</small>
                          {{ typeof datum.break!=='undefined'?getDurationBySeconds(datum.break.second):'00:00:00' }}
                        </span>
                        <span class="pX-5 bdR" data-toggle="tooltip" title="Rendered Hours">
                          <small class="fw-900">RH:</small>
                          {{ getWorkDuration(datum.time_in,datum.time_out) }}
                        </span>
                        <span class="pX-5" data-toggle="tooltip" title="Billed Hours">
                          <small class="fw-900">BH:</small>
                          <span
                            class="c-green-300"
                          >{{ getDurationBySeconds(datum.rendered_hours.billable.second) }}</span>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="block-body w-100 bd">
                    <div
                      v-for="datum1 in datum.attendances.slice().reverse()"
                      :key="datum1.id"
                      class="w-100 p-15 bd"
                    >
                      <div class="row">
                        <div class="col-md-6">
                          <div class="w-100 text-center">
                            <span
                              class="mR-5"
                            >{{ dtFormat(datum1.time_in,'LT') +" - "+dtFormat(datum1.time_out,'LT') }}</span>
                            <span
                              class="ti-calendar cur-p c-grey-500"
                              data-toggle="tooltip"
                              :title="datum1.time_in+' to '+datum1.time_out"
                            ></span>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="w-100 text-center">
                            <span class="mR-5">{{ getDurationBySeconds(datum1.rendered_time) }}</span>
                            <span class="ti-alarm-clock cur-p c-grey-500"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Highcharts from "highcharts";
import exportingInit from "highcharts/modules/exporting";
import { Chart } from "highcharts-vue";
exportingInit(Highcharts);
import moment from "moment";

export default {
  props: ["userId"],
  components: {
    highchart: Chart
  },
  data() {
    return {
      timeTracker: {
        buttontrigger: false,
        user: {
          id: this.userId
        },
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
            label: "ti-control-play",
            class: "btn-danger",
            disabled: true
          },
          rendered_hours: {
            tick: false,
            value: "00:00:00"
          },
          timer: {
            interval: null,
            time: 0,
            value: null
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
      },
      trackerGraph: {
        config: {
          chart: {
            show: true
          },
          filter: {
            date: {
              option: 1,
              value: {
                start: moment()
                  .startOf("month")
                  .format("YYYY-MM-DD"),
                end: moment()
                  .endOf("month")
                  .format("YYYY-MM-DD")
              }
            }
          }
        },
        processed: {
          data: {
            schedules: {},
            filtered: {
              overallbillable: 0,
              totalworkdays: 0,
              schedules: {}
            }
          }
        },
        data: {
          reports: {}
        },
        chartOptions: {
          chart: {
            type: "column"
          },
          title: {
            text: "",
            align: "left"
          },
          xAxis: {
            categories: [],
            stackLabels: {
              enabled: true
            }
          },
          yAxis: {
            title: {
              text: "Total Time Spent"
            }
          },

          plotOptions: {
            column: {
              stacking: "normal"
            }
          },
          series: [
            {
              data: [],
              name: "None-billable",
              color: "#8cc34c",
              opacity: 0.8,
              stack: 0 // sample data
            },
            {
              data: [],
              name: "Billable",
              color: "#8cc34c", // sample data
              stack: 0
            },
            {
              data: [],
              name: "None-billable(OT)",
              color: "#ffbc75",
              opacity: 0.8,
              stack: 1 // sample data
            },
            {
              data: [],
              name: "Billable(OT)",
              color: "#ffbc75", // sample data
              stack: 1
            }
          ]
        }
      }
    };
  },
  mounted() {
    // this.broadcastListerner();
  },
  created() {
    this.fetchTodaysSchedule();
    // this.fetchAgentReports();
    this.timeTracker.today.time = moment().format("LTS");
    this.timeTracker.today.date = moment().format("MMM Do YYYY");
    // this.workTimer();

    setInterval(() => {
      this.updateTime();
      if (this.timeTracker.tracker.rendered_hours.tick == true) {
        this.updateRenderedHours();
      }
    }, 1 * 1000);

    //test timer
  },
  methods: {
    broadcastListerner: function() {
      Echo.private("workingAgent." + this.userId).listen("StartWork", e => {
        // console.log(e);
      });
    },
    fetchAgentReports: function() {
      let startDate = this.trackerGraph.config.filter.date.value.start;
      let endDate = this.trackerGraph.config.filter.date.value.end;
      let pageurl =
        "/api/v1/schedules/work/report?userid=" +
        this.userId +
        "&start=" +
        startDate +
        "&end=" +
        endDate;
      // this.chartOptions.title.text = "Work Graph("+moment(startDate).format('MMM Do YY')+" to "+moment(startDate).format('MMM Do YY')+")";
      // console.log(pageurl);
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          this.trackerGraph.data.reports = res.meta.agent_schedules[0];
          // console.log("mao ni");
          // console.log(res.meta.agent_schedules[0]);
          this.extractDataReports(res.meta.agent_schedules[0]);
        })
        .catch(err => console.log(err));
    },
    extractDataReports: function(obj) {
      // filter work schedule
      var schedules = [
        ...new Set([].concat(...obj.schedule.map(a => a)))
      ].filter(this.getWorkSchedules);
      var asc_schedules = this.objSort(schedules, "asc", function(a, b) {
        return {
          a: a.start_event,
          b: b.start_event
        };
      });
      var tmp = asc_schedules;
      let dates = [],
        billable = [],
        nonbillable = [],
        otbillable = [],
        otnonbillable = [],
        overallbillable = 0;
      tmp.forEach(
        function(v, i) {
          dates.push(
            moment(v.start_event).format("MMM Do") +
              " - " +
              moment(v.end_event).format("MMM Do")
          );
          billable.push(
            v.is_present == 1 ? v.rendered_hours.billable.second / 60 / 60 : 0
          );
          nonbillable.push(
            v.is_present == 1
              ? v.rendered_hours.nonbillable.second / 60 / 60
              : 0
          );
          otbillable.push(
            v.is_present == 1 ? v.overtime.billable.second / 60 / 60 : 0
          );
          otnonbillable.push(
            v.is_present == 1 ? v.overtime.nonbillable.second / 60 / 60 : 0
          );
          overallbillable += v.rendered_hours.billable.second;
        }.bind(this)
      );
      this.trackerGraph.chartOptions.xAxis.categories = dates;
      this.trackerGraph.chartOptions.series[0].data = nonbillable;
      this.trackerGraph.chartOptions.series[1].data = billable;
      this.trackerGraph.chartOptions.series[2].data = otnonbillable;
      this.trackerGraph.chartOptions.series[3].data = otbillable;

      tmp = this.objSort(tmp, "desc", function(a, b) {
        return {
          a: a.start_event,
          b: b.start_event
        };
      });
      tmp = tmp.filter(function(index) {
        return moment(index.start_event, "YYYY-MM-DD HH:mm:ss").isBefore(
          moment(),
          "YYYY-MM-DD HH:mm:ss"
        );
      });

      // console.log(tmp);
      // console.log(tmp[1].attendances[tmp[1].attendances.length - 1]);
      // console.log("PROGRAMMER: NAKALOGOUT BA SYA TUNG NIAGI?");
      // console.log("Sole: " + this.checkPrevUndismissedAttendance(tmp[1]));
      // if (this.checkPrevUndismissedAttendance(tmp[1])) {
      //   while (!confirm("End?")) {
      //     // console.log("sawakas");
      //   }
      // }
      this.trackerGraph.processed.data.filtered.schedules = tmp;
      this.trackerGraph.processed.data.filtered.totalworkdays = tmp.length;
      this.trackerGraph.processed.data.filtered.overallbillable = this.getDHMS(
        overallbillable
      );
      // console.log(dates);
      // console.log(nonbillable);
      // console.log(billable);
      // console.log(otnonbillable);
      // console.log(otbillable);
    },
    getWorkSchedules: function(index) {
      return index.title_id < 3;
    },
    getWorkDuration: function(time_in, time_out) {
      var t1 = moment(time_in);
      var t2 = moment(time_out);
      return moment(moment(t2).diff(t1))
        .utcOffset(0)
        .format("HH:mm:ss");
    },
    getDurationBySeconds: function(second) {
      return moment
        .utc(moment.duration(second, "seconds").asMilliseconds())
        .format("HH:mm:ss");
    },
    updateTime: function() {
      this.timeTracker.today.time = moment().format("LTS");
    },
    fetchTodaysSchedule: function() {
      // console.log("START");

      let startDate = moment()
        .subtract(1, "days")
        .format("YYYY-MM-DD");
      let endDate = moment()
        .add(1, "days")
        .format("YYYY-MM-DD");
      let pageurl =
        "/api/v1/schedules/work/report?userid=" +
        this.userId +
        "&start=" +
        startDate +
        "&end=" +
        endDate;
      console.log(pageurl);
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          console.log(res.meta.agent_schedules[0].schedule);
          let schedule = res.meta.agent_schedules[0].schedule.filter(
            this.getQueueSchedule
          )[0];
          // console.log(schedule);
          this.processOngoingSchedule(schedule);
        })
        .catch(err => {
          console.log(err);
        });
    },
    getQueueSchedule: function(schedule) {
      return (
        schedule.title_id < 3 &&
        moment(moment(), "YYYY-MM-DD HH:mm:ss").isBetween(
          moment(
            moment(schedule.start_event).subtract(30, "minutes"),
            "YYYY-MM-DD HH:mm:ss"
          ),
          moment(schedule.end_event, "YYYY-MM-DD HH:mm:ss")
        )
      );
    },
    processOngoingSchedule: function(schedule) {
      let sched = schedule;
      // console.log(sched);
      this.timeTracker.today.schedule.label =
        this.calendarFormat(sched.start_event) +
        " - " +
        this.calendarFormat(sched.end_event);
      this.timeTracker.form.schedule.id = sched.id;
      // console.log(sched.attendances);
      let latestAttendance = sched.attendances[sched.attendances.length - 1];
      if (sched.title_id < 3) {
        //   console.log(sched);
        if (!this.isEmpty(sched.attendances)) {
          if (
            latestAttendance.time_out == null &&
            latestAttendance.time_in != null
          ) {
            //timeout
            // console.log("Enable timeout");
            this.timeTracker.tracker.button.disabled = false;
            this.timeTracker.form.schedule.attendance.action = "update";
            this.timeTracker.form.schedule.attendance.id = latestAttendance.id;
            this.timeTracker.form.schedule.attendance.time_in =
              latestAttendance.time_in;
            if (this.timeTracker.buttontrigger === false) {
              this.updateRenderedHours();
              this.timeTracker.tracker.rendered_hours.tick = true;
            }
            if (sched.break ? sched.break.remaining > 0 : false) {
              this.timeTracker.tracker.button.label = "ti-control-stop";
              this.timeTracker.tracker.button.class = "btn-secondary";
            } else {
              this.timeTracker.tracker.button.label = "ti-control-stop";
              this.timeTracker.tracker.button.class = "btn-dark";
            }
          } else if (
            latestAttendance.time_out != null &&
            latestAttendance.time_in != null
          ) {
            //enable store new attendance
            this.timeTracker.tracker.button.disabled = false;
            this.timeTracker.tracker.rendered_hours.tick = false;
            this.timeTracker.tracker.button.label = "ti-control-play";
            this.timeTracker.tracker.button.class = "btn-danger";
            this.timeTracker.form.schedule.attendance.action = "create";
            // console.log("Enable attendance insert");
            if (sched.break ? sched.break.remaining > 0 : false) {
              this.timeTracker.tracker.button.disabled = false;
            } else {
              this.timeTracker.tracker.button.disabled = true;
              this.timeTracker.tracker.button.class = "btn-dark";
              this.timeTracker.tracker.button.label = "ti-control-play";
            }
          }
        } else {
          this.timeTracker.tracker.button.disabled = false;
          this.timeTracker.tracker.rendered_hours.tick = false;
          this.timeTracker.tracker.button.label = "ti-control-play";
          this.timeTracker.tracker.button.class = "btn-danger";
          this.timeTracker.form.schedule.attendance.action = "create";
          //   console.log("Empty Attendance");
        }
      } else {
        this.timeTracker.today.schedule.label = "No Schedule";
        this.timeTracker.tracker.button.disabled = true;
        this.timeTracker.tracker.button.class = "btn-dark";
        this.timeTracker.tracker.button.label = "ti-control-play";
      }
      //   this.broadcastListerner();
    },
    addAttendance: function() {
      // console.log("create");
      let pageurl = "/api/v1/attendance/create";
      let schedule = this.timeTracker.form.schedule;
      // console.log({
      //   auth_id: this.userId,
      //   user_id: this.userId,
      //   schedule_id: schedule.id,
      //   id: schedule.attendance.id,
      //   time_in: moment().format("YYYY-MM-DD HH:mm:ss"),
      //   time_out: null
      // });
      fetch(pageurl, {
        method: "post",
        body: JSON.stringify({
          auth_id: this.userId,
          user_id: this.userId,
          schedule_id: schedule.id,
          time_in: moment().format("YYYY-MM-DD HH:mm:ss"),
          time_out: null
        }),
        headers: {
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          // console.log(data);
          if (data.code == 200) {
            this.processOngoingSchedule(data.meta);
            this.fetchAgentReports();
          } else {
            // console.log(data);
            alert(
              "Something's wrong, the system can't process your request. Please check connection or call for IT support."
            );
          }
        })
        .catch(err => console.log(err));
    },
    updateAttendance: function() {
      // console.log("update");
      let schedule = this.timeTracker.form.schedule;
      let pageurl = "/api/v1/attendance/update/" + schedule.attendance.id;
      // console.log({
      //   auth_id: this.userId,
      //   user_id: this.userId,
      //   schedule_id: schedule.id,
      //   id: schedule.attendance.id,
      //   time_out: moment().format("YYYY-MM-DD HH:mm:ss"),
      //   time_in: moment(schedule.attendance.time_in).format(
      //     "YYYY-MM-DD HH:mm:ss"
      //   )
      // });
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
          // console.log(data);
          if (data.code == 200) {
            this.processOngoingSchedule(data.meta);
            this.fetchAgentReports();
          } else {
            // console.log(data);
            alert(
              "Something's wrong, the system can't process your request. Please check connection or call for IT support."
            );
          }
        })
        .catch(err => console.log(err));
    },
    clickTracker: function() {
      this.timeTracker.tracker.button.disabled = true;
      if (this.timeTracker.form.schedule.attendance.action == "create") {
        this.timeTracker.buttontrigger = true;
        if (this.timeTracker.buttontrigger) {
          // timer function here
          this.workTimer();
        }
        this.addAttendance();
      } else if (this.timeTracker.form.schedule.attendance.action == "update") {
        if (this.timeTracker.buttontrigger) {
          clearInterval(this.timeTracker.tracker.timer.interval);
          this.timeTracker.tracker.timer.interval = null;
        } else {
          this.timeTracker.tracker.rendered_hours.tick = false;
        }
        this.updateAttendance();
      }
    },
    workTimer: function() {
      this.timeTracker.tracker.timer.value = moment
        .utc(moment.duration(0, "seconds").asMilliseconds())
        .format("HH:mm:ss");
      this.timeTracker.tracker.timer.time = 0;
      this.timeTracker.tracker.timer.interval = setInterval(() => {
        this.timeTracker.tracker.timer.time++;
        this.timeTracker.tracker.timer.value = moment
          .utc(
            moment
              .duration(this.timeTracker.tracker.timer.time, "seconds")
              .asMilliseconds()
          )
          .format("HH:mm:ss");
      }, 1 * 1000);
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
    },
    objSort: function(obj, order, condition) {
      var result = [];
      if (order == "asc") {
        result = obj.sort(function(a, b) {
          let name = condition(a, b);
          if (name.a < name.b)
            //sort string ascending
            return -1;
          if (name.a > name.b) return 1;
          return 0;
        });
      } else if (order == "desc") {
        result = obj.sort(function(a, b) {
          let name = condition(a, b);
          if (name.a > name.b)
            //sort string ascending
            return -1;
          if (name.a < name.b) return 1;
          return 0;
        });
      }
      return result;
    },
    getRemainingBreakDuration: function(seconds) {
      let result;
      if (seconds <= 3600) {
        result = moment
          .utc(moment.duration(3600 - seconds, "seconds").asMilliseconds())
          .format("HH:mm:ss");
      } else {
        result = "00:00:00";
      }
      return result;
    },
    getDHMS: function(seconds) {
      // console.log(seconds);
      let result = "00:00:00";
      if (seconds > 0) {
        let day, hr, min;
        day = Math.floor(seconds / (3600 * 24));
        seconds -= day * 3600 * 24;
        hr = Math.floor(seconds / 3600);
        seconds -= hr * 3600;
        min = Math.floor(seconds / 60);
        seconds -= min * 60;

        result = day + ":" + hr + ":" + min + ":" + seconds;
      }
      return result;
    },
    checkPrevUndismissedAttendance: function(schedule) {
      let result = false;
      if (
        !this.isEmpty(schedule.attendances)
          ? schedule.attendances[schedule.attendances.length - 1].time_out ==
            null
          : false
      ) {
        result = true;
      }
      return result;
    }
  }
};
</script>
