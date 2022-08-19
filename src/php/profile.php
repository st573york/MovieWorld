<?php

require_once('lib/Page.php');

class Profile extends Page
{
    function __construct()
    {
        parent::__construct( _("Profile") );

        $this->setBootstrap();
    }

    function head()
    {
        echo "<link rel=\"stylesheet\" href=\"/css/main.css\">\n";
        echo "<link rel=\"stylesheet\" href=\"/css/user.css\">\n";
    }

    function body()
    {
        $username_error = $email_error = '';

        $values = array();
        $user_dao = new UserDao;

        $user_dao->getByID( $values );

        $username = ( isset( $_POST['username'] ) )? $_POST['username'] : $values['username'];
        $password = ( isset( $_POST['password'] ) )? $_POST['password'] : $values['password'];
        $email = ( isset( $_POST['email'] ) )? $_POST['email'] : $values['email'];

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {            
            if( strlen( $username ) ) 
            {
                if( strlen( trim( $username ) ) > 20 ) {
                    $username_error = 'Username must be less than 20 characters';
                }
                else if( !preg_match( '/^[a-zA-Z0-9_]+$/', $username ) ) {
                    $username_error = 'Username can only consist of letters, numbers and underscores';
                }
                else if( $user_dao->getByUsername( $username ) ) {
                    $username_error = 'Username is already in use';
                }
            }
            else {
                $username_error = "Username is a required field";
            }

            if( strlen( $email ) ) 
            {
                if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                    $email_error = 'Invalid email format';
                }
                else if( $user_dao->getByEmail( $email ) ) {
                    $email_error = 'Email address is already in use';
                }
            }
            else {
                $email_error = "Email is a required field";
            }

            if( empty( $username_error ) && 
                empty( $email_error ) )
            {
                $values = array();
                $values['username'] = $username;
                if( strlen( $password ) ) {
                    $values['password'] = md5( $password );
                }
                $values['email'] = $email;

                if( $user_dao->update( $values ) )
                {
                    // update session with new values
                    $_SESSION['username'] = $username;
                    if( strlen( $password ) ) {
                        $_SESSION['password'] = $password;
                    }

                    // go back to the login page
                    header( "Location: /" );
                }
            }
        }

        echo "<div class=\"main\">\n";
        echo "<form action=\"\" method=\"POST\">\n";
        echo "<div class=\"action\">Profile</div>\n";
        echo "<div class=\"movies\"><a href='/'>Movies</a></div>\n";
        $class = ( !empty( $username_error ) )? 'class="invalid"' : '';
        echo "<div class=\"field\"><input type=\"text\" name=\"username\" autocomplete=\"off\" placeholder=\"Username\" title=\"$username_error\" value=\"$username\" $class/></div>\n";
        if( !empty( $username_error ) ) {
            echo "<div class=\"invalid_message\">$username_error</div>\n";
        }
        echo "<div class=\"field\"><input type=\"password\" name=\"password\" placeholder=\"Password\"/></div>\n";
        $class = ( !empty( $email_error ) )? 'class="invalid"' : '';
        echo "<div class=\"field\"><input type=\"text\" name=\"email\" autocomplete=\"off\" placeholder=\"Email Address\" title=\"$email_error\" value=\"$email\" $class/></div>\n";
        if( !empty( $email_error ) ) {
            echo "<div class=\"invalid_message\">$email_error</div>\n";
        }
        echo "<div class=\"field\">\n";
        echo "<button class=\"btn-primary\" type=\"submit\">Save</button>\n";
        echo "</div>\n";
        echo "</form>\n";
        echo "</div>\n";
    }
}
