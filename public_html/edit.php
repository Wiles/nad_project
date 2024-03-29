<?php


/**
 * edit.php
 *
 * This php page will allow users of the setbook website to edit their
 * profile preferences. It uses a form to gather the updated information,
 * and connects do the database to update with the new information.
 *
 * Authors:	Eric Copeland, Tom Kempton, Samuel Lewis, James Rockel
 * Date:	Sunday March 27th, 2011
 * Assignment:	NAD Project
 * Milesstone:	Apache Client
 */


require $_SERVER['DOCUMENT_ROOT'].'/includes/database.php';
$page_title = "Preferences";

session_start();
//Check if user is logged in
if(!isset($_SESSION['user_id']))
{
    header( 'Location: /index.php');
}

$name = isset($_POST['name'])?$_POST['name']:'';
$email = isset($_POST['email'])?$_POST['email']:'';
$year = isset($_POST['year'])?$_POST['year']:'';
$month = isset($_POST['month'])?$_POST['month']:'';
$day = isset($_POST['day'])?$_POST['day']:'';

$error = "&nbsp;";
$nameError = "";
$emailError = "";
$passwordError = "";
$dateError = "";

if( isset($_POST['submitted']))
{
    //connect
    $conn = mysql_connect($db_host, $db_user, $db_password)
        or die( "Database error: " . mysql_error());
    mysql_select_db("nadproject")
        or die ("Database not found.");
//validate information
    $valid = true;
    $query = 'UPDATE users SET ';

    $oldPassword = $_POST['password'];
    $password = $_POST['newPassword'];
    $passwordValidator = $_POST['newPasswordValidation'];
    if( checkdate($month, $day, $year))
    {
        $dateOfBirth = $year.'-'.$month.'-'.$day;

        $query .= 'dateofbirth="'.mysql_real_escape_string($dateOfBirth).'", ';
    }
    else
    {
        $valid = false;
        $dateError = "Invalid date.";
    }

    if( strlen($name) <= 0)
    {
        $valid = false;
        $nameError = "Name must not be blank.";
    }
    else
    {
        $query .= 'name="'.mysql_real_escape_string($name).'", ';
    }

    if( strlen($password) != 0 )
    {
        if( $passwordValidator != $password)
        {
            $valid = false;
            $passwordError = "Passwords must match.";
        }
        else
        {
            $query .= 'password="'.hashPassword($password).'", ';
        }
    }

    //PHP has a function for everything
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $valid = false;
        $emailError = "Email is invalid.";
    }
    else
    {
        $query .= 'email="'.mysql_real_escape_string($email).'" ';
    }

    if( $valid)
    {
        $query .= " WHERE id='".$_SESSION['user_id']."' AND password='".hashPassword($oldPassword)."';";
        echo $query;
        $result = mysql_query($query);

        if( !$result )
        {
            $error = mysql_error();
        }
        else if ( mysql_affected_rows() == 0)
        {
            $error = "Incorrect Password.";
        }
    }
}
else
{
    $conn = mysql_connect($db_host, $db_user, $db_password)
            or die( "Database error: " . mysql_error());
    mysql_select_db("nadproject")
        or die ("Database not found.");
        
    //fill existing slots
    $query = "SELECT name, email, dateofbirth FROM users WHERE id ='".$_SESSION['user_id']."';";

    $result = mysql_query($query);
    if( !$result || mysql_num_rows($result)== 0)
    {
        $error = mysql_error();
    }
    else
    {
        //update output
        $row = mysql_fetch_row($result);
        
        $date = new DateTime($row[2]);

        $name = $row[0];
        $email = $row[1];
        $year = $date->format("Y");
        $month = $date->format("m");
        $day = $date->format("d");
    }
}

$style = "";
$header = "";
$footer = "";
if (isset($_SESSION['mobile']))
{
    if ($_SESSION['mobile'] == "true")
    {
        $style = "/style_m.css";
        $header = "/templates/private_header_m.html";
        $footer = "/templates/private_footer_m.html";
    }
    else
    {
        $style = "/style.css";
        $header = "/templates/private_header.html";
        $footer = "/templates/private_footer.html";
    }
}
else
{
    $style = "/style.css";
    $header = "/templates/private_header.html";
    $footer = "/templates/private_footer.html";
}
?>
<html>
    <head>
        <title>Setbook - <?php echo $page_title; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo $style; ?>" />
        <script type="text/javascript" src="notify.js"></script>
    </head>
    <body onload="getPostCount()">
<?php include $_SERVER['DOCUMENT_ROOT'].$header; ?>

        <div class="error"><?php echo $error; ?></div>
        <form id="regForm" method="POST" action ="<?=$_SERVER['PHP_SELF']?>">
            <input type="hidden" name="submitted" value="yes" />
            Password:*<br />
            <input type="password" name="password" /><span class="error"><?php echo $passwordError; ?></span><br />
            Name:*<br />
            <input type="text" name="name" value="<?php echo $name;?>"/><span class="error"><?php echo $nameError; ?></span><br />
            E-mail:*<br />
            <input type="text" name="email" value="<?php echo $email;?>"/><span class="error"><?php echo $emailError; ?></span><br />
            New Password:<br />
            <input type="password" name="newPassword" /><span class="error"><?php echo $passwordError; ?></span><br />
            New Again:<br />
            <input type="password" name="newPasswordValidation" /><br />
            <br />
            Date of Birth:*<br />
            Year:
            <select name="year" >
                <?php
                for( $i = date("Y"); $i >= 1900; --$i)
                {
                    if( $i == $year )
                    {
                        echo "<option value = '".$i."' selected='selected'>". $i ."</option>";
                    }
                    else
                    {
                        echo "<option value = '".$i."'>". $i ."</option>";
                    }
                }
                ?>
            </select>
            Month:
            <select name="month" >
                <?php
                for( $i = 1; $i <= 12; ++$i)
                {
                    if( $i == $month )
                    {
                        echo "<option value = '".$i."' selected='selected'>". $i ."</option>";
                    }
                    else
                    {
                        echo "<option value = '".$i."'>". $i ."</option>";
                    }
                }
                ?>
            </select>
            Day:
            <select name="day" >
                <?php
                for( $i = 1; $i <= 31; ++$i)
                {
                    if( $i == $day )
                    {
                        echo "<option value = '".$i."' selected='selected'>". $i ."</option>";
                    }
                    else
                    {
                        echo "<option value = '".$i."'>". $i ."</option>";
                    }
                }
                ?>
            </select><span class="error"><?php echo $dateError; ?></span>
            <br />
            <br />
            <input type="submit" value="Update" />
        </form>
<?php include $_SERVER['DOCUMENT_ROOT'].$footer; ?>
    </body>
</html>
