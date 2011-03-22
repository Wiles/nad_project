<?php
require $_SERVER['DOCUMENT_ROOT'].'/../includes/database.php';

include $_SERVER['DOCUMENT_ROOT'].'/../templates/header.php';
$error = "";
if( isset($_POST['email']) && isset($_POST['password']))
{
    //attempt login

}
?>

<html>
    <head>
        <title>Setbook - Login</title>
    </head>
    <body>
        <form method="POST" action ="<?=$_SERVER['PHP_SELF']?>">
            <div id="error"><?php echo $error?></div>
            E-mail:<br />
            <input type="text" name="email" /><br />
            Password:<br />
            <input type="password" name="password" /><br /><br />
            <input type="submit" value="Login!" />
        </form>
    </body>
</html>

<?php include $_SERVER['DOCUMENT_ROOT'].'/../templates/footer.php'; ?>
