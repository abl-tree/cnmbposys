<template>
  <td style="font-size:.95em" class="text-right">
    <div>
      <span
        data-toggle="tooltip"
        data-placement="right"
        :title="schedule.rendered_hours.billable.time"
      >
        {{getDurationBySeconds(schedule.rendered_hours.billable.second)}}
        <span
          class="mL-3"
          style="font-size:.8em"
        >REG</span>
      </span>
    </div>
    <div>
      <span data-toggle="tooltip" data-placement="right" :title="schedule.overtime.billable.time">
        {{getDurationBySeconds(schedule.overtime.billable.second)}}
        <span
          class="mL-3"
          style="font-size:.8em"
        >OT</span>
      </span>
    </div>
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
