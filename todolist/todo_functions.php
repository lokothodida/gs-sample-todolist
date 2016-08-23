<?php
// == Todo Functions ==
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