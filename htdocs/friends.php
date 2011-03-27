<?php
require $_SERVER['DOCUMENT_ROOT'].'/../includes/database.php';
$page_title = "Friends";
$error = "&nbsp;";
$email = isset($_POST['email'])?$_POST['email']:"";
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


//attempt to get the list of friends from the server
$friends="";
$invitedfriends="";
$invitations="";
$count=0;

//connect to server
$conn = mysql_connect($db_host, $db_user, $db_password)
    or die( "Database error: " . mysql_error());

//select database
mysql_select_db("nadproject")
    or die ("Database not found.");

//create database query to get everyone with friends
$query = "SELECT firstfriend, secondfriend, status FROM friends WHERE status = \"friends\"
    AND firstfriend='".mysql_real_escape_string($_SESSION['user_id'])."' OR secondfriend='".mysql_real_escape_string($_SESSION['user_id'])."' ";

//send query
$result = mysql_query($query);

//check results
//no results
if( !$result || mysql_num_rows($result)== 0)
{
    $friends = "No confirmed friends";

}

//has friends
else
{
    //get friends
    $row = mysql_fetch_row($result);
    while($row!=NULL)
    {
        //if current user is firstfriend
        if($row[0] == $_SESSION['user_id'])
        {
            $query2 = "SELECT name, email, dateOfBirth  FROM users WHERE id = '".mysql_real_escape_string($row[1])."' LIMIT 1";
            $result2 = mysql_query($query2);
            if( !$result2 || mysql_num_rows($result2)== 0)
            {
                //failed to get name
                $friends = $friends."Failed to get name for user ".$row[1]."<br />";


            }
            else
            {
                //got name
                $row2 = mysql_fetch_row($result2);
                $friends =$friends."<fieldset >
                                    <legend> <a href = \"profile.php\" onclick = \"load_profile(".$row[1]."\")>".$row2[0]."</a></legend>"
                                    .$row2[1]."<br \>
                                    Birth date:".$row2[2].
                           "</fieldset> <br \>" ;
            }
        }

        //current user is second friend
        else
        {
               //get users names
            $query2 = "SELECT name, email, dateOfBirth  FROM users WHERE id = '".mysql_real_escape_string($row[0])."' LIMIT 1";
            $result2 = mysql_query($query2);
            if( !$result2 || mysql_num_rows($result2)== 0)
            {
                //failed to get name

            }
            else
            {
                //got name
                $row2 = mysql_fetch_row($result2);
                $friends =$friends."<fieldset >
                                    <legend> <a href = \"profile.php\" onclick = \"load_profile(".$row[1]."\")>".$row2[0]."</a></legend>"
                                    .$row2[1]."<br \>
                                    Birth date:".$row2[2].
                           "</fieldset> <br \>" ;

                
            }
        }
        $row = mysql_fetch_row($result);
    }

}


//check for invitations
//create database query to get everyone with friends
$query = "SELECT firstfriend, secondfriend, status FROM friends WHERE status = \"pending\"
    AND firstfriend='".mysql_real_escape_string($_SESSION['user_id'])."' OR secondfriend='".mysql_real_escape_string($_SESSION['user_id'])."' ";

//send query
$result = mysql_query($query);

//check results
//no results
if( !$result || mysql_num_rows($result)== 0)
{
    $invitedfriends = "No open invites for friends friends";

}

//has friends
else
{
    //get friends
    $row = mysql_fetch_row($result);
    while($row!=NULL)
    {
        //if current user is firstfriend it means that they made request
        if($row[0] == $_SESSION['user_id'])
        {
            $query2 = "SELECT name  FROM users WHERE id = '".mysql_real_escape_string($row[1])."' LIMIT 1";
            $result2 = mysql_query($query2);
            if( !$result2 || mysql_num_rows($result2)== 0)
            {
                //failed to get name
                $invitedfriends = $invitedfriends."Failed to get name for user ".$row[1]."<br />";


            }
            else
            {
                //got name
                $row2 = mysql_fetch_row($result2);
                $invitedfriends = $invitedfriends.$row2[0]."<br />";

            }
        }
        else
        {
            
            $query2 = "SELECT name, email, dateOfBirth  FROM users WHERE id = '".mysql_real_escape_string($row[0])."' LIMIT 1";
            $result2 = mysql_query($query2);
            if( !$result2 || mysql_num_rows($result2)== 0)
            {
                //failed to get name
                $count++;
                $invitations = $invitations."Failed to get name for user ".$row[1]."<br />";


            }
            else
            {
                //got name
                $row2 = mysql_fetch_row($result2);
                $count++;
                $invitations =$invitations."<fieldset >
                                    <legend> <a href = \"profile.php\" onclick = \"load_profile(".$row[1]."\")>".$row2[0]."</a></legend>"
                                    .$row2[1]."<br \>
                                    Birth date:".$row2[2].
                           "<br \><input type=\"button\"value=\"Accept Request\"/>
                               <input type=\"button\" value=\"Decline Request\"/></fieldset> <br \>" ;

                

            }
        }
        

       
        $row = mysql_fetch_row($result);
    }

}

if($count==0)
{
    $invitations="No new friend requests";
}



?>
<html>
    <head>
        <script type ="text/javascript">
        function load_profile(id)
        {
            document.getElementsByName(profileload).value = id;
        }

        

        </script>
        <title><?php echo $user; ?></title>
	<link rel="stylesheet" type="text/css" href="/style.css" />
    </head>
     <?php include $_SERVER['DOCUMENT_ROOT'].'/../templates/private_header.html'; ?>
        <form method="POST" action ="<?=$_SERVER['PHP_SELF']?>">
            <input type="hidden" name="profileload" value="0"/>
            <div id="error"><?php echo $error; ?></div>
            Friend Requests:<br />
            <?php echo $invitations?><br />
            Requested Friends:<br />
            <?php echo $invitedfriends?><br />
            Friends:<br />
            <?php echo $friends?><br />
            
        </form>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/../templates/private_footer.html'; ?>
</html>
