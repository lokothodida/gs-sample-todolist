<?php
// == Common Functions ==
// i18n alias function
function todolist_i18n($hash, $echo = false) {
  return i18n(TODOLIST_ID . '/' . $hash, $echo);
}

// Get the configuration object that we want to pass on to our JavaScript
function todolist_js_config($echo = false) {
  // Basic configuration to pass on
  $config = array(
    "api_url" => TODOLIST_APIURL,
    "js_base_url" => TODOLIST_JSURL,
    "i18n" => array(),
  );

  // Load all i18n hashes for current language  (using a language file we know exists)
  require(TODOLIST_PLUGINPATH . 'lang/en_US.php');

  foreach ($i18n as $hash => $string) {
    $config['i18n'][$hash] = todolist_i18n($hash);
  }

  if ($echo) {
    echo json_encode($config);
  } else {
    return $config;
  }
}

// Load a (.vue) component
function todolist_component($name) {
  $content = @file_get_contents(TODOLIST_PLUGINPATH . 'components/' . $name . '.vue');
  echo $content;
}