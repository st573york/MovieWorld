<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Movie World</title>
    <link rel="icon" type="image/x-icon" href="/images/movies-icon.jpeg">

    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/movie.css">
    <link rel="stylesheet" href="/css/dropdownbutton.css">
    <link rel="stylesheet" href="/css/popup-dialog.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/movie.js"></script>
    <script type="text/javascript" src="/js/popup-dialog-widget.js"></script>    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
</head>
<body>
    <div class="main">    
        <div class="top_panel">
            <span class="title_panel">Movie World</span>
            <span class="message_panel">Welcome Back 
                <span class="loggedin_user">
<?php 
                    if( $_SESSION['username'] == 'admin' ) {   
                        echo "<a href=\"\">".$_SESSION['username']."</a>";                
                    }
                    else {
                        echo "<a href=\"javascript:sortMovies( 'sort_by_user' )\">".$_SESSION['username']."</a>";
                    }
?>
                </span>
            </span>
        </div>
        <div class="bottom_panel">
            <span class="logout_panel"><a href="logout.php">Logout</a></span>
        </div>
   
        <!-- Dialog Process Movie -->
	    <div id="popup-dialog-process_movie" style="display: none;"></div>

        <!-- Dialog Confirm -->
        <div id="popup-dialog-confirm" style="display: none;"></div>
      
<?php
    require('lib/Movie.php');
    require('lib/MovieSort.php');

    require('dao/MovieDao.php');
    require('dao/MovieVoteDao.php');
    require('dao/MovieCommentDao.php');
      
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

            $count++;
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
?>
    </div>
</body>
</html>
