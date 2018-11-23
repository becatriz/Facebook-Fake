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
$profile->settings = $page_settings;

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties and credentials
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Fetch logged user
	$user = $profile->getUser();
	
	// If user exists
	if(!empty($user['idu'])){
		
		// Pass settings and user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		
		// Create express bar
		$express_open = '<div id="right_express" class="brz-express-uni brz-no-overflow brz-clear brz-hide-small brz-border-left">';
		$express_close = '</div>';
		
		// Fetch friends suggestions
		$express_suggests = ($page_settings['feature_expresssuggestions']) ? $profile->expressSuggestions($user['idu'],$page_settings['suggestions_limit']) : '' ;
		
		// Fetch friends activity
		$express_activity_content = ($page_settings['feature_expressactivity']) ? $profile->expressActivity($user['idu']) : '';
		$express_activity = (empty($express_activity_content)) ? '<div id="EXPRESS_ACTIVITY" style="max-height:180px!important;"  class="brz-clear brz-margin-dead brz-small nicescrollit brz-border-bottom"></div>': '<div id="EXPRESS_ACTIVITY" style="max-height:180px!important;"  class="brz-clear brz-margin-dead brz-small nicescrollit brz-border-bottom">'.$express_activity_content.'</div>';
		
		// Fetch online friends
		$express_friends_content = ($page_settings['feature_expressfriends']) ? $profile->expressFriends($user['idu'],$page_settings['active_limit']) : '';
		$express_friends = (empty($express_friends_content)) ? '<div id="EXPRESS_FRIENDS" style="max-height:180px!important;"  class="brz-clear brz-margin-dead brz-small nicescrollit brz-border-bottom"></div>': '<div id="EXPRESS_FRIENDS" style="max-height:180px!important;"  class="brz-clear brz-margin-dead brz-small nicescrollit brz-border-bottom">'.$express_friends_content.'</div>';
	
	    // Add express show
		echo ($page_settings['feature_expresssuggestions'] || $page_settings['feature_expressactivity'] || $page_settings['feature_expressfriends']) ? $express_open.$express_suggests.$express_activity.$express_friends.$express_close.'<script>$(".nicescrollit").niceScroll();</script>' : '';

	}
}
?>