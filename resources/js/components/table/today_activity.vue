
<template>
  <div id="parent" class="bd bgc-white">
    <div class="layers">
      <div class="layer w-100 pX-20 pT-20 pB-10">
        <h6 class="lh-1">{{config.table_name}}</h6>
      </div>
      <div class="p-5 pX-30 layer w-100">
        <div class="row">
          <div class="col-md-6">
            <!-- <div class="input-group">
              <div class="input-group-prepend mR-5">
                <date-time-picker
                  v-if="config.filter.date.option==1"
                  class="s-modal"
                  :style="'width:300px'"
                  v-model="config.filter.date.value"
                  range-mode
                  :no-label="true"
                  overlay-background
                  color="red"
                  format="YYYY-MM-DD"
                  formatted="ddd D MMM YYYY"
                  @input="fetchReportsTable"
                />
                <select
                  v-else-if="config.filter.date.option==2"
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
                  v-model="config.filter.date.option"
                >
                  <option value="1">Range</option>
                  <option value="2" disabled>Cutoff</option>
                </select>
              </div>
            </div>-->
          </div>
          <div class="col-md-6 text-right">
            <div class="pull-right">
              <div class="input-group">
                <div class="input-group-prepend mR-5">
                  <input
                    type="text"
                    class="p-10"
                    v-model="config.filter.search.value"
                    @input="(config.no_display=false),processFilters(config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ,1)"
                    style="width:300px;border-radius:5px;border:1px solid #ccc"
                    placeholder="Search..."
                  >
                </div>
                <div class="input-group-append">
                  <select
                    class="p-10"
                    v-model="config.filter.search.option"
                    style="border-radius:5px;border:1px solid #ccc"
                  >
                    <option value="1">Agent</option>
                    <option value="2">Team Leader</option>
                    <option value="3">Operations Manager</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="layer pX-20 w-100">
        <div class="row pX-20">
          <div
            v-for="(tab,index) in config.tabs"
            :key="tab.id"
            class="col text-center pX-0 cur-p"
            @click="(config.selected_tab = index),(config.selected_page=1),processFilters(config.filter.search.value=='' ? tab.code : 'search' ,1)"
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
                  @change="(config.selected_page=1),processFilters((config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ),1)"
                >
                  <option value="15">15</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                </select>
              </div>
            </div>
            <div class="col-md-6 text-center"></div>
            <div class="col text-right">
              <span
                v-if="config.loader==true"
                style="width:200px;height:20px;margin-bottom:2px;"
                class="text-right"
              >
                <div class="loader-12 pull-right"></div>
              </span>
              <template v-else>
                <div
                  v-if="config.no_display==false"
                  class="c-grey-600"
                  style="font-size:0.8em;border-style:none"
                >Showing {{ config.filter.data.cur }} of {{ config.filter.data.total_pages }} page/s from {{ config.filter.data.total_result }} records</div>

                <div
                  v-else
                  class="c-grey-600"
                  style="font-size:0.8em;border-style:none"
                >Nothing to display...</div>
              </template>

              <div class="btn-group pull-right" style="max-width: 358px;">
                <button
                  class="fsz-xs btn btn-xs bd bgc-white bdrs-2 mR-3 cur-p"
                  type="button"
                  @click="(config.filter.data.prev!=null?config.selected_page--:''),processFilters((config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
                  :disabled="config.filter.data.prev == null"
                >
                  <i class="ti-angle-left"></i>
                </button>
                <button
                  class="fsz-xs btn btn-xs bgc-white bd bdrs-2 mR-3 cur-p"
                  type="button"
                  @click="(config.filter.data.next!=null?config.selected_page++:''),processFilters((config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
                  :disabled="config.filter.data.next == null"
                >
                  <i class="ti-angle-right"></i>
                </button>
                <select
                  style="width:50px"
                  class="p-2 pY-5"
                  v-model="config.selected_page"
                  @change="processFilters((config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
                >
                  <option v-for="n in config.filter.data.total_pages" :key="n.id" :value="n">{{n}}</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="table-responsive pX-20 pB-20" style="height:500px;">
          <table class="table">
            <thead>
              <tr style="position:relative">
                <th class="bdwT-0">
                  Agent
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="(config.filter.sort.by='agent'),(config.filter.sort.order['agent'] = !config.filter.sort.order['agent']),processFilters((config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
                    ></span>
                  </span>
                </th>
                <th class="bdwT-0">
                  Team Leader
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="(config.filter.sort.by='team_leader'),(config.filter.sort.order['team_leader'] = !config.filter.sort.order['team_leader']),processFilters((config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
                    ></span>
                  </span>
                </th>
                <th class="bdwT-0">
                  Operations Manager
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="(config.filter.sort.by='operations_manager'),(config.filter.sort.order['operations_manager'] = !config.filter.sort.order['operations_manager']),processFilters((config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
                    ></span>
                  </span>
                </th>
                <th class="bdwT-0 text-center">
                  Schedule
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="(config.filter.sort.by='schedule'),(config.filter.sort.order['schedule'] = !config.filter.sort.order['schedule']),processFilters((config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
                    ></span>
                  </span>
                </th>
                <th class="bdwT-0 text-center" data-toggle="tooltip" title="Scheduled Duration">SD</th>
                <th class="bdwT-0 text-center">
                  Attendance
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="(config.filter.sort.by='attendance'),(config.filter.sort.order['attendance'] = !config.filter.sort.order['attendance']),processFilters((config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
                    ></span>
                  </span>
                </th>
                <th class="bdwT-0 text-center">Log</th>
                <th class="bdwT-0 text-center">Log Status</th>
                <th class="bdwT-0 text-center">
                  <span data-toggle="tooltip" title="Break Duration">BD</span>
                </th>
              </tr>
            </thead>
            <tbody>
              <template v-for="datum in config.filter.data.data">
                <tr :key="datum.id">
                  <td style="font-size:.95em">
                    <div>
                      <div class="fw-600">
                        <template>
                          <span
                            :class="component.table.td.badges.attendance[strToLower(datum.attendance)].statusColor"
                            class="mR-5 text-secondary fsz-xs ti-user"
                            style="cursor:pointer;"
                            data-toogle="tooltip"
                            :title="datum.attendance=='leave'?datum.schedule.title.title:component.table.td.badges.attendance[strToLower(datum.attendance)].label"
                          ></span>
                          <!-- &#9900; -->
                        </template>
                        {{datum.info.full_name}}
                      </div>
                      <div>
                        <i class="ti-email c-grey-400 mR-5"></i>
                        <a
                          v-if="!isEmpty(datum.info.email)"
                          :href="'https://mail.google.com/mail/u/0/?view=cm&fs=1&to='+datum.info.email+'&tf=1'"
                        >{{split(datum.info.email,'@',0)}}</a>
                      </div>
                      <div class="c-grey-700">@cnmsolutions.net</div>
                    </div>
                  </td>
                  <td-personnel :personnel="datum.info.tl"></td-personnel>
                  <td-personnel :personnel="datum.info.om"></td-personnel>
                  <td-schedule :schedule="datum.schedule"></td-schedule>
                  <td-regular-hour-duration :schedule="datum.schedule"></td-regular-hour-duration>
                  <td-attendance :attendance="datum.attendance" :schedule="datum.schedule"></td-attendance>
                  <td-attendance-log :attendance="datum.attendance" :schedule="datum.schedule"></td-attendance-log>
                  <td style="font-size:.99em">
                    <div>
                      <template v-if="isEmpty(datum.schedule)">
                        <div>
                          <span
                            class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100"
                          >NO LOGS</span>
                        </div>
                      </template>
                      <template v-else>
                        <template v-if="datum.schedule.title_id==1">
                          <template v-if="datum.schedule.is_present==1">
                            <template
                              v-if="!ifReady(datum.schedule.time_in,datum.schedule.start_event)"
                            >
                              <div>
                                <span
                                  class="badge badge-pill p-5 bgc-orange-200 c-orange-800 fw-900 w-100"
                                >TARDY</span>
                              </div>
                            </template>
                            <template v-else>
                              <div>
                                <span
                                  class="badge badge-pill p-5 bgc-green-200 c-green-800 fw-900 w-100"
                                >READY</span>
                              </div>
                            </template>
                          </template>
                          <template v-else>
                            <div>
                              <span
                                class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100"
                              >NO LOGS</span>
                            </div>
                          </template>
                        </template>
                        <template
                          v-else-if="datum.schedule.title_id>1 && datum.schedule.title_id<8"
                        >
                          <div>
                            <span
                              class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100"
                            >NO LOGS</span>
                          </div>
                        </template>
                      </template>
                    </div>
                  </td>
                  <td-break-duration :schedule="datum.schedule"></td-break-duration>
                </tr>
              </template>
              <!-- LOADER -->
              <template v-if="config.loader">
                <tr-loader v-for="d in 5" :key="d.id" :tablename="config.code"></tr-loader>
              </template>
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
  props: ["userId"],
  mounted() {
    this.fetchTodaysTable();
    this.$nextTick(() => {
      this.tablemount = true;
    });
  },
  created() {},
  data() {
    return {
      user_id: this.userId,
      tablemount: false,
      config: {
        loader: true,
        no_display: false,
        table_name: "Today's Activity",
        code: "todays_activity",

        tabs: [
          { tab_name: "All", code: "all" },
          { tab_name: "Scheduled", code: "scheduled" },
          { tab_name: "Present", code: "present" },
          { tab_name: "No Show", code: "no_show" },
          { tab_name: "Leave", code: "leave" },
          { tab_name: "Off Duty", code: "off_duty" }
        ],
        selected_tab: 0, //index based,
        selected_page: 1,
        searchAgent: "",
        data: {
          all: [],
          scheduled: [],
          present: [],
          no_show: [],
          leave: [],
          off_duty: [],
          search: []
        },
        filter: {
          sort: {
            by: "agent",
            order: {
              agent: true,
              team_leader: true,
              schedule: true,
              attendance: true,
              operations_manager: true
            }
          },
          search: {
            option: 1, //1=agent,2=teamLeader,3=OperationsManager
            value: ""
          },
          data: [],
          paginate: {
            page: 1,
            perpage: 15
          },
          no_records: 15
        }
      },
      table: {
        data: []
      }
    };
  },
  methods: {
    fetchTodaysTable: function() {
      let pageurl = "/api/v1/schedules/work/today";
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          this.table.data = res.meta.agent_schedules;
          var obj = [];
          for (var l = 0; l < res.meta.agent_schedules.length; l++) {
            var v = res.meta.agent_schedules[l];
            var tmp = [];
            if (!this.isEmpty(v.schedule) && v.schedule[0].title_id < 9) {
              tmp.push(this.extractSchedule(v, v.schedule[0]));
            }
            if (!this.isEmpty(v.schedule) && v.schedule[0].title_id > 8) {
              tmp.push(this.extractSchedule(v, []));
            } else if (this.isEmpty(v.schedule)) {
              tmp.push(this.extractSchedule(v, []));
            }
            obj.push(tmp);
          }
          obj = [...new Set([].concat(...obj.map(a => a)))];
          // console.log(obj);
          this.config.data.present = obj.filter(this.getPresent);
          this.config.data.no_show = obj.filter(this.getNoShow);
          this.config.data.scheduled = obj.filter(this.getScheduled);
          this.config.data.leave = obj.filter(this.getLeave);
          this.config.data.off_duty = obj.filter(this.getNoSchedule);
          this.config.data.all = obj;
          let temp = res.meta.agent_schedules;

          this.processFilters(
            this.config.tabs[this.config.selected_tab].code,
            1
          );
        })
        .catch(err => {
          console.log(err);
        });
    },
    ifReady: function(time_in, sched_in) {
      if (new Date(time_in) > new Date(sched_in)) {
        return false;
      } else if (new Date(time_in) < new Date(sched_in)) {
        return true;
      } else {
        return true;
      }
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
      this.config.loader = false;
      if (this.config.filter.data.total_result == 0) {
        this.config.no_display = true;
      }
    },
    processFilters: function(tabCode, page) {
      if (this.config.filter.search.value != "") {
        this.searchBy();
      }
      this.paginate(
        this.columnSort(this.config.data[tabCode]),
        page,
        this.config.filter.no_records
      );
    },
    searchBy: function() {
      if (this.config.filter.search.option == 1) {
        // agent
        this.config.data.search = this.config.data[
          this.config.tabs[this.config.selected_tab].code
        ].filter(this.getAgentSearch);
      } else if (this.config.filter.search.option == 2) {
        // tl
        this.config.data.search = this.config.data[
          this.config.tabs[this.config.selected_tab].code
        ].filter(this.getTeamLeaderSearch);
      } else if (this.config.filter.search.option == 3) {
        // om
        this.config.data.search = this.config.data[
          this.config.tabs[this.config.selected_tab].code
        ].filter(this.getOperationsManagerSearch);
      }
    },
    getAgentSearch: function(index) {
      return index.info.full_name
        .trim()
        .toLowerCase()
        .includes(this.config.filter.search.value.trim().toLowerCase());
    },
    getTeamLeaderSearch: function(index) {
      return index.info.tl.full_name
        .trim()
        .toLowerCase()
        .includes(this.config.filter.search.value.trim().toLowerCase());
    },
    getOperationsManagerSearch: function(index) {
      return index.info.om.full_name
        .trim()
        .toLowerCase()
        .includes(this.config.filter.search.value.trim().toLowerCase());
    },
    columnSort: function(obj) {
      var result = [];
      if (this.config.filter.sort.order[this.config.filter.sort.by]) {
        result = obj.sort(this.asc);
      } else {
        result = obj.sort(this.desc);
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
      switch (this.config.filter.sort.by) {
        case "agent":
          // console.log(a);
          // console.log(b);
          nameA = a.info.full_name.toLowerCase();
          nameB = b.info.full_name.toLowerCase();
          break;
        case "operations_manager":
          nameA = b.info.om.full_name.toLowerCase();
          nameB = a.info.om.full_name.toLowerCase();
          break;
        case "team_leader":
          nameA = b.info.tl.full_name.toLowerCase();
          nameB = a.info.tl.full_name.toLowerCase();
          break;
        case "schedule":
          nameA = b.schedule.start_event;
          nameB = a.schedule.start_event;
          break;
        case "attendance":
          nameA = b.attendance;
          nameB = a.attendance;
          break;
      }
      return { a: nameA, b: nameB };
    },
    hasSchedule: function(work) {
      return !this.isEmpty(work.schedule);
    },
    workSchedule: function(work) {
      return work.schedule.title_id < 3;
    },
    leaveSchedule: function(work) {
      return work.schedule.title_id > 2 && work.schedule.title_id < 9;
    },
    isPresent: function(work) {
      return work.schedule.is_present == 1;
    },
    getLeave: function(work) {
      return this.hasSchedule(work) && this.leaveSchedule(work);
    },
    getPresent: function(work) {
      return (
        this.hasSchedule(work) && this.workSchedule(work) && isPresent(work)
      );
    },
    getScheduled: function(work) {
      return this.hasSchedule(work) && this.workSchedule(work);
    },
    getNoShow: function(work) {
      return (
        this.hasSchedule(work) &&
        this.workSchedule(work) &&
        !this.isPresent(work)
      );
    },
    getPresent: function(work) {
      return (
        this.hasSchedule(work) &&
        this.workSchedule(work) &&
        this.isPresent(work)
      );
    },
    getNoSchedule: function(work) {
      return !this.hasSchedule(work);
    },
    getAttendance: function(schedule) {
      let result;
      if (!this.isEmpty(schedule)) {
        if (schedule.title_id < 3) {
          if (schedule.is_present == 1) {
            result = "present";
          } else {
            result = "no_show";
          }
        } else if (schedule.title_id > 2 && schedule.title_id < 9) {
          result = "leave";
        }
      } else {
        result = "off_duty";
      }
      return result;
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
      if (!this.isEmpty(schedule)) {
        tmp.attendance = this.getAttendance(schedule);
      } else {
        tmp.attendance = "off_duty";
      }
      return tmp;
    }
  }
};
</script>