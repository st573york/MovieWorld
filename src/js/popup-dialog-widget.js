/**
 * Popup-Dialog Widget
 */

var popupDialogPrefix = 'popup-dialog-';

$( document ).ready( function() {

    initPopupDialogs();
                    				
} );

function getPopupDialogs()
{
    var ids = '';
    for( var i = 0; i < dialogs.length; i++ ) {
        ids += '#' + popupDialogPrefix + dialogs[i] + ( ( i < dialogs.length - 1 ) ? ', ' : '' );
    }
    return ids;
}

function initPopupDialogs()
{			
	$( getPopupDialogs() ).dialog({
		autoOpen: false,
		draggable: false,
		resizable: false,
        modal: true,
		width: 'auto'
    });
}

function popupDialog( settings )
{
    /* Default options */
    var options = $.extend({
        id: '',
        title: '',
        html: $( '#' + popupDialogPrefix + settings.id + ' .popup-dialog-container' ),
        buttons: []
        }, settings );

    processPopupDialog( options );
}

function processPopupDialog( options )
{
	$( '#' + popupDialogPrefix + options.id  )
        .data( options )
        .html( options.html )
        .dialog( 'option',
               { 'title': options.title,
                 'buttons': options.buttons } );

	if( !$( '#' + popupDialogPrefix + options.id ).dialog( 'isOpen' ) ) {
		$( '#' + popupDialogPrefix + options.id  ).dialog( 'open' );
	}
}

function closePopupDialog( id )
{
    $( '#' + popupDialogPrefix + id ).dialog( 'close' );
}
