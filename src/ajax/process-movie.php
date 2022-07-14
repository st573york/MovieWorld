<?php 

set_include_path( get_include_path() . PATH_SEPARATOR . "../" );

require('database/db.php');

require('lib/Movie.php');

require('dao/MovieDao.php');
require('dao/MovieVoteDao.php');

session_start();

open_db();

$action = $_POST['action'];

$obj = array( 'resp' => true );

$movie_values = array();
$movie_dao = new MovieDao;

switch( $action )
{
case 'add':    
    $movie_values['userid'] = $_SESSION['userid'];

    $popupDialogData = array();
    parse_str( $_POST['popupDialogData'], $popupDialogData );
    
    foreach( $popupDialogData as $key => $val ) {
        $movie_values[ $key ] = $val;
    }        
    
    if( !$movie_dao->insert( $movie_values ) ) {
        $obj['resp'] = false;
    }

    echo json_encode( $obj );

    break;
case 'like':
    $movie_vote_values = array();
    $movie_vote_values['userid'] = $_SESSION['userid'];
    $movie_vote_values['movieid'] = $_POST['movieid'];
    $movie_vote_values['vote_like'] = true;
    
    $movie_vote_dao = new MovieVoteDao;

    // check if movie has got hate voted already
    if( $movie_vote_dao->getVoteHate( $movie_vote_values ) ) {
        // update vote to like        
        $movie_vote_dao->update( $movie_vote_values );
    }
    else {
        // insert like vote
        $movie_vote_dao->insert( $movie_vote_values );
    }

    $movie_values['movieid'] = $_POST['movieid'];
        
    if( !$movie_dao->updateVoteLike( $movie_values ) ) {
        $obj['resp'] = false;
    }

    echo json_encode( $obj );

    break;
case 'hate':
    $movie_vote_values = array();
    $movie_vote_values['userid'] = $_SESSION['userid'];
    $movie_vote_values['movieid'] = $_POST['movieid'];
    $movie_vote_values['vote_hate'] = true;
        
    $movie_vote_dao = new MovieVoteDao;

    // check if movie has got like voted already 
    if( $movie_vote_dao->getVoteLike( $movie_vote_values ) ) {
        // update vote to hate
        $movie_vote_dao->update( $movie_vote_values );
    }
    else {
        // insert hate vote
        $movie_vote_dao->insert( $movie_vote_values );
    }

    $movie_values['movieid'] = $_POST['movieid'];
    
    if( !$movie_dao->updateVoteHate( $movie_values ) ) {
        $obj['resp'] = false;
    }

    echo json_encode( $obj );

    break;
case 'sort_by_likes':
case 'sort_by_hates':
case 'sort_by_dates':
    $movies = array();

    $movie_dao->getAll( $movies, $action );
    
    foreach( $movies as $data )
    {
        $movie = new Movie( $data );
        
        if( $_POST['can_vote'] )
        {
            $movie->setLike();
            $movie->setHate();
        }

        $movie->renderHtml();
    }    

    break;
}
    
?>
