
<template>
  <!-- #Sales Report ==================== -->
  <div id="parent" class="bd bgc-white">
    <div class="layers">
      <div class="layer w-100 p-20">
        <div class="peers">
          <div class="peer peer-greed">
            <h6 class="lh-100">{{config.table_name}}</h6>
          </div>
          <div class="peer mL-10">
            <button
              class="btn bdrs-50p p-5 lh-0"
              data-toggle="tooltip"
              title="Create Incident Report"
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
      <div class="layer pX-20 w-100">
        <div class="row pX-20">
          <div
            v-for="(tab,index) in config.tabs"
            :key="tab.id"
            class="col text-center pX-0 cur-p"
            @click="(config.searchAgent=''),(config.selected_tab = index),(config.selected_page=1),changeTableTab(tab.code,1,config.curSort)"
          >
            <span
              class="text-center w-100 pY-10 badge-c"
              :class="config.selected_tab == index? 'bgc-white c-grey-900 bdL bdR': 'bgc-grey-200 c-grey-600 bd'"
              :style="config.selected_tab == index? 'border-top: 1px red solid':''"
            >{{tab.tab_name}}</span>
          </div>
        </div>
      </div>
      <div class="layer w-100 pX-20 pB-40">
        <div class="pY-10 pX-30">
          <div class="row">
            <div class="col text-left">
              <div>
                <div class="c-grey-600" style="font-size:0.8em">No.of records display</div>
                <select
                  style="width:50px:border-style:none"
                  class="p-2 pY-5 bdrs-5"
                  v-model="config.filter.no_records"
                  @change="(config.selected_page=1),changeTableTab((config.searchAgent=='' ? config.tabs[config.selected_tab].code : 'search' ),1,config.curSort)"
                >
                  <option value="15">15</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                </select>
              </div>
            </div>
            <div class="col-md-6 text-center">
              <div v-if="config.selected_tab==0">
                <div class="c-grey-600" style="font-size:0.8em">Agent Name</div>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Search..."
                  style="border-color:#ccc;border-radius:2px;"
                  v-model="config.searchAgent"
                  @input="searchAgent(),changeTableTab('search',1,config.curSort)"
                >
              </div>
            </div>
            <div class="col text-right">
              <div
                class="c-grey-600"
                style="font-size:0.8em;border-style:none"
              >Showing {{ config.filter.data.cur }} of {{ config.filter.data.total_pages }} page/s from {{ config.filter.data.total_result }} records</div>

              <div class="btn-group pull-right" style="max-width: 358px;">
                <button
                  class="fsz-xs btn btn-xs bd bgc-white bdrs-2 mR-3 cur-p"
                  type="button"
                  @click="(config.filter.data.prev!=null?config.selected_page--:''),changeTableTab((config.searchAgent=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page,config.curSort)"
                  :disabled="config.filter.data.prev == null"
                >
                  <i class="ti-angle-left"></i>
                </button>
                <button
                  class="fsz-xs btn btn-xs bgc-white bd bdrs-2 mR-3 cur-p"
                  type="button"
                  @click="(config.filter.data.next!=null?config.selected_page++:''),changeTableTab((config.searchAgent=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page,config.curSort)"
                  :disabled="config.filter.data.next == null"
                >
                  <i class="ti-angle-right"></i>
                </button>
                <select
                  style="width:50px"
                  class="p-2 pY-5"
                  v-model="config.selected_page"
                  @change="changeTableTab((config.searchAgent=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page,config.curSort)"
                >
                  <option v-for="n in config.filter.data.total_pages" :key="n.id" :value="n">{{n}}</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="table-responsive pX-20 pB-20" style="height:500px;">
          <table class="table" style="table-layout:auto">
            <thead>
              <tr style="position:relative">
                <th class="bdwT-0">Image</th>
                <th class="bdwT-0">
                  Issued to
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="config.sorter.issued_to = !config.sorter.issued_to,config.curSort={column:'issued_to',type:'name'},changeTableTab((config.searchAgent=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page,config.curSort)"
                    ></span>
                  </span>
                </th>
                <th class="bdwT-0">
                  Type
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="config.sorter.type = !config.sorter.type,config.curSort={column:'type',type:'name'},changeTableTab((config.searchAgent=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page,config.curSort)"
                    ></span>
                  </span>
                </th>
                <th class="bdwT-0">
                  Level
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="config.sorter.level = !config.sorter.level,config.curSort={column:'level',type:'name'},changeTableTab((config.searchAgent=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page,config.curSort)"
                    ></span>
                  </span>
                </th>
                <th class="bdwT-0">
                  Date Filed
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="config.sorter.date_filed = !config.sorter.date_filed,config.curSort={column:'date_filed',type:'name'},changeTableTab((config.searchAgent=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page,config.curSort)"
                    ></span>
                  </span>
                </th>
                <th class="bdwT-0">
                  Filed by
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="config.sorter.date_filed = !config.sorter.date_filed,config.curSort={column:'date_filed',type:'name'},changeTableTab((config.searchAgent=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page,config.curSort)"
                    ></span>
                  </span>
                </th>
                <th class="bdwT-0" data-toggle="tooltip" title="Total Incident Reports">TIR</th>
                <th class="bdwT-0">Response</th>
                <th class="bdwT-0">Preview</th>
              </tr>
            </thead>
            <tbody>
              <template v-for="datum in config.filter.data.data">
                <tr :key="datum.id">
                  <td style="font-size:.95em">
                    <img
                      v-if="datum.issued_to.image!=null"
                      class="bdrs-50p w-3r h-3r"
                      :src="datum.issued_to.image"
                    >
                    <img v-else class="bdrs-50p w-3r h-3r" src="/images/nobody.jpg">
                  </td>
                  <td-personnel :personnel="datum.issued_to"></td-personnel>
                  <td style="font-size:.95em">
                    <div
                      v-if="!isEmpty(datum.report_details.sanction_type)"
                    >{{datum.report_details.sanction_type.type_description}}</div>
                    <div class="c-grey-500" v-else>
                      <i>Prerequisite entry must have been deleted.</i>
                    </div>
                  </td>
                  <td style="font-size:.95em">
                    <div
                      v-if="!isEmpty(datum.report_details.sanction_level)"
                    >{{datum.report_details.sanction_level.level_description}}</div>
                    <div class="c-grey-500" v-else>
                      <i>Prerequisite entry must have been deleted.</i>
                    </div>
                  </td>
                  <td style="font-size:.95em">
                    <div>{{split(datum.report_details.created_at.date,'.',0)}}</div>
                  </td>

                  <td-personnel :personnel="datum.issued_by"></td-personnel>

                  <td style="font-size:.95em">
                    <div
                      class="fw-600 badge badge-pill bgc-indigo-100 c-indigo-800 w-100 fsz-xs"
                      v-html="extractTIR(datum.issued_to.id)"
                    ></div>
                  </td>
                  <td style="font-size:.95em">
                    <div v-if="datum.report_details.agent_response==null">
                      <!-- <span class="bdrs-25p p-10 c-red-900">
                    <i
                      class="ti-info"
                      data-toggle="tooltip"
                      title="You are obliged to make a response within 24 hours."
                    ></i>
                      </span>-->
                      <i class="c-grey-500">No response</i>
                    </div>
                    <div v-else>
                      <!-- <span class="badge badge-pill p-10 c-green-900">
                    <i class="ti-check"></i>
                      </span>-->
                      {{datum.report_details.agent_response.created_at}}
                    </div>
                  </td>
                  <td>
                    <button
                      class="btn"
                      @click="setIRdetails(datum),showModal('preview-only-incident-report')"
                    >
                      <span class="ti-eye"></span>
                    </button>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Incident Report Modal -->
    <modal name="issued_incident_report" :pivotY="0.2" :scrollable="true" height="auto">
      <div class="layer">
        <div class="e-modal-header bd">
          <h6 style="margin-bottom:0px">Incident Report</h6>
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
              storeIR(
                {
                  user_reports_id:form.issued_incident_report.selected.child_list.value,
                  filed_by:user_id,
                  description:form.issued_incident_report.textarea,sanction_type_id:form.issued_incident_report.selected.sanction_type.value,sanction_level_id:form.issued_incident_report.selected.sanction_level.value
              },
              form.issued_incident_report.action,
              'issued_incident_report'):formValidationError())"
              :disabled="form.submit_buttons.incident_report"
            >Confirm</button>
          </div>
        </div>
      </div>
    </modal>
    <!-- preview ONLY IR -->

    <modal name="preview-only-incident-report" :pivotY="0.2" :scrollable="true" height="auto">
      <div class="layer p-20 w-100" style="overflow:hidden">
        <table class="w-100">
          <tbody>
            <tr>
              <td width="60px" style="vertical-align:text-top">
                <div>
                  <img
                    v-if="config.ir_details.filed_by.image!=null"
                    class="bdrs-50p w-3r h-3r"
                    :src="config.ir_details.filed_by.image"
                  >
                  <img v-else class="bdrs-50p w-3r h-3r" src="/images/nobody.jpg">
                </div>
              </td>
              <td>
                <div style="font-size:1.25em">
                  <span
                    class="fw-900"
                  >{{config.ir_details.filed_by.id == user_id? 'You ':config.ir_details.filed_by.name+' '}}</span>
                  wrote an incident report to
                  <span
                    class="fw-900"
                  >{{config.ir_details.issued_to.id == user_id? 'You ':config.ir_details.issued_to.name+' '}}</span>
                  regarding
                  <span class="c-blue-300">{{' '+config.ir_details.type}}</span>
                  (
                  <span class="c-blue-300">{{config.ir_details.level}}</span>)
                </div>
                <div
                  v-html="fromNow(config.ir_details.date_filed)"
                  data-toogle="tooltip"
                  :title="split(config.ir_details.date_filed,'.',0)"
                ></div>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="w-100 p-20">{{config.ir_details.description}}</div>

        <div v-if="config.selected_tab==receivedTab()">
          <div class="row pT-15">
            <div class="col">
              <label class="fsz-xs">Response:</label>
              <textarea name id cols="74" rows="5" v-model="config.ir_details.response.message"></textarea>
            </div>
          </div>
        </div>
        <div v-else>
          <span
            v-if="config.ir_details.response.message==''"
            class="alert bgc-orange-200 c-orange-600 text-center w-100"
          >
            <i>No Response</i>
          </span>
          <div v-else class="layer p-20 w-100 bgc-grey-100 bd" style="overflow:hidden">
            <table class="w-100 bgc-grey-100">
              <tbody>
                <tr class="bgc-grey-100">
                  <td width="60px" style="vertical-align:text-top bgc-grey-100">
                    <div class="bgc-grey-100">
                      <img
                        v-if="config.ir_details.issued_to.image!=null"
                        class="bdrs-50p w-3r h-3r"
                        :src="config.ir_details.issued_to.image"
                      >
                      <img v-else class="bdrs-50p w-3r h-3r" src="/images/nobody.jpg">
                    </div>
                  </td>
                  <td class="bgc-grey-100">
                    <div style="font-size:1.25em">
                      <span
                        class="fw-900"
                      >{{config.ir_details.issued_to.id == user_id? 'You ':config.ir_details.issued_to.name+' '}}</span>
                      responded.
                    </div>
                    <div
                      v-html="fromNow(config.ir_details.response.date)"
                      data-toogle="tooltip"
                      :title="split(config.ir_details.response.date,'.',0)"
                    ></div>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="w-100 p-20">{{config.ir_details.response.message}}</div>
          </div>
        </div>
        <div v-if="config.selected_tab==receivedTab()" class="w-100 pT-10">
          <button class="btn btn-xs btn-danger" @click="storeIRresponse">Save</button>
          <button
            class="btn btn-xs btn-secondary"
            @click="hideModal('preview-only-incident-report')"
            :disabled="form.submit_buttons.incident_report_response"
          >Close</button>
        </div>
      </div>
    </modal>
    <profile-preview-modal v-bind:user-profile="this.userId"></profile-preview-modal>
    <notifications group="foo" animation-type="velocity" position="bottom right"/>
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
.table-responsive,
#parent {
   {
    page-break-after: auto;
  }
}
@media print {
  table {
    page-break-after: auto;
  }
  tr {
    page-break-inside: avoid;
    page-break-after: auto;
  }
  td {
    page-break-inside: avoid;
    page-break-after: auto;
  }
  thead {
    display: table-header-group;
  }
  tfoot {
    display: table-footer-group;
  }
}
</style>

