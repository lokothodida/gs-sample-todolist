<!--todo component-->
<style>
  .todo {
    overflow: hidden;
  }

  .todo .completed {
    text-decoration: line-through;
  }

  .todo .delete {
    float: right;
  }
</style>

<template name="todo">
  <!--if a todo is submitted, toggle away from the edit view-->
  <form class="todo" v-on:submit.prevent="toggleEdit">
    <!--description view (if double-clicked, switches to edit view)-->
    <div class="description" v-on:dblclick="toggleEdit" v-show="!todo.edit">
      <input type="checkbox" :checked="todo.completed" v-on:click="toggleComplete"/>

      <!--strike-through the todo if is completed-->
      <span v-bind:class="{ 'completed': todo.completed }">{{ todo.desc }}</span>
    </div>

    <!--edit view-->
    <div class="edit" v-show="todo.edit">
      <p>
        <input class="text" type="text" v-model="todo.desc">
      </p>
    </div>

    <!--delete button-->
    <a class="delete" v-on:click.prevent="deleteTodo">&times;</a>
  </form>
</template>

<script>
/* global Vue */
define("components/todo", ["config", "utils"], function(config, utils) {
  // Export the component
  return Vue.extend({
    template: utils.template("todo"),

    props: ["todo"],

    data: function() {
      return { i18n: config.i18n };
    },

    methods: {
      toggleComplete: function() {
        this.todo.completed = !this.todo.completed;
      },

      toggleEdit: function() {
        this.todo.edit = !this.todo.edit;
      },

      deleteTodo: function() {
        // Pprompt the user, asking if they're sure they want to delete this todo
        var desc = this.todo.desc;
        var msg  = this.i18n.DELETE_TODO_SURE.replace("%s", desc);
        var sure = confirm(msg);

        if (sure) {
          // Mark todo as deleted
          this.todo.delete = true;
        }
      },
    },
  });
});
</script>