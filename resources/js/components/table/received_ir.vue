
<template>
  <div class="layers pX-10" style="height:auto">
    <div
      class="layer w-100 pY-20 text-center"
      v-if="isEmpty(table[tabledata.tableName].data.reports)"
    >
      <i
        v-show="table[tabledata.tableName].fetch_status=='fetching'"
        class="fsz-xs"
      >Fetching data...</i>
      <i
        v-show="table[tabledata.tableName].fetch_status=='fetched'"
        class="fsz-xs"
      >Nothing to display...</i>
    </div>

    <div v-else class="layer w-100">
      <!-- <div class="input-group">
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
      </div>-->
      <div class="table-responsive pY-20">
        <table class="table">
          <thead>
            <tr>
              <th class="bdwT-0">Image</th>
              <th class="bdwT-0">Name</th>
              <th class="bdwT-0">Sanction Type</th>
              <th class="bdwT-0">Sanction Level</th>
              <th class="bdwT-0">Date Filed</th>
              <th class="bdwT-0">Filed By</th>
              <th class="bdwT-0">IR Count</th>
              <th class="bdwT-0">Response</th>
              <!-- <th class="bdwT-0">Billable</th>
              <th class="bdwT-0">Action</th>-->
            </tr>
          </thead>
          <tbody>
            <tr v-for="datum in table[tabledata.tableName].data.reports" :key="datum.id">
              <td style="font-size:.95em">
                <img
                  v-if="datum.issued_to.image!=null"
                  class="bdrs-50p w-3r h-3r"
                  :src="datum.issued_to.image"
                >
                <img v-else class="bdrs-50p w-3r h-3r" src="/images/nobody.jpg">
              </td>
              <td style="font-size:.95em">
                <div class="fw-600">
                  <i class="ti-user c-grey-400 mR-5"></i>
                  {{datum.issued_to.full_name}}
                </div>
                <div>
                  <i class="ti-email c-grey-400 mR-5"></i>
                  {{split(datum.issued_to.email,'@',0)}}
                </div>
                <div class="c-light-blue-400">@cnmsolutions.net</div>
              </td>
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

              <td style="font-size:.95em">
                <div class="fw-600">
                  <i class="ti-user c-grey-400 mR-5"></i>
                  {{datum.issued_by.full_name}}
                </div>
                <div>
                  <i class="ti-email c-grey-400 mR-5"></i>
                  {{split(datum.issued_by.email,'@',0)}}
                </div>
                <div class="c-light-blue-400">@cnmsolutions.net</div>
              </td>
              <td style="font-size:.95em">
                <div
                  class="fw-600 badge badge-pill bgc-indigo-100 c-indigo-800 w-100 fsz-xs"
                >{{"2"}}</div>
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
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- <h1>ISSUED</h1> -->
  </div>
</template>


​<style>
</style>

<script>
export default {
  props: ["obj", "userId"],
  mounted() {
    console.log("userID = " + this.user_id);
    this.initialize();
  },
  created() {},
  data() {
    return {
      user_id: this.userId,
      tabledata: this.obj
    };
  },
  methods: {
    initialize: function() {
      this.endpoints.table[this.tabledata.tableName] =
        this.tabledata.endpoint.retreive + this.user_id;
      this.fetchTableObject(this.tabledata.tableName);
    },
    splitEmail: function(email) {
      return email.split("@")[0];
    }
  }
};
</script>