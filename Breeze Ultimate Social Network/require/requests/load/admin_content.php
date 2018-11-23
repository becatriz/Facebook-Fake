<?php
session_start();

require_once("../../../main/config.php");        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/manage.php');           // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// New administration class
$profile = new admin();
$profile->db = $db;	

// Verify administration
if((isset($_SESSION['a_username']) && isset($_SESSION['a_password'])) || (isset($_COOKIE['a_username']) && isset($_COOKIE['a_password']))) {
    
	// Pass properties
	$profile->username = (isset($_SESSION['a_username'])) ? $_SESSION['a_username'] : $_COOKIE['a_username'];
	$profile->password = (isset($_SESSION['a_password'])) ? $_SESSION['a_password'] : $_COOKIE['a_password'];
	
	// Try fetching logged administration
	$admin = $profile->getAdmin();
	
	// If administration is logged display sponsor settings
	if(!empty($admin['id'])) {
	
		// Load ads settings
		if($_POST['ff'] == 1) {
	
			// Get ads settings
			$TEXT['content'] = $profile->getManageadds($admin,$page_settings);
            echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
		
		// Load registration settings
		} elseif($_POST['ff'] == 2) {
	
			// Get registration settings
			$TEXT['content'] = $profile->newRegsettings($admin,$page_settings);
            echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);

		// Load manage reports
		} elseif($_POST['ff'] == 3) {
	
			// Add starting point
			$from = (isset($_POST['f']) && is_numeric($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
		
			// Add filter to reports
			$filter = (isset($_POST['v1']) && is_numeric($_POST['v1'])) ? $_POST['v1'] : '1' ;

			// Apply type
			if($from == 0) {
				
				// Get reports
		    	$TEXT['posts'] = $profile->getReports($admin,$from,$filter);
				$main_body = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	
		    
				// Get report filters 
				$TEXT['content'] = display('../../../themes/'.$TEXT['theme'].'/html/modals/filter_reports'.$TEXT['templates_extension']);
				$right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);	
			
				// Bind and display full body
				echo $main_body.$right_body;
			
			} else {
			
				// Else get reports only
				echo $profile->getReports($admin,$from,$filter);
			
			}
		
		// Load admin home
		} elseif($_POST['ff'] == 4) {
	
	        // Add JS loader
			$TEXT['posts'] =  '<div class="brz-new-container brz-white"><div id="graphs_top"></div></div><script>loadRegChart(1);</script>';
			$left_large = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
			$right_small = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);
		
			// Admin dashboard
			echo $left_large.$right_small;
	
		// Load admin themes
		} elseif($_POST['ff'] == 5) {

			// Get all themes
			$TEXT['content'] = $profile->getThemes($admin);
			echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
	
	    // Load admin charts and graphs
		} elseif($_POST['ff'] == "graphs_top") {
	
			echo $profile->getRegChart($admin,$_POST['v1']);
	
	    // Load admin web settings
		} elseif($_POST['ff'] == 6) {
	
	        // Get website settings
			$TEXT['content'] = $profile->getWebsettings($admin,$page_settings);
	        echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	
	
	    // Load admin manage users
		} elseif($_POST['ff'] == 7) {
	
			// Add starting point
			$from = (isset($_POST['f']) && is_numeric($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
		
			// Add filter to users
			$filter = (isset($_POST['v1']) && is_numeric($_POST['v1'])) ? $_POST['v1'] : '0' ;

			// Apply type
			if($from == 0) {
			
				// Get users
		    	$TEXT['posts'] = $profile->manageUsers($from,$filter);
				$main_body = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	
		    
				// Get user filters 
				$TEXT['content'] = display('../../../themes/'.$TEXT['theme'].'/html/modals/filter_manage_users'.$TEXT['templates_extension']);
				$right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);	
			
				// Display full body
				echo $main_body.$right_body;
			
			} else {
			
				// Else get users only
				echo $profile->manageUsers($from,$filter);
			
			}
		
		// Load edit user profile for admin
		} elseif($_POST['ff'] == 8 && isset($_POST['v1']) && is_numeric($_POST['v1']) && $_POST['v1'] > 0) {
	
			// Get users
		    $TEXT['posts'] = $profile->editUser($_POST['v1']);
			echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	
		
        // Load edit admin		
		} elseif($_POST['ff'] == 9) {
	
	        // Get admin password change wizard
		    $TEXT['content'] = $profile->getEditAdmin($admin);
	        echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	

		// Load edit post backgrounds		
		} elseif($_POST['ff'] == 10) {
	
	        // Get admin password change wizard
		    $TEXT['content'] = $profile->getPostBackgrounds($admin);
	        echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	

	    // Activate post backgrounds		
		} elseif($_POST['ff'] == 11) {
	
	        // Get admin password change wizard
		    $TEXT['content'] = $profile->activateBackground($_POST['t']);
	        echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	

		// Reorder post backgrounds
		} elseif($_POST['ff'] == 12) {
	
	        // Get admin password change wizard
		    $TEXT['content'] = $profile->reorderBackground($_POST['t']);
	        echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	

		// Load languages manager
		} elseif($_POST['ff'] == 13) {
	
	        // Get admin password change wizard
		    $TEXT['content'] = $profile->loadLanguages($admin);
	        echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	
      
	    // Save language as default
		}  elseif($_POST['ff'] == 14) {
	
	        // Get admin password change wizard
		    $TEXT['content'] = $profile->saveLanguage($_POST['t']);
	        echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	

		// Load extensions
		}  elseif($_POST['ff'] == 15) {
	
	        // Get admin password change wizard
		    $TEXT['content'] = $profile->loadExtensions();
	        echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	

		// Load page categories
		} elseif($_POST['ff'] == 16) {
	
	        // Get admin password change wizard
		    $TEXT['content'] = $profile->loadCategoris();
	        echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	

		// Add new category
		} elseif($_POST['ff'] == 17) {
	
	        // Get admin password change wizard
		    $TEXT['content'] = $profile->addCategory($_POST['v1'],$_POST['t']);
	        echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	

		// Delete category
		} elseif($_POST['ff'] == 18) {
	
	        // Get admin password change wizard
		    $TEXT['content'] = $profile->deleteCategory($_POST['v1']);
	        echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	

		// Website updates
		} elseif($_POST['ff'] == 19) {
	
		    $TEXT['content'] = $profile->websiteUpdates($_POST['v1']);
	        echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	

		// Website patches
		} elseif($_POST['ff'] == 20) {
	
		    $TEXT['content'] = $profile->websitePatches($_POST['v1']);
	        echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	

		}
		
	// If nothing set redirect to home
	} else {
		echo '<script>window.location.href = \''.$TEXT['installation'].'\'</script>';
	}	
	
// Go to homepage as administrations credentials not found
} else {
	echo '<script>window.location.href = \''.$TEXT['installation'].'\'</script>';
}
?>