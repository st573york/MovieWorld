<?php

class Dialog
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
        $html .= '<div class="field"><textarea id="description" name="description" placeholder="Description" rows="5" cols="30">'.$description.'</textarea></div>';
        $html .= '<div class="error_message"></div>';
        $html .= '</form>';
        $html .= '</div>';

        return $html;
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
}

?>