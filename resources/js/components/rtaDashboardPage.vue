<template>
  <div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>

    <div class="masonry-item w-100">
      <div class="row gap-20">
        <!-- #Toatl Visits ==================== -->
        <div class="col-md-3">
          <stats-component-1 stat-name="scheduled"></stats-component-1>
        </div>

        <!-- #Total Page Views ==================== -->
        <div class="col-md-3">
          <stats-component-1 stat-name="working"></stats-component-1>
        </div>

        <!-- #Unique Visitors ==================== -->
        <div class="col-md-3">
          <stats-component-1 stat-name="tardy"></stats-component-1>
        </div>

        <!-- #Bounce Rate ==================== -->
        <div class="col-md-3">
          <stats-component-1 stat-name="off_duty"></stats-component-1>
        </div>
      </div>
    </div>

    <div class="masonry-item col-md-12">
      <today-activity></today-activity>
    </div>

    <!-- <div class="masonry-item col-md-6">
      <div class="bd bgc-white p-20">
        <h6 class="c-grey-900">Request</h6>
        <div class="mT-30">
          <div class="list-group" style="font-size:0.83em">
            <div class="list-group-item list-group-item-action">
              <div>
                <b>Ice Cream</b> requested for
                <span class="text-danger">Leave Type 1</span> under supervision of
                <b>Mango Float</b> dated 02-18-2019 to 02-25-2019
                <br>
              </div>
              <div class="peers">
                <span class="peer peer-greed">
                  <span class="text-muted">
                    <small>Request Date: 01-03-2019 12:00:00</small>
                  </span>
                </span>
                <span class="peer">
                  <button class="btn btn-outline-secondary btn-custom-size">
                    <i class="ti-close"></i>
                  </button>
                  <button class="btn btn-outline-danger btn-custom-size">Manage</button>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <incident-report :userId="user_id"></incident-report>

      <div>
        <div class="container">
          <div class="row">
            <sanction-level></sanction-level>
            <sanction-type></sanction-type>
          </div>
        </div>
      </div>
    </div>-->

    <!-- Modals -->

    <!-- profile preview modal -->
    <profile-preview-modal v-bind:user-profile="this.userId"></profile-preview-modal>
    <notifications group="foo" animation-type="velocity" position="bottom right"/>
  </div>
</template>

<script>
export default {
  props: ["userId"],
  created() {},
  mounted() {},
  data() {
    return {
      user_id: this.userId
    };
  },
  methods: {}
};
</script>