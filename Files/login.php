<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Login</title>
</head>
<body>      
      <form action="index.php" method="POST">
          <h1>Login</h1>
          <input type="text" name="username" placeholder="Username"/>
          <input type="password" name="password" placeholder="Password">
          <input type="submit" name="submit" value="Login">
          <p><a href="registration.php">New Registration</a></p>
    </form>
      
<?php
      require('dao/MovieDao.php');

      $movies = array();
      $movie_dao = new MovieDao;

      $movie_dao->getAll( $movies );

      $count = count( $movies );
      echo "<div><h2>Found $count movies</h2></div>";
      
      if( $count )
      {
        foreach( $movies as $movie )
        {
            $title = $movie['title'];
            $posted = $movie['posted'];
            $description = $movie['description'];
            $likes = $movie['number_of_likes'];
            $hates = $movie['number_of_hates'];
            $posted_by = $movie['posted_by'];

            echo "<div><h1>$title</h1></div>";
            echo "<div><h2>Posted $posted</h2></div>";
            echo "<div><p>$description</p></div>";
            // Num of Likes | Hates
            echo "<div>";
            echo "<span>$likes likes</span><br/>";
            echo "<span>|</span><br/>";
            echo "<span>$hates hates</span>";
            echo "</div>";
            echo "<div><h2>Posted by $posted_by</h2></div>";
        }
    }
?>

  </body>
</html>
