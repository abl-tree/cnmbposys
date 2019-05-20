<template>
  <div class="layers">
    <div class="layer bd p-20 w-100">
      <div class="row">
        <!-- <div class="col-md-3 text-center">
          <div>
            <small>{{ timeTracker.today.schedule.label }}</small>
          </div>
          <div>
            <button
              class="btn form-control"
              @click="clickTracker"
            >{{  }}</button>
          </div>
        </div>-->
        <div class="col-md-1">
          <button
            class="btn bdrs-50p p-20 fsz-lg lh-0"
            :class="timeTracker.tracker.button.class"
            :disabled="timeTracker.tracker.button.disabled"
            @click="clickTracker"
          >
            <i :class="timeTracker.tracker.button.label"></i>
          </button>
        </div>

        <div class="col text-center">
          <div class="fsz-md">
            <!-- <span
              class="badge bgc-green-400 w-100 c-white"
            >{{ timeTracker.form.schedule.attendance.time_in }}</span>-->
            <span class="badge bgc-green-400 w-100 c-white">{{ timeTracker.today.schedule.label }}</span>
          </div>
          <!-- <div class="fsz-xs">00:00:00 to 00:00:00</div>
              <div class="fsz-xs">00:00:00 to 00:00:00</div>
          <div class="fsz-xs">00:00:00 to 00:00:00</div>-->
        </div>
        <div class="col text-center">
          <div class="fsz-xs">Rendered Hours</div>
          <div class="fsz-lg">{{ timeTracker.tracker.rendered_hours.value }}</div>
        </div>
        <div class="col text-center">
          <div class="fsz-xs">Remaining Break</div>
          <div class="fsz-lg">00:00:00</div>
        </div>
        <div class="col text-center">
          <div class="fsz-xs">Break Times</div>
          <div class="fsz-lg">3</div>
        </div>
        <div class="col text-right">
          <div class="fsz-lg fw-300">{{ timeTracker.today.date }}</div>
          <div>{{ timeTracker.today.time }}</div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["timeTracker"],
  data() {
    return {};
  },
  methods: {
  }
};
</script>
