<template>
  <td style="font-size:.95em" class="text-left">
    <tbody class="m-0 p-0">
      <tr style="border-style:none;" class="m-0 p-0">
        <td v-if="defined(image)" style="border-style:none;" class="m-0 p-0">
          <div class="p-0">
            <img v-if="image!=''" class="bdrs-50p w-3r h-3r" :src="image">
            <div v-else class="bdrs-50p w-3r h-3r bgc-grey-200" style="display:flex">
              <span
                class="fsz-lg c-grey-500 text-center w-100"
                style="align-self:center"
              >{{ getNameInitials(personnel.fname,personnel.lname) }}</span>
              <!-- <span
                class="badge bgc-green-500 c-white fsz-xs"
                style="position:absolute;"
              >{{ }}</span>-->
            </div>
          </div>
        </td>
        <td style="border-style:none;" class="m-0 p-0">
          <div class="pL-20">
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
          </div>
        </td>
      </tr>
    </tbody>
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
    },
    getNameInitials: function(fname, lname) {
      return fname[0] + "" + lname[0].toUpperCase();
    }
  }
};
</script>
