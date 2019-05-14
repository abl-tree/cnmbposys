
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
                    <option value="2">Type</option>
                    <option value="3">Status</option>
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
                <th class="bdwT-0 text-center">
                  Agent
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="(config.filter.sort.by='agent'),(config.filter.sort.order['agent'] = !config.filter.sort.order['agent']),processFilters((config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
                    ></span>
                  </span>
                </th>
                <th class="bdwT-0 text-center">
                  Type
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="(config.filter.sort.by='type'),(config.filter.sort.order['type'] = !config.filter.sort.order['type']),processFilters((config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
                    ></span>
                  </span>
                </th>
                <th class="bdwT-0 text-center">Date Range</th>
                <th class="bdwT-0 text-center">
                  Request Date
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="(config.filter.sort.by='request_date'),(config.filter.sort.order['request_date'] = !config.filter.sort.order['request_date']),processFilters((config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
                    ></span>
                  </span>
                </th>
                <th class="bdwT-0 text-center">Status</th>
                <th class="bdwT-0 text-center">RTA Remarks</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(datum,index) in config.filter.data.data" :key="datum.id">
                <td-personnel :personnel="{full_name:datum.info.full_name,email:datum.info.email}"></td-personnel>
                <td style="font-size:.95em">
                  <div>
                    <span class="mR-5" :style="'color:'+datum.request.title.color">&#9679;</span>
                    {{datum.request.title.name}}
                  </div>
                </td>
                <td style="font-size:.95em" class="text-center">
                  <div>
                    {{datum.request.start_date+" - "+ datum.request.end_date}}
                    <span
                      class="mX-5 ti-calendar c-blue-500"
                    ></span>
                  </div>
                </td>
                <td style="font-size:.95em" class="text-center">
                  <div
                    data-toggle="tooltip"
                    :title="calendarFormat(datum.request.requested.date)"
                  >{{fromNow(datum.request.requested.date)}}</div>
                </td>
                <td style="font-size:.99em" class="text-center">
                  <div v-if="!isAfter(datum.request.start_date)">
                    <span class="badge badge-pill p-5 bgc-grey-100 c-grey-800 fw-900">
                      <span class="badge badge-pill p-3 bgc-white mR-5">
                        <span class="ti-close c-grey-500 fw-900"></span>
                      </span>EXPIRED
                    </span>
                  </div>
                  <div v-else>
                    <span
                      class="badge badge-pill p-5 fw-900"
                      v-bind:class="component.table.td.badges.request_schedule[datum.request.status].class2"
                      :id="'rs-popover-'+index"
                    >
                      <span class="badge badge-pill p-3 bgc-white mR-5">
                        <span
                          class="fw-900"
                          v-bind:class="component.table.td.badges.request_schedule[datum.request.status].class1"
                        ></span>
                      </span>
                      {{component.table.td.badges.request_schedule[datum.request.status].label}}
                    </span>
                  </div>
                  <b-popover
                    v-if="datum.request.status=='pending'"
                    triggers="click"
                    placement="auto"
                    :target="'rs-popover-'+index"
                    title="Approval"
                    @show="approvalPopShow(datum,index)"
                  >
                    <div class="container">
                      <div class="row">
                        <div class="col-sm-12">
                          <input
                            type="text"
                            class="form-control"
                            v-model="form.response.rta_remarks[index]"
                          >
                        </div>
                      </div>
                      <div class="row" style="margin-top:2px;">
                        <div class="col">
                          <div class="btn-group w-100">
                            <b-button
                              variant="secondary"
                              size="sm"
                              class="form-control"
                              :disabled="form.response.button[index]"
                              @click="(form.response.status[index]='deny'),storeRequestResponse(datum,index)"
                            >Deny</b-button>
                            <b-button
                              variant="danger"
                              size="sm"
                              class="form-control"
                              :disabled="form.response.button[index]"
                              @click="(form.response.status[index]='approve'),storeSchedule(datum,index)"
                            >Approve</b-button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </b-popover>
                </td>
                <td style="font-size:.95em">
                  <div
                    v-if="datum.request.managed.remark=='' || datum.request.managed.remark==null"
                    class="c-grey-500"
                  >No Response</div>
                  <div v-else class="c-grey-900">{{datum.request.managed.remark}}</div>
                </td>
              </tr>
              <!-- LOADER -->
              <template v-if="config.loader">
                <tr-loader v-for="d in 5" :key="d.id" :tablename="config.code"></tr-loader>
              </template>
            </tbody>
          </table>
        </div>
      </div>
    </div>
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
      no_display: false,
      config: {
        loader: true,
        table_name: "Schedule Request",
        code: "schedule_request",
        tabs: [
          { tab_name: "All", code: "all" },
          { tab_name: "Pending", code: "pending" },
          { tab_name: "Approved", code: "approved" },
          { tab_name: "Expired", code: "expired" },
          { tab_name: "Denied", code: "denied" }
        ],
        selected_tab: 0, //index based,
        selected_page: 1,
        searchAgent: "",
        data: {
          all: [],
          pending: [],
          approved: [],
          expired: [],
          denied: []
        },
        filter: {
          sort: {
            by: "request_date",
            order: {
              agent: true,
              type: true,
              request_date: false,
              status: true
            }
          },
          search: {
            option: 1, //1=agent,2=type,3=status
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
      form: {
        response: {
          rta_remarks: [],
          status: [],
          button: []
        }
      },
      table: {
        data: []
      },
      temp: {
        loop: {
          status: {}
        }
      }
    };
  },
  methods: {
    fetchSchedRequest: function() {
      this.config.loader = true;
      this.config.no_display = false;
      let pageurl = "/api/v1/request_schedules";
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          this.table.data = res.meta.request_schedules;
          let obj = [];
          console.log(res.meta.request_schedules)
          res.meta.request_schedules.forEach(
            function(v, i) {
              let temp = {
                info: {
                  full_name: "",
                  image: "",
                  id: "",
                  email: ""
                },
                request: {
                  id: "",
                  status: "",
                  start_date: "",
                  end_date: "",
                  mark: "",
                  title: {
                    name: "",
                    id: "",
                    color: ""
                  },
                  managed: {
                    by: {
                      id: "",
                      full_name: ""
                    },
                    date: "",
                    remark: ""
                  },
                  requested: {
                    by: {
                      id: "",
                      full_name: ""
                    },
                    date: ""
                  }
                }
              };
              // info
              temp.info.full_name = v.applicant.full_name;
              temp.info.id = v.applicant.id;
              temp.info.image = v.applicant.info.image;
              temp.info.email = v.applicant.email;
              // request
              temp.request.id = v.id;
              temp.request.status = this.getRequestStatus(v);
              temp.request.start_date = v.start_date;
              temp.request.end_date = v.end_date;
              temp.request.mark = v.mark;
              // request -> title
              temp.request.title.name = v.title.title;
              temp.request.title.id = v.title.id;
              temp.request.title.color = v.title.color;
              // request -> managed by
              if (v.managed_by != null) {
                temp.request.managed.by.id = v.managed_by.id;
                temp.request.managed.by.full_name = v.managed_by.full_name;
                temp.request.managed.date = v.response_date;
                temp.request.managed.remark = v.rta_remarks;
              }
              // request -> reuqested
              temp.request.requested.by.id = v.requested_by.id;
              temp.request.requested.by.full_name = v.requested_by.full_name;
              temp.request.requested.date = v.created_at;
              obj.push(temp);
            }.bind(this)
          );
          this.config.data.all = obj;
          // this.config.data.pending = obj.filter(this.getPending);
          // this.config.data.denied = obj.filter(this.getDenied);
          // this.config.data.approved = obj.filter(this.getApproved);
          // this.config.data.expired = obj.filter(this.getExpired);

          res.meta.request_schedules.forEach(
            function(v, i) {
              if (v.status == "pending" && !this.isAfter(v.start_date)) {
                this.storeExpired(v);
              }
            }.bind(this)
          );

          this.processFilters(
            this.config.tabs[this.config.selected_tab].code,
            1
          );
        })
        .catch(err => {
          console.log(err);
        });
    },
    paginate: function(obj, page, per_page) {
      var page = page,
        per_page = per_page,
        offset = (page - 1) * per_page,
        paginatedItems = obj.slice(offset).slice(0, per_page),
        total_pages = Math.ceil(obj.length / per_page);
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
      this.form.response.rta_remarks = [
        ...new Set(
          [].concat(
            this.config.filter.data.data.map(a => a.request.managed.remark)
          )
        )
      ];
    },
    getExpiredtoStore: function(status) {
      return (
        !this.isAfter(status.request.start_date) &&
        status.request.status == "pending"
      );
    },
    getExpired: function(status) {
      return !this.isAfter(status.request.start_date);
    },
    getPending: function(status) {
      return (
        this.isAfter(status.request.start_date) &&
        status.request.status == "pending"
      );
    },
    getDenied: function(status) {
      return (
        this.isAfter(status.request.start_date) &&
        status.request.status == "denied"
      );
    },
    getApproved: function(status) {
      return (
        this.isAfter(status.request.start_date) &&
        status.request.status == "approved"
      );
    },
    processFilters: function(tabCode, page) {
      if (this.config.filter.search.value != "") {
        this.searchBy();
      }
      this.paginate(
        this.columnSort(this.config.data[tabCode]),
        // this.config.data[tabCode],
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
        // type search
        this.config.data.search = this.config.data[
          this.config.tabs[this.config.selected_tab].code
        ].filter(this.getTypeSearch);
      } else if (this.config.filter.search.option == 3) {
        // type search
        this.config.data.search = this.config.data[
          this.config.tabs[this.config.selected_tab].code
        ].filter(this.getStatusSearch);
      }
    },
    getAgentSearch: function(index) {
      return index.info.full_name
        .trim()
        .toLowerCase()
        .includes(this.config.filter.search.value.trim().toLowerCase());
    },
    getTypeSearch: function(index) {
      return index.request.title.name
        .trim()
        .toLowerCase()
        .includes(this.config.filter.search.value.trim().toLowerCase());
    },
    getStatusSearch: function(index) {
      return index.request.status
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
      if (name.a > name.b) return -1;
      if (name.a < name.b) return 1;
      return 0;
    },
    asc: function(a, b) {
      let name = this.sortCondition(a, b);
      if (name.a < name.b) return -1;
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
        case "type":
          nameA = a.request.title.name.toLowerCase();
          nameB = b.request.title.name.toLowerCase();
          break;
        case "status":
          nameA = a.request.status.toLowerCase();
          nameB = b.request.status.toLowerCase();
          break;
        case "request_date":
          nameA = moment(a.request.requested.date);
          nameB = moment(b.request.requested.date);
          break;
      }
      return { a: nameA, b: nameB };
    },

    storeExpired: function(data) {
      console.log(data)
      let request = data;
      let pageurl = "/api/v1/request_schedules/update/" + request.id;
      fetch(pageurl, {
        method: "post",
        body: JSON.stringify({
        id: request.id,
        auth_id: this.userId,
        applicant: request.applicant.id,
        requested_by: request.requested_by.id,
        managed_by: this.user_id,
        title_id: request.title.id,
        start_date: request.start_date,
        end_date: request.end_date,
        status: "expired"
      }),
        headers: {
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          if (data.code == 500) {
          } else {
            // console.log(data);
          }
        })
        .catch(err => console.log(err));
    },
    getRequestStatus: function(data) {
      let result;
      if (
        (!this.isAfter(data.start_date) && data.status == "pending") ||
        (!this.isAfter(data.start_date) && data.status == "expired")
      ) {
        result = "expired";
      } else if (this.isAfter(data.start_date) && data.status == "pending") {
        result = "pending";
      } else if (this.isAfter(data.start_date) && data.status == "denied") {
        result = "denied";
      } else if (this.isAfter(data.start_date) && data.status == "approved") {
        result = "approved";
      }
      return result;
    },
    storeRequestResponse: function(req, index) {
      if (this.form.response.status[index] == "deny") {
        this.form.response.button[index] = true;
      }
      let request = req.request;
      let pageurl = "/api/v1/request_schedules/update/" + request.id;
      let obj = {
        id: request.id,
        auth_id: this.userId,
        applicant: req.info.id,
        requested_by: request.requested.by.id,
        managed_by: this.userId,
        title_id: request.title.id,
        start_date: request.start_date,
        end_date: request.end_date,
        status:
          this.form.response.status[index] == "approve" ? "approved" : "denied",
        rta_remarks: this.form.response.rta_remarks[index]
      };
      // console.log(obj);

      fetch(pageurl, {
        method: "post",
        body: JSON.stringify(obj),
        headers: {
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          if (data.code == 500) {
            this.notify("error", "update");
          } else {
            this.form.response.button[index] = false;
            this.notify("success", "update");
            console.log(data);
            this.table.data.forEach(
              function(v, i) {
                if (v.id == data.parameters.id) {
                  this.table.data[i].rta_remarks = data.parameters.rta_remarks;
                  this.table.data[i].status = data.parameters.status;
                  console.log(data.parameters.rta_remarks);
                  console.log(data.parameters.status);
                }
              }.bind(this)
            );
            let obj1 = [];
            this.table.data.forEach(
              function(v, i) {
                let temp = {
                  info: {
                    full_name: "",
                    image: "",
                    id: "",
                    email: ""
                  },
                  request: {
                    id: "",
                    status: "",
                    start_date: "",
                    end_date: "",
                    mark: "",
                    title: {
                      name: "",
                      id: "",
                      color: ""
                    },
                    managed: {
                      by: {
                        id: "",
                        full_name: ""
                      },
                      date: "",
                      remark: ""
                    },
                    requested: {
                      by: {
                        id: "",
                        full_name: ""
                      },
                      date: ""
                    }
                  }
                };
                // info
                temp.info.full_name = v.applicant.full_name;
                temp.info.id = v.applicant.id;
                temp.info.image = v.applicant.info.image;
                temp.info.email = v.applicant.email;
                // request
                temp.request.id = v.id;
                temp.request.status = this.getRequestStatus(v);
                temp.request.start_date = v.start_date;
                temp.request.end_date = v.end_date;
                temp.request.mark = v.mark;
                // request -> title
                temp.request.title.name = v.title.title;
                temp.request.title.id = v.title.id;
                temp.request.title.color = v.title.color;
                // request -> managed by
                if (v.managed_by != null) {
                  temp.request.managed.by.id = v.managed_by.id;
                  temp.request.managed.by.full_name = v.managed_by.full_name;
                  temp.request.managed.date = v.response_date;
                  temp.request.managed.remark = v.rta_remarks;
                }
                // request -> reuqested
                temp.request.requested.by.id = v.requested_by.id;
                temp.request.requested.by.full_name = v.requested_by.full_name;
                temp.request.requested.date = v.created_at;
                obj1.push(temp);
              }.bind(this)
            );
            console.log(obj1);
            this.config.data.all = obj1;
            this.config.data.pending = obj1.filter(this.getPending);
            this.config.data.denied = obj1.filter(this.getDenied);
            this.config.data.approved = obj1.filter(this.getApproved);
            this.config.data.expired = obj1.filter(this.getExpired);

            this.processFilters(
              this.config.tabs[this.config.selected_tab].code,
              1
            );
          }
        })
        .catch(err => console.log(err));
    },
    storeSchedule: function(request, index) {
      if (this.form.response.status[index] == "approve") {
        this.form.response.button[index] = true;
      }
      let pageurl = "/api/v1/schedules/create/bulk/";
      let data = request.request;
      let dates = [];
      let obj = [{ auth_id: this.userId }];
      if (data.end_date == null) {
        dates.push(moment(moment(data.start_date)).format("YYYY-MM-DD"));
      } else {
        dates = this.getDates(data.start_date, data.end_date);
      }
      dates.forEach(
        function(v, i) {
          let start = v + " 00:00:00";
          let obj_element = {
            title_id: data.title.id,
            auth_id: this.userId,
            user_id: request.info.id,
            start_event: start,
            end_event: moment(
              moment(start)
                .add("24", "h")
                .toDate()
            ).format("YYYY-MM-DD HH:mm:ss")
          };
          obj.push(obj_element);
        }.bind(this)
      );
      console.log(obj);

      fetch(pageurl, {
        method: "post",
        body: JSON.stringify(obj),
        headers: {
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          if (data.code == 500) {
          } else {
            // console.log(data);
            this.storeRequestResponse(request, index);
          }
        })
        .catch(err => console.log(err));
    },
    getDates: function(startDate, stopDate) {
      // console.log("start " + startDate);
      // console.log("end " + stopDate);
      var dateArray = [];
      var currentDate = moment(startDate);
      var stopDate = moment(stopDate);
      while (currentDate <= stopDate) {
        dateArray.push(moment(currentDate).format("YYYY-MM-DD"));
        currentDate = moment(currentDate).add(1, "days");
      }
      return dateArray;
      // console.log(dateArray);
    },
    approvalPopShow: function(data, index) {
      // console.log(data.request.managed.remark)
      this.form.response.status[index] = "";
      this.form.response.button[index] = false;
    }
  }
};
</script>