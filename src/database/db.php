<?php

/* GLOBAL VARIABLE
 */
$conn = null;

function open_db()
{
    global $conn;

    $servername = "localhost";
    $username = "root";
    $password = "Milanezos7";
    $dbname = "MovieWorld";
    
    if( !$conn ) {
        $conn = new PDO( "mysql:host=$servername; dbname=$dbname", $username, $password );
    }
}

?>
