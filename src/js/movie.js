function voteMovie( action, movieid )
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
            // Update vote number
            $( 'span#like_votes_' + movieid ).html( data.like_votes + ' likes' );
            $( 'span#hate_votes_' + movieid ).html( data.hate_votes + ' hates' );

            // Toggle vote link
            if( action == 'like' ) 
            {
                $( 'span#like_link_' + movieid ).html( 'Like' );
                $( 'span#hate_link_' + movieid ).html(
                    "<a href='javascript:voteMovie( \"hate\", \"" + movieid + "\" )'>Hate</a>"
                );
            }
            else if( action == 'hate' )
            {
                $( 'span#hate_link_' + movieid ).html( 'Hate' );
                $( 'span#like_link_' + movieid ).html(
                    "<a href='javascript:voteMovie( \"like\", \"" + movieid + "\" )'>Like</a>"
                );
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
            // Update movie list
            $( '.movie_list' ).empty();
            $( '.movie_list' ).append( data );               
        }        
    });
}
