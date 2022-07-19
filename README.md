----------------------------------

      MovieWorld Application

----------------------------------


Files and Directories
---------------------

index.php
* Handles users session, login requests etc

login.php
* Login page

logout.php
* Handles logout request

registration.php
* Handles new users

ajax/process-movie.php
* Handles AJAX requests

css/main.css
css/movie.css
css/popup-dialog.css
* The style of the interface

dao/MovieDao.php
dao/MovieVoteDao.php
dao/UserDao.php
* Data access objects 

database/create-db.sql
* Database schema

database/db.php
* Database connection management

js/movie.js
* Javascript functions to vote / sort movie
 
js/popup-dialog-widget.js
* Javascript library to open pop up dialog

lib/Movie.php
lib/MovieSort.php
* Libraries to render HTML elements for movie / sort

modules/user-movies.php
* Page controller

----------------------------------

How to run the application on a local php server:
* Checkout the src folder
* Open terminal on that folder and run <php -S localhost:9000>
* Run mysql server
* Connect to mysql server and run the create-db.sql
* Add mysql username/password in database/db.php
* Browse to localhost:9000

