<?php

/**                                                                                                                                                                                                         
 * The Page class provides a common base class for all user interface pages.                                                                                                                              
 */
class Page
{
    var $title;

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

    function renderHtml()
    {
        echo "<!DOCTYPE html>";
        echo "<html>";
        echo "<head>";
        echo "<meta charset=\"utf-8\"/>";
        echo "<title>$this->title</title>";
        echo "<link rel=\"icon\" type=\"image/x-icon\" href=\"/images/movies-icon.jpeg\">";
        $this->head();   /* Include header code from the subclass */
        echo "</head>";
        echo "<body>";
        echo "<div id=\"loader\"></div>";
        $this->body();   /* Implemented by subclass */
        echo "</body>";
        echo "</html>";
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