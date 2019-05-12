<template>
  <td style="font-size:.95em" :class="defined(image)?'text-center':'text-left'">
    <template v-if="defined(image)">
      <div class="p-10">
        <img v-if="image!=null" class="bdrs-50p w-3r h-3r" :src="image">
        <img v-else class="bdrs-50p w-3r h-3r" src="/images/nobody.jpg">
      </div>
    </template>
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
  props: ["personnel", "type", "image"],
  mounted() {},
  data() {
    return {};
  },
  methods: {
    defined: function(item) {
      return typeof item !== "undefined";
    }
  }
};
</script>
