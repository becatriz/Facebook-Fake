<?php
session_start();

require_once("../../../main/config.php");        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/public.php');           // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

$profile = new main();

$public = new access();

$profile->db = $public->db = $db;
$profile->settings = $public->settings = $page_settings;

// Load login info
if($_POST['t'] == '1') {
	
	// Get user
	$user = $profile->getUserByID($_POST['v1']);
	
	$profile_picture = $public->getImage($user['idu'],$user['p_image'],$user['image']);
	
	echo '<div class="brz-white">
        	<div class="brz-white brz-padding-8 brz-padding">
				<span onclick="loadModal(0);" class="brz-closebtn brz-hover-text-black brz-text-grey brz-text-bold brz-padding brz-round brz-small">X</span>
			</div>
		</div>
		<div class="brz-padding-8 brz-small brz-full brz-center brz-display-middle brz-padding">
		    <div class="brz-padding brz-clear brz-center brz-full">
			    <img style="width:70px;height:70px;" src="'.$TEXT['installation'].'/thumb.php?src='.$profile_picture.'&fol=a&w=245&h=245&q=100" class="brz-center brz-round-xlarge">
			</div>
		    <div class="brz-text-black">'.sprintf($TEXT['_uni-T_s_login_cnn_heading'],fixName(25,$user['username'],$user['first_name'],$user['last_name']),$TEXT['web_name']).'</div> 
		
		    <div class="brz-padding">
				<div onclick="locate(\''.$TEXT['installation'].'/index.php?profile='.$user['idu'].'&ref=login\');" style="width:50%;" class="brz-tag brz-round brz-border-blue-new brz-cursor-pointer brz-padding brz-pink-hd-2 brz-opacity-min brz-hover-opacity-off">'.$TEXT['_uni-LOG_IN'].'</div>
			</div>
			
			<div class="brz-padding">	
				<div onclick="locate(\''.$TEXT['installation'].'/index.php?profile='.$user['idu'].'&ref=signup\');" style="width:50%;" class="brz-tag brz-round brz-border brz-cursor-pointer brz-padding brz-body-it brz-text-black brz-opacity brz-hover-opacity-off">'.$TEXT['_uni-SIGN_UP'].'</div>
			</div>
		
		</div>';
		
}

?>