<?php 

require('database/db.php');

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

    break;
case 'like':
    $movie_vote_values = array();
    $movie_vote_values['userid'] = $_SESSION['userid'];
    $movie_vote_values['movieid'] = $_POST['movieid'];
    $movie_vote_values['like'] = true;
    
    $movie_vote_dao = new MovieVoteDao;

    // check if movie has got hate voted already
    if( $movie_vote_dao->getVoteHate( $movie_vote_values ) )
    {
        // decrement number of hates
        $movie_values['hate'] = false;

        // update vote to like        
        $movie_vote_dao->update( $movie_vote_values );
    }
    else {
        // insert like vote
        $movie_vote_dao->insert( $movie_vote_values );
    }
    
    $movie_values['like'] = true;
    $movie_values['movieid'] = $_POST['movieid'];
        
    if( !$movie_dao->update( $movie_values ) ) {
        $obj['resp'] = false;
    }

    break;
case 'hate':
    $movie_vote_values = array();
    $movie_vote_values['userid'] = $_SESSION['userid'];
    $movie_vote_values['movieid'] = $_POST['movieid'];
    $movie_vote_values['hate'] = true;
        
    $movie_vote_dao = new MovieVoteDao;

    // check if movie has got like voted already 
    if( $movie_vote_dao->get( $movie_vote_values ) )
    {
        // decrement number of likes
        $movie_values['like'] = false;

        // update vote to hate
        $movie_vote_dao->update( $movie_vote_values );
    }
    else {
        // insert hate vote
        $movie_vote_dao->insert( $movie_vote_values );
    }
    
    $movie_values['hate'] = true;
    $movie_values['movieid'] = $_POST['movieid'];
    
    if( !$movie_dao->update( $movie_values ) ) {
        $obj['resp'] = false;
    }

    break;
case 'sort_by_likes':
case 'sort_by_hates':
case 'sort_by_dates':
    $movie_values['action'] = $action;
    
    if( $movie_dao->getByOrder( $movie_values ) ) {
        $obj['resp_data'] = $movie_values;
    }
    
    break;
}

echo json_encode( $obj );
    
?>
