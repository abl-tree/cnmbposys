<template>
  <li class="notifications" data-toggle="tooltip" :title="title">
    <span v-if="count>0" class="counter bgc-red">{{count}}</span>
    <a href="/leave_requests" class="dropdown-toggle no-after">
      <i class="ti-agenda"></i>
    </a>
  </li>
</template>


<script>
export default {
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
      let fetch_url = "/api/v1/request_schedules";
      fetch(fetch_url)
        .then(res => res.json())
        .then(res => {
          let request = res.meta.request_schedules;
          this.count = request.filter(this.getPending).length;
          this.title =
            "You've got " + this.count + " unmanaged schedule request/s...";
          // console.log(this.userId);
        })
        .catch(err => {
          console.log(err);
        });
    },
    getPending: function(status) {
      return this.isAfter(status.start_date) && status.status == "pending";
    }
  }
};
</script>
