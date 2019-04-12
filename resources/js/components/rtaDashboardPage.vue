<template>
  <div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>

    <div class="masonry-item w-100">
      <div class="row gap-20">
        <!-- #Toatl Visits ==================== -->
        <div class="col-md-3">
          <stats-component-1 stat-name="scheduled"></stats-component-1>
        </div>

        <!-- #Total Page Views ==================== -->
        <div class="col-md-3">
          <stats-component-1 stat-name="working"></stats-component-1>
        </div>

        <!-- #Unique Visitors ==================== -->
        <div class="col-md-3">
          <stats-component-1 stat-name="absent"></stats-component-1>
        </div>

        <!-- #Bounce Rate ==================== -->
        <div class="col-md-3">
          <stats-component-1 stat-name="off_duty"></stats-component-1>
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
                  <button class="btn btn-outline-secondary btn-custom-size">
                    <i class="ti-close"></i>
                  </button>
                  <button class="btn btn-outline-danger btn-custom-size">Manage</button>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <incident-report :userId="user_id"></incident-report>

      <div>
        <div class="container">
          <div class="row">
            <!-- Sanction Levels -->
            <sanction-level></sanction-level>
            <!-- Sanction Types -->
            <sanction-type></sanction-type>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals -->

    <!-- profile preview modal -->
    <profile-preview-modal v-bind:user-profile="this.userId"></profile-preview-modal>
    <notifications group="foo" animation-type="velocity" position="bottom right"/>
  </div>
</template>

<script>
import { BasicSelect } from "vue-search-select";
import { ModelSelect } from "vue-search-select";

export default {
  props: ["userId"],
  components: {
    BasicSelect,
    ModelSelect
  },
  created() {
    this.endpoints.table.issued_incident_report =
      this.endpoints.table.issued_incident_report + this.user_id;
  },
  mounted() {},
  data() {
    return {
      user_id: this.userId
    };
  },
  methods: {}
};
</script>