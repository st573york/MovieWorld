/**
 * Generic events, functions
 */

 var requestTimer = false;
 var loader = false;
 
 $( document ).ready( function() {
 
     // Highlight default date most recent sort by option
     $( '.movie_actions_panel .dropdown-menu' ).find( 'li#date_most_recent' ).addClass( 'active' );
 
     // Show loader when ajax request begins
     $( this ).ajaxStart( function() {
         if( loader ) { 
             $( '#loader' ).show();
         }
     });
 
     // Hide loader when ajax request has completed
     $( this ).ajaxStop( function() { 
         if( loader ) 
         { 
             $( '#loader' ).hide();
             
             loader = false;
         }
     });
 
     // Trigger ajax on search input change                                                                                                                                                                               
     $( 'input#searchtext' ).on( 'input propertychange', function() {
         if( requestTimer )
         {
             window.clearTimeout( requestTimer );
             requestTimer = false;
         }
 
         var obj = {};
         obj['action'] = 'sort_by_text';
         obj['searchtext'] = this.value;
 
         requestTimer = setTimeout( function () { loader = true; sortMovies( obj ); }, 500 );
     });
 
     // Highlight sort by option
     $( '.movie_actions_panel .dropdown-menu li' ).click( function() {
         if( this.id != 'sort_by' )
         {
             $( this ).parent().find( 'li' ).removeClass( 'active' );
             $( this ).addClass( 'active' );
         }
     });

 } );

function confirmDeletion( obj)
{
    var buttons = 
    [ 
        {   'text': 'OK',
            'click': function ()
		  	{
                if( obj.type == 'user' ) {
                    processUser( obj );
                }
                else if( obj.type == 'movie' ) {
                    processMovie( obj );
                }
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