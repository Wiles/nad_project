<?php

/**
 * register.php
 *
 * This php page will allow users to enter registration information, 
 * 	when the form is submitted, the fields are validated before 
 *	adding the new user to the database.
 *
 * Authors:	Eric Copeland, Tom Kempton, Samuel Lewis, James Rockel
 * Date:	Sunday March 27th, 2011
 * Assignment:	NAD Project
 * Milesstone:	Apache Client
 */

require $_SERVER['DOCUMENT_ROOT'].'/../includes/database.php';
$page_title = "Registration";

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
    //validate information
    $valid = true;

    $password = $_POST['password'];
    $passwordValidator = $_POST['passwordValidation'];
    if( checkdate($month, $day, $year))
    {
        $dateOfBirth = $year.'-'.$month.'-'.$day;
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

    if( strlen($password) <= 0)
    {
        $valid = false;
        $passwordError = "Password cannot be blank.";
    }
    else if( $passwordValidator != $password)
    {
        $valid = false;
        $passwordError = "Passwords must match.";
    }

    //PHP has a function for everything 
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $valid = false;
        $emailError = "Email is invalid.";
    }

    if( $valid )
    {
        $conn = mysql_connect($db_host, $db_user, $db_password)
            or die( "Database error: " . mysql_error());
        mysql_select_db("nadproject")
            or die ("Database not found.");

        $query = "INSERT INTO users (name,email,dateOfBirth,password,lastActive) VALUES ('".mysql_real_escape_string($name)."','".mysql_real_escape_string($email)."','".$dateOfBirth."','".hashPassword($password)."','".date("Y-m-d")."')";
        $result = mysql_query($query);

        if( !$result )
        {
            $error = mysql_error();
        }
        else
        {
            $query = "SELECT id, name FROM users WHERE email='".mysql_real_escape_string($_POST['email'])."' AND password='".hashPassword($_POST['password'])."' LIMIT 1";

            $result = mysql_query($query);
            if( mysql_num_rows($result)== 0)
            {
                $error = mysql_error();
            }
            else
            {
                //login
                $row = mysql_fetch_row($result);
                session_start();
                $_SESSION['user_id'] = $row[0];
		$_SESSION['user_name'] = $row[1];
                header( 'Location: /profile.php');
            }
        }
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
        $header = "/../templates/private_header_m.html";
        $footer = "/../templates/private_footer_m.html";
    }
    else
    {
        $style = "/style.css";
        $header = "/../templates/private_header.html";
        $footer = "/../templates/private_footer.html";
    }
}
else
{
    $style = "/style.css";
    $header = "/../templates/private_header.html";
    $footer = "/../templates/private_footer.html";
}
?>
<html>
    <head>
        <title>Setbook - <?php echo $page_title; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo $style; ?>" />
    </head>
    <body>
<?php include $_SERVER['DOCUMENT_ROOT'].$header; ?>
        <div class="error"><?php echo $error; ?></div>
        <form id="regForm" method="POST" action ="<?=$_SERVER['PHP_SELF']?>">
            <input type="hidden" name="submitted" value="yes" />
            Name:<br />
            <input type="text" name="name" value="<?php echo $name;?>"/><span class="error"><?php echo $nameError; ?></span><br />
            E-mail:<br />
            <input type="text" name="email" value="<?php echo $email;?>"/><span class="error"><?php echo $emailError; ?></span><br />
            Password:<br />
            <input type="password" name="password" /><span class="error"><?php echo $passwordError; ?></span><br />
            Again:<br />
            <input type="password" name="passwordValidation" /><br />
            <br />
            Date of Birth:<br />
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
            <input type="submit" value="Register" />
        </form>
<?php include $_SERVER['DOCUMENT_ROOT'].$footer; ?>
    </body>
</html>
