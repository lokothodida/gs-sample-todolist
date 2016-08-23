<!--status message (success/error) component-->
<style>
  /* close status button */
  .status .close {
    float: right;
    text-decoration: none !important;
  }
</style>

<template name="status">
  <div class="status {{ type }}" v-if="show">
    <slot></slot>
    <a class="close" v-on:click.prevent="closeMessage" href="#">&times;</a>
  </div>
</template>

<script>
/* global Vue */
define("components/status", ["config", "utils"], function(config, utils) {
  // Export the component
  return Vue.extend({
    template: utils.template("status"),

    // Properties
    props: ["type", "show"],

    methods: {
      closeMessage: function() {
        this.show = false;
      },
    }
  });
});
</script>