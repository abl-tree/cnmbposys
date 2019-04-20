
<template>
  <div class="full-container">
    <div class="email-app">
      <div class="email-side-nav remain-height ov-h">
        <div class="h-100 layers">
          <div class="p-20 bgc-grey-200 layer w-100 text-center">
            <input ref="file" type="file" id="file" name="file" hidden>
            <div class="btn-group">
              <button
                @click="$refs.file.click()"
                class="btn btn-danger btn-block pX-40"
              >Import excel</button>
              <button class="btn btn-danger">
                <i class="ti-download"></i>
              </button>
            </div>
          </div>
          <div class="layer w-100 p-15">
            <div class="row">
              <div class="col">
                <h6>Events</h6>
              </div>
              <div class="col text-right">
                <button
                  class="btn bdrs-50p p-5 lh-0"
                  type="button"
                  @click="(form.event.action=='create'),showModal('event')"
                >
                  <i class="ti-plus"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="pos-r bdT layer w-100 fxg-1" style="overflow-y:auto">
            <ul class="p-20 nav flex-column" v-if="isEmpty(table.event.data.event_titles)">
              <li class="nav-item nav-title-header">
                <a href="javascript:void(0)" class="nav-link c-grey-800 cH-blue-500 active">
                  <div class="peers ai-c jc-sb">
                    <div class="peer peer-greed">
                      <span>
                        <i v-show="table.event.fetch_status=='fetching'">Fetching data...</i>
                        <i v-show="table.event.fetch_status=='fetched'">Nothing to display...</i>
                      </span>
                    </div>
                    <div class="peer"></div>
                  </div>
                </a>
              </li>
            </ul>
            <ul class="p-20 nav flex-column" v-else>
              <li
                class="nav-item nav-title-header"
                v-for="events in table.event.data.event_titles"
                v-bind:key="events.id"
              >
                <a
                  href="javascript:void(0)"
                  class="nav-link c-grey-800 cH-blue-500 active"
                  @click="(form.event.action = 'update'),(endpoints.update.event=endpoints.tmp.update.event+events.id),(endpoints.delete.event=endpoints.tmp.delete.event+events.id),(form.event.title=events.title),(form.event.color.hex=events.color),showModal('event')"
                >
                  <div class="peers ai-c jc-sb">
                    <div class="peer peer-greed">
                      <span>{{events.title}}</span>
                    </div>
                    <div class="peer">
                      <div class="circle" :style="{ backgroundColor: events.color,}"></div>
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
            <div class="bgc-grey-200 ai-c jc-sb p-20 fxw-nw">
              <div class="row">
                <div class="col">
                  <!-- <div class="btn-group" role="group">
                    <button
                      type="button"
                      class="email-side-toggle d-n@md+ btn bgc-white bdrs-2 mR-3 cur-p"
                    >
                      <i class="ti-menu"></i>
                    </button>
                  </div>-->
                  <div
                    style="font-size:.8em"
                    v-if="!isEmpty(local.agents.paginated)"
                  >{{'Showing '+((local.agents.paginated.cur*10)-9)+' to '+((local.agents.paginated.total_result) > (local.agents.paginated.cur*10)? local.agents.paginated.cur*10: local.agents.paginated.total_result)+' of '+local.agents.paginated.total_result}}</div>
                  <div v-else class="loader-12" style="height:10px"></div>
                </div>

                <div class="col text-right">
                  <div class="btn-group" role="group">
                    <button
                      type="button"
                      class="fsz-xs btn btn-xs bgc-white bdrs-2 mR-3 cur-p"
                      @click="paginate((local.agents.search!=''?local.agents.search_array:local.agents.array),local.agents.paginated.prev,local.agents.per_page)"
                      :disabled="local.agents.paginated.prev == null"
                    >
                      <i class="ti-angle-left"></i>
                    </button>
                    <button
                      type="button"
                      class="fsz-xs btn btn-xs bgc-white bdrs-2 mR-3 cur-p"
                      @click="paginate((local.agents.search!=''?local.agents.search_array:local.agents.array),local.agents.paginated.next,local.agents.per_page)"
                      :disabled="local.agents.paginated.next == null"
                    >
                      <i class="ti-angle-right"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="layer w-100">
            <div class="bdT bdB">
              <input
                type="text"
                class="form-control m-0 bdw-0 pY-15 pX-20"
                v-model="local.agents.search"
                @input="searchAgent(local.agents.search)"
                placeholder="Search..."
              >
            </div>
          </div>
          <div class="layer w-100 fxg-1 pos-r" style="overflow-y:auto">
            <div>
              <div
                v-for="(agent,index) in local.agents.paginated.data"
                v-bind:key="agent.id"
                @click="(local.agents.selected_index=index)"
                class="email-list-item peers fxw-nw p-20 bdB bgcH-grey-100 cur-p"
              >
                <div class="peer mR-5">
                  <!-- <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                    <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                    <label for="inputCall1" class="peers peer-greed js-sb ai-c"></label>
                  </div>-->
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
                  <span class="whs-nw w-100 ov-h tov-e d-b">{{agent.email}}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="email-content h-100">
          <div class="h-100 pos-r" style="overflow-y:auto">
            <!-- <div class="bgc-grey-200 peers ai-c jc-sb p-20 fxw-nw d-n@md+">
              <div class="peer">
                <div class="btn-group" role="group">
                  <button type="button" class="back-to-mailbox btn bgc-white bdrs-2 mR-3 cur-p">
                    <i class="ti-angle-left"></i>
                  </button>
                  <button type="button" @click="show()" class="btn bgc-white bdrs-2 mR-3 cur-p">
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
            </div>-->
            <div class="email-content-wrapper">
              <div class="peers ai-c jc-sb pX-40 pY-30">
                <div class="peers peer-greed">
                  <div class="peer mR-20">
                    <img
                      v-if="!isEmpty(local.agents.paginated) && local.agents.paginated.data[local.agents.selected_index].info.image!=null"
                      class="bdrs-50p w-3r h-3r"
                      :src="local.agents.paginated.data[local.agents.selected_index].info.image"
                    >
                    <img v-else class="bdrs-50p w-3r h-3r" src="/images/nobody.jpg">
                  </div>
                  <div class="peer">
                    <h5
                      v-if="!isEmpty(local.agents.paginated)"
                      class="c-grey-900 mB-5"
                      v-html="local.agents.paginated.data[local.agents.selected_index].full_name"
                    ></h5>
                    <div v-else style="width:200px;height:24px;">
                      <div class="loader-18"></div>
                    </div>
                    <span
                      v-if="!isEmpty(local.agents.paginated)"
                      v-html="local.agents.paginated.data[local.agents.selected_index].email"
                    ></span>
                    <div v-else style="width:200px;height:20px;">
                      <div class="loader-15"></div>
                    </div>

                    <br>
                    <small
                      v-if="!isEmpty(local.agents.paginated)"
                      class="badge badge-pill bgc-deep-purple-50 c-deep-purple-700"
                      v-html="local.agents.paginated.data[local.agents.selected_index].team_leader?local.agents.paginated.data[local.agents.selected_index].team_leader:'TL not assigned'"
                    ></small>
                    <small
                      v-if="!isEmpty(local.agents.paginated)"
                      class="badge badge-pill bgc-deep-purple-50 c-deep-purple-700"
                      v-html="local.agents.paginated.data[local.agents.selected_index].operations_manager?local.agents.paginated.data[local.agents.selected_index].operations_manager:'OM not assigned'"
                    ></small>
                  </div>
                </div>
                <div class="peer">
                  <button
                    class="btn btn-danger bdrs-50p p-15 lh-0"
                    type="button"
                    @click="(form.schedule.action=='create'),fetchSelectOptions(endpoints.select.schedule_title,'schedule','title'),showModal('schedule')"
                  >
                    <i class="ti-plus"></i>
                  </button>
                </div>
              </div>
              <div class="bdT">
                <full-calendar
                  ref="calendar"
                  :events="(!isEmpty(local.agents.paginated)?local.agents.paginated.data[local.agents.selected_index].calendar.events:[])"
                  :config="local.calendar.config"
                ></full-calendar>
              </div>
            </div>
          </div>
        </div>
      </div>
      <profile-preview-modal v-bind:user-profile="userId"></profile-preview-modal>
      <!-- Modal -->
      <!-- Modal -->
      <!-- Schedule Form Modal -->
      <modal name="schedule" :pivotY="0.2" :scrollable="true" height="auto">
        <div class="layer">
          <div class="e-modal-header bd">
            <h5 style="margin-bottom:0px">Schedule</h5>
          </div>
          <div class="w-100 p-15 pT-80" style>
            <div class="container">
              <form action>
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="exampleFormControlSelect1">Event</label>
                      <model-select
                        placeholder="Event"
                        :options="form.schedule.select_option.title"
                        v-model="form.schedule.title"
                      ></model-select>
                    </div>
                  </div>
                  <div class="col">
                    <label>Date</label>
                    <date-time-picker
                      class="s-modal"
                      v-model="form.schedule.event"
                      range-mode
                      overlay-background
                      color="red"
                      format="YYYY-MM-DD"
                      formatted="ddd D MMM YYYY"
                      label="Select range"
                      :disabled="form.schedule.action=='update'"
                    />
                  </div>
                </div>
                <div class="row pT-5">
                  <div class="col">
                    <label>Time IN</label>
                    <date-time-picker
                      class="s-modal"
                      v-model="form.schedule.time_in"
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
                      v-model="form.schedule.hours"
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

          <div class="e-modal-footer bd">
            <div class="row">
              <div class="peer peer-greed text-left pL-20">
                <button
                  v-show="form.schedule.action=='update'"
                  class="btn"
                  @click="(local.form.delete=true),cudSched()"
                >Delete</button>
              </div>

              <div class="peer text-right pR-20">
                <button class="btn btn-secondary" @click="hideModal('schedule')">Cancel</button>
                <button
                  class="btn btn-danger"
                  @click="(form.schedule.title!='' && form.schedule.event.start!='' && form.schedule.time_in !='' && form.schedule.hours!='' ? cudSched():formValidationError())"
                >Confirm</button>
              </div>
            </div>
          </div>
        </div>
      </modal>

      <!-- event -->
      <modal name="event" :pivotY="0.2" :scrollable="true" width="300px" height="auto">
        <div class="layer">
          <div class="e-modal-header bd">
            <h5 style="margin-bottom:0px">Events</h5>
          </div>
          <div class="w-100 p-15 pT-80" style>
            <div class="container">
              <form action>
                <div class="row pT-5">
                  <div class="col">
                    <label>Color:</label>
                    <compact-picker v-model="form.event.color"/>
                  </div>
                </div>
                <div class="row pT-5">
                  <div class="col">
                    <label>Title:</label>
                    <input type="text" class="form-control" v-model="form.event.title">
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="e-modal-footer bd">
            <div class="row">
              <div class="peer peer-greed text-left">
                <button
                  v-show="form.event.action=='update'"
                  class="btn"
                  @click="store({},'delete','event')"
                >Delete</button>
              </div>
              <div class="peer" style="text-align:right">
                <button class="btn btn-secondary" @click="hideModal('event')">Cancel</button>
                <button
                  class="btn btn-danger"
                  @click="(form.event.color != '' && form.event.title != '' ? 
                      store(
                        {
                            color: form.event.color.hex,
                            title: form.event.title
                        },
                        form.event.action,
                        'event'
                            ) :
                      formValidationError())"
                >Confirm</button>
              </div>
            </div>
          </div>
        </div>
      </modal>
      <daily-work-report-modal></daily-work-report-modal>
      <!-- notification -->
      <notifications group="foo" animation-type="velocity" position="bottom right"/>
    </div>
  </div>
