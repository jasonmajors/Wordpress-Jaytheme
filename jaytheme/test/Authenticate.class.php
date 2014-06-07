<?php

	class Authenticate
	{
		public function login_required_redirect($url, $admin_only=false)
	    {	
	    	if ($admin_only) {
	    		// Default value for admin is 0.
	    		if (!isset($_SESSION['admin']) || $_SESSION['admin'] == false) {
	    			$_SESSION['Auth_Required_Msg'] = "Admin rights required. </br>";
	    			return header("Location: http://localhost" . $url);
	    		}
	    	}
	    	elseif (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
	            $_SESSION['Auth_Required_Msg'] = "Login required.</br>";
	            return header("Location: http://localhost" . $url);
	        }
	    }

	    public function authenticated()
	    {
	    	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] = true) {
				return true;
			} else {
				return false;
			}
	    }

	    public function redirect_user($url)
	    {
	    	$loggedin = $this->authenticated();
	    	if ($loggedin) {
	    		return header("Location: http://localhost" . $url);
	    	}
	    }
	}