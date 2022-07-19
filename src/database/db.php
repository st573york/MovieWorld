<?php

/* GLOBAL VARIABLE
 */
$conn = null;

function open_db()
{
    global $conn;

    $servername = "localhost";
    $username = "";
    $password = "";
    $dbname = "MovieWorld";
    
    if( !$conn ) {
        $conn = new PDO( "mysql:host=$servername; dbname=$dbname", $username, $password );
    }
}

?>
