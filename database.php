<?php
	$db_host = "localhost";
	$dp_port = "3306";
	$db_user = "setcca_nadsrv";
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
