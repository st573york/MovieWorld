<?php 

set_include_path( get_include_path() . PATH_SEPARATOR . "../" );

require('database/db.php');

require('lib/PopupDialog.php');
require('lib/Movie.php');

require('dao/MovieDao.php');
require('dao/MovieVoteDao.php');
require('dao/MovieReviewDao.php');

session_start();

open_db();

$movies = array();
$movie_dao = new MovieDao;
        
$movie_dao->getAll( $movies, $_POST );

foreach( $movies as $data )
{
    $movie = new Movie( $data );
    $movie->renderHtml();
}
    
?>
