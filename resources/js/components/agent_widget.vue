<template>
  <div class="layers">
    <div class="layer w-100 p-20">
      <div class="container">
        <div class="row bdB">
          <div class="col-md-8">
            <div class="row pB-5 bdB">
              <div class="col">
                <div class="peers ai-sb fxw-nw">
                  <div class="peer mR-10">
                    <img
                      v-if="agent_widget.config.profile.image==null || agent_widget.config.profile.image==''"
                      class="bdrs-50p w-3r h-3r"
                      src="/images/nobody.jpg"
                    >
                    <img v-else class="bdrs-50p w-3r h-3r" :src="agent_widget.config.profile.image">
                  </div>
                  <div class="peer peer-greed">
                    <div class="container">
                      <div class="row peers m-0 p-0">
                        <div class="peer peer-greed">
                          <span>
                            <div
                              class="fw-500 c-blue-500 p-0 m0"
                            >{{ agent_widget.config.profile.full_name }}</div>
                            <span style="font-size:0.8em">{{ agent_widget.config.profile.email }}</span>
                            <span class="mX-5 bgc-grey" style="font-size:0.7em">&#9679;</span>
                            <span style="font-size:0.8em">{{ agent_widget.config.profile.position }}</span>
                          </span>
                        </div>
                        <div class="peer"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row pY-10 bdB">
              <div class="col-md-6">
                <div class="layer w-100 bdL bdR p-20">
                  <div class="layer w-100 text-center">
                    <small>Days under CNM</small>
                  </div>
                  <div class="layer w-100 text-center">
                    <h1>{{ agent_widget.config.stats.days }}</h1>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="layer w-100 bdL bdR p-20">
                  <div class="layer w-100 text-center">
                    <small>Total Work Schedule</small>
                  </div>
                  <div class="layer w-100 text-center">
                    <h1>{{ agent_widget.config.stats.work_days }}</h1>
                  </div>
                </div>
              </div>
            </div>
            <div class="row pY-10 bdB">
              <div class="col-md-6">
                <div class="layer w-100 bdL bdR p-20">
                  <div class="layer w-100 text-center">
                    <small>Present Days</small>
                  </div>
                  <div class="layer w-100 text-center">
                    <h1>{{ agent_widget.config.stats.present_days }}</h1>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="layer w-100 bdL bdR p-20">
                  <div class="layer w-100 text-center">
                    <small>Leave Days</small>
                  </div>
                  <div class="layer w-100 text-center">
                    <h1>{{ agent_widget.config.stats.leaved_days }}</h1>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <mini-calendar :user-id="userId"></mini-calendar>
          </div>
        </div>
      </div>
    </div>

    <div class="w-100">
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
          <stats-component-1 stat-name="leave"></stats-component-1>
        </div>

        <!-- #Bounce Rate ==================== -->
        <div class="col-md-3">
          <stats-component-1 stat-name="off_duty"></stats-component-1>
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
  props: ["userId"],
  mounted() {
    this.fetchStats();
  },
  data() {
    return {
      agent_widget: {
        datetime: 0,
        config: {
          form: {
            schedule: {
              id: "",
              attendance: {
                id: "",
                action: "create",
                time_in: "",
                time_out: ""
              }
            }
          },
          button: {
            start_work: "true"
          },
          stats: {
            days: "",
            work_days: "",
            present_days: "",
            leaved_days: ""
          },
          profile: {
            id: "",
            full_name: "",
            email: "",
            image: "",
            date_hired: "",
            position: ""
          }
        },
        data: {
          agent_reports: []
        }
      }
    };
  },
  methods: {
    getStats: function() {
      pageurl = "/api/v1/schedules/reports/" + this.user_id;
    },
    fetchStats: function() {
      let startDate = moment("2018-01-01").format("YYYY-MM-DD"),
        endDate = moment().format("YYYY-MM-DD");
      let pageurl =
        "/api/v1/schedules/work/report?start=" +
        startDate +
        "&end=" +
        endDate +
        "&userid=" +
        this.userId;
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          //   console.log(res);
          this.agent_widget.config.profile.full_name =
            res.meta.agent_schedules[0].full_name;
          this.agent_widget.config.profile.image =
            res.meta.agent_schedules[0].info.image;
          this.agent_widget.config.profile.email =
            res.meta.agent_schedules[0].email;
          this.agent_widget.config.profile.id = res.meta.agent_schedules[0].id;
          this.agent_widget.config.profile.date_hired =
            res.meta.agent_schedules[0].info.hired_date;
          this.agent_widget.config.profile.position =
            res.meta.agent_schedules[0].access.name;
          var obj = [];
          res.meta.agent_schedules[0].schedule.forEach(function(v, i) {
            obj.push({
              info: {
                full_name: res.meta.agent_schedules[0].full_name,
                image: res.meta.agent_schedules[0].info.image,
                email: res.meta.agent_schedules[0].email,
                id: res.meta.agent_schedules[0].id,
                tl: res.meta.agent_schedules[0].team_leader,
                om: res.meta.agent_schedules[0].operations_manager
              },
              schedule: v
            });
          });
          this.agent_widget.data.agent_reports = obj;
          this.agent_widget.config.stats.days = moment().diff(
            moment(res.meta.agent_schedules[0].info.hired_date).format(
              "YYYY-MM-DD"
            ),
            "days"
          );
          this.agent_widget.config.stats.work_days = obj.filter(
            this.workSchedules
          ).length;
          this.agent_widget.config.stats.present_days = obj.filter(
            this.workedSchedules
          ).length;
          this.agent_widget.config.stats.leaved_days = obj.filter(
            this.leavedSchedules
          ).length;
        })
        .catch(err => {
          console.log(err);
        });
    },
    workSchedules: function(index) {
      return index.schedule.title_id < 3;
    },
    workedSchedules: function(index) {
      return index.schedule.title_id < 3 && index.schedule.is_present == 1;
    },
    leavedSchedules: function(index) {
      return index.schedule.title_id > 2 && index.schedule.title_id < 9;
    },
    updateDateTime: function() {
      this.agent_widget.datetime = moment().format("LTS");
    }
  },
  created() {
    this.agent_widget.datetime = moment().format("LTS");
    setInterval(() => this.updateDateTime(), 1 * 1000);
  }
};
</script>
