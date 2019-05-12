<template>
  <td style="font-size:.95em" class="text-center">
    <span
      data-toggle="tooltip"
      data-placement="right"
      :title="(!isEmpty(schedule) || schedule.title_id<3)?schedule.regular_hours.time:'0'"
    >{{(!isEmpty(schedule) || schedule.title_id<3)? getDurationBySeconds(schedule.regular_hours.second):'0'}}</span>
  </td>
</template>
<script>
export default {
  props: ["schedule"],
  data() {
    return {};
  },
  mounted() {
    // console.log(this.schedule);
  }
};
</script>
