<?php

require_once('lib/Page.php');
require_once('lib/Movie.php');
require_once('lib/MovieSort.php');
    
require_once('dao/MovieDao.php');
require_once('dao/MovieVoteDao.php');
require_once('dao/MovieCommentDao.php');

class UserMovies extends Page
{
    function __construct()
    {
        parent::__construct( _("Movie World") );
    }

    function head()
    {
        echo "<link rel=\"stylesheet\" href=\"//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css\">";
        echo "<link rel=\"stylesheet\" href=\"/css/main.css\">";
        echo "<link rel=\"stylesheet\" href=\"/css/movie.css\">";
        echo "<link rel=\"stylesheet\" href=\"/css/dropdownbutton.css\">";
        echo "<link rel=\"stylesheet\" href=\"/css/popup-dialog.css\">";        

        echo "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js\"></script>";
        echo "<script type=\"text/javascript\" src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js\"></script>";
        echo "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js\"></script>";
        echo "<script type=\"text/javascript\" src=\"/js/movie.js\"></script>";
        echo "<script type=\"text/javascript\" src=\"/js/popup-dialog-widget.js\"></script>";
    }

    function body()
    {
        // Main
        echo "<div class=\"main\">";
        // Top panel
        echo "<div class=\"top_panel\">";
        echo "<div class=\"title_panel\">Movie World</div>";
        echo "<div class=\"searchtext_panel\"><input type=\"text\" id=\"searchtext\" name=\"searchtext\" placeholder=\"Search...\"/></div>";
        echo "<div class=\"message_panel\">Welcome Back";
        echo "<span class=\"loggedin_user\">";
        if( $_SESSION['username'] == 'admin' ) {   
            echo "<a href=\"\">".$_SESSION['username']."</a>";                
        }
        else {
            echo "<a href=\"/?profile\">".$_SESSION['username']."</a>";
        }
        echo "</span>";
        echo "</div>";
        echo "</div>";
        echo "<div class=\"bottom_panel\">";
        echo "<span class=\"logout_panel\"><a href=\"php/logout.php\">Logout</a></span>";
        echo "</div>";
   
        // Dialog Process Movie 
	    echo "<div id=\"popup-dialog-process_movie\" style=\"display: none;\"></div>";

        // Dialog Confirm
        echo "<div id=\"popup-dialog-confirm\" style=\"display: none;\"></div>";
      
        $movies = array();
        $movie_dao = new MovieDao;

        $movie_dao->getAll( $movies );

        $count = count( $movies );
        echo "<div class=\"found_movies\">Found <span class=\"found_movies_count\">$count</span> movies</div>";

        // Movie Container
        echo "<div class=\"movie_container\">";

        if( $count )
        {
            // Movie List
            echo "<div class=\"movie_list\">";
            
            foreach( $movies as $data )
            {         
                $movie = new Movie( $data );
                $movie->renderHtml();
            }

            echo "</div>";
        }

        // Movie Actions
        echo "<div class=\"movie_actions\">";

        if( $_SESSION['username'] != 'admin' )
        {
            // Add new movie
            $obj = array();
            $obj['action'] = 'add';
            $obj['type'] = 'movie';
            $obj['title'] = 'Add Movie';
            $obj['html'] = Movie::getMovieDialogHtml();

            $onclick = 'showDialog( '.json_encode( $obj ).' );';
            echo "<div class=\"movie_add\"><input id=\"new_movie\" type=\"button\" value=\"New Movie\" title=\"Add New Movie\" onclick='{$onclick}'/></div>";
        }

        if( $count )
        {
            $movie_sort = new MovieSort;
            $movie_sort->renderHtml();
        }
    
        echo "</div>";

        echo "</div>";

        echo "</div>";
    }
}
