<template>
  <td style="font-size:.95em" class="text-center">
    <span
      data-toggle="tooltip"
      data-placement="right"
      :title="schedule.regular_hours.time"
    >{{getDurationBySeconds(schedule.regular_hours.second)}}</span>
  </td>
</template>
<script>
export default {
  props: ["schedule"],
  data() {
    return {};
  }
};
</script>
