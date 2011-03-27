<?php
require $_SERVER['DOCUMENT_ROOT'].'/../includes/database.php';
session_start();
if( isset($_SESSION['user_id']))
{
            //connect to server
    $conn = mysql_connect($db_host, $db_user, $db_password)
        or die( "Database error: " . mysql_error());

    //select database
    mysql_select_db("nadproject")
        or die ("Database not found.");

    //Get time of users last visit to profile
    $query = "SELECT lastactive FROM users where id='".$_SESSION['user_id']."'";
    $time = "";
    $result = mysql_query($query);
    if( $result)
    {
        $row = mysql_fetch_row($result);
        $time = $row[0];

        $query = "SELECT count(*) FROM post WHERE time > '".$time."' AND profileid='".$_SESSION['user_id']."'";
        $result = mysql_query($query);
        
        if( $result )
        {
            $row = mysql_fetch_array($result);
            echo $row[0];
        }

    }
    mysql_close();
}
?>
