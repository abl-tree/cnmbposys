
<template>
  <div id="parent" class="bd bgc-white">
    <div class="layers">
      <div class="layer w-100 pX-20 pT-20 pB-10">
        <h6 class="lh-1">{{config.table_name}}</h6>
      </div>
      <div class="p-5 pX-30 layer w-100">
        <div class="row">
          <div class="col-md-6"></div>
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
                    <option value="1">User</option>
                    <option value="2">Position</option>
                    <option value="3">Log</option>
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
                <!-- <th class="bdwT-0 text-center">Image</th> -->
                <th class="bdwT-0 text-center">
                  User
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="(config.filter.sort.by='user'),(config.filter.sort.order['user'] = !config.filter.sort.order['user']),processFilters((config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
                    ></span>
                  </span>
                </th>
                <th class="bdwT-0 text-center">
                  Position
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="(config.filter.sort.by='position'),(config.filter.sort.order['position'] = !config.filter.sort.order['position']),processFilters((config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
                    ></span>
                  </span>
                </th>
                <th class="bdwT-0 text-center">
                  Log
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="(config.filter.sort.by='log'),(config.filter.sort.order['log'] = !config.filter.sort.order['log']),processFilters((config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
                    ></span>
                  </span>
                </th>
                <th class="bdwT-0 text-center">
                  Date
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="(config.filter.sort.by='date'),(config.filter.sort.order['date'] = !config.filter.sort.order['date']),processFilters((config.filter.search.value=='' ? config.tabs[config.selected_tab].code : 'search' ),config.selected_page)"
                    ></span>
                  </span>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(datum,index) in config.filter.data.data" :key="datum.id">
                <td-personnel :personnel="datum.info" :image="datum.info.image"></td-personnel>
                <td>
                  <span style="font-size:.95em">{{datum.info.position}}</span>
                </td>
                <td>
                  <span style="font-size:.95em">{{datum.log.affected_data}}</span>
                </td>
                <td>
                  <span
                    style="font-size:.95em"
                    data-toggle="tooltip"
                    :title="calendarFormat(datum.log.created_at)"
                  >{{fromNow(datum.log.created_at)}}</span>
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
  props: ["userId", "accessId"],
  mounted() {
    this.fetchActionLogs();
  },
  created() {},
  data() {
    return {
      user_id: this.userId,
      no_display: false,
      config: {
        loader: true,
        table_name: "Action Logs",
        code: "action_logs",
        tabs: [{ tab_name: "All", code: "all" }],
        selected_tab: 0, //index based,
        selected_page: 1,
        searchAgent: "",
        data: {
          all: []
        },
        filter: {
          sort: {
            by: "date",
            order: {
              user: true,
              date: false,
              position: true
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
    fetchActionLogs: function() {
      this.config.loader = true;
      this.config.no_display = false;
      let pageurl = "/api/v1/logs";
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          this.table.data = res.meta.metadata;
          this.createList(res.meta.metadata);
          this.processFilters(
            this.config.tabs[this.config.selected_tab].code,
            1
          );
        })
        .catch(err => {
          console.log(err);
        });
    },
    createList: function(obj) {
      let result = [];
      obj.forEach(
        function(v, i) {
          if (!this.isEmpty(v.user_logs)) {
            v.user_logs.forEach(
              function(v1, i1) {
                if (v1.user_id != 3) {
                  result.push({
                    info: {
                      id: v.id,
                      full_name: v.fname + " " + v.mname + " " + v.lname,
                      image: v.image,
                      email: v.email,
                      position: v.position
                    },
                    log: v1
                  });
                }
              }.bind(this)
            );
          }
        }.bind(this)
      );
      // console.log(result);
      if (this.accessId == 12 || this.accessId == 13 || this.accessId == 14) {
        this.config.data.all = result.filter(this.getRTA);
      } else if (
        this.accessId == 1 ||
        this.accessId == 2 ||
        this.accessId == 3
      ) {
        this.config.data.all = result;
      }
      // this.config.data.pending = obj.filter(this.getPending);
      // this.config.data.denied = obj.filter(this.getDenied);
      // this.config.data.approved = obj.filter(this.getApproved);
      // this.config.data.expired = obj.filter(this.getExpired);
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
    },
    getRTA: function(rta) {
      return (
        rta.info.position.toLowerCase() == "rta analyst" ||
        rta.info.position.toLowerCase() == "rta supervisor" ||
        rta.info.position.toLowerCase() == "rta manager"
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
        // user
        this.config.data.search = this.config.data[
          this.config.tabs[this.config.selected_tab].code
        ].filter(this.getUserSearch);
      } else if (this.config.filter.search.option == 2) {
        // type search
        this.config.data.search = this.config.data[
          this.config.tabs[this.config.selected_tab].code
        ].filter(this.getPositionSearch);
      } else if (this.config.filter.search.option == 3) {
        // type search
        this.config.data.search = this.config.data[
          this.config.tabs[this.config.selected_tab].code
        ].filter(this.getLogSearch);
      }
    },
    getUserSearch: function(index) {
      return index.info.full_name
        .trim()
        .toLowerCase()
        .includes(this.config.filter.search.value.trim().toLowerCase());
    },
    getPositionSearch: function(index) {
      return index.info.position
        .trim()
        .toLowerCase()
        .includes(this.config.filter.search.value.trim().toLowerCase());
    },
    getLogSearch: function(index) {
      return index.log.affected_data
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
        case "user":
          nameA = a.info.full_name.toLowerCase();
          nameB = b.info.full_name.toLowerCase();
          break;
        case "date":
          nameA = a.log.created_at.toLowerCase();
          nameB = b.log.created_at.toLowerCase();
          break;
        case "position":
          nameA = a.info.position.toLowerCase();
          nameB = b.info.position.toLowerCase();
          break;
        case "log":
          nameA = a.log.affected_data.toLowerCase();
          nameB = b.log.affected_data.toLowerCase();
          break;
        // case "type":
        //   nameA = a.request.title.name.toLowerCase();
        //   nameB = b.request.title.name.toLowerCase();
        //   break;
        // case "status":
        //   nameA = a.request.status.toLowerCase();
        //   nameB = b.request.status.toLowerCase();
        //   break;
        // case "request_date":
        //   nameA = moment(a.request.requested.date);
        //   nameB = moment(b.request.requested.date);
        //   break;
      }
      return { a: nameA, b: nameB };
    }
  }
};
</script>