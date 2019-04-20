
<template>
  <!-- #Sales Report ==================== -->
  <div class="bd bgc-white">
    <div class="layers">
      <div class="layer w-100 p-20">
        <h6 class="lh-1">Today's Activity</h6>
      </div>

      <div class="layer p-20 w-100">
        <span v-for="(tab,index) in config.tabs" :key="tab.id">
          <input
            :id="config.code+index"
            type="radio"
            class="input-table-tabs"
            :checked="index==0"
            :name="config.code"
          >
          <label :for="config.code+index" class="label-table-tabs">{{tab.tab_name}}</label>
        </span>
      </div>
      <div class="layer w-100 pX-20">
        <div class="input-group">
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
        </div>
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
                <th class="bdwT-0">Billable</th>
                <th class="bdwT-0">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="font-size:.95em">
                  <div class="fw-600">
                    Emmanuel James Eng Lajom
                    <span
                      class="mL-5 text-success fsz-xs"
                      style="cursor:pointer;"
                      data-toogle="tooltip"
                      title="Working"
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
                <td style="font-size:.95em">
                  <div>
                    <span class="c-blue-500">8.0</span>
                    <span class="mL-5" style="font-size:0.8em;">REG</span>
                  </div>
                  <div>
                    <span class="c-light-blue-500">1.0</span>
                    <span class="mL-5" style="font-size:0.8em;">OT</span>
                  </div>
                </td>
                <td style="font-size:.99em">buttons here</td>
              </tr>
              <tr>
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
                    <span class="badge badge-pill p-5 bgc-grey-100 c-grey-800 fw-900 w-100">ABSENT</span>
                  </div>
                </td>
                <td style="font-size:.95em">
                  <div>
                    <span class="badge badge-pill p-5 bgc-grey-100 c-grey-800 fw-900 w-100">NULL</span>
                  </div>
                </td>
                <td style="font-size:.99em">
                  <div class="w-100">
                    <span class="badge badge-pill p-5 bgc-grey-100 c-grey-800 fw-900 w-100">NULL</span>
                  </div>
                  <div class="w-100 mT-2">
                    <span class="badge badge-pill p-5 bgc-grey-100 c-grey-800 fw-900 w-100">NULL</span>
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
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>


​<style>
.input-table-tabs {
  display: none;
}

.label-table-tabs {
  display: inline-block;
  margin: 0 0 -1px;
  padding: 5px 10px;
  font-weight: 600;
  text-align: center;
  color: #abc;
  border: 1px solid transparent;
}

.label-table-tabs:before {
  font-family: fontawesome;
  font-weight: normal;
  margin-right: 10px;
}

/* label[for*="1"]:before {
  content: "\f1cb";
}
label[for*="2"]:before {
  content: "\f17d";
}
label[for*="3"]:before {
  content: "\f16c";
}
label[for*="4"]:before {
  content: "\f171";
} */

.label-table-tabs:hover {
  color: #789;
  cursor: pointer;
}

.input-table-tabs:checked + .label-table-tabs {
  color: #0af;
  border: 1px solid #abc;
  border-top: 2px solid #0af;
  border-bottom: 1px solid #fff;
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
  created() {},
  data() {
    return {
      user_id: this.userId,
      config: {
        table_name: "Today's Activity",
        code: "todays_activity",
        tabs: [
          { tab_name: "All", url: "" },
          { tab_name: "Present", url: "" },
          { tab_name: "Absent", url: "" },
          { tab_name: "Vacation", url: "" },
          { tab_name: "Off Duty", url: "" }
        ]
      }
    };
  },
  methods: {}
};
</script>