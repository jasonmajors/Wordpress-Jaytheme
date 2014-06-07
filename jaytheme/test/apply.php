<?php
    require 'header.inc.php';
    require 'Database.class.php';
    require 'FormValidate.class.php';

    // Key will be used as the html input name and Value will be used as the type.
    $DEMOGRAPHICS = array("Last_Name" => "text",
                    "First_Name" => "text",
                    "Email" => "text",
                    "Phone_Number" => "text",
                    "Address" => "text",
                    "City" => "text",
                    "State" => "text",
                    "Zipcode" => "text",
                    "Last_Four_SSN" => "password",
                    );

    $OTHER = array("Salary_Requirements" => "text",
                );
    // Keys will be printed out as category titles.
    $APPLICATION = array("Demographics" => $DEMOGRAPHICS, 
                        "Other" => $OTHER,
                        );

    function get_position()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $position = $_POST['applied_position'];
            $position = urldecode($position);
            // Need this variable for the form incase submission fails. See line 94.
            $position_encoded = urlencode($position);    
        } else {
            // Set neccesary variables for the form.
            $position = $_GET['position'];
            $position_test = new FormValidate(array(''));
            if ($position_test->positions($position)) {
                // Replace the +'s with spaces to display as h3 heading.
                $position = urldecode($position);
                // Replace the spaces with +'s to pass to the form.
                $position_encoded = urlencode($position);
            }
        }
        return array($position, $position_encoded);
    }

    function main()
    {
        list($position, $position_encoded) = get_position();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Fields must be identical to the keys in $DEMOGRAPHICS etc.
            $validation_fields = array("Last_Name",
                                    "First_Name",
                                    "Email",
                                    "Phone_Number",
                                    "Last_Four_SSN",
                                    "Work_History",
                                    "City",
                                    "State",
                                    
                                    );

            $validation = new FormValidate($validation_fields);
            $completed_app = $validation->validate();
            if ($completed_app) {
                $connect = new DatabaseAccess();
                $connect->submitApplication($completed_app, $position);
                $connect = null;
                // Redirect.
                return header("Location: http://localhost/test/applicant.php");
            } else {
                return array($position, $position_encoded);
            }
        } else {
            return array($position, $position_encoded);  
        }
    }


    list($position, $position_encoded) = main();
?>

<!-- Begin HTML application form -->
<div class="centered">
    <h1><?php echo $position; ?></h1>
    <form method="POST" action="apply.php" enctype="multipart/form-data">

        <?php 
        // Build the form based on $APPLICATION.
            foreach($APPLICATION as $key => $value) {
                echo "<label><h3>$key</h3></label>";
                foreach ($value as $k => $v) {
                    $decoded = str_replace('_', ' ', $k);
                    $encoded = str_replace(' ', '_', $k);
                    // Make a session variable to repopulate form fields.
                    if ($_SERVER['REQUEST_METHOD'] === "POST") {
                        $_SESSION[$encoded] = $_POST[$encoded];
                        $formval = $_SESSION[$encoded];
                    } else {
                        if (isset($_SESSION[$encoded])) {
                            $formval = $_SESSION[$encoded];
                        } else {
                            $formval = '';
                        }
                    }
                    echo "<label>$decoded:</label><input type='$v' name='$encoded' value='$formval' /></br>";
                }
            }
        ?>  

        </br><label><h3>Relevant Work History</h3></label> </br> 
        <textarea name='Work_History' rows='20' cols='92' maxlength='20000'></textarea>
        <!-- Removing file uploads...
            <label>Upload a Resume </label> <input type="file" name="Resume" /></br> 
        -->
        <!-- Used to pass the position after a POST request -->
        <input type="hidden" name="applied_position" value=<?php echo $position_encoded ?>></br>
        </br><input class="centered-button" type="submit" value="Submit Application"/></br>
    </form>
</div>
<!-- End form -->

<script type="text/javascript" charset="utf-8" src="/test/js/DataTables/media/js/jquery.js"></script>
<script type="text/javascript" charset="utf-8" src="/test/js/DataTables/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf-8" src="/test/js/datatables.js"></script>    
<?php require 'footer.php'; ?>
