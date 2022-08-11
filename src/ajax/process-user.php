<?php 

set_include_path( get_include_path() . PATH_SEPARATOR . "../" );

require('database/db.php');

require('dao/UserDao.php');

session_start();

open_db();

$action = $_POST['action'];

$error_resp = 'ERROR: Something went wrong!';

switch( $action )
{
case 'delete':
    $user_dao = new UserDao;
    echo ( $user_dao->delete( $_SESSION['userid'] ) )? '' : $error_resp;
    
    break;
}
    
?>
