<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Login</title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/movie.css">

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
    <script type="text/javascript" src="/js/movie.js"></script>
</head>
<body>      
      <form action="" method="POST">
          <h1>Movie World</h1>
          <div class="field"><input type="text" name="username" placeholder="Username"/></div>
          <div class="field"><input type="password" name="password" placeholder="Password"></div>
          <div class="field"><input type="submit" name="submit" value="Login"></div>
          <p><a href="/registration.php">New Registration</a></p>
    </form>
<?php
    $warning_msg = '';
    if( isset( $login_denied ) && $login_denied ) {
        $warning_msg = 'The credentials you entered were invalid<br />Please re-enter them and try again';
    }

    if( $warning_msg )
    {   
        echo "<div>";
        echo "<span class='message'>$warning_msg</span>";
        echo "</div>";
    }

    require('lib/Movie.php');
    require('lib/MovieSort.php');
    
    require('dao/MovieDao.php');
    require('dao/MovieVoteDao.php');
   
    $movies = array();
    $movie_dao = new MovieDao;

    $movie_dao->getAll( $movies );

    $count = count( $movies );
    echo "<div><h2>Found $count movies</h2></div>";

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

  </body>
</html>
