<template>
  <div :data-content="event.title" @click="click">
    <div>
      <i class="icon" :class="[event.icon]"></i>
      {{ event.title }}
    </div>
  </div>
</template>

<script>
export default {
  name: "CalendarEvent",

  props: {
    event: {}
  },

  methods: {
    click() {
      console.log("clicked");
    }
  },

  mounted() {
    $(this.$el).popup();
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
div {
  display: flex;
  color: white;
  align-items: center;
  justify-content: center;
  min-height: 30px;
}
</style>
