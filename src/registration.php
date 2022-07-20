<?php
    require('database/db.php');
    require('dao/UserDao.php');

    open_db();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Registration</title>
    <link rel="icon" type="image/x-icon" href="/images/movies-icon.jpeg">
    
    <link rel="stylesheet" href="css/action.css">
</head>
<body>
    <div class="main"> 
        <form action="" method="POST">
            <div class="action">Registration</div>
            <div class="movies"><a href='/'>Movies</a></div>
            <div class="field"><input type="text" name="username" placeholder="Username"/></div>
            <div class="field"><input type="password" name="password" placeholder="Password"></div>
            <div class="field"><input type="text" name="email" placeholder="Email Address"></div>
            <div class="field"><input type="submit" name="submit" value="Register"></div>
        </form>

<?php            
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
                      <span class='message'>Username is already in use!<br />Click <a href='/login.php'>here</a> to login</span>                                                                                                                                 
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
                      <span class='message'>You have successfully registered!<br />Click <a href='/login.php'>here</a> to login</span>                                                                                                                                 
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
?>

    </div>
</body>
</html>
