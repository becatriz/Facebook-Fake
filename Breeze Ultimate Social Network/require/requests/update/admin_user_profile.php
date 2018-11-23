<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/manage.php');           // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// New administration class
$profile = new admin();
$profile->db = $db;

// If SESSIONS set verify administration
if((isset($_SESSION['a_username']) && isset($_SESSION['a_password'])) || (isset($_COOKIE['a_username']) && isset($_COOKIE['a_password']))) {
    
	// Pass properties
	$profile->username = (isset($_SESSION['a_username'])) ? $_SESSION['a_username'] : $_COOKIE['a_username'];
	$profile->password = (isset($_SESSION['a_password'])) ? $_SESSION['a_password'] : $_COOKIE['a_password'];
	
	// Try fetching logged administration
	$admin = $profile->getAdmin();
	
	// If fake cookies are set
	if(empty($admin['id'])){
		
		echo showError($TEXT['lang_error_connection2']);
		
	} else {

        // Check if user is suspended	
		if(isset($_POST['v7']) && $_POST['v7'] == 1) {
			
			// Block user from reporting
			$b_users  = $b_posts  = $b_comments = '1';
			
			// Remove profile verification too
			$verify = '0';
			
			// Block user login access 
			$state = '3';
			
		} else {
			
			// Get blocking properties
            $b_users  = ($_POST['v4']) ? '1' : '0' ;
	        $b_posts  = ($_POST['v5']) ? '1' : '0' ;
	        $b_comments = ($_POST['v6']) ? '1' : '0' ;

            // Check whether user is set to verified			
			$verify = ($_POST['v2']) ? '1' : '0' ;
	        
			// Check whether user email is set to verify
			$state  = (isset($_POST['v3']) && $_POST['v3'] == 1) ? '1' : '2' ;        
		
		}
		
		// Security check
		if(isset($_POST['v1']) && is_numeric($_POST['v1']) && $_POST['v1'] > 0) {	
			
			// Update user profile
			echo $profile->updateUserprofile($_POST['v1'],$state,$verify,$b_users,$b_posts,$b_comments,$_POST['v8'],$_POST['v9'],$_POST['v10'],$_POST['v11'],$_POST['v12'],$_POST['v13'],$_POST['v14'],$_POST['v15'],$_POST['v16']);
		
		}					
	}
// No credentials set
} else {
	echo showError($TEXT['lang_error_connection2']);
}
?>