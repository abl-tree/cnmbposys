
<template>
  <div class="email-app">
    <div class="email-side-nav remain-height ov-h">
      <div class="h-100 layers">
        <div class="p-20 bgc-grey-100 layer w-100">
          <a href="/compose" class="btn btn-danger btn-block">Import excel</a>
        </div>
        <div class="scrollable pos-r bdT layer w-100 fxg-1">
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
                <button type="button" class="fsz-xs btn bgc-white bdrs-2 mR-3 cur-p">
                  <i class="ti-angle-left"></i>
                </button>
                <button type="button" class="fsz-xs btn bgc-white bdrs-2 mR-3 cur-p">
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
        <div class="layer w-100 fxg-1 scrollable pos-r">
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
        <div class="h-100 scrollable pos-r">
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
                  <h5 class="c-grey-900 mB-5" id="calendar-header-fullname">{{agent.full_name}}</h5>
                  <span id="calendar-header-email">{{agent.email}}</span>
                  <br>
                  <small
                    class="badge badge-pill bgc-deep-purple-50 c-deep-purple-700"
                    id="calendar-header-tl"
                  >{{agent.teamLeader}}</small>
                  <small
                    class="badge badge-pill bgc-deep-purple-50 c-deep-purple-700"
                    id="calendar-header-om"
                  >{{agent.operationsManager}}</small>
                </div>
              </div>
              <div class="peer">
                <button
                  class="btn btn-danger bdrs-50p p-15 lh-0"
                  type="button"
                  data-toggle="modal"
                  data-target="#cu-schedule-modal"
                >
                  <i class="ti-plus"></i>
                </button>
              </div>
            </div>
            <!-- Content -->
            <div class>
              <div>
                <full-calendar></full-calendar>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

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
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="exampleFormControlSelect1">Example select</label>
                    <select class="form-control" v-model="title">
                      <option value="work">Work</option>
                      <option value="leave">Leave</option>
                      <option value="maternity">Maternity</option>
                    </select>
                  </div>
                </div>

                <div class="col">
                  <label>Date</label>
                  <date-time-picker
                    v-model="event"
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
                    v-model="time_in"
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
                  <label>Time Out</label>
                  <date-time-picker
                    v-model="time_out"
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
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
​
<script>
export default {
  mounted() {
    console.log("rtapage mounted");
  },
  created() {
    this.fetchAgentList();
  },
  data() {
    return {
      event: { start: "", end: "" },
      time_in: "",
      time_out: "",
      title: "",
      agentList: {},
      agent: {
        image: "",
        full_name: "",
        email: "",
        teamLeader: "",
        operationsManager: "",
        id: ""
      },
      events: {}
    };
  },
  methods: {
    fetchAgentList: function() {
      const limit = 10;
      const sort = "email";
      const order = "asc";
      let pageurl =
        "/api/agents?limit=" + limit + "&order=" + order + "&sort=" + sort;
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          this.agentList = res.meta.agents;
          this.displayAgentSchedule(this.agentList[0].id);
        })
        .catch(err => console.log(err));
    },
    displayAgentSchedule: function(id) {
      let pageurl = "/api/agents/fetch/" + id;
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
        })
        .catch(err => console.log(err));
    }
  }
};
</script>