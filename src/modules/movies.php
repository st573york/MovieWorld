<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Movie World</title>
    <link rel="icon" type="image/x-icon" href="/images/movies-icon.jpeg">    

    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/movie.css">
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
    <script type="text/javascript" src="/js/movie.js"></script>
</head>
<body>      
    <div class="main">
        <div class="top_panel">
            <span class="title_panel">Movie World</span>
                <span class="actions_panel">
                <span><a href="/login.php">Log in</a></span>
                <span>or</span>
                <span class="register_link"><a href="/registration.php">Sign up</a></span>
            </span>
        </div>

<?php
    require('lib/Movie.php');
    require('lib/MovieSort.php');
    
    require('dao/MovieDao.php');
    require('dao/MovieVoteDao.php');
   
    $movies = array();
    $movie_dao = new MovieDao;

    $movie_dao->getAll( $movies );

    $count = count( $movies );
    echo "<div class=\"found_movies\">Found $count movies</div>";

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
?>

    </div>
</body>
</html>
