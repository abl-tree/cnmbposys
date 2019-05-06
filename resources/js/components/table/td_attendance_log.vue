<template>
  <td style="font-size:.95em" class="text-center">
    <template v-if="attendance == 'present'">
      <div>
        {{dtFormat(schedule.time_in,"MMMM DD hh:mm a")}}
        <span
          class="mL-5"
          style="font-size:0.8em;"
        >IN</span>
      </div>
      <template v-if="isEmpty(schedule.time_out)">
        <div>
          <span class="mL-5 btn btn-link c-red-500" style="font-size:0.8em;">NO LOGOUT</span>
        </div>
      </template>
      <template v-else>
        <div>
          {{dtFormat(schedule.time_out,"MMMM DD hh:mm a")}}
          <span
            class="mL-5"
            style="font-size:0.8em;"
          >OUT</span>
        </div>
      </template>
    </template>
    <template v-else>
      <div>
        <span
          :class="component.table.td.badges.attendance_log.no_logs.class+' badge badge-pill p-5 fw-900 w-100'"
        >{{component.table.td.badges.attendance_log.no_logs.label}}</span>
      </div>
    </template>
  </td>
</template>
<script>
export default {
  props: ["attendance", "schedule"],
  data() {
    return {};
  }
};
</script>
