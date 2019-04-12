
<template>
  <div class="w-100">
    <div class="text-center">
      <!-- ul -->
      <!-- <div class="row" v-if="isEmpty(tabs.data)">
        <i
          v-show="table[tabledata.tableName].fetch_status=='fetching'"
          style="font-size:.7em;"
        >Fetching data...</i>
        <i
          v-show="table[table.tableName].fetch_status=='fetched'"
          style="font-size:.7em;"
        >Nothing to display...</i>
      </div>-->
      <h1>RECEIVED</h1>
    </div>
  </div>
</template>


​<style>
</style>

<script>
export default {
  props: ["obj"],
  mounted() {
    this.tabledata = this.obj;
  },
  created() {},
  data() {
    return {
      tabledata: []
    };
  },
  methods: {}
};
</script>