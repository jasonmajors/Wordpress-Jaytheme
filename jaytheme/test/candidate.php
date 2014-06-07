<?php
    require 'header.inc.php';
    require 'Database.class.php';
    require 'Authenticate.class.php';

    $auth = new Authenticate();
    $auth->login_required_redirect('/test/login.php');
    date_default_timezone_set('America/Los_Angeles');
    
    function get_application()
    {
    	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	   	   	$app = $_GET['id'];		
    	} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    		$app = $_POST['id'];
    	}

    	$db_connect = new DatabaseAccess();
	    $applicant = $db_connect->getTable($table='applications', $field='ID', $condition=$app);
	    $db_connect = null;
	    // Applicant is an array of 1 array.
	    $application = $applicant[0]; 

	   	return $application;
    }

    function get_next_status($status)
    {
        switch($status) {
            case 'Pending':
                $new_status = 'Interview';
                break;
            case 'Interview':
                $new_status = 'Work_Permit';
                break;
            case 'Work_Permit':
                $new_status = 'Orientation';
                break;   
            case 'Deleted':
                $new_status = 'Deleted';   
        } 
        return $new_status; 
    }

    function change_app_status(array $application, $event_date)
    {
    	$id = $application['ID'];
    	$status = $application['App_Status'];
    	$new_status = get_next_status($status);	
    	// Cast to array in order to use the alterApplication method.
    	$id_array = (array)$id;
    	$db_connect = new DatabaseAccess();
        $db_connect->alterApplication($id_array, 'Date', $event_date);
    	$db_connect->alterApplication($id_array, 'App_Status', $new_status);
    	$db_connect = null;
    }

    function format_date($rawdate)
    { 
        $date = str_replace('T', ' ', $rawdate);
        $date = strtotime($date);
        return $date;
    }

    function main()
    {
    	$application = get_application();
        // Need to pass the next step for the applicant so we can use it in the scheduling form.
        $next_status = get_next_status($application['App_Status']);
        $error_msg = '';

    	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rawdate = $_POST['time'];
            $event_date = format_date($rawdate);
            if ($event_date) {
                change_app_status($application, $event_date);
                return header("Location: http://localhost/test/employer.php");
            } else {
                $error_msg = "fuck you";
                return array($application, $next_status, $error_msg);
            }

    	} else {
    		return array($application, $next_status, $error_msg);
    	}
    }

    list($application, $next_status, $error_msg) = main();

?>    
<div class="centered">
	<h1><?php echo $application['First_Name'] . ' ' . $application['Last_Name']; ?></h1>
	<h3><?php echo $application['Position']; ?></h3>
    <h3>Employment History</h3>
    <pre><?php echo $application['Work_History']; ?></pre></br></br>
    <h4>Schedule for <?php echo str_replace('_', ' ', $next_status); ?></h4>
    <?php echo $error_msg; ?>
	<form method="POST" action="candidate.php">
        <input type='datetime-local' name='time' /></br>  
        <!-- Set the id value so we can still retrieve $application if theres an incomplete
            POST request -->
		<input type='hidden' name='id' value=<?php echo $application['ID']; ?> /></br>
		<input type='submit' name='update' value='Confirm' />
	</form>
    </br></br></br><a href="/test/employer.php">Return to Dashboard</a>

</div>	

<?php require 'footer.php'; ?>