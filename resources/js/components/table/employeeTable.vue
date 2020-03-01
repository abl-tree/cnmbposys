<template>
  <div id="parent" class="bd bgc-white">
    <div class="layers">
      <div class="layer w-100 pX-20 pT-20 pB-10">
        <h6 class="lh-1">{{ employeeTable.config.table_name}}</h6>
      </div>
      <div class="p-5 pX-30 layer w-100">
        <div class="row">
          <div class="col-md-6">
            <a
              href="/employee/create"
              class="p-10 btn google-hover c-grey-800"
              style="border-radius:25px"
              @click="showModal('employee-form-modal')"
            >
              Create
              <span class="pL-5 fw-900">
                <i class="ti-plus w-3r h-3r fw-900"></i>
              </span>
            </a>
          </div>
          <div class="col-md-6 text-right">
            <div class="pull-right">
              <div class="input-group">
                <div class="input-group-prepend mR-5">
                  <input
                    type="text"
                    class="p-10"
                    v-model="employeeTable.config.filter.search.value"
                    @input="(employeeTable.config.no_display=false),processFilters(employeeTable.config.filter.search.value=='' ? employeeTable.config.tabs[employeeTable.employeeTable.config.selected_tab].code : 'search' ,1)"
                    style="width:300px;border-radius:5px;border:1px solid #ccc"
                    placeholder="Search..."
                  >
                </div>
                <div class="input-group-append">
                  <select
                    class="p-10"
                    v-model="employeeTable.config.filter.search.option"
                    style="border-radius:5px;border:1px solid #ccc"
                  >
                    <option value="1">User</option>
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
            v-for="(tab,index) in employeeTable.config.tabs"
            :key="tab.id"
            class="col text-center pX-0 cur-p"
            @click="(employeeTable.config.selected_tab = index),(employeeTable.config.selected_page=1),processFilters(employeeTable.config.filter.search.value=='' ? tab.code : 'search' ,1)"
          >
            <span
              class="text-center w-100 pY-10 badge-c"
              :class="employeeTable.config.selected_tab == index? 'bgc-white c-grey-900 bdL bdR': 'bgc-grey-200 c-grey-600 bd'"
              :style="employeeTable.config.selected_tab == index? 'border-top: 1px red solid':''"
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
                  v-model="employeeTable.config.filter.no_records"
                  @change="(employeeTable.config.selected_page=1),processFilters((employeeTable.config.filter.search.value=='' ? employeeTable.config.tabs[employeeTable.config.selected_tab].code : 'search' ),1)"
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
                v-if="employeeTable.config.loader==true"
                style="width:200px;height:20px;margin-bottom:2px;"
                class="text-right"
              >
                <div class="loader-12 pull-right"></div>
              </span>
              <template v-else>
                <div
                  v-if="employeeTable.config.no_display==false"
                  class="c-grey-600"
                  style="font-size:0.8em;border-style:none"
                >Showing {{ employeeTable.config.filter.data.cur }} of {{ employeeTable.config.filter.data.total_pages }} page/s from {{ employeeTable.config.filter.data.total_result }} records</div>

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
                  @click="(employeeTable.config.filter.data.prev!=null?employeeTable.config.selected_page--:''),processFilters((employeeTable.config.filter.search.value=='' ? employeeTable.config.tabs[employeeTable.config.selected_tab].code : 'search' ),employeeTable.config.selected_page)"
                  :disabled="employeeTable.config.filter.data.prev == null"
                >
                  <i class="ti-angle-left"></i>
                </button>
                <button
                  class="fsz-xs btn btn-xs bgc-white bd bdrs-2 mR-3 cur-p"
                  type="button"
                  @click="(employeeTable.config.filter.data.next!=null?employeeTable.config.selected_page++:''),processFilters((employeeTable.config.filter.search.value=='' ? employeeTable.config.tabs[employeeTable.config.selected_tab].code : 'search' ),employeeTable.config.selected_page)"
                  :disabled="employeeTable.config.filter.data.next == null"
                >
                  <i class="ti-angle-right"></i>
                </button>
                <select
                  style="width:50px"
                  class="p-2 pY-5"
                  v-model="employeeTable.config.selected_page"
                  @change="processFilters((employeeTable.config.filter.search.value=='' ? employeeTable.config.tabs[employeeTable.config.selected_tab].code : 'search' ),employeeTable.config.selected_page)"
                >
                  <option
                    v-for="n in employeeTable.config.filter.data.total_pages"
                    :key="n.id"
                    :value="n"
                  >{{n}}</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="table-responsive text-nowrap pX-20 pB-20" style="height:500px;">
          <table class="table">
            <thead>
              <tr>
                <th class="bdwT-0">
                  User
                  <span class="pull-right">
                    <span
                      class="ti-exchange-vertical cur-p"
                      @click="(employeeTable.config.filter.sort.by='user'),(employeeTable.config.filter.sort.order['user'] = !employeeTable.config.filter.sort.order['user']),processFilters((employeeTable.config.filter.search.value=='' ? employeeTable.config.tabs[employeeTable.config.selected_tab].code : 'search' ),config.selected_page)"
                    ></span>
                  </span>
                </th>
                <th class="bdwT-0">Contact Details</th>
                <th class="bdwT-0">Head</th>
              </tr>
            </thead>
            <tbody>
              <!-- LOADER -->
              <template v-for="datum in employeeTable.config.filter.data.data">
                <tr :key="datum.id">
                  <td>
                    <div class="p-5" style="float:left">
                      <div class="checkbox checkbox-circle checkbox-info peers ai-c mB-15">
                        <input
                          type="checkbox"
                          id="inputCall1"
                          name="inputCheckboxesCall"
                          class="peer"
                        >
                        <label for="inputCall1" class="peers peer-greed js-sb ai-c"></label>
                      </div>
                    </div>
                    <div class="p-5" style="float:left">
                      <img v-if="datum.image!=null" class="bdrs-50p w-3r h-3r" :src="datum.image">
                      <div v-else class="bdrs-50p w-3r h-3r bgc-grey-200" style="display:flex">
                        <span
                          class="fsz-lg c-grey-500 text-center w-100"
                          style="align-self:center"
                        >{{ getNameInitials(datum.fname,datum.lname) }}</span>
                      </div>
                    </div>
                    <div class="p-5" style="float:left">
                      <div class="c-blue-500 fsz-xs fw-300">{{ datum.full_name }}</div>
                      <div class="c-grey-600" style="font-weight:lighter">{{ datum.position }}</div>
                      <div class="c-grey-600" style="font-weight:lighter">
                        <span class="badge c-white" :class="datum.status_color">{{ datum.status }}</span>
                      </div>
                      <!-- <div class="c-grey-600 pT-5">
                        <button class="w-2r h-2r p-5 bdrs-50p btn">
                          <i class="ti-eye"></i>
                        </button>
                        <button class="w-2r h-2r p-5 bdrs-50p btn">
                          <i class="ti-pencil-alt"></i>
                        </button>
                      </div>-->
                    </div>
                    <div class="pL-5" style="float:left">
                      <button class="w-2r h-2r p-5 bdrs-50p btn c-grey-700 google-hover">
                        <i class="ti-pencil-alt"></i>
                      </button>
                    </div>
                  </td>
                  <td>
                    <div class="pB-5">
                      <span class="pL-5">
                        <i class="ti-email c-grey-700"></i>
                      </span>
                      <span class="pL-10">
                        <span class="c-grey-600">{{ datum.email }}</span>
                      </span>
                    </div>
                    <div class="pB-5">
                      <span class="pL-5">
                        <i class="ti-mobile c-grey-700"></i>
                      </span>
                      <span class="pL-10">
                        <span v-if="datum.contact!=null" class="c-grey-600">{{ datum.contact }}</span>
                        <span v-else>
                          <i class="c-grey-400">NA</i>
                        </span>
                      </span>
                    </div>
                    <div class="pB-5">
                      <span class="pL-5 c-grey-700 fsz-xs">
                        <i class="ti-home"></i>
                      </span>
                      <span class="pL-10">
                        <span class="c-grey-600">{{ datum.address }}</span>
                      </span>
                    </div>
                  </td>
                  <td>
                    <div class="w-100">
                      <div class="text-center">
                        <span
                          v-if="datum.head.full_name != null"
                          class="c-grey-600"
                          style="font-weight:lighter;"
                        >{{ datum.head.full_name }}</span>
                        <span v-else class="c-grey-400" style="font-weight:normal;">
                          <i v-if="datum.id!=1">Not Assigned</i>
                          <i v-else>ADMIN</i>
                        </span>
                      </div>
                    </div>
                  </td>
                </tr>
              </template>
              <template v-if="employeeTable.config.loader">
                <tr-loader v-for="d in 5" :key="d.id" :tablename="employeeTable.config.code"></tr-loader>
              </template>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- <notifications group="foo" animation-type="velocity" position="bottom right"/> -->
  </div>
