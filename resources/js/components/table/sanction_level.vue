
<template>
  <div class="bd bgc-white p-20 col mR-5">
    <div class="layers">
      <div class="layer w-100 mB-10">
        <div class="peers">
          <div class="peer peer-greed">
            <h6 class="lh-1">Sanction Levels</h6>
          </div>
          <div class="peer">
            <button
              class="btn bdrs-50p p-5 lh-0"
              @click="
              (form[tableName].action = 'create'),
              showModal(tableName)"
            >
              <i class="ti-plus"></i>
            </button>
          </div>
        </div>
      </div>
      <div class="row" v-if="isEmpty(table[tableName].data.options)">
        <i
          v-show="table[tableName].fetch_status=='fetching'"
          style="font-size:.7em;"
        >Fetching data...</i>
        <i
          v-show="table[tableName].fetch_status=='fetched'"
          style="font-size:.7em;"
        >Nothing to display...</i>
      </div>
      <div style="overflow-y:auto;height:200px;width:100%;overflow-x:hidden" v-else>
        <ul class="lis-n p-0 m-0 fsz-sm w-100">
          <li v-for="item in table[tableName].data.options" v-bind:key="item.id">
            <div class="row pY-10 bdT bdB mT-0 mB-0">
              <div class="col-sm-3">
                <div class="text-left pB-5" style="font-size:0.6em">Level</div>
                <div class="text-center">{{item.level_number}}</div>
              </div>
              <div class="col-sm-5">
                <div class="text-left pB-5" style="font-size:0.6em">Description</div>
                <div class="text-left">{{item.level_description}}</div>
              </div>
              <div class="col-2">
                <div class="text-right pB-5" style="font-size:0.6em">Action</div>
                <div class="text-right">
                  <i
                    class="btn btn-link ti-pencil"
                    @click="
                    (endpoints.update[tableName]=endpoints.tmp.update[tableName]+item.id),
                    (endpoints.delete[tableName]=endpoints.tmp.delete[tableName]+item.id),
                    (form[tableName].level =item.level_number),
                    (form[tableName].description =item.level_description),
                    (form[tableName].action = 'update'),
                    showModal(tableName)
                    "
                  ></i>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <!-- modal -->
    <!-- Sanction Type Form Modal -->
    <modal name="sanction_level" :pivotY="0.2" :scrollable="true" width="350px" height="auto">
      <div class="layer">
        <div class="e-modal-header bd">
          <h5 style="margin-bottom:0px">Sanction Level</h5>
        </div>
        <div class="w-100 p-15 pT-80" style>
          <div class="container">
            <form action>
              <div class="row pT-5">
                <div class="col-md-3">
                  <label>Number:</label>
                  <input
                    type="number"
                    class="form-control"
                    min="1"
                    step="1"
                    v-model="form[tableName].level"
                  >
                </div>
                <div class="col">
                  <label>Description:</label>
                  <input type="text" class="form-control" v-model="form[tableName].description">
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="e-modal-footer bd">
          <div class="row">
            <div class="peer peer-greed text-left pL-20">
              <button
                v-show="form[tableName].action=='update'"
                class="btn"
                @click="store({},'delete',tableName)"
              >Delete</button>
            </div>

            <div class="peer text-right pR-20">
              <button class="btn btn-secondary" @click="hideModal(tableName)">Cancel</button>
              <button
                class="btn btn-danger"
                @click="(form[tableName].level!=''&&form[tableName].description!='' ? 
                      store(
                        {
                          level_number: form[tableName].level,
                          level_description: form[tableName].description
                        },
                        form[tableName].action,
                        tableName
                            ) :
                      formValidationError())"
              >Confirm</button>
            </div>
          </div>
        </div>
      </div>
    </modal>
  </div>
</template>


​<style>
</style>

<script>
export default {
  mounted() {
    this.fetchTableObject(this.tableName);
  },
  created() {},
  data() {
    return {
      tableName: "sanction_level"
    };
  },
  methods: {}
};
</script>