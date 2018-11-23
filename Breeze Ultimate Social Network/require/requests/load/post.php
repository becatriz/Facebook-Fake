<?php
session_start();

require_once("../../../main/config.php");        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// User class
$profile = new main();
$profile->db = $db;	

// Check credentials
if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
	
	// Pass user credentials
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Try to fetch logged user
	$user = $profile->getUser();
	
	// If user doesn't exists
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
		
		// Pass more user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		$profile->settings = $page_settings;

		// Fetch Full post on different page
		if($_POST['ff'] == 1 && isset($_POST['v1']) && is_numeric($_POST['v1']) && $_POST['v1'] >= 0) {
			
			// Get post
			$TEXT['content'] = $profile->getPost($_POST['v1'],$user);
			
			// Bind body
			$main = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
		
			// Get recent likers
			$TEXT['temp-content'] = $profile->getLovers(0,$_POST['v1'],$user,10);
		
			$TEXT['temp-data'] = $TEXT['_uni-REC_LIKES'];
			
			$TEXT['temp-data-id'] = 'RIGHT_RECENT_LIKES';	
			
		    $likes = (!empty($TEXT['temp-content'])) ? display('../../../themes/'.$TEXT['theme'].'/html/modals/boxed_users'.$TEXT['templates_extension']):'';

			// Parse ads
			$TEXT['content'] = $profile->parseAdd($page_settings['fi_add_post'],1).$likes; // Fixed
			
			// Bind ads
			$ads = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);
		
			// Display page
			echo $main.$ads;
		
		// Get post text for edit modal
		} elseif($_POST['ff'] == 2 && isset($_POST['v1']) && is_numeric($_POST['v1']) && $_POST['v1'] >= 0) {
			
			// get post text
			echo $profile->getPostText($_POST['v1'],$user);	
			
		// Load single post
		} elseif($_POST['ff'] == 3 && isset($_POST['v1']) && is_numeric($_POST['v1']) && $_POST['v1'] >= 0) {
	
			// Get single post
			echo $profile->getPost($_POST['v1'],$user);

		}  else {
			// Invalid inputs
			echo showError($TEXT['lang_error_script1']); 
		}
	}		
// Neither user nor administration logged
} else {
	echo showError($TEXT['lang_error_connection2']);
}
?>