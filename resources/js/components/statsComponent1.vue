<template>
  <div class="layers bd bgc-white p-20">
    <div class="layer w-100 mB-10">
      <h6 class="lh-1">{{stats.title}}</h6>
    </div>
    <div class="layer w-100">
      <div class="peers ai-sb fxw-nw">
        <div class="peer peer-greed">
          <sparkline>
            <sparklineBar
              :data="spData4"
              :margin="spMargin4"
              :limit="spData4.length"
              :styles="spBarStyles4"
              :refLineStyles="spRefLineStyles4"
            />
          </sparkline>
        </div>
        <div class="peer">
          <span
            class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500"
          >{{stats.today}}</span>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["statName"],
  mounted() {
    this.fetchStats(this.statName);
  },
  data() {
    return {
      endpoints: {
        today: {
          scheduled: "/api/v1/schedules/stats",
          off_duty: "/api/v1/schedules/stats?filter=off-duty",
          on_break: "/api/v1/schedules/stats?filter=on-break",
          working: "/api/v1/schedules/stats?filter=working",
          absent: "/api/v1/schedules/stats?filter=absent"
        }
      },
      stats: {
        title: "",
        today: "",
        sparkline: {}
      },
      title: {
        scheduled: "Scheduled",
        off_duty: "Off-Duty",
        on_break: "On-Break",
        working: "Working",
        absent: "Absent"
      },
      spData4: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
      // margin
      spMargin4: 2,
      spBarStyles4: {
        fill: "#54a5ff"
      },
      spLineStyles4: {
        stroke: "#d14"
      },
      spRefLineStyles4: {
        stroke: "#d14",
        strokeOpacity: 1,
        strokeDasharray: "3, 3"
      }
    };
  },
  methods: {
    fetchStats: function(statName) {
      let pageurl = this.endpoints.today[statName];
      this.stats.title = this.title[statName];
      console.log(pageurl);
      console.log(this.title[statName]);
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          // console.log(res);
          if (res.code == 200) {
            this.stats.today = res.meta.count;
          }
        })
        .catch(err => console.log(err));
      //   pageurl = this.endpoints.sparkline[statName];
      //   fetch(pageurl)
      //     .then(res => res.json())
      //     .then(res => {
      //       // console.log(res);
      //       if (res.code == 200) {
      //         this.stats.sparkline = res.meta;
      //       }
      //     })
      //     .catch(err => console.log(err));
    }
  }
};
</script>