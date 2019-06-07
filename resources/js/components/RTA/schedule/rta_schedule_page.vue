<template>
  <div class="fluid-container">
    <div class="row">
      <div class="col bd bgc-white p-20">
        <h6>Schedules</h6>
        <div class="row mB-5">
          <div class="col-md-4 mB-3">
            <date-time-picker
              no-label
              range
              no-shortcuts
              only-date
              format="YYYY-MM-DD"
              formatted="ddd D MMM YYYY"
              color="red"
              v-model="rta_schedule_page.filters.week"
              @input="datepickerEvent()"
            >
              <button class="btn bgc-red-500 c-white form-control"></button>
            </date-time-picker>
          </div>
          <div class="col-md-4 offset-md-4 mB-3">
            <div class="input-group">
              <select name id class="select form-control">
                <option value="1">All</option>
                <option value="2">Operations Manager</option>
                <option value="3">Team Leader</option>
              </select>
              <select name id class="select form-control">
                <option value="2">Some OM</option>
                <option value="3">Some TL</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row mB-5">
          <div class="col-md-4">
            <div class="row">
              <div class="col-md-6 mB-3">
                <div class="btn-group w-100">
                  <button
                    class="btn bgc-blue-500 c-white"
                    data-toogle="tooltip"
                    title="Toggle select"
                  >Select</button>
                  <button
                    class="btn bgc-blue-500 c-white"
                    data-toggle="tooltip"
                    title="Select All"
                    disabled
                  >All</button>
                  <button
                    class="btn bgc-blue-500 c-white"
                    data-toggle="tooltip"
                    title="Clear Select"
                    disabled
                  >Clear</button>
                </div>
              </div>
              <div class="col-md-6 mB-3">
                <div class="btn-group w-100">
                  <select name id class="select form-control">
                    <option selected>Select...</option>
                  </select>
                  <button class="btn bgc-red-500 c-white">
                    <i class="ti-check"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 offset-md-4 mB-3">
            <div class="btn-group w-100">
              <button
                class="btn bgc-teal-700 border-red c-white"
                data-toggle="tooltip"
                title="Excel Import Schedule"
              >
                <i class="ti-import"></i>
              </button>
              <button class="btn bgc-teal-700 c-white" data-toggle="tooltip" title="Excel Export">
                <i class="ti-export"></i>
              </button>
              <select name id class="select form-control">
                <option value="1">Backup</option>
                <option value="2">Blank with Leaves</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row mB-5">
          <div class="col">
            <div class="table-responsive table-md">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col" style="width:20%">
                      <div
                        class="fsz-lg c-grey-700 align-self-center"
                        style="font-weight:lighter;"
                      >AGENT</div>
                    </th>
                    <th
                      v-for="datum in rta_schedule_page.table.headers"
                      scope="col"
                      :key="datum.id"
                    >
                      <div
                        class="w-100 fsz-lg c-grey-700"
                        style="font-weight:lighter"
                      >{{ datum.day }}</div>
                      <div
                        class="w-100 fsz-xs c-grey-700"
                        style="font-weight:lighter"
                      >{{ datum.date }}</div>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <!-- <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="bdrs-50p w-3r h-3r bgc-grey-200" style="display:flex">
                                                <span
                                                    class="fsz-md c-grey-500 text-center w-100"
                                                    style="align-self:center"
                                                >EX</span>
                                            </div>
                                        </div>
                                        <div class="col-8 pL-20">
                                            <div class="w-100 text-truncate fsz-xs pL-5 font-weight-bold">Emmanuel James Emmauel Eng Lajom</div>
                                            <div class="w-100 text-truncate pL-5">emmanueljamesenglajom@cnmsolutions.net</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="position:relative">
                                    <div style="position:absolute;top:8px;left:2px;">
                                        <div class="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ti-more"></i>
                                            <div class="dropdown-menu p-0">
                                                <div class="w-100 bgc-light-blue-200 p-10">
                                                    <div class="font-weight-light text-truncate fw-600">Emmanuel James Eng Lajom</div>
                                                    <div class="font-weight-light text-truncate">2019-04-05</div>
                                                </div>
                                                <hr class='m-0'>
                                                <button class="dropdown-item">Work (REG)</button>
                                                <button class="dropdown-item">Work (OT)</button>
                                                <hr class='m-0'>
                                                <button class="dropdown-item">Vacation Leave</button>
                                                <button class="dropdown-item">Absent</button>
                                                <button class="dropdown-item">Sick Leave</button>
                                                <button class="dropdown-item">Suspended</button>
                                                <button class="dropdown-item">Terminated</button>
                                                <button class="dropdown-item">Resigned</button>
                                                <button class="dropdown-item">Promoted</button>
                                                <button class="dropdown-item">Maternity Leave</button>
                                                <button class="dropdown-item">Bereavement Leave</button>
                                                <button class="dropdown-item">Paternity Leave</button>
                                                <hr class='m-0'>
                                                <button class="dropdown-item">Clear this date</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-100 mL-10 pL-5" style="border-left:solid 4px red">
                                        <div class="w-100 text-truncate">WORK (REG) </div>
                                        <div class="w-100">06:00 AM to</div>
                                        <div class="w-100">04:00 PM</div>
                                    </div>
                                </td>
                                <td style="position:relative">
                                    <div style="position:absolute;top:8px;left:2px;">
                                        <div class="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ti-more"></i>
                                            <div class="dropdown-menu p-0">
                                                <div class="w-100 bgc-light-blue-200 p-10">
                                                    <div class="font-weight-light text-truncate fw-600">Emmanuel James Eng Lajom</div>
                                                    <div class="font-weight-light text-truncate">2019-04-05</div>
                                                </div>
                                                <hr class='m-0'>
                                                <button class="dropdown-item">Work (REG)</button>
                                                <button class="dropdown-item">Work (OT)</button>
                                                <hr class='m-0'>
                                                <button class="dropdown-item">Vacation Leave</button>
                                                <button class="dropdown-item">Absent</button>
                                                <button class="dropdown-item">Sick Leave</button>
                                                <button class="dropdown-item">Suspended</button>
                                                <button class="dropdown-item">Terminated</button>
                                                <button class="dropdown-item">Resigned</button>
                                                <button class="dropdown-item">Promoted</button>
                                                <button class="dropdown-item">Maternity Leave</button>
                                                <button class="dropdown-item">Bereavement Leave</button>
                                                <button class="dropdown-item">Paternity Leave</button>
                                                <hr class='m-0'>
                                                <button class="dropdown-item">Clear this date</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-100 mL-10 pL-5" style="border-left:solid 4px red">
                                        <div class="w-100 text-truncate">WORK (REG) </div>
                                        <div class="w-100">06:00 AM to</div>
                                        <div class="w-100">04:00 PM</div>
                                    </div>
                                </td>
                                <td style="position:relative">
                                    <div style="position:absolute;top:8px;left:2px;">
                                        <div class="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ti-more"></i>
                                            <div class="dropdown-menu p-0">
                                                <div class="w-100 bgc-light-blue-200 p-10">
                                                    <div class="font-weight-light text-truncate fw-600">Emmanuel James Eng Lajom</div>
                                                    <div class="font-weight-light text-truncate">2019-04-05</div>
                                                </div>
                                                <hr class='m-0'>
                                                <button class="dropdown-item">Work (REG)</button>
                                                <button class="dropdown-item">Work (OT)</button>
                                                <hr class='m-0'>
                                                <button class="dropdown-item">Vacation Leave</button>
                                                <button class="dropdown-item">Absent</button>
                                                <button class="dropdown-item">Sick Leave</button>
                                                <button class="dropdown-item">Suspended</button>
                                                <button class="dropdown-item">Terminated</button>
                                                <button class="dropdown-item">Resigned</button>
                                                <button class="dropdown-item">Promoted</button>
                                                <button class="dropdown-item">Maternity Leave</button>
                                                <button class="dropdown-item">Bereavement Leave</button>
                                                <button class="dropdown-item">Paternity Leave</button>
                                                <hr class='m-0'>
                                                <button class="dropdown-item">Clear this date</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-100 mL-10 pL-5" style="border-left:solid 4px red">
                                        <div class="w-100 text-truncate">WORK (REG) </div>
                                        <div class="w-100">06:00 AM to</div>
                                        <div class="w-100">04:00 PM</div>
                                    </div>
                                </td>
                                <td style="position:relative">
                                    <div style="position:absolute;top:8px;left:2px;">
                                        <div class="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ti-more"></i>
                                            <div class="dropdown-menu p-0">
                                                <div class="w-100 bgc-light-blue-200 p-10">
                                                    <div class="font-weight-light text-truncate fw-600">Emmanuel James Eng Lajom</div>
                                                    <div class="font-weight-light text-truncate">2019-04-05</div>
                                                </div>
                                                <hr class='m-0'>
                                                <button class="dropdown-item">Work (REG)</button>
                                                <button class="dropdown-item">Work (OT)</button>
                                                <hr class='m-0'>
                                                <button class="dropdown-item">Vacation Leave</button>
                                                <button class="dropdown-item">Absent</button>
                                                <button class="dropdown-item">Sick Leave</button>
                                                <button class="dropdown-item">Suspended</button>
                                                <button class="dropdown-item">Terminated</button>
                                                <button class="dropdown-item">Resigned</button>
                                                <button class="dropdown-item">Promoted</button>
                                                <button class="dropdown-item">Maternity Leave</button>
                                                <button class="dropdown-item">Bereavement Leave</button>
                                                <button class="dropdown-item">Paternity Leave</button>
                                                <hr class='m-0'>
                                                <button class="dropdown-item">Clear this date</button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td style="position:relative">
                                    <div style="position:absolute;top:8px;left:2px;">
                                        <div class="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ti-more"></i>
                                            <div class="dropdown-menu p-0">
                                                <div class="w-100 bgc-light-blue-200 p-10">
                                                    <div class="font-weight-light text-truncate fw-600">Emmanuel James Eng Lajom</div>
                                                    <div class="font-weight-light text-truncate">2019-04-05</div>
                                                </div>
                                                <hr class='m-0'>
                                                <button class="dropdown-item">Work (REG)</button>
                                                <button class="dropdown-item">Work (OT)</button>
                                                <hr class='m-0'>
                                                <button class="dropdown-item">Vacation Leave</button>
                                                <button class="dropdown-item">Absent</button>
                                                <button class="dropdown-item">Sick Leave</button>
                                                <button class="dropdown-item">Suspended</button>
                                                <button class="dropdown-item">Terminated</button>
                                                <button class="dropdown-item">Resigned</button>
                                                <button class="dropdown-item">Promoted</button>
                                                <button class="dropdown-item">Maternity Leave</button>
                                                <button class="dropdown-item">Bereavement Leave</button>
                                                <button class="dropdown-item">Paternity Leave</button>
                                                <hr class='m-0'>
                                                <button class="dropdown-item">Clear this date</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-100 mL-10 pL-5" style="border-left:solid 4px red">
                                        <div class="w-100 text-truncate">WORK (REG) </div>
                                        <div class="w-100">06:00 AM to</div>
                                        <div class="w-100">04:00 PM</div>
                                    </div>
                                </td>
                                <td style="position:relative">
                                    <div style="position:absolute;top:8px;left:2px;">
                                        <div class="checkbox checkbox-circle checkbox-info peers ai-c mB-15">
                                            <input
                                            type="checkbox"
                                            id="inputCall1"
                                            name="inputCheckboxesCall"
                                            class="peer"
                                            >
                                            <label for="inputCall1" class="peers peer-greed js-sb ai-c"></label>
                                        </div>
                                    </div>
                                </td>
                                <td style="position:relative">
                                    <div style="position:absolute;top:8px;left:2px;">
                                        <div class="checkbox checkbox-circle checkbox-info peers ai-c mB-15">
                                            <input
                                            type="checkbox"
                                            id="inputCall1"
                                            name="inputCheckboxesCall"
                                            class="peer"
                                            >
                                            <label for="inputCall1" class="peers peer-greed js-sb ai-c"></label>
                                        </div>
                                    </div>
                                    <div class="w-100 mL-10 pL-5" style="border-left:solid 4px red">
                                        <div class="w-100 text-truncate">WORK (REG) </div>
                                        <div class="w-100">06:00 AM to</div>
                                        <div class="w-100">04:00 PM</div>
                                    </div>
                                </td>
                  </tr>-->
                  <tr v-for="datum in rta_schedule_page.table.data" :key="datum.id">
                    <td>
                      <div class="row">
                        <div class="col-2">
                          <div class="bdrs-50p w-3r h-3r bgc-grey-200" style="display:flex">
                            <span
                              class="fsz-md c-grey-500 text-center w-100"
                              style="align-self:center"
                            >EX</span>
                          </div>
                        </div>
                        <div class="col-8 pL-20">
                          <div
                            class="w-100 text-truncate fsz-xs pL-5 font-weight-bold"
                          >{{ datum.agent.full_name }}</div>
                          <div class="w-100 text-truncate pL-5">{{ datum.agent.email }}</div>
                        </div>
                      </div>
                    </td>
                    <td v-for="(datum1,i) in datum.week" :key="datum1.id" style="position:relative">
                      <div style="position:absolute;top:8px;left:2px;">
                        <div class="checkbox checkbox-circle checkbox-info peers ai-c mB-15">
                          <input
                            type="checkbox"
                            :id="'cb-'+datum.agent.id+'-'+i"
                            name="inputCheckboxesCall"
                            class="peer"
                          >
                          <label :for="'cb-'+datum.id+'-'+i" class="peers peer-greed js-sb ai-c"></label>
                        </div>
                      </div>
                      <div
                        v-for="datum2 in datum1"
                        :key="datum2.id"
                        class="w-100 mL-10 pL-5"
                        :style="'border-left:solid 4px '+datum2.color"
                      >
                        <div class="w-100 text-truncate">{{ datum2.title }}</div>
                        <template v-if="datum2.time">
                          <div class="w-100">{{ datum2.time.start }} AM to</div>
                          <div class="w-100">{{ datum2.time.end }}</div>
                        </template>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Moment from "moment/moment";
