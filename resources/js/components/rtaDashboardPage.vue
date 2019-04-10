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

      <div class="bd bgc-white p-20 mY-20">
        <div class="layers">
          <div class="layer w-100 mB-10">
            <div class="peers">
              <div class="peer peer-greed">
                <h6 class="lh-100">Incident Reports</h6>
              </div>
              <div class="peer">
                <button
                  class="btn bdrs-50p p-5 lh-0"
                  @click="clearForm('incident_report'),
                  fetchSelectOptions(endpoints.select.child_list,'incident_report','child_list'),
                  fetchSelectOptions(endpoints.select.sanction_level,'incident_report','sanction_level'),
                  fetchSelectOptions(endpoints.select.sanction_type,'incident_report','sanction_type'),
                  showModal('incident_report')"
                >
                  <i class="ti-plus"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="row" v-if="isEmpty(table.received_incident_report.data.reports_data)">
            <i style="font-size:.7em;">Nothing to display...</i>
          </div>
          <div class="table-responsive" v-else>
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
                <tr
                  v-for="rir in table.received_incident_report.data.reports_data[0].reports"
                  v-bind:key="rir.id"
                >
                  <td>
                    {{rir.id}}
                    <span class="badge badge-pill badge-warning">NEW!</span>
                  </td>
                  <td>{{rir.created_at}}</td>
                  <td>{{rir.sanction_level.text}}</td>
                  <td>{{rir.sanction_type.text}}</td>
                  <td>
                    <span
                      v-if="rir.agent_response==null"
                      class="badge badge-pill badge-danger"
                    >NO Reply</span>
                    <span
                      v-else
                      class="badge badge-pill badge-success"
                    >{{rir.agent_response.created_at}}</span>
                  </td>
                  <td>
                    <div class="btn-group">
                      <button
                        class="btn btn-xs btn-info view-employee btn-custom-size"
                        @click="loadIRResponseForm(rir.id),showModal('incident_report_response')"
                      >
                        <i class="ti-eye"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

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
    <!-- Incident Report Response Modal -->
    <modal
      name="incident_report_response"
      :pivotY="0.2"
      :scrollable="true"
      width="900px"
      height="auto"
    >
      <div class="layer">
        <div class="e-modal-header bd">
          <h5 style="margin-bottom:0px">Incident Report Response</h5>
        </div>
        <div class="w-100 p-15 pT-80" style>
          <div class="container">
            <div class="row">
              <div class="col">
                <div class="layer p-0">
                  <!-- level -->
                  <span
                    class="badge badge-pill badge-primary"
                  >{{this.form.incident_report_response.sanction.level}}</span>
                  <!-- type -->
                  <span
                    class="badge badge-pill badge-dark"
                  >{{this.form.incident_report_response.sanction.type}}</span>
                </div>
                <div class="layer bd mT-5 pY-20 pX-15">
                  To:
                  <h6>{{this.form.incident_report_response.received_by}},</h6>
                  <br>
                  <br>
                  <!-- message content -->
                  <div class="cntent">
                    <pre class="ir_description">{{this.form.incident_report_response.ir_description}}</pre>
                  </div>
                  <br>
                  <h6>{{this.form.incident_report_response.filed_by}}</h6>
                </div>

                <div
                  class="alert-danger p-20 m-10"
                >You have 24hours to submit a response for this report. Refusal of response will be penalize.</div>
              </div>
              <div class="col" style="padding-top:25px">
                <textarea
                  name
                  id
                  class="form-control"
                  rows="15"
                  v-model="form.incident_report_response.response"
                ></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="e-modal-footer bd">
          <div style="text-align:right">
            <button class="btn btn-secondary" @click="hideModal('incident_report_response')">Cancel</button>
            <button
              class="btn btn-danger"
              @click="(form.sanction_level.level!=''&&form.sanction_level.description!='' ? 
                      store(
                        {
                          user_response_id: userId,
                          commitment: form.incident_report_response.response
                        },
                        'update',
                        'incident_report_response'
                            ) :
                      formValidationError())"
            >Confirm</button>
          </div>
        </div>
      </div>
    </modal>
    <!-- Incident Report Modal -->
    <modal name="incident_report" :pivotY="0.2" :scrollable="true" height="auto">
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
                    <model-select
                      placeholder="Names"
                      :options="form.incident_report.select_option.child_list"
                      v-model="form.incident_report.selected.child_list"
                    ></model-select>
                  </div>
                </div>
              </div>
              <div class="row pT-5">
                <div class="col">
                  <label>Sanction Level:</label>
                  <model-select
                    placeholder="Level"
                    :options="form.incident_report.select_option.sanction_level"
                    v-model="form.incident_report.selected.sanction_level"
                  ></model-select>
                </div>
                <div class="col">
                  <label>Sanction Type:</label>
                  <model-select
                    placeholder="Type"
                    :options="form.incident_report.select_option.sanction_type"
                    v-model="form.incident_report.selected.sanction_type"
                  ></model-select>
                </div>
              </div>
              <div class="row pT-15">
                <div class="col">
                  <label>Report Description</label>
                  <textarea name id cols="74" rows="5" v-model="form.incident_report.textarea"></textarea>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="e-modal-footer bd">
          <div style="text-align:right">
            <button class="btn btn-secondary" @click="hideModal('incident_report')">Cancel</button>
            <button
              class="btn btn-danger"
              @click="submitForm('incident_report',form.incident_report.action)"
            >Confirm</button>
          </div>
        </div>
      </div>
    </modal>
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
  mounted() {
    this.fetchTableObject("received_incident_report");
    this.user_id = this.userId;
    console.log(this.user_id);
  },
  data() {
    return {};
  },
  methods: {}
};
</script>