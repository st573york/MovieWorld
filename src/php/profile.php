<?php

require_once('lib/Page.php');

class Profile extends Page
{
    function __construct()
    {
        parent::__construct( _("Profile") );
    }

    function head()
    {
        echo "<link rel=\"stylesheet\" href=\"/css/action.css\">\n";
    }

    function body()
    {
        $values = array();
        $user_dao = new UserDao;

        $user_dao->getByID( $values );

        $username = $values['username'];
        $email = $values['email'];

        echo "<div class=\"main\">\n";
        echo "<form action=\"\" method=\"POST\">\n";
        echo "<div class=\"action\">Profile</div>\n";
        echo "<div class=\"movies\"><a href='/'>Movies</a></div>\n";
        echo "<div class=\"field\"><input type=\"text\" name=\"username\" placeholder=\"Username\" value=\"$username\"/></div>\n";
        echo "<div class=\"field\"><input type=\"password\" name=\"password\" placeholder=\"Password\"></div>\n";
        echo "<div class=\"field\"><input type=\"text\" name=\"email\" placeholder=\"Email Address\" value=\"$email\"></div>\n";
        echo "<div class=\"field\"><input type=\"submit\" name=\"submit\" value=\"Save\"></div>\n";
        echo "</form>\n";

        if( isset( $_POST['username'] ) )
        {
            $values = array();
            $values['username'] = $_POST['username'];
            if( strlen( $_POST['password'] ) ) {
                $values['password'] = md5( $_POST['password'] );
            }
            $values['email'] = $_POST['email'];
            
            if( strlen( $values['username'] ) && 
                strlen( $values['email'] ) )
            {
                if( $user_dao->getByUsername( $values ) ) {
                    echo "<div class=\"action_message\">                                                                                                                                                                               
                          <span class='message'>Username is already in use!</span>                                                                                                                                 
                          </div>";
                }
                else if( $user_dao->getByEmail( $values ) ) {
                    echo "<div class=\"action_message\">                                                                                                                                                                               
                          <span class='message'>Email address is already in use!</span>                                                                                                                                 
                          </div>";
                }
                else if( $user_dao->update( $values ) )
                {
                    // update session with new values
                    $_SESSION['username'] = $_POST['username'];
                    if( strlen( $_POST['password'] ) ) {
                        $_SESSION['password'] = $_POST['password'];
                    }

                    // go back to the login page
                    header( "Location: /" );
                }
            }
            else
            {
                echo "<div class=\"action_message\">                                                                                                                                                                               
                      <span class='message'>All the fields are required!</span>                                                                                                                                 
                      </div>";
            }
        }

        echo "</div>\n";
    }
}
