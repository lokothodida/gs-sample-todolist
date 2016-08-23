/* global $ */
// Some useful utility methods
define(["config"], function(config) {
  // Get a named <template>
  function template(name) {
    return document.querySelector("template[name='" + name + "']");
  }

  // Load the todos with AJAX
  function getTodos(success, error, complete) {
    $.ajax({
      dataType: "json",
      url: config.api_url,
      type: "GET",
      data: { get: true },
      success: success,
      error: handleError(error),
      complete: complete,
    });
  }

  // Save the todos with AJAX
  function saveTodos(todos, success, error, complete) {
    $.ajax({
      dataType: "json",
      url: config.api_url,
      type: "POST",
      data: { save: true, todos: todos},
      success: success,
      error: handleError(error),
      complete: complete,
    });
  }

  // Wrapper for AJAX error handling, so there is always a message to show
  function handleError(handler) {
    return function(err) {
      try {
        var response = JSON.parse(err.responseText);
        handler(response, err);
      } catch (e) {
        handler({ message: err.responseText }, err);
      }
    };
  }

  // Filter to show only an active todo
  function filterActiveTodos(todo) {
    return !todo.delete;
  }

  // Before a todo is saved, it must only send the completion status and description
  function mapBeforeSave(todo) {
    return {
      completed: todo.completed,
      desc: todo.desc
    };
  }

  // Before a todo is loaded, it must have an edit status and deleted status
  function mapBeforeLoad(todo) {
    todo.edit = false;
    todo.delete = false;
    return todo;
  }

  return {
    template:  template,
    getTodos:  getTodos,
    saveTodos: saveTodos,
    filter: {
      active: filterActiveTodos,
    },
    map: {
      beforeSave: mapBeforeSave,
      beforeLoad: mapBeforeLoad,
    }
  };
});