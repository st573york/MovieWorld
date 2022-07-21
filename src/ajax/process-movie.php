<?php 

set_include_path( get_include_path() . PATH_SEPARATOR . "../" );

require('database/db.php');

require('lib/Movie.php');

require('dao/MovieDao.php');
require('dao/MovieVoteDao.php');

session_start();

open_db();

$action = $_POST['action'];
if( isset( $_POST['popupDialogData'] ) )
{
    $popupDialogData = array();
    parse_str( $_POST['popupDialogData'], $popupDialogData );
}

$obj = array( 'resp' => true );

$movie_dao = new MovieDao;

switch( $action )
{
case 'validate':
    $obj['resp'] = ( strlen( trim( $popupDialogData['title'] ) ) && 
                     strlen( trim( $popupDialogData['description'] ) ) );

    echo json_encode( $obj );

    break;
case 'add':    
    if( !$movie_dao->insert( $popupDialogData ) ) {
        $obj['resp'] = false;
    }

    echo json_encode( $obj );

    break;
case 'edit':  
    $movie_values = array();
    $movie_values['movieid'] = $_POST['movieid'];

    foreach( $popupDialogData as $key => $val ) {
        $movie_values[ $key ] = $val;
    }        

    if( !$movie_dao->update( $movie_values ) ) {
        $obj['resp'] = false;
    }
    
    echo json_encode( $obj );
    
    break;
case 'delete':
    if( !$movie_dao->delete( $_POST['movieid'] ) ) {
        $obj['resp'] = false;
    }
    
    echo json_encode( $obj );
    
    break;
case 'like':
    $movie_values = array();
    $movie_values['movieid'] = $_POST['movieid'];

    $movie_vote_values = array();
    $movie_vote_values['movieid'] = $_POST['movieid'];
    $movie_vote_values['vote_like'] = true;
    
    $movie_vote_dao = new MovieVoteDao;

    // check if movie has got hate voted already
    if( $movie_vote_dao->getVoteHate( $movie_vote_values ) ) 
    {
        // update vote to like        
        $movie_vote_dao->update( $movie_vote_values );

        // toggle total like vote
        $movie_dao->toggleVoteLike( $movie_values );
    }
    else 
    {
        // insert like vote
        $movie_vote_dao->insert( $movie_vote_values );

        // update total like votes
        $movie_dao->updateVoteLike( $movie_values );
    }

    $movie_values = array();
    $movie_dao->get( $_POST['movieid'], $movie_values );

    $obj['like_votes'] = $movie_values['number_of_likes'];
    $obj['hate_votes'] = $movie_values['number_of_hates'];

    echo json_encode( $obj );

    break;
case 'hate':
    $movie_values = array();
    $movie_values['movieid'] = $_POST['movieid'];

    $movie_vote_values = array();
    $movie_vote_values['movieid'] = $_POST['movieid'];
    $movie_vote_values['vote_hate'] = true;
        
    $movie_vote_dao = new MovieVoteDao;

    // check if movie has got like voted already 
    if( $movie_vote_dao->getVoteLike( $movie_vote_values ) ) 
    {
        // update vote to hate
        $movie_vote_dao->update( $movie_vote_values );

        // toggle total hate vote
        $movie_dao->toggleVoteHate( $movie_values );
    }
    else 
    {
        // insert hate vote
        $movie_vote_dao->insert( $movie_vote_values );

        // update total hate votes
        $movie_dao->updateVoteHate( $movie_values );
    }
   
    $movie_values = array();
    $movie_dao->get( $_POST['movieid'], $movie_values );

    $obj['like_votes'] = $movie_values['number_of_likes'];
    $obj['hate_votes'] = $movie_values['number_of_hates'];

    echo json_encode( $obj );

    break;
case 'sort_by_user':
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
