<?php
	require 'header.inc.php';
	require 'Database.class.php';
	//include 'positions.php';

	function get_available()
	{
		$connect = new DatabaseAccess();
		$available = $connect->getTable('positions');
		$connect = null;

		return $available;
	}

	function build_app_link(array $available, $position)
	{
		$connect = new DatabaseAccess();
		$available_positions = $connect->build_column_array('positions', 'Position');
		$connect = null;
		
		$position_url = urlencode($position);
		
		if (in_array($position, $available_positions)) {
			$apply_url = "<a href='/test/apply.php?position=$position_url'>Apply Now!</a>";
			return $apply_url;
		} else {
			$msg = "$position is no longer available";
			return $msg;
		}		
	}

	$position = $_GET["position"];
	$available = get_available();
	$link = build_app_link($available, $position);
?>

<div class='centered'>
	<h1><?php echo $position ?></h1>
	<?php include 'descriptions/' . $position . '.php' ?>
	</br><h3><?php echo $link ?></h3>
</div>

<!-- Figure out how to put position-specific description here -->
<!-- Require a $position_url.txt document and have PHP read it -->

<!-- End description -->

<?php require 'footer.php'; ?>