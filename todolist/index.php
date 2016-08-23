<!-- app root -->
<div id="todolist">
  <todolist></todolist>
</div>

<!--module loader (requirejs) - using this, we won't need to worry about the order that the components are loaded -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/require.js/2.2.0/require.js"></script>

<!--load the components-->
<?php todolist_component("todolist"); ?>
<?php todolist_component("status"); ?>
<?php todolist_component("todo"); ?>

<!--main script-->
<script>
// Protect the scope of your variables
(function(require, $) {
  // Define the configuration object (passing on the PHP variables)
  var config = <?php todolist_js_config(true); ?>;

  // Make config available to all other JS modules
  define("config", [], function() { return config });

  // If jQuery is already loaded globally, add it as a module so others can use it
  $ && define("jquery", [], function() { return $ });

  // Configure RequireJS
  require.config({
    // Allow us to require() JS files from the plugin's /js directory
    baseUrl: config.js_base_url,

    // Load Vue from a CDN
    paths: {
      "vue": "https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.26/vue",
    },
  });

  // Initialize the application
  require(["app"], function(app) { app.init() });
})(requirejs, jQuery);
</script>