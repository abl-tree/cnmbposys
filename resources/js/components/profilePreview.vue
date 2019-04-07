<template>
  <modal name="profile_preview" :pivotY="0.2" :scrollable="true" height="auto">
    <div class="layer">
      <div class="e-modal-header bd">
        <h5 style="margin-bottom:0px">Incident Report</h5>
      </div>
      <div class="w-100 p-15 pT-80" style>
        <div class="container">
          <div class="row">
            <div class="col-md-3 p-0">
              <img v-if="profile.image" :src="profile.image" alt>
              <img v-else src="/images/nobody.jpg" alt>
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
                    <b>{{this.profile.name.last}}, {{profile.name.first+" "+profile.name.middle}}</b>
                  </small>
                </div>
                <div class="col-md-3">
                  <small>
                    <b>{{profile.id}}</b>
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
                    <b>{{profile.email}}</b>
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
                    <b>{{profile.position}}</b>
                  </small>
                </div>
                <div class="col-md-2">
                  <small>
                    <b>{{profile.gender}}</b>
                  </small>
                </div>
                <div class="col-md-2">
                  <small>
                    <b>{{profile.birth}}</b>
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
                    <b>{{profile.position}}</b>
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
                    <b>{{profile.mobile}}</b>
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
                    <b>{{profile.benefit.ph}}</b>
                  </small>
                </div>
                <div class="col-md-6">
                  <small>
                    <b>{{profile.benefit.sss}}</b>
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
                    <b>{{profile.benefit.tin}}</b>
                  </small>
                </div>
                <div class="col-md-6">
                  <small>
                    <b>{{profile.benefit.pibg}}</b>
                  </small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="e-modal-footer bd">
        <div style="text-align:right">
          <button class="btn btn-secondary" @click="hideModal('profile_preview')">Cancel</button>
        </div>
      </div>
    </div>
  </modal>
</template>

<script>
export default {
  props: ["userProfile"],
  mounted() {
    this.showProfile(this.userProfile);
  },
  data() {
    return {
      profile: {
        id: "",
        name: {
          first: "",
          middle: "",
          last: ""
        },
        gender: "",
        birth: "",
        address: "",
        mobile: "",
        email: "",
        position: "",
        benefit: {
          sss: "",
          tin: "",
          ph: "",
          pibg: ""
        },
        image: ""
      }
    };
  },
  methods: {
    showProfile: function(id) {
      let pageurl = "/api/v1/users/" + id;
      console.log(pageurl);
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          var obj = res.meta.metadata[0];
          console.log(obj.fname);

          this.profile.name.first = obj.fname;
          this.profile.name.middle = obj.mname;
          this.profile.name.last = obj.lname;
          this.profile.id = obj.id;
          this.profile.gender = obj.gender;
          // this.profile.birth = obj.
          this.profile.address = obj.address;
          this.profile.mobile = obj.contact;
          this.profile.email = obj.email;
          this.profile.position = obj.position;
          this.profile.benefit.sss = obj.benefits[0].id_number;
          this.profile.benefit.ph = obj.benefits[1].id_number;
          this.profile.benefit.tin = obj.benefits[2].id_number;
          this.profile.benefit.pibg = obj.benefits[3].id_number;
          this.profile.image = obj.image;
          console.log(this.profile);
        })
        .catch(err => console.log(err));
    }
  }
};
</script>