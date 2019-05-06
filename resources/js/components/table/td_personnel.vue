<template>
  <td style="font-size:.95em">
    <div>
      <div class="fw-600">
        <i class="ti-user c-grey-400 mR-5"></i>
        {{personnel.full_name}}
      </div>
      <div>
        <i class="ti-email c-grey-400 mR-5"></i>
        <a
          v-if="!isEmpty(personnel.email)"
          :href="'https://mail.google.com/mail/u/0/?view=cm&fs=1&to='+personnel.email+'&tf=1'"
        >{{split(personnel.email,'@',0)}}</a>
        <span v-else>{{personnel.email}}</span>
      </div>
      <div class="c-grey-700">@cnmsolutions.net</div>
    </div>
  </td>
</template>

<script>
export default {
  props: ["personnel", "type"],
  mounted() {},
  data() {
    return {};
  }
};
</script>
