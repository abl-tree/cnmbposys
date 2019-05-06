

<template>
  <!-- #Sales Report ==================== -->
  <div id="parent" class="bd bgc-white">
    <div class="layers">
      <div class="layer w-100 p-20">
        <h6 class="lh-1">{{config.table_name}}</h6>
      </div>

      <div class="layer pX-20 w-100">
        <div class="row pX-20">
          <div
            v-for="(tab,index) in config.tabs"
            :key="tab.id"
            class="col text-center pX-0 cur-p"
            @click="(config.searchAgent=''),(config.selected_tab = index),(config.selected_page=1),changeTableTab(tab.code,1)"
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
                  @change="(config.selected_page=1),changeTableTab((config.searchAgent=='' ? config.tabs[config.selected_tab].code : 'search' ),1)"
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
                  @input="searchAgent(),changeTableTab('search',1)"
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
                  @click="(config.filter.data.prev!=null?config.selected_page--:''),changeTableTab((config.searchAgent=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
                  :disabled="config.filter.data.prev == null"
                >
                  <i class="ti-angle-left"></i>
                </button>
                <button
                  class="fsz-xs btn btn-xs bgc-white bd bdrs-2 mR-3 cur-p"
                  type="button"
                  @click="(config.filter.data.next!=null?config.selected_page++:''),changeTableTab((config.searchAgent=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
                  :disabled="config.filter.data.next == null"
                >
                  <i class="ti-angle-right"></i>
                </button>
                <select
                  style="width:50px"
                  class="p-2 pY-5"
                  v-model="config.selected_page"
                  @change="changeTableTab((config.searchAgent=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
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
                  Name
                  <span class="pull-right">
                  <span class="ti-exchange-vertical cur-p" @click="config.agentSort = !config.agentSort,changeTableTab((config.searchAgent=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)" ></span>
                  </span>
                </th>
                <th class="bdwT-0">Type</th>
                <th class="bdwT-0">Date range</th>
                <th class="bdwT-0">Request Date</th>
                <th class="bdwT-0">Status</th>
                <th class="bdwT-0">RTA remarks</th>
              </tr>
            </thead>
            <tbody>
                    <tr v-for="datum in config.filter.data.data" :key="datum.id">
                        <td style="font-size:.95em">
                            <div>
                            <div class="fw-600">
                                <i class="ti-user c-grey-400 mR-5"></i>
                                {{datum.applicant.full_name}}
                            </div>
                            <div>
                                <i class="ti-email c-grey-400 mR-5"></i>
                                {{split(datum.applicant.email,'@',0)}}
                            </div>
                            <div class="c-light-blue-400">@cnmsolutions.net</div>
                            </div>
                        </td>
                        <td style='font-size:.95em'><div><span class="mR-5" :style="'color:'+datum.title.color">&#9679;</span>{{datum.title.title}}</div></td>
                        <td style='font-size:.95em'><div>{{datum.start_date+" - "+  datum.end_date}}<span class="mX-5 ti-calendar c-blue-500"></span></div></td>
                        <td style='font-size:.95em'><div data-toggle="tooltip" :title="calendarFormat(datum.requested_by)">{{fromNow(datum.requested_by)}}</div></td>
                        <td style='font-size:.99em'>
                            <div v-if="!isAfter(datum.start_date)">
                                <span class="badge badge-pill p-5 bgc-grey-100 c-grey-800 fw-900"><span class='badge badge-pill p-3 bgc-white mR-5'><span class="ti-close c-grey-500 fw-900"></span></span>EXPIRED</span>
                            </div>
                            <div v-else>
                                <span class="badge badge-pill p-5 fw-900" v-bind:class="getBadgeByStatus(datum.status).c2"><span class='badge badge-pill p-3 bgc-white mR-5'><span class="fw-900" v-bind:class="getBadgeByStatus(datum.status).c1"></span></span>{{getBadgeByStatus(datum.status).label}}</span><span class="mL-15 ti-pencil c-blue-500"></span>
                            </div>
                        </td>
                        <td style='font-size:.95em'><div class='c-grey-500'>No remarks</div></td>
                    </tr>
                <!-- <tr>
                <td style='font-size:.95em'><div class="fw-600">Agent Name</div><div><i class="ti-email c-grey-400 mR-5"></i>agent@gmail.com</div></td>
                <td style='font-size:.95em'><div>Vacation Leave</div></td>
                <td style='font-size:.95em'><div>2019/04/10 - 2019/04/20 <span class="mX-5 ti-calendar c-blue-500"></span></div></td>
                <td style='font-size:.95em'><div>2019/03/15</div></td>
                <td style='font-size:.99em'><span class="badge badge-pill p-5 bgc-red-100 c-red-800 fw-900"><span class='badge badge-pill p-3 bgc-white mR-5'><span class="ti-alert c-red-500 fw-900"></span></span>DENIED</span><span class="mL-15 ti-pencil c-blue-500"></span></td>
                <td style='font-size:.95em'><div class='c-grey-900'>Please send another request for another date.</div></td>
                </tr>
                <tr>
                <td style='font-size:.95em'><div class="fw-600">Agent Name</div><div><i class="ti-email c-grey-400 mR-5"></i>agent@gmail.com</div></td>
                <td style='font-size:.95em'><div>Vacation Leave</div></td>
                <td style='font-size:.95em'><div>2019/04/10 - 2019/04/20 <span class="mX-5 ti-calendar c-blue-500"></span></div></td>
                <td style='font-size:.95em'><div>2019/03/15</div></td>
                <td style='font-size:.99em'><span class="badge badge-pill p-5 bgc-orange-100 c-orange-800 fw-900"><span class='badge badge-pill p-3 bgc-white mR-5'><span class="ti-time c-orange-500 fw-900"></span></span>PENDING</span><span class="mL-15 ti-pencil c-blue-500"></span></td>
                <td style='font-size:.95em'><div class='c-grey-500'>No remarks</div></td>
                </tr>
                <tr>
                <td style='font-size:.95em'><div class="fw-600">Agent Name</div><div><i class="ti-email c-grey-400 mR-5"></i>agent@gmail.com</div></td>
                <td style='font-size:.95em'><div>Vacation Leave</div></td>
                <td style='font-size:.95em'><div>2019/04/10 - 2019/04/20 <span class="mX-5 ti-calendar c-blue-500"></span></div></td>
                <td style='font-size:.95em'><div>2019/03/15</div></td>
                <td style='font-size:.99em'><span class="badge badge-pill p-5 bgc-grey-100 c-grey-800 fw-900"><span class='badge badge-pill p-3 bgc-white mR-5'><span class="ti-close c-grey-500 fw-900"></span></span>EXPIRED</span></td>
                <td style='font-size:.95em'><div class='c-grey-500'>No remarks</div></td>
                </tr> -->
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
    this.fetchSchedRequest();
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
        agentSort: true,
        table_name: "Schedule Request",
        code: "schedule_request",
        tabs: [
          { tab_name: "All", code: "all" },
          { tab_name: "Pending", code: "pending" },
          { tab_name: "Approved", code: "approved" },
          { tab_name: "Expired", code: "expired" },
          { tab_name: "Denied", code: "denied" },
        ],
        selected_tab: 0, //index based,
        selected_page: 1,
        searchAgent: "",
        data: {
          all: [],
          pending: [],
          approved: [],
          expired: [],
          denied: [],
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
      table: {
        data: []
      },
      temp:{
          loop:{
              status:{},
          }
      }
    };
  },
  methods: {
    fetchSchedRequest: function() {
      let pageurl = "/api/v1/request_schedules";
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          this.table.data = res.meta.request_schedules;
          this.config.data.all = res.meta.request_schedules;
          this.config.data.pending = res.meta.request_schedules.filter(this.getPending); 
          this.config.data.denied = res.meta.request_schedules.filter(this.getDenied); 
          this.config.data.approved = res.meta.request_schedules.filter(this.getApproved); 
          this.config.data.expired = res.meta.request_schedules.filter(this.getExpired); 
          this.changeTableTab("all", 1);
        })
        .catch(err => {
          console.log(err);
        });
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
    },
    
    getExpired: function(status) {
      return !this.isAfter(status.start_date)
    },
    getPending: function(status) {
      return this.isAfter(status.start_date) && status.status=="pending"
    },
    getDenied: function(status) {
      return this.isAfter(status.start_date) && status.status=="denied"
    },
    getApproved: function(status) {
      return this.isAfter(status.start_date) && status.status=="approved"
    },
    changeTableTab: function(tabCode, page) {
      this.paginate(
        // this.agentSort(this.config.data[tabCode]),
        this.config.data[tabCode],
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
    agentSort: function(obj) {
      var result = [];
      if (this.config.agentSort) {
        result = obj.sort(function(a, b) {
          var nameA = a.full_name.toLowerCase(),
            nameB = b.full_name.toLowerCase();
          if (nameA < nameB)
            //sort string ascending
            return -1;
          if (nameA > nameB) return 1;
          return 0; //default return value (no sorting)
        });
      } else {
        result = obj.sort(function(a, b) {
          var nameA = b.full_name.toLowerCase(),
            nameB = a.full_name.toLowerCase();
          if (nameA < nameB)
            //sort string ascending
            return -1;
          if (nameA > nameB) return 1;
          return 0; //default return value (no sorting)
        });
      }
      return result;
    },
    getBadgeByStatus:function(status){
        let result;
        let class1,class2;
        if(status=='pending'){
            result="PENDING";
            class1="ti-time c-orange-500";
            class2="bgc-orange-100 c-orange-800";
        }else if(status=='approved'){
            result="APPROVED";
            class1="ti-check c-green-500";
            class2="bgc-green-100 c-green-800";
        }else if(status=='denied'){
            result="DENIED";
            class1="ti-alert c-red-500";
            class2="bgc-red-100 c-red-800";
        }
        return {c1:class1,c2:class2,label:result}
    },
    isAfter:function(date){
        let date2 = moment().format("YYYY-MM-DD hh:mm:ss a"),
        date1= moment(date).format("YYYY-MM-DD hh:mm:ss a");
        console.log(date1+"-"+date2)
        return moment(date1).isAfter(date2);
    }
  }
};
</script>