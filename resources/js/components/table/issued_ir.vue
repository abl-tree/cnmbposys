
<template>
  <div class="layers w-100 pX-10">
    <div class="layer w-100">
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
      <div v-else class="layer w-100">
        <div
          v-for="datum in table[tabledata.tableName].data.reports"
          :key="datum.id"
          class="peers bdB bdT pY-15"
        >
          <!-- v-for -->
          <div class="peer mR-10">
            <img class="bdrs-50p w-3r h-3r" src="/images/nobody.jpg">
          </div>
          <div class="peer peer-greed">
            <div class="container">
              <div class="row peers m0 p0">
                <div class="peer peer-greed" style="height:30px;display:table">
                  <span style="display:table-cell;vertical-align:middle">
                    <span class="fw-500" style="font-size:0.87em">Emmanuel James Lajom</span>
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
                  <span style="font-size:0.8em">Written</span>
                  <span class="mX-5 bgc-grey" style="font-size:0.7em">&#9679;</span>
                  <span style="font-size:0.8em">Absentism</span>
                </div>
                <div class="peer">
                  <span style="font-size:0.7em" class="c-grey-700">2019/04/10 10:10:10</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- <h1>ISSUED</h1> -->
    </div>
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