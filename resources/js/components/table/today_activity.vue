
<template>
  <!-- #Sales Report ==================== -->
  <div class="bd bgc-white">
    <div class="layers">
      <div class="layer w-100 p-20">
        <h6 class="lh-1">Today's Activity</h6>
      </div>

      <div class="layer pX-20 w-100">
        <div class="row pX-20">
          <div
            v-for="(tab,index) in config.tabs"
            :key="tab.id"
            class="col text-center pX-0 cur-p"
            @click="config.selected_tab = index"
          >
            <span
              class="text-center w-100 pY-10 badge-c"
              :class="config.selected_tab == index? 'bgc-white c-grey-900 bdL bdR': 'bgc-grey-200 c-grey-600 bd'"
              :style="config.selected_tab == index? 'border-top: 1px red solid':''"
            >{{tab.tab_name}}</span>
          </div>
        </div>
      </div>
      <div class="layer w-100 pX-20">
        <!-- <div class="input-group">
          <select class="custom-select custom-select-sm" id="inputGroupSelect04">
            <option selected>Agent...</option>
            <option value="1">One</option>
            <option value="2">Two</option>
            <option value="3">Three</option>
          </select>
          <select class="custom-select custom-select-sm" id="inputGroupSelect04">
            <option selected>Supervisor...</option>
            <option value="1">One</option>
            <option value="2">Two</option>
            <option value="3">Three</option>
          </select>
          <select class="custom-select custom-select-sm" id="inputGroupSelect04">
            <option selected>Manager...</option>
            <option value="1">One</option>
            <option value="2">Two</option>
            <option value="3">Three</option>
          </select>
          <select class="custom-select custom-select-sm" id="inputGroupSelect04">
            <option selected>Attendance...</option>
            <option value="1">One</option>
            <option value="2">Two</option>
            <option value="3">Three</option>
          </select>
          <select class="custom-select custom-select-sm" id="inputGroupSelect04">
            <option selected>Log Status...</option>
            <option value="1">One</option>
            <option value="2">Two</option>
            <option value="3">Three</option>
          </select>
          <div class="input-group-append">
            <button class="btn btn-outline-secondary btn-sm" type="button">Clear</button>
          </div>
        </div>-->
        <div class="table-responsive p-20">
          <table class="table">
            <thead>
              <tr>
                <th class="bdwT-0">Agent</th>
                <th class="bdwT-0">Supervisor</th>
                <th class="bdwT-0">Manager</th>
                <th class="bdwT-0">Schedule</th>
                <th class="bdwT-0">Attendance</th>
                <th class="bdwT-0">Log</th>
                <th class="bdwT-0">Log Status</th>
                <!-- <th class="bdwT-0">Billable</th>
                <th class="bdwT-0">Action</th>-->
              </tr>
            </thead>
            <tbody>
              <tr v-for="datum in table.data" :key="datum.id">
                <td style="font-size:.95em">
                  <div class="fw-600">
                    <i class="ti-user c-grey-400 mR-5"></i>Emmanuel James Eng Lajom
                    <span
                      v-if="datum.schedule.length<1"
                      class="mL-5 text-secondary fsz-xs"
                      style="cursor:pointer;"
                      data-toogle="tooltip"
                      title="Off-Duty"
                    >&#9900;</span>
                  </div>
                  <div>
                    <i class="ti-email c-grey-400 mR-5"></i>jamesenglajom
                  </div>
                  <div class="c-light-blue-400">@cnmsolutions.net</div>
                </td>
                <td style="font-size:.95em">
                  <div class="fw-600">
                    <i class="ti-user c-grey-400 mR-5"></i>The Supervisor
                  </div>
                  <div>
                    <i class="ti-email c-grey-400 mR-5"></i>supervisor@gmail.com
                  </div>
                  <div class="c-light-blue-400">@cnmsolutions.net</div>
                </td>
                <td style="font-size:.95em">
                  <div class="fw-600">
                    <i class="ti-user c-grey-400 mR-5"></i>The Operations Manager
                  </div>
                  <div>
                    <i class="ti-email c-grey-400 mR-5"></i>manger@gmail.com
                  </div>
                  <div class="c-light-blue-400">@cnmsolutions.net</div>
                </td>
                <td style="font-size:.95em">
                  <div>
                    <span
                      class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100"
                    >NO SCHEDULE</span>
                  </div>
                </td>
                <td style="font-size:.99em">
                  <div>
                    <span class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100">OFF-DUTY</span>
                  </div>
                </td>
                <td style="font-size:.95em">
                  <div>
                    <span class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100">NO LOGS</span>
                  </div>
                </td>
                <td style="font-size:.99em">
                  <div>
                    <span class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100">NO LOGS</span>
                  </div>
                </td>
                <!-- <td style="font-size:.95em">
                  <div>
                    <span class="c-blue-500">8.0</span>
                    <span class="mL-5" style="font-size:0.8em;">REG</span>
                  </div>
                  <div>
                    <span class="c-light-blue-500">1.0</span>
                    <span class="mL-5" style="font-size:0.8em;">OT</span>
                  </div>
                </td>-->
                <!-- <td style="font-size:.99em">buttons here</td> -->
              </tr>
              <tr>
                <td style="font-size:.95em">
                  <div class="fw-600">
                    <i class="ti-user c-grey-400 mR-5"></i>Emmanuel James Eng Lajom
                    <span
                      class="mL-5 text-success fsz-xs"
                      style="cursor:pointer;"
                      data-toogle="tooltip"
                      title="Working"
                    >&#9679;</span>
                  </div>
                  <div>
                    <i class="ti-email c-grey-400 mR-5"></i>jamesenglajom
                  </div>
                  <div class="c-light-blue-400">@cnmsolutions.net</div>
                </td>
                <td style="font-size:.95em">
                  <div class="fw-600">
                    <i class="ti-user c-grey-400 mR-5"></i>The Supervisor
                  </div>
                  <div>
                    <i class="ti-email c-grey-400 mR-5"></i>supervisor@gmail.com
                  </div>
                  <div class="c-light-blue-400">@cnmsolutions.net</div>
                </td>
                <td style="font-size:.95em">
                  <div class="fw-600">
                    <i class="ti-user c-grey-400 mR-5"></i>The Operations Manager
                  </div>
                  <div>
                    <i class="ti-email c-grey-400 mR-5"></i>manger@gmail.com
                  </div>
                  <div class="c-light-blue-400">@cnmsolutions.net</div>
                </td>
                <td style="font-size:.95em">
                  <div>
                    08:00am
                    <span class="mL-5" style="font-size:0.8em;">IN</span>
                  </div>
                  <div>
                    04:00pm
                    <span class="mL-5" style="font-size:0.8em;">OUT</span>
                  </div>
                </td>
                <td style="font-size:.99em">
                  <div>
                    <span
                      class="badge badge-pill p-5 bgc-green-100 c-green-800 fw-900 w-100"
                    >PRESENT</span>
                  </div>
                </td>
                <td style="font-size:.95em">
                  <div>
                    <span class="c-green-500">08:00am</span>
                    <span class="mL-5" style="font-size:0.8em;">IN</span>
                  </div>
                  <div>
                    <span class="c-purple-500">04:10pm</span>
                    <span class="mL-5" style="font-size:0.8em;">OUT</span>
                  </div>
                </td>
                <td style="font-size:.99em">
                  <div class="w-100">
                    <span
                      class="badge badge-pill p-5 bgc-green-100 c-green-800 fw-900 w-100"
                    >PUNCTUAL</span>
                  </div>
                  <div class="w-100 mT-2">
                    <span
                      class="badge badge-pill p-5 bgc-purple-100 c-purple-800 fw-900 w-100"
                    >OVERTIME</span>
                  </div>
                </td>
                <!-- <td style="font-size:.95em">
                  <div>
                    <span class="c-blue-500">8.0</span>
                    <span class="mL-5" style="font-size:0.8em;">REG</span>
                  </div>
                  <div>
                    <span class="c-light-blue-500">1.0</span>
                    <span class="mL-5" style="font-size:0.8em;">OT</span>
                  </div>
                </td>
                <td style="font-size:.99em">buttons here</td>-->
              </tr>
              <!-- <tr>
                <td style="font-size:.95em">
                  <div class="fw-600">
                    Emmanuel James Eng Lajom 1
                    <span
                      class="mL-5 c-grey-500 fsz-xs"
                      style="cursor:pointer;"
                      data-toogle="tooltip"
                      title="Absent"
                    >&#9679;</span>
                  </div>
                  <div>
                    <i class="ti-email c-grey-400 mR-5"></i>jamesenglajom@gmail.com
                  </div>
                </td>
                <td style="font-size:.95em">
                  <div class="fw-600">The Supervisor</div>
                  <div>
                    <i class="ti-email c-grey-400 mR-5"></i>supervisor@gmail.com
                  </div>
                </td>
                <td style="font-size:.95em">
                  <div class="fw-600">The Operations Manager</div>
                  <div>
                    <i class="ti-email c-grey-400 mR-5"></i>manger@gmail.com
                  </div>
                </td>
                <td style="font-size:.95em">
                  <div>
                    08:00am
                    <span class="mL-5" style="font-size:0.8em;">IN</span>
                  </div>
                  <div>
                    04:00pm
                    <span class="mL-5" style="font-size:0.8em;">OUT</span>
                  </div>
                </td>
                <td style="font-size:.99em">
                  <div>
                    <span class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100">ABSENT</span>
                  </div>
                </td>
                <td style="font-size:.95em">
                  <div>
                    <span class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100">NULL</span>
                  </div>
                </td>
                <td style="font-size:.99em">
                  <div class="w-100">
                    <span class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100">NULL</span>
                  </div>
                  <div class="w-100 mT-2">
                    <span class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100">NULL</span>
                  </div>
                </td>
                <td style="font-size:.95em">
                  <div>
                    <span class>0.0</span>
                    <span class="mL-5" style="font-size:0.8em;">REG</span>
                  </div>
                  <div>
                    <span class>0.0</span>
                    <span class="mL-5" style="font-size:0.8em;">OT</span>
                  </div>
                </td>

                <td style="font-size:.99em">buttons here</td>
              </tr>
              <tr>
                <td style="font-size:.95em">
                  <div class="fw-600">
                    Emmanuel James Eng Lajom
                    <span
                      class="mL-5 c-blue-500 fsz-xs"
                      style="cursor:pointer;"
                      data-toogle="tooltip"
                      title="Break"
                    >&#9679;</span>
                  </div>
                  <div>
                    <i class="ti-email c-grey-400 mR-5"></i>jamesenglajom@gmail.com
                  </div>
                </td>
                <td style="font-size:.95em">
                  <div class="fw-600">The Supervisor</div>
                  <div>
                    <i class="ti-email c-grey-400 mR-5"></i>supervisor@gmail.com
                  </div>
                </td>
                <td style="font-size:.95em">
                  <div class="fw-600">The Operations Manager</div>
                  <div>
                    <i class="ti-email c-grey-400 mR-5"></i>manger@gmail.com
                  </div>
                </td>
                <td style="font-size:.95em">
                  <div>
                    08:00am
                    <span class="mL-5" style="font-size:0.8em;">IN</span>
                  </div>
                  <div>
                    04:00pm
                    <span class="mL-5" style="font-size:0.8em;">OUT</span>
                  </div>
                </td>
                <td style="font-size:.99em">
                  <div>
                    <span
                      class="badge badge-pill p-5 bgc-green-100 c-green-800 fw-900 w-100"
                    >PRESENT</span>
                  </div>
                </td>
                <td style="font-size:.95em">
                  <div>
                    <span class="c-orange-500">08:33am</span>
                    <span class="mL-5" style="font-size:0.8em;">IN</span>
                  </div>
                  <div>
                    <span class="c-orange-500">03:45pm</span>
                    <span class="mL-5" style="font-size:0.8em;">OUT</span>
                  </div>
                </td>
                <td style="font-size:.99em">
                  <div class="w-100">
                    <span
                      class="badge badge-pill p-5 bgc-orange-100 c-orange-800 fw-900 w-100"
                    >TARDY</span>
                  </div>
                  <div class="w-100 mT-2">
                    <span
                      class="badge badge-pill p-5 bgc-orange-100 c-orange-800 fw-900 w-100"
                    >UNDERTIME</span>
                  </div>
                </td>
                <td style="font-size:.95em">
                  <div>
                    <span class="c-blue-500">6.2</span>
                    <span class="mL-5" style="font-size:0.8em;">REG</span>
                  </div>
                  <div>
                    <span class>0.0</span>
                    <span class="mL-5" style="font-size:0.8em;">OT</span>
                  </div>
                </td>
                <td style="font-size:.99em">buttons here</td>
              </tr>-->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>


