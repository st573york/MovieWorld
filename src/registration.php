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
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <form action="" method="POST">
        <h1>Registration</h1>
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
            if( $user_dao->insert( $values ) )
            {
                echo "<div>                                                                                                                                                                               
                  <h3>You have successfully registered!</h3>                                                                                                                                              
                  <p>Click <a href='/'>here</a> to login</p>                                                                                                                                              
                  </div>";
            }
        }
        else
        {
            echo "<div>
                  <h3>All the fields are required</h3>
                  <p>Click <a href='/'>here</a> to go back to login</p>
                  </div>";
        }
    }
?>

</body>
</html>
