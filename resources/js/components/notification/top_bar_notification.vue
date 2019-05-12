<template>
  <li class data-toggle="tooltip" :title="config.message">
    <span v-if="count>0" class="counter bgc-blue">3</span>
    <a :href="config.page_url" class="dropdown-toggle no-after">
      <i :class="config.icon"></i>
    </a>
  </li>
</template>

<script>
export default {
  //config
  // >>> icon
  // >>> message
  // >>> pageurl
  // >>> fetchurl

  props: ["config", "userId"],
  mounted() {
    console.log(config.fetch_url + this.userId);
  },
  data() {
    return {
      count: 0
    };
  },
  methods: {
    fetchCount: function() {
      fetch(this.config.fetch_url)
        .then(res => res.json())
        .then(res => {
          // this.count =
        })
        .catch(err => {
          console.log(err);
        });
    }
  }
};
</script>
