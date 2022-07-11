<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Registration</title>
</head>
<body>
      
<?php
      require('dao/UserDao.php');

      if( isset( $_POST['username'] ) )
      {
          $values = array();
          $values['username'] = $_POST['username'];
          $values['email'] = $_POST['email'];
          $values['password'] = md5( $_POST['password'] );

          $user_dao = new UserDao;
          if( $user_dao->insert( $values ) )
          {
              echo "<div>
                    <h3>You have successfully registered.</h3>
                    <p>Click <a href='login.php'>here</a> to login</p>
                    </div>";
          }
          else
          {
              echo "<div>
                    <h3>Some fields are required</h3>
                    <p>Click <a href='registration.php'>here</a> to register again</p>
                    </div>";
          }
      }
      else
      {
?>
          <form action="" method="POST">
              <h1>Registration</h1>
              <input type="text" name="username" placeholder="Username"/>
              <input type="text" name="email" placeholder="Email Address">
              <input type="password" name="password" placeholder="Password">
              <input type="submit" name="submit" value="Register">
          </form>
<?php
      }
?>

</body>
</html>