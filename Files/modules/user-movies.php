<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Movie World</title>

    <script type="text/javascript" src="/js/popup-dialog-widget.js"></script>
    <script type="text/javascript">
    <!--
      var dialogs = [ 'new_movie' ];

      $( document ).ready( function() {
          // Enable hate vote, disable like
          $( this ).on( 'click', '.like_link', function() {                        
                  var parts = this.id.split( 'like_link_' );
                  var id = parts[1];
                  
                  $( '#like_link_' + id ).prop( 'disabled', true );
                  $( '#hate_link_' + id ).prop( 'disabled', false );
			  } );

          // Enable like vote, disable hate
          $( this ).on( 'click', '.hate_link', function() {
                  var parts = this.id.split( 'hate_link_' );
                  var id = parts[1];
                  
                  $( '#hate_link_' + id ).prop( 'disabled', true );
                  $( '#like_link_' + id ).prop( 'disabled', false );
			  } );
                               
          });

      function newMovie()
      {
          var buttons = [ { 'text': 'OK',
                            'click': function ()
				            {					
					            $.ajax({
						            type: "POST",
						            url: "ajax/process-movie.php",
						            data: {
							            'action': 'add',
							            'popupDialogData': $( '#popup-dialog' ).serialize()
						            },
						            dataType: 'json',
						            cache: false,
						            success: function()
						            {
							            if( data.success ) {
                                            closePopupDialog( 'new_movie' );
                                        }
							        }
					            });
                            },
                        },
                        { 'text': 'Cancel',
                          'click': function ()
				          {
                              closePopupDialog( 'new_movie' );
                          }
                        }
                    ];

          // Movie Form
	      var html = "";
	      html += "<div>";
	      html += "<form id=\"popup-dialog\">";
	      html += "<input type=\"text\" name=\"name\" placeholder=\"Name\"/>";
	      html += "<input type=\"text\" name=\"description\" placeholder=\"Description\"/>";
	      html += "</form>";
	      html += "</div>";

          popupDialog( {
		        'id': 'new_movie',
                'title': 'New Movie',
                'buttons': buttons,
                'html': html 
            } );
      }

      function voteMovie( action, movieid )
      {			
          $.ajax({
              type: "POST",
              url: "ajax/process-movie.php",
              data: {
                  'action': action,
                  'movieid': movieid
              },
              dataType: 'json',
              cache: false
          });
      }

      function sortMovies( action )
      {			
          $.ajax({
              type: "POST",
              url: "ajax/process-movie.php",
              data: { 'action': action },
              dataType: 'json',
              cache: false,
              success: function()
              {
                  if( data.resp_data )
                  {
                      $( '#movielist' ).empty();
                      $( '#movielist' ).append( data.resp_data );
                  }
              }        
          });
      }

    -->
    </script>
</head>
<body>
      <div>
          <p>Welcome Back <?php echo $_SESSION['username']; ?></p>
          <p><a href="logout">Logout</a></p>
      </div>
      
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
            $movieid = $movie['movieid'];
            $myself = ( $movie['posted_by'] == $_SESSION['username'] );          
            $title = $movie['title'];
            $posted = $movie['posted'];
            $description = $movie['description'];
            $likes = $movie['number_of_likes'];
            $hates = $movie['number_of_hates'];
            $posted_by = ( $myself )? "You" : $movie['posted_by'];

            echo "<div id=\"movielist\">";
            echo "<div><h1>$title</h1></div>";
            echo "<div><h2>Posted $posted</h2></div>";
            echo "<div><p>$description</p></div>";
            // Num of Likes | Hates
            echo "<div>";
            echo "<span>$likes likes</span>";
            echo "<span><br/>|<br/></span>";
            echo "<span>$hates hates</span>";
            echo "</div>";
            // Like | Hate - hide when movie has been submitted by logged in user
            if( !$myself )
            {
                echo "<div>";
                $like = 'javascript:voteMovie( "like", "'.$movieid.'" )';
                echo "<span id=\"like_link_\"".$movieid." class=\"like_link\"><a href='{$like}'>Like</a></span><br/>";
                echo "<span>|</span><br/>";
                $hate = 'javascript:voteMovie( "hate", "'.$movieid.'" )';
                echo "<span id=\"hate_link_\"".$movieid." class=\"hate_link\"><a href='{$hate}'>Hate</a></span>";
                echo "</div>";
            }
            echo "<div><h2>Posted by $posted_by</h2></div>";
            echo "</div>";
        }
    }

    // Open dialog to add new movie
    echo "<div><input id=\"new_movie\" type=\"button\" value=\"New Movie\" onclick=\"newMovie();\"/></div>";

    if( $count )
    {
        // Sort by Likes, Hates, Dates
        echo "<div>";
        $sort_by_likes = 'javascript:sortMovies( "sort_by_likes" )';
        echo "<span><a href='{$sort_by_likes}'>Likes</a></span><br/>";
        $sort_by_hates = 'javascript:sortMovies( "sort_by_hates" )';
        echo "<span><a href='{$sort_by_hates}'>Hates</a></span><br/>";
        $sort_by_dates = 'javascript:sortMovies( "sort_by_dates" )';
        echo "<span><a href='{$sort_by_dates}'>Dates</a></span><br/>";
        echo "</div>";
    }
?>

  </body>
</html>
