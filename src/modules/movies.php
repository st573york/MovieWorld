<?php

require_once('lib/Page.php');
require_once('lib/Dialog.php');
require_once('lib/Movie.php');
    
require_once('dao/MovieDao.php');
require_once('dao/MovieVoteDao.php');
require_once('dao/MovieCommentDao.php');

class Movies extends Page
{
    function __construct()
    {
        global $product_name;
        parent::__construct( _( $product_name ) );

        if( $_SESSION['logged_in'] ) {
            $this->setPopupDialogs( array( 'process_movie', 'confirm' ) );
        }
        $this->setBootstrap();
    }

    function head()
    {
        echo "<link rel=\"stylesheet\" href=\"/css/main.css\">\n";
        echo "<link rel=\"stylesheet\" href=\"/css/movie.css\">\n";
        echo "<link rel=\"stylesheet\" href=\"/css/dropdown.css\">\n"; 

        echo "<script type=\"text/javascript\" src=\"/js/main.js\"></script>\n";
        echo "<script type=\"text/javascript\" src=\"/js/movie.js\"></script>\n";
        echo "<script type=\"text/javascript\" src=\"/js/user.js\"></script>\n";
    }

    function body()
    {   
        $this->renderFixedContent();

        $movies = array();
        $movie_dao = new MovieDao;

        $movie_dao->getAll( $movies );

        // Movie Container
        echo "<div class=\"movie_container\">\n";

        // Found Movies
        $count = count( $movies );
        echo "<div class=\"found_movies\">Found <span class=\"found_movies_count\">$count</span> movies</div>\n";

        // Movie Content
        echo "<div class=\"movie_content\">\n";

        if( $count )
        {
            foreach( $movies as $data )
            {
                $movie = new Movie( $data );
                $movie->renderHtml();
            }
        }

        echo "</div>\n";

        // Movie Spacer
        echo "<div>&nbsp;</div>\n";

        echo "</div>\n";
    }
}
