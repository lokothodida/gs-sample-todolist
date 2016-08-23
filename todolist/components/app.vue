<!--app component-->
<template name="app">
  <!-- status update message -->
  <status :type="status.type" :show="status.show">{{ status.message }}</status>

  <h3>{{ i18n.PLUGIN_TITLE }}</h3>

  <!--form that adds a todo when submitted-->
  <form v-on:submit.prevent="addTodo">
    <p>
      <input class="text title" v-model="todo" :placeholder="i18n.TODO_PLACEHOLDER"/>
    </p>
  </form>

  <!--list the (active) todos-->
  <ul class="todos">
    <li v-for="item in todos | active">
      <todo :todo.sync="item"></todo>
    </li>
  </ul>

  <!--loading message (shown only if the todos are loading)-->
  <div class="loading" v-if="loading">
    {{ loading_message }}
  </div>

  <!--form that saves a todo when submitted-->
  <form v-on:submit.prevent="saveTodos">
    <p>
      <input class="submit" type="submit" v-on:click.prevent="saveTodos" :value="i18n.SAVE_TODOS" />
    </p>
  </form>
</template>

<script>
/* global Vue */
define("components/app", [
  "config",
  "utils",
  "components/status",
  "components/todo",
],
function(config, utils, status, todo) {
  // Register a filter for vue so we can list only the todos NOT marked as deleted
  Vue.filter("active", function(todos) {
    return todos.filter(utils.filter.active);
  });

  // Export the component
  return Vue.extend({
    template: utils.template("app"),

    data: function() {
      return {
        i18n: config.i18n,
        status: { show: false, type: "error", message: "" },
        loading: false,
        loading_message: "",
        todo: "",
        todos: [],
      };
    },

    ready: function() {
      // Component has loaded, so load the todos
      this.loadTodos();
    },

    methods: {
      addTodo: function() {
        var desc = this.todo;

        // If there is no message, don't continue
        if (!desc) {
          this.updateStatus("error", this.i18n.TODO_EMPTY_ERR);
          return;
        }

        // Add the new todo to the list
        this.todos.unshift({ desc: desc, edit: false, completed: false });

        // Empty the field
        this.todo = "";

        // Save the todo list!
        this.saveTodos();
      },

      saveTodos: function() {
        // Keep reference to this (when passed to AJAX callbacks)
        var self = this;

        // Preprocess the todos before sending them to the server
        var todos = this.todos
          .filter(utils.filter.active)    // Filter down to active todos (ones not deleted)
          .map(utils.map.beforeSave);     // Ensure they have no extra info sent to the server

        utils.saveTodos(todos, function(data) {
          // Success
          self.updateStatus("updated", self.i18n.SAVE_TODOS_SUCC);
        }, function(err) {
          // Error
          self.updateStatus("error", err.message);
        });
      },

      loadTodos: function() {
        var self = this;
        self.loading = true;
        self.loading_message = self.i18n.TODOS_LOADING;

        utils.getTodos(function(data) {
          // Success; preprocess the todos
          self.todos = data.todos.map(utils.map.beforeLoad);
        }, function(err) {
          // Error
          self.updateStatus("error", err.message);
        }, function() {
          // Finished loading
          self.loading = false;
        });
      },

      updateStatus: function(type, message) {
        this.status = { show: true, type: type, message: message };
      }
    },

    components: { status: status, todo: todo },
  });
});
</script>