<template>
  <td style="font-size:.95em" class="text-center">
    <template v-if="schedule.overtime.nonbillable.second>0">
      <b-button
        :id="'ot-popover-'+index"
        variant="link"
        class="p-0 m-0"
        @click="schedule.overtime.nonbillable.time"
        style="font-size:1em"
      >{{getDurationBySeconds(schedule.overtime.nonbillable.second)}}</b-button>
    </template>
    <template v-else>
      <span>0</span>
    </template>
  </td>
</template>
<script>
export default {
  props: ["schedule", "index"],
  data() {
    return {};
  }
};
</script>
