<?php

class MovieSort
{
    function renderHtml()
    {
        // Sort by Likes, Hates, Dates
        echo "<div class=\"movie_sort\">";
        echo "<span>Sort by:</span><br/>";
        $sort_by_likes = 'javascript:sortMovies( { "action": "sort_by_likes" } )';
        echo "<span class=\"sort_btn\"><a href='{$sort_by_likes}'>Likes</a></span><br/>";
        $sort_by_hates = 'javascript:sortMovies( { "action": "sort_by_hates" } )';
        echo "<span class=\"sort_btn\"><a href='{$sort_by_hates}'>Hates</a></span><br/>";
        $sort_by_comments = 'javascript:sortMovies( { "action": "sort_by_comments" } )';
        echo "<span class=\"sort_btn\"><a href='{$sort_by_comments}'>Comments</a></span><br/>";
        $sort_by_date = 'javascript:sortMovies( { "action": "sort_by_date" } )';
        echo "<span class=\"sort_btn\"><a href='{$sort_by_date}'>Date</a></span><br/>";
        echo "</div>";
    }
}

?>