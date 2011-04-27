<?php
session_start();
if (isset($_SESSION['mobile']))
{
    if ($_SESSION['mobile'] == "true")
    {
        $_SESSION['mobile'] = "false";
    }
    else
    {
        $_SESSION['mobile'] = "true";
    }
}
else
{
    $_SESSION['mobile'] = "true";
}

header( 'Location: /');
?>