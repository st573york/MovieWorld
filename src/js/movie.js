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

function sortMovies( action )
{			
    $.ajax({
        type: "POST",
        url: "/ajax/process-movie.php",
        data: { 'action': action },
        dataType: 'html',
        cache: false,
        success: function( data )
        {                 
            $( '.movie_list' ).empty();
            $( '.movie_list' ).append( data );               
        }        
    });
}