</template>


​<style>
.e-modal-header,
.e-modal-footer {
  min-height: 50px;
  padding: 20px 20px 20px 20px;
  width: 100%;
}
.e-modal-header {
  position: absolute;
  top: 0px;
}
.v--modal-overlay .v--modal-box {
  position: relative;
  overflow: visible;
  box-sizing: border-box;
}
.loader-12 {
  height: 12px;
  width: 100%;
  position: relative;
  overflow: hidden;
  background-color: #bdbdbd;
}

.loader-12:before {
  display: block;
  position: absolute;
  content: "";
  left: -200px;
  width: 200px;
  height: 12px;
  background-color: #ababab;
  animation: loading 2s linear infinite;
}

.loader-15 {
  height: 15px;
  width: 100%;
  position: relative;
  overflow: hidden;
  background-color: #bdbdbd;
}
.fc-view-month .fc-event,
.fc-view-agendaWeek .fc-event,
.fc-content {
  font-size: 0;
  overflow: hidden;
  height: 5px;
}

.fc-view-agendaWeek .fc-event-vert {
  font-size: 0;
  overflow: hidden;
  width: 5px !important;
}
.loader-15:before {
  display: block;
  position: absolute;
  content: "";
  left: -200px;
  width: 200px;
  height: 15px;
  background-color: #ababab;
  animation: loading 2s linear infinite;
}

