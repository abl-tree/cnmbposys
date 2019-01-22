
<template>
  <div class="full-container">
    <div class="email-app">
      <div class="email-side-nav remain-height ov-h">
        <div class="h-100 layers">
          <div class="p-20 bgc-grey-100 layer w-100">
            <input ref="file" type="file" id="file" name="file" hidden>
            <button @click="$refs.file.click()" class="btn btn-danger btn-block">Import excel</button>
          </div>
          <div class="pos-r bdT layer w-100 fxg-1" style="overflow-y:auto">
            <ul class="p-20 nav flex-column">
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link c-grey-800 cH-blue-500 active">
                  <div class="peers ai-c jc-sb">
                    <div class="peer peer-greed">
                      <i class="mR-10 ti-email"></i>
                      <span>Inbox</span>
                    </div>
                    <div class="peer">
                      <span class="badge badge-pill bgc-deep-purple-50 c-deep-purple-700">+99</span>
                    </div>
                  </div>
                </a>
              </li>
              <li class="nav-item">
                <a href class="nav-link c-grey-800 cH-blue-500">
                  <div class="peers ai-c jc-sb">
                    <div class="peer peer-greed">
                      <i class="mR-10 ti-share"></i>
                      <span>Sent</span>
                    </div>
                    <div class="peer">
                      <span class="badge badge-pill bgc-green-50 c-green-700">12</span>
                    </div>
                  </div>
                </a>
              </li>
              <li class="nav-item">
                <a href class="nav-link c-grey-800 cH-blue-500">
                  <div class="peers ai-c jc-sb">
                    <div class="peer peer-greed">
                      <i class="mR-10 ti-star"></i>
                      <span>Important</span>
                    </div>
                    <div class="peer">
                      <span class="badge badge-pill bgc-blue-50 c-blue-700">3</span>
                    </div>
                  </div>
                </a>
              </li>
              <li class="nav-item">
                <a href class="nav-link c-grey-800 cH-blue-500">
                  <div class="peers ai-c jc-sb">
                    <div class="peer peer-greed">
                      <i class="mR-10 ti-file"></i>
                      <span>Drafts</span>
                    </div>
                    <div class="peer">
                      <span class="badge badge-pill bgc-amber-50 c-amber-700">5</span>
                    </div>
                  </div>
                </a>
              </li>
              <li class="nav-item">
                <a href class="nav-link c-grey-800 cH-blue-500">
                  <div class="peers ai-c jc-sb">
                    <div class="peer peer-greed">
                      <i class="mR-10 ti-alert"></i>
                      <span>Spam</span>
                    </div>
                    <div class="peer">
                      <span class="badge badge-pill bgc-red-50 c-red-700">1</span>
                    </div>
                  </div>
                </a>
              </li>
              <li class="nav-item">
                <a href class="nav-link c-grey-800 cH-blue-500">
                  <div class="peers ai-c jc-sb">
                    <div class="peer peer-greed">
                      <i class="mR-10 ti-trash"></i>
                      <span>Trash</span>
                    </div>
                    <div class="peer">
                      <span class="badge badge-pill bgc-red-50 c-red-700">+99</span>
                    </div>
                  </div>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="email-wrapper row remain-height bgc-white ov-h">
        <div class="email-list h-100 layers">
          <div class="layer w-100">
            <div class="bgc-grey-100 peers ai-c jc-sb p-20 fxw-nw">
              <div class="peer">
                <div class="btn-group" role="group">
                  <button
                    type="button"
                    class="email-side-toggle d-n@md+ btn bgc-white bdrs-2 mR-3 cur-p"
                  >
                    <i class="ti-menu"></i>
                  </button>
                  <button type="button" class="btn bgc-white bdrs-2 mR-3 cur-p">
                    <i class="ti-folder"></i>
                  </button>
                  <button type="button" class="btn bgc-white bdrs-2 mR-3 cur-p">
                    <i class="ti-tag"></i>
                  </button>
                  <div class="btn-group" role="group">
                    <button
                      id="btnGroupDrop1"
                      type="button"
                      class="btn cur-p bgc-white no-after dropdown-toggle"
                      data-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
                    >
                      <i class="ti-more-alt"></i>
                    </button>
                    <ul class="dropdown-menu fsz-sm" aria-labelledby="btnGroupDrop1">
                      <li>
                        <a href class="d-b td-n pY-5 pX-10 bgcH-grey-100 c-grey-700">
                          <i class="ti-trash mR-10"></i>
                          <span>Delete</span>
                        </a>
                      </li>
                      <li>
                        <a href class="d-b td-n pY-5 pX-10 bgcH-grey-100 c-grey-700">
                          <i class="ti-alert mR-10"></i>
                          <span>Mark as Spam</span>
                        </a>
                      </li>
                      <li>
                        <a href class="d-b td-n pY-5 pX-10 bgcH-grey-100 c-grey-700">
                          <i class="ti-star mR-10"></i>
                          <span>Star</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="peer">
                <div class="btn-group" role="group">
                  <button
                    type="button"
                    class="fsz-xs btn bgc-white bdrs-2 mR-3 cur-p"
                    @click="agentPaginate('prev')"
                    :disabled="agentListPage==agentListMin"
                  >
                    <i class="ti-angle-left"></i>
                  </button>
                  <button
                    type="button"
                    class="fsz-xs btn bgc-white bdrs-2 mR-3 cur-p"
                    @click="agentPaginate('next')"
                    :disabled="agentListPage==agentListMax"
                  >
                    <i class="ti-angle-right"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="layer w-100">
            <div class="bdT bdB">
              <input type="text" class="form-control m-0 bdw-0 pY-15 pX-20" placeholder="Search...">
            </div>
          </div>
          <div class="layer w-100 fxg-1 pos-r" style="overflow-y:auto">
            <div>
              <div
                v-for="agent in agentList"
                v-bind:key="agent.id"
                @click="displayAgentSchedule(agent.id)"
                class="email-list-item peers fxw-nw p-20 bdB bgcH-grey-100 cur-p"
              >
                <div class="peer mR-10">
                  <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                    <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                    <label for="inputCall1" class="peers peer-greed js-sb ai-c"></label>
                  </div>
                </div>
                <div class="peer peer-greed ov-h">
                  <div class="peers ai-c">
                    <div class="peer peer-greed">
                      <h6>{{agent.full_name}}</h6>
                    </div>
                    <div class="peer">
                      <small class="badge badge-pill badge-danger">{{agent.company_id}}</small>
                    </div>
                  </div>
                  <!-- <h5 class="fsz-def tt-c c-grey-900">title goes here</h5> -->
                  <span class="whs-nw w-100 ov-h tov-e d-b">{{agent.email}}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="email-content h-100">
          <div class="h-100 pos-r" style="overflow-y:auto">
            <div class="bgc-grey-100 peers ai-c jc-sb p-20 fxw-nw d-n@md+">
              <div class="peer">
                <div class="btn-group" role="group">
                  <button type="button" class="back-to-mailbox btn bgc-white bdrs-2 mR-3 cur-p">
                    <i class="ti-angle-left"></i>
                  </button>
                  <button type="button" class="btn bgc-white bdrs-2 mR-3 cur-p">
                    <i class="ti-folder"></i>
                  </button>
                  <button type="button" class="btn bgc-white bdrs-2 mR-3 cur-p">
                    <i class="ti-tag"></i>
                  </button>
                  <div class="btn-group" role="group">
                    <button
                      id="btnGroupDrop1"
                      type="button"
                      class="btn cur-p bgc-white no-after dropdown-toggle"
                      data-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
                    >
                      <i class="ti-more-alt"></i>
                    </button>
                    <ul class="dropdown-menu fsz-sm" aria-labelledby="btnGroupDrop1">
                      <li>
                        <a href class="d-b td-n pY-5 pX-10 bgcH-grey-100 c-grey-700">
                          <i class="ti-trash mR-10"></i>
                          <span>Delete</span>
                        </a>
                      </li>
                      <li>
                        <a href class="d-b td-n pY-5 pX-10 bgcH-grey-100 c-grey-700">
                          <i class="ti-alert mR-10"></i>
                          <span>Mark as Spam</span>
                        </a>
                      </li>
                      <li>
                        <a href class="d-b td-n pY-5 pX-10 bgcH-grey-100 c-grey-700">
                          <i class="ti-star mR-10"></i>
                          <span>Star</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="peer">
                <div class="btn-group" role="group">
                  <button type="button" class="fsz-xs btn bgc-white bdrs-2 mR-3 cur-p">
                    <i class="ti-angle-left"></i>
                  </button>
                  <button type="button" class="fsz-xs btn bgc-white bdrs-2 mR-3 cur-p">
                    <i class="ti-angle-right"></i>
                  </button>
                </div>
              </div>
            </div>
            <div class="email-content-wrapper">
              <!-- Header -->
              <div class="peers ai-c jc-sb pX-40 pY-30">
                <div class="peers peer-greed">
                  <div class="peer mR-20">
                    <img class="bdrs-50p w-3r h-3r" alt :src="agent.image">
                  </div>
                  <div class="peer">
                    <h5 class="c-grey-900 mB-5" v-html="agent.full_name"></h5>
                    <span v-html="agent.email"></span>
                    <br>
                    <small
                      class="badge badge-pill bgc-deep-purple-50 c-deep-purple-700"
                      v-html="agent.teamLeader"
                    >{{agent.teamLeader}}</small>
                    <small
                      class="badge badge-pill bgc-deep-purple-50 c-deep-purple-700"
                      v-html="agent.operationsManager"
                    >{{agent.operationsManager}}</small>
                  </div>
                </div>
                <div class="peer">
                  <button
                    class="btn btn-danger bdrs-50p p-15 lh-0"
                    type="button"
                    data-toggle="modal"
                    data-target="#cu-schedule-modal"
                    @click="scheduleTitleOptions()"
                  >
                    <i class="ti-plus"></i>
                  </button>
                </div>
              </div>
              <!-- Content -->
              <div class="bdT">
                <full-calendar
                  ref="calendar"
                  :events="events"
                  :config="config"
                  @even-selected="eventClick"
                  @event-render="eventRender()"
                ></full-calendar>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- popover -->
      <!-- Modal -->
      <div class="modal fade" id="cu-schedule-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="cu-schedule-modal-title"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container">
                <form action>
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label for="exampleFormControlSelect1">Example select</label>
                        <select class="form-control" v-model="form.title">
                          <option
                            v-for="option in titleOptions"
                            :key="option.id"
                            :value="option.id"
                          >{{option.title}}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col">
                      <label>Date</label>
                      <date-time-picker
                        v-model="form.event"
                        range-mode
                        overlay-background
                        color="red"
                        format="YYYY-MM-DD"
                        formatted="ddd D MMM YYYY"
                        label="Select range"
                      />
                    </div>
                  </div>
                  <div class="row pT-5">
                    <div class="col">
                      <label>Time IN</label>
                      <date-time-picker
                        v-model="form.time_in"
                        formatted="HH:mm"
                        format="HH:mm"
                        time-format="HH:mm"
                        label="Choose time"
                        disable-date
                        overlay-background
                        color="red"
                        :minute-interval="1"
                      />
                    </div>
                    <div class="col">
                      <label>Hours</label>
                      <date-time-picker
                        v-model="form.hours"
                        formatted="HH:mm"
                        format="HH:mm"
                        time-format="HH:mm"
                        label="Choose time"
                        disable-date
                        overlay-background
                        color="red"
                        :minute-interval="1"
                      />
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-danger" @click="submitScheduleForm">Save changes</button>
            </div>
          </div>
        </div>
      </div>

      <div
        class="modal fade"
        id="profile-preview-modal"
        tabindex="-1"
        role="dialog"
        aria-hidden="true"
      >
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

      <ir-response-modal></ir-response-modal>
      <!-- Modal -->
    </div>
  </div>
