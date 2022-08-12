/**
 * Generic events, functions
 */

 var requestTimer = false;
 var loader = false;
 
 $( document ).ready( function() {
 
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

 } );