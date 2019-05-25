<template>
  <li class="notifications" data-toggle="tooltip" :title="title">
    <span v-if="count>0" class="counter bgc-red">{{count}}</span>
    <a href="/incident_reports" class="dropdown-toggle no-after">
      <i class="ti-write"></i>
    </a>
  </li>
</template>


<script>
export default {
  //config
  // >>> icon
  // >>> message
  // >>> pageurl
  // >>> fetchurl

  props: ["accessId", "userId"],
  mounted() {
    this.fetchCount();
  },
  data() {
    return {
      count: 0,
      title: ""
    };
  },
  methods: {
    fetchCount: function() {
      let fetch_url = "/api/v1/reports";
      fetch(fetch_url)
        .then(res => res.json())
        .then(res => {
          let reports = [
            ...new Set([].concat(...res.meta.all_reports.map(o => o.reports)))
          ];
          console.log(reports);
          this.count = reports.filter(this.getUserUnrespondedReports).length;
          this.title =
            "You've got " + this.count + " unresponded IR report/s...";
          // console.log(this.userId);
        })
        .catch(err => {
          console.log(err);
        });
    },
    getUserUnrespondedReports: function(report) {
      return (
        report.issued_to.id == this.userId &&
        report.report_details != null &&
        report.report_details.agent_response === null
      );
    }
  }
};
</script>
