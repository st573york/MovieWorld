<?php

class Movie
{
    var $data = array();

    function __construct( $data )
    {
        $this->data = $data;

        $this->setLike();
        $this->setHate();
    }

    function setLike()
    {
        $movie_vote_values = array();
        $movie_vote_values['userid'] = $_SESSION['userid'];
        $movie_vote_values['movieid'] = $this->data['movieid'];

        $movie_vote_dao = new MovieVoteDao;

        $class = '';
        if( $this->data['liked'] = $movie_vote_dao->hasUserLiked( $movie_vote_values ) ) {
            $class = 'movie_voted';
        }

        $obj = array();
        $obj['action'] = 'like';
        $obj['movieid'] = $this->data['movieid'];

        $href = 'javascript:processMovie( '.json_encode( $obj ).' );';
        $this->data['like'] = "<span class=\"$class\"><a href='{$href}'>Like</a></span>";
    }

    function setHate()
    {
        $movie_vote_values = array();
        $movie_vote_values['userid'] = $_SESSION['userid'];
        $movie_vote_values['movieid'] = $this->data['movieid'];

        $movie_vote_dao = new MovieVoteDao;

        $class = '';
        if( $this->data['hated'] = $movie_vote_dao->hasUserHated( $movie_vote_values ) ) {
            $class = 'movie_voted';
        }

        $obj = array();
        $obj['action'] = 'hate';
        $obj['movieid'] = $this->data['movieid'];

        $href = 'javascript:processMovie( '.json_encode( $obj ).' );';
        $this->data['hate'] = "<span class=\"$class\"><a href='{$href}'>Hate</a></span>";
    }

    function renderVotesNum()
    {
        $movieid = $this->data['movieid'];

        $movie_vote_values = array();
        $movie_vote_values['movieid'] = $movieid;

        $movie_vote_dao = new MovieVoteDao;

        $movie_vote_dao->getUsersByLike( $movie_vote_values );
        $movie_vote_dao->getUsersByHate( $movie_vote_values );

        $users_like = ( !empty( $movie_vote_values['users_like'] ) && $_SESSION['logged_in'] );
        $users_hate = ( !empty( $movie_vote_values['users_hate'] ) && $_SESSION['logged_in'] );

        echo "<div id=\"movie_votes_num_{$movieid}\" class=\"movie_votes_num\">";
        echo "<div class=\"like_votes\">"; 
        $class = ( $this->data['liked'] )? 'liked' : '';
        echo "<div id=\"like_votes_{$movieid}\" class=\"$class\">";
        $class = ( $users_like )? 'like_votes_text' : '';
        echo "<span id=\"like_votes_text_{$movieid}\" class=\"$class\">".$movie_vote_dao->getLikeVotesNum( $movieid )." likes</span>";
        echo "</div>";
        if( $users_like )
        {
            echo "<div id=\"users_like_{$movieid}\" class=\"users_list\">";
            echo "<ul class=\"users_list_ul\">";        
            foreach( $movie_vote_values['users_like'] as $user ) {
                echo "<li class=\"users_list_li\">$user</li>";
            }
            echo "</ul>";
            echo "</div>";
        }
        echo "</div>";
        echo "<div style=\"float: inherit;\">|</div>";
        echo "<div class=\"hate_votes\">";
        $class = ( $this->data['hated'] )? 'hated' : '';
        echo "<div id=\"hate_votes_{$movieid}\" class=\"$class\">";
        $class = ( $users_hate )? 'hate_votes_text' : '';
        echo "<span id=\"hate_votes_text_{$movieid}\" class=\"$class\">".$movie_vote_dao->getHateVotesNum( $movieid )." hates</span>";
        echo "</div>";        
        if( $users_hate )
        {
            echo "<div id=\"users_hate_{$movieid}\" class=\"users_list\">";
            echo "<ul class=\"users_list_ul\">";
            foreach( $movie_vote_values['users_hate'] as $user ) {
                echo "<li class=\"users_list_li\">$user</li>";
            }
            echo "</ul>";
            echo "</div>";
        }
        echo "</div>";
        echo "</div>";
    }

    function renderVotesBtn()
    {
        echo "<div id=\"movie_votes_btn_".$this->data['movieid']."\" class=\"movie_votes_btn\">"; 
        echo "<span id=\"like_btn_".$this->data['movieid']."\" class=\"like_btn\">".$this->data['like']."</span>";
        echo "<span>|</span>";
        echo "<span id=\"hate_btn_".$this->data['movieid']."\" class=\"hate_btn\">".$this->data['hate']."</span>";
        echo "</div>";
    }

    function renderHtml()
    {
        $movieid = $this->data['movieid'];
        $isadmin = ( $_SESSION['username'] == 'admin' );
        $myself = ( $this->data['posted_by'] == $_SESSION['username'] );          
        $title = $this->data['title'];
        $posted = $this->data['posted'];
        $description = $this->data['description'];
        $posted_by = ( $myself )? "You" : $this->data['posted_by'];

        $can_vote = ( !$isadmin && !$myself && $_SESSION['logged_in'] );
        $can_edit_or_delete = ( $isadmin || $myself );

        // Movie Data
        echo "<div id=\"movie_{$movieid}\" class=\"movie_data\">";
        echo "<div class=\"movie_header\">";
        echo "<span class=\"movie_title\">$title</span>";
        echo "<span class=\"movie_posted\">Posted $posted</span>"; 
        echo "</div>";           
        echo "<div class=\"movie_content\">";
        echo "<span>$description</span>";
        echo "</div>";
        echo "<div class=\"movie_footer\">";
        // Num of Likes | Hates
        $this->renderVotesNum();
        // Like | Hate       
        if( $can_vote ) {
            $this->renderVotesBtn();
        }
        echo "<div class=\"movie_posted_by\">Posted by <span class=\"movie_posted_by_user\">$posted_by</div>";
        echo "</div>";
        // Edit | Delete
        if( $can_edit_or_delete ) 
        {
            echo "<div class=\"movie_actions_per_movie\">";                                    

            // Edit movie
            $obj = array();
            $obj['movieid'] = $movieid;
            $obj['action'] = 'edit';
            $obj['title'] = 'Edit Movie';

            $movie_content = array();
            $movie_content['title'] = $title;
            $movie_content['description'] = $description;

            $onedit = 'javascript:resetMovieDialog(); setMovieDialogContent( '.htmlspecialchars( json_encode( $movie_content ), ENT_QUOTES, 'UTF-8' ).' ); showMovieDialog( '.json_encode( $obj ).' );';
            
            // Delete movie
            $obj = array();
            $obj['movieid'] = $movieid;
            $obj['action'] = 'delete';
            $obj['title'] = $title;
            
            $ondelete = 'javascript:confirmMovieDeletion( '.htmlspecialchars( json_encode( $obj ), ENT_QUOTES, 'UTF-8' ).' );';
            
            echo "<div class=\"dropdown\">";
            echo "<button class=\"dropdown-btn\" type=\"button\" data-toggle=\"dropdown\">Actions";
            echo "<span class=\"caret\"></span></button>";
            echo "<ul class=\"dropdown-menu\">";
            echo "<li id=\"edit\" title=\"Edit Movie\"><a href='{$onedit}'>Edit</a></li>";
            echo "<li id=\"delete\" title=\"Delete Movie\"><a href='{$ondelete}'>Delete</a></li>";
            echo "</ul>";
            echo "</div>";

            echo "</div>";
        }
        echo "</div>";
    }
}

?>
