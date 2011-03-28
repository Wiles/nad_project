<?php
    require $_SERVER['DOCUMENT_ROOT'].'/../includes/database.php';

    session_start();
    $user = isset($_SESSION['user_id'])?$_SESSION['user_id']:"";
    
    // 1 space is necessary
    $form = " ";

    if( isset($_GET['id']))
    {
        if( $_SESSION['user_id'] == $_GET['id'])
        {
            header( 'Location: /profile.php') ;
        }
    }
    if( $user == "" )
    {
        header( 'Location: /') ;
    }

    $conn = mysql_connect($db_host, $db_user, $db_password)
        or die( "Database error: " . mysql_error());
    mysql_select_db("nadproject")
        or die ("Database not found.");

    // handle a new post
    if ( isset($_POST['message']) && (strlen($_POST['message'].trim()) > 0))
    {
        $query = "INSERT INTO post (parent, time, text, userid, profileid) VALUES ("
            .$_POST['parentid'].", "
            ."NOW(), "
            ."'".mysql_real_escape_string($_POST['message'])."', "
            .mysql_real_escape_string($_POST['userid']).", "
            .mysql_real_escape_string($_POST['profileid'])
            .")";
        mysql_query($query);
    }

    // friend profile page
    if ( isset($_GET['id'] ) )
    {
        //Check for friend ship
        $query = "SELECT firstfriend, secondfriend, status FROM friends WHERE (status = 'friends' AND (firstFriend='".$_GET['id']."' OR secondFriend='".$_GET['id']."'))";
        $result = mysql_query($query);
        if( !$result )
        {
            header( 'Location: /profile.php');
        }
        else if( mysql_num_rows($result) == 0)
        {
            //not friends
            $form = "";
            $posts = "You are not friends with this user. <a href = 'friends.php/?id=".$_GET['id']."' >send invite.</a>";

        }

        $query = "SELECT name FROM users WHERE id=".mysql_real_escape_string($_GET['id']);

        $result = mysql_query($query);

        if( !$result || mysql_num_rows($result)== 0)
        {
            header( 'Location: /profile.php');
        }
        else
        {
            $row = mysql_fetch_row($result);
            $profile_name = $row[0];
            $profileid = $_GET['id'];
        }
    }
    // main profile page
    else
    {
        //update users activity used for new post notification
        $query = "UPDATE users SET lastActive=NOW() WHERE id='".$user."'";
        mysql_query($query);

        $profile_name = $_SESSION['user_name'];
        $profileid = $_SESSION['user_id'];
    }
    if( $form != "")
    {
        $query = "SELECT profileid, userid, text, time, name, post.id FROM post LEFT JOIN (users) ON (post.userid=users.id) WHERE profileid=".mysql_real_escape_string($profileid)." AND parent IS NULL ORDER BY time desc;";
        $result = mysql_query($query);

        $posts = "<div class=\"posts\" >";

        $postcount = 0;

        // main posts loop
        $row = mysql_fetch_row($result);
        while ($row != NULL)
        {
            $postcount++;
            $legend = ($row[0] == $row[1])? $profile_name : $row[4];
            $posts = $posts."<div class=\"wrap\"><a href=\"profile.php?id=".$row[1]."\" >".$legend."</a><br/>"
                .nl2br(htmlspecialchars($row[2]))."<br /><p class=\"postfoot\" >".$row[3]."</p>"
                ."<p class=\"postfoot\" >"
                ."<a href=\"javascript:vote()\" >"." Likes</a>&nbsp;-&nbsp;"
                ."<a href=\"javascript:vote()\" >"." Dislikes</a>&nbsp;-&nbsp;"
                ."<a href=\"javascript:toggleComments(".$postcount.")\" id=\"a".$postcount."\" >Show Comments</a></p>"."</div><hr/>";

            $posts = $posts."<div class=\"comments\" id=\"c".$postcount."\" style=\"display:none\" >";

            // comments loop!
            $query2 = "SELECT profileid, userid, text, time, name FROM post LEFT JOIN (users) ON (post.userid=users.id) WHERE profileid=".mysql_real_escape_string($profileid)." AND parent=".mysql_real_escape_string($row[5])." ORDER BY time desc;";
            $result2 = mysql_query($query2);
            $row2 = mysql_fetch_row($result2);
            while ($row2 != NULL)
            {
                $posts = $posts."<div class=\"wrap\"><a href=\"profile.php?id=".$row2[1]."\" >".$row2[4]."<a></br />"
                    .nl2br(htmlspecialchars($row2[2]))."<br /><p class=\"postfoot\" >".$row2[3]."</p></div>"
                    ."<p class=\"postfoot\" ><a href=\"javascript:vote()\" >Likes</a>&nbsp;-&nbsp;"
                    ."<a href=\"javascript:vote()\" >Dislikes</a></p>"
                    ."<hr/>\n";
                $row2 = mysql_fetch_row($result2);
            }

            $posts = $posts."<form method=\"POST\" action =\"profile.php?id=".$profileid."\">"
                ."<textarea id =\"message\" name=\"message\" onKeyPress=\"textLimit(this.form.message, 1024)\" rows=\"2\" cols=\"30\"></textarea><br />"
                ."<input type=\"submit\" value=\"Reply\" />"
                ."<input type=\"hidden\" name=\"profileid\" value=".$profileid." >"
                ."<input type=\"hidden\" name=\"userid\" value=".$user." />"
                ."<input type=\"hidden\" name=\"parentid\" value=".$row[5]." />"
                ."</form></div>\n";

            $row = mysql_fetch_row($result);
        }

        $posts = $posts."</div>";
    }
    mysql_close();

    $form = "<form method=\"POST\" action =\"profile.php?id=".$profileid."\">"
        ."<textarea id =\"message\" name=\"message\" onKeyPress=\"textLimit(this.form.message, 1024)\" rows=\"5\" cols=\"50\"></textarea><br />"
        ."<input type=\"submit\" value=\"Post\" />"
        ."<input type=\"hidden\" name=\"profileid\" value=".$profileid." />"
        ."<input type=\"hidden\" name=\"userid\" value=".$user." />"
        ."<input type=\"hidden\" name=\"parentid\" value="."NULL"." />"
        ."</form>\n"
        ."<form method=\"POST\" action=\"profile.php?id=\"".$profileid."\">"
        ."<input type=\"hidden\" name=\"postid\" />"
        ."<input type=\"hidden\" name=\"userid\" />"
        ."<input type=\"hidden\" name=\"type\" />"
        ."</form>\n"

?>
<html>
    <head>
        <title>Setbook - <?php echo $profile_name; ?></title>
	<link rel="stylesheet" type="text/css" href="/style.css" />
        <script type="text/javascript" src="notify.js"></script>

        <script type="text/javascript">
            <!--
            function textLimit(field, maxlen)
            {
                if (field.value.length > maxlen)
                    field.value = field.value.substring(0, maxlen);
            }

            function toggleComments(id)
            {
                if (document.getElementById("c" + id).style.display == "block")
                {
                    document.getElementById("c" + id).style.display = "none";
                    document.getElementById("a" + id).innerHTML = "Show Comments";
                }
                else
                {
                    document.getElementById("c" + id).style.display = "block";
                    document.getElementById("a" + id).innerHTML = "Hide Comments";
                }
            }
            //-->
        </script>
    </head>
    <body onload="getPostCount()">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/../templates/private_header.html'; ?>
        <h2><?php echo $profile_name ?></h2>

        <?php echo $form ?>
        <?php echo $posts ?>


    <?php include $_SERVER['DOCUMENT_ROOT'].'/../templates/private_footer.html'; ?>
    </body>
</html>