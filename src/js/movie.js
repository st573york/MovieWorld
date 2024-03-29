/**
 * Movie events, functions
 */

function showMovieDialog( obj )
{
    var buttons = 
    [ 
        {   'text': 'Close',
            'class': 'btn-secondary',
            'click': function () 
            {			          
                closePopupDialog( 'process_movie' );
            }
        },
        {   'text': 'OK',
            'class': 'btn-primary',
            'click': function ()
		  	{
                obj['popupDialogData'] = $( '#popup-dialog-form' ).serialize();

                resetErrors();
                validateMovieDialog( obj );
            },
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
        dataType: 'json',
		cache: false,
		success: function( data )
		{
			if( data.resp == 'success' ) {
                processMovie( obj );
            }
            else 
            {
                data.error_elems.forEach( item => {
                    $( '.popup-dialog-container #' + item.elem ).addClass( 'invalid' ).parent().next( '.invalid_message' ).html( item.msg );
                } );
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
                processMovie( obj );
            },
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
