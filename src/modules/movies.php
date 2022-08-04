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
        echo "<link rel=\"stylesheet\" href=\"/css/main.css\">";
        echo "<link rel=\"stylesheet\" href=\"/css/movie.css\">";     

        echo "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js\"></script>";
        echo "<script type=\"text/javascript\" src=\"/js/movie.js\"></script>";
    }

    function body()
    {
        // Main
        echo "<div class=\"main\">";
        // Top panel
        echo "<div class=\"top_panel\">";
        echo "<div class=\"title_panel\">Movie World</div>";
        echo "<div class=\"searchtext_panel\"><input type=\"text\" id=\"searchtext\" name=\"searchtext\" placeholder=\"Search...\"/></div>";
        echo "<div class=\"actions_panel\">";
        echo "<span><a href=\"/?login\">Log in</a></span>";
        echo "<span class=\"actions_panel_separator\">or</span>";
        echo "<span class=\"register_link\"><a href=\"/?register\">Sign up</a></span>";
        echo "</div>";
        echo "</div>";
        echo "<div class=\"bottom_panel\">";
        echo "<span class=\"empty_panel\">&nbsp;</span>";
        echo "</div>";
   
        $movies = array();
        $movie_dao = new MovieDao;

        $movie_dao->getAll( $movies );

        $count = count( $movies );
        echo "<div class=\"found_movies\">Found <span class=\"found_movies_count\">$count</span> movies</div>";

        // Movie Container
        echo "<div class=\"movie_container\">";

        if( $count )
        {
            echo "<div class=\"movie_list\">";

            foreach( $movies as $data )
            {
                $movie = new Movie( $data );
                $movie->renderHtml();
            }

            echo "</div>";

            // Movie Actions
            echo "<div class=\"movie_actions\">";

            $movie_sort = new MovieSort;
            $movie_sort->renderHtml();

            echo "</div>";
        }

        echo "</div>";

        echo "</div>";
    }
}
