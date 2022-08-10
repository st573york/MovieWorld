<?php

require_once('conf/config.php');
require_once('conf/version.php');

require_once('database/db.php');

require_once('dao/UserDao.php');

/*
 * clear the session vars
 */
function session_clear()
{    
	$_SESSION['userid'] = "";
	$_SESSION['username'] = "";
	$_SESSION['password'] = "";
	$_SESSION['logged_in'] = 0;    
}

/*
 * initialise a new session
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
 * process a login request
 */
function login()
{
	if( isset( $_POST['username'] ) )
	{
        $values = array();
        $values['username'] = $_POST['username'];
        $values['password'] = md5( $_POST['password'] );
         
        $user_dao = new UserDao;
		
        if( $user_dao->get( $values ) ) {
			// authenticated
			session_init( $values['userid'], $_POST['username'], $_POST['password'] );
		}

		// login failed?
		if( !$_SESSION['logged_in'] )
		{            
			require( 'php/login.php' );

			$login = new Login( 1 );
			$login->renderHtml();

			exit;
		}
	}
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
 * login request?
 */
if( isset( $_GET['login'] )  )
{
	require( 'php/login.php' );

	$login = new Login( 0 );
	$login->renderHtml();

	exit;
}

/*
 * register request?
 */
if( isset( $_GET['register'] )  )
{
	require( 'php/registration.php' );

	$registration = new Registration;
	$registration->renderHtml();

	exit;
}

/*
 * profile request?
 */
if( isset( $_GET['profile'] )  )
{
	require( 'php/profile.php' );

	$profile = new Profile;
	$profile->renderHtml();

	exit;
}

/*
 * ensure we're logged in
 */
if( !isset( $_SESSION['logged_in'] ) ||
    !$_SESSION['logged_in'] )
{
	login();
}

require_once( 'modules/movies.php' );

$movies = new Movies;
$movies->renderHtml();

?>
