<!-- app root -->
<div id="todolist">
  <app></app>
</div>

<!--web components library (vuejs)-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.26/vue.js"></script>

<!--module loader (requirejs) - using this, we won't need to worry about the order that the components are loaded -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/require.js/2.2.0/require.js"></script>

<!--load the components-->
<?php todolist_component("app"); ?>
<?php todolist_component("status"); ?>
<?php todolist_component("todo"); ?>

<!--main script-->
<script>
// Protect the scope of your variables
(function(require) {
  // Define the configuration object (passing on the PHP variables)
  var config = <?php todolist_js_config(true); ?>;

  // Make config available to all other JS modules
  define("config", [], function() { return config; });

  // Allow us to require() JS files from the plugin's /js directory
  require.config({ baseUrl: config.js_base_url });

  // Initialize the application
  require(["components/app"], function(app) {
    new Vue({
      el: "#todolist",
      components: { app: app },
    });
  });
})(requirejs);
</script>