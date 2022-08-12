<?php

class Movie
{
    var $data = array();

    function __construct( $data )
    {
        $this->data = $data;

        $this->setLike();
        $this->setHate();
        $this->setComment();
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

        $href = 'javascript:processVote( '.json_encode( $obj ).' );';
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

        $href = 'javascript:processVote( '.json_encode( $obj ).' );';
        $this->data['hate'] = "<span class=\"$class\"><a href='{$href}'>Hate</a></span>";
    }

    function setComment()
    {
        $obj = array();
        $obj['movieid'] = $this->data['movieid'];
        $obj['action'] = 'add';
        $obj['title'] = 'Add Comment';
        $obj['html'] = Dialog::getCommentDialogHtml();      

        $this->data['comment'] = 'showCommentDialog( '.json_encode( $obj ).' );';
    }

    function renderTable( $data, $header = array() )
    {
        $has_header = ( !empty( $header ) );

        echo "<table class=\"list\">\n";
        echo "<thead>\n";
        if( $has_header )
        {
            echo "<tr>\n";
            foreach( $header as $th ) {
                echo "<th>$th</th>\n";
            }
            echo "</tr>\n";
        }
        echo "</thead>\n";
        echo "<tbody>\n";
        $odd = false;
        foreach( $data as $row ) 
        {
            $class = '';
            if( $has_header ) {
                $class = $odd? 'odd' : 'even';
            }

            echo "<tr class=\"$class\">\n";
            foreach( $row as $key => $val ) 
            {
                $class = ( !$has_header )? 'single_item' : '';

                echo "<td class=\"$class\">$val</td>\n";
            }
            echo "</tr>\n";

            $odd = !$odd;
        }
        echo "</tbody>\n";
        echo "</table>\n";
    }

    function renderVotesNum()
    {
        $movieid = $this->data['movieid'];

        $movie_vote_dao = new MovieVoteDao;

        echo "<div id=\"movie_votes_num_{$movieid}\" class=\"movie_votes_num\">\n";

        $values = array();
        $movie_vote_dao->getUsersByLike( $movieid, $values );

        $users_list = ( !empty( $values ) && $_SESSION['logged_in'] );

        // Users who have liked a movie
        echo "<div class=\"movie_likes\">\n"; 
        $class = ( $this->data['liked'] )? 'liked' : '';
        echo "<div id=\"movie_likes_{$movieid}\" class=\"$class\">\n";
        $class = ( $users_list )? 'movie_likes_text' : '';
        echo "<span id=\"movie_likes_text_{$movieid}\" class=\"$class\">".$movie_vote_dao->getLikeVotesNum( $movieid )." likes</span>\n";
        echo "</div>\n";
        if( $users_list )
        {
            echo "<div id=\"users_like_{$movieid}\" class=\"items_list users_list\">\n";
            $this->renderTable( $values );
            echo "</div>\n";
        }
        echo "</div>\n";

        echo "<div class=\"num_separator\">|</div>\n";

        $values = array();
        $movie_vote_dao->getUsersByHate( $movieid, $values );

        $users_list = ( !empty( $values ) && $_SESSION['logged_in'] );

        // Users who have hated a movie
        echo "<div class=\"movie_hates\">\n";
        $class = ( $this->data['hated'] )? 'hated' : '';
        echo "<div id=\"movie_hates_{$movieid}\" class=\"$class\">\n";
        $class = ( $users_list )? 'movie_hates_text' : '';
        echo "<span id=\"movie_hates_text_{$movieid}\" class=\"$class\">".$movie_vote_dao->getHateVotesNum( $movieid )." hates</span>\n";
        echo "</div>\n";        
        if( $users_list )
        {
            echo "<div id=\"users_hate_{$movieid}\" class=\"items_list users_list\">\n";
            $this->renderTable( $values );
            echo "</div>\n";
        }
        echo "</div>\n";

        echo "</div>\n";
    }

    function renderCommentsNum()
    {
        $movieid = $this->data['movieid'];

        echo "<div id=\"movie_comments_num_{$movieid}\" class=\"movie_comments_num\">\n";

        echo "<div class=\"num_separator\">|</div>\n";

        $values = array();
        $movie_comment_dao = new MovieCommentDao;

        $movie_comment_dao->getComments( $movieid, $values );

        $comments_list = ( !empty( $values ) && $_SESSION['logged_in'] );

        // Users who have commented a movie
        echo "<div class=\"movie_comments\">\n";
        echo "<div id=\"movie_comments_{$movieid}\">\n";
        $class = ( $comments_list )? 'movie_comments_text' : '';
        echo "<span id=\"movie_comments_text_{$movieid}\" class=\"$class\">".$movie_comment_dao->getCommentsNum( $movieid )." comments</span>\n";
        echo "</div>\n";        
        if( $comments_list )
        {
            echo "<div id=\"comments_{$movieid}\" class=\"items_list comments_list\">\n";
            $this->renderTable( $values, array( 'Time', 'User', 'Comment' ) );
            echo "</div>\n";
        }
        echo "</div>\n";
        
        echo "</div>\n";
    }

