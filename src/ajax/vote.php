<?php 

set_include_path( get_include_path() . PATH_SEPARATOR . "../" );

require('database/db.php');

require('lib/Dialog.php');
require('lib/Movie.php');

require('dao/MovieDao.php');
require('dao/MovieVoteDao.php');

session_start();

open_db();

$action = $_POST['action'];

$movie_vote_values = array();
$movie_vote_dao = new MovieVoteDao;

switch( $action )
{
case 'like':
    $movie_vote_values['movieid'] = $_POST['movieid'];
    
    // check if user has already voted 
    if( $movie_vote_dao->hasUserVoted( $movie_vote_values ) ) {
        // update vote        
        $movie_vote_dao->updateVoteLike( $movie_vote_values );
    }
    else 
    {
        // insert vote
        $movie_vote_values['vote_like'] = true;
        $movie_vote_dao->insert( $movie_vote_values );
    }
    
    break;
case 'hate':
    $movie_vote_values['movieid'] = $_POST['movieid'];
                
    // check if user has already voted
    if( $movie_vote_dao->hasUserVoted( $movie_vote_values ) ) {
        // update vote
        $movie_vote_dao->updateVoteHate( $movie_vote_values );
    }
    else 
    {
        // insert vote
        $movie_vote_values['vote_hate'] = true;
        $movie_vote_dao->insert( $movie_vote_values );
    }
    
    break;
}

$movie_values = array();
$movie_dao = new MovieDao;

$movie_dao->get( $_POST['movieid'], $movie_values );

$movie = new Movie( $movie_values );
    
$movie->renderVotesNum();
$movie->renderVotesBtn();
    
?>
