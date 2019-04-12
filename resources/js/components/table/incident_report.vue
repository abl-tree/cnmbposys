
<template>
  <div class="w-100">
    <div class="bd bgc-white p-20 mY-20">
      <div class="layers">
        <div class="layer w-100 mB-10">
          <div class="peers">
            <div class="peer peer-greed">
              <h6 class="lh-100">Incident Reports</h6>
            </div>
            <div class="peer">
              <div class="btn-group" role="group">
                <button
                  v-for="(tabs,index) in tableTab"
                  v-bind:key="tabs.id"
                  type="button"
                  class="fsz-xs btn bgc-white bdrs-2 mR-3 cur-p"
                  :class="[tabs.selected?'bgc-grey-200':'bgc-white']"
                  @click="selectTab(index)"
                >
                  <small>{{ tabs.label}}</small>
                </button>
              </div>
            </div>
            <div class="peer mL-10">
              <button
                class="btn bdrs-50p p-5 lh-0"
                @click="
                  fetchSelectOptions(endpoints.select.child_list,'incident_report','child_list'),
                  fetchSelectOptions(endpoints.select.sanction_level,'incident_report','sanction_level'),
                  fetchSelectOptions(endpoints.select.sanction_type,'incident_report','sanction_type'),
                  showModal('incident_report')"
              >
                <i class="ti-plus"></i>
              </button>
            </div>
          </div>n
        </div>
        <div class="text-center">
          <!-- <h1 v-if="tabs.selected">{{index+" "+tabs.label}}</h1> -->
          <!-- ul -->
          <div v-for="tabs in tableTab" :key="tabs.id" class="layer w-100">
            <div class="row" v-if="isEmpty(tabs.data)">
              <i
                v-show="table[tabs.tableName].fetch_status=='fetching'"
                style="font-size:.7em;"
              >Fetching data...</i>
              <i
                v-show="table[tabs.tableName].fetch_status=='fetched'"
                style="font-size:.7em;"
              >Nothing to display...</i>
            </div>
            <div class="layer w-100 fxg-1 pos-r" style="overflow-y:auto;height:100vh">
              <div>
                <div
                  v-for="items in tabs.data"
                  :key="items.id"
                  class="email-list-item peers fxw-nw p-20 bdB bgcH-grey-100 cur-p"
                >
                  <h6>{{(tabs.label=='ISSUED'?items.filedby.full_name:items.full_name)}}</h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- modal -->
    <!-- Incident Report Response Modal -->
    <modal
      name="incident_report_response"
      :pivotY="0.2"
      :scrollable="true"
      width="900px"
      height="auto"
    >
      <div class="layer">
        <div class="e-modal-header bd">
          <h5 style="margin-bottom:0px">Incident Report Response</h5>
        </div>
        <div class="w-100 p-15 pT-80" style>
          <div class="container">
            <div class="row">
              <div class="col">
                <div class="layer p-0">
                  <!-- level -->
                  <span
                    class="badge badge-pill badge-primary"
                  >{{this.form.incident_report_response.sanction.level}}</span>
                  <!-- type -->
                  <span
                    class="badge badge-pill badge-dark"
                  >{{this.form.incident_report_response.sanction.type}}</span>
                </div>
                <div class="layer bd mT-5 pY-20 pX-15">
                  To:
                  <h6>{{this.form.incident_report_response.received_by}},</h6>
                  <br>
                  <br>
                  <!-- message content -->
                  <div class="cntent">
                    <pre class="ir_description">{{this.form.incident_report_response.ir_description}}</pre>
                  </div>
                  <br>
                  <h6>{{this.form.incident_report_response.filed_by}}</h6>
                </div>

                <div
                  class="alert-danger p-20 m-10"
                >You have 24hours to submit a response for this report. Refusal of response will be penalize.</div>
              </div>
              <div class="col" style="padding-top:25px">
                <textarea
                  name
                  id
                  class="form-control"
                  rows="15"
                  v-model="form.incident_report_response.response"
                ></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="e-modal-footer bd">
          <div style="text-align:right">
            <button class="btn btn-secondary" @click="hideModal('incident_report_response')">Cancel</button>
            <button
              class="btn btn-danger"
              @click="(form.sanction_level.level!=''&&form.sanction_level.description!='' ? 
                      store(
                        {
                          user_response_id: userId,
                          commitment: form.incident_report_response.response
                        },
                        'update',
                        'incident_report_response'
                            ) :
                      formValidationError())"
            >Confirm</button>
          </div>
        </div>
      </div>
    </modal>
    <!-- Incident Report Modal -->
    <modal name="incident_report" :pivotY="0.2" :scrollable="true" height="auto">
      <div class="layer">
        <div class="e-modal-header bd">
          <h5 style="margin-bottom:0px">Incident Report</h5>
        </div>
        <div class="w-100 p-15 pT-80" style>
          <div class="container">
            <form action>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="exampleFormControlSelect1">To:</label>
                    <model-select
                      placeholder="Names"
                      :options="form.incident_report.select_option.child_list"
                      v-model="form.incident_report.selected.child_list"
                    ></model-select>
                  </div>
                </div>
              </div>
              <div class="row pT-5">
                <div class="col">
                  <label>Sanction Level:</label>
                  <model-select
                    placeholder="Level"
                    :options="form.incident_report.select_option.sanction_level"
                    v-model="form.incident_report.selected.sanction_level"
                  ></model-select>
                </div>
                <div class="col">
                  <label>Sanction Type:</label>
                  <model-select
                    placeholder="Type"
                    :options="form.incident_report.select_option.sanction_type"
                    v-model="form.incident_report.selected.sanction_type"
                  ></model-select>
                </div>
              </div>
              <div class="row pT-15">
                <div class="col">
                  <label>Report Description</label>
                  <textarea name id cols="74" rows="5" v-model="form.incident_report.textarea"></textarea>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="e-modal-footer bd">
          <div style="text-align:right">
            <button class="btn btn-secondary" @click="hideModal('incident_report')">Cancel</button>
            <button class="btn btn-danger">Confirm</button>
          </div>
        </div>
      </div>
    </modal>
  </div>
</template>


​<style>
</style>

<script>
export default {
  props: ["userId"],
  mounted() {
    this.initTable();
  },
  created() {},
  data() {
    return {
      user_id: this.userId,
      tableTab: [
        {
          tableName: "issued_incident_report",
          label: "ISSUED",
          selected: true,
          data: [],
          endpoint: {
            retreive: "/api/v1/reports/user_filed_ir/"
          }
        },
        {
          tableName: "received_incident_report",
          label: "RECEIVED",
          selected: false,
          data: [],
          endpoint: {
            retreive: "/api/v1/reports/user/"
          }
        }
      ]
    };
  },
  methods: {
    selectTab: function(index) {
      console.log("selected index is " + index);
      this.tableTab.forEach(function(v, i) {
        v.selected = false;
      });
      this.tableTab[index].selected = true;
    },
    initTable: function() {
      for (let l = 0; l < this.tableTab.length; l++) {
        this.endpoints.table[this.tableTab[l].tableName] =
          this.tableTab[l].endpoint.retreive + this.user_id;
        this.fetchTableObject(this.tableTab[l].tableName);
        this.tableTab[l] = this.table[this.tableTab[l].tableName].reports;
      }
      console.log(this.tableTab);
    }
  }
};
</script>