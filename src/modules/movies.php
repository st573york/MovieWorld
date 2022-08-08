<?php

require_once('lib/Page.php');
require_once('lib/Movie.php');
require_once('lib/MovieSort.php');
    
require_once('dao/MovieDao.php');
require_once('dao/MovieVoteDao.php');
require_once('dao/MovieCommentDao.php');

class Movies extends Page
{
    function __construct()
    {
        parent::__construct( _("Movie World") );
    }

    function head()
    {
        echo "<link rel=\"stylesheet\" href=\"/css/main.css\">\n";
        echo "<link rel=\"stylesheet\" href=\"/css/movie.css\">\n";     

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
        echo "<div class=\"actions_panel\">\n";
        echo "<span><a href=\"/?login\">Log in</a></span>\n";
        echo "<span class=\"actions_panel_separator\">or</span>\n";
        echo "<span class=\"register_link\"><a href=\"/?register\">Sign up</a></span>\n";
        echo "</div>\n";
        echo "</div>\n";
        echo "<div class=\"bottom_panel\">\n";
        echo "<span class=\"empty_panel\">&nbsp;</span>\n";
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
            echo "<div class=\"movie_list\">\n";

            foreach( $movies as $data )
            {
                $movie = new Movie( $data );
                $movie->renderHtml();
            }

            echo "</div>\n";

            // Movie Actions
            echo "<div class=\"movie_actions\">\n";

            $movie_sort = new MovieSort;
            $movie_sort->renderHtml();

            echo "</div>\n";
        }

        echo "</div>\n";

        echo "</div>\n";
    }
}
