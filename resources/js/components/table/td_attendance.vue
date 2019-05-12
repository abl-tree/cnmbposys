<template>
  <td style="font-size:.99em" class="text-center">
    <div>
      <span
        :cLass="component.table.td.badges.attendance[strToLower(attendance)].class+' badge badge-pill p-5 fw-900 w-100'"
      >{{attendance=='leave'?strToUpper(schedule.title.title):component.table.td.badges.attendance[strToLower(attendance)].label}}</span>
    </div>
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
