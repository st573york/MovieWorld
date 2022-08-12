/**
 * Vote events, functions
 */

 $( document ).ready( function() { 

    // Hide users list when clicked outside of div
    $( this ).on( 'click', function( e ) {
        if( $( e.target ).closest( '.users_list' ).length === 0 ) {
            $( '.users_list' ).hide();
        }
    });   

    // Show users who liked a movie
    $( this ).on( 'click', '.movie_likes_text', function( e ) {
        var parts = this.id.split( 'movie_likes_text_' );

        // Hide all lists other than the one clicked
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

        // Hide all lists other than the one clicked
        $( '.items_list' ).not( '#users_hate_' + parts[1] ).hide();

        if( $( '#users_hate_' + parts[1] ).is( ':visible' ) ) {
            $( '#users_hate_' + parts[1] ).hide();
        }
        else {
            $( '#users_hate_' + parts[1] ).show();
        }
 
        e.stopPropagation();
    });

} );

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
            $( 'div#movie_votes_num_' + obj.movieid ).empty().append( movie_votes_num );

            // Update movie votes btn 
            $( 'div#movie_votes_btn_' + obj.movieid ).empty().append( movie_votes_btn );        	    
        },
        error: function( e, jqxhr )
        {
            alert( 'An error occurred: ' + jqxhr );
        }
    });
}