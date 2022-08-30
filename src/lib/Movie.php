<?php

class Movie
{
    var $data = array();

    function __construct( $data )
    {
        $this->data = $data;

        $this->setLike();
        $this->setHate();
        $this->setReview();
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
        $this->data['like'] = "<span class=\"$class\"><a href='{$href}' class=\"vote\">Like</a></span>";
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
        $this->data['hate'] = "<span class=\"$class\"><a href='{$href}' class=\"vote\">Hate</a></span>";
    }

    function setReview()
    {
        $obj = array();
        $obj['movieid'] = $this->data['movieid'];
        $obj['action'] = 'add';
        $obj['title'] = 'Add Review';
        $obj['html'] = getReviewDialogHtml();      

        $this->data['review'] = 'showReviewDialog( '.json_encode( $obj ).' );';
    }

    function renderVoteUsers( $values )
    {
        echo "<div class=\"dropdown-menu\">\n";
        $last = end( $values );
        foreach( $values as $key => $val ) 
        {
            $username = $val['username'];
            
            $class = ( $last == $val )? 'last' : '';
            echo "<div class=\"user_vote_data $class\">$username</div>\n";
        }
        echo "</div>\n";
    }

    function renderVotesNum()
    {
        $movieid = $this->data['movieid'];

        $movie_vote_dao = new MovieVoteDao;

        echo "<div id=\"movie_votes_num_{$movieid}\" class=\"movie_votes_num\">\n";

        $values = array();

        $movie_vote_dao->getUsersByLike( $movieid, $values );
        $users_like_num = short_number( $movie_vote_dao->getLikeVotesNum( $movieid ) );

        $users_list = ( !empty( $values ) && $_SESSION['logged_in'] );

        // Users who have liked a movie
        $class = ( $this->data['liked'] )? 'liked' : '';
        echo "<div class=\"movie_likes $class\">";
        echo ( $users_list )? 
            "\n<a href=\"\" class=\"dropdown-link only-text\" data-toggle=\"dropdown\"><span class=\"movie_votes_text\">$users_like_num likes</span></a>\n" : "$users_like_num likes";
        if( $users_list ) {
            $this->renderVoteUsers( $values );
        }
        echo "</div>\n";

        echo "<div class=\"num_separator\">|</div>\n";

        $values = array();

        $movie_vote_dao->getUsersByHate( $movieid, $values );
        $users_hate_num = short_number( $movie_vote_dao->getHateVotesNum( $movieid ) );

        $users_list = ( !empty( $values ) && $_SESSION['logged_in'] );

        // Users who have hated a movie
        $class = ( $this->data['hated'] )? 'hated' : '';
        echo "<div class=\"movie_hates $class\">\n";
        echo ( $users_list )? 
            "\n<a href=\"\" class=\"dropdown-link only-text\" data-toggle=\"dropdown\"><span class=\"movie_votes_text\">$users_hate_num hates</span></a>\n" : "$users_hate_num hates";
        if( $users_list ) {
            $this->renderVoteUsers( $values );
        }
        echo "</div>\n";                

        echo "</div>\n";
    }

    function renderReviewsNum()
    {
        $movieid = $this->data['movieid'];

        echo "<div id=\"movie_reviews_num_{$movieid}\" class=\"movie_reviews_num\">\n";

        echo "<div class=\"num_separator\">|</div>\n";

        $values = array();
        $movie_review_dao = new MovieReviewDao;

        $movie_review_dao->getReviews( $movieid, $values );
        $reviews_num = short_number( $movie_review_dao->getReviewsNum( $movieid ) );

        $reviews_list = ( !empty( $values ) && $_SESSION['logged_in'] );

        // Users who have added a review for a movie
        echo "<div class=\"movie_reviews\">";
        echo ( $reviews_list )? 
            "\n<a href=\"\" class=\"dropdown-link only-text\" data-toggle=\"dropdown\">$reviews_num reviews</a>\n" : "$reviews_num reviews";    
        if( $reviews_list )
        {
            echo "<div class=\"dropdown-menu\">\n";
            $last = end( $values );
            foreach( $values as $key => $val ) 
            {
                $creation_date = get_time_ago( $val['creation_date'] );
                $username = $val['username'];
                $review = $val['review'];
                
                $class = ( $last == $val )? 'last' : '';
                echo "<div class=\"review_data $class\">\n";
                echo "<div class=\"top_panel\">By $username - $creation_date</div>\n";
                echo "<div class=\"bottom_panel $class\">$review</div>\n";
                echo "</div>\n";
            }
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

    function renderReviewBtn()
    {
        echo "<div id=\"movie_review_btn_".$this->data['movieid']."\" class=\"movie_review_btn\">\n"; 
        echo "<span class=\"btn_separator\">|</span>\n";
        echo "<span id=\"review_btn_".$this->data['movieid']."\" class=\"review_btn\" onclick='".$this->data['review']."' title=\"Add Review\"></span>\n";
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

        $can_vote_review = ( !$isadmin && !$myself && $_SESSION['logged_in'] );
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
        echo "<div class=\"movie_num_container\">\n";
        // Num of Likes | Hates
        $this->renderVotesNum();
        // Num of reviews
        $this->renderReviewsNum();
        echo "</div>\n";
        // Like | Hate | Review      
        if( $can_vote_review ) 
        {
            echo "<div class=\"movie_btn_container\">\n";
            $this->renderVotesBtn();
            $this->renderReviewBtn();
            echo "</div>\n";
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
            $obj['html'] = getMovieDialogHtml( $title, $description );

            $onedit = 'javascript:showMovieDialog( '.json_encode( $obj ).' );';
            
            // Delete movie            
            $obj = array();
            $obj['movieid'] = $movieid;
            $obj['action'] = 'delete';
            $obj['html'] = getConfirmDialogHtml( "Movie '$title' will be deleted. Are you sure?" ); 
            
            $ondelete = 'javascript:confirmMovieDeletion( '.htmlspecialchars( json_encode( $obj ), ENT_QUOTES, 'UTF-8' ).' );';
            
            echo "<div class=\"dropdown\">\n";
            echo "<button class=\"dropdown-btn\" type=\"button\" data-toggle=\"dropdown\">Actions";
            echo "<span class=\"caret-down\"></span>";
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
