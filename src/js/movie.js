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
                $( 'div#movie_' + movieid ).remove();

                // Update found movies
                $( '.found_movies_count' ).html( $( '.movie_data' ).length );
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
