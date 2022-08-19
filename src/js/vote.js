/**
 * Vote events, functions
 */

function processVote( obj )
{		
    $.ajax({
        type: "POST",
        url: "/ajax/vote.php",
        data: obj,
        cache: false,
        success: function( data )
        {                             
            var movie_votes_num = $( $.parseHTML( data ) ).filter( '#movie_votes_num_' + obj.movieid );
            var movie_votes_btn = $( $.parseHTML( data ) ).filter( '#movie_votes_btn_' + obj.movieid );

            // Update movie votes num
            $( 'div#movie_votes_num_' + obj.movieid ).remove();
            $( 'div#movie_' + obj.movieid + ' .movie_num_container' ).prepend( movie_votes_num );

            // Update movie votes btn 
            $( 'div#movie_votes_btn_' + obj.movieid ).remove();    
            $( 'div#movie_' + obj.movieid + ' .movie_btn_container' ).prepend( movie_votes_btn );    
        },
        error: function( e, jqxhr )
        {
            alert( 'An error occurred: ' + jqxhr );
        }
    });
}