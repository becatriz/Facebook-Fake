<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// Login class
$log = new Login();
$log->db = $db;

// If values are set
if(isset($_POST['v1']) && !empty($_POST['v1']) && isset($_POST['v2']) && !empty($_POST['v2'])) {

	// Pass properties
	$log->installation = $TEXT['installation'];
	$log->username = $_POST['v1'];
	$log->password = $_POST['v2'];
	$log->cookie = (isset($_POST['t']) && is_numeric($_POST['t'])  && $_POST['t'] == 1) ? 1 : 0;

	// Pass settings
	$log->emails_verification = $page_settings['emails_verification'];
  
	// Perform
	$data = $log->start();
	
	if($data == 1) {

		// Login success
		echo '<script>location.reload();</script>';

	} else {
		// Login fail - show error
		echo '<script>
				switchPage(\'#animated-loader-page\',\'#login-page\');
				switchMessage("'.$data.'",0);	
			</script>';		
	}
} else {
	// Empty credentials
	echo '<script>
			switchPage(\'#animated-loader-page\',\'#login-page\');
			switchMessage("'.$TEXT['_uni-login-5'].'",2);		
		</script>';
}
?>