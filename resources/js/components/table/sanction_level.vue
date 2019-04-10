
<template>
  <div class="bd bgc-white p-20 col mR-5">
    <div class="layers">
      <div class="layer w-100 mB-10">
        <div class="peers">
          <div class="peer peer-greed">
            <h6 class="lh-1">Sanction Levels</h6>
          </div>
          <div class="peer">
            <button class="btn bdrs-50p p-5 lh-0" @click="showModal('sanction_level')">
              <i class="ti-plus"></i>
            </button>
          </div>
        </div>
      </div>
      <div class="row" v-if="isEmpty(table.sanction_level.data.options)">
        <i
          v-show="table.sanction_level.fetch_status=='fetching'"
          style="font-size:.7em;"
        >Fetching data...</i>
        <i
          v-show="table.sanction_level.fetch_status=='fetched'"
          style="font-size:.7em;"
        >Nothing to display...</i>
      </div>
      <div style="overflow-y:auto;height:200px;width:100%;overflow-x:hidden" v-else>
        <ul class="lis-n p-0 m-0 fsz-sm w-100">
          <li v-for="levels in table.sanction_level.data.options" v-bind:key="levels.id">
            <div class="row pY-10 bdT bdB mT-0 mB-0">
              <div class="col-sm-3">
                <div class="text-left pB-5" style="font-size:0.6em">Level</div>
                <div class="text-center">{{levels.level_number}}</div>
              </div>
              <div class="col-sm-5">
                <div class="text-left pB-5" style="font-size:0.6em">Description</div>
                <div class="text-left">{{levels.level_description}}</div>
              </div>
              <div class="col-2">
                <div class="text-right pB-5" style="font-size:0.6em">Action</div>
                <div class="text-right">
                  <i class="btn btn-link ti-pencil"></i>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <!-- modal -->
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
              @click="(form.sanction_level.level!=''&&form.sanction_level.description!='' ? 
                      store(
                        {
                          level_number: form.sanction_level.level,
                          level_description: form.sanction_level.description
                        },
                        form.sanction_level.action,
                        'sanction_level'
                            ) :
                      formValidationError())"
            >Confirm</button>
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
    this.fetchTableObject("sanction_level");
  },
  created() {},
  data() {
    return {
      fetch_status: "fetching"
    };
  },
  methods: {}
};
</script>