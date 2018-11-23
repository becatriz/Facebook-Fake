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
	
	// If administration is logged and exists
	if(!empty($admin['id'])) {
		
		// Validate inputs
		if(isset($_POST['v1']) && isset($_POST['v2']) && isset($_POST['v3'])) {
			echo $profile->updateAdmin($admin,$_POST['v1'],$_POST['v2'],$_POST['v3']);	
		} else {
		    echo '<script>window.location.href = \''.$TEXT['installation'].'\'';	
		}
	} else {
		echo '<script>window.location.href = \''.$TEXT['installation'].'\'';
	}		
// No credentials
} else {
    echo '<script>window.location.href = \''.$TEXT['installation'].'\'';
}
?>