​<style>
.badge-c {
  display: inline-block;
  padding: 0.25em 0.4em;
  font-size: 75%;
  font-weight: 700;
  line-height: 1;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  -webkit-transition: color 0.15s ease-in-out,
    background-color 0.15s ease-in-out, border-color 0.15s ease-in-out,
    -webkit-box-shadow 0.15s ease-in-out;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
    border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
    border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
    border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out,
    -webkit-box-shadow 0.15s ease-in-out;
}
</style>

<script>
import { BasicSelect } from "vue-search-select";
import { ModelSelect } from "vue-search-select";
export default {
  components: {
    BasicSelect,
    ModelSelect
  },
  props: ["userId"],
  mounted() {},
  created() {
    this.fetchTodaysTable();
  },
  data() {
    return {
      user_id: this.userId,
      config: {
        table_name: "Today's Activity",
        code: "todays_activity",
        tabs: [
          { tab_name: "All" },
          { tab_name: "Scheduled" },
          { tab_name: "Present" },
          { tab_name: "Tardy" },
          { tab_name: "Vacation" },
          { tab_name: "Off Duty" }
        ],
        selected_tab: 0, //index based,
        data: {
          all: [],
          scheduled: [],
          present: [],
          tardy: [],
          vacation: [],
          off_duty: []
        }
      },
      table: {
        data: []
      }
    };
  },
  methods: {
    fetchTodaysTable: function() {
      let pageurl = "/api/v1/schedules/work";
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          this.table.data = res.meta.agent_schedules;
          let temp = res.meta.agent_schedules;
          // this.config.data.present = temp.filter(function(present){ returnpresent.schedule});
        })
        .catch(err => {
          console.log(err);
        });
    }
  }
};
</script>