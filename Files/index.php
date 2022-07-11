<?php

require_once('database/db.php');

require_once('dao/UserDao.php');

/*
 * Clear the session vars
 */
function session_clear()
{    
	$_SESSION['userid'] = "";
	$_SESSION['username'] = "";
	$_SESSION['password'] = "";
	$_SESSION['logged_in'] = 0;    
}

/*
 * Initialise a new session
 */
function session_init( $userid, $username, $password )
{        
	// set up session
	$_SESSION['userid'] = $userid;
	$_SESSION['username'] = $username;
	$_SESSION['password'] = $password;
	$_SESSION['logged_in'] = 1;
	$_SESSION['last_activity'] = time();
}

/*
 * Process a login request. 
 */
function login()
{
	if( isset( $_POST['username'] ) )
	{
        $values = array();
        $values['username'] = $_POST['username'];
        $values['password'] = md5( $_POST['password'] );
          
        $user_dao = new UserDao;
        if( $user_dao->get( $values ) )
        {
			// authenticated
			session_init( $_POST['userid'], $_POST['username'], $_POST['password'] );

            header( "Location: modules/user-movies.php" );
		}

		// login failed?
		if( !$_SESSION['logged_in'] )
		{            
			require( 'login.php' );
			exit;
		}
	}
	else
	{
		require( 'login.php' );
		exit;
	}
}

/*
 * Log out the user and show the login page. This function does not return.
 */
function logout()
{
	// destroy session
	$_SESSION = array();
    session_destroy();
    session_start();
    session_regenerate_id( true );

	/* Go back to the login page.*/
	require('login.php');
	exit;
}

/*
 * load/reset session
 */
session_start();

/*
 * (re-)initialise session, as required
 */
if( !isset( $_SESSION['last_activity'] ) ) {
	session_clear();
}

/*
 * open database connections
 */
open_db();

/*
 * ensure we're logged in
 */
if( !isset( $_SESSION['logged_in'] ) ||
    !$_SESSION['logged_in'] )
{
	login();
}

/*
 * check for a logout request (as a special case)
 */
if( $section == 'logout' ) {
	logout();
}

?>
