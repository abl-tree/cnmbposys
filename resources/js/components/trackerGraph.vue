<template>
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
            title="Total Billable"
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
</template>

<script>
export default {
  props: ["trackerGraph"],
  data() {
    return {};
  },
  mounted() {},
  methods: {}
};
</script>
