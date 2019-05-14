<template>
  <div class="layer w-100 pX-20 pY-30 pB-20">
    <!-- filter -->
    <div class="layer pB-20 w-100">
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
            class="pX-10 c-green-300 fw-600 fsz-lg"
            data-toggle="tooltip"
            title="Total Billable"
          >{{ trackerGraph.processed.data.filtered.overallbillable }}</span>
        </div>
      </div>
    </div>
    <!-- chart -->
    <highchart :options="trackerGraph.chartOptions"/>
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
                    <small>BD:</small>
                    {{ getDurationBySeconds(datum.break.second) }}
                  </span>
                  <span class="pX-5 bdR" data-toggle="tooltip" title="Rendered Hours">
                    <small>RH:</small>
                    {{ getWorkDuration(datum.time_in,datum.time_out) }}
                  </span>
                  <span class="pX-5" data-toggle="tooltip" title="Billed Hours">
                    <small>BH:</small>
                    <span
                      class="c-green-300"
                    >{{ getDurationBySeconds(datum.rendered_hours.billable.second) }}</span>
                  </span>
                </div>
              </div>
            </div>
            <div class="block-body w-100 bd">
              <div v-for="datum1 in datum.attendances" :key="datum1.id" class="w-100 p-15 bd">
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
</template>

<script>
import Highcharts from "highcharts";
import exportingInit from "highcharts/modules/exporting";
import { Chart } from "highcharts-vue";
exportingInit(Highcharts);
import moment from "moment";

export default {
  components: {
    highchart: Chart
  },
  props: ["userId"],
  data() {
    return {
      trackerGraph: {
        config: {
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
            text: "Work Graph",
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
  mounted() {},
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
      console.log(pageurl);
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          this.trackerGraph.data.reports = res.meta.agent_schedules[0];
          this.extractDataReports(res.meta.agent_schedules[0]);
        })
        .catch(err => console.log(err));
    },
    extractDataReports: function(obj) {
      let schedules = [...new Set([].concat(...obj.schedule.map(a => a)))];
      // console.log(schedules);
      this.trackerGraph.processed.data.schedules = schedules;
      let tmp = schedules.filter(this.getWorkAndLeaveSchedules);
      let dates = [],
        billable = [],
        nonbillable = [],
        otbillable = [],
        otnonbillable = [],
        overallbillable = 0;
      tmp.forEach(
        function(v, i) {
          dates.push(
            moment(v.start_event).format("MMM Do YY") +
              " - " +
              moment(v.end_event).format("MMM Do YY")
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
      this.trackerGraph.processed.data.filtered.schedules = tmp;

      this.trackerGraph.processed.data.filtered.totalworkdays = tmp.length;
      this.trackerGraph.processed.data.filtered.overallbillable = this.getDurationBySeconds(
        overallbillable
      );
      // console.log(dates);
      // console.log(nonbillable);
      // console.log(billable);
      // console.log(otnonbillable);
      // console.log(otbillable);
    },
    getWorkAndLeaveSchedules: function(index) {
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
    }
  }
};
</script>