</template>
<style>
.google-hover:hover {
  background-color: #f2f3f5;
}
</style>

<script>
import Moment from "moment/moment";
import { extendMoment } from "moment-range";
const moment = extendMoment(Moment);
export default {
  props: ["userId", "all_user"],
  data() {
    return {
      employeeTable: {
        data: {},
        config: {
          loader: true,
          no_display: false,
          table_name: "Employees",
          code: "employee_table",
          tabs: [
            { tab_name: "Active", code: "active" },
            { tab_name: "Inactive", code: "present" }
          ],
          selected_tab: 0, //index based,
          selected_page: 1,
          data: {
            active: [],
            inactive: []
          },
          filter: {
            sort: {
              by: "user",
              order: {
                user: true
              }
            },
            search: {
              option: 1, //1=agent,2=clusterORoperationsManger
              value: ""
            },
            data: [],
            paginate: {
              page: 1,
              perpage: 15
            },
            no_records: 15
          }
        }
      }
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    fetchData: function() {
      fetch("api/v1/users")
        .then(res => res.json())
        .then(res => {
          let filter3 = res.meta.metadata.filter(i => i.id != 3);
          this.employeeTable.config.data.active = filter3.filter(
            i => i.status != "inactive"
          );
          this.employeeTable.config.data.inactive = filter3.filter(
            i => i.status == "inactive"
          );

          this.processFilters(
            this.employeeTable.config.tabs[
              this.employeeTable.config.selected_tab
            ].code,
            1
          );
        })
        .catch(err => console.log(err));
    },
    paginate: function(obj, page, per_page) {
      var page = page,
        per_page = per_page,
        offset = (page - 1) * per_page,
        paginatedItems = obj.slice(offset).slice(0, per_page),
        total_pages = Math.ceil(obj.length / per_page);
      this.employeeTable.config.filter.data = {
        data: paginatedItems,
        cur: page,
        prev: page - 1 ? page - 1 : null,
        next: total_pages > page ? page + 1 : null,
        total_result: obj.length,
        total_pages: total_pages
      };
      this.employeeTable.config.loader = false;
      if (this.employeeTable.config.filter.data.total_result == 0) {
        this.employeeTable.config.no_display = true;
      }
    },
    processFilters: function(tabCode, page) {
      if (this.employeeTable.config.filter.search.value != "") {
        this.searchBy();
      }
      this.paginate(
        this.columnSort(this.employeeTable.config.data[tabCode]),
        page,
        this.employeeTable.config.filter.no_records
      );
    },
    searchBy: function() {
      if (this.employeeTable.config.filter.search.option == 1) {
        // agent
        this.employeeTable.config.data.search = this.employeeTable.config.data[
          this.employeeTable.config.tabs[this.employeeTable.config.selected_tab]
            .code
        ].filter(this.getAgentSearch);
      } else if (this.employeeTable.config.filter.search.option == 2) {
        // tl
        this.employeeTable.config.data.search = this.employeeTable.config.data[
          this.employeeTable.config.tabs[this.employeeTable.config.selected_tab]
            .code
        ].filter(this.getTeamLeaderSearch);
      } else if (this.employeeTable.config.filter.search.option == 3) {
        // om
        this.employeeTable.config.data.search = this.employeeTable.config.data[
          this.employeeTable.config.tabs[this.employeeTable.config.selected_tab]
            .code
        ].filter(this.getOperationsManagerSearch);
      }
    },
    getAgentSearch: function(index) {
      return index.info.full_name
        .trim()
        .toLowerCase()
        .includes(
          this.employeeTable.config.filter.search.value.trim().toLowerCase()
        );
    },
    getTeamLeaderSearch: function(index) {
      return index.info.tl.full_name
        .trim()
        .toLowerCase()
        .includes(
          this.employeeTable.config.filter.search.value.trim().toLowerCase()
        );
    },
    getOperationsManagerSearch: function(index) {
      return index.info.om.full_name
        .trim()
        .toLowerCase()
        .includes(
          this.employeeTable.config.filter.search.value.trim().toLowerCase()
        );
    },
    columnSort: function(obj) {
      var result = [];
      if (
        this.employeeTable.config.filter.sort.order[
          this.employeeTable.config.filter.sort.by
        ]
      ) {
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
      switch (this.employeeTable.config.filter.sort.by) {
        case "user":
          nameA = a.full_name.toLowerCase();
          nameB = b.full_name.toLowerCase();
          break;
      }
      return { a: nameA, b: nameB };
    },

    getNameInitials: function(fname, lname) {
      return fname[0] + "" + lname[0].toUpperCase();
    }
  }
};
</script>
