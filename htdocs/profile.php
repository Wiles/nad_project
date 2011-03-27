<?php
    session_start();
    $user = isset($_SESSION['user_id'])?$_SESSION['user_name']:"";
    if( $user == "" )
    {
        header( 'Location: /') ;
    }

    if ( !isset($_POST['user_id'] ) )
    {
        $profile_name = $_SESSION['user_name'];
    }
?>
<html>
    <head>
        <title>Setbook - <?php echo $user; ?></title>
	<link rel="stylesheet" type="text/css" href="/style.css" />

        <script type="text/javascript">
            <!--
            function textLimit(field, maxlen) {
                if (field.value.length > maxlen)
                field.value = field.value.substring(0, maxlen);
            }
            //-->
        </script>
    </head>
    <body>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/../templates/private_header.html'; ?>
        <h1><?php echo $profile_name ?></h1>
        
        <form method="POST" action ="<?=$_SERVER['PHP_SELF']?>">
            <textarea id ="message" name="message" onKeyPress="textLimit(this.form.message, 1024)" rows="3" cols="60"></textarea><br />
            <input type="button" value="Post" />
        </form>

        
    <?php include $_SERVER['DOCUMENT_ROOT'].'/../templates/private_footer.html'; ?>
    </body>
</html>