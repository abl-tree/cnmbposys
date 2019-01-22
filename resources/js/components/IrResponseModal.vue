<template>
  <div
    id="ir-response-modal"
    class="modal fade"
    role="dialog"
    data-keyboard="false"
    data-backdrop="static"
  >
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">
            <small>
              <span id="employee-form-modal-header-title"></span> Incident Report
            </small>
          </h4>
        </div>
        <div class="modal-body">
          <div class="container">
            <div class="row">
              <div class="col">
                <div class="layer p-0">
                  <!-- level -->
                  <span class="badge badge-pill badge-primary">Written</span>
                  <!-- type -->
                  <span class="badge badge-pill badge-dark">Absentism</span>
                </div>
                <div class="layer p-5 bd mT-5 pT-20 pB-20 pL-10 pR-10">Dear Mr. Lajom,
                  <br>
                  <br>
                  <!-- message content -->
                  <div class="cntent">
                    <p style="text-align:justify">
                      "Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                      sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                      Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi
                      ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit
                      in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
                      occaecat cupidatat non proident,
                      sunt in culpa qui officia deserunt mollit anim id est laborum."
                    </p>

                    <p style="text-align:justify">
                      "Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                      sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                      Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi
                      ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit
                      in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
                      occaecat cupidatat non proident,
                      sunt in culpa qui officia deserunt mollit anim id est laborum."
                    </p>
                  </div>
                  <br>
                  <h6>Mr. Lajom</h6>Manager
                </div>
              </div>
              <div class="col" style="padding-top:25px">
                <textarea name id class="form-control" rows="15"></textarea>
                <div
                  class="alert-danger p-20 m-10"
                >You have 24hours to submit a response for this report. Refusal of response will be penalize.</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="col"></div>
          <div class="col-md-4">
            <div class="pull-right">
              <a class="btn btn-default" data-dismiss="modal">Cancel</a>
              <button id class="btn btn-danger push-right" style="color:white">Confirm</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("IR Response Modal");
  },
  data() {
    return {};
  }
};
</script>