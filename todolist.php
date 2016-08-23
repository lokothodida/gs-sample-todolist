<?php
// == Define useful constants ==
// ID, version and plugin folder
define('TODOLIST_ID', basename(__FILE__, '.php'));
define('TODOLIST_VERSION', '1.0.0');
define('TODOLIST_PLUGINPATH', GSPLUGINPATH . TODOLIST_ID . '/');

// Path for saving the plugin's data
define('TODOLIST_DATAPATH', GSDATAOTHERPATH . TODOLIST_ID . '/');
define('TODOLIST_FILE', TODOLIST_DATAPATH . 'todolist.json');

// Front-end plugin-specific URLs
define('TODOLIST_PLUGINURL', $SITEURL . '/plugins/' . TODOLIST_ID . '/');
define('TODOLIST_JSURL', TODOLIST_PLUGINURL . 'js/');
define('TODOLIST_CSSURL', TODOLIST_PLUGINURL . 'css/');
define('TODOLIST_APIURL', $SITEURL . '/?api=' . TODOLIST_ID);

// Error statuses (codes)
define('TODOLIST_MKDIR_ERR', -1);
define('TODOLIST_INITFILE_ERR', -2);
define('TODOLIST_SAVE_ERR', -3);
define('TODOLIST_GET_ERR', -4);

// == Load the language file (en_US by default ==
i18n_merge(TODOLIST_ID) || i18n_merge(TODOLIST_ID, 'en_US');

// == Register plugin ==
register_plugin(
  TODOLIST_ID,
  todolist_i18n('PLUGIN_TITLE'),
  TODOLIST_VERSION,
  'Lawrence Okoth-Odida',
  'https://github.com/lokothodida/',
  todolist_i18n('PLUGIN_DESC'),
  'plugins',
  'todolist_admin'
);

// == Register actions and filters ==
// Add sidebar link
add_action('plugins-sidebar', 'createSideMenu', array(TODOLIST_ID, todolist_i18n('PLUGIN_SIDEBAR')));

// API entry point (before the template is rendered)
add_action('index-pretemplate', 'todolist_api_entrypoint');

// == Functions ==
// Admin panel
function todolist_admin() {
  // Initialize the necessary files
  $init = todolist_init();

  // Load the page
  require(TODOLIST_PLUGINPATH . 'index.php');
}

// i18n alias function
function todolist_i18n($hash, $echo = false) {
  return i18n(TODOLIST_ID . '/' . $hash, $echo);
}

// Initialization (create necessary files)
function todolist_init() {
  // Create the directory if it doesn't exist
  $mkdir = true;
  if (!file_exists(TODOLIST_DATAPATH)) {
    $mkdir = mkdir(TODOLIST_DATAPATH, 0755);
  }

  if (!$mkdir) {
    return TODOLIST_MKDIR_ERR;
  }

  // Create some sample todos if there is no existing todo file
  $init = true;
  if (!file_exists(TODOLIST_FILE)) {
    // Save some sample todos
    $todos = array(
      array('desc' => 'Read the sample code', 'completed' => false),
      array('desc' => 'Download the sample', 'completed' => false),
      array('desc' => 'Create a plugin', 'completed' => false),
    );

    $init = todolist_save($todos, true);
  }

  if (!$init) {
    return TODOLIST_INITFILE_ERR;
  }

  return $mkdir && $init;
}

// API Functions
// Get the todos
function todolist_get() {
  $content = @file_get_contents(TODOLIST_FILE);

  // If we failed to get the content, or couldn't parse the JSON, return an error
  if (!$content || !($content = @json_decode($content))) {
    return TODOLIST_GET_ERR;
  } else {
    return (array) $content;
  }
}

// Save the todos
function todolist_save($todos = array(), $init = false) {
  if ($init) {
    $data = array();
  } else {
    $data = todolist_get();

    if ($data === TODOLIST_GET_ERR) {
      return TODOLIST_GET_ERR;
    } else {
      $data = (array) $data;
    }
  }

  // Validate the todos
  foreach ($todos as $i => $todo) {
    // "completed" status should be a boolean
    $todos[$i]['completed'] = filter_var($todo['completed'], FILTER_VALIDATE_BOOLEAN);
  }

  // Save the todos
  $data['todos'] = $todos;
  $save = @file_put_contents(TODOLIST_FILE, json_encode($data));

  if (!$save) {
    return TODOLIST_SAVE_ERR;
  } else {
    return $save;
  }
}

// Route to the correct action and get the result
function todolist_api() {
  $result = array();

  if (cookie_check() && isset($_POST['save']) && isset($_POST['todos'])) {
    // Save todos, only if the user is logged in as an admin
    $save = todolist_save($_POST['todos']);

    if ($save === TODOLIST_SAVE_ERR) {
      $message = todolist_i18n('TODOLIST_SAVE_ERR');
    } else if ($save === TODOLIST_GET_ERR) {
      $message = todolist_i18n('TODOLIST_GET_ERR');
    } else {
      $message = todolist_i18n('TODOLIST_SAVE_SUCC');
    }

    $result['success'] = $save;
    $result['message'] = $message;
  } elseif (isset($_GET['get'])) {
    // Get todos
    $get = todolist_get();

    if ($get === TODOLIST_GET_ERR) {
      $message = todolist_i18n('TODOLIST_GET_ERR');
    } else {
      $message = todolist_i18n('TODOLIST_GET_SUCC');
      $result['todos'] = $get['todos'];
    }

    $result['success'] = $get;
    $result['message'] = $message;
  } else {
    // API error
    $result['success'] = false;
    $result['message'] = todolist_i18n('API_ERR');
  }

  return $result;
}

function in_todolist_api() {
  // Check URL is of the form $SITEURL...?...api=TODOLIST_ID...
  return isset($_GET['api']) && $_GET['api'] === TODOLIST_ID;
}

function todolist_api_entrypoint() {
  // If a call to the API is made, execute thta API call
  if (in_todolist_api()) {
    header('Content-Type: application/json');
    $result = todolist_api();

    // Set the server response code if there was a problem
    if (!$result['success']) {
      http_response_code(500);
    }

    exit(json_encode($result));
  }
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