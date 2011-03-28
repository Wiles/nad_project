<?php
/**
 * logout.php
 *
 * This php page will log the current user out, destroying the session.
 *
 * Authors:	Eric Copeland, Tom Kempton, Samuel Lewis, James Rockel
 * Date:	Sunday March 27th, 2011
 * Assignment:	NAD Project
 * Milesstone:	Apache Client
 */
session_start();
session_unset();
session_destroy();

header( 'Location: /index.php');

?>
