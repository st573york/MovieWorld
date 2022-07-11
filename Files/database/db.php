<?php

/* GLOBAL VARIABLE
 */
$conn = null;

function open_db()
{
    global $conn;

    if( !$conn )
    {
        $conn = mysqli_connect( "localhost", "user", "password", "db" );

        // Check connection
        if( mysqli_connect_errno() ) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    }
}

?>