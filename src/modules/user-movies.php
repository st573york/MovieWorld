<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Movie World</title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/movie.css">
    <link rel="stylesheet" href="/css/popup-dialog.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
    <script type="text/javascript" src="/js/movie.js"></script>
    <script type="text/javascript" src="/js/popup-dialog-widget.js"></script>    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript">
    <!--
    var dialogs = [ 'new_movie' ];

    function newMovie()
    {
        var buttons = 
        [ 
            { 'text': 'OK',
              'click': function ()
		  	            {					
                            console.log($( '#popup-dialog-form' ).serialize());
					        $.ajax({
						        type: "POST",
						        url: "/ajax/process-movie.php",
						        data: {
						            'action': 'add',
							        'popupDialogData': $( '#popup-dialog-form' ).serialize()
						        },
						        dataType: 'json',
						        cache: false,
						        success: function( data )
						        {
							        if( data.resp ) {
                                        closePopupDialog( 'new_movie' );
                                    }

                                    window.location.reload();
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

        popupDialog( {
		    'id': 'new_movie',
            'title': 'New Movie',
            'buttons': buttons,
            'html': $( '#popup-dialog-form' )
        } );
      }

    -->
    </script>
</head>
<body>
    <div>
        <h1>Movie World</h1>
        <p>Welcome Back <span class="loggedin_user"><?php echo $_SESSION['username'] ?></span></p>
        <p><a href="/logout">Logout</a></p>
    </div>
    <!-- Movie Form -->
	<div id="popup-dialog-new_movie" style="display: none;">
	    <form id="popup-dialog-form">
            <div class="field"><input type="text" name="title" placeholder="Name"/></div>
            <div class="field"><input type="text" name="description" placeholder="Description"/></div>
	    </form>
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
    echo "<div><h2>Found $count movies</h2></div>";

    // Movie Container
    echo "<div class=\"movie_container\">";

    if( $count )
    {
        // Movie List
        echo "<div class=\"movie_list\">";

        foreach( $movies as $data )
        {         
            $movie = new Movie( $data );

            $movie->setLike();
            $movie->setHate();

            $movie->renderHtml();
        }

        echo "</div>";
    }

    // Movie Actions
    echo "<div class=\"movie_actions\">";
    // Open dialog to add new movie
    echo "<div class=\"movie_add\"><input id=\"new_movie\" type=\"button\" value=\"New Movie\" onclick=\"newMovie()\"/></div>";
    if( $count )
    {
        $movie_sort = new MovieSort;
        $movie_sort->renderHtml();
    }
    echo "</div>";

    echo "</div>";
?>

  </body>
</html>
