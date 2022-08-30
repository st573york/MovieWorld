----------------------------------

      MovieWorld Application

----------------------------------


Files and Directories
---------------------

index.php
* Handles user session, login/register/profile/movie requests etc

ajax/***
* AJAX pages

conf/***
* Configuration

css/***
* The style of the interface

dao/***
* Data access objects 

database/***
* Database schema, data, connection management

images/***
* Custom images

js/***
* JS movie events, functions, libraries

lib/***
* Libraries to render HTML elements

modules/***
* Page controllers

php/***
* Core PHP code

----------------------------------

How to run the application on macOS:

Pull image from docker hub: 
* docker pull st573york/movieworld 

Run image in a new container:
* docker run -itd --privileged -p 80:80 st573york/movieworld:latest /usr/sbin/init

Browse to localhost

