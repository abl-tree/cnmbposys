
<template>
  <!-- #Sales Report ==================== -->

  <div id="parent" class="bd bgc-white">
    <div class="layers">
      <div class="layer w-100 pX-20 pT-20 pB-10">
        <h6 class="lh-1">{{config.table_name}}</h6>
      </div>
      <div class="p-5 pX-30 layer w-100">
        <div class="row">
          <div class="col-md-6">
            <div class="input-group">
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
            </div>
          </div>
          <div class="col-md-6 text-right">
            <div class="pull-right">
              <div class="input-group">
                <div class="input-group-prepend mR-5">
                  <input
                    type="text"
                    class="p-10"
                    v-model="config.filter.search.value"
                    @input="(searchBy()),(config.no_display=false),processFilters(config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ,1)"
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
                    <option value="2" disabled>Cluster</option>
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
            @click="(config.selected_tab = index),(config.selected_page=1),processFilters(tab.code,1)"
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
                  Cluster
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="(config.filter.sort.by='cluster'),(config.filter.sort.order['cluster'] = !config.filter.sort.order['cluster']),processFilters((config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
                    ></span>
                  </span>
                </th>
                <th class="bdwT-0 text-center">Schedule</th>
                <th class="bdwT-0 text-center" data-toggle="tooltip" title="Scheduled Duration">SD</th>
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
              <template
                v-for="(datum,index) in config.filter.data.data"
                v-if="config.loader==false"
              >
                <tr :key="datum.id">
                  <td-personnel
                    :personnel="{full_name:datum.info.full_name,email:datum.info.email,}"
                  ></td-personnel>
                  <td-personnel :personnel="datum.info.om"></td-personnel>
                  <td-schedule
                    :schedule="!isEmpty(datum.schedule)?{start:datum.schedule.start_event,end:datum.schedule.end_event}:null"
                  ></td-schedule>
                  <td-regular-hour-duration :schedule="datum.schedule"></td-regular-hour-duration>
                  <td-attendance :attendance="datum.attendance"></td-attendance>
                  <td-attendance-log :attendance="datum.attendance" :schedule="datum.schedule"></td-attendance-log>
                  <td-rendered-hours :schedule="datum.schedule"></td-rendered-hours>
                  <td-nonbillable-ot :schedule="datum.schedule" :index="index"></td-nonbillable-ot>
                  <b-popover
                    v-if="datum.schedule.overtime.second>0"
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
                    <b-button variant="danger" size="sm">Bill</b-button>
                  </b-popover>
                  <td-break-duration :schedule="datum.schedule"></td-break-duration>
                  <td-billable-hours :schedule="datum.schedule"></td-billable-hours>
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

.loader-12 {
  height: 12px;
  width: 100%;
  position: relative;
  overflow: hidden;
  background-color: #bdbdbd;
}

.loader-12:before {
  display: block;
  position: absolute;
  content: "";
  left: -200px;
  width: 200px;
  height: 12px;
  background-color: #ababab;
  animation: loading 2s linear infinite;
}
@keyframes loading {
  from {
    left: -200px;
    width: 30%;
  }

  50% {
    width: 30%;
  }

  70% {
    width: 70%;
  }

  80% {
    left: 50%;
  }

  95% {
    left: 120%;
  }

  to {
    left: 100%;
  }
}
</style>

