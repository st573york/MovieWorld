<?php

/**                                                                                                                                                                                                         
 * The Page class provides a common base class for all user interface pages.                                                                                                                              
 */
class Page
{
    var $title;
    var $popup_dialogs = array();
    var $bootstrap = false;

    function __construct( $title )
    {
        $this->setTitle( $title );
    }

    /**                                                                                                                                                                                                     
     * Set the page title                                                                                                                                                                                   
     */
    function setTitle( $title )
    {
        $this->title = $title;
    }

     /**                                                                                                                                                                                                   
      * Set Popup Dialogs                                                                                                                                                                                  
      */
    function setPopupDialogs( $popup_dialogs )
    {
        $this->popup_dialogs = $popup_dialogs;
    }

    /**                                                                                                                                                                                                   
     * Set Bootstrap                                                                                                                                                                                      
     */
    function setBootstrap()
    {
        $this->bootstrap = true;
    }

     /**                                                                                                                                                                                                   
      * Generate and display the page                                                                                                                                                            
      */
    function renderHtml()
    {
        echo "<!DOCTYPE html>\n";
        echo "<html>\n";
        echo "<head>\n";
        echo "<meta charset=\"utf-8\"/>\n";
        echo "<title>$this->title</title>\n";
        echo "<link rel=\"icon\" type=\"image/x-icon\" href=\"/images/movies-icon.jpeg\">\n";
        echo "<link rel=\"stylesheet\" href=\"//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css\">\n";
        if( !empty( $this->popup_dialogs ) ) {
            echo "<link rel=\"stylesheet\" href=\"/css/popup-dialog.css\">\n";
        }
        echo "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js\"></script>\n";
        if( !empty( $this->popup_dialogs ) ) {
            echo "<script type=\"text/javascript\" src=\"/js/popup-dialog-widget.js\"></script>\n";
        }
        if( $this->bootstrap ) {
            echo "<script type=\"text/javascript\" src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js\"></script>\n";
        }
        echo "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js\"></script>\n";
        echo "<script type=\"text/javascript\">\n";
        echo "<!--\n";
        echo "var dialogs = ";
        echo json_encode( $this->popup_dialogs );
        echo ";\n";
?>

        $( document ).ready( function() {

                if( dialogs.length ) {
                    initPopupDialogs();
                }

            } );
        
<?php
        echo "//-->\n";
        echo "</script>\n";
        $this->head();   /* Include header code from the subclass */
        echo "</head>\n";
        echo "<body>\n";
        echo "<div id=\"loader\"></div>\n";
        foreach( $this->popup_dialogs as $popup_dialog ) {
            echo "<div id=\"popup-dialog-" . $popup_dialog . "\" style=\"display: none;\"></div>\n";
        }
        $this->body();   /* Implemented by subclass */
        echo "</body>\n";
        echo "</html>\n";
    }

    /**                                                                                                                                                                                                     
     * Called to draw page header. Override in subclasses.                                                                                                                                                  
     */
    function head()
    {

    }

    /**                                                                                                                                                                                                     
     * Called to draw page body. Override in subclasses.                                                                                                                                                    
     */
    function body()
    {

    }
}

?>