import { extendMoment } from "moment-range";
const moment = extendMoment(Moment);
export default {
  props: [],
  components: {},
  data() {
    return {
      temp: {
        agent: [
          {
            id: 1,
            full_name: "Emmanuel James Lajom",
            email: "jeng@ssws.dev",
            schedules: [
              {
                id: 1, // work
                title: "Work (REG)",
                color: "red",
                start: "2019-06-02 08:00:00",
                end: "2019-06-02 16:00:00",
                time: {
                  start: "08:00 AM",
                  end: "04:00 PM"
                }
              },
              {
                id: 1, // work
                title: "Work (OT)",
                color: "purple",
                start: "2019-06-09 10:00:00",
                end: "2019-06-09 18:00:00",
                time: {
                  start: "10:00 AM",
                  end: "06:00 PM"
                }
              },
              {
                id: 1, // work
                title: "Absent",
                color: "grey",
                start: "2019-06-16 00:00:00",
                end: "2019-06-17 00:00:00",
                time: null
              },
              {
                id: 1, // work
                title: "Sick Leave",
                color: "orange",
                start: "2019-06-23 00:00:00",
                end: "2019-06-24 00: 00:00",
                time: null
              }
            ]
          }
        ]
      },
      rta_schedule_page: {
        onload: {
          data: {
            action_option: [
              "Work (REG)",
              "Work (OT)",
              "Absent",
              "Sick Leave",
              "Suspended",
              "Promoted",
              "Clear date/s"
            ],
            agents: [],
            om: [],
            tl: [],
            all: []
          }
        },
        filters: {
          personnel_access: 1,
          personnel: null,
          week: {
            start: moment().startOf("isoweek"),
            end: moment().endOf("isoweek")
          }
        },
        filtered: {
          data: []
        },
        table: {
          headers: [],
          data: []
        },
        select: {
          list: [],
          count: 0
        }
      }
    };
  },
  computed: {},
  methods: {
    //   date picker event callback function
    datepickerEvent: function() {
      let selected_date = this.rta_schedule_page.filters.week.start,
        start = moment(selected_date, "YYYY-MM-DD").startOf("isoweek"),
        end = moment(selected_date, "YYYY-MM-DD").endOf("isoweek"),
        range = moment.range(start, end),
        dates = Array.from(range.by("day")).map(m => m.format("MMM Do")),
        querydates = Array.from(range.by("day")).map(m =>
          m.format("YYYY-MM-DD")
        ),
        theaders = [];
      this.rta_schedule_page.filters.week.start = start;
      this.rta_schedule_page.filters.week.end = end;

      querydates.forEach((v, i) => {
        theaders.push({ day: moment(v).format("ddd"), date: v });
      });
      this.rta_schedule_page.table.headers = theaders;
      this.plotCalendar();
    },
    //   onload fetch
    fetchOnload: function() {
      fetch("api/v1/users")
        .then(res => json())
        .then(res => {
          let tmp = res.meta.metadata.filter(this.woDev);
          this.rta_schedule_page.onload.data.all = tmp;
          this.rta_schedule_page.onload.data.om = tmp.filter(this.isOM);
          this.rta_schedule_page.onload.data.tl = tmp.filter(this.isTL);
        })
        .catch(err => console.log(err));
    },
    fetchAgentandSchedule: function() {
      let local = this.rta_schedule_page.filters.week;
      fetch(
        "api/v1/schedules/work/reports?start=" +
          local.start +
          "&end=" +
          local.end
      )
        .then(res => json())
        .then(res => {
          console.log();
        })
        .catch(err => console.log(err));
    },
    // refetch
    refetch: function() {},
    // process fetched data
    processData: function() {},
    // process schedule data
    plotCalendar: function() {
      let agents = this.temp.agent;
      let newlist = [];
      agents.forEach(
        ((v, i) => {
          let week = [];
          this.rta_schedule_page.table.headers.forEach(
            ((v1, i1) => {
              let date = v1.date;
              week.push(
                v.schedules.filter(i =>
                  moment(i.start, "YYYY-MM-DD").isSame(
                    moment(v1.date, "YYYY-MM-DD")
                  )
                )
              );
              v.schedules.forEach(
                ((v3, i3) => {
                  console.log(
                    moment(v1.date, "YYYY-MM-DD") +
                      " == " +
                      moment(v3.start, "YYYY-MM-DD")
                  );
                  console.log(
                    moment(v1.date, "YYYY-MM-DD").isSame(
                      moment(v3.start, "YYYY-MM-DD")
                    )
                  );
                }).bind(this)
              );
            }).bind(this)
          );

          newlist.push({
            agent: {
              id: v.id,
              full_name: v.full_name,
              email: v.email
            },
            week: week
          });
        }).bind(this)
      );
      this.rta_schedule_page.table.data = newlist;
    },
    //   filter callbacks
    isAgent: function(i) {
      return i.access_id == 17;
    },
    isOM: function(i) {
      return i.access_id == 15;
    },
    isTL: function(i) {
      return i.access_id == 16;
    },
    woDev: function(i) {
      return i.id == 3;
    }
  }
};
</script>