<script>
import { BasicSelect } from "vue-search-select";
import { ModelSelect } from "vue-search-select";
import VueTimepicker from "vuejs-timepicker";
import moment from "moment";
export default {
  components: {
    BasicSelect,
    ModelSelect,
    VueTimepicker
  },
  props: ["userId"],
  mounted() {
    // this.fetchReportsTable();
  },
  created() {},
  data() {
    return {
      user_id: this.userId,
      tablemount: false,
      config: {
        overtime: {
          approve: {
            time: {
              HH: "00",
              mm: "00",
              ss: "00"
            }
          }
        },
        loader: true,
        no_display: false,
        table_name: "Work Reports",
        code: "work_reports",
        tabs: [
          { tab_name: "All", code: "all" },
          { tab_name: "Present", code: "present" },
          { tab_name: "Absent", code: "absent" }
        ],
        selected_tab: 0, //index based,
        selected_page: 1,
        data: {
          all: [],
          present: [],
          absent: [],
          search: []
        },
        filter: {
          sort: {
            by: "agent",
            order: {
              agent: true,
              cluster: true
            }
          },
          search: {
            option: 1, //1=agent,2=clusterORoperationsManger
            value: ""
          },
          date: {
            option: 1,
            value: {
              start: moment()
                .subtract(1, "month")
                .startOf("month"),
              end: moment().endOf("month")
            }
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
    fetchReportsTable: function() {
      if (
        this.config.filter.date.value.start != null &&
        this.config.filter.date.value.end != null
      ) {
        this.config.loader = true;
        this.config.no_display = false;
        let pageurl =
          "/api/v1/schedules/work/report?start=" +
          moment(this.config.filter.date.value.start).format("YYYY-MM-DD") +
          "&end=" +
          moment(this.config.filter.date.value.end).format("YYYY-MM-DD");
        fetch(pageurl)
          .then(res => res.json())
          .then(res => {
            this.table.data = res.meta.agent_schedules;
            var obj = [];
            for (var l = 0; l < res.meta.agent_schedules.length; l++) {
              var v = res.meta.agent_schedules[l];
              var tmp = [];
              // console.log(v.schedule);
              if (v.schedule.length !== 0) {
                for (var l1 = 0; l1 < v.schedule.length; l1++) {
                  var v1 = v.schedule[l1];
                  tmp.push(this.extractSchedule(v, v1));
                }
              }
              obj.push(tmp);
            }
            console.log(obj);
            obj = [...new Set([].concat(...obj.map(a => a)))];
            this.config.data.all = obj;
            this.config.data.present = obj.filter(function(present) {
              return present.attendance == "present";
            });
            this.config.data.absent = obj.filter(function(absent) {
              return absent.attendance == "absent";
            });
            this.processFilters(
              this.config.tabs[this.config.selected_tab].code,
              1
            );
            // this.config.loader=false;
          })
          .catch(err => {
            console.log(err);
          });
      }
    },
    calendarFormat: function(date) {
      return moment(date).calendar();
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
      // console.log(this.config.filter.data);
      this.config.loader = false;
      if (this.config.filter.data.total_result == 0) {
        this.config.no_display = true;
      }
    },
    processFilters: function(tabCode, page) {
      this.paginate(
        this.columnSort(this.config.data[tabCode]),
        page,
        this.config.filter.no_records
      );
    },
    searchBy: function() {
      if (this.config.filter.search.option === 1) {
        // agent
        this.config.data.search = this.config.data[
          this.config.tabs[this.config.selected_tab].code
        ].filter(this.getAgentSearch);
      } else if (this.config.filter.search.option === 2) {
        // cluster
        this.config.data.search = this.config.data[
          this.config.tabs[this.config.selected_tab].code
        ].filter(this.getClusterSearch);
      }
    },
    getAgentSearch: function(index) {
      return index.info.full_name
        .trim()
        .toLowerCase()
        .includes(this.config.filter.search.value.trim().toLowerCase());
    },
    getClusterSearch: function(index) {
      return index.info.operations_manager.full_name
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
          nameA = a.info.full_name.toLowerCase();
          nameB = b.info.full_name.toLowerCase();
          break;
        case "cluster":
          nameA = b.info.operations_manager.full_name.toLowerCase();
          nameB = a.info.operations_manager.full_name.toLowerCase();
          break;
      }
      return { a: nameA, b: nameB };
    },
    ifSchedOngoing(start, end) {
      return (
        moment(start).format("YYYY-MM-DD") == moment().format("YYYY-MM-DD") ||
        moment(end).format("YYYY-MM-DD") == moment().format("YYYY-MM-DD")
      );
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
      tmp.attendance = this.getAttendance(schedule);
      return tmp;
    },
    isUpcomingSchedule: function(start) {
      return moment(start).isAfter(moment());
    },
    getAttendance: function(schedule) {
      let result;
      if (schedule.is_present == 1) {
        result = "present";
      } else {
        console.log(schedule.start_event);
        console.log(schedule.end_event);
        if (
          moment().isBetween(
            moment(schedule.start_event),
            moment(schedule.end_event)
          )
        ) {
          result = "no_show";
        } else if (moment(schedule.end_event).isBefore(moment())) {
          result = "absent";
        } else if (moment(schedule.start_event).isAfter(moment())) {
          result = "none";
        }
      }
      return result;
    },
    isBefore: function(date) {
      return moment(date)
        .format("YYYY-MM-DD")
        .isBefore(moment().format("YYYY-MM-DD"));
    }
  }
};
</script>