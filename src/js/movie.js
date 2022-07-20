/**
 * Movie JS functions
 */

var dialogs = [ 'new_movie', 'confirm' ];
var popupDialogData = '';

function validateMovie()
{
    $.ajax({
		type: "POST",
		url: "/ajax/process-movie.php",
		data: {
				'action': 'validate',
				'popupDialogData': popupDialogData
        },
		dataType: 'json',
		cache: false,
		success: function( data )
		{
			if( data.resp )  {
                addMovie();
            }
            else {
                $( '.popup-dialog-container .error_message' ).html( 'All the fields are required!' );
            }
		}
    });
}

function addMovie()
{
    $.ajax({
		type: "POST",
		url: "/ajax/process-movie.php",
		data: {
			'action': 'add',
            'popupDialogData': popupDialogData
		},
        cache: false,
		dataType: 'json',
		success: function( data )
		{
			if( data.resp ) {
                closePopupDialog( 'new_movie' );
            }

            window.location.reload();
		}
    });
}

function showMovieDialog()
{
    var buttons = 
    [ 
        {   'text': 'OK',
            'click': function ()
		  	{
                popupDialogData = $( '#popup-dialog-form' ).serialize();

                validateMovie();
            },
        },
        {   'text': 'Cancel',
            'click': function () 
            {			          
                closePopupDialog( 'new_movie' );
            }
        }
    ];

    $( '#' + popupDialogPrefix + 'new_movie' )
        .closest( '.ui-dialog' )
        .children( '.ui-dialog-titlebar')
        .css( 'background', '#6495ED' );

    popupDialog( {
	    'id': 'new_movie',
        'title': 'New Movie',
        'buttons': buttons
    } );
}

function resetMovieDialog()
{
    $( '#popup-dialog-form' )[0].reset();
    $( '.popup-dialog-container .error_message' ).html( '' );
}

function confirmMovieDeletion( movieid, title )
{
    var buttons = 
    [ 
        {   'text': 'OK',
            'click': function ()
		  	{
                processMovie( 'delete', movieid );
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

    $( '.popup-dialog-container .confirm_message' ).html( "Movie '" + title + "' will be deleted. Are you sure?" );

    popupDialog( {
		'id': 'confirm',
        'title': 'Delete Movie',
        'buttons': buttons
    } );
}

function processMovie( action, movieid )
{			
    $.ajax({
        type: "POST",
        url: "/ajax/process-movie.php",
        data: {
            'action': action,
            'movieid': movieid        
        },
        dataType: 'json',
        cache: false,
        success: function( data )
        {               
            if( action == 'like' || 
                action == 'hate' )
            {
                // Update vote count           
                $( 'span#like_votes_' + movieid ).html( data.like_votes + ' likes' )
                $( 'span#hate_votes_' + movieid ).html( data.hate_votes + ' hates' )

                if( action == 'like' ) 
                {
                    // Toggle vote count
                    $( 'span#like_votes_' + movieid ).addClass( 'liked' );
                    $( 'span#hate_votes_' + movieid ).removeClass( 'hated' );

                    // Update vote link
                    $( 'span#like_link_' + movieid ).html( '<span class="movie_voted">Like</span>' );
                    $( 'span#hate_link_' + movieid ).html(
                        "<a href='javascript:processMovie( \"hate\", \"" + movieid + "\" )'>Hate</a>"
                    );
                }
                else if( action == 'hate' )
                {
                    // Toggle vote count
                    $( 'span#hate_votes_' + movieid ).addClass( 'hated' );
                    $( 'span#like_votes_' + movieid ).removeClass( 'liked' );

                    // Update vote link
                    $( 'span#hate_link_' + movieid ).html( '<span class="movie_voted">Hate</span>' );
                    $( 'span#like_link_' + movieid ).html(
                        "<a href='javascript:processMovie( \"like\", \"" + movieid + "\" )'>Like</a>"
                    );
                }   
        	}
            else if( action == 'delete' ) 
            {
                if( data.resp ) {
                    closePopupDialog( 'confirm' );
                }
    
                window.location.reload();
            }
        }
    });
}

function sortMovies( action, can_vote )
{			
    $.ajax({
        type: "POST",
        url: "/ajax/process-movie.php",
        data: { 
            'action': action, 
            'can_vote': can_vote 
        },
        dataType: 'html',
        cache: false,
        success: function( data )
        {               
            // Remove movie list 
            $( '.movie_list' ).remove();

            // Add movie list
            if( data ) {
                $( '.movie_container' ).prepend( '<div class="movie_list">' + data + '</div>' ); 
            }
            
            // Update found movies
            $( '.found_movies_count' ).html( $( '.movie_data' ).length );
        }        
    });
}
