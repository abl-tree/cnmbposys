<template>
  <div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>

    <div class="masonry-item w-100">
      <div class="row gap-20">
        <!-- #Toatl Visits ==================== -->
        <div class="col-md-3">
          <div class="layers bd bgc-white p-20">
            <div class="layer w-100 mB-10">
              <h6 class="lh-1">Scheduled</h6>
            </div>
            <div class="layer w-100">
              <div class="peers ai-sb fxw-nw">
                <div class="peer peer-greed">
                  <span id="sparklinedash"></span>
                </div>
                <div class="peer">
                  <span
                    class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500"
                  >+10%</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- #Total Page Views ==================== -->
        <div class="col-md-3">
          <div class="layers bd bgc-white p-20">
            <div class="layer w-100 mB-10">
              <h6 class="lh-1">Working</h6>
            </div>
            <div class="layer w-100">
              <div class="peers ai-sb fxw-nw">
                <div class="peer peer-greed">
                  <span id="sparklinedash2"></span>
                </div>
                <div class="peer">
                  <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-red-50 c-red-500">-7%</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- #Unique Visitors ==================== -->
        <div class="col-md-3">
          <div class="layers bd bgc-white p-20">
            <div class="layer w-100 mB-10">
              <h6 class="lh-1">Absent</h6>
            </div>
            <div class="layer w-100">
              <div class="peers ai-sb fxw-nw">
                <div class="peer peer-greed">
                  <span id="sparklinedash3"></span>
                </div>
                <div class="peer">
                  <span
                    class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-purple-50 c-purple-500"
                  >~12%</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- #Bounce Rate ==================== -->
        <div class="col-md-3">
          <div class="layers bd bgc-white p-20">
            <div class="layer w-100 mB-10">
              <h6 class="lh-1">Off-duty</h6>
            </div>
            <div class="layer w-100">
              <div class="peers ai-sb fxw-nw">
                <div class="peer peer-greed">
                  <span id="sparklinedash4"></span>
                </div>
                <div class="peer">
                  <span
                    class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500"
                  >33%</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="masonry-item col-md-6">
      <!-- #Sales Report ==================== -->
      <div class="bd bgc-white">
        <div class="layers">
          <div class="layer w-100 p-20">
            <h6 class="lh-1">Today's Activity</h6>
          </div>
          <div class="layer w-100">
            <div class="bgc-light-blue-500 c-white p-20">
              <div class="peers ai-c jc-sb gap-40">
                <div class="peer peer-greed">
                  <h5></h5>
                  <!-- <p class="mB-0">Today's Activity</p> -->
                </div>
                <div class="peer">
                  <h3 class="text-right">$6,000</h3>
                </div>
              </div>
            </div>
            <div class="table-responsive p-20">
              <table class="table">
                <thead>
                  <tr>
                    <th class="bdwT-0">Agent Name</th>
                    <th class="bdwT-0">Status</th>
                    <th class="bdwT-0">Work Time</th>
                    <th class="bdwT-0">Break Time</th>
                    <th class="bdwT-0">Breakdown</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="fw-600">Item #1 Name</td>
                    <td>
                      <span class="badge bgc-red-50 c-red-700 p-10 lh-0 tt-c badge-pill">Unavailable</span>
                    </td>
                    <td>Nov 18</td>
                    <td>
                      <span class="text-success">$12</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-600">Item #2 Name</td>
                    <td>
                      <span
                        class="badge bgc-deep-purple-50 c-deep-purple-700 p-10 lh-0 tt-c badge-pill"
                      >New</span>
                    </td>
                    <td>Nov 19</td>
                    <td>
                      <span class="text-info">$34</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-600">Item #3 Name</td>
                    <td>
                      <span class="badge bgc-pink-50 c-pink-700 p-10 lh-0 tt-c badge-pill">New</span>
                    </td>
                    <td>Nov 20</td>
                    <td>
                      <span class="text-danger">-$45</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-600">Item #4 Name</td>
                    <td>
                      <span
                        class="badge bgc-green-50 c-green-700 p-10 lh-0 tt-c badge-pill"
                      >Unavailable</span>
                    </td>
                    <td>Nov 21</td>
                    <td>
                      <span class="text-success">$65</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-600">Item #5 Name</td>
                    <td>
                      <span class="badge bgc-red-50 c-red-700 p-10 lh-0 tt-c badge-pill">Used</span>
                    </td>
                    <td>Nov 22</td>
                    <td>
                      <span class="text-success">$78</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-600">Item #6 Name</td>
                    <td>
                      <span class="badge bgc-orange-50 c-orange-700 p-10 lh-0 tt-c badge-pill">Used</span>
                    </td>
                    <td>Nov 23</td>
                    <td>
                      <span class="text-danger">-$88</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-600">Item #7 Name</td>
                    <td>
                      <span class="badge bgc-yellow-50 c-yellow-700 p-10 lh-0 tt-c badge-pill">Old</span>
                    </td>
                    <td>Nov 22</td>
                    <td>
                      <span class="text-success">$56</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="ta-c bdT w-100 p-20">
          <a href="#">Check all the activities</a>
        </div>
      </div>
    </div>

    <div class="masonry-item col-md-6">
      <!-- #Todo ==================== -->
      <div class="bd bgc-white p-20">
        <h6 class="c-grey-900">Request</h6>
        <div class="mT-30">
          <div class="list-group" style="font-size:0.83em">
            <div class="list-group-item list-group-item-action">
              <div>
                <b>Ice Cream</b> requested for
                <span class="text-danger">Leave Type 1</span> under supervision of
                <b>Mango Float</b> dated 02-18-2019 to 02-25-2019
                <br>
              </div>
              <div class="peers">
                <span class="peer peer-greed">
                  <span class="text-muted">
                    <small>Request Date: 01-03-2019 12:00:00</small>
                  </span>
                </span>
                <span class="peer">
                  <button
                    class="btn btn-outline-secondary"
                    style="font-size:0.8em;padding:3px 10px 3px 10px;"
                  >
                    <i class="ti-close"></i>
                  </button>
                  <button
                    class="btn btn-outline-danger"
                    style="font-size:0.8em;padding:3px 10px 3px 10px;"
                  >Manage</button>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="bd bgc-white p-20 mY-20">
        <div class="layers">
          <div class="layer w-100 mB-10">
            <div class="peers">
              <div class="peer peer-greed">
                <h6 class="lh-1">Incident Reports</h6>
              </div>
              <div class="peer">
                <button
                  class="btn btn-secondary"
                  style="font-size:0.8em;padding:3px 10px 3px 10px;"
                  @click="showModal('ir-form-modal')"
                >
                  <i class="ti-plus"></i>
                </button>
              </div>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>IR No.</th>
                  <th>Date</th>
                  <th>Level</th>
                  <th>Type</th>
                  <th>Reply</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    2
                    <span class="badge badge-pill badge-warning">NEW!</span>
                  </td>
                  <td>10/20/18</td>
                  <td>Written</td>
                  <td>Absentism</td>
                  <td>
                    <span class="badge badge-pill badge-danger">NO Reply</span>
                  </td>
                  <td>
                    <div class="btn-group">
                      <button
                        class="btn btn-xs btn-info ti-eye view-employee"
                        data-toggle="modal"
                        data-target="#ir-response-modal"
                      ></button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>1</td>
                  <td>10/01/18</td>
                  <td>Verbal</td>
                  <td>Absentism</td>
                  <td>
                    <span class="badge badge-pill badge-success">10/02/18</span>
                  </td>
                  <td>
                    <div class="btn-group">
                      <button
                        class="btn btn-xs btn-info ti-eye view-employee"
                        data-toggle="modal"
                        data-target="#ir-response-modal"
                      ></button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- Sanction Levels -->
      <div class="bd bgc-white p-20 mY-20">
        <div class="layers">
          <div class="layer w-100 mB-10">
            <div class="peers">
              <div class="peer peer-greed">
                <h6 class="lh-1">Sanction Levels</h6>
              </div>
              <div class="peer">
                <button
                  class="btn btn-secondary"
                  style="font-size:0.8em;padding:3px 10px 3px 10px;"
                  @click="showModal('sanction-level-form-modal')"
                >
                  <i class="ti-plus"></i>
                </button>
              </div>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Level</th>
                  <th>Description</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Written</td>
                  <td>
                    <button class="btn btn-dark">
                      <i class="ti-pencil"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- Sanction Types -->
      <div class="bd bgc-white p-20 mY-20">
        <div class="layers">
          <div class="layer w-100 mB-10">
            <div class="peers">
              <div class="peer peer-greed">
                <h6 class="lh-1">Sanction Types</h6>
              </div>
              <div class="peer">
                <button
                  class="btn btn-secondary"
                  style="font-size:0.8em;padding:3px 10px 3px 10px;"
                  @click="showModal('sanction-type-form-modal')"
                >
                  <i class="ti-plus"></i>
                </button>
              </div>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Type ID</th>
                  <th>Description</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Absentism</td>
                  <td>
                    <button class="btn btn-dark">
                      <i class="ti-pencil"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <ir-response-modal></ir-response-modal>

    <!-- IR Form Modal -->
    <modal name="ir-form-modal" pivotY="0.2" scrollable="true" height="auto">
      <div class="layer">
        <div class="e-modal-header bd">
          <h5 style="margin-bottom:0px">Incident Report</h5>
        </div>
        <div class="w-100 p-15 pT-80" style>
          <div class="container">
            <form action>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="exampleFormControlSelect1">To:</label>
                    <basic-select placeholder="Names"></basic-select>
                  </div>
                </div>
              </div>
              <div class="row pT-5">
                <div class="col">
                  <label>Sanction Level:</label>
                  <basic-select placeholder="Level"></basic-select>
                </div>
                <div class="col">
                  <label>Sanction Type:</label>
                  <basic-select placeholder="Type"></basic-select>
                </div>
              </div>
              <div class="row pT-15">
                <div class="col">
                  <label>Report Description</label>
                  <textarea name id cols="74" rows="5"></textarea>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="e-modal-footer bd">
          <div style="text-align:right">
            <button class="btn btn-secondary" @click="hideModal('ir-form-modal')">Cancel</button>
            <button class="btn btn-danger">Confirm</button>
          </div>
        </div>
      </div>
    </modal>
    <!-- Sanction Level Form Modal -->
    <modal name="sanction-level-form-modal" pivotY="0.2" scrollable="true" height="auto">
      <div class="layer">
        <div class="e-modal-header bd">
          <h5 style="margin-bottom:0px">Sanction Level</h5>
        </div>
        <div class="w-100 p-15 pT-80" style>
          <div class="container">
            <form action>
              <div class="row pT-5">
                <div class="col">
                  <label>No:</label>
                  <basic-select placeholder="Level"></basic-select>
                </div>
                <div class="col">
                  <label>Description:</label>
                  <basic-select placeholder="Type"></basic-select>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="e-modal-footer bd">
          <div style="text-align:right">
            <button class="btn btn-secondary" @click="hideModal('sanction-level-form-modal')">Cancel</button>
            <button class="btn btn-danger">Confirm</button>
          </div>
        </div>
      </div>
    </modal>
    <!-- Sanction Form Modal -->
    <modal name="sanction-type-form-modal" pivotY="0.2" scrollable="true" height="auto">
      <div class="layer">
        <div class="e-modal-header bd">
          <h5 style="margin-bottom:0px">Sanction Level</h5>
        </div>
        <div class="w-100 p-15 pT-80" style>
          <div class="container">
            <form action>
              <div class="row pT-5">
                <div class="col">
                  <label>No:</label>
                  <basic-select placeholder="Level"></basic-select>
                </div>
                <div class="col">
                  <label>Description:</label>
                  <basic-select placeholder="Type"></basic-select>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="e-modal-footer bd">
          <div style="text-align:right">
            <button class="btn btn-secondary" @click="hideModal('sanction-type-form-modal')">Cancel</button>
            <button class="btn btn-danger">Confirm</button>
          </div>
        </div>
      </div>
    </modal>
  </div>
</template>

<script>
import { BasicSelect } from "vue-search-select";

export default {
  components: {
    BasicSelect
  },
  mounted() {
    console.log("PAGE");
    this.alertTO();
  },
  data() {
    return {
      james: ""
    };
  },
  methods: {}
};
</script>