##################################

      MovieWorld Application

##################################


FILES
#####

index.php
* Handles users session, login, logout requests etc

login.php
* Displays login page with ability to register new user
* Displays list of all movies with ability to sort them

registration.php
* Ability to register new user, it uses md5 hash for password

ajax/process-movie.php
* Ability to add, like, hate and sort movies via AJAX

css/main.css
css/movie.css
css/popup-dialog.css
* Apply custom css

dao/MovieDao.php
dao/MovieVoteDao.php
dao/UserDao.php
* Ability to run sql queries to fetch, insert, update data 

database/create-db.sql
* Database structure

database/db.php
* Database connection management

js/movie.js
* JS functions to vote / sort movie
 
js/popup-dialog-widget.js
* JS library to open pop up dialog

lib/Movie.php
lib/MovieSort.php
* Renders HTML elements for movie / sort

modules/user-movies.php
* Displays logged in user with the ability to logout
* Displays list of all movies
* Ability to like/hate (only when movie has not been submitted by logged in user)
* Ability to add New Movie
* Ability to sort by likes/hates/dates


