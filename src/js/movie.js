/**
 * Movie events, functions
 */

function showMovieDialog( obj )
{
    var buttons = 
    [ 
        {   'text': 'OK',
            'click': function ()
		  	{
                obj['popupDialogData'] = $( '#popup-dialog-form' ).serialize();

                validateMovieDialog( obj );
            },
        },
        {   'text': 'Cancel',
            'click': function () 
            {			          
                closePopupDialog( 'process_movie' );
            }
        }
    ];

    $( '#' + popupDialogPrefix + 'process_movie' )
        .closest( '.ui-dialog' )
        .children( '.ui-dialog-titlebar')
        .css( 'background', 'limegreen' );

    popupDialog( {
	    'id': 'process_movie',
        'title': obj.title,
        'html': obj.html,
        'buttons': buttons
    } );
}

function validateMovieDialog( obj )
{
    $.ajax({
		type: "POST",
		url: "/ajax/movie.php",
		data: {
				'action': 'validate',
				'popupDialogData': obj.popupDialogData
        },
		cache: false,
		success: function( data )
		{
			if( data.indexOf( 'ERROR' ) == -1 ) {
                processMovie( obj );
            }
            else {
                $( '.popup-dialog-container .error_message' ).html( 'All the fields are required!' );
            }
		},
        error: function( e, jqxhr )
        {
            alert( 'An error occurred: ' + jqxhr );
        }
    });
}

function confirmMovieDeletion( obj)
{
    var buttons = 
    [ 
        {   'text': 'OK',
            'click': function ()
		  	{    
                processMovie( obj );
            },
        },
        {   'text': 'Cancel',
            'click': function () 
            {			          
                closePopupDialog( 'confirm' );
            }
        }
    ];

    $( '#' + popupDialogPrefix + 'confirm' )
        .closest( '.ui-dialog' )
        .children( '.ui-dialog-titlebar')
        .css( 'background', '#d92' );

    popupDialog( {
		'id': 'confirm',
        'title': 'Delete Movie',
        'html': obj.html,
        'buttons': buttons
    } );
}

function processMovie( obj )
{		
    $.ajax({
        type: "POST",
        url: "/ajax/movie.php",
        data: obj,
        cache: false,
        success: function( data )
        {                              
            if( obj.action == 'add' || 
                obj.action == 'edit' )
            {
                closePopupDialog( 'process_movie' );
            }
            else if( obj.action == 'delete' ) {
                closePopupDialog( 'confirm' );                    
            }

            sortMovies( { 'action': sort_by } );
        },
        error: function( e, jqxhr )
        {
            alert( 'An error occurred: ' + jqxhr );
        }
    });
}
