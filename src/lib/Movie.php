<?php

class Movie
{
    var $data = array();

    function __construct( $data )
    {
        $this->data = $data;
    }

    function setLike()
    {
        if( isset( $_SESSION['userid'] ) && 
            $_SESSION['userid'] )
        {
            $movie_vote_values = array();
            $movie_vote_values['userid'] = $_SESSION['userid'];
            $movie_vote_values['movieid'] = $this->data['movieid'];

            $movie_vote_dao = new MovieVoteDao;

            $href = 'javascript:voteMovie( "like", "'.$this->data['movieid'].'" )';
            $this->data['like'] = ( $movie_vote_dao->getVoteLike( $movie_vote_values ) )? 'Like' : "<a href='{$href}'>Like</a>";
        }
    }

    function setHate()
    {
        if( isset( $_SESSION['userid'] ) && 
            $_SESSION['userid'] )
        {
            $movie_vote_values = array();
            $movie_vote_values['userid'] = $_SESSION['userid'];
            $movie_vote_values['movieid'] = $this->data['movieid'];

            $movie_vote_dao = new MovieVoteDao;

            $href = 'javascript:voteMovie( "hate", "'.$this->data['movieid'].'" )';
            $this->data['hate'] = ( $movie_vote_dao->getVoteHate( $movie_vote_values ) )? 'Hate' : "<a href='{$href}'>Hate</a>";
        }
    }

    function renderHtml()
    {
        $movieid = $this->data['movieid'];
        $myself = ( isset( $_SESSION['username'] ) && $this->data['posted_by'] == $_SESSION['username'] );          
        $title = $this->data['title'];
        $posted = $this->data['posted'];
        $description = $this->data['description'];
        $likes = $this->data['number_of_likes'];
        $hates = $this->data['number_of_hates'];
        $posted_by = ( $myself )? "You" : $this->data['posted_by'];

        // Movie Data
        echo "<div class=\"movie_data\">";
        echo "<div><h2>Posted $posted</h2></div>";
        echo "<div><h1>$title</h1></div>";            
        echo "<div><p>$description</p></div>";
        // Num of Likes | Hates
        echo "<div class=\"votes_container\">";
        echo "<span class=\"votes\">$likes likes</span>";
        echo "<span class=\"votes\">|</span>";
        echo "<span class=\"votes\">$hates hates</span>";
        echo "</div>";
        // Like | Hate - hide when movie has been submitted by logged in user

        if( !$myself && 
            isset( $this->data['like'] ) && 
            isset( $this->data['hate'] ) )
        {            
            echo "<div>";
            echo "<span class=\"vote_link\">".$this->data['like']."</span>";
            echo "<span>|</span>";
            echo "<span class=\"vote_link\">".$this->data['hate']."</span>";

            echo "</div>";
        }
        echo "<div><h2>Posted by <span class=\"posted_by\">$posted_by</span></h2></div>";
        echo "</div>";
    }
}

?>
