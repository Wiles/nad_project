<?php
require $_SERVER['DOCUMENT_ROOT'].'/../includes/database.php';
$page_title = "Invite";

session_start();
//Check if user is logged in
if(!isset($_SESSION['user_id']))
{
    header( 'Location: /index.php');
}

if( isset($_GET['email']))
{
    //connect
    $conn = mysql_connect($db_host, $db_user, $db_password)
        or die( "Database error: " . mysql_error());
    mysql_select_db("nadproject")
        or die ("Database not found.");
}
?>
<html>
    <head>
        <title>Setbook - <?php echo $page_title; ?></title>
	<link rel="stylesheet" type="text/css" href="/style.css" />
    </head>
    <body>
<?php include $_SERVER['DOCUMENT_ROOT'].'/../templates/private_header.html'; ?>

        <div class="error"><?php echo $error; ?></div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/../templates/private_footer.html'; ?>
    </body>
</html>
