<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/manage.php');           // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// Global ARRAY
global $TEXT;

// Administration login class
$log = new AdminLogin();
$log->db = $db;
	
// If values are set
if(isset($_POST['v1']) && !empty($_POST['v1']) && isset($_POST['v2']) && !empty($_POST['v2'])) {

	// pass properties
	$log->username = $_POST['v1'];
	$log->password = $_POST['v2'];

	// Perform login
	$data = $log->start();

	if($data == 1) {
		// Success
		echo '<script>window.location.href = \''.$TEXT['installation'].'/manage/home\'</script>';
	} else {
		// Display error
		echo '<script>	
            	switchPage(\'#animated-loader-page\',\'#admin-page\');
		    	switchMessage("'.$data.'",0);		
			</script>';
	}
} else {
	// echo error
	echo '<script>
            switchPage(\'#animated-loader-page\',\'#admin-page\');
		    switchMessage("'.$TEXT['_uni-login-5'].'",2);	
		</script>';
}
?>