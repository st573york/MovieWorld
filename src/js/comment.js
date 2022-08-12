/**
 * Comment events, functions
 */

 $( document ).ready( function() { 

    // Hide comments list when clicked outside of div
    $( this ).on( 'click', function( e ) {
        if( $( e.target ).closest( '.comments_list' ).length === 0 ) {
            $( '.comments_list' ).hide();
        }
    });   

    // Show comments list
    $( this ).on( 'click', '.movie_comments_text', function( e ) {
        var parts = this.id.split( 'movie_comments_text_' );

        // Hide all lists other than the one clicked
        $( '.items_list' ).not( '#comments_' + parts[1] ).hide();

        if( $( '#comments_' + parts[1] ).is( ':visible' ) ) {
            $( '#comments_' + parts[1] ).hide();
        }
        else {
            $( '#comments_' + parts[1] ).show();
        }
 
        e.stopPropagation();
    });

} );

function showCommentDialog( obj )
{
    var buttons = 
    [ 
        {   'text': 'OK',
            'click': function ()
		  	{
                obj['popupDialogData'] = $( '#popup-dialog-form' ).serialize();

                validateCommentDialog( obj );
            },
        },
        {   'text': 'Cancel',
            'click': function () 
            {			          
                closePopupDialog( 'process_comment' );
            }
        }
    ];

    $( '#' + popupDialogPrefix + 'process_comment' )
        .closest( '.ui-dialog' )
        .children( '.ui-dialog-titlebar')
        .css( 'background', 'limegreen' );

    popupDialog( {
	    'id': 'process_comment',
        'title': obj.title,
        'html': obj.html,
        'buttons': buttons
    } );
}

function validateCommentDialog( obj )
{
    $.ajax({
		type: "POST",
		url: "/ajax/comment.php",
		data: {
				'action': 'validate',
				'popupDialogData': obj.popupDialogData
        },
		cache: false,
		success: function( data )
		{
			if( data.indexOf( 'ERROR' ) == -1 ) {
                processComment( obj );
            }
            else {
                $( '.popup-dialog-container .error_message' ).html( 'Comment field is required!' );
            }
		},
        error: function( e, jqxhr )
        {
            alert( 'An error occurred: ' + jqxhr );
        }
    });
}

function processComment( obj )
{		
    $.ajax({
        type: "POST",
        url: "/ajax/comment.php",
        data: obj,
        cache: false,
        success: function( data )
        {                   
            closePopupDialog( 'process_comment' );

            sortMovies( { 'action': sort_by } );
        },
        error: function( e, jqxhr )
        {
            alert( 'An error occurred: ' + jqxhr );
        }
    });
}