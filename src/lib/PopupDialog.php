<?php

class PopupDialog
{
    static function getConfirmDialogHtml( $msg )
    {
        $html = '';
        $html .= '<div class="popup-dialog-container">';
        $html .= '<div class="confirm_message">'.$msg.'</div>';
        $html .= '</div>';

        return $html;
    }

    static function getMovieDialogHtml( $title = '', $description = '' )
    {
        $html = '';
        $html .= '<div class="popup-dialog-container">';
        $html .= '<form id="popup-dialog-form">';
        $html .= '<div class="field"><input type="text" id="title" name="title" placeholder="Title" value="'.$title.'"/></div>';
        $html .= "<div class=\"invalid_message\"></div>\n";
        $html .= '<div class="field"><textarea id="description" name="description" placeholder="Description" rows="5" cols="30">'.$description.'</textarea></div>';
        $html .= "<div class=\"invalid_message\"></div>\n";
        $html .= '</form>';
        $html .= '</div>';

        return $html;
    }

    static function getReviewDialogHtml()
    {
        $html = '';
        $html .= '<div class="popup-dialog-container">';
        $html .= '<form id="popup-dialog-form">';
        $html .= '<div class="field"><textarea id="review" name="review" placeholder="Review" rows="5" cols="30"></textarea></div>';
        $html .= "<div class=\"invalid_message\"></div>\n";
        $html .= '</form>';
        $html .= '</div>';

        return $html;
    }
}

?>