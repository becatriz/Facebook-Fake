<?php
require_once('./main/config.php');             // Import configuration
require_once('./require/main/database.php');   // Import database connection
require_once('./require/main/manage.php');     // Import all classes
require_once('./require/main/settings.php');   // Import settings
require_once('./language.php');                // Import language

// Add administration class
$profile = new admin();
$profile->db = $db;	

// Reset
$TEXT['content_redirect'] = $TEXT['posts'] = '';

// Check administration credentials
if((isset($_SESSION['a_username']) && isset($_SESSION['a_password'])) || (isset($_COOKIE['a_username']) && isset($_COOKIE['a_password']))) {
    
	// Pass properties
	$profile->username = (isset($_SESSION['a_username'])) ? $_SESSION['a_username'] : $_COOKIE['a_username'];
	$profile->password = (isset($_SESSION['a_password'])) ? $_SESSION['a_password'] : $_COOKIE['a_password'];
	
	// Try fetching logged administration
	$admin = $profile->getAdmin();
	
	// If administration is logged and exists
	if(!empty($admin['id'])) {
		
		// If a theme is requested
		if(isset($_GET['theme'])) {
			
			// Apply theme and redirect to themes page
			if($profile->applyTheme($_GET['theme'])) {
				header("Location: ".$TEXT['installation']."/index.php?admin=done&view=themes");
			}
		
		}
		
		// Generate navigation 
		$TEXT['page_navigation'] = $profile->genNavigation($admin);
		
		// Add CSS loader to body
		$TEXT['page_mainbody'] = '<div id="content-body" name="content-body" class="brz-main brz-clear brz-content-body" style="margin-left:250px;margin-top:44px;">
		                            <div align="center" id="temp_pre_loader_load_more_feed" class="brz-animate-zoom">
										<img src="'.$TEXT['installation'].'/themes/'.$page_settings['theme'].'/img/icons/loader.gif" style="margin-top:20%;" ></img>
									</div>
								</div>
								<script>refreshElements();</script>';
								
		// Display page
		echo display('themes/'.$TEXT['theme'].'/html/main/main'.$TEXT['templates_extension']);	

		// Check URL
		if(isset($_GET['view']) && in_array($_GET['view'],array("languages","backgrounds","extensions","patch","users","update","themes","websettings","usersettings","reports","adds","edit","search"))) {
			
			$val = (isset($_GET['v']) && !empty($_GET['v']) && !isXSSED($_GET['v'])) ? $_GET['v'] : FALSE ;
			
			// Use JS function to display requested page if page exists
			if($_GET['view'] == "themes") {
				echo '<script>loadThemes();</script>';
			} elseif($_GET['view'] == "languages") {
				echo '<script>loadLanguages();</script>';
			} elseif($_GET['view'] == "update") {
				echo '<script>updateWebsite();</script>';
			} elseif($_GET['view'] == "patch") {
				echo '<script>patchWebsite();</script>';
			} elseif($_GET['view'] == "backgrounds") {
				echo '<script>loadBackgrounds();</script>';
			} elseif($_GET['view'] == "extensions") {
				echo '<script>loadExtensions();</script>';
			} elseif($_GET['view'] == "websettings") {
				echo '<script>loadWebSettings();</script>';
			} elseif($_GET['view'] == "usersettings") {
				echo '<script>loadNewregsettings();</script>';
			} elseif($_GET['view'] == "reports") {
				echo '<script>loadReports(0,1,1);</script>';
			} elseif($_GET['view'] == "adds") {
				echo '<script>manageAdds();</script>';
			} elseif($_GET['view'] == "edit") {
				echo '<script>editAdmin();</script>';
			} elseif($_GET['view'] == "search" && $val) {
				echo '<script>$(\'.wefw4er3e\').val(\''.$val.'\');searchAdmin(0,0,1);</script>';
			} elseif($_GET['view'] == "users") {
				echo '<script>manageUsers(0,0,1);</script>';
			} else {
				echo '<script>loadStats();</script>';
			}		
		} else {
			
			// Else fetch full body
			echo '<script>loadStats();</script>';		
		}
			
    // Go to home
	} else {
		echo '<script>window.location.href = \''.$TEXT['installation'].'\' ;</script>';
	}	
	
// Welcome page for administration
} else {
	
	// Redirect to home
    echo '<script>window.location.href = \''.$TEXT['installation'].'\' ;</script>';
}
?>