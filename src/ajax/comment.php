<?php 

set_include_path( get_include_path() . PATH_SEPARATOR . "../" );

require('database/db.php');

require('dao/MovieCommentDao.php');

session_start();

open_db();

$action = $_POST['action'];
if( isset( $_POST['popupDialogData'] ) )
{
    $popupDialogData = array();
    parse_str( $_POST['popupDialogData'], $popupDialogData );
}

switch( $action )
{
case 'validate':
   echo ( strlen( trim( $popupDialogData['comment'] ) ) )? '' : 'ERROR';
    
    break;
case 'add':
    $movie_comment_values = array();
    $movie_comment_values['movieid'] = $_POST['movieid'];
    
    foreach( $popupDialogData as $key => $val ) {
        $movie_comment_values[ $key ] = $val;
    }        
    
    $movie_comment_dao = new MovieCommentDao;
    $movie_comment_dao->insert( $movie_comment_values );
    
    break;
}
    
?>
