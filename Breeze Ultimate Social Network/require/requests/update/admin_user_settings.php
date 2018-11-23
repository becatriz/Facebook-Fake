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
	
	// If fake cookies are set
	if(empty($admin['id'])){
		
		echo showError($TEXT['lang_error_connection2']);
		
	} else {	
	
		// Validate inputs 
		if(!isset($_POST['v1']) || !in_array($_POST['v1'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-1']);
		}elseif(!isset($_POST['v2']) || !in_array($_POST['v2'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-2']);
		}elseif(!isset($_POST['v3']) || !in_array($_POST['v3'],array("4","6","8"))) {
			echo showError($TEXT['_uni_admin_settings_error2-3']);
		}elseif(!isset($_POST['v4']) || !in_array($_POST['v4'],array("15","20","32"))) {
			echo showError($TEXT['_uni_admin_settings_error2-4']);
		}elseif(!isset($_POST['v5']) || !in_array($_POST['v5'],array("4","6","8"))) {
			echo showError($TEXT['_uni_admin_settings_error2-5']);
		}elseif(!isset($_POST['v6']) || !in_array($_POST['v6'],array("15","20","32"))) {
			echo showError($TEXT['_uni_admin_settings_error2-6']);
		}elseif(!isset($_POST['v7']) || !in_array($_POST['v7'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-7']);
		}elseif(!isset($_POST['v8']) || !in_array($_POST['v8'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-8']);
		}elseif(!isset($_POST['v9']) || !in_array($_POST['v9'],array("5","10","15"))) {
			echo showError($TEXT['_uni_admin_settings_error2-9']);
		}elseif(!isset($_POST['v10']) || !in_array($_POST['v10'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-10']);
		}elseif(!isset($_POST['v11']) || !in_array($_POST['v11'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-11']);
		}elseif(!isset($_POST['v12']) || !in_array($_POST['v12'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-12']);
		}elseif(!isset($_POST['v13']) || !in_array($_POST['v13'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-13']);
		}elseif(!isset($_POST['v14']) || !in_array($_POST['v14'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-14']);
		}elseif(!isset($_POST['v15']) || !in_array($_POST['v15'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-15']);
		}elseif(!isset($_POST['v16']) || !in_array($_POST['v16'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-1']);
		}elseif(!isset($_POST['v17']) || !in_array($_POST['v17'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-17']);
		}elseif(!isset($_POST['v18']) || !in_array($_POST['v18'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-18']);
		}elseif(!isset($_POST['v19']) || !in_array($_POST['v19'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-19']);
		}elseif(!isset($_POST['v20']) || !in_array($_POST['v20'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-20']);
		}elseif(!isset($_POST['v21']) || !in_array($_POST['v21'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-21']);
		}elseif(!isset($_POST['v22']) || !in_array($_POST['v22'],array("5","8","12"))) {
			echo showError($TEXT['_uni_admin_settings_error2-22']);
		}elseif(!isset($_POST['v23']) || !in_array($_POST['v23'],array("8","10","15"))) {
			echo showError($TEXT['_uni_admin_settings_error2-23']);
		}elseif(!isset($_POST['v24']) || !in_array($_POST['v24'],array("8","10","15"))) {
			echo showError($TEXT['_uni_admin_settings_error2-24']);
		}elseif(!isset($_POST['v25']) || !in_array($_POST['v25'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-25']);
		}elseif(!isset($_POST['v26']) || !in_array($_POST['v26'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-26']);
		}elseif(!isset($_POST['v27']) || !in_array($_POST['v27'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-27']);
		}elseif(!isset($_POST['v28']) || !in_array($_POST['v28'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-28']);
		}elseif(!isset($_POST['v29']) || !in_array($_POST['v29'],array("0","1"))) {
			echo showError($TEXT['_uni_admin_settings_error2-29']);
		}elseif(!isset($_POST['v30']) || isXSSED($_POST['v30'])) {
			echo showError($TEXT['_uni_admin_settings_error2-30']);
		}elseif(!isset($_POST['v31']) || isXSSED($_POST['v31'])) {
			echo showError($TEXT['_uni_admin_settings_error2-31']);
		}elseif(!isset($_POST['v32']) || isXSSED($_POST['v32'])) {
			echo showError($TEXT['_uni_admin_settings_error2-32']);
		}elseif(!isset($_POST['v33']) || isXSSED($_POST['v33'])) {
			echo showError($TEXT['_uni_admin_settings_error2-33']);
		} else {
	
			// Update settings
		    echo $profile->updateSettings(array($_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5'],$_POST['v6'],$_POST['v7'],$_POST['v8'],$_POST['v9'],$_POST['v10'],$_POST['v11'],$_POST['v12'],$_POST['v13'],$_POST['v14'],$_POST['v15'],$_POST['v16'],$_POST['v17'],$_POST['v18'],$_POST['v19'],$_POST['v20'],$_POST['v21'],$_POST['v22'],$_POST['v23'],$_POST['v24'],$_POST['v25'],$_POST['v26'],$_POST['v27'],$_POST['v28'],$_POST['v29'],$_POST['v30'],$_POST['v31'],$_POST['v32'],$_POST['v33']),$key_set3,$protection_set3);
		
		}			
	}
} else {
	echo showError($TEXT['lang_error_connection2']);
}
?>