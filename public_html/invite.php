<?php

/**
 * invite.php
 *
 * This php page will get an email address from a POST, and send an email
 * 	to that address with a link to the registration page.
 *
 * Authors:	Eric Copeland, Tom Kempton, Samuel Lewis, James Rockel
 * Date:	Sunday March 27th, 2011
 * Assignment:	NAD Project
 * Milesstone:	Apache Client
 */

    include('Mail.php');
    include('Mail/mime.php');
    require $_SERVER['DOCUMENT_ROOT'].'/includes/database.php';
    
    $page_title = "Invite";
    $error = "";
    $valid = true;
    
    //Get email if present in the post
    $email = isset($_POST['inviteBox'])?$_POST['inviteBox']:'';
    
    session_start();
    //Check if user is logged in
    if(!isset($_SESSION['user_id']))
    {
        header( 'Location: /index.php');
    }
    
    //Validate the e-mail address
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $valid = false;
        $error = "The email address " . $email . " is invalid.";
    }
    
    
    if ($valid == true)
    {
        // Constructing the email
        $sender = "SETBook@gmail.com";                                         
        $recipient = $_POST['inviteBox'];
        $subject = "You have been invited to SETBook!";         
        $text = 'You have been invited to SETBook.com by ' . $_SESSION['user_name'] . '.\r\n\r\nTo register an account on SETBook, please go to http://127.0.0.1/register.php to register a new account.'; // Text version of the email
        $html = '<html><body><p>You have been invited to SETBook.com by ' . $_SESSION['user_name'] . '.<br /><br />To register an account on SETBook, please go to <a href="http://127.0.0.1/register.php" >SETBook Registration</a> to register a new account.</p></body></html>';      // HTML version of the email
        $crlf = "\n";
        $headers = array(
            'From'          => $sender,
            'Return-Path'   => $sender,
            'Subject'       => $subject
        );
    
        // Creating the Mime message
        $mime = new Mail_mime($crlf);
    
        // Setting the body of the email
        $mime->setTXTBody($text);
        $mime->setHTMLBody($html);
        
        // Set body and headers ready for base mail class
        $body = $mime->get();
        $headers = $mime->headers($headers);
            
        // SMTP authentication params
        $smtp_params["host"]     = "ssl://smtp.gmail.com";
        $smtp_params["port"]     = "465";
        $smtp_params["auth"]     = true;
        $smtp_params["username"] = "SET.Book.Mail@gmail.com";
        $smtp_params["password"] = "setbookpassword";
        
        // Sending the email using smtp
        $mail =& Mail::factory("smtp", $smtp_params);
        $result = $mail->send($recipient, $headers, $body);
        if($result == 1)
        {
            $error = "You successfully sent an invite to " . $email . "!";
        }
        else
        {
            $error = "Your invite could  not be sent: " . $result;
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
<meta http-equiv="refresh" content="5;url=profile.php">
<html>
    <head>
        <title>Setbook - <?php echo $page_title; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo $style; ?>" />
    </head>
    <body>
<?php include $_SERVER['DOCUMENT_ROOT'].$header; ?>
        <br />
        <br />
        <div class="error"><?php echo $error ?></div>
        <p>You should automatically be redirected to your profile page in 5 seconds</p>
<?php include $_SERVER['DOCUMENT_ROOT'].$footer; ?>
    </body>
</html>
