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
        $movie_vote_values = array();
        $movie_vote_values['userid'] = $_SESSION['userid'];
        $movie_vote_values['movieid'] = $this->data['movieid'];

        $movie_vote_dao = new MovieVoteDao;

        $href = 'javascript:voteMovie( "like", "'.$this->data['movieid'].'" )';
        $this->data['like'] = "<a href='{$href}'>Like</a>";

        if( $this->data['liked'] = $movie_vote_dao->getVoteLike( $movie_vote_values ) ) {
            $this->data['like'] = '<span class="movie_voted">Like</span>';
        }
    }

    function setHate()
    {
        $movie_vote_values = array();
        $movie_vote_values['userid'] = $_SESSION['userid'];
        $movie_vote_values['movieid'] = $this->data['movieid'];

        $movie_vote_dao = new MovieVoteDao;

        $href = 'javascript:voteMovie( "hate", "'.$this->data['movieid'].'" )';
        $this->data['hate'] = "<a href='{$href}'>Hate</a>";

        if( $this->data['hated'] = $movie_vote_dao->getVoteHate( $movie_vote_values ) ) {
            $this->data['hate'] = '<span class="movie_voted">Hate</span>';
        }
    }

    function renderHtml()
    {
        $movieid = $this->data['movieid'];
        $myself = ( $this->data['posted_by'] == $_SESSION['username'] );          
        $title = $this->data['title'];
        $posted = $this->data['posted'];
        $description = $this->data['description'];
        $likes = $this->data['number_of_likes'];
        $hates = $this->data['number_of_hates'];
        $posted_by = ( $myself )? "You" : $this->data['posted_by'];

        $movie_liked = ( isset( $this->data['liked'] ) && $this->data['liked'] );
        $movie_hated = ( isset( $this->data['hated'] ) && $this->data['hated'] );
        $can_vote = ( !$myself && isset( $this->data['like'] ) && isset( $this->data['hate'] ) );

        // Movie Data
        echo "<div class=\"movie_data\">";
        echo "<div class=\"movie_header\">";
        echo "<span class=\"movie_title\">$title</span>";
        echo "<span class=\"movie_posted\">Posted $posted</span>"; 
        echo "</div>";           
        echo "<div class=\"movie_content\">";
        echo "<span>$description</span>";
        echo "</div>";
        // Num of Likes | Hates
        echo "<div class=\"movie_footer\">";
        echo "<span class=\"movie_votes\">";
        $class = ( $movie_liked )? 'liked' : '';
        echo "<span id=\"like_votes_{$movieid}\" class=\"like_votes $class\">$likes likes</span>";
        echo "<span>|</span>";
        $class = ( $movie_hated )? 'hated' : '';
        echo "<span id=\"hate_votes_{$movieid}\" class=\"hate_votes $class\">$hates hates</span>";
        echo "</span>";
        // Like | Hate
        if( $can_vote )
        {           
            echo "<span id=\"like_link_{$movieid}\" class=\"like_link\">".$this->data['like']."</span>";
            echo "<span>|</span>";
            echo "<span id=\"hate_link_{$movieid}\" class=\"hate_link\">".$this->data['hate']."</span>";
        }
        echo "<span class=\"movie_posted_by\">Posted by <span class=\"movie_posted_by_user\">$posted_by</span>";
        echo "</div>";
        echo "</div>";
    }
}

?>
