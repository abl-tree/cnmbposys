<template>
  <div class="bd bgc-white">
    <div class="peers fxw-nw@lg+ ai-s">
      <div class="peer peer-greed w-70p@lg+ w-100@lg- p-20">
        <div class="layers">
          <div class="layer w-100 mB-10">
            <h6 class="lh-1">Events</h6>
          </div>
          <div class="layer w-100">
            <mini-calendar :user-id="userId"></mini-calendar>
          </div>
        </div>
      </div>
      <div class="peer bdL p-20 w-30p@lg+ w-100p@lg-">
        <div class="layers">
          <div class="layer w-100 align-self-center">
            <!-- Progress Bars -->
            <div class="layers">
              <div class="layer w-100 pY-20">
                <h5 class="mB-5">{{ numberWithCommas(statComponent.config.stats.days.decimal) }}</h5>
                <small class="fw-600 c-grey-700">Days Employed</small>
                <span
                  class="pull-right c-grey-600 fsz-sm"
                >{{ precision2(statComponent.config.stats.days.percentage)+" %" }}</span>
                <div class="progress mT-10">
                  <div
                    class="progress-bar bgc-deep-purple-500"
                    role="progressbar"
                    :aria-valuenow="statComponent.config.stats.days.decimal"
                    aria-valuemin="0"
                    :aria-valuemax="statComponent.cnmStartDate"
                    :style="'width:'+statComponent.config.stats.days.percentage+'%;'"
                  >
                    <!-- <span class="sr-only">50% Complete</span> -->
                  </div>
                </div>
              </div>
              <div class="layer w-100 mT-15 pY-20">
                <h5
                  class="mB-5"
                >{{ numberWithCommas(statComponent.config.stats.work_days.decimal) }}</h5>
                <small class="fw-600 c-grey-700">Days Scheduled</small>
                <span
                  class="pull-right c-grey-600 fsz-sm"
                >{{ precision2(statComponent.config.stats.work_days.percentage)+" %" }}</span>
                <div class="progress mT-10">
                  <div
                    class="progress-bar bgc-green-500"
                    role="progressbar"
                    :aria-valuenow="statComponent.config.stats.work_days.decimal"
                    aria-valuemin="0"
                    :aria-valuemax="statComponent.config.stats.days.decimal"
                    :style="'width:'+statComponent.config.stats.work_days.percentage+'%;'"
                  >
                    <!-- <span class="sr-only">80% Complete</span> -->
                  </div>
                </div>
              </div>
              <div class="layer w-100 mT-15 pY-20">
                <h5
                  class="mB-5"
                >{{ numberWithCommas(statComponent.config.stats.present_days.decimal) }}</h5>
                <small class="fw-600 c-grey-700">Days Present</small>
                <span
                  class="pull-right c-grey-600 fsz-sm"
                >{{ precision2(statComponent.config.stats.present_days.percentage)+" %" }}</span>
                <div class="progress mT-10">
                  <div
                    class="progress-bar bgc-light-blue-500"
                    role="progressbar"
                    :aria-valuenow="statComponent.config.stats.present_days.decimal"
                    aria-valuemin="0"
                    :aria-valuemax="statComponent.config.stats.work_days.decimal"
                    :style="'width:'+statComponent.config.stats.present_days.percentage+'%;'"
                  >
                    <!-- <span class="sr-only">40% Complete</span> -->
                  </div>
                </div>
              </div>
              <div class="layer w-100 mT-15 pY-20">
                <h5
                  class="mB-5"
                >{{ numberWithCommas(statComponent.config.stats.leaved_days.decimal) }}</h5>
                <small class="fw-600 c-grey-700">Days On-Leave</small>
                <span
                  class="pull-right c-grey-600 fsz-sm"
                >{{ precision2(statComponent.config.stats.leaved_days.percentage)+" %" }}</span>
                <div class="progress mT-10">
                  <div
                    class="progress-bar bgc-blue-grey-500"
                    role="progressbar"
                    :aria-valuenow="statComponent.config.stats.leaved_days.decimal"
                    aria-valuemin="0"
                    :aria-valuemax="statComponent.config.stats.work_days.decimal"
                    :style="'width:'+statComponent.config.stats.leaved_days.percentage+'%;'"
                  >
                    <span class="sr-only">90% Complete</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pie Charts -->
            <!-- <div class="peers pT-20 mT-20 bdT fxw-nw@lg+ jc-sb ta-c gap-10">
              <div class="peer">
                <div
                  class="easy-pie-chart"
                  data-size="80"
                  data-percent="75"
                  data-bar-color="#f44336"
                >
                  <span></span>
                </div>
                <h6 class="fsz-sm">New Users</h6>
              </div>
              <div class="peer">
                <div
                  class="easy-pie-chart"
                  data-size="80"
                  data-percent="50"
                  data-bar-color="#2196f3"
                >
                  <span></span>
                </div>
                <h6 class="fsz-sm">New Purchases</h6>
              </div>
              <div class="peer">
                <div
                  class="easy-pie-chart"
                  data-size="80"
                  data-percent="90"
                  data-bar-color="#ff9800"
                >
                  <span></span>
                </div>
                <h6 class="fsz-sm">Bounce Rate</h6>
              </div>
            </div>-->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import moment from "moment";
