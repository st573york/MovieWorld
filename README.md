----------------------------------

      MovieWorld Application

----------------------------------


Files and Directories
---------------------

index.php
* Handles user session, login/register/profile requests etc

ajax/process-movie.php
* Handles AJAX requests

css/***
* The style of the interface

dao/***
* Data access objects 

database/db-create.sql
* Database schema

database/db-data.sql
* Database data

database/db.php
* Database connection management

images/***
* Custom images

js/movie.js
* JS movie events, functions
 
js/popup-dialog-widget.js
* JS popup dialog library

lib/***
* Libraries to render HTML elements

modules/***
* Page controllers

php/***
* Core PHP code

----------------------------------

How to run the application on a local web server:
* Checkout the src folder
* Open terminal on that folder and run <php -S localhost:9000>
* Run MySQL server
* Connect to MySQL server and run the database/db-create.sql and database/db-data.sql
* Add MySQL username/password in the database/db.php
* Browse to localhost:9000

