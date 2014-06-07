<?php 
	require 'header.inc.php';
	//require 'Database.class.php';

	function __autoload($classname) {
		include $classname . '.php';
	}
	
	$positions = new Database();
	$available = $positions->getTable('positions');
?>

<div class='centered'>
<h1>Open Positions</h1>
<ul>
	<?php
		// $available is an array of associative arrays (list of dicts!).
		foreach($available as $position) {
			$open_position = $position['Position'];

			$position_url = urlencode($open_position);

			echo "<li><a href='/test/description.php?position=$position_url'>$open_position</a></li>";
		}
	?>
</ul>	
</div>

<?php require 'footer.php'; ?>

