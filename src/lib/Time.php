<?php

function get_time_ago( $time )
{
    $diff = time() - $time;
        
    if( $diff < 1 ) { 
        return 'less than 1 second ago'; 
    }
        
    $time_rules = array( 12 * 30 * 24 * 60 * 60 =>  'year',
                         30 * 24 * 60 * 60      =>  'month',
                         24 * 60 * 60           =>  'day',
                         60 * 60                =>  'hour',
                         60                     =>  'minute',
                         1                      =>  'second'
    );

    foreach( $time_rules as $secs => $str )
    {
        $d = $diff / $secs;

        if( $d >= 1 )
        {   
            $t = round( $d );
            return 'about ' . $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
        }
    }
}

?>