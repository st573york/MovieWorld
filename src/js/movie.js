/**
 * Movie JS functions
 */

var dialogs = [ 'process_movie', 'confirm' ];
var sort_by = 'sort_by_dates';

$( document ).ready( function() {
        
    // Hide items list when clicked outside of div
    $( this ).on( 'click', function( e ) {
        if( $( e.target ).closest( '.items_list' ).length === 0 ) {
            $( '.items_list' ).hide();
        }
    });   

    // Show users who liked a movie
    $( this ).on( 'click', '.movie_likes_text', function( e ) {
        var parts = this.id.split( 'movie_likes_text_' );

        // Hide all users list other than the one clicked
        $( '.items_list' ).not( '#users_like_' + parts[1] ).hide();

        if( $( '#users_like_' + parts[1] ).is( ':visible' ) ) {
            $( '#users_like_' + parts[1] ).hide();
        }
        else {
            $( '#users_like_' + parts[1] ).show();
        }

        e.stopPropagation();
    });   

    // Show users who hated a movie
    $( this ).on( 'click', '.movie_hates_text', function( e ) {
        var parts = this.id.split( 'movie_hates_text_' );

        // Hide all users list other than the one clicked
        $( '.items_list' ).not( '#users_hate_' + parts[1] ).hide();

        if( $( '#users_hate_' + parts[1] ).is( ':visible' ) ) {
            $( '#users_hate_' + parts[1] ).hide();
        }
        else {
            $( '#users_hate_' + parts[1] ).show();
        }
 
        e.stopPropagation();
    });

    // Show users who commented a movie
    $( this ).on( 'click', '.movie_comments_text', function( e ) {
        var parts = this.id.split( 'movie_comments_text_' );

        // Hide all users list other than the one clicked
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

function validateDialog( obj )
{
    $.ajax({
		type: "POST",
		url: "/ajax/process-movie.php",
		data: {
				'action': 'validate',
                'type': obj.type,
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
		}
    });
}

function showDialog( obj )
{
    var buttons = 
    [ 
        {   'text': 'OK',
            'click': function ()
		  	{
                obj.popupDialogData = $( '#popup-dialog-form' ).serialize();

                validateDialog( obj );
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

function confirmDeletion( obj)
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
        'title': obj.title,
        'html': obj.html,
        'buttons': buttons
    } );
}

function processMovie( obj )
{			
    $.ajax({
        type: "POST",
        url: "/ajax/process-movie.php",
        data: obj,
        cache: false,
        success: function( data )
        {                  
            if( data.indexOf( 'ERROR' ) == -1 )
            {
                if( obj.action == 'like' || 
                    obj.action == 'hate' )
                {                    
                    var movie_votes_num = $( $.parseHTML( data ) ).filter( '#movie_votes_num_' + obj.movieid );
                    var movie_votes_btn = $( $.parseHTML( data ) ).filter( '#movie_votes_btn_' + obj.movieid );

                    // Update movie votes num
                    $( 'div#movie_votes_num_' + obj.movieid ).empty().append( movie_votes_num.html() );

                    // Update movie votes btn 
                    $( 'div#movie_votes_btn_' + obj.movieid ).empty().append( movie_votes_btn.html() );
        	    }
                else if( obj.action == 'comment' || 
                         obj.action == 'add' || 
                         obj.action == 'edit' )
                {
                    closePopupDialog( 'process_movie' );

                    sortMovies( sort_by );
                }
                else if( obj.action == 'delete' ) 
                {
                    closePopupDialog( 'confirm' );

                    sortMovies( sort_by );
                }
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

function sortMovies( action )
{			
    $.ajax({
        type: "POST",
        url: "/ajax/process-movie.php",
        data: { 'action': action },
        cache: false,
        success: function( data )
        {               
            // Update movies
            if( data ) {
                $( '.movie_list' ).empty().append( data ); 
            }
            
            // Update found movies
            $( '.found_movies_count' ).html( $( '.movie_data' ).length );

            sort_by = action;
        }        
    });
}
