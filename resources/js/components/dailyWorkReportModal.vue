<template>
  <modal
    name="daily-work-report-modal"
    :pivotY="0.2"
    :scrollable="true"
    width="900px"
    height="auto"
  >
    <div class="layer">
      <div class="e-modal-header bd">
        <h6 style="margin-bottom:0px">Work Log</h6>
      </div>
      <div class="w-100 p-15 pT-80" style>
        <div class="container">
          <div class="row">
            <!-- <div class="col-sm-2 pX-0 text-center">
              <img class="bdrs-50p w-3r h-3r" src="/images/nobody.jpg">
            </div>
            <div class="col-sm-7 pX-0">
              <div class="text-group">
                <div class="p-0 m-0 fw-500">Avalo Kitesva Rapad Mapani</div>
                <div class="p-0 m-0">jamesenglajom@gmail.com</div>
              </div>
              <div class="text-group p-0 mT-15">
                <div class="fsz-xs fw-300">Schedule Details</div>
                <div class="p-0 m-0 fsz-xs">Monday Jan. 25, 2019</div>
                <div class="fsz-xs">08:00am - 04:00pm</div>
              </div>
            </div>
            <div class="col-sm-3 text-center pX-0">
              <small class="p-0 m-0">Log Details</small>
              <h6 class="mY-0">
                <span class="badge badge-pill badge-info fw-900 w-100">08:00am - 03:00pm</span>
              </h6>
              <h6 class="mY-0">
                <span class="badge badge-pill badge-success fw-900 w-100">PRESENT</span>
              </h6>
              <h6 class="mY-0">
                <span class="badge badge-pill badge-success fw-900 w-100">READY</span>
              </h6>
              <h6 class="mY-0">
                <span class="badge badge-pill badge-danger fw-900 w-100">UNDERTIME</span>
              </h6>
            </div>-->
            <div class="col">
              <div class="p-10">
                <table>
                  <t-body>
                    <tr>
                      <td>
                        <td-personnel></td-personnel>
                      </td>
                    </tr>
                  </t-body>
                </table>
              </div>
              <div class="table-responsive pX-10 pB-20" style="height:200px;">
                <table class="table">
                  <thead>
                    <tr style="position:relative">
                      <th class="bdwT-0 text-center">Schedule</th>
                      <th
                        class="bdwT-0 text-center"
                        data-toggle="tooltip"
                        title="Scheduled Duration"
                      >SD</th>
                      <th class="bdwT-0 text-center">Attendance</th>
                      <th class="bdwT-0 text-center">Logs</th>
                      <th class="bdwT-0 text-center">
                        <span data-toggle="tooltip" title="Work Duration">WD</span>
                      </th>
                      <th class="bdwT-0 text-center">
                        <span data-toggle="tooltip" title="Non Billable Over Time Duration">NBOTD</span>
                      </th>
                      <th class="bdwT-0 text-center">
                        <span data-toggle="tooltip" title="Break Duration">BD</span>
                      </th>
                      <th class="bdwT-0 text-right">
                        <span data-toggle="tooltip" title="Billable Hours">BH</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- <template
                      v-for="(datum,index) in config.filter.data.data"
                      v-if="config.loader==false"
                    >
                      <tr :key="datum.id">
                        <td-personnel
                          :personnel="{full_name:datum.info.full_name,email:datum.info.email,}"
                        ></td-personnel>
                        <td-personnel :personnel="datum.info.tl"></td-personnel>
                        <td-personnel :personnel="datum.info.om"></td-personnel>
                        <td-schedule
                          :schedule="!isEmpty(datum.schedule)?{start:datum.schedule.start_event,end:datum.schedule.end_event}:null"
                        ></td-schedule>
                        <td-regular-hour-duration :schedule="datum.schedule"></td-regular-hour-duration>
                        <td-attendance :attendance="datum.attendance"></td-attendance>
                        <td-attendance-log
                          :attendance="datum.attendance"
                          :schedule="datum.schedule"
                        ></td-attendance-log>
                        <td-rendered-hours :schedule="datum.schedule"></td-rendered-hours>
                        <td-nonbillable-ot :schedule="datum.schedule" :index="index"></td-nonbillable-ot>
                        <b-popover
                          triggers="focus"
                          placement="auto"
                          :target="'ot-popover-'+index"
                          title="Overtime"
                        >
                          <div class="w-100 text-center">
                            <span class="c-grey-600" style="font-size:0.8em;">NonBillable(OT)</span>
                          </div>
                          <div class="w-100 text-center">
                            <span
                              class="c-grey-600"
                              style="font-size:1em;"
                            >{{datum.schedule.overtime.nonbillable.time}}</span>
                          </div>
                          <vue-timepicker v-model="config.overtime.approve.time" format="HH:mm:ss"></vue-timepicker>
                        </b-popover>
                        <td-break-duration :schedule="datum.schedule"></td-break-duration>
                        <td-billable-hours :schedule="datum.schedule"></td-billable-hours>
                      </tr>
                    </template>-->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="e-modal-footer bd">
        <div class="row">
          <div class="layer w-100 text-right pR-20">
            <button class="btn btn-secondary" @click="hideModal('daily-work-report-modal')">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </modal>
</template>
<script>
export default {
  props: [],
  mounted() {},
  data() {
    return {};
  }
};
</script>
