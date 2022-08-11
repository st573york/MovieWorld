/**
 * User events, functions
 */

function processUser( obj )
{		
    $.ajax({
        type: "POST",
        url: "/ajax/process-user.php",
        data: obj,
        cache: false,
        success: function( data )
        {                              
            if( data.indexOf( 'ERROR' ) == -1 )
            {
                closePopupDialog( 'confirm' );

                window.location = 'php/logout.php';
            }
            else {
                alert( data );
            }
        },
        error: function( e, jqxhr )
        {
            alert( 'An error occurred: ' + jqxhr );
        }
    });
}
