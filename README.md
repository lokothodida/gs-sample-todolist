# GetSimple Sample Todo-List
A sample Todo-list plugin for GetSimple, demonstrating how to:

* Create a simple RESTful API for your plugin
* Share your PHP and JavaScript variables sensibly
* Use web components (through [VueJS](https://vuejs.org/)) to structure the view of your admin panel

# Installation
1. Clone the repository
2. Copy the contents (or symbolically link them) to your `/plugins` folder.
3. Enable the plugin from your Administation Panel.

# Development
All the plugin's files (except plugin registration) are kept in `/todolist`.

* PHP scripts/classes --> `/todolist` (root)
* Language files (PHP) --> `todolist/lang`.
* JavaScript --> `/todolist/js`
* Stylesheets --> `/todolist/css`
* Web components (in this case, VueJS components) --> `/todolist/components`.

Check out the [wiki](wiki) for a full tutorialized breakdown, and look at the source
code for the working, fully commented plugin.

# Contributing
Language translations are welcome.