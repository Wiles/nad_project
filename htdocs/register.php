<?php
require $_SERVER['DOCUMENT_ROOT'].'/../includes/database.php';
$page_title = "Registration";
if( isset($_POST['submitted']))
{

}
?>
<html>
    <head>
        <title>Setbook - <?php echo $page_title; ?></title>
        <script type="text/javascript">
            function formValidate()
            {
            }
        </script>
    </head>
    <body>
<?php include $_SERVER['DOCUMENT_ROOT'].'/../templates/header.php'; ?>
        <form method="POST" action ="<?=$_SERVER['PHP_SELF']?>">
            <input type="hidden" name="submitted" value="yes" />
            Name:<br />
            E-mail:<br />
            Password:<br />
            Again:<br />
            Date of Birth:<br />
            Year:
            Month:
            Day:
            <input type="button" value="Register" onclick="formValidate();"
        </form>
<?php include $_SERVER['DOCUMENT_ROOT'].'/../templates/footer.php'; ?>
    </body>
</html>