# GetSimple Sample Todo-List
A sample Todo-list plugin for [GetSimple](http://get-simple.info/), demonstrating how to:

* Create a simple RESTful API for your plugin
* Share your PHP and JavaScript variables sensibly
* Use web components (through [VueJS](https://vuejs.org/)) to structure the view of your admin panel

![gs-sample-todolist](https://cloud.githubusercontent.com/assets/4363863/17894444/4aa06072-6941-11e6-8d75-297786b74d1f.png)

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

Check out the [wiki](https://github.com/lokothodida/gs-sample-todolist/wiki) for a full tutorialized breakdown, and look at the source
code for the working, fully commented plugin.

# Contributing
Welcome are:

* Language translations
* Additions/alterations to the existing tutorial(s)
* Tutorials that show how to incorporate other web components libraries/frameworks

# License
This sample plugin is licensed under [MIT](http://www.opensource.org/licenses/MIT).

# Copyright
All rights reserved.