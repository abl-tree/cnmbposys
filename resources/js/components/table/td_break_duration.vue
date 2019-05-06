<template>
  <td style="font-size:.95em" class="text-center">
    <span
      v-if="!isEmpty(schedule.break)"
      data-toggle="tooltip"
      data-placement="right"
      :title="schedule.break.total"
    >{{getDurationBySeconds(schedule.break.second)}}</span>
    <span v-else data-toggle="tooltip" data-placement="right" title="00:00:00">0</span>
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
