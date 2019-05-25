<template>
  <div class="layer w-100 pX-20 pY-30 pB-20">
    <highchart :options="chartOptions"/>
  </div>
</template>

<script>
import Highcharts from "highcharts";
import exportingInit from "highcharts/modules/exporting";
import { Chart } from "highcharts-vue";
import Moment from "moment/moment";
import { extendMoment } from "moment-range";
const moment = extendMoment(Moment);
exportingInit(Highcharts);
export default {
  components: {
    highchart: Chart
  },
  props: ["userId"],
  data() {
    return {
      chartOptions: {
        chart: {
          type: "line"
        },
        title: {
          text: "15 days Graph",
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
            text: "Population"
          }
        },

        plotOptions: {
          column: {
            stacking: "normal"
          }
        },
        series: [
          {
            data: this.init_graph
          },
          {
            data: this.init_graph
          },
          {
            data: this.init_graph
          },
          {
            data: this.init_graph
          },
          {
            data: this.init_graph
          },
          {
            data: this.init_graph
          }
        ]
      },
      init_graph: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    sumOFobject: function(input) {
      if (toString.call(input) !== "[object Array]") return false;

      var total = 0;
      for (var i = 0; i < input.length; i++) {
        if (isNaN(input[i])) {
          continue;
        }
        total += Number(input[i]);
      }
      return total;
    },
    fetchData: function() {
      let startDate = moment()
          .subtract("15", "day")
          .format("YYYY-MM-DD"),
        endDate = moment()
          .subtract("1", "day")
          .format("YYYY-MM-DD"),
        range = moment.range(startDate, endDate),
        dates = Array.from(range.by("day")).map(m => m.format("MMM Do")),
        querydates = Array.from(range.by("day")).map(m =>
          m.format("YYYY-MM-DD")
        );
      this.chartOptions.xAxis.categories = dates;
      let pageurl =
        "/api/v1/schedules/work/report?start=" + startDate + "&end=" + endDate;

      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          this.table.data = res.meta.agent_schedules.filter(function(index) {
            return index.info.status != "inactive";
          });
          let filtered = res.meta.agent_schedules.filter(function(index) {
            return index.info.status != "inactive";
          });
          var obj = [];
          for (var l = 0; l < filtered.length; l++) {
            var v = filtered[l];
            var tmp = [];
            // console.log(v.schedule);
            if (v.schedule.length !== 0) {
              for (var l1 = 0; l1 < v.schedule.length; l1++) {
                var v1 = v.schedule[l1];
                if (v1.title_id < 9) {
                  tmp.push(this.extractSchedule(v, v1));
                }
              }
            }
            obj.push(tmp);
          }
          obj = [...new Set([].concat(...obj.map(a => a)))];
          // console.log(obj);
          let scheduled = [],
            present = [],
            absent = [],
            tardy = [],
            leave = [],
            off = [];
          querydates.forEach(
            function(v, i) {
              // x
              scheduled.push(
                obj.filter(
                  function(x) {
                    return (
                      !this.isEmpty(x.schedule) &&
                      v ==
                        moment(x.schedule.start_event).format("YYYY-MM-DD") &&
                      x.schedule.title_id < 3
                    );
                  }.bind(this)
                ).length
              );
              //   present
              present.push(
                obj.filter(
                  function(x) {
                    return (
                      !this.isEmpty(x.schedule) &&
                      v ==
                        moment(x.schedule.start_event).format("YYYY-MM-DD") &&
                      x.attendance == "present"
                    );
                  }.bind(this)
                ).length
              );
              //   absent
              absent.push(
                obj.filter(
                  function(x) {
                    return (
                      !this.isEmpty(x.schedule) &&
                      v ==
                        moment(x.schedule.start_event).format("YYYY-MM-DD") &&
                      x.schedule.title_id == "absent"
                    );
                  }.bind(this)
                ).length
              );
              //   tardy
              tardy.push(
                obj.filter(
                  function(x) {
                    return (
                      !this.isEmpty(x.schedule) &&
                      v ==
                        moment(x.schedule.start_event).format("YYYY-MM-DD") &&
                      !this.isEmpty(x.schedule.attendances) &&
                      moment(x.schedule.time_in).isAfter(
                        moment(x.schedule.start_event).format("HH:mm:ss")
                      )
                    );
                  }.bind(this)
                ).length
              );
              // leave
              leave.push(
                obj.filter(
                  function(x) {
                    return (
                      !this.isEmpty(x.schedule) &&
                      v ==
                        moment(x.schedule.start_event).format("YYYY-MM-DD") &&
                      x.schedule.title_id > 2 &&
                      x.schedule.title_id < 9
                    );
                  }.bind(this)
                ).length
              );
              //off
              off.push(filtered.length - (leave[i] + scheduled[i]));

              //   tardy.push(
              //     obj.filter(
              //       function(x) {
              //         return (
              //           !this.isEmpty(x.schedule) &&
              //           v ==
              //             moment(x.schedule.start_event).format("YYYY-MM-DD") &&
              //           !this.isEmpty(x.schedule.attendances) &&
              //           moment(x.schedule.time_in).isAfter(
              //             moment(x.schedule.start_event).format("HH:mm:ss")
              //           )
              //         );
              //       }.bind(this)
              //     ).length
              //   );
            }.bind(this)
          );

          // console.log("schedule");
          // console.log(scheduled);
          // console.log("off");
          // console.log(off);
          this.chartOptions.series[0].data = scheduled;
          this.chartOptions.series[0].name = "Scheduled";
          this.chartOptions.series[0].color = "#5396D3";
          this.chartOptions.series[0].opacity = 0.8;
          this.chartOptions.series[0].stack = 0;
          this.chartOptions.series[1].data = present;
          this.chartOptions.series[1].name = "Present";
          this.chartOptions.series[1].color = "#98B955";
          this.chartOptions.series[1].opacity = 0.8;
          this.chartOptions.series[1].stack = 0;
          this.chartOptions.series[2].data = tardy;
          this.chartOptions.series[2].name = "Tardy";
          this.chartOptions.series[2].color = "#e93a75";
          this.chartOptions.series[2].opacity = 0.8;
          this.chartOptions.series[2].stack = 0;
          this.chartOptions.series[3].data = absent;
          this.chartOptions.series[3].name = "Absent";
          this.chartOptions.series[3].color = "grey";
          this.chartOptions.series[3].opacity = 0.8;
          this.chartOptions.series[3].stack = 0;
          this.chartOptions.series[4].data = leave;
          this.chartOptions.series[4].name = "Leave";
          this.chartOptions.series[4].color = "#F3914F";
          this.chartOptions.series[4].opacity = 0.8;
          this.chartOptions.series[4].stack = 0;
          this.chartOptions.series[5].data = off;
          this.chartOptions.series[5].name = "Off-Duty";
          this.chartOptions.series[5].color = "#872E93";
          this.chartOptions.series[5].opacity = 0.8;
          this.chartOptions.series[5].stack = 0;
        })
        .catch(err => {
          console.log(err);
        });
    },

    extractSchedule: function(info, schedule) {
      var tmp = {
        info: {
          full_name: "",
          email: "",
          id: "",
          tl: {
            full_name: "",
            email: "",
            id: ""
          },
          om: {
            full_name: "",
            email: "",
            id: ""
          }
        },
        schedule: [],
        attendance: ""
      };
      tmp.info.full_name = info.full_name;
      tmp.info.email = info.email;
      tmp.info.id = info.id;
      tmp.info.om.full_name = info.operations_manager.full_name;
      tmp.info.om.email = info.operations_manager.email;
      tmp.info.om.id = info.operations_manager.id;
      tmp.info.tl.full_name = info.team_leader.full_name;
      tmp.info.tl.email = info.team_leader.email;
      tmp.info.tl.id = info.team_leader.id;
      tmp.schedule = schedule;
      tmp.attendance = this.getAttendance(schedule);
      return tmp;
    },
    getAttendance: function(schedule) {
      let result;
      if (schedule.is_present == 1) {
        result = "present";
      } else {
        if (
          moment().isBetween(
            moment(schedule.start_event),
            moment(schedule.end_event)
          )
        ) {
          result = "no_show";
        } else if (moment(schedule.end_event).isBefore(moment())) {
          result = "absent";
        } else if (moment(schedule.start_event).isAfter(moment())) {
          result = "none";
        }
      }
      return result;
    }
  }
};
</script>