.loader-18 {
  height: 18px;
  width: 100%;
  position: relative;
  overflow: hidden;
  background-color: #bdbdbd;
}

.loader-18:before {
  display: block;
  position: absolute;
  content: "";
  left: -200px;
  width: 200px;
  height: 18px;
  background-color: #ababab;
  animation: loading 2s linear infinite;
}

@keyframes loading {
  from {
    left: -200px;
    width: 30%;
  }

  50% {
    width: 30%;
  }

  70% {
    width: 70%;
  }

  80% {
    left: 50%;
  }

  95% {
    left: 120%;
  }

  to {
    left: 100%;
  }
}
</style>

<script>
import moment from "moment";
import { BasicSelect } from "vue-search-select";
import { Compact } from "vue-color";
import { ModelSelect } from "vue-search-select";

export default {
  props: ["userId"],
  components: { BasicSelect, "compact-picker": Compact, ModelSelect },
  mounted() {
    this.fetchTableObject("event");
    this.fetchAgentList();
  },
  data() {
    return {
      local: {
        agents: {
          array: [],
          paginated: [],
          search_array: [],
          per_page: 10,
          selected_index: 0,
          search: "",
          selected_id: ""
        },
        form: {
          calendar: {
            endpoints: {
              retreive: "/api/v1/schedules/agents"
            }
          },
          delete: false
        },
        calendar: {
          agent_schedule: [],
          events: [],
          fetch_status: "fetching",
          config: {
            eventClick: event => {
              console.log(event.start._i);
              let date_today = new Date(moment().format("YYYY/MM/DD hh:mm:ss"));
              let event_start_date = new Date(event.start._i);

              if (date_today > event_start_date) {
                // console.log("DISPLAY WORK REPORT MODAL");
                this.showModal("daily-work-report-modal");
              } else {
                // console.log("DISPLAY EDIT SCHEDULE MODAL");
                this.showModal("schedule");
                this.form.schedule.action = "update";
                this.endpoints.update.schedule =
                  "/api/v1/schedules/update/" + event.id;
                this.fetchSelectOptions(
                  this.endpoints.select.schedule_title,
                  "schedule",
                  "title"
                );
                this.form.schedule.title = this.table.event.data.event_titles.filter(
                  index => index.title == event.title
                )[0].id;
                // this.form.schedule.title = this.form.schedule.select_option.title.filter(
                //   index =>
                //     index.text.trim().toLoweCase() ===
                //     event.title.trim().toLowerCase()
                // )[0].id;
                this.form.schedule.event.start = event.start;
                this.form.schedule.time_in =
                  event.start._i.split(" ")[1].split(":")[0] +
                  ":" +
                  event.start._i.split(" ")[1].split(":")[1];
                let duration = moment.duration(event.end.diff(event.start));
                if (duration._data.minutes == 0) {
                  this.form.schedule.hours = duration._data.hours + ":00";
                } else {
                  this.form.schedule.hours =
                    duration._data.hours + ":" + duration._data.minutes;
                }
                this.endpoints.delete.schedule =
                  "/api/v1/schedules/delete/" + event.id;

                this.form.schedule.schedule_id = event.id;
              }

              //   this.form.edit = true;
              //   this.form.label = "Edit Schedule";
              //   this.form.delete_btn = true;
              //   this.scheduleTitleOptions();
              //   let pageurl = "/api/v1/schedules/fetch/" + event.id;
              //   let fetched_event = "";
              //   fetch(pageurl)
              //     .then(res => res.json())
              //     .then(res => {
              //       // return res.meta.agent_schedule;
              //       let temp = res.meta.agent_schedule;
              //       this.form.title = temp.title.id;
              //       this.form.id = temp.id;
              //     })
              //     .catch(err => console.log(err));
              //   // this.form.title = fetched_event.title.id;
              //   this.form.event.start = event.start;
              //   this.form.time_in =
              //     event.start._i.split(" ")[1].split(":")[0] +
              //     ":" +
              //     event.start._i.split(" ")[1].split(":")[1];
              //   let duration = moment.duration(event.end.diff(event.start));
              //   if (duration._data.minutes == 0) {
              //     this.form.hours = duration._data.hours + ":00";
              //   } else {
              //     this.form.hours =
              //       duration._data.hours + ":" + duration._data.minutes;
              //   }
            },

            eventRender(event, element) {
              if (event != null) {
                var etitle = event.title,
                  start = moment(
                    event.start._i.split(" ")[1],
                    "HH:mm:ss"
                  ).format("hh:mm a"),
                  end = moment(event.end._i.split(" ")[1], "HH:mm:ss").format(
                    "hh:mm a"
                  );

                element.attr({
                  "data-toggle": "tooltip",
                  "data-placement": "top",
                  title: etitle + " " + start + " to " + end
                });
              }
            },
            defaultView: "listMonth",
            header: {
              left: "title",
              center: "",
              right: "today listMonth,month prev,next"
            },
            nowIndicator: true,
            disableDragging: true,
            editable: false
          }
        }
      }
    };
  },
  methods: {
    // modal Toggle Functions
    fetchAgentList: function() {
      let pageurl = "/api/v1/schedules/agents?sort=email&order=asc";
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          this.local.agents.array = res.meta.agents;
          this.paginate(this.local.agents.array, 1, this.local.agents.per_page);
        })
        .catch(err => console.log(err));
    },
    paginate: function(obj, page, per_page) {
      var page = page,
        per_page = per_page,
        offset = (page - 1) * per_page,
        paginatedItems = obj.slice(offset).slice(0, per_page),
        total_pages = Math.ceil(obj.length / per_page);
      this.local.agents.selected_index = 0;
      this.local.agents.paginated = {
        data: paginatedItems,
        cur: page,
        prev: page - 1 ? page - 1 : null,
        next: total_pages > page ? page + 1 : null,
        total_result: obj.length,
        total_pages: total_pages
      };
      console.log(this.local.agents.paginated);
    },
    fetchAgentSched: function(id) {
      let pageurl = "/api/v1/schedules/agents/" + id;
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          if (!this.isEmpty(res.meta.agent.calendar.events)) {
            this.local.agents.paginated.data[
              this.local.agents.selected_index
            ].calendar.events = res.meta.agent.calendar.events;
          }
        })
        .catch(err => console.log(err));
    },
    searchAgent: function(query) {
      var obj = [];
      obj = this.local.agents.array.filter(index =>
        index.full_name
          .trim()
          .toLowerCase()
          .includes(query.trim().toLowerCase())
      );
      console.log(obj);
      if (obj.length > 0) {
        this.local.agents.search_array = obj;
        this.paginate(
          this.local.agents.search_array,
          1,
          this.local.agents.per_page
        );
      }
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
    cudSched: function() {
      //add something
      let id = this.local.agents.paginated.data[
        this.local.agents.selected_index
      ].id;
      let pageurl = "";
      let form = this.form.schedule;
      var obj = [{ auth_id: this.userId }];

      if (this.local.form.delete == true) {
        this.local.form.delete = false;
        pageurl = "/api/v1/schedules/delete/" + this.form.schedule.schedule_id;
      } else {
        if (form.action == "create") {
          pageurl = "/api/v1/schedules/create/bulk/";
          let dates = [];
          if (form.event.end == null) {
            dates.push(moment(moment(form.event.start)).format("YYYY-MM-DD"));
          } else {
            dates = this.getDates(form.event.start, form.event.end);
          }
          $.each(dates, function(k, v) {
            let start =
              form.time_in != "" ? v + " " + form.time_in + ":00" : "";
            let hr = form.hours.split(":");
            let obj_element = {
              title_id: form.title,
              user_id: id,
              start_event: start,
              end_event:
                form.time_in != ""
                  ? form.hours == "00:00"
                    ? moment(
                        moment(start)
                          .add("24", "h")
                          .toDate()
                      ).format("YYYY-MM-DD HH:mm:ss")
                    : moment(
                        moment(start)
                          .add(hr[0], "h")
                          .add(hr[1], "m")
                          .toDate()
                      ).format("YYYY-MM-DD HH:mm:ss")
                  : ""
            };
            obj.push(obj_element);
          });
        } else if (form.action == "update") {
          pageurl =
            "/api/v1/schedules/update/" + this.form.schedule.schedule_id;
          let hr = form.hours.split(":");
          let start =
              form.time_in == ""
                ? form.event.start
                : form.event.start + " " + form.time_in + ":00",
            end =
              form.time_in != ""
                ? form.hours == "00:00"
                  ? moment(
                      moment(start)
                        .add("24", "h")
                        .toDate()
                    ).format("YYYY-MM-DD HH:mm:ss")
                  : moment(
                      moment(start)
                        .add(hr[0], "h")
                        .add(hr[1], "m")
                        .toDate()
                    ).format("YYYY-MM-DD HH:mm:ss")
                : "";
          obj =
            // {user_id: this.user_id},
            {
              title_id: form.title,
              user_id: id,
              start_event: start,
              end_event: end
            };
          console.log(obj);
        }
      }

      fetch(pageurl, {
        method: "post",
        body: JSON.stringify(obj),
        headers: {
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(res => {
          console.log(res);
          this.hideModal("schedule");
          this.notify("success", form.action);
          this.fetchAgentSched(id);
        })
        .catch(err => console.log(err));
    }
  }
};
</script>