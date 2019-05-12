
<template>
  <!-- #Sales Report ==================== -->
  <div id="parent" class="bd bgc-white">
    <div class="layers">
      <div class="layer w-100 pT-20 pX-20">
        <div class="peers">
          <div class="peer peer-greed">
            <h6 class="lh-100">{{config.table_name}}</h6>
          </div>
          <div class="peer mL-10">
            <button
              class="btn bdrs-50p p-5 lh-0"
              data-toggle="tooltip"
              :title="'Create '+config.table_name"
              @click="(form.action=='create'),showModal('sanction_level')"
            >
              <i class="ti-plus"></i>
            </button>
          </div>
        </div>
      </div>
      <div class="layer w-100 pX-20 pB-40">
        <div class="table-responsive pX-20 pB-20" style="height:200px;">
          <table class="table" style="table-layout:auto">
            <thead>
              <tr>
                <th class="bdwT-0">Number</th>
                <th class="bdwT-0">Description</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="datum in table.data"
                :key="datum.id"
                @click="(form.action='update'),(config.sanction_level.id=datum.id),(config.sanction_level.number=datum.level_number),(config.sanction_level.description=datum.level_description),showModal('sanction_level')"
              >
                <td>{{datum.level_number}}</td>
                <td>{{datum.level_description}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Sanction Type Form Modal -->
    <modal name="sanction_level" :pivotY="0.2" :scrollable="true" width="450px" height="auto">
      <div class="layer">
        <div class="e-modal-header bd">
          <h6 style="margin-bottom:0px">Sanction Level</h6>
        </div>
        <div class="w-100 p-15 pT-80" style>
          <div class="container">
            <form action>
              <div class="row pT-5">
                <div class="col-md-3">
                  <label>Number:</label>
                  <input
                    type="number"
                    class="form-control no-after"
                    min="1"
                    step="1"
                    v-model="config.sanction_level.number"
                  >
                </div>
                <div class="col">
                  <label>Description:</label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="config.sanction_level.description"
                  >
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="e-modal-footer bd">
          <div class="row">
            <div class="peer peer-greed text-left pL-20">
              <button
                v-show="form.action=='update'"
                class="btn"
                @click="storesanctionlevel({auth_id:user_id},'delete')"
              >Delete</button>
            </div>

            <div class="peer text-right pR-20">
              <button class="btn btn-secondary" @click="hideModal('sanction_level')">Cancel</button>
              <button
                class="btn btn-danger"
                @click="(config.sanction_level.number!=''&&config.sanction_level.description!='' ? 
                      storesanctionlevel(
                        {
                          level_number: config.sanction_level.number,
                          level_description: config.sanction_level.description,
                          auth_id:user_id
                        },form.action
                            ) :
                      formValidationError())"
                :disabled="form.submit_button.sanction_level"
              >Confirm</button>
            </div>
          </div>
        </div>
      </div>
    </modal>
    <profile-preview-modal v-bind:user-profile="this.userId"></profile-preview-modal>
    <notifications group="foo" animation-type="velocity" position="bottom right"/>
  </div>
</template>


​<style>
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
    this.fetchTableData();
  },
  created() {},
  data() {
    return {
      user_id: this.userId,
      access_id: this.accessId,
      // access_id: 4,
      config: {
        table_name: "Sanction Level",
        code: "sanction_level",
        curSort: { column: "date_filed", type: "name" },
        sorter: {
          issued_to: true,
          issued_by: true,
          type: true,
          level: true,
          date_filed: true
        },
        sanction_level: {
          id: "",
          number: "",
          description: ""
        },
        selected_page: 1,
        data: {
          all: []
        },
        filter: {
          data: [],
          paginate: {
            page: 1,
            perpage: 5
          },
          no_records: 5
        }
      },
      form: {
        submit_button: {
          sanction_level: false
        },
        action: "create"
      },
      table: {
        data: []
      }
    };
  },
  methods: {
    fetchTableData: function() {
      let pageurl = "/api/v1/sanction_level/sanction_levels";
      let userid = this.user_id;
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          this.table.data = res.meta.options;
        })
        .catch(err => {
          console.log(err);
        });
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
    storesanctionlevel: function(obj, action) {
      this.form.submit_button.sanction_level = true;
      let pageurl;
      if (action == "create") {
        pageurl = "/api/v1/sanction_level/create";
      } else if (action == "update") {
        pageurl =
          "/api/v1/sanction_level/update/" + this.config.sanction_level.id;
      } else if (action == "delete") {
        pageurl =
          "/api/v1/sanction_level/delete/" + this.config.sanction_level.id;
      }
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
          this.form.submit_button.sanction_level = false;
          if (data.code == 500) {
            this.notify("error", action);
          } else {
            console.log(data);
            this.fetchTableData();
            this.hideModal("sanction_level");
            this.notify("success", action);
            // this.saveLog('succuss', formName, action, data);
          }
        })
        .catch(err => console.log(err));
    }
  }
};
</script>