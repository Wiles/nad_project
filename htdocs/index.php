<?php
require $_SERVER['DOCUMENT_ROOT'].'/../includes/database.php';
$page_title = "Login";
$error = "&nbsp;";
$email = isset($_POST['email'])?$_POST['email']:"";

session_start();

//Check if they are already logged in
if( isset($_SESSION['user_id']) )
{
    header( 'Location: /profile.php');
}

if( isset($_POST['email']) && isset($_POST['password']))
{
    //attempt login

    $conn = mysql_connect($db_host, $db_user, $db_password)
        or die( "Database error: " . mysql_error());
    mysql_select_db("nadproject")
        or die ("Database not found.");

    $query = "SELECT id,suspended FROM users WHERE email='".mysql_real_escape_string($_POST['email'])."' AND password='".hashPassword($_POST['password'])."' LIMIT 1";

    $result = mysql_query($query);
    
    if( !$result || mysql_num_rows($result)== 0)
    {
        //login failed
        $error = "Invalid email or password.";
    }
    else
    {
        //login
        $row = mysql_fetch_row($result);

        //check suspention
        if( $row[1] == '0')
        {
            session_start();
            $_SESSION['user_id'] = $row[0];
            header( 'Location: /profile.php');
        }
        else
        {
            $error = "Account suspended.";
        }
    }
    $error = "Invalid email or password.";
}
?>
<html>
    <head>
        <title>Setbook - <?php echo $page_title; ?></title>
	<link rel="stylesheet" type="text/css" href="/style.css" />
    </head>
    <body>
<?php include $_SERVER['DOCUMENT_ROOT'].'/../templates/public_header.html'; ?>
        <form method="POST" action ="<?=$_SERVER['PHP_SELF']?>">
            <div class="error"><?php echo $error; ?></div>
            E-mail:<br />
            <input type="text" name="email" value ="<?php echo $email; ?>" /><br />
            Password:<br />
            <input type="password" name="password" /><br /><br />
            <input type="submit" value="Login!" />
        </form>
<?php include $_SERVER['DOCUMENT_ROOT'].'/../templates/public_footer.html'; ?>
    </body>
</html>
