<template>
  <div class="full-container">
    <div class="layers">
      <div class="layer bgc-white w-100">
        <div class="row">
          <div class="col-10 pR-0 mR-0 pB-50" style="overflow-y:auto">
            <div class="bgc-white p-20 bdB">
              <h6 class="c-grey-900">Company Information</h6>
              <div class="mT-30">
                <div class="container">
                  <div class="row">
                    <div class="col-md-3">
                      <img :src="employeeComponent.vueCam.img" alt>
                      <div class="btn-group mT-10 w-100">
                        <input
                          type="file"
                          style="display:none"
                          ref="inputImage"
                          @input="imageSelect"
                          accept=".png, .jpg, .jpeg"
                        >
                        <button class="btn-light btn" @click="$refs.inputImage.click()">Browse</button>
                        <button
                          class="btn-light btn"
                          @click="showModal('camera-modal'),
                            employeeComponent.vueCam.autoplay = true"
                        >Camera</button>
                      </div>
                    </div>
                    <div class="col">
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label for="inputState">Position</label>
                          <select
                            id="inputState"
                            class="form-control"
                            v-model="employeeComponent.form.model.position"
                            @change="positionChanged"
                          >
                            <option
                              v-for="position in employeeComponent.form.options.position"
                              :key="position.id"
                              :value="position"
                            >{{ position.name }}</option>
                          </select>
                        </div>
                        <div class="col-md-6 mb-3">
                          <label>Head</label>
                          <select
                            id="inputState"
                            class="form-control"
                            v-model="employeeComponent.form.model.head"
                          >
                            <option
                              v-for="head in employeeComponent.form.options.head"
                              :key="head.id"
                              :value="head"
                            >{{ head.full_name }}</option>
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col mb-3">
                          <label>Email</label>
                          <input
                            name="Company Email"
                            v-model="employeeComponent.form.model.company_email"
                            class="form-control"
                            :class="{'input': true, 'border-danger': errors.has('Company Email') }"
                            type="email"
                            placeholder="sample@cnmsolutions.net"
                          >
                          <div
                            v-show="errors.has('Company Email')"
                            class="text-danger fsz-xs"
                          >{{ errors.first('Company Email') }}</div>
                        </div>
                        <div class="col mb-3">
                          <label>Hired Date</label>
                          <vue-datepicker
                            name="Hired Date"
                            :input-class="{'form-control bgc-white':true, 'border-danger':errors.has('Hired Date')}"
                            placeholder="Hired Date"
                            :clear-button="true"
                            v-model="employeeComponent.form.model.hired_date"
                          ></vue-datepicker>
                          <div
                            v-show="errors.has('Hired Date')"
                            class="text-danger fsz-xs"
                          >{{ errors.first('Hired Date') }}</div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 mb-3">
                          <label>CNM ID</label>
                          <input
                            name="CNM ID"
                            :class="{'border-danger': errors.has('CNM ID')}"
                            type="numeric"
                            step="1"
                            class="form-control"
                            placeholder="CNM ID"
                            v-model="employeeComponent.form.model.cnm_id"
                          >
                          <div
                            v-show="errors.has('CNM ID')"
                            class="text-danger fsz-xs"
                          >{{ errors.first('CNM ID') }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                          <label>Status</label>
                          <select
                            id="inputState"
                            class="form-control"
                            v-model="employeeComponent.form.model.status"
                          >
                            <option value="new" selected>NEW</option>
                            <option value="active">ACTIVE</option>
                          </select>
                        </div>
                        <div class="col-md-4 mb-3">
                          <label>Contract</label>
                          <input
                            type="text"
                            class="form-control"
                            placeholder="Contract"
                            v-model="employeeComponent.form.model.contract"
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="bgc-white p-20 bdB">
              <h6 class="c-grey-900">Personal Information</h6>
              <div class="mT-30">
                <div class="container">
                  <div class="row">
                    <div class="col mb-3">
                      <label>First name</label>
                      <input
                        name="First Name"
                        :class="{'border-danger':errors.has('First Name')}"
                        type="text"
                        class="form-control"
                        placeholder="First name"
                        v-model="employeeComponent.form.model.firstname"
                      >
                      <div
                        v-show="errors.has('First Name')"
                        class="text-danger fsz-xs"
                      >{{ errors.first('First Name') }}</div>
                    </div>
                    <div class="col mb-3">
                      <label>Middle name</label>
                      <input
                        name="Middle Name"
                        :class="{'border-danger': errors.has('Middle Name')}"
                        type="text"
                        class="form-control"
                        placeholder="Middle name"
                        v-model="employeeComponent.form.model.middlename"
                      >
                      <div
                        v-show="errors.has('Middle Name')"
                        class="text-danger fsz-xs"
                      >{{ errors.first('Middle Name') }}</div>
                    </div>
                    <div class="col mb-3">
                      <label>Last name</label>
                      <input
                        name="Last Name"
                        :class="{'border-danger':errors.has('Last Name')}"
                        type="text"
                        class="form-control"
                        placeholder="Last name"
                        v-model="employeeComponent.form.model.lastname"
                      >
                      <div
                        v-show="errors.has('Last Name')"
                        class="text-danger fsz-xs"
                      >{{ errors.first('Last Name') }}</div>
                    </div>
                    <div class="col mb-3">
                      <label>Suffix</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Sr., Jr., I, II, III etc.."
                        v-model="employeeComponent.form.model.suffix"
                      >
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <label>Address</label>
                      <input
                        name="Address"
                        :class="{'border-danger': errors.has('Address')}"
                        type="text"
                        class="form-control"
                        placeholder="Addess"
                        v-model="employeeComponent.form.model.address"
                      >
                      <div
                        v-show="errors.has('Address')"
                        class="text-danger fsz-xs"
                      >{{ errors.first('Address') }}</div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3 mb-3">
                      <label>Mobile</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Mobile"
                        v-model="employeeComponent.form.model.mobile"
                      >
                      <div class="invalid-feedback">Please provide a valid state.</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <label>Personal Email</label>
                      <input
                        type="email"
                        class="form-control"
                        placeholder="sample@anotherdomain.com"
                        v-model="employeeComponent.form.model.personal_email"
                      >
                    </div>
                    <div class="col-md-3 mb-3">
                      <label>Birth Date</label>
                      <vue-datepicker
                        name="Birth Date"
                        :input-class="{'form-control bgc-white':true, 'border-danger':errors.has('Birth Date')}"
                        class="bgc-white"
                        placeholder="Birth Date"
                        :clear-button="true"
                        v-model="employeeComponent.form.model.birthdate"
                      ></vue-datepicker>
                      <div
                        v-show="errors.has('Birth Date')"
                        class="text-danger fsz-xs"
                      >{{ errors.first('Birth Date') }}</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="inputState">Gender</label>
                      <select
                        id="inputState"
                        class="form-control"
                        v-model="employeeComponent.form.model.gender"
                      >
                        <option value="male" selected>Male</option>
                        <option value="female">Female</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="bgc-white p-20 bdB">
              <h6 class="c-grey-900">Benifit Numbers</h6>
              <div class="mT-30">
                <div class="container">
                  <div class="row">
                    <div class="col mb-3">
                      <label>SSS</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="SSS"
                        v-model="employeeComponent.form.model.sss"
                      >
                    </div>
                    <div class="col mb-3">
                      <label>PhilHealth</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="PhilHealth"
                        v-model="employeeComponent.form.model.philhealth"
                      >
                    </div>
                    <div class="col mb-3">
                      <label>PagIbig</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="PagIbig"
                        v-model="employeeComponent.form.model.pagibig"
                      >
                    </div>
                    <div class="col mb-3">
                      <label>TIN</label>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="TIN"
                        v-model="employeeComponent.form.model.tin"
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="errors.items.length>0" class="bgc-white p-20 bdB">
              <div class="alert alert-danger">
                <h6>Something's Wrong!</h6>
                <span>Please fill all the required fields.</span>
              </div>
            </div>
            <div class="bgc-white p-20 bdB">
              <div class="row">
                <div class="col">
                  <div class="pull-right">
                    <button class="btn btn-danger" @click="formSubmit">Confirm</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col bgc-grey-200">dsadsa</div>
        </div>
      </div>
    </div>
    <modal name="camera-modal" :pivotY="0.2" :draggable="true" height="auto" :clickToClose="false">
      <div class="layer">
        <div class="w-100 p-15" style>
          <div class="container">
            <div class="row">
              <div class="col-6">
                <select
                  v-if="isEmpty(employeeComponent.vueCam.devices)"
                  class="select form-control"
                  disabled
                >
                  <option>No Device Found</option>
                </select>
                <select
                  v-else
                  v-model="employeeComponent.vueCam.camera"
                  class="select form-control"
                >
                  <option
                    v-for="device in employeeComponent.vueCam.devices"
                    :key="device.deviceId"
                    :value="device.deviceId"
                  >{{ device.label }}</option>
                </select>
              </div>
              <div class="col-6">
                <div class="btn-group w-100">
                  <button class="btn btn-secondary form-control" @click="onStart()">
                    <i class="ti-reload pR-10"></i> Refresh
                  </button>
                  <button class="btn btn-secondary form-control" @click="capture()">
                    <i class="ti-camera pR-10"></i> Capture
                  </button>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <vue-camera
                  ref="webcam"
                  :device-id="employeeComponent.vueCam.deviceId"
                  width="100%"
                  :autoplay="employeeComponent.vueCam.autoplay"
                  @started="onStarted"
                  @stopped="onStopped"
                  @error="onError"
                  @cameras="onCameras"
                  @camera-change="onCameraChange"
                />
              </div>
            </div>
            <div class="row">
              <div class="col">
                <button
                  type="button"
                  class="btn btn-danger form-control"
                  @click="onStop(),
      employeeComponent.vueCam.autoplay = false,hideModal('camera-modal')"
                >Close Camera</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </modal>
  </div>
</template>

<style>
.vdp-datepicker__clear-button span i span {
  position: absolute;
  right: 10px;
  top: 5px;
}
</style>



<script>
import { WebCam } from "vue-web-cam";
import Datepicker from "vuejs-datepicker";
import { ValidationProvider } from "vee-validate";
export default {
  props: ["form", "accessid", "action"],
  components: {
    "vue-camera": WebCam,
    "vue-datepicker": Datepicker,
    "v-validator": ValidationProvider
  },
  computed: {
    device: function() {
      return this.employeeComponent.vueCam.devices.find(
        n => n.deviceId === this.employeeComponent.vueCam.deviceId
      );
    }
  },
  watch: {
    "employeeComponent.vueCam.camera": function(id) {
      this.employeeComponent.vueCam.deviceId = id;
    },
    "employeeComponent.vueCam.devices": function() {
      // Once we have a list select the first one
      const [first, ...tail] = this.employeeComponent.vueCam.devices;
      if (first) {
        this.employeeComponent.vueCam.camera = first.deviceId;
        this.employeeComponent.vueCam.deviceId = first.deviceId;
      }
    }
  },
  data() {
    return {
      employeeComponent: {
        vueCam: {
          img: "/images/nobody.jpg",
          camera: null,
          deviceId: null,
          devices: [],
          autoplay: false
        },
        data: {
          users: []
        },
        form: {
          csrf: document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
          errors: [],
          options: {
            position: [],
            head: []
          },
          model: {
            status: "new",
            position: {
              code: "representative_op",
              deleted_at: null,
              id: 17,
              name: "Representative - Order Placer",
              parent: 16
            },
            head: {},
            company_email: null,
            hired_date: null,
            cnm_id: null,
            contract: null,
            firstname: null,
            middlename: null,
            lastname: null,
            suffix: null,
            address: null,
            mobile: null,
            personal_email: null,
            birthdate: null,
            gender: "male",
            sss: null,
            philhealth: null,
            pagibig: null,
            tin: null
          }
        }
      }
    };
  },
  methods: {
    // VUE camera
    capture() {
      this.employeeComponent.vueCam.img = this.$refs.webcam.capture();
    },
    onStarted(stream) {
      console.log("On Started Event", stream);
      this.employeeComponent.vueCam.autoplay = true;
    },
    onStopped(stream) {
      console.log("On Stopped Event", stream);
      this.employeeComponent.vueCam.autoplay = false;
    },
    onStop() {
      this.$refs.webcam.stop();
    },
    onStart() {
      this.$refs.webcam.start();
    },
    onError(error) {
      console.log("On Error Event", error);
    },
    onCameras(cameras) {
      this.employeeComponent.vueCam.devices = cameras;
      console.log("On Cameras Event", cameras);
    },
    onCameraChange(deviceId) {
      this.employeeComponent.vueCam.deviceId = deviceId;
      this.employeeComponent.vueCam.camera = deviceId;
      console.log("On Camera Change Event", deviceId);
    },
    // End vue camera
    imageSelect: function(event) {
      this.employeeComponent.vueCam.img = this.getBase64(event.target.files[0]);
    },
    getBase64(file, getResult) {
      var reader = new FileReader();
      var image;
      reader.readAsDataURL(file);
      reader.onload = function() {
        this.employeeComponent.vueCam.img = reader.result;
      }.bind(this);
      reader.onerror = function(error) {
        console.log("Error: ", error);
      };
    },
    oncreate: function() {
      if (this.accessid != 1) {
        this.employeeComponent.form.options.position = this.form.positions.filter(
          i => i.id != 1 && i.id != 2
        );
      } else {
        this.employeeComponent.form.options.position = this.form.positions;
      }
    },
    fetchAllUser: function() {
      fetch("/api/v1/users")
        .then(res => res.json())
        .then(res => {
          console.log(res);
          this.employeeComponent.data.users = res.meta.metadata.filter(
            i => i.id != 3
          );
        })
        .catch(err => console.log(err));
    },
    positionChanged: function() {
      this.employeeComponent.form.options.head = this.employeeComponent.data.users.filter(
        i => i.access_id == this.employeeComponent.form.model.position.parent
      );
      this.employeeComponent.form.model.head = this.employeeComponent.form.options.head[0];
    },
    formSubmit: function() {
      let tmp = this.employeeComponent.form.model;
      let data = {
        user_info: {
          image: this.employeeComponent.vueCam.img
            ? this.employeeComponent.vueCam.img
            : null,
          firstname: tmp.firstname ? tmp.firstname.trim() : null,
          middlename: tmp.middlename ? tmp.middlename.trim() : null,
          lastname: tmp.lastname ? tmp.lastname.trim() : null,
          suffix: tmp.suffix ? tmp.suffix.trim() : null,
          contact_number: tmp.mobile,
          address: tmp.address ? tmp.address.trim() : null,
          birthdate: tmp.birthdate,
          p_email: tmp.personal_email ? tmp.personal_email : null,
          gender: tmp.gender,
          hired_date: tmp.hired_date,
          status: tmp.status
        },
        user: {
          email: tmp.company_email,
          company_id: tmp.cnm_id,
          contract: tmp.contract ? tmp.contract : null,
          access_id: tmp.position.id
        },
        benefits: [tmp.sss, tmp.philhealth, tmp.pagibig, tmp.tin],
        hierarchy: {
          parent_id: tmp.head ? tmp.head.id : null
        }
      };
      fetch("/employee", {
        method: "post",
        body: JSON.stringify(data),
        headers: {
          // "content-type": "application/json",
          "X-CSRF-TOKEN": tmp.csrf
        }
      })
        .then(res => res.json())
        .then(res => {
          console.log(res);
        })
        .catch(err => console.log(err));
      // console.log(data);
    }
  },
  created() {
    this.oncreate();
    this.fetchAllUser();
  }
};
</script>