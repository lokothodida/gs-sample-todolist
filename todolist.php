<?php
// == Constants ==
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

// == Functions ==
require(TODOLIST_PLUGINPATH . 'common_functions.php');
require(TODOLIST_PLUGINPATH . 'todo_functions.php');
require(TODOLIST_PLUGINPATH . 'api_functions.php');

// == Load the language file (en_US by default) ==
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

// Admin panel
function todolist_admin() {
  // Initialize the necessary files
  $init = todolist_init();

  // Load the page
  require(TODOLIST_PLUGINPATH . 'index.php');
}