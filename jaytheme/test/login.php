<?php
    require 'header.inc.php';
    require 'Database.class.php';
    require 'FormValidate.class.php';
    require 'Authenticate.class.php';
    require "/opt/lampp/htdocs/password_compat/lib/password.php";
	
    // Redirects users already logged in.
	$auth = new Authenticate();
	$auth->redirect_user("/test/employer.php");

	if (isset($_SESSION['Auth_Required_Msg'])) {
		$error_msg = $_SESSION['Auth_Required_Msg'];
		echo "<div class='error-msg'>$error_msg</div>";
	}

	function login_user($completed_form)
	{
		$username = $completed_form['username'];
		$user_pw = $completed_form['password'];
		// Add the salt.
		$password = $user_pw . $username;
		$saved_pw = '';

		$db_connect = new DatabaseAccess();
		$user_array = $db_connect->getTable('users', 'username', $username);
		$db_connect = null;

		if (!empty($user_array)) {
			// The assoc. array will always only have 1 user array inside since usernames are unique.
			// Get hashed & salted pw from the db.
			$saved_pw = $user_array[0]['Password'];			
		}

		if (password_verify($password, $saved_pw)) {
			$_SESSION['loggedin'] = true;
			$_SESSION['username'] = $username;
			// Default value for Admin column in db is false.
			$admin = $user_array[0]['Admin'];
			$_SESSION['admin'] = $admin;
			
			if (isset($_SESSION['Auth_Required'])) {
				unset($_SESSION['Auth_Required']);
			}

			return header("Location: http://localhost/test/employer.php"); 

		} else {
			echo "<div class='error-msg'>Invalid login information</div>";
		}
	}

	function main()
	{
		// Must be the names of the form inputs.
		$fields = array("username", "password");
		$validate = new FormValidate($fields);
		// Checks for POST request - returns assoc. array of form data.
		$completed_form = $validate->get_form_data();
		
		if ($completed_form) {
			login_user($completed_form);
		}
	}
	
	main();

?>
<div class='centered'>
	<h1>Login</h1>
	<form method="POST" action="login.php">
		<label>Username:</label> <input type="text" name="username" /></br>
		<label>Password:</label> <input type="password" name="password" /></br></br>
		<input class="centered-button" type="submit" value="Login" /></br></br>
	</form>	
</div>
<?php require 'footer.php'; ?>