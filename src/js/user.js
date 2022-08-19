/**
 * User events, functions
 */

function confirmUserDeletion( obj)
{
    var buttons = 
    [ 
        {   'text': 'Close',
            'class': 'btn-secondary',
            'click': function () 
            {			          
                closePopupDialog( 'confirm' );
            }
        },
        {   'text': 'OK',
            'class': 'btn-primary',
            'click': function ()
            {
                processUser( obj );
            },
        }
    ];
 
    $( '#' + popupDialogPrefix + 'confirm' )
        .closest( '.ui-dialog' )
        .children( '.ui-dialog-titlebar')
        .css( 'background', '#d92' );
 
    popupDialog( {
        'id': 'confirm',
        'title': 'Delete User',
        'html': obj.html,
        'buttons': buttons
    } );
}

function processUser( obj )
{		
    $.ajax({
        type: "POST",
        url: "/ajax/user.php",
        data: obj,
        cache: false,
        success: function( data )
        {                              
            closePopupDialog( 'confirm' );

            window.location = 'php/logout.php';
        },
        error: function( e, jqxhr )
        {
            alert( 'An error occurred: ' + jqxhr );
        }
    });
}
