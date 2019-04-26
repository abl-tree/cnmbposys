
<template>
  <!-- #Sales Report ==================== -->
  <div id="parent" class="bd bgc-white">
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
            @click="(config.selected_tab = index),(config.selected_page=1),changeTableTab(tab.code,1)"
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
        <!-- <div class="input-group pY-10 pX-30">
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
        </div> -->
        <div class="pY-10 pX-30">
          <div class="row">
            <div class='col text-left'>
              <div>
                <div class='c-grey-600' style='font-size:0.8em'>No.of records display</div>
                <select style='width:50px' class="p-2 pY-5"
                  v-model="config.filter.no_records"
                  @change="(config.selected_page=1),changeTableTab(config.tabs[config.selected_tab].code,1)"
                >
                  <option value="15">15</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                </select>
              </div>
              <div>
              </div>
            </div>
            <div class='col text-right'>
              <div class="c-grey-600" style='font-size:0.8em'>Showing {{ config.filter.data.cur }} of {{ config.filter.data.total_pages }} page/s from {{ config.filter.data.total_result }} records</div>

              <div class="btn-group pull-right"  style="max-width: 358px;">
                <button class="fsz-xs btn btn-xs bd bgc-white bdrs-2 mR-3 cur-p" type="button"
                @click="(config.filter.data.prev!=null?config.selected_page--:''),changeTableTab(config.tabs[config.selected_tab].code,config.selected_page)"
                :disabled="config.filter.data.prev == null"><i class="ti-angle-left"></i></button>
                <button class="fsz-xs btn btn-xs bgc-white bd bdrs-2 mR-3 cur-p" type="button"
                @click="(config.filter.data.next!=null?config.selected_page++:''),changeTableTab(config.tabs[config.selected_tab].code,config.selected_page)"
                :disabled="config.filter.data.next == null"><i class="ti-angle-right"></i></button>
                <select style='width:50px' class="p-2 pY-5"
                  v-model="config.selected_page"
                  @change="changeTableTab(config.tabs[config.selected_tab].code,config.selected_page)"
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
              <tr style='position:relative'>
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
            <tbody v-if="tablemount==true">
              <template  v-for="datum in config.filter.data.data" >
                <tr :key="datum.id">
                <td style="font-size:.95em">
                  <div>
                    <div class="fw-600">
                      <i class="ti-user c-grey-400 mR-5"></i>{{datum.full_name}}
                      <template v-if='isEmpty(datum.schedule)'>
                        <span
                          class="mL-5 text-secondary fsz-xs"
                          style="cursor:pointer;"
                          data-toogle="tooltip"
                          title="Off-Duty"
                        >&#9900;</span>
                      </template>
                      <template v-else>
                        <template v-if="datum.schedule[0].is_present==1">
                              <span
                            class="mL-5 fsz-xs"
                            :class="datum.schedule[0].is_working==1?'c-green-400':'c-blue-400'"
                            style="cursor:pointer;"
                            data-toogle="tooltip"
                            :title="datum.schedule[0].is_working==1?'WORKING':'BREAK'"
                          >&#9679;</span>
                          </template>
                        <template v-else>
                          <span
                          class="mL-5 text-secondary fsz-xs"
                          style="cursor:pointer;"
                          data-toogle="tooltip"
                          title="NO SHOW"
                        >&#9679;</span>
                        </template>
                      </template>
                    </div>
                    <div>
                      <i class="ti-email c-grey-400 mR-5"></i>{{split(datum.email,'@',0)}}
                    </div>
                    <div class="c-light-blue-400">@cnmsolutions.net</div>
                  </div>
                </td>
                <td style="font-size:.95em">
                  <div>
                    <div class="fw-600">
                      <i class="ti-user c-grey-400 mR-5"></i>{{datum.team_leader?datum.team_leader:'not assigned'}}
                    </div>
                    <div>
                      <i class="ti-email c-grey-400 mR-5"></i>{{datum.team_leader?datum.team_leader:'null'}}
                    </div>
                    <div class="c-light-blue-400">@cnmsolutions.net</div>
                  </div>
                </td>
                <td style="font-size:.95em">
                  <div>
                    <div class="fw-600">
                      <i class="ti-user c-grey-400 mR-5"></i>{{datum.operations_manager?datum.operations_manager:'not assigned'}}
                    </div>
                    <div>
                      <i class="ti-email c-grey-400 mR-5"></i>{{datum.operations_manager?datum.operations_manager:'null'}}
                    </div>
                    <div class="c-light-blue-400">@cnmsolutions.net</div>
                  </div>
                </td>
                <td style="font-size:.95em">
                  <div>
                    <div  v-if="isEmpty(datum.schedule)">
                      <span
                        class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100"
                      >NO SCHEDULE</span>
                    </div>
                    <div  v-else>
                      <span
                        v-if="datum.schedule[0].title_id>1 && datum.schedule[0].title_id<8"
                        class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100"
                      >NO SCHEDULE</span>
                      <div v-else>
                        <div>
                          {{calendarFormat(datum.schedule[0].start_event)}}
                          <span class="mL-5" style="font-size:0.8em;">IN</span>
                        </div>
                        <div>
                          {{calendarFormat(datum.schedule[0].end_event)}}
                          <span class="mL-5" style="font-size:0.8em;">OUT</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
                <td style="font-size:.99em">
                  <div>
                    <template v-if="isEmpty(datum.schedule)">
                      <div>
                        <span class="badge badge-pill p-5 bgc-grey-400 c-grey-800 fw-900 w-100">OFF-DUTY</span>
                      </div>
                    </template>
                    <template v-else>
                      <template v-if="datum.schedule[0].title_id==1">
                        <template v-if="datum.schedule[0].is_present==1">
                          <div>
                            <span class="badge badge-pill p-5 bgc-green-200 c-green-800 fw-900 w-100">PRESENT</span>
                          </div>
                        </template>
                        <template v-else>
                          <div>
                            <span class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100">NO SHOW</span>
                          </div>
                        </template>
                      </template>
                      <template v-else-if="datum.schedule[0].title_id>1 && datum.schedule[0].title_id<8">
                        <div>
                          <span class="badge badge-pill p-5 bgc-yellow-200 c-yellow-800 fw-900 w-100">{{datum.schedule[0].title.title.toUpperCase()}}</span>
                        </div>
                      </template>
                    </template>
                  </div>
                </td>
                <td style="font-size:.95em">
                  <div>
                    <template v-if="isEmpty(datum.schedule)">
                      <div>
                        <span class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100">NO LOGS</span>
                      </div>
                    </template>
                    <template v-else>
                      <template v-if="datum.schedule[0].title_id==1">
                        <template v-if="datum.schedule[0].is_present==1">
                          <div>
                            {{calendarFormat(datum.schedule[0].time_in)}}
                            <span class="mL-5" style="font-size:0.8em;">IN</span>
                          </div>
                          <div v-if="datum.schedule[0].time_out!=null">
                            {{calendarFormat(datum.schedule[0].time_out)}}
                            <span class="mL-5" style="font-size:0.8em;">OUT</span>
                          </div>
                        </template>
                        <template v-else>
                        <div>
                          <span class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100">NO LOGS</span>
                        </div>
                        </template>
                      </template>
                      <template v-else-if="datum.schedule[0].title_id>1 && datum.schedule[0].title_id<8">
                        <div>
                          <span class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100">NO LOGS</span>
                        </div>
                      </template>
                    </template>
                  </div>
                </td>
                <td style="font-size:.99em">
                  <div>
                    <template v-if="isEmpty(datum.schedule)">
                      <div>
                        <span class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100">NO LOGS</span>
                      </div>
                    </template>
                    <template v-else>
                      <template v-if="datum.schedule[0].title_id==1">
                        <template v-if="datum.schedule[0].is_present==1">
                          <template v-if="!ifReady(datum.schedule[0].time_in,datum.schedule[0].start_event)">
                            <div>
                              <span class="badge badge-pill p-5 bgc-orange-200 c-orange-800 fw-900 w-100">TARDY</span>
                            </div>
                          </template>
                          <template v-else>
                            <div>
                              <span class="badge badge-pill p-5 bgc-green-200 c-green-800 fw-900 w-100">READY</span>
                            </div>
                          </template>
                          
                        </template>
                        <template v-else>
                          <div>
                            <span class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100">NO LOGS</span>
                          </div>
                        </template>
                      </template>
                      <template v-else-if="datum.schedule[0].title_id>1 && datum.schedule[0].title_id<8">
                        <div>
                          <span class="badge badge-pill p-5 bgc-grey-200 c-grey-800 fw-900 w-100">NO LOGS</span>
                        </div>
                      </template>
                    </template>
                  </div>
                </td>
              </tr>
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
.table-responsive,#parent{
  { page-break-after:auto }
}
@media print
{
  table { page-break-after:auto }
  tr    { page-break-inside:avoid; page-break-after:auto }
  td    { page-break-inside:avoid; page-break-after:auto }
  thead { display:table-header-group }
  tfoot { display:table-footer-group }
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
      this.tablemount=true;})
    
  },
  created() {
  },
  data() {
    return {
      user_id: this.userId,
      tablemount:false,
      config: {
        table_name: "Today's Activity",
        code: "todays_activity",
        tabs: [
          { tab_name: "All",code:'all' },
          { tab_name: "Scheduled",code:'scheduled' },
          { tab_name: "Present",code:'present' },
          { tab_name: "No Show",code:'no_show' },
          { tab_name: "Leave",code:'leave' },
          { tab_name: "Off Duty",code:'off_duty' }
        ],
        selected_tab: 0, //index based,
        selected_page:1,
        data: {
          all: [],
          scheduled: [],
          present: [],
          no_show: [],
          leave: [],
          off_duty: []
        },
        filter:{
          data:[],
          paginate:{
            page:1,
            perpage:15,
          },
          no_records:15
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
          this.config.data.all = res.meta.agent_schedules;
          this.config.data.present = res.meta.agent_schedules.filter(this.getPresent);
          this.config.data.no_show = res.meta.agent_schedules.filter(this.getNoShow);
          this.config.data.scheduled = res.meta.agent_schedules.filter(this.getScheduled);
          this.config.data.leave = res.meta.agent_schedules.filter(this.getLeave);
          this.config.data.off_duty = res.meta.agent_schedules.filter(this.getNoSchedule);
          this.config.data.all = res.meta.agent_schedules;
          let temp = res.meta.agent_schedules;
          this.changeTableTab('all',1)
        })
        .catch(err => {
          console.log(err);
        });
    },
    calendarFormat:function(date){
      return moment(date).calendar();
    },
    ifReady:function(time_in,sched_in){
      if(new Date(time_in) > new Date(sched_in)){
        return false;
      }else if(new Date(time_in) < new Date(sched_in)){
        return true;
      }else{
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
    },
    hasSchedule:function(work){
      return !this.isEmpty(work.schedule);
    },
    workSchedule:function(work){
      return work.schedule[0].title_id==1;
    },
    leaveSchedule:function(work){
      return work.schedule[0].title_id>1 && work.schedule[0].title_id<8;
    },
    isPresent:function(work){
      return work.schedule[0].is_present==1;
    },
    getLeave:function(work){
      return this.hasSchedule(work) && this.leaveSchedule(work);
    },
    getPresent:function(work){
      return this.hasSchedule(work) && this.workSchedule(work) && isPresent(work); 
    },
    getScheduled:function(work){
      return this.hasSchedule(work) && this.workSchedule(work);
    },
    getNoShow:function(work){
      return this.hasSchedule(work) && this.workSchedule(work) && !this.isPresent(work); 
    },
    getPresent:function(work){
      return this.hasSchedule(work) && this.workSchedule(work) && this.isPresent(work); 
    },
    getNoSchedule:function(work){
      return !this.hasSchedule(work);
    },
    changeTableTab:function(tabCode,page){
      this.paginate(this.config.data[tabCode],page,this.config.filter.no_records)
    },onSelect:function(){

    }
  }
};
</script>