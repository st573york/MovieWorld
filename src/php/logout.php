<?php

session_start();

// destroy session
$_SESSION = array();
session_destroy();
session_start();
session_regenerate_id( true );

// go back to the login page
header( "Location: /" );

?>