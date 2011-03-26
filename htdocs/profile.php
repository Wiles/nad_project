<?php
    session_start();
    $user = isset($_SESSION['user_id'])?$_SESSION['user_name']:"";
    if( $user == "" )
    {
        header( 'Location: /') ;
    }
    else
    {
        //echo "Profile: ".$user;
    }
?>
<html>
    <head>
        <title>Setbook - <?php echo $user; ?></title>
	<link rel="stylesheet" type="text/css" href="/style.css" />
    </head>
    <body>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/../templates/private_header.html'; ?>
        <form method="POST" action ="<?=$_SERVER['PHP_SELF']?>">
            <div id="error"><?php echo $error; ?></div>
            E-mail:<br />
            <input type="text" name="email" value ="<?php echo $email; ?>" /><br />
            Password:<br />
            <input type="password" name="password" /><br /><br />
            <input type="submit" value="Login!" />
        </form>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/../templates/private_footer.html'; ?>
    </body>
</html>