<template>
  <div class="full-container">
    <div class="layers">
      <div class="layer bgc-white w-100">
        <div class="row">
          <div class="col-10 pR-0 mR-0 pB-50" style="overflow-y:auto">
            <div class="bgc-white p-20 bd">
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
                        <div class="col mb-3">
                          <label for="validationCustom01">Position</label>
                          <input
                            type="text"
                            class="form-control"
                            id="validationCustom01"
                            placeholder="Position"
                          >
                        </div>
                        <div class="col mb-3">
                          <label for="validationCustom02">Head</label>
                          <input
                            type="text"
                            class="form-control"
                            id="validationCustom02"
                            placeholder="Head"
                          >
                        </div>
                      </div>
                      <div class="row">
                        <div class="col mb-3">
                          <label for="validationCustom01">Email</label>
                          <input
                            type="text"
                            class="form-control"
                            id="validationCustom01"
                            placeholder="Email"
                          >
                        </div>
                        <div class="col mb-3">
                          <label for="validationCustom01">Hired Date</label>
                          <input
                            type="text"
                            class="form-control"
                            id="validationCustom01"
                            placeholder="Hired Date"
                          >
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-3 mb-3">
                          <label for="validationCustom01">CNM ID</label>
                          <input
                            type="text"
                            class="form-control"
                            id="validationCustom01"
                            placeholder="CNM ID"
                          >
                        </div>
                        <div class="col mb-3">
                          <label for="validationCustom01">Contract</label>
                          <input
                            type="text"
                            class="form-control"
                            id="validationCustom01"
                            placeholder="Contract"
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="bgc-white p-20 bd">
              <h6 class="c-grey-900">Personal Information</h6>
              <div class="mT-30">
                <div class="container">
                  <div class="row">
                    <div class="col mb-3">
                      <label for="validationCustom01">First name</label>
                      <input
                        type="text"
                        class="form-control"
                        id="validationCustom01"
                        placeholder="First name"
                      >
                    </div>
                    <div class="col mb-3">
                      <label for="validationCustom02">Middle name</label>
                      <input
                        type="text"
                        class="form-control"
                        id="validationCustom02"
                        placeholder="Middle name"
                      >
                    </div>
                    <div class="col mb-3">
                      <label for="validationCustom02">Last name</label>
                      <input
                        type="text"
                        class="form-control"
                        id="validationCustom02"
                        placeholder="Last name"
                      >
                    </div>
                    <div class="col mb-3">
                      <label for="validationCustom02">Extention</label>
                      <input
                        type="text"
                        class="form-control"
                        id="validationCustom02"
                        placeholder="Sr., Jr., I, II, III etc.."
                      >
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <label for="validationCustom03">Address</label>
                      <input
                        type="text"
                        class="form-control"
                        id="validationCustom03"
                        placeholder="Addess"
                      >
                      <div class="invalid-feedback">Please provide a valid city.</div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3 mb-3">
                      <label for="validationCustom04">Mobile</label>
                      <input
                        type="text"
                        class="form-control"
                        id="validationCustom04"
                        placeholder="Mobile"
                      >
                      <div class="invalid-feedback">Please provide a valid state.</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="validationCustom05">Personal Email</label>
                      <input
                        type="text"
                        class="form-control"
                        id="validationCustom05"
                        placeholder="Personal Email"
                      >
                      <div class="invalid-feedback">Please provide a valid zip.</div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="inputState">Gender</label>
                      <select id="inputState" class="form-control">
                        <option selected="selected">Choose...</option>
                        <option>...</option>
                      </select>
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="validationCustom05">Birth Date</label>
                      <input
                        type="text"
                        class="form-control"
                        id="validationCustom05"
                        placeholder="Birth Date"
                      >
                      <div class="invalid-feedback">Please provide a valid zip.</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="bgc-white p-20 bd">
              <h6 class="c-grey-900">Benifit Numbers</h6>
              <div class="mT-30">
                <div class="container">
                  <div class="row">
                    <div class="col mb-3">
                      <label for="validationCustom01">SSS</label>
                      <input
                        type="text"
                        class="form-control"
                        id="validationCustom01"
                        placeholder="SSS"
                      >
                    </div>
                    <div class="col mb-3">
                      <label for="validationCustom02">PhilHealth</label>
                      <input
                        type="text"
                        class="form-control"
                        id="validationCustom02"
                        placeholder="PhilHealth"
                      >
                    </div>
                    <div class="col mb-3">
                      <label for="validationCustom02">PagIbig</label>
                      <input
                        type="text"
                        class="form-control"
                        id="validationCustom02"
                        placeholder="PagIbig"
                      >
                    </div>
                    <div class="col mb-3">
                      <label for="validationCustom02">TIN</label>
                      <input
                        type="text"
                        class="form-control"
                        id="validationCustom02"
                        placeholder="TIN"
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="bgc-white p-20 bd">
              <div class="row">
                <div class="col">
                  <div class="pull-right">
                    <button class="btn btn-danger">Confirm</button>
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

<script>
import { WebCam } from "vue-web-cam";

export default {
  props: ["employee"],
  components: { "vue-camera": WebCam },
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
        form: {}
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
    }
  }
};
</script>

