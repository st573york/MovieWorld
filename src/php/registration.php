<?php

require_once('lib/Page.php');

class Registration extends Page
{
    function __construct()
    {
        parent::__construct( _("Registration") );
    }

    function head()
    {
        echo "<link rel=\"stylesheet\" href=\"/css/action.css\">\n";
    }

    function body()
    {
        echo "<div class=\"main\">\n";
        echo "<form action=\"\" method=\"POST\">\n";
        echo "<div class=\"action\">Registration</div>\n";
        echo "<div class=\"movies\"><a href='/'>Movies</a></div>\n";
        echo "<div class=\"field\"><input type=\"text\" name=\"username\" placeholder=\"Username\"/></div>\n";
        echo "<div class=\"field\"><input type=\"password\" name=\"password\" placeholder=\"Password\"></div>\n";
        echo "<div class=\"field\"><input type=\"text\" name=\"email\" placeholder=\"Email Address\"></div>\n";
        echo "<div class=\"field\"><input type=\"submit\" name=\"submit\" value=\"Register\"></div>\n";
        echo "</form>\n";

        if( isset( $_POST['username'] ) )
        {
            $values = array();
            $values['username'] = $_POST['username'];
            $values['password'] = md5( $_POST['password'] );
            $values['email'] = $_POST['email'];
            
            $user_dao = new UserDao;

            if( strlen( $values['username'] ) && 
                strlen( $values['password'] ) && 
                strlen( $values['email'] ) )
            {
                if( $user_dao->getByUsername( $values ) ) {
                    echo "<div class=\"action_message\">                                                                                                                                                                               
                          <span class='message'>Username is already in use!<br />Click <a href='/?login'>here</a> to login</span>                                                                                                                                 
                          </div>";
                }
                else if( $user_dao->getByEmail( $values ) ) {
                    echo "<div class=\"action_message\">                                                                                                                                                                               
                          <span class='message'>Email address is already in use!</span>                                                                                                                                 
                          </div>";
                }
                else if( $user_dao->insert( $values ) )
                {
                    echo "<div class=\"action_message\">                                                                                                                                                                               
                          <span class='message'>You have successfully registered!<br />Click <a href='/?login'>here</a> to login</span>                                                                                                                                 
                          </div>";
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
