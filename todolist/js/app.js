// app.js
define(["vue", "components/todolist"], function(Vue, todolist) {
  // Start the application
  function init() {
    new Vue({
      el: "#todolist",
      components: { todolist: todolist },
    });
  }

  return {
    init: init,
  };
});