export default {
  props: ["userId", "accessId"],
  mounted() {
    this.fetchStats();
  },
  data() {
    return {
      statComponent: {
        cnmStartDate: "",
        config: {
          stats: {
            days: {
              decimal: 0,
              percentage: 0
            },
            work_days: {
              decimal: 0,
              percentage: 0
            },
            present_days: {
              decimal: 0,
              percentage: 0
            },
            leaved_days: {
              decimal: 0,
              percentage: 0
            }
          }
        }
      }
    };
  },
  methods: {
    fetchStats: function() {
      this.statComponent.cnmStartDate = moment().diff(
        moment("2015-02-01", "YYYY-MM-DD"),
        "days"
      );
      // alert(this.statComponent.cnmStartDate);

      let startDate = moment("2015-01-01").format("YYYY-MM-DD"),
        endDate = moment().format("YYYY-MM-DD");
      let pageurl =
        "/api/v1/schedules/work/report?start=" +
        startDate +
        "&end=" +
        endDate +
        "&userid=" +
        this.userId;
      // console.log(pageurl);
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          //   console.log(res);
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
          //cnm days
          this.statComponent.config.stats.days.decimal = moment().diff(
            moment(res.meta.agent_schedules[0].info.hired_date).format(
              "YYYY-MM-DD"
            ),
            "days"
          );
          this.statComponent.config.stats.days.percentage =
            (this.statComponent.config.stats.days.decimal /
              this.statComponent.cnmStartDate) *
            100;

          // scheduled days
          this.statComponent.config.stats.work_days.decimal = obj.filter(
            this.workSchedules
          ).length;
          this.statComponent.config.stats.work_days.percentage =
            (this.statComponent.config.stats.work_days.decimal /
              this.statComponent.config.stats.days.decimal) *
            100;

          // present days
          this.statComponent.config.stats.present_days.decimal = obj.filter(
            this.workedSchedules
          ).length;
          this.statComponent.config.stats.present_days.percentage =
            (this.statComponent.config.stats.present_days.decimal /
              this.statComponent.config.stats.work_days.decimal) *
            100;

          // leave days
          this.statComponent.config.stats.leaved_days.decimal = obj.filter(
            this.leavedSchedules
          ).length;
          this.statComponent.config.stats.leaved_days.percentage =
            (this.statComponent.config.stats.leaved_days.decimal /
              this.statComponent.config.stats.days.decimal) *
            100;
        })
        .catch(err => {
          console.log(err);
        });
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
    numberWithCommas: function(x) {
      var parts = x.toString().split(".");
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      return parts.join(".");
    },
    precision2: function(x) {
      return Number.parseFloat(x).toFixed(2);
    }
  }
};
</script>