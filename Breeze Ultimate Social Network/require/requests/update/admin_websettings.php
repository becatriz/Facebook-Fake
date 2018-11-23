<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/manage.php');           // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language
require_once('../../main/presets/presets.php');  // Import preset arrays

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
	
	// If fake cookies set
	if(empty($admin['id'])){
		
		echo showError($TEXT['lang_error_connection2']);
		
	} else {
		
		// Form validation
		if(!isset($_POST['v1']) || empty($_POST['v1'])) {
			echo showError($TEXT['_uni_admin_settings_error-1']);
		} elseif(!isset($_POST['v2']) || empty($_POST['v2'])) {
			echo showError($TEXT['_uni_admin_settings_error-2']);
		} elseif(!isset($_POST['v3']) || empty($_POST['v3']) || !in_array($_POST['v3'] ,array("white","grey","light-grey","blue","red","green","pink","pink-light","amber","teal"))) {
			echo showError($TEXT['_uni_admin_settings_error-3']);
		} elseif(!isset($_POST['v4']) || empty($_POST['v4']) || !in_array($_POST['v4'] ,array("5","10","15"))) {
			echo showError($TEXT['_uni_admin_settings_error-4']);
		} elseif(!isset($_POST['v5']) || empty($_POST['v5']) || !in_array($_POST['v5'] ,array("9","12","18"))) {
			echo showError($TEXT['_uni_admin_settings_error-5']);
		} elseif(!isset($_POST['v6']) || empty($_POST['v6']) || !in_array($_POST['v6'] ,array("10","15","20"))) {
			echo showError($TEXT['_uni_admin_settings_error-6']);
		} elseif(!isset($_POST['v7']) || empty($_POST['v7']) || !in_array($_POST['v7'] ,array("10","15","20"))) {
			echo showError($TEXT['_uni_admin_settings_error-7']);
		} elseif(!isset($_POST['v8']) || empty($_POST['v8']) || !in_array($_POST['v8'] ,array("6","12","18"))) {
			echo showError($TEXT['_uni_admin_settings_error-8']);
		} elseif(!isset($_POST['v9']) || empty($_POST['v9']) || !in_array($_POST['v9'] ,array("15","20","35"))) {
			echo showError($TEXT['_uni_admin_settings_error-9']);						
		} elseif(!isset($_POST['v15']) || empty($_POST['v15']) || !in_array($_POST['v15'] ,array("500","1000","2000"))) {
			echo showError($TEXT['_uni_admin_settings_error-10b']);						
		} elseif(!isset($_POST['v10']) || empty($_POST['v10']) || !in_array($_POST['v10'] ,array("100","150","200"))) {
			echo showError($TEXT['_uni_admin_settings_error-10']);						
		} elseif(!isset($_POST['v11']) || empty($_POST['v11']) || !in_array($_POST['v11'] ,array("75","90","100"))) {
			echo showError($TEXT['_uni_admin_settings_error-11']);						
		} elseif(!isset($_POST['v12']) || empty($_POST['v12']) || !in_array($_POST['v12'] ,array("1280","2000","10000"))) {
			echo showError($TEXT['_uni_admin_settings_error-12']);						
		} elseif(!isset($_POST['v13']) || empty($_POST['v13']) || !in_array($_POST['v13'] ,array("1280","2000","10000"))) {
			echo showError($TEXT['_uni_admin_settings_error-13']);						
		} elseif(!isset($_POST['v14']) || empty($_POST['v14']) || !in_array($_POST['v14'] ,array("1280","2000","10000"))) {
			echo showError($TEXT['_uni_admin_settings_error-14']);						
		} elseif(!isset($_POST['v16']) || !in_array($_POST['v16'] ,array("1","0"))) {
			echo showError($TEXT['_uni_admin_settings_error-15']);						
		} elseif(!isset($_POST['v17']) || !in_array($_POST['v17'] ,array("1","0"))) {
			echo showError($TEXT['_uni_admin_settings_error-16']);						
		} elseif(!isset($_POST['v18']) || empty($_POST['v18']) || !in_array($_POST['v18'] ,array("6","12","18"))) {
			echo showError($TEXT['_uni_admin_settings_error-18']);
		} elseif(!isset($_POST['v19']) || empty($_POST['v19']) || !in_array($_POST['v19'] ,array("1000","2000","5000"))) {
			echo showError($TEXT['_uni_admin_settings_error-19']);
		} elseif(!isset($_POST['v20']) || empty($_POST['v20']) || !in_array($_POST['v20'] ,array("1280","2000","10000"))) {
			echo showError($TEXT['_uni_admin_settings_error-20']);
		} elseif(!isset($_POST['v21']) || !in_array($_POST['v21'] ,array("1280","2000","10000"))) {
			echo showError($TEXT['_uni_admin_settings_error-21']);
		} elseif(!isset($_POST['v22']) || !in_array($_POST['v22'] ,array("2","5","10"))) {
			echo showError($TEXT['_uni_admin_settings_error-22']);
		} else {
			
			// Update settings using input protection
		    echo $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5'],$_POST['v6'],$_POST['v7'],$_POST['v8'],$_POST['v9'],$_POST['v10'],$_POST['v11'],$_POST['v12'],$_POST['v13'],$_POST['v14'],$_POST['v15'],$_POST['v16'],$_POST['v17'],$_POST['v18'],$_POST['v19'],$_POST['v20'],$_POST['v21'],$_POST['v22']),$key_set2,$protection_set2);

		
		}			
	}
} else {
	echo showError($TEXT['lang_error_connection2']);
}
?>