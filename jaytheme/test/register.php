<?php
    require 'header.inc.php';
    require 'Database.class.php';
    require 'FormValidate.class.php';
    require 'Authenticate.class.php';
    require "/opt/lampp/htdocs/password_compat/lib/password.php";


    $auth = new Authenticate();
    $auth->login_required_redirect('/test/login.php', $admin_only=true);

	// Must be the names of the form inputs.
	$fields = array("firstname", "lastname", "username", "password");
	$validate = new FormValidate($fields);
	// Checks for POST request.
	$completed_form = $validate->get_form_data();

	function hash_pw($username, $password)
	{	
		// Salt
		$salted_pw = $password . $username;
		$hashed_password = password_hash($salted_pw, PASSWORD_DEFAULT);
		
		return $hashed_password;
	}

	function register_user($completed_form)
	{
		$username = $completed_form['username'];

		$db_connect = new DatabaseAccess();
		$usernames = $db_connect->build_column_array('users', 'Username');

		if (!in_array($username, $usernames)) {
			$password = $completed_form['password'];
			$firstname = $completed_form['firstname'];
			$lastname = $completed_form['lastname'];

			$hashed_password = hash_pw($username, $password);
			$db_connect->register($firstname, $lastname, $username, $hashed_password);
			$db_connect = null;
			
		} else {
			echo "<div class='error-msg'>Username taken.</div>";
		}
	}

	if ($completed_form) {
		register_user($completed_form);
	}

?>
<!-- Registration Form -->
<div class='centered-form'>
	<h2>Register</h2>
	<form method="POST" action="register.php">
		First Name: <input type="text" name="firstname" /></br>
		Last Name: <input type="text" name="lastname" /></br>
		Username: <input type="text" name="username" /></br>
		Password: <input type="password" name="password" /></br>
		<input type="submit" value="Register"></br>
	</form>	
</div>

<?php require 'footer.php'; ?>