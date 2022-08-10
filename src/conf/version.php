<?php

function getVersion()
{   
    global $author, $product_name, $product_version;

    $status = @shell_exec( 'svnversion ' . dirname( __DIR__, 1 ) );
    preg_match( '/\d+/', $status, $match );
    $revision = ( !empty( $match ) )? $match[0] : 0;

    return "&copy; $author - $product_name v. {$product_version}.{$revision}";
}

?>