</template>
​<style scoped>
</style>

<script>
import moment from "moment";
export default {
  props: ["userProfile"],
  mounted() {
    console.log("rtapage mounted");
    console.log(this.userProfile);
  },
  created() {
    //on create function
    this.fetchAgentList(0);
    //TEST function create
    // this.getDates("2018-12-25", "2019-01-05");
    this.submitScheduleForm();
  },
  data() {
    return {
      agentListPage: 0,
      agentListMin: 0,
      agentListMax: 0,
      agentList: {},
      agent: {
        image: "",
        full_name: "",
        email: "",
        teamLeader: "",
        operationsManager: "",
        id: ""
      },
      events: [],
      form: {
        id: "",
        title: "",
        event: { start: "", end: "" },
        time_in: "",
        hours: "",
        edit: ""
      },
      titleOptions: [],

      config: {
        eventClick: event => {
          console.log(event);
        },
        eventRender: function(event, element) {
          element.attr({
            "data-toggle": "modal",
            "data-target": "#cu-schedule-modal"
          });
        }
      },
      selected: {}
    };
  },
  methods: {
    refreshEvents: function() {
      this.$refs.calendar.$emit("reload-events");
    },
    fetchAgentList: function(listpage) {
      const limit = 10;
      const sort = "email";
      const order = "asc";
      let offset = listpage * limit;
      let pageurl =
        "/api/v1/agents?limit=" +
        limit +
        "&order=" +
        order +
        "&sort=" +
        sort +
        "&offset=" +
        offset;
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          this.agentList = res.meta.agents;
          this.agentListMax = Math.ceil(res.meta.count / limit) - 1;
          this.displayAgentSchedule(this.agentList[0].id);
        })
        .catch(err => console.log(err));
    },
    getDates: function(startDate, stopDate) {
      console.log("start " + startDate);
      console.log("end " + stopDate);
      var dateArray = [];
      var currentDate = moment(startDate);
      var stopDate = moment(stopDate);
      while (currentDate <= stopDate) {
        dateArray.push(moment(currentDate).format("YYYY-MM-DD"));
        currentDate = moment(currentDate).add(1, "days");
      }
      return dateArray;
      // console.log(dateArray);
    },
    agentPaginate: function(action) {
      let act = action;
      if (act == "next") {
        this.agentListPage++;
      } else {
        this.agentListPage--;
      }
      this.fetchAgentList(this.agentListPage);
    },
    displayAgentSchedule: function(id) {
      //fetch for agent info
      let pageurl = "/api/v1/agents/fetch/" + id;
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          let img = "/images/nobody.jpg";
          if (res.meta.agent.info.image != null) {
            img = res.meta.agent.info.image;
          }
          this.agent.image = img;
          this.agent.full_name = res.meta.agent.full_name;
          this.agent.email = res.meta.agent.email;
          this.agent.teamLeader = res.meta.agent.team_leader;
          this.agent.operationsManager = res.meta.agent.operations_manager;
          this.agent.id = res.meta.agent.uid;
        })
        .catch(err => console.log(err));
      // fetch for schedules
      pageurl = "/api/v1/schedules/agents/" + id;
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          let temp = [];
          if (res.meta.agent.schedule != null) {
            $.each(res.meta.agent.schedule, function(k, v) {
              let temp1 = {
                title: v.title.title,
                start: v.start_event,
                end: v.end_event,
                color: v.title.color,
                id: v.id
              };
              temp.push(temp1);
            });
          }
          this.events = temp;
          console.log(this.events);
          // this.refreshEvents();
        })
        .catch(err => console.log(err));
    },
    submitScheduleForm: function() {
      //format object to be sent backend
      let obj = [];
      let id = this.form.id;
      let title_id = this.form.title;
      let time_in = this.form.time_in;
      let hours = this.form.hours;
      let agent_id = this.agent.id;
      var dates = [];

      if (this.form.event.end == null) {
        dates.push(moment(moment(this.form.event.start)).format("YYYY-MM-DD"));
        console.log(moment(moment(this.form.event.start)).format("YYYY-MM-DD"));
        console.log(moment(this.form.event.start));
        console.log("mao ni " + dates);
      } else {
        dates = this.getDates(this.form.event.start, this.form.event.end);
      }
      $.each(dates, function(k, v) {
        let start = v + " " + time_in + ":00";
        let hr = hours.split(":");
        let obj_element = {
          title_id: title_id,
          user_id: agent_id,
          start_event: start,
          end_event: moment(
            moment(start)
              .add(hr[0], "h")
              .add(hr[1], "m")
              .toDate()
          ).format("YYYY-MM-DD HH:mm:ss")
        };
        obj.push(obj_element);
      });
      console.log(dates);
      console.log(obj);
      //submiting the object
      // this.insertSchedule(obj);
    },
    scheduleTitleOptions: function() {
      let pageurl = "api/v1/events";
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          let result = res.meta;
          // console.log(result.event_titles);
          this.titleOptions = result.event_titles;
        })
        .catch(err => console.log(err));
    },
    insertSchedule: function(data) {
      let pageurl = "/api/v1/schedules/create/bulk";
      fetch(pageurl, {
        method: "post",
        body: JSON.stringify(data),
        headers: {
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          console.log(data);
          this.form.id = "";
          this.form.title = "";
          this.form.time_in = "";
          this.form.hours = "";
          this.form.event.start = "";
          this.form.event.end = "";
        })
        .catch(err => console.log(err));
    },
    editSchedule: function() {
      console.log("WAPAKPAKPAK");
    },

    //full calendar functions
    eventRender: function(event, element) {
      element.attr({
        dataToggle: "modal"
      });
    },
    eventClick: function(event) {
      console.log(event.id);
    }
  }
};
</script>