<?php 

set_include_path( get_include_path() . PATH_SEPARATOR . "../" );

require('database/db.php');

require('lib/Dialog.php');
require('lib/Movie.php');

require('dao/MovieDao.php');
require('dao/MovieVoteDao.php');
require('dao/MovieCommentDao.php');

session_start();

open_db();

$action = $_POST['action'];
if( isset( $_POST['popupDialogData'] ) )
{
    $popupDialogData = array();
    parse_str( $_POST['popupDialogData'], $popupDialogData );
}

$error_resp = 'ERROR: Something went wrong!';

switch( $action )
{
case 'validate':
    if( $_POST['type'] == 'comment' ) {
        echo ( strlen( trim( $popupDialogData['comment'] ) ) )? '' : $error_resp;
    }
    else if( $_POST['type'] == 'movie' )
    {
        echo ( strlen( trim( $popupDialogData['title'] ) ) && 
               strlen( trim( $popupDialogData['description'] ) ) )? '' : $error_resp;
    }

    break;
case 'comment':
    $movie_comment_values = array();
    $movie_comment_values['movieid'] = $_POST['movieid'];

    foreach( $popupDialogData as $key => $val ) {
        $movie_comment_values[ $key ] = $val;
    }        

    $movie_comment_dao = new MovieCommentDao;
    echo ( $movie_comment_dao->insert( $movie_comment_values ) )? '' : $error_resp;

    break;
case 'add':   
    $movie_dao = new MovieDao; 
    echo ( $movie_dao->insert( $popupDialogData ) )? '' : $error_resp;

    break;
case 'edit':  
    $movie_values = array();
    $movie_values['movieid'] = $_POST['movieid'];

    foreach( $popupDialogData as $key => $val ) {
        $movie_values[ $key ] = $val;
    }        

    $movie_dao = new MovieDao;
    echo ( $movie_dao->update( $movie_values ) )? '' : $error_resp;
    
    break;
case 'delete':
    $movie_dao = new MovieDao;
    echo ( $movie_dao->delete( $_POST['movieid'] ) )? '' : $error_resp;
    
    break;
case 'like':
    $movie_vote_values = array();
    $movie_vote_values['movieid'] = $_POST['movieid'];
    
    $movie_vote_dao = new MovieVoteDao;

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

    $movie_values = array();
    $movie_dao = new MovieDao;

    $movie_dao->get( $_POST['movieid'], $movie_values );

    $movie = new Movie( $movie_values );

    $movie->renderVotesNum();
    $movie->renderVotesBtn();

    break;
case 'hate':
    $movie_vote_values = array();
    $movie_vote_values['movieid'] = $_POST['movieid'];
        
    $movie_vote_dao = new MovieVoteDao;

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
   
    $movie_values = array();
    $movie_dao = new MovieDao;

    $movie_dao->get( $_POST['movieid'], $movie_values );

    $movie = new Movie( $movie_values );

    $movie->renderVotesNum();
    $movie->renderVotesBtn();

    break;
case 'sort_by_text':
case 'sort_by_title':
case 'sort_by_likes':
case 'sort_by_hates':
case 'sort_by_comments':
case 'sort_by_author':
case 'sort_by_date_most_recent':
case 'sort_by_date_oldest':
    $movies = array();
    $movie_dao = new MovieDao;

    $movie_dao->getAll( $movies, $_POST );
    
    foreach( $movies as $data )
    {
        $movie = new Movie( $data );
        $movie->renderHtml();
    }

    break;
}
    
?>
