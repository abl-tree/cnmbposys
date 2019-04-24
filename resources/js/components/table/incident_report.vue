
<template>
  <div class="w-100">
    <div class="bd bgc-white p-20">
      <div class="layers">
        <div class="layer w-100 mB-10">
          <div class="peers">
            <div class="peer peer-greed">
              <h6 class="lh-100">Incident Reports</h6>
            </div>
            <div class="peer mL-10">
              <button
                class="btn bdrs-50p p-5 lh-0"
                @click="
                  fetchSelectOptions(endpoints.select.child_list,'issued_incident_report','child_list'),
                  fetchSelectOptions(endpoints.select.sanction_level,'issued_incident_report','sanction_level'),
                  fetchSelectOptions(endpoints.select.sanction_type,'issued_incident_report','sanction_type'),
                  (form.issued_incident_report.action='create'),
                  showModal('issued_incident_report')"
              >
                <i class="ti-plus"></i>
              </button>
            </div>
          </div>
        </div>
        <div class="layer w-100 pX-20">
          <div class="row">
            <div
              v-for="(tabs,index) in tableTab"
              :key="tabs.id"
              class="col text-center pX-0 cur-p"
              @click="selectedTab=index"
            >
              <span
                class="text-center w-100 pY-10 badge-c"
                :class="selectedTab == index? 'bgc-white c-grey-900 bdL bdR': 'bgc-grey-200 c-grey-600 bd'"
                :style="selectedTab == index? 'border-top: 1px red solid':''"
              >{{tabs.label}}</span>
            </div>
          </div>
        </div>
        <div class="layer w-100">
          <!-- component tab contents -->
          <issued-ir v-if="selectedTab==0" :obj="tableTab[0]" :userId="user_id"></issued-ir>
          <received-ir v-if="selectedTab==1" :obj="tableTab[1]" :userId="user_id"></received-ir>
        </div>
      </div>
    </div>
    <!-- modal -->
    <!-- Incident Report Response Modal -->
    <!-- <modal
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
                  <span
                    class="badge badge-pill badge-primary"
                  >{{this.form.incident_report_response.sanction.level}}</span>
                  <span
                    class="badge badge-pill badge-dark"
                  >{{this.form.incident_report_response.sanction.type}}</span>
                </div>
                <div class="layer bd mT-5 pY-20 pX-15">
                  To:
                  <h6>{{this.form.incident_report_response.received_by}},</h6>
                  <br>
                  <br>
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
    </div>-->
    <!-- </modal> -->
    <!-- Incident Report Modal -->
    <modal name="issued_incident_report" :pivotY="0.2" :scrollable="true" height="auto">
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
                      :options="form.issued_incident_report.select_option.child_list"
                      v-model="form.issued_incident_report.selected.child_list"
                    ></model-select>
                  </div>
                </div>
              </div>
              <div class="row pT-5">
                <div class="col">
                  <label>Sanction Level:</label>
                  <model-select
                    placeholder="Level"
                    :options="form.issued_incident_report.select_option.sanction_level"
                    v-model="form.issued_incident_report.selected.sanction_level"
                  ></model-select>
                </div>
                <div class="col">
                  <label>Sanction Type:</label>
                  <model-select
                    placeholder="Type"
                    :options="form.issued_incident_report.select_option.sanction_type"
                    v-model="form.issued_incident_report.selected.sanction_type"
                  ></model-select>
                </div>
              </div>
              <div class="row pT-15">
                <div class="col">
                  <label>Report Description</label>
                  <textarea
                    name
                    id
                    cols="74"
                    rows="5"
                    v-model="form.issued_incident_report.textarea"
                  ></textarea>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="e-modal-footer bd">
          <div style="text-align:right">
            <button class="btn btn-secondary" @click="hideModal('issued_incident_report')">Cancel</button>
            <button
              class="btn btn-danger"
              @click="(form.issued_incident_report.selected.child_list.value!='' && form.issued_incident_report.selected.sanction_level.value!='' && form.issued_incident_report.selected.sanction_type.value!='' && form.issued_incident_report.textarea!=''  ?
              store(
                {
                  user_reports_id:form.issued_incident_report.selected.child_list.value,
                  filed_by:user_id,
                  description:form.issued_incident_report.textarea,sanction_type_id:form.issued_incident_report.selected.sanction_type.value,sanction_level_id:form.issued_incident_report.selected.sanction_level.value
              },
              form.issued_incident_report.action,
              'issued_incident_report'):formValidationError())"
            >Confirm</button>
          </div>
        </div>
      </div>
    </modal>
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
  mounted() {
    console.log("IR " + this.user_id);
    this.endpoints.select.child_list =
      "/api/v1/reports/select_all_users/" + this.user_id;
    this.endpoints.table.issued_incident_report =
      this.endpoints.table.issued_incident_report + this.user_id;
  },
  created() {},
  data() {
    return {
      user_id: this.userId,
      tableTab: [
        {
          tableName: "issued_incident_report",
          label: "ISSUED",
          data: [],
          endpoint: {
            retreive: "/api/v1/reports/issued_by/"
          }
        },
        {
          tableName: "received_incident_report",
          label: "RECEIVED",
          data: [],
          endpoint: {
            retreive: "/api/v1/reports/issued_to/"
          }
        }
      ],
      selectedTab: 0
    };
  },
  methods: {}
};
</script>