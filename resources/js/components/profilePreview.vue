<template>
  <div class="modal fade" id="profile-preview-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Profile</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container">
            <!-- test -->
            <div class="row">
              <div class="col-md-3 p-0">
                <img :src="agent.image" alt>
              </div>
              <div class="col-md-9">
                <!-- NAME -->
                <div class="form-row">
                  <div class="col-md-9">
                    <small>Last Name.First Name.Middle Name</small>
                  </div>
                  <div class="col-md-3">
                    <small>ID no.</small>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-9">
                    <small>
                      <b>LAJOM, EMMANUEL JAMES ENG</b>
                    </small>
                  </div>
                  <div class="col-md-3">
                    <small>
                      <b>1000</b>
                    </small>
                  </div>
                </div>

                <!-- EMAIL -->
                <div class="form-row">
                  <div class="col">
                    <small>Email</small>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col">
                    <small>
                      <b>jamesenglajom@cnmsolutions.net</b>
                    </small>
                  </div>
                </div>

                <!-- Position -->
                <div class="form-row">
                  <div class="col-md-6">
                    <small>Position</small>
                  </div>
                  <div class="col-md-2">
                    <small>Gender</small>
                  </div>
                  <div class="col-md-2">
                    <small>Birth</small>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-6">
                    <small>
                      <b>OPERATIONS MANAGER</b>
                    </small>
                  </div>
                  <div class="col-md-2">
                    <small>
                      <b>MALE</b>
                    </small>
                  </div>
                  <div class="col-md-2">
                    <small>
                      <b>12/25/1991</b>
                    </small>
                  </div>
                </div>

                <!-- ADDRESS -->
                <div class="form-row">
                  <div class="col">
                    <small>Address</small>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col">
                    <small>
                      <b>LOWER BINUGAO, TORIL, DAVAO CITY</b>
                    </small>
                  </div>
                </div>

                <!-- CONTACT -->
                <div class="form-row">
                  <div class="col">
                    <small>Mobile</small>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col">
                    <small>
                      <b>093964*****</b>
                    </small>
                  </div>
                </div>
                <br>

                <div class="form-row">
                  <div class="col-md-6">
                    <small>PhilHealth</small>
                  </div>
                  <div class="col-md-6">
                    <small>SSS</small>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-6">
                    <small>
                      <b>Pht-00001</b>
                    </small>
                  </div>
                  <div class="col-md-6">
                    <small>
                      <b>SSS-00001</b>
                    </small>
                  </div>
                </div>

                <div class="form-row">
                  <div class="col-md-6">
                    <small>TIN</small>
                  </div>
                  <div class="col-md-6">
                    <small>PagIbig</small>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-6">
                    <small>
                      <b>TIN-00001</b>
                    </small>
                  </div>
                  <div class="col-md-6">
                    <small>
                      <b>PBG-00001</b>
                    </small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["userProfile"],
  data() {
    return {};
  },
  methods: {}
};
</script>