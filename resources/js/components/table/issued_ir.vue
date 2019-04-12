
<template>
  <div class="layers pX-10">
    <div class="layer w-100 text-center" v-if="isEmpty(table[tabledata.tableName].data.reports)">
      <i
        v-show="table[tabledata.tableName].fetch_status=='fetching'"
        style="font-size:.7em;"
      >Fetching data...</i>
      <i
        v-show="table[tabledata.tableName].fetch_status=='fetched'"
        style="font-size:.7em;"
      >Nothing to display...</i>
    </div>

    <div v-else class="layer w-100" style="max-height:250px;overflow:auto">
      <div class="container bgc-blue-500">
        <div
          v-for="datum in table[tabledata.tableName].data.reports"
          :key="datum.id"
          class="row bdB bdT pY-15 bgc-green-500 pR-10"
        >
          <!-- v-for -->
          <div class="col-md-12 bgc-red-500">
            <div class="peers">
              <div class="peer mR-10">
                <img
                  v-if="datum.issued_to.image!=null"
                  class="bdrs-50p w-3r h-3r"
                  :src="datum.issued_to.image"
                >
                <img v-else class="bdrs-50p w-3r h-3r" src="/images/nobody.jpg">
              </div>
              <div class="peer peer-greed">
                <div class="container">
                  <div class="row peers m0 p0">
                    <div class="peer peer-greed" style="height:30px;display:table">
                      <span style="display:table-cell;vertical-align:middle">
                        <span class="fw-500" style="font-size:0.87em">{{datum.issued_to.full_name}}</span>
                      </span>
                    </div>
                    <div class="peer">
                      <button class="btn btn-xs bgc-white">
                        <i class="ti-eye"></i>
                      </button>
                    </div>
                  </div>
                  <div class="row peers m-0 p-0">
                    <div class="peer peer-greed">
                      <span
                        v-if="!isEmpty(datum.report_details.sanction_level)"
                        style="font-size:0.8em"
                      >{{datum.report_details.sanction_level.level_description}}</span>
                      <span v-else style="font-size:0.8em">
                        <i>Entry deleted.</i>
                      </span>
                      <span class="mX-5 bgc-grey" style="font-size:0.7em">&#9679;</span>
                      <span
                        v-if="!isEmpty(datum.report_details.sanction_type)"
                        style="font-size:0.8em"
                      >{{datum.report_details.sanction_type.type_description}}</span>
                      <span v-else style="font-size:0.8em">
                        <i>Entry deleted.</i>
                      </span>
                    </div>
                    <div class="peer">
                      <span
                        style="font-size:0.7em"
                        class="c-grey-700"
                      >{{split(datum.report_details.created_at.date,'.',0)}}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
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
    }
  }
};
</script>