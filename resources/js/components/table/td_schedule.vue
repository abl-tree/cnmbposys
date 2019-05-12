<template>
  <td style="font-size:.95em" class="text-center">
    <div v-if="!this.isEmpty(schedule)">
      <template v-if="schedule.title_id<3">
        <div data-toggle="tooltip" :title="fromNow(schedule.start_event)">
          {{dtFormat(schedule.start_event,"MMMM DD hh:mm a")}}
          <span
            class="mL-5"
            style="font-size:0.8em;"
          >IN</span>
        </div>
        <div data-toggle="tooltip" :title="fromNow(schedule.end_event)">
          {{dtFormat(schedule.end_event,"MMMM DD hh:mm a")}}
          <span
            class="mL-5"
            style="font-size:0.8em;"
          >OUT</span>
        </div>
      </template>
      <template v-else-if="schedule.title_id>2 && schedule.title_id<9">
        <span
          class="badge badge-pill p-5 fw-900 w-100"
          :class="component.table.td.badges.no_schedule.class"
        >{{component.table.td.badges.no_schedule.label}}</span>
      </template>
    </div>
    <div v-else>
      <span
        class="badge badge-pill p-5 fw-900 w-100"
        :class="component.table.td.badges.no_schedule.class"
      >{{component.table.td.badges.no_schedule.label}}</span>
    </div>
  </td>
</template>
<script>
export default {
  props: ["schedule"],
  mounted() {
    // console.log(this.schedule);
  },
  data() {
    return {};
  }
};
</script>
