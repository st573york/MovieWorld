----------------------------------

      MovieWorld Application

----------------------------------


Files and Directories
---------------------

index.php
* Handles user session, login request etc

login.php
* Ability to login user

logout.php
* Handles logout request

registration.php
* Ability to register new user

ajax/process-movie.php
* Handles AJAX requests

css/***
* The style of the interface

dao/***
* Data access objects 

database/create-db.sql
* Database schema

database/db.php
* Database connection management

images/***
* Custom images

js/movie.js
* JS movie functions
 
js/popup-dialog-widget.js
* JS popup dialog library

lib/***
* Libraries to render HTML elements for movie / sort

modules/***
* Page controllers

----------------------------------

How to run the application on a local web server:
* Checkout the src folder
* Open terminal on that folder and run <php -S localhost:9000>
* Run MySQL server
* Connect to MySQL server and run the database/create-db.sql
* Add MySQL username/password in the database/db.php
* Browse to localhost:9000

