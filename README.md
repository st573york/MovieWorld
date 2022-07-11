##################################

      MovieWorld Application

##################################


FILES
#####

.htaccess
	- Route all requests to index.php

index.php
	- Handles user's $_SESSION, login, logout requests etc

login.php
	- Displays login page with ability to register new user
	- Displays list of all movies

registration.php
	- Ability to register new user, it uses md5 hash for password
	  - The password encryption/hashing could be improved by using a nonce (sent to the client as a challenge) and server's public private keypair
	    but that would require more work :)

ajax/process-movie.php
	- Ability to add, like, hate and sort movies on server side

dao/MovieDao.php
dao/MovieVoteDao.php
dao/UserDao.php
	- Ability to run sql queries to fetch, insert, update data 

js/popup-dialog-widget.js
	- Opens pop up dialog with New Movie Form submitted via AJAX request

modules/user-movies.php
	- Displays list of all movies
	- Ability to like/hate via AJAX request (only when movie has not been submitted by logged in user)
	- Ability to add New Movie
	- Ability to sort by likes/hates/dates

sql/create-db.sql
	- Database structure

sql/db.php
	- Database connection management
