<template>
  <div class="layers pB-0 mB-15">
    <div class="layer w-100 bgc-white bd">
      <div class="col pY-20 pX-10">
        <h6 class="lh-1" style="font-weight:lighter">{{stats.title}}</h6>
      </div>
      <div class="col">
        <div class="layer w-100" style="position:relative;">
          <div
            style="position:absolute;border-radius:3px;top:-50px;right:0;"
            class="text-center align-self-center w-50p pY-15 pX-5"
            :style="bgc[statName]"
          >
            <span class="fw-300 fsz-lg c-white">{{ stats.today[statName] }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import moment from "moment";
export default {
  props: ["statName"],
  mounted() {
    this.fetchData();
    this.getStatsToday();
  },
  data() {
    return {
      stats: {
        title: "",
        today: {
          scheduled: 0,
          off_duty: 0,
          on_break: 0,
          present: 0,
          tardy: 0,
          leave: 0
        }
      },
      title: {
        scheduled: "Scheduled",
        off_duty: "Off-Duty",
        on_break: "On-Break",
        present: "Present",
        tardy: "Tardy",
        leave: "On-Leave"
      },
      bgc: {
        leave: "background-image: linear-gradient(-90deg, #ffa420, #fb8f07);",
        tardy: "background-image: linear-gradient(-90deg, #e93a75, #da2063);",
        on_break:
          "background-image: linear-gradient(-90deg, #64b968, #44a049);",
        scheduled:
          "background-image: linear-gradient(-90deg, #20c5d6, #04abc3);",
        off_duty:
          "background-image: linear-gradient(-90deg, #8540A7, #7435A2);",
        present: "background-image: linear-gradient(-90deg, #74db7a, #45a748);"
      },
      sparklines: {
        data: {
          scheduled: [],
          off_duty: [],
          on_break: [],
          present: [],
          tardy: [],
          leave: []
        }
      },
      init_graph: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],

      // 曲线样式
      spCurveStyles3: {
        stroke: "#ffff",
        fill: "#fff"
      },
      // 结点样式
      spSpotStyles3: {
        fill: "#fff"
      },
      // 结点属性
      spSpotProps3: {
        size: 2
      },
      // 参考线种类：'max', 'min', 'mean', 'avg', 'median', 'custom' or false
      spRefLineType3: "avg",
      // 参考线样式
      spRefLineStyles3: {
        stroke: "#d14",
        strokeOpacity: 1,
        strokeDasharray: "2, 2"
      },
      // 当前结点
      spIndex3: 3,
      // 字体样式
      spTextStyles3: {
        fill: "#d14",
        fontSize: "12"
      },
      // 指示器样式
      spIndicatorStyles3: {
        stroke: "#000"
      },
      // tooltip属性（通常用于自定义提示信息, formatter方法参数为数据value和颜色color对象）
      // spTooltipProps3: {
      //   formatter(val) {
      //     return (
      //       this.title[this.statName] +
      //       `：<label style="color:${val.color};font-weight:bold;">${
      //         val.value
      //       }</label>`
      //     );
      //   }
      // },
      nowCount: 0
    };
  },
  methods: {
    fetchData: function() {
      this.stats.title = this.title[this.statName];
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
      let pageurl =
        "/api/v1/schedules/work/report?start=" + startDate + "&end=" + endDate;

      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          var obj = [];
          for (var l = 0; l < res.meta.agent_schedules.length; l++) {
            var v = res.meta.agent_schedules[l];
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
              off.push(
                res.meta.agent_schedules.length - (leave[i] + scheduled[i])
              );

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
          this.sparklines.data.scheduled = scheduled;
          this.sparklines.data.present = present;
          this.sparklines.data.tardy = tardy;
          this.sparklines.data.absent = absent;
          this.sparklines.data.leave = leave;
          this.sparklines.data.off_duty = off;
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
    },
    getStatsToday: function() {
      fetch("/api/v1/schedules/work/today")
        .then(res => res.json())
        .then(res => {
          // console.log("TODAY");
          // console.log(res);
          let obj = [],
            scheduled,
            present,
            leave,
            off_duty,
            tardy;
          let temp = res.meta.agent_schedules.filter(
            i => i.info.status != "inactive"
          );
          temp.forEach(
            ((v, i) => {
              obj.push(v.schedule[0]);
            }).bind(this)
          );
          // console.log(obj)
          this.stats.today.scheduled = obj.filter(
            i => typeof i != "undefined" && i.title_id < 3
          ).length;
          this.stats.today.present = obj.filter(
            i => typeof i != "undefined" && i.title_id < 3 && i.is_present == 1
          ).length;
          this.stats.today.tardy = obj.filter(
            i =>
              typeof i != "undefined" &&
              i.title_id < 3 &&
              i.is_present == 1 &&
              i.log_status[0] == "Tardy"
          ).length;
          this.stats.today.leave = obj.filter(
            i => typeof i != "undefined" && i.title_id > 2 && i.title_id < 9
          ).length;
          this.stats.today.off_duty = obj.filter(
            i => typeof i == "undefined"
          ).length;
        })
        .catch(err => console.log(err));
    }
  }
};
</script>