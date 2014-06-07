<?php
	require "Database.class.php";

	$db = new DatabaseAccess();

	$array = $db->getFields("positions", "Position");

	print_r($array);
?>	