<script>
import { BasicSelect } from "vue-search-select";
import { ModelSelect } from "vue-search-select";
import moment from "moment";
export default {
  components: {
    BasicSelect,
    ModelSelect
  },
  props: ["userId", "accessId"],
  mounted() {
    this.fetchIRTable();
    if (this.access_id > 3 && this.access_id != 17) {
      this.config.tabs = this.config.tabs.slice(1, 3);
    } else if (this.access_id == 17) {
      this.config.tabs = this.config.tabs.slice(2, 3);
    }
  },
  created() {},
  data() {
    return {
      user_id: this.userId,
      access_id: this.accessId,
      // access_id: 4,
      tablemount: false,
      config: {
        agentSort: true,
        table_name: "Incident Report",
        code: "incident_report",
        tabs: [
          { tab_name: "All", code: "all" },
          { tab_name: "Issued", code: "issued_by" },
          { tab_name: "Received", code: "issued_to" }
        ],
        curSort: { column: "date_filed", type: "name" },
        sorter: {
          issued_to: true,
          issued_by: true,
          type: true,
          level: true,
          date_filed: true
        },
        ir_details: {
          filed_by: {
            id: "",
            name: "",
            email: "",
            image: ""
          },
          issued_to: {
            id: "",
            name: "",
            email: "",
            image: ""
          },
          id: "",
          date_filed: "",
          type: "",
          level: "",
          tir: "",
          description: "",
          response: {
            id: "",
            date: "",
            message: ""
          },
          save_endpoint: "",
          action: ""
        },
        selected_tab: 0, //index based,
        selected_page: 1,
        searchAgent: "",
        data: {
          all: [],
          issued: [],
          received: [],
          search: []
        },
        filter: {
          data: [],
          paginate: {
            page: 1,
            perpage: 15
          },
          no_records: 15
        }
      },
      form: {
        submit_buttons: {
          incident_report: false,
          incident_report_response: false
        }
      },
      table: {
        data: []
      }
    };
  },
  methods: {
    fetchIRTable: function() {
      let pageurl = "/api/v1/reports";
      let userid = this.user_id;
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          this.table.data = res.meta.all_reports;
          let all_reports = res.meta.all_reports;
          this.config.data.all = [
            ...new Set([].concat(...res.meta.all_reports.map(o => o.reports)))
          ];

          this.config.data.all.forEach(function(v, i) {
            all_reports.filter(function(a) {
              if (v.id == a.id) {
                v.push({ tir: a.count });
              }
            });
          });

          this.config.data.issued_to = this.config.data.all.filter(function(v) {
            return v.issued_to.id == userid;
          });
          this.config.data.issued_by = this.config.data.all.filter(function(v) {
            return v.issued_by.id == userid;
          });
          this.changeTableTab(
            this.config.tabs[this.config.selected_tab].code,
            1,
            this.config.curSort
          );
        })
        .catch(err => {
          console.log(err);
        });
    },
    calendarFormat: function(date) {
      return moment(date).calendar();
    },
    paginate: function(obj, page, per_page) {
      var page = page,
        per_page = per_page,
        offset = (page - 1) * per_page,
        paginatedItems = obj.slice(offset).slice(0, per_page),
        total_pages = Math.ceil(obj.length / per_page);
      // this.local.agents.selected_index = 0;
      this.config.filter.data = {
        data: paginatedItems,
        cur: page,
        prev: page - 1 ? page - 1 : null,
        next: total_pages > page ? page + 1 : null,
        total_result: obj.length,
        total_pages: total_pages
      };
      // console.log(this.config.filter.data);
    },
    getPresent: function(work) {
      return (
        this.hasSchedule(work) && this.workSchedule(work) && isPresent(work)
      );
    },
    changeTableTab: function(tabCode, page, sort) {
      this.paginate(
        this.columnSort(this.config.data[tabCode], sort.column, sort.type),
        page,
        this.config.filter.no_records
      );
    },
    searchAgent: function() {
      var obj = [];
      this.config.data.search = this.table.data.filter(index =>
        index.full_name
          .trim()
          .toLowerCase()
          .includes(this.config.searchAgent.trim().toLowerCase())
      );
    },
    ///column sorter functions START
    columnSort: function(obj, column, type) {
      var result = [];
      if (type == "name") {
        if (this.config.sorter[column]) {
          result = obj.sort(this.asc);
        } else {
          result = obj.sort(this.desc);
        }
      }
      return result;
    },
    desc: function(a, b) {
      let name = this.sortCondition(a, b);
      if (name.a > name.b)
        //sort string ascending
        return -1;
      if (name.a < name.b) return 1;
      return 0;
    },
    asc: function(a, b) {
      let name = this.sortCondition(a, b);
      if (name.a < name.b)
        //sort string ascending
        return -1;
      if (name.a > name.b) return 1;
      return 0;
    },
    sortCondition: function(a, b) {
      let nameA = "",
        nameB = "";
      switch (this.config.curSort.column) {
        case "issued_to":
          nameA = a.issued_to.full_name.toLowerCase();
          nameB = b.issued_to.full_name.toLowerCase();
          break;
        case "issued_by":
          nameA = b.issued_by.full_name.toLowerCase();
          nameB = a.issued_by.full_name.toLowerCase();
          break;
        case "type":
          nameA = b.report_details.sanction_type.type_description.toLowerCase();
          nameB = a.report_details.sanction_type.type_description.toLowerCase();
          break;
        case "level":
          nameA = b.report_details.sanction_level.level_description.toLowerCase();
          nameB = a.report_details.sanction_level.level_description.toLowerCase();
          break;
        case "level":
          nameA = b.report_details.sanction_level.level_description.toLowerCase();
          nameB = a.report_details.sanction_level.level_description.toLowerCase();
          break;
        case "date_filed":
          nameA = b.report_details.created_at.date.toLowerCase();
          nameB = a.report_details.created_at.date.toLowerCase();
          break;
      }
      return { a: nameA, b: nameB };
    },
    ///column sorter functions END
    ///==========================================================
    ///store IR START
    storeIR: function(obj, action, formName) {
      this.form.submit_buttons.incident_report = true;

      let pageurl = this.endpoints[action][formName];
      fetch(pageurl, {
        method: "post",
        body: JSON.stringify(obj),
        headers: {
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          console.log(data);
          this.form.submit_buttons.incident_report = false;

          if (data.code == 500) {
            this.notify("error", action);
          } else {
            console.log(data);
            this.fetchIRTable();
            this.hideModal(formName);
            this.notify("success", action);
            // this.saveLog('succuss', formName, action, data);
          }
        })
        .catch(err => console.log(err));
    },
    ///store IR END
    setIRdetails: function(report) {
      this.config.ir_details.filed_by.name = report.issued_by.full_name;
      this.config.ir_details.filed_by.email = report.issued_by.email;
      this.config.ir_details.filed_by.image = report.issued_by.image;
      this.config.ir_details.filed_by.id = report.issued_by.id;
      this.config.ir_details.issued_to.name = report.issued_to.full_name;
      this.config.ir_details.issued_to.email = report.issued_to.email;
      this.config.ir_details.issued_to.image = report.issued_to.image;
      this.config.ir_details.issued_to.id = report.issued_to.id;
      this.config.ir_details.date_filed = report.report_details.created_at.date;
      this.config.ir_details.description = report.report_details.description;
      this.config.ir_details.id = report.report_details.id;
      this.config.ir_details.type =
        report.report_details.sanction_type.type_description;
      this.config.ir_details.level =
        report.report_details.sanction_level.level_description;
      // this.config.ir_details.tir =
      this.config.ir_details.response.date = this.isEmpty(
        report.report_details.agent_response
      )
        ? ""
        : report.report_details.agent_response.created_at;

      if (this.isEmpty(report.report_details.agent_response)) {
        this.config.ir_details.action = "create";
        // this.config.ir_details.save_endpoint = "/api/v1/reports/user_response"
      } else {
        this.config.ir_details.action = "update";
        // this.config.ir_details.save_endpoint = "/api/v1/reports/update_response"
        this.config.ir_details.response.id =
          report.report_details.agent_response.id;
      }

      this.config.ir_details.response.message = this.isEmpty(
        report.report_details.agent_response
      )
        ? ""
        : report.report_details.agent_response.commitment;
    },
    extractTIR: function(id) {
      return this.table.data.filter(function(a) {
        return a.id === id;
      })[0].count;
    },
    receivedTab: function() {
      let result;
      if (this.access_id < 4) {
        result = 2;
      } else if (this.access_id > 3 && this.access_id != 17) {
        result = 1;
      } else if (this.access_id == 17) {
        result = 0;
      }
      return result;
    },
    storeIRresponse: function() {
      this.form.submit_buttons.incident_report_response = true;
      let pageurl = "/api/v1/reports/user_response";
      fetch(pageurl, {
        method: "post",
        body: JSON.stringify(
          this.config.ir_details.action == "create"
            ? {
                user_response_id: this.config.ir_details.id,
                commitment: this.config.ir_details.response.message,
                auth_id: this.user_id
              }
            : {
                id: this.config.ir_details.response.id,
                user_response_id: this.config.ir_details.id,
                commitment: this.config.ir_details.response.message,
                auth_id: this.user_id
              }
        ),
        headers: {
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          console.log(data);
          this.form.submit_buttons.incident_report_response = false;

          if (data.code == 500) {
            this.notify("error", "update");
          } else {
            console.log(data);
            this.fetchIRTable();
            this.hideModal("preview-only-incident-report");
            this.notify("success", "update");
            // this.saveLog('succuss', formName, action, data);
          }
        })
        .catch(err => console.log(err));
    }
  }
};
</script>