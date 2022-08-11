<?php

require_once('lib/Page.php');

class Login extends Page
{
    var $login_denied = 0;

    function __construct( $login_denied )
    {
        parent::__construct( _("Login") );

        $this->login_denied = $login_denied;
    }

    function head()
    {
        echo "<link rel=\"stylesheet\" href=\"/css/main.css\">\n";
        echo "<link rel=\"stylesheet\" href=\"/css/user.css\">\n";
    }

    function body()
    {
        echo "<div class=\"main\">\n";
        echo "<form action=\"/\" method=\"POST\">\n";
        echo "<div class=\"action\">Login</div>\n";
        echo "<div class=\"movies\"><a href='/'>Movies</a></div>\n";
        echo "<div class=\"field\"><input type=\"text\" name=\"username\" autocomplete=\"off\" placeholder=\"Username\"/></div>\n";
        echo "<div class=\"field\"><input type=\"password\" name=\"password\" placeholder=\"Password\"></div>\n";
        echo "<div class=\"field\"><input type=\"submit\" name=\"submit\" value=\"Login\"></div>\n";
        echo "</form>\n";
        
        $warning_msg = '';
        if( $this->login_denied ) {
            $warning_msg = "The credentials you entered were invalid<br />Please re-enter them and try again";
        }

        if( $warning_msg )
        {   
            echo "<div class=\"action_message\">";
            echo "<span class='message'>$warning_msg</span>";
            echo "</div>";
        }

        echo "</div>\n";
    }
}