<?php
	$db_host = "localhost";
	$db_user = "nad_admin";
	$db_password = "admin";

	/*
	 * Unified password hashing function
	 * must have already run mysql_connect to use this
	 */
	function hashPassword( $password )
	{
		return md5($password);
	}
?>
