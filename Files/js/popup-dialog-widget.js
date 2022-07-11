/**
 * Popup-Dialog Widget
*/

var popupDialogPrefix = 'popup-dialog';

$( document ).ready( function() {

    initPopupDialog();
                    				
} );

function getPopupDialogs()
{
    var ids = '';
    for( var i = 0; i < dialogs.length; i++ ) {
        ids += '#' + popupDialogPrefix + '-' + dialogs[i] + ( ( i < dialogs.length - 1 ) ? ', ' : '' );
    }
    return ids;
}

function initPopupDialog()
{			
	$( getPopupDialogs() ).dialog({
		autoOpen: false,
		draggable: false,
		resizable: false,
		width: 'auto'
    });
}

function popupDialog( settings )
{
    /* Default options */
    var options = $.extend({
        id: '',
        title: '',
        html: '',
        buttons: []
        }, settings );

    processPopupDialog( options );
}

function processPopupDialog()
{
	$( '#' + popupDialogPrefix + '-' + options.id )
        .data( options )
        .html( options.html )
        .dialog( 'option',
               { 'title': options.title,
                 'buttons': options.buttons } );

	if( !$( '#' + popupDialogPrefix + '-' + options.id ).dialog( 'isOpen' ) ) {
		$( '#' + popupDialogPrefix + '-' + options.id ).dialog( 'open' );
	}
}

function isPopupDialogOpen( id )
{
    if( $( '#' + popupDialogPrefix + '-' + id ).dialog( 'isOpen' ) ) {
        return true;
    }

    return false;
}

function closePopupDialog( id )
{
    $( '#' + popupDialogPrefix + '-' + id ).dialog( 'close' );
}
