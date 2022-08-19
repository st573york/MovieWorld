<?php 

set_include_path( get_include_path() . PATH_SEPARATOR . "../" );

require('database/db.php');

require('dao/MovieReviewDao.php');

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
    $obj = array( 'resp' => 'success', 'error_elems' => array() );

    if( !strlen( trim( $popupDialogData['review'] ) ) ) {
        $obj['error_elems'][] = array( 'elem' => 'review', 'msg' => 'Review is a required field' );
    }

    if( !empty( $obj['error_elems'] ) ) {
        $obj['resp'] = 'error';
    }

    echo json_encode( $obj );
    
    break;
case 'add':
    $movie_review_values = array();
    $movie_review_values['movieid'] = $_POST['movieid'];
    
    foreach( $popupDialogData as $key => $val ) {
        $movie_review_values[ $key ] = $val;
    }        
    
    $movie_review_dao = new MovieReviewDao;
    $movie_review_dao->insert( $movie_review_values );
    
    break;
}
    
?>
