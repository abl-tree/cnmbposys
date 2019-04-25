<template>
  <modal
    name="daily-work-report-modal"
    :pivotY="0.2"
    :scrollable="true"
    width="500px"
    height="auto"
  >
    <div class="layer">
      <div class="e-modal-header bd">
        <h5 style="margin-bottom:0px">Work Report</h5>
      </div>
      <div class="w-100 p-15 pT-80" style>
        <div class="container">
          <div class="row">
            <div class="col-sm-2 pX-0 text-center">
              <img class="bdrs-50p w-3r h-3r" src="/images/nobody.jpg">
            </div>
            <div class="col-sm-7 pX-0">
              <div class="text-group">
                <div class="p-0 m-0 fw-500">Avalo Kitesva Rapad Mapani</div>
                <div class="p-0 m-0">jamesenglajom@gmail.com</div>
              </div>
              <div class="text-group p-0 mT-15">
                <div class="fsz-xs fw-300">Schedule Details</div>
                <div class="p-0 m-0 fsz-xs">Monday Jan. 25, 2019</div>
                <div class="fsz-xs">08:00am - 04:00pm</div>
              </div>
            </div>
            <div class="col-sm-3 text-center pX-0">
              <small class="p-0 m-0">Log Details</small>
              <h6 class="mY-0">
                <span class="badge badge-pill badge-info fw-900 w-100">08:00am - 03:00pm</span>
              </h6>
              <h6 class="mY-0">
                <span class="badge badge-pill badge-success fw-900 w-100">PRESENT</span>
              </h6>
              <h6 class="mY-0">
                <span class="badge badge-pill badge-success fw-900 w-100">READY</span>
              </h6>
              <h6 class="mY-0">
                <span class="badge badge-pill badge-danger fw-900 w-100">UNDERTIME</span>
              </h6>
            </div>
          </div>
        </div>
      </div>
      <div class="e-modal-footer bd">
        <div class="row">
          <div class="layer w-100 text-right pR-20">
            <button class="btn btn-secondary" @click="hideModal('daily-work-report-modal')">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </modal>
</template>
<script>
export default {
  props: [],
  mounted() {},
  data() {
    return {};
  }
};
</script>
