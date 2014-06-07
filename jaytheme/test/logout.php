<?php
	function logout()
	{
		session_start();
		unset($_SESSION);
		session_destroy();

		return header("Location: http://localhost/test/login.php");
	}

	logout()
?>
