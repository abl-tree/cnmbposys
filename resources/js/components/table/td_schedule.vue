<template>
  <td style="font-size:.95em" class="text-center">
    <div v-if="schedule!=null">
      <div data-toggle="tooltip" :title="fromNow(schedule.start)">
        {{dtFormat(schedule.start,"MMMM DD hh:mm a")}}
        <span class="mL-5" style="font-size:0.8em;">IN</span>
      </div>
      <div data-toggle="tooltip" :title="fromNow(schedule.end)">
        {{dtFormat(schedule.end,"MMMM DD hh:mm a")}}
        <span class="mL-5" style="font-size:0.8em;">OUT</span>
      </div>
    </div>
    <div v-else>
      <span
        class="badge badge-pill p-5 fw-900 w-100"
        :class="component.table.td.badges.no_schedule.class"
      >component.table.td.badges.no_schedule.label</span>
    </div>
  </td>
</template>
<script>
export default {
  props: ["schedule"],
  mounted() {},
  data() {
    return {};
  }
};
</script>
