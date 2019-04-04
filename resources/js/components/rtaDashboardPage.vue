<template>
  <div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>

    <div class="masonry-item w-100">
      <div class="row gap-20">
        <!-- #Toatl Visits ==================== -->
        <div class="col-md-3">
          <div class="layers bd bgc-white p-20">
            <div class="layer w-100 mB-10">
              <h6 class="lh-1">Scheduled</h6>
            </div>
            <div class="layer w-100">
              <div class="peers ai-sb fxw-nw">
                <div class="peer peer-greed">
                  <span id="sparklinedash"></span>
                </div>
                <div class="peer">
                  <span
                    class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500"
                  >+10%</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- #Total Page Views ==================== -->
        <div class="col-md-3">
          <div class="layers bd bgc-white p-20">
            <div class="layer w-100 mB-10">
              <h6 class="lh-1">Working</h6>
            </div>
            <div class="layer w-100">
              <div class="peers ai-sb fxw-nw">
                <div class="peer peer-greed">
                  <span id="sparklinedash2"></span>
                </div>
                <div class="peer">
                  <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-red-50 c-red-500">-7%</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- #Unique Visitors ==================== -->
        <div class="col-md-3">
          <div class="layers bd bgc-white p-20">
            <div class="layer w-100 mB-10">
              <h6 class="lh-1">Absent</h6>
            </div>
            <div class="layer w-100">
              <div class="peers ai-sb fxw-nw">
                <div class="peer peer-greed">
                  <span id="sparklinedash3"></span>
                </div>
                <div class="peer">
                  <span
                    class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-purple-50 c-purple-500"
                  >~12%</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- #Bounce Rate ==================== -->
        <div class="col-md-3">
          <div class="layers bd bgc-white p-20">
            <div class="layer w-100 mB-10">
              <h6 class="lh-1">Off-duty</h6>
            </div>
            <div class="layer w-100">
              <div class="peers ai-sb fxw-nw">
                <div class="peer peer-greed">
                  <span id="sparklinedash4"></span>
                </div>
                <div class="peer">
                  <span
                    class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500"
                  >33%</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="masonry-item col-md-6">
      <!-- #Sales Report ==================== -->
      <div class="bd bgc-white">
        <div class="layers">
          <div class="layer w-100 p-20">
            <h6 class="lh-1">Today's Activity</h6>
          </div>
          <div class="layer w-100">
            <div class="bgc-light-blue-500 c-white p-20">
              <div class="peers ai-c jc-sb gap-40">
                <div class="peer peer-greed">
                  <h5></h5>
                  <!-- <p class="mB-0">Today's Activity</p> -->
                </div>
                <div class="peer">
                  <h3 class="text-right">$6,000</h3>
                </div>
              </div>
            </div>
            <div class="table-responsive p-20">
              <table class="table">
                <thead>
                  <tr>
                    <th class="bdwT-0">Agent Name</th>
                    <th class="bdwT-0">Status</th>
                    <th class="bdwT-0">Work Time</th>
                    <th class="bdwT-0">Break Time</th>
                    <th class="bdwT-0">Breakdown</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="fw-600">Item #1 Name</td>
                    <td>
                      <span class="badge bgc-red-50 c-red-700 p-10 lh-0 tt-c badge-pill">Unavailable</span>
                    </td>
                    <td>Nov 18</td>
                    <td>
                      <span class="text-success">$12</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-600">Item #2 Name</td>
                    <td>
                      <span
                        class="badge bgc-deep-purple-50 c-deep-purple-700 p-10 lh-0 tt-c badge-pill"
                      >New</span>
                    </td>
                    <td>Nov 19</td>
                    <td>
                      <span class="text-info">$34</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-600">Item #3 Name</td>
                    <td>
                      <span class="badge bgc-pink-50 c-pink-700 p-10 lh-0 tt-c badge-pill">New</span>
                    </td>
                    <td>Nov 20</td>
                    <td>
                      <span class="text-danger">-$45</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-600">Item #4 Name</td>
                    <td>
                      <span
                        class="badge bgc-green-50 c-green-700 p-10 lh-0 tt-c badge-pill"
                      >Unavailable</span>
                    </td>
                    <td>Nov 21</td>
                    <td>
                      <span class="text-success">$65</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-600">Item #5 Name</td>
                    <td>
                      <span class="badge bgc-red-50 c-red-700 p-10 lh-0 tt-c badge-pill">Used</span>
                    </td>
                    <td>Nov 22</td>
                    <td>
                      <span class="text-success">$78</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-600">Item #6 Name</td>
                    <td>
                      <span class="badge bgc-orange-50 c-orange-700 p-10 lh-0 tt-c badge-pill">Used</span>
                    </td>
                    <td>Nov 23</td>
                    <td>
                      <span class="text-danger">-$88</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-600">Item #7 Name</td>
                    <td>
                      <span class="badge bgc-yellow-50 c-yellow-700 p-10 lh-0 tt-c badge-pill">Old</span>
                    </td>
                    <td>Nov 22</td>
                    <td>
                      <span class="text-success">$56</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="ta-c bdT w-100 p-20">
          <a href="#">Check all the activities</a>
        </div>
      </div>
    </div>

    <div class="masonry-item col-md-6">
      <!-- #Todo ==================== -->
      <div class="bd bgc-white p-20">
        <h6 class="c-grey-900">Request</h6>
        <div class="mT-30">
          <div class="list-group" style="font-size:0.83em">
            <div class="list-group-item list-group-item-action">
              <div>
                <b>Ice Cream</b> requested for
                <span class="text-danger">Leave Type 1</span> under supervision of
                <b>Mango Float</b> dated 02-18-2019 to 02-25-2019
                <br>
              </div>
              <div class="peers">
                <span class="peer peer-greed">
                  <span class="text-muted">
                    <small>Request Date: 01-03-2019 12:00:00</small>
                  </span>
                </span>
                <span class="peer">
                  <button class="btn btn-outline-secondary btn-custom-size">
                    <i class="ti-close"></i>
                  </button>
                  <button class="btn btn-outline-danger btn-custom-size">Manage</button>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="bd bgc-white p-20 mY-20">
        <div class="layers">
          <div class="layer w-100 mB-10">
            <div class="peers">
              <div class="peer peer-greed">
                <h6 class="lh-1">Incident Reports</h6>
              </div>
              <div class="peer">
                <button
                  class="btn btn-secondary btn-custom-size"
                  @click="clearForm('incident_report'),
                  fetchSelectOptions(endpoints.select.child_list,'incident_report','child_list'),
                  fetchSelectOptions(endpoints.select.sanction_level,'incident_report','sanction_level'),
                  fetchSelectOptions(endpoints.select.sanction_type,'incident_report','sanction_type'),
                  showModal('incident_report')"
                >
                  <i class="ti-plus"></i>
                </button>
              </div>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>IR No.</th>
                  <th>Date</th>
                  <th>Level</th>
                  <th>Type</th>
                  <th>Reply</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    2
                    <span class="badge badge-pill badge-warning">NEW!</span>
                  </td>
                  <td>10/20/18</td>
                  <td>Written</td>
                  <td>Absentism</td>
                  <td>
                    <span class="badge badge-pill badge-danger">NO Reply</span>
                  </td>
                  <td>
                    <div class="btn-group">
                      <button class="btn btn-xs btn-info ti-eye view-employee btn-custom-size"></button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>1</td>
                  <td>10/01/18</td>
                  <td>Verbal</td>
                  <td>Absentism</td>
                  <td>
                    <span class="badge badge-pill badge-success">10/02/18</span>
                  </td>
                  <td>
                    <div class="btn-group">
                      <button
                        class="btn btn-xs btn-info ti-eye view-employee"
                        data-toggle="modal"
                        data-target="#ir-response-modal"
                      ></button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div>
        <div class="container">
          <div class="row">
            <!-- Sanction Levels -->
            <div class="bd bgc-white p-20 col mR-5">
              <div class="layers">
                <div class="layer w-100 mB-10">
                  <div class="peers">
                    <div class="peer peer-greed">
                      <h6 class="lh-1">Sanction Levels</h6>
                    </div>
                    <div class="peer">
                      <button
                        class="btn btn-secondary btn-custom-size"
                        @click="showModal('sanction_level')"
                      >
                        <i class="ti-plus"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="table-responsive table-scroll-200">
                  <table class="table table-scroll-200">
                    <thead>
                      <tr>
                        <th>Level</th>
                        <th>Description</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="levels in table.sanction_level" v-bind:key="levels.id">
                        <td>{{levels.level_number}}</td>
                        <td>{{levels.level_description}}</td>
                        <td>
                          <button class="btn btn-dark btn-custom-size">
                            <i class="ti-pencil"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <!-- Sanction Types -->
            <div class="bd bgc-white p-20 col mL-5">
              <div class="layers">
                <div class="layer w-100 mB-10">
                  <div class="peers">
                    <div class="peer peer-greed">
                      <h6 class="lh-1">Sanction Types</h6>
                    </div>
                    <div class="peer">
                      <button
                        class="btn btn-secondary btn-custom-size"
                        @click="showModal('sanction_type')"
                      >
                        <i class="ti-plus"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="table-scroll-200 table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Type ID</th>
                        <th>Description</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="types in table.sanction_type" v-bind:key="types.id">
                        <td>{{types.type_number}}</td>
                        <td>{{types.type_description}}</td>
                        <td>
                          <button class="btn btn-dark btn-custom-size">
                            <i class="ti-pencil"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <ir-response-modal></ir-response-modal>

    <!-- IR Form Modal -->
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
    <!-- Sanction Level Form Modal -->
    <modal name="sanction_level" :pivotY="0.2" :scrollable="true" height="auto">
      <div class="layer">
        <div class="e-modal-header bd">
          <h5 style="margin-bottom:0px">Sanction Level</h5>
        </div>
        <div class="w-100 p-15 pT-80" style>
          <div class="container">
            <form action>
              <div class="row pT-5">
                <div class="col">
                  <label>Number:</label>
                  <input
                    type="number"
                    class="form-control"
                    min="1"
                    step="1"
                    v-model="form.sanction_level.level"
                  >
                </div>
                <div class="col">
                  <label>Description:</label>
                  <input type="text" class="form-control" v-model="form.sanction_level.description">
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="e-modal-footer bd">
          <div style="text-align:right">
            <button class="btn btn-secondary" @click="hideModal('sanction_level')">Cancel</button>
            <button
              class="btn btn-danger"
              @click="submitForm('sanction_level',form.sanction_level.action)"
            >Confirm</button>
          </div>
        </div>
      </div>
    </modal>
    <!-- Sanction Form Modal -->
    <modal name="sanction_type" :pivotY="0.2" :scrollable="true" height="auto">
      <div class="layer">
        <div class="e-modal-header bd">
          <h5 style="margin-bottom:0px">Sanction Level</h5>
        </div>
        <div class="w-100 p-15 pT-80" style>
          <div class="container">
            <form action>
              <div class="row pT-5">
                <div class="col">
                  <label>No:</label>
                  <basic-select placeholder="Level"></basic-select>
                </div>
                <div class="col">
                  <label>Description:</label>
                  <basic-select placeholder="Type"></basic-select>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="e-modal-footer bd">
          <div style="text-align:right">
            <button class="btn btn-secondary" @click="hideModal('sanction_type')">Cancel</button>
            <button class="btn btn-danger">Confirm</button>
          </div>
        </div>
      </div>
    </modal>
    <notifications group="foo" animation-type="velocity" position="bottom right"/>
  </div>
