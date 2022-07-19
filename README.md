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

js/movie.js
* JS functions to process (vote, delete) and sort movies
 
js/popup-dialog-widget.js
* JS library to open pop up dialog

lib/***
* Libraries to render HTML elements for movie / sort

modules/***
* Page controllers

----------------------------------

How to run the application on a local php server:
* Checkout the src folder
* Open terminal on that folder and run <php -S localhost:9000>
* Run mysql server
* Connect to mysql server and run the database/create-db.sql
* Add mysql username/password in database/db.php
* Browse to localhost:9000

