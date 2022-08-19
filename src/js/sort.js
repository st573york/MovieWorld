/**
 * Sort events, functions
 */

 var sort_by = 'sort_by_date_most_recent';

 $( document ).ready( function() {
 
    // Highlight default date most recent sort by option
    $( '.movie_actions_panel .dropdown-menu' ).find( 'li#date_most_recent' ).addClass( 'active' );

    // Highlight sort by option
    $( '.movie_actions_panel .dropdown-menu li' ).click( function() {
        if( this.id != 'sort_by' )
        {
            $( this ).parent().find( 'li' ).removeClass( 'active' );
            $( this ).addClass( 'active' );
        }
    });

} );

 function sortMovies( obj )
{		    	
    $.ajax({
        type: "POST",
        url: "/ajax/sort.php",
        data: obj,
        cache: false,
        success: function( data )
        {          
            // Update movies
            $( '.movie_content' ).remove();
            if( data ) {
                $( '.movie_container' ).append( data );
            }
            
            // Update found movies
            $( '.found_movies_count' ).html( $( '.movie_data' ).length );

            sort_by = obj.action;
        },
        error: function( e, jqxhr )
        {
            alert( 'An error occurred: ' + jqxhr );
        }
    });
}