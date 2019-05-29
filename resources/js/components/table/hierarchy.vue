<template>
  <div>
    <div class="layers">
      <template v-if="hierarchy.js_status_loading">
        <div class="p-100">
          <div class="w-100 fsz-lg fw-600 c-grey-400">Loading..</div>
        </div>
      </template>
      <template v-else>
        <template v-if="!isEmpty(hierarchy.subordinates.data)">
          <div class="layer w-100 pX-20 pT-20 pB-10">
            <!-- <h6 class="lh-1">{{config.table_name}}</h6> -->
            <h6 class="lh-1">Subordinates</h6>
            <div class="pull-right">{{ hierarchy.subordinates.count }}</div>
          </div>
          <div class="layer w-100 p-20">
            <div class="row">
              <template v-for="datum in hierarchy.subordinates.data">
                <div class="col-lg-6 mB-10" :key="datum.id">
                  <div
                    class="w-100 bgc-white p-20 bd"
                    style="box-shadow: 0 1px 2px rgba(0,0,0,0.15);position:relative"
                  >
                    <!-- <div style="position:absolute;top:15px;right:20px;">
                  <span class="ti-list cur-p"></span>
                    </div>-->
                    <tbody>
                      <tr>
                        <td width="80px">
                          <span>
                            <!-- <img v-if="image!=null" class="bdrs-50p w-3r h-3r" :src="image">
                            <img v-else class="bdrs-50p w-3r h-3r" src="/images/nobody.jpg">-->
                            <div class="bdrs-50p w-3r h-3r bgc-grey-200" style="display:flex">
                              <span
                                class="fsz-lg c-grey-500 text-center w-100"
                                style="align-self:center"
                              >{{ getNameInitials(datum.fname,datum.lname) }}</span>
                              <span
                                class="badge bgc-green-500 c-white fsz-xs"
                                style="position:absolute;"
                              >{{ }}</span>
                            </div>
                          </span>
                        </td>
                        <td>
                          <div style="display:flex">
                            <div style="align-self:center">
                              <div class="fsz-xs c-grey-900" style="font-weight:300">
                                <a :href="'/hierarchy?user='+datum.crypted_id">{{ datum.full_name }}</a>
                              </div>
                              <div
                                class="fsz-xs c-grey-600"
                                style="font-weight:lighter"
                              >{{ datum.email }}</div>
                              <div
                                class="fsz-xs c-grey-600"
                                style="font-weight:lighter"
                              >{{ datum.position }}</div>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </template>
        <template v-else>
          <div class="p-100">
            <div class="w-100 fsz-lg fw-600 c-grey-400">Nothing to display..</div>
          </div>
        </template>
      </template>
    </div>
  </div>
</template>
<style>
.text-loader {
  color: rgb(138, 138, 138);
  animation: loader 1s infinite;
}

@keyframes loading {
  from {
    left: -200px;
    width: 30%;
  }

  0% {
    color: rgb(161, 161, 161);
  }
  50% {
    color: rgb(214, 214, 214);
  }
  100% {
    color: rgb(68, 68, 68);
  }
}
</style>

<script>
import moment from "moment";
export default {
  props: ["auth", "parent"],
  mounted() {
    this.fetchSubordinates();
  },
  data() {
    return {
      hierarchy: {
        subordinates: {
          data: [],
          count: 0
        },
        js_status_loading: true
      }
    };
  },
  methods: {
    fetchSubordinates: function() {
      this.hierarchy.js_status_loading = true;
      fetch("/api/v1/users")
        .then(res => res.json())
        .then(res => {
          console.log(res);
          let data, subordinates, subordinates_count;
          //dettach inactive
          data = res.meta.metadata.filter(this.activeOnly);
          this.hierarchy.subordinates.data = data.filter(this.subordinatesOnly);
          this.hierarchy.subordinates.count = this.hierarchy.subordinates.data.length;
          this.hierarchy.js_status_loading = false;
        })
        .catch(err => console.log(err));
    },
    activeOnly: function(i) {
      return i.status != "inactive";
    },
    subordinatesOnly: function(i) {
      return i.parent_id == this.parent.id;
    },
    getNameInitials: function(fname, lname) {
      return fname[0] + "" + lname[0].toUpperCase();
    },
    getStatus: function() {
      let result;
      return;
    }
  }
};
</script>