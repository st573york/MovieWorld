<?php 

set_include_path( get_include_path() . PATH_SEPARATOR . "../" );

require('database/db.php');

require('dao/UserDao.php');

session_start();

open_db();

$action = $_POST['action'];

switch( $action )
{
case 'delete':
    $user_dao = new UserDao;
    $user_dao->delete( $_SESSION['userid'] );
    
    break;
}
    
?>
