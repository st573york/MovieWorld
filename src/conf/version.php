<?php

function getRevision()
{
    $status = @shell_exec( 'svnversion ' . dirname( __DIR__, 1 ) );
    if( preg_match( '/(\d+)M/', $status, $matches ) ) {
        return $matches[1];
    }

    return 0;
}

function getVersion()
{   
    global $author, $product_name, $product_version;
    
    return "&copy; $author - $product_name v. {$product_version}." . getRevision();
}

?>