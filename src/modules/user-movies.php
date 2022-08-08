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
        $this->setPopupDialogs( array( 'process_movie', 'confirm' ) );
        $this->setBootstrap();
    }

    function head()
    {
        echo "<link rel=\"stylesheet\" href=\"/css/main.css\">\n";
        echo "<link rel=\"stylesheet\" href=\"/css/movie.css\">\n";
        echo "<link rel=\"stylesheet\" href=\"/css/dropdownbutton.css\">\n";

        echo "<script type=\"text/javascript\" src=\"/js/movie.js\"></script>\n";
    }

    function body()
    {
        // Main
        echo "<div class=\"main\">\n";
        // Top panel
        echo "<div class=\"top_panel\">\n";
        echo "<div class=\"title_panel\">Movie World</div>\n";
        echo "<div class=\"searchtext_panel\"><input type=\"text\" id=\"searchtext\" name=\"searchtext\" placeholder=\"Search...\"/></div>\n";
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
        echo "</div>\n";
        echo "<div class=\"bottom_panel\">\n";
        echo "<span class=\"logout_panel\"><a href=\"php/logout.php\">Logout</a></span>\n";
        echo "</div>\n";
      
        $movies = array();
        $movie_dao = new MovieDao;

        $movie_dao->getAll( $movies );

        $count = count( $movies );
        echo "<div class=\"found_movies\">Found <span class=\"found_movies_count\">$count</span> movies</div>\n";

        // Movie Container
        echo "<div class=\"movie_container\">\n";

        if( $count )
        {
            // Movie List
            echo "<div class=\"movie_list\">\n";
            
            foreach( $movies as $data )
            {         
                $movie = new Movie( $data );
                $movie->renderHtml();
            }

            echo "</div>\n";
        }

        // Movie Actions
        echo "<div class=\"movie_actions\">\n";

        if( $_SESSION['username'] != 'admin' )
        {
            // Add new movie
            $obj = array();
            $obj['action'] = 'add';
            $obj['type'] = 'movie';
            $obj['title'] = 'Add Movie';
            $obj['html'] = Movie::getMovieDialogHtml();

            $onclick = 'showDialog( '.json_encode( $obj ).' );';
            echo "<div class=\"movie_add\">\n<input id=\"new_movie\" type=\"button\" value=\"New Movie\" title=\"Add New Movie\" onclick='{$onclick}'/>\n</div>\n";
        }

        if( $count )
        {
            $movie_sort = new MovieSort;
            $movie_sort->renderHtml();
        }
    
        echo "</div>\n";

        echo "</div>\n";

        echo "</div>\n";
    }
}
