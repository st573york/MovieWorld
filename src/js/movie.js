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
            window.location.reload();
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
            'can_vote': can_vote },
        dataType: 'html',
        cache: false,
        success: function( data )
        {                 
            $( '.movie_list' ).empty();
            $( '.movie_list' ).append( data );               
        }        
    });
}