/**
 * Review events, functions
 */

function showReviewDialog( obj )
{
    var buttons = 
    [ 
        {   'text': 'Close',
            'class': 'btn-secondary',
            'click': function () 
            {			          
                closePopupDialog( 'process_review' );
            }
        },
        {   'text': 'OK',
            'class': 'btn-primary',
            'click': function ()
		  	{
                obj['popupDialogData'] = $( '#popup-dialog-form' ).serialize();

                resetErrors();
                validateReviewDialog( obj );
            },
        }
    ];

    $( '#' + popupDialogPrefix + 'process_review' )
        .closest( '.ui-dialog' )
        .children( '.ui-dialog-titlebar')
        .css( 'background', 'limegreen' );

    popupDialog( {
	    'id': 'process_review',
        'title': obj.title,
        'html': obj.html,
        'buttons': buttons
    } );
}

function validateReviewDialog( obj )
{
    $.ajax({
		type: "POST",
		url: "/ajax/review.php",
		data: {
				'action': 'validate',
				'popupDialogData': obj.popupDialogData
        },
        dataType: 'json',
		cache: false,
		success: function( data )
		{
            if( data.resp == 'success' ) {
                processReview( obj );
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

function processReview( obj )
{		
    $.ajax({
        type: "POST",
        url: "/ajax/review.php",
        data: obj,
        cache: false,
        success: function( data )
        {                   
            closePopupDialog( 'process_review' );

            sortMovies( { 'action': sort_by } );
        },
        error: function( e, jqxhr )
        {
            alert( 'An error occurred: ' + jqxhr );
        }
    });
}