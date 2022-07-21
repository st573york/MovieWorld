/**
 * Movie JS functions
 */

var dialogs = [ 'process_movie', 'confirm' ];
var sort_by = 'sort_by_dates';

function validateMovie( obj )
{
    $.ajax({
		type: "POST",
		url: "/ajax/process-movie.php",
		data: {
				'action': 'validate',
				'popupDialogData': obj.popupDialogData
        },
		dataType: 'json',
		cache: false,
		success: function( data )
		{
			if( data.resp ) {
                processMovie( obj );
            }
            else {
                $( '.popup-dialog-container .error_message' ).html( 'All the fields are required!' );
            }
		}
    });
}

function resetMovieDialog()
{
    $( '#popup-dialog-form' )[0].reset();
    $( '.popup-dialog-container .error_message' ).html( '' );
}

function setMovieDialogContent( $movie_content )
{
    $( '.popup-dialog-container #title' ).val( $movie_content.title );
    $( '.popup-dialog-container #description' ).val( $movie_content.description );
}

function showMovieDialog( obj )
{
    var buttons = 
    [ 
        {   'text': 'OK',
            'click': function ()
		  	{
                obj.popupDialogData = $( '#popup-dialog-form' ).serialize();

                validateMovie( obj );
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
        'buttons': buttons
    } );
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

    $( '.popup-dialog-container .confirm_message' ).html( "Movie '" + obj.title + "' will be deleted. Are you sure?" );

    popupDialog( {
		'id': 'confirm',
        'title': 'Delete Movie',
        'buttons': buttons
    } );
}

function processMovie( obj )
{			
    $.ajax({
        type: "POST",
        url: "/ajax/process-movie.php",
        data: obj,
        dataType: 'json',
        cache: false,
        success: function( data )
        {           
            if( data.resp )
            {
                if( obj.action == 'like' || 
                    obj.action == 'hate' )
                {
                    // Update votes           
                    $( 'span#like_votes_' + obj.movieid ).html( data.like_votes + ' likes' )
                    $( 'span#hate_votes_' + obj.movieid ).html( data.hate_votes + ' hates' )

                    if( obj.action == 'like' ) 
                    {
                        // Toggle votes
                        $( 'span#like_votes_' + obj.movieid ).addClass( 'liked' );
                        $( 'span#hate_votes_' + obj.movieid ).removeClass( 'hated' );

                        // Update vote link
                        obj.action = 'hate';
                        $( 'span#like_link_' + obj.movieid ).html( '<span class="movie_voted">Like</span>' );
                        $( 'span#hate_link_' + obj.movieid ).html(
                            "<a href='javascript:processMovie( " + JSON.stringify( obj ) + " )'>Hate</a>"
                        );
                    }
                    else if( obj.action == 'hate' )
                    {
                        // Toggle votes
                        $( 'span#hate_votes_' + obj.movieid ).addClass( 'hated' );
                        $( 'span#like_votes_' + obj.movieid ).removeClass( 'liked' );

                        // Update vote link
                        obj.action = 'like';
                        $( 'span#hate_link_' + obj.movieid ).html( '<span class="movie_voted">Hate</span>' );
                        $( 'span#like_link_' + obj.movieid ).html(
                            "<a href='javascript:processMovie( " + JSON.stringify( obj ) + " )'>Like</a>"
                        );
                    }   
        	    }
                else if( obj.action == 'add' || 
                         obj.action == 'edit' )
                {
                    closePopupDialog( 'process_movie' );

                    sortMovies( sort_by, 1 );
                }
                else if( obj.action == 'delete' ) 
                {
                    closePopupDialog( 'confirm' );

                    sortMovies( sort_by, 1 );
                }
            }
            else {
                alert( 'Something went wrong!' );
            }
        },
        error: function( e, jqxhr )
        {
            alert( 'An error occurred: ' + jqxhr );
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

            sort_by = action;
        }        
    });
}
