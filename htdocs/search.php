<?php

/**
 * search.php
 *
 * This php page allows uers to search by Name or E-mail,
 *
 * Authors:	Eric Copeland, Tom Kempton, Samuel Lewis, James Rockel
 * Date:	Wednesday March 30th, 2011
 * Assignment:	NAD Project
 * Milesstone:	Apache Client
 */


require $_SERVER['DOCUMENT_ROOT'].'/../includes/database.php';
$error = "&nbsp;";
session_start();
$user = isset($_SESSION['user_id'])?$_SESSION['user_name']:"";
$people = "<br />";
$contain = "";
$exact = "";
$email = "";

if( $user == "" )
{
    header( 'Location: /') ;
}
else
{
    //echo "Profile: ".$user;
}

if ($_POST['searchfield'] != "")
{
    $conn = mysql_connect($db_host, $db_user, $db_password)
        or die( "Database error: " . mysql_error());

    mysql_select_db("nadproject")
        or die ("Database not found.");

    if (isset($_POST['type']))
    {
        if ($_POST['type'] == "starts")
        {
            $field = " LIKE '".mysql_escape_string($_POST['searchfield'])."%'";
        }
        else if ($_POST['type'] == "exact")
        {
            $field = "='".mysql_escape_string($_POST['searchfield']."'");
            $exact = "checked";
        }
        else if ($_POST['type'] == "contains")
        {
            $field = " LIKE '%".mysql_escape_string($_POST['searchfield'])."%'";
            $contain = "checked";
        }

        if ($_POST['field'] == "email")
        {
            $email = "checked";
        }

        $query = "SELECT name, email, dateOfBirth, id FROM users WHERE ".mysql_escape_string($_POST['field']).$field.";";
    }
    else
    {
        $query = "SELECT name, email, dateOfBirth, id FROM users WHERE name LIKE '".mysql_escape_string($_POST['searchfield'])."%';";
    }

    $result = mysql_query($query);

    if ( !$result || mysql_num_rows($result)== 0)
    {
        $people = $people."No Matches Found";
    }
    else
    {
        $row = mysql_fetch_row($result);
        while ($row != NULL)
        {
            $people = $people."<fieldset><legend><a href=\"profile.php?id=".$row[3]."\">".$row[0]."</a></legend>".$row[1]."<br />".$row[2]."</fieldset><br />";
            $row = mysql_fetch_row($result);
        }
    }

    mysql_close();
}

?>
<html>
    <head>
        <script type="text/javascript" src="notify.js"></script>
        <script type ="text/javascript">
        function load_profile(id)
        {
            document.getElementsByName(profileload).value = id;
        }

        </script>
        <title>SetBook - <?php echo $user; ?> - Search</title>
	<link rel="stylesheet" type="text/css" href="/style.css" />
    </head>
    <body onload="getPostCount()">
     <?php include $_SERVER['DOCUMENT_ROOT'].'/../templates/private_header.html'; ?>
            <input type="hidden" name="profileload" value="0"/>
            <div id="error"><?php echo $error; ?></div>
            <form action="search.php" method="POST" >
                <p>Search:</p>
                <input type="text" id="searchfield" name="searchfield" value="<?php echo (isset($_POST['searchfield']))? $_POST['searchfield'] : "" ;?>" /><br /><br />

                <fieldset>
                    <legend>Field:</legend>
                    <input type="radio" id="field" name="field" value="name" checked="true" />Name&nbsp;
                    <input type="radio" id="field" name="field" value="email" <?php echo $email ?> />E-mail&nbsp;
                </fieldset>
                <br />

                <fieldset>
                    <legend>Filter:</legend>
                    <input type="radio" id="type" name="type" value="starts" checked="true" />Starts With&nbsp;
                    <input type="radio" id="type" name="type" value="contains" <?php echo $contain ?> />Contains&nbsp;
                    <input type="radio" id="type" name="type" value="exact" <?php echo $exact ?> />Exact Match&nbsp;
                </fieldset>
                <br />

                <input type="submit" value="Search" />
            </form>
            <br />
            <?php echo $people ?>
            
    <?php include $_SERVER['DOCUMENT_ROOT'].'/../templates/private_footer.html'; ?>
    </body>
</html>