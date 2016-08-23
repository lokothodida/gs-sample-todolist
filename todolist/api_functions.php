<?php
// == API Functions ==
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