</template>

<script>
import { BasicSelect } from "vue-search-select";
import { ModelSelect } from "vue-search-select";

export default {
  props: ["userId"],
  components: {
    BasicSelect,
    ModelSelect
  },
  mounted() {
    this.fetchTableObject("sanction_level");
    this.fetchTableObject("sanction_type");
    console.log(this.user_id);
  },
  data() {
    return {
      user_id: this.userId,
      endpoints: {
        get: {
          sanction_level: "/api/v1/reports/sanction_levels",
          sanction_type: "/api/v1/reports/sanction_types",
          child_list: "/api/v1/reports/all_users",
          received_incident_report: "/api/v1/reports/user/" + this.user_id,
          filed_incident_report: "/api/v1/reports/user_filed_ir/" + this.user_id
        },
        create: {
          sanction_level: "/api/v1/reports/add_sanction_level",
          sanction_type: "/api/v1/reports/add_sanction_type"
        },
        table: {
          sanction_level: "/api/v1/reports/sanction_levels",
          sanction_type: "/api/v1/reports/sanction_types",
          received_incident_report: "/api/v1/reports/user/" + this.user_id,
          filed_incident_report: "/api/v1/reports/user_filed_ir/" + this.user_id
        },
        select: {
          sanction_level: "/api/v1/reports/select_sanction_levels",
          sanction_type: "/api/v1/reports/select_sanction_types",
          child_list: "/api/v1/reports/select_all_users/1"
        }
      },
      form: {
        incident_report: {
          select_option: {
            sanction_level: [],
            sanction_type: [],
            child_list: []
          },
          selected: {
            sanction_level: [],
            sanction_type: [],
            child_list: []
          },
          textarea: "",
          action: "create"
        },
        sanction_level: {
          level: "",
          description: "",
          action: "create"
        },
        sanction_type: {
          type: "",
          description: "",
          action: "create"
        }
      },
      table: {
        received_incident_report: [],
        sanction_level: [],
        sanction_type: []
      }
    };
  },
  methods: {
    fetchSelectOptions: function(url, formName, element) {
      let pageurl = url;
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          if (res.code == 200) {
            this.form[formName].select_option[element] = res.meta.options;
          }
          // console.log(res.meta.options);
        })
        .catch(err => console.log(err));
    },

    fetchTableObject: function(tableName) {
      let pageurl = this.endpoints.table[tableName];
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          // console.log(res);
          if (res.code == 200) {
            this.table[tableName] = res.meta.options;
          }
        })
        .catch(err => console.log(err));
    },

    clearForm: function(formName) {
      switch (formName) {
        case "incident_report":
          this.form.incident_report.selected.sanction_level = [];
          this.form.incident_report.selected.sanction_type = [];
          this.form.incident_report.selected.child_list = [];
          break;
        case "sanction_level":
          this.form.sanction_level.level = "";
          this.form.sanction_level.description = "";
          break;
        case "sanction_type":
          this.form.sanction_type.type = "";
          this.form.sanction_type.description = "";
          break;
      }
    },

    submitForm: function(formName, action) {
      let obj = [];
      let pageurl = "";
      let validated = false;
      switch (formName) {
        case "incident_report":
          console.log(this.form.incident_report.textarea);
          break;

        case "sanction_level":
          if (
            this.form.sanction_level.level != "" &&
            this.form.sanction_level.description != ""
          ) {
            validated = true;
            pageurl = this.endpoints[action].sanction_level;
            obj = {
              level_number: this.form.sanction_level.level,
              level_description: this.form.sanction_level.description
            };
          }
          break;

        case "sanction_type":
          if (
            this.form.sanction_type.type != "" &&
            this.form.sanction_type.description != ""
          ) {
            validated = true;
            pageurl = this.endpoints[action].sanction_type;
            obj = {
              type_number: this.form.sanction_type.type,
              type_description: this.form.sanction_type.description
            };
          }
          break;
      }
      if (validated == true) {
        this.store(obj, pageurl);
        this.fetchTableObject(formName);
        this.clearForm(formName);
        this.hideModal(formName);
        this.notify();
      }
    },

    store: function(obj, pageurl) {
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
        })
        .catch(err => console.log(err));
    },
    notify: function(status) {
      this.$notify({
        group: "foo",
        title: "Success Notification",
        text:
          "Your database has been updated!<br/><small>CNM Solutions WebApp</small>",
        type: "success"
      });
    }
  }
};
</script>