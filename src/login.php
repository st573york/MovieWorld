<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="/images/movies-icon.jpeg">
    
    <link rel="stylesheet" href="css/action.css">
</head>
<body>
    <div class="main">
        <form action="/" method="POST">
            <div class="action">Login</div>
            <div class="movies"><a href='/'>Movies</a></div>
            <div class="field"><input type="text" name="username" placeholder="Username"/></div>
            <div class="field"><input type="password" name="password" placeholder="Password"></div>
            <div class="field"><input type="submit" name="submit" value="Login"></div>
        </form>

<?php
    $warning_msg = '';
    if( isset( $login_denied ) && $login_denied ) {
        $warning_msg = "The credentials you entered were invalid<br />Please re-enter them and try again";
    }

    if( $warning_msg )
    {   
        echo "<div class=\"action_message\">";
        echo "<span class='message'>$warning_msg</span>";
        echo "</div>";
    }
?>

    </div>
</body>
</html>