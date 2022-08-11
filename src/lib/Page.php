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
      * Generate and display the fixed content                                                                                                                                                            
      */
    function renderFixedContent()
    {
        global $product_name;

        // Left panel
        echo "<div class=\"fixed_left_panel\"></div>\n";
        // Right panel
        echo "<div class=\"fixed_right_panel\"></div>\n";
        // Top panel
        echo "<div class=\"fixed_top_panel\">\n";
        echo "<img src=\"images/movies-icon.jpeg\"/>\n";
        echo "<div class=\"title_panel\">$product_name</div>\n";
        echo "<div class=\"searchtext_panel\"><input type=\"text\" id=\"searchtext\" name=\"searchtext\" autocomplete=\"off\" placeholder=\"Search...\"/></div>\n";
        if( $_SESSION['logged_in'] ) 
        {
            echo "<div class=\"message_panel\">Welcome Back\n";
            echo "<span class=\"loggedin_user\">";
            if( $_SESSION['username'] == 'admin' ) {   
                echo "<a href=\"\">".$_SESSION['username']."</a>";                
            }
            else {
                echo "<a href=\"/?profile\">".$_SESSION['username']."</a>";
            }
            echo "</span>\n";
            echo "</div>\n";
            echo "<div class=\"logout_panel\"><a href=\"php/logout.php\">Logout</a></div>\n";
        }
        else
        {
            echo "<div class=\"user_actions_panel\">\n";
            echo "<span><a href=\"/?login\">Log in</a></span>\n";
            echo "<span class=\"user_actions_panel_separator\">or</span>\n";
            echo "<span class=\"register_link\"><a href=\"/?register\">Sign up</a></span>\n";
            echo "</div>\n";
            echo "<div class=\"empty_panel\">&nbsp;</div>\n";
        }
        echo "<div class=\"movie_actions_panel\">";
        if( $_SESSION['logged_in'] && 
            $_SESSION['username'] != 'admin') 
        {
            // Add new movie
            $obj = array();
            $obj['action'] = 'add';
            $obj['type'] = 'movie';
            $obj['title'] = 'Add Movie';
            $obj['html'] = Movie::getMovieDialogHtml();

            $onclick = 'this.blur(); showDialog( '.json_encode( $obj ).' );';

            echo "<div class=\"dropdown\">\n";
            echo "<button class=\"dropdown-btn\" type=\"button\" onclick='{$onclick}'>New Movie</button>\n";
            echo "</div>\n";
        }
        echo "<div class=\"dropdown\">\n";
        echo "<button class=\"dropdown-btn\" type=\"button\" data-toggle=\"dropdown\">Options";
        echo "<span class=\"caret\"></span>";
        echo "</button>\n";
        echo "<ul class=\"dropdown-menu\">\n";
        echo "<li id=\"sort_by\" class=\"disabled\">Sort by</li>\n";
        $sort_by_title = 'javascript:sortMovies( { "action": "sort_by_title" } )';
        echo "<li id=\"title\" title=\"Sort by title\"><a href='{$sort_by_title}'>Title</a></li>\n";
        $sort_by_likes = 'javascript:sortMovies( { "action": "sort_by_likes" } )';
        echo "<li id=\"likes\" title=\"Sort by highest number of likes\"><a href='{$sort_by_likes}'>Likes</a></li>\n";
        $sort_by_hates = 'javascript:sortMovies( { "action": "sort_by_hates" } )';
        echo "<li id=\"hates\" title=\"Sort by highest number of hates\"><a href='{$sort_by_hates}'>Hates</a></li>\n";
        $sort_by_comments = 'javascript:sortMovies( { "action": "sort_by_comments" } )';
        echo "<li id=\"comments\" title=\"Sort by highest number of comments\"><a href='{$sort_by_comments}'>Comments</a></li>\n";
        $sort_by_author = 'javascript:sortMovies( { "action": "sort_by_author" } )';
        echo "<li id=\"author\" title=\"Sort by author\"><a href='{$sort_by_author}'>Author</a></li>\n";
        $sort_by_date_most_recent = 'javascript:sortMovies( { "action": "sort_by_date_most_recent" } )';
        echo "<li id=\"date_most_recent\" title=\"Sort by most recent date\"><a href='{$sort_by_date_most_recent}'>Date: Most recent</a></li>\n";
        $sort_by_date_oldest = 'javascript:sortMovies( { "action": "sort_by_date_oldest" } )';
        echo "<li id=\"date_oldest\" title=\"Sort by oldest date\"><a href='{$sort_by_date_oldest}'>Date: Oldest</a></li>\n";
        echo "</ul>\n";
        echo "</div>\n";
        echo "</div>\n";
        echo "<div class=\"fade-top\"></div>\n";
        echo "</div>\n";
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
        // Bottom panel
        echo "<div class=\"fixed_bottom_panel\">\n";
        echo "<div class=\"fade-bottom\"></div>\n";
        $this->displayFooter();
        echo "</div>\n";
        echo "</body>\n";
        echo "</html>\n";
    }

    /**                                                                                                                                                                                                   
     * Display footer                                                                                                                                                                                     
     */
    function displayFooter()
    {
        global $footer_text;

        if( !empty( $footer_text ) )
            echo "<div class=\"footer\">$footer_text</div>\n";
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