    function renderVotesBtn()
    {
        echo "<div id=\"movie_votes_btn_".$this->data['movieid']."\" class=\"movie_votes_btn\">\n"; 
        echo "<span id=\"like_btn_".$this->data['movieid']."\" class=\"like_btn\">".$this->data['like']."</span>\n";
        echo "<span class=\"btn_separator\">|</span>\n";
        echo "<span id=\"hate_btn_".$this->data['movieid']."\" class=\"hate_btn\">".$this->data['hate']."</span>\n";
        echo "</div>\n";
    }

    function renderCommentBtn()
    {
        echo "<div id=\"movie_comment_btn_".$this->data['movieid']."\" class=\"movie_comment_btn\">\n"; 
        echo "<span class=\"btn_separator\">|</span>\n";
        echo "<span id=\"comment_btn_".$this->data['movieid']."\" class=\"comment_btn\" onclick='".$this->data['comment']."' title=\"Add Comment\"></span>\n";
        echo "</div>\n";
    }

    function renderHtml()
    {
        $movieid = $this->data['movieid'];
        $isadmin = ( $_SESSION['username'] == 'admin' );
        $myself = ( $this->data['posted_by'] == $_SESSION['username'] );  
        $title = htmlspecialchars( $this->data['title'], ENT_QUOTES, 'UTF-8' );
        $posted = $this->data['posted'];
        $description = htmlspecialchars( $this->data['description'], ENT_QUOTES, 'UTF-8' );
        $posted_by = ( $myself )? "You" : $this->data['posted_by'];

        $can_vote_comment = ( !$isadmin && !$myself && $_SESSION['logged_in'] );
        $can_edit_or_delete = ( $isadmin || $myself );

        // Movie Data
        echo "<div id=\"movie_{$movieid}\" class=\"movie_data\">\n";
        echo "<div class=\"movie_data_top\">\n";
        echo "<span class=\"movie_title\">$title</span>\n";
        echo "<span class=\"movie_posted\">Posted $posted</span>\n"; 
        echo "</div>\n";           
        echo "<div class=\"movie_data_middle\">\n";
        echo "<span>$description</span>\n";
        echo "</div>\n";
        echo "<div class=\"movie_data_bottom\">\n";
        // Num of Likes | Hates
        $this->renderVotesNum();
        // Num of comments
        $this->renderCommentsNum();
        // Like | Hate | Comment      
        if( $can_vote_comment ) 
        {
            $this->renderVotesBtn();
            $this->renderCommentBtn();
        }
        echo "<div class=\"movie_posted_by\">Posted by <span class=\"movie_posted_by_user\">$posted_by</div>\n";
        echo "</div>\n";
        // Edit | Delete
        if( $can_edit_or_delete ) 
        {
            echo "<div class=\"movie_data_actions\">\n";                                    

            // Edit movie
            $obj = array();
            $obj['movieid'] = $movieid;
            $obj['action'] = 'edit';
            $obj['title'] = 'Edit Movie';
            $obj['html'] = Dialog::getMovieDialogHtml( $title, $description );

            $onedit = 'javascript:showMovieDialog( '.json_encode( $obj ).' );';
            
            // Delete movie            
            $obj = array();
            $obj['movieid'] = $movieid;
            $obj['action'] = 'delete';
            $obj['html'] = Dialog::getConfirmDialogHtml( "Movie '$title' will be deleted. Are you sure?" ); 
            
            $ondelete = 'javascript:confirmMovieDeletion( '.htmlspecialchars( json_encode( $obj ), ENT_QUOTES, 'UTF-8' ).' );';
            
            echo "<div class=\"dropdown\">\n";
            echo "<button class=\"dropdown-btn\" type=\"button\" data-toggle=\"dropdown\">Actions";
            echo "<span class=\"caret\"></span>";
            echo "</button>\n";
            echo "<ul class=\"dropdown-menu\">\n";
            echo "<li id=\"edit\" title=\"Edit Movie\"><a href='{$onedit}'>Edit</a></li>\n";
            echo "<li id=\"delete\" title=\"Delete Movie\"><a href='{$ondelete}'>Delete</a></li>\n";
            echo "</ul>\n";
            echo "</div>\n";

            echo "</div>\n";
        }
        echo "</div>\n";
    }
}

?>
