<?php 

set_include_path( get_include_path() . PATH_SEPARATOR . "../" );

require('database/db.php');

require('dao/MovieDao.php');

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

    if( !strlen( trim( $popupDialogData['title'] ) ) ) {
        $obj['error_elems'][] = array( 'elem' => 'title', 'msg' => 'Title is a required field' );
    }

    if( !strlen( trim( $popupDialogData['description'] ) ) ) {
        $obj['error_elems'][] = array( 'elem' => 'description', 'msg' => 'Description is a required field' );
    }

    if( !empty( $obj['error_elems'] ) ) {
        $obj['resp'] = 'error';
    }

    echo json_encode( $obj );

    break;
case 'add':   
    $movie_dao = new MovieDao; 
    $movie_dao->insert( $popupDialogData );

    break;
case 'edit':  
    $movie_values = array();
    $movie_values['movieid'] = $_POST['movieid'];

    foreach( $popupDialogData as $key => $val ) {
        $movie_values[ $key ] = $val;
    }        

    $movie_dao = new MovieDao;
    $movie_dao->update( $movie_values );
    
    break;
case 'delete':
    $movie_dao = new MovieDao;
    $movie_dao->delete( $_POST['movieid'] );
    
    break;
}
    
?>
