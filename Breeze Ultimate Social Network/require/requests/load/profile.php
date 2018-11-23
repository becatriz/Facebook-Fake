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
	
	// Pass properties to fetch logged user if exists
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];

	// Try fetching logged user
	$user = $profile->getUser();
	
	// Pass administration settings
	$profile->settings = $page_settings;
	
	// If user doesn't exists
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
	
		// Pass user properties
		$profile->followings = $profile->listFollowings($user['idu']);

		// Validate inputs
		if(isset($_POST['p']) && is_numeric($_POST['p']) && $_POST['p'] > 0) {
	
			// Get user top and intro
			list($profile_top,$intro) = $profile->getProfileTop($_POST['p'],$user,1);
		
			// Reset
			$edit = '';
		
			// Add update cover and photo buttons if user id matches
			if($_POST['p'] == $user['idu']) {
				
				// Add image
				$TEXT['temp-image'] = $user['image'];
		
				// Parse background images
				parseBackgrounds($TEXT['ACTIVE_BACKGROUNDS']);
		
				$TEXT['content'] = display('../../../themes/'.$TEXT['theme'].'/html/main/post_form'.$TEXT['templates_extension']);
				$edit = '<script>
							$("#uPc-f-2").on(\'change\', function(event){	
								$(document).on(\'change\', \':file\', function () {
									if($("#uPc-f-2").val()) {
										// Start buttonloader
										smartLoader(1,\'#btn-cover-chn\');
	
										// Submit photo form	
   		 								document.getElementById("uPc-2").submit();				
									}
								});
							}); 
							$("#uPp-f-1").on(\'change\', function(event){	
								$(document).on(\'change\', \':file\', function () {
									if($("#uPp-f-1").val()) {
				
										smartLoader(1,\'#btn-photo-chn\');
	
										// Submit photo form	
   	 									document.getElementById("uPp-1").submit();
									}
								});
							});
						</script>';
			}
		    
			// fetch feeds 
		    $TEXT['posts']   = $profile->getFeeds(0,$_POST['p'],NULL,1);
  
			// Display full profile
			$main_body = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
  		
			// Get some stuff
			$TEXT['content'] = $intro.$profile->getBoxedUsers($user['idu']);
		
			echo $profile_top.$edit.$main_body.display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);
			
		} else {
			// Invalid inputs
			echo showError($TEXT['lang_error_script1']);
		}
	}

// No credentials	
} else {
	echo showError($TEXT['lang_error_connection2']);
}
?>