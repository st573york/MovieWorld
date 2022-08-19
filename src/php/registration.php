<?php

require_once('lib/Page.php');

class Registration extends Page
{
    function __construct()
    {
        parent::__construct( _("Registration") );

        $this->setBootstrap();
    }

    function head()
    {
        echo "<link rel=\"stylesheet\" href=\"/css/main.css\">\n";
        echo "<link rel=\"stylesheet\" href=\"/css/user.css\">\n";
    }

    function body()
    {
        $success = false;
        $username_error = $password_error = $email_error = '';

        $username = ( isset( $_POST['username'] ) )? $_POST['username'] : '';
        $password = ( isset( $_POST['password'] ) )? $_POST['password'] : '';
        $email = ( isset( $_POST['email'] ) )? $_POST['email'] : '';

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {            
            $user_dao = new UserDao;

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

            if( !strlen( $password ) ) {
                $password_error = "Password is a required field";
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
                empty( $password_error ) && 
                empty( $email_error ) )
            {
                $values = array();
                $values['username'] = $username;
                $values['password'] = md5( $password );
                $values['email'] = $email;

                $success = $user_dao->insert( $values );

                $username = $password = $email = '';
            }
        }

        echo "<div class=\"main\">\n";
        echo "<form action=\"\" method=\"POST\">\n";
        echo "<div class=\"action\">Registration</div>\n";
        echo "<div class=\"movies\"><a href='/'>Movies</a></div>\n";
        $class = ( !empty( $username_error ) )? 'class="invalid"' : '';
        echo "<div class=\"field\"><input type=\"text\" name=\"username\" autocomplete=\"off\" placeholder=\"Username\" value=\"$username\" $class/></div>\n";
        if( !empty( $username_error ) ) {
            echo "<div class=\"invalid_message\">$username_error</div>\n";
        }
        $class = ( !empty( $password_error ) )? 'class="invalid"' : '';
        echo "<div class=\"field\"><input type=\"password\" name=\"password\" placeholder=\"Password\" value=\"$password\" $class/></div>\n";
        if( !empty( $password_error ) ) {
            echo "<div class=\"invalid_message\">$password_error</div>\n";
        }
        $class = ( !empty( $email_error ) )? 'class="invalid"' : '';
        echo "<div class=\"field\"><input type=\"text\" name=\"email\" autocomplete=\"off\" placeholder=\"Email Address\" value=\"$email\" $class/></div>\n";
        if( !empty( $email_error ) ) {
            echo "<div class=\"invalid_message\">$email_error</div>\n";
        }
        echo "<div class=\"button\">\n";
        echo "<button class=\"btn-primary\" type=\"submit\">Register</button>\n";
        echo "</div>\n";
        echo "</form>\n";
        if( $success ) {
            echo "<div class=\"action_message\">                                                                                                                                                                               
                  <span class='success_message'>You have successfully registered!<br />Click <a href='/?login'>here</a> to login</span>                                                                                                                                 
                  </div>";
        }
        echo "</div>\n";
    }
}
