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

    static function getCommentDialogHtml()
    {
        $html = '';
        $html .= '<div class="popup-dialog-container">';
        $html .= '<form id="popup-dialog-form">';
        $html .= '<div class="field"><textarea id="comment" name="comment" placeholder="Comment" rows="5" cols="30"></textarea></div>';
        $html .= '<div class="error_message"></div>';
        $html .= '</form>';
        $html .= '</div>';

        return $html;
    }

    static function getMovieDialogHtml( $title = '', $description = '' )
    {
        $html = '';
        $html .= '<div class="popup-dialog-container">';
        $html .= '<form id="popup-dialog-form">';
        $html .= '<div class="field"><input type="text" id="title" name="title" placeholder="Title" value="'.$title.'"/></div>';
        $html .= '<div class="field"><textarea id="description" name="description" placeholder="Description" rows="5" cols="30">'.htmlspecialchars( $description, ENT_QUOTES, 'UTF-8' ).'</textarea></div>';
        $html .= '<div class="error_message"></div>';
        $html .= '</form>';
        $html .= '</div>';

        return $html;
    }

    static function getConfirmDialogHtml( $msg )
    {
        $html = '';
        $html .= '<div class="popup-dialog-container">';
        $html .= '<div class="confirm_message">'.$msg.'</div>';
        $html .= '</div>';

        return $html;
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

    function setComment()
    {
        $obj = array();
        $obj['movieid'] = $this->data['movieid'];
        $obj['action'] = $obj['type'] = 'comment';
        $obj['title'] = 'Add Comment';  
        $obj['html'] = $this->getCommentDialogHtml();      

        $this->data['comment'] = 'showDialog( '.json_encode( $obj ).' );';
    }

    function renderTable( $data, $header = array() )
    {
        $has_header = ( !empty( $header ) );

        echo "<table class=\"list\">";
        echo "<thead>";
        if( $has_header )
        {
            echo "<tr>";
            foreach( $header as $th ) {
                echo "<th>$th</th>";
            }
            echo "</tr>";
        }
        echo "</thead>";
        echo "<tbody>";
        $odd = false;
        foreach( $data as $row ) 
        {
            $class = '';
            if( $has_header ) {
                $class = $odd? 'odd' : 'even';
            }

            echo "<tr class=\"$class\">";
            foreach( $row as $key => $val ) 
            {
                $class = ( !$has_header )? 'single_item' : '';

                echo "<td class=\"$class\">$val</td>";
            }
            echo "</tr>";

            $odd = !$odd;
        }
        echo "</tbody>";
        echo "</table>";
    }

    function renderVotesNum()
    {
        $movieid = $this->data['movieid'];

        $movie_vote_dao = new MovieVoteDao;

        echo "<div id=\"movie_votes_num_{$movieid}\" class=\"movie_votes_num\">";

        $values = array();
        $movie_vote_dao->getUsersByLike( $movieid, $values );

        $users_list = ( !empty( $values ) && $_SESSION['logged_in'] );

        // Users who have liked a movie
        echo "<div class=\"movie_likes\">"; 
        $class = ( $this->data['liked'] )? 'liked' : '';
        echo "<div id=\"movie_likes_{$movieid}\" class=\"$class\">";
        $class = ( $users_list )? 'movie_likes_text' : '';
        echo "<span id=\"movie_likes_text_{$movieid}\" class=\"$class\">".$movie_vote_dao->getLikeVotesNum( $movieid )." likes</span>";
        echo "</div>";
        if( $users_list )
        {
            echo "<div id=\"users_like_{$movieid}\" class=\"items_list\">";
            $this->renderTable( $values );
            echo "</div>";
        }
        echo "</div>";

        echo "<div class=\"separator\">|</div>";

        $values = array();
        $movie_vote_dao->getUsersByHate( $movieid, $values );

        $users_list = ( !empty( $values ) && $_SESSION['logged_in'] );

        // Users who have hated a movie
        echo "<div class=\"movie_hates\">";
        $class = ( $this->data['hated'] )? 'hated' : '';
        echo "<div id=\"movie_hates_{$movieid}\" class=\"$class\">";
        $class = ( $users_list )? 'movie_hates_text' : '';
        echo "<span id=\"movie_hates_text_{$movieid}\" class=\"$class\">".$movie_vote_dao->getHateVotesNum( $movieid )." hates</span>";
        echo "</div>";        
        if( $users_list )
        {
            echo "<div id=\"users_hate_{$movieid}\" class=\"items_list\">";
            $this->renderTable( $values );
            echo "</div>";
        }
        echo "</div>";

        echo "</div>";
    }

    function renderCommentsNum()
    {
        $movieid = $this->data['movieid'];

        echo "<div id=\"movie_comments_num_{$movieid}\" class=\"movie_comments_num\">";

        echo "<div class=\"separator\">|</div>";

        $values = array();
        $movie_comment_dao = new MovieCommentDao;

        $movie_comment_dao->getComments( $movieid, $values );

        $comments_list = ( !empty( $values ) && $_SESSION['logged_in'] );

        // Users who have commented a movie
        echo "<div class=\"movie_comments\">";
        echo "<div id=\"movie_comments_{$movieid}\">";
        $class = ( $comments_list )? 'movie_comments_text' : '';
        echo "<span id=\"movie_comments_text_{$movieid}\" class=\"$class\">".$movie_comment_dao->getCommentsNum( $movieid )." comments</span>";
        echo "</div>";        
        if( $comments_list )
        {
            echo "<div id=\"comments_{$movieid}\" class=\"items_list\">";
            $this->renderTable( $values, array( 'Time', 'User', 'Comment' ) );
            echo "</div>";
        }
        echo "</div>";
        
        echo "</div>";
    }

    function renderVotesBtn()
    {
        echo "<div id=\"movie_votes_btn_".$this->data['movieid']."\" class=\"movie_votes_btn\">"; 
        echo "<span id=\"like_btn_".$this->data['movieid']."\" class=\"like_btn\">".$this->data['like']."</span>";
        echo "<span class=\"separator\">|</span>";
        echo "<span id=\"hate_btn_".$this->data['movieid']."\" class=\"hate_btn\">".$this->data['hate']."</span>";
        echo "</div>";
    }

    function renderCommentBtn()
    {
        echo "<div id=\"movie_comment_btn_".$this->data['movieid']."\" class=\"movie_comment_btn\">"; 
        echo "<span class=\"separator\">|</span>";
        echo "<span id=\"comment_btn_".$this->data['movieid']."\" class=\"comment_btn\" onclick='".$this->data['comment']."' title=\"Add Comment\"></span>";
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

        $can_vote_comment = ( !$isadmin && !$myself && $_SESSION['logged_in'] );
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
        // Num of comments
        $this->renderCommentsNum();
        // Like | Hate | Comment      
        if( $can_vote_comment ) 
        {
            $this->renderVotesBtn();
            $this->renderCommentBtn();
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
            $obj['type'] = 'movie';
            $obj['title'] = 'Edit Movie';
            $obj['html'] = $this->getMovieDialogHtml( $title, $description );

            $onedit = 'javascript:showDialog( '.json_encode( $obj ).' );';
            
            // Delete movie            
            $obj = array();
            $obj['movieid'] = $movieid;
            $obj['action'] = 'delete';
            $obj['title'] = 'Delete Movie';
            $obj['html'] = $this->getConfirmDialogHtml( "Movie '$title' will be deleted. Are you sure?" ); 
            
            $ondelete = 'javascript:confirmDeletion( '.htmlspecialchars( json_encode( $obj ), ENT_QUOTES, 'UTF-8' ).' );';
            
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
