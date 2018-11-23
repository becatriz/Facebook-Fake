<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// Global ARRAY
global $TEXT;

// User class
$profile = new main();
$profile->db = $db;	
$profile->settings = $page_settings;	

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
	
	// pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// try to fetch logged user
	$user = $profile->getUser();
	
	// if user exists
	if(!empty($user['idu'])){
		
		// Get type add or remove member
		$type = ($_POST['t']) ? 1 : 0;
		
		// Edit group member
		if($_POST['ff'] == 1) {

			// Fetch group user
			$group_user = $profile->getGroupUser($user['idu'],$_POST['v1']);
		
			// Get group
			$group = $profile->getGroup($_POST['v1']);
			
			// Allow user to add/remove members on the basis of group privacy
		    if($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu'] || ($group_user['group_status'] == 1 && $group['group_approval_type'] == '1')) {
			    
				// Add member to group
			    echo $profile->editMember($group['group_id'],$_POST['v2'],$user['idu'],$type,1);	
				
			} else {
			    echo $TEXT['_uni-No_allow_add_members'];
			}
		
		
		// Send like
		} elseif($_POST['ff'] == 2) {
			
			$pages = new pages();
			$pages->db = $db;	
			$pages->settings = $page_settings;

			// Send invitation
			echo $pages->sendInvite($_POST['v1'],$_POST['v2'],$user['idu']);	

		// Edit chat member
		} else {
            
			// Get form
		    $form = $profile->getChatFormByID($_POST['v1'],$user['idu']);
	
			// Confirm permissions
			if($form['form_id'] && $form['form_by'] == $user['idu']) {

			
		    	// Add or remove member
				echo $profile->editMember($form['form_id'],$_POST['v2'],$user['idu'],$type);
			
			} else {	
				echo '<div class="brz-margin brz-card brz-round brz-small brz-padding">'.$TEXT['_uni-You_permission2'].'</div>
                  	<button class="brz-new_btn brz-round brz-right brz-padding-standard brz-text-bold brz-tiny-2 brz-text-grey" onclick="requestMember('.$form['idu'].','.$form['form_id'].','.$_POST['v2'].','.$type.')"><img class="nav-item-text-inverse-big brz-img-following" src="'.$TEXT['DATA-IMG-7'].'">&nbsp;'.$TEXT['_uni-Yes'].'</button>';

			}
		}
	} else {
		echo showError($TEXT['lang_error_connection2']);   // user doesn't exists
	}
} else {
	echo showError($TEXT['lang_error_connection2']);       // user is not logged
}
?>