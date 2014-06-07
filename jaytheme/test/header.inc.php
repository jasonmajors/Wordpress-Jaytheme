<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
    	<link href="css/bootstrap.min.css" rel="stylesheet">
    	<link href="css/applicant_tracking.css" rel="stylesheet">
    	<style type="text/css" title="currentStyle">
    		@import "/test/js/DataTables/media/css/demo_table.css";
		</style>
		<title>Jason's PHP ATS</title>
	</head>
	<body>
	<?php 
		session_start();
		$loggedin = false;
		$admin = false;

		if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        	$username = $_SESSION['username'];
        	$admin = $_SESSION['admin'];
        	$loggedin = true;
        }
	?>	
	<!-- Nav bar -->
	<div id="navbar">
		<ul id="left-links">
			<?php if ($loggedin): ?>
				<li><a href="/test/employer.php">Welcome, <?php echo $username ?></a></li>
			<?php else: ?>
				<li>Welcome!<li>
			<?php endif; ?>
		</ul>	
		<ul id="right-links">	
			<?php if ($admin): ?>
			<li><a href="/test/register.php">Add User</a></li>
			<?php endif; ?>
			<?php if ($loggedin): ?>
			<li><a href="/test/logout.php">Logout</a></li>
			<?php endif; ?>	
		</ul>	
	</div>