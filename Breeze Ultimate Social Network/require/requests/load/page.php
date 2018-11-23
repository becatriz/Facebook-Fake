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
	
	// Preload switcer
	$pre_load = '<script>$("#brz-GNAV_ALL").find("img").hide();</script>';
	$update_tab = '$("#page_bar_{$TEXT->TEMP_TAB_ID}").removeClass(\'brz-hvr-active\').addClass(\'brz-hvr-active-1\');';
	
	// If user doesn't exists
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
	
		// Pass user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		
		// Load complete page
		if($_POST['ff'] == '1') {
		    
			$page = $profile->getPage($_POST['p']);
			
			// If page_access exists
			if($page) {
			
				// Get page content
				list($page,$page_user,$page_role,$page_top,$page_feeds,$about,$d_nav_items) = $profile->getPageTop($_POST['p'],$user,1,$page);
 
                // Reset
                $TEXT['TEMP-NAV-UPDATE'] = '';

            	// Add page feeds
            	$TEXT['posts'] = $page_feeds;

                // Add group id to html template
			    $TEXT['TEMP_PAGE_ID'] = $page['page_id'];
				
				// Set data for post form
				$TEXT['temp-image'] = $page['page_icon'];
				$TEXT['temp-page-image'] = $page['page_icon'];
				$TEXT['temp-page-name'] = $page['page_name'].' '.$profile->verifiedBatch($page['page_verified']);
				$TEXT['temp-page-username'] = $TEXT['temp-page-type'] = ($page['page_username']) ? '<span class="brz-text-bold brz-medium brz-text-super-grey brz-opacity">'.$TEXT['sign-at'].$page['page_username'].'</span>' : '' ;
		
		        // Parse background images
				parseBackgrounds($TEXT['ACTIVE_BACKGROUNDS']);
		
			    $TEXT['content'] = ($page_role['page_role'] > 3) ? display('../../../themes/'.$TEXT['theme'].'/html/main/post_form_page'.$TEXT['templates_extension']):'';
			
			    // Allow update page icon
			    if($page_role['page_role'] > 3) {
				    $TEXT['TEMP-NAV-UPDATE'] = '<div class="brz-display-bottomright brz-padding brz-small">					 
									<form id="uGp-2" name="uGp-2" action="'.$TEXT['installation'].'/require/requests/update/page_profile_photo.php" onsubmit ="return false;" method="POST" enctype="multipart/form-data" target="uGp-t-2">   
                                        <label id="btn-photo-chn" class="brz-button brz-hover-btnn3 brz-text-white brz-medium" for="uGp-f-2" ><i class="fa fa-camera brz-shadow"></i> &nbsp;</label>
										<input style="display:none!important;" name="uGp-f-2" id="uGp-f-2" type="file"/>
										<input id="page-pic-form-id" name="page-pic-form-id" class="brz-hide" value="'.$page['page_id'].'"/>
                                        <iframe id="uGp-t-2" name="uGp-t-2" src="" style="display: none"></iframe>
                                    </form>	
									
							</div>
							<script>
							$("#uGp-f-2").on(\'change\', function(event){	
								$(document).on(\'change\', \':file\', function () {
									if($("#uGp-f-2").val()) {
										// Start buttonloader
										smartLoader(1,\'#btn-photo-chn\');
	
										// Submit photo form	
   		 								document.getElementById("uGp-2").submit();				
									}
								});
							});</script>							
							';	
				}
				
			    // Parse Navigation for desktops
				$TEXT['TEMP-NAV-ITEMS'] = $d_nav_items;
				$d_nav = '<style>@media screen and (min-width:992px){#P_NAV_LEFT {display:none!important;}}.G-PAGE-NAV{display:block!important;}</style>';
				$d_nav .= display('../../../themes/'.$TEXT['theme'].'/html/navigations/main_page'.$TEXT['templates_extension']);
				$d_nav .= '<script>groupNav();</script>';

				// Display full group
				$main_body = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']).$d_nav;
		
				// Right side body
				$TEXT['content'] = $about.$profile->getPersonalities($user,$page_settings['trendind_per_limit']);
				
				$add_tab = ($page_role['page_role'] > 4) ? display('../../../themes/'.$TEXT['theme'].'/html/tab/page_tab'.$TEXT['templates_extension']) : '';
			
            	echo $add_tab.$page_top.$main_body.display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);				
		
				
			} else {		
				echo '<script>loadHome();</script>';
			}
			
		// Search page invites
		} elseif($_POST['ff'] == '3') {	
			
			// Get page
			$page = $profile->getPage($_POST['p']);
		
		    // Search i page exists		
		    echo ($page['page_id']) ? $profile->searchInvites($user,$_POST['v1'],$page) : '' ;			
		
        // Pages list		
		} elseif($_POST['ff'] == '2') {	
			
			// Get page class
			$pages = new pages();
			$pages->db = $db;
			$pages->followings = $profile->followings;
	
			// Add starting point
		    $from = (isset($_POST['f']) && is_numeric($_POST['f'])) ? $_POST['f'] : 0;
		
		    // Search i page exists		
		    echo $pages->getAllPages($user['page_feeds'],5,$from);			
		
        // Page activity log		
		} elseif($_POST['ff'] == '10') {	
			
			// Add start up
		    $from = (isset($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
		
			// Fetch page role
			$page_role = $profile->getPageRole($user['idu'],$_POST['p']);
		
			// Get page
			$page = $profile->getPage($_POST['p']);
		
		    // Allow user to add members on the basis of group privacy
		    if($page_role['page_role']) {
			    
				// Get page class
				$pages = new pages();
				$pages->db = $db;
				$pages->followings = $profile->followings;
				
				// Get member requests
				$TEXT['content'] = ($from > 0) ? '' : '<div class="brz-new-container brz-text-black brz-padding-8 brz-padding"><span class="brz-text-bold">'.$TEXT['_uni-Activity_log'].'</span></div>' ;
				
			    $TEXT['posts'] = $profile->getPageLog($page['page_id'],$_POST['f'],$page_settings['group_log_per_page']);
				
				echo ($from > 0) ? $pre_load.$TEXT['posts'] : $pre_load.display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']).'<script>$("#all_posts").addClass("brz-new-container");</script>';
				
			} else {
			    $TEXT['posts'] = bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-No_allow_activity_log_page']); 
				echo $pre_load.display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
			}
			
	    // Load page feeds
	    } elseif($_POST['ff'] == '15') {	
			
			// Add start up
		    $from = (isset($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
			
			// Add filter
		    $filter = (isset($_POST['t']) && in_array($_POST['t'],array(1,2))) ? $_POST['t'] : 0 ;

			if($_POST['p'] && is_numeric($_POST['p'])) {
				
				// Get page
				$page = $profile->getPage($_POST['p']);
		
				// Add group id to html template
			    $TEXT['TEMP_PAGE_ID'] = $page['page_id'];
				$TEXT['temp-image'] = $page['page_icon'];
				$TEXT['temp-page-image'] = $page['page_icon'];
				$TEXT['temp-page-username'] = $TEXT['temp-page-name'] = $page['page_name'];
		      
				// Parse background images
				parseBackgrounds($TEXT['ACTIVE_BACKGROUNDS']);
				
				$page_role = $profile->getPageRole($user['idu'],$_POST['p']);
				
                $TEXT['content'] = ($from == 0 && $page_role && $page_role['page_role'] > 3) ? display('../../../themes/'.$TEXT['theme'].'/html/main/post_form_page'.$TEXT['templates_extension']):'';
			
				$TEXT['posts'] = $profile->getPageFeeds($user,$from,$_POST['p'],NULL,1,null,$filter);
				
				echo ($from > 0) ? $pre_load.$TEXT['posts'] : $pre_load.display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
				
			} else {
		
				// Get page class
				$pages = new pages();
				$pages->db = $db;
				$pages->followings = $profile->followings;

				$pages_feeded = $user['page_feeds'];
		
				$TEXT['posts'] = ($pages_feeded) ? $profile->getPageFeeds($user,$from,$pages_feeded,null,$filter) : bannerIt('feeds'.mt_rand(1,4),$TEXT['_uni-lang_load_no_feeds'],$TEXT['_uni-No_feeds-inf2']);
	
				$return = ($from > 0) ? $TEXT['posts'] : display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
	
            	// Add more stuff if user lands directly on groups page
            	if($_POST['bo'] == 1) {
               	
					// Get pages
					$pages_all = $pages->getAllPages($user['page_feeds'],7);
		
					// Get suggestions and trending users
					$groups = ($page_settings['groups_on_home']) ? $profile->getGroups($user['idu'],15) : '';
                	$boxed_users = ($page_settings['feature_pe_tren_on_home']) ? $profile->getBoxedUsers($user['idu']) : '';
                	$personalities = ($page_settings['feature_trending_on_home']) ? $profile->getPersonalities($user,$page_settings['trendind_per_limit']) : '';
                	$TEXT['content'] = $pages_all.$boxed_users.$groups.$personalities;
			   
                	$return .= display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);         
           	 	}
                
            	echo $pre_load.$return ;

	    	}
			
		// Load page settings
		} elseif($_POST['ff'] == '9') {
			
		   	// Fetch page role
			$page_role = $profile->getPageRole($user['idu'],$_POST['p']);
		
			// Get page
			$page = $profile->getPage($_POST['p']);
			
			// If admin
			if($page_role['page_role'] > 4) {

				// General settings
                if($_POST['v1'] == 1) { 
				    
					// Verify Name length
			    	if(strlen($_POST['v2']) < 6 || strlen($_POST['v2']) > 32) {
			
			        	$return = showError($TEXT['_uni-Create_a_page_err-4']);
			
			    	// Page email check
					} elseif((!filter_var($db->real_escape_string($_POST['v3']), FILTER_VALIDATE_EMAIL) || strlen($_POST['v3']) < 3 || strlen($_POST['v3']) > 62)  && !empty($_POST['v3'])) {
			
			        	$return = showError($TEXT['_uni-signup-4']);
			
			    	// Page location check
					} elseif(strlen($_POST['v4'])> 50) { 

				    	$return = showError($TEXT['_uni-error_location_len23']);	
					
		        	// Page website check			
		        	} elseif((strlen($_POST['v5']) > 64 || !filter_var($_POST['v5'], FILTER_VALIDATE_URL)) && !empty($_POST['v5'])){ 
				
				    	$return = showError($TEXT['_uni-error_web_in']);	
					
					// Page description check			
					} elseif(strlen($_POST['v6']) > 2000){ 
				
				   		$return = showError($TEXT['_uni-error_bio_in3']);
					
		        	} else {
					
						$pages = new pages();
				    	$pages->db = $db;
				    
						$return = ($pages->updatePage($_POST['p'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5'],$_POST['v6'],$user['idu'])) ? showSuccess($TEXT['_uni-page_updatd'],NULL,'loadPage('.$page['page_id'].',1,1)')  : showNotification($TEXT['_uni-No_changes']);
					}			
				
				// Update page username
				} elseif($_POST['v1'] == 3) {
					
					if(!empty($_POST['v2']) && $page['page_username'] !== $_POST['v2'] && ($profile->isUsernameExists($_POST['v2'],2))) {
			
			            // Verify whether user name exists
			            $return = showError($TEXT['_uni-Username_exists']);
			
			        // page username check
		            } elseif(!empty($_POST['v2']) && (!ctype_alnum(trim($_POST['v2'])) || is_numeric(trim($_POST['v2'])))) {
			
			            // Allow only valid chars for username
			            $return = showError($TEXT['_uni-signup-9']);
			    
				    // page username check
				    } elseif(!empty($_POST['v2']) && (strlen($_POST['v2']) < $page_settings['username_min_len'] || strlen($_POST['v2']) > $page_settings['username_max_len'])) {
			
			            $return = showError(sprintf($TEXT['_uni-signup-1'],$page_settings['username_min_len'],$page_settings['username_max_len']));
	
				    } else {
					
					    $pages = new pages();
				        $pages->db = $db;
				    
					    $return = ($pages->updateUsername($_POST['p'],$_POST['v2'],$user['idu'])) ? showSuccess($TEXT['_uni-page_updatd2'],NULL,'loadPage('.$page['page_id'].',1,1)')  : showNotification($TEXT['_uni-No_changes']);
				
				    }
					
				// Add a new role
				} elseif($_POST['v1'] == 4) {
					
					$pages = new pages();
					$pages->db = $db;
					
					// Get target user
					$get_user = $profile->getUserByUsername($_POST['v2']);
					
					if(empty($_POST['v2']) || !$get_user['idu']) {
	
			            // Verify whether user name doesn't exists
			            $return = showError($TEXT['_uni-No_username']);
			
		            } elseif($pages->isRoleExists($_POST['p'],$get_user['idu'])) {
						
						// If role already exists
			            $return = showError($TEXT['_uni-role_exists']);
						
					} elseif(!in_array($_POST['v3'],array(2,3,4,5))) {
						
						// If role already exists
			            $return = showError($TEXT['_uni-role_type_no']);
						
					} else {
						
						$return = $pages->addRole($_POST['p'],$_POST['v3'],$get_user['idu'],$user['idu']);
						
					}
	
	            // Remove role
				} elseif($_POST['v1'] == 5) {
					
					$pages = new pages();
					$pages->db = $db;
					
					// Get target user
					$page_role_target = $profile->getPageRole($_POST['v2'],$_POST['p'],$_POST['v2']);
					
					if($page_role_target['user_id'] == $page['page_owner']) {
						$bug = 'TARGET IS FOUNDER';
					} else {
						$return = $pages->deleteRole($_POST['v2'],$_POST['p'],$page_role_target['user_id'],$user['idu']);
					}
					
					
				}
				
			} else {
				$return = showError($TEXT['_uni-settings_rights_page_fail']);
			}		
			
			echo $return.'<script>contentLoader(0,1);</script>';
		
		// Load page settings
		} elseif($_POST['ff'] == '8') {	

		   	// Fetch page role
			$page_role = $profile->getPageRole($user['idu'],$_POST['p']);
		
			// Get page
			$page = $profile->getPage($_POST['p']);
			
			// If editor or admin
			if($page_role['page_role'] > 3 ) {
				
				// Add page id
				$TEXT['TEMP_PAGE_ID'] = $page['page_id'];

				echo '<style>@media screen and (min-width:992px){#P_NAV_LEFT {display:none!important;}}.G-PAGE-NAV{display:block!important;}</style>';

			    // General settings
				if($_POST['t'] == 0 || $_POST['t'] == 1) {

				    $TEXT['posts'] = '<div class="brz-new-container brz-text-black brz-padding-8 brz-padding"><span class="brz-text-bold">'.$TEXT['_uni-General_settings'].'</span></div>        
						<div class="brz-new-container brz-small brz-text-black brz-padding-8 brz-padding">				
						    <div class="brz-border-bottom brz-padding brz-border-super-grey">			
								<div class="brz-clear brz-padding-16">			
									<div class="brz-col s4 brz-padding-medium">
										<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Page_name'].'</span>
									</div>
									<div class="brz-col s6 brz-padding-medium">
										<span class="brz-left">
											<input id="settings-page-1" class="nav-item-text-inverse brz-border brz-text-grey brz-small brz-card" value="'.$page['page_name'].'">
										</span>
									</div>	 
								</div>
							</div>							
							<div class="brz-border-bottom brz-padding brz-border-super-grey">			
								<div class="brz-clear brz-padding-16">			
									<div class="brz-col s4 brz-padding-medium">
										<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Page_email'].'</span>
									</div>
									<div class="brz-col s6 brz-padding-medium">
										<span class="brz-left">
											<input id="settings-page-2" class="nav-item-text-inverse brz-border brz-text-grey brz-small brz-card" value="'.$page['page_email'].'">
										</span>
									</div>	 
								</div>
							</div>						
							<div class="brz-border-bottom brz-padding brz-border-super-grey">			
								<div class="brz-clear brz-padding-16">			
									<div class="brz-col s4 brz-padding-medium">
										<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Location'].'</span>
									</div>
									<div class="brz-col s6 brz-padding-medium">
										<span class="brz-left">
											<input id="settings-page-3" class="nav-item-text-inverse brz-border brz-text-grey brz-small brz-card" value="'.$page['page_location'].'">
										</span>
									</div>	 
								</div>
							</div>							
							<div class="brz-border-bottom brz-padding brz-border-super-grey">			
								<div class="brz-clear brz-padding-16">			
									<div class="brz-col s4 brz-padding-medium">
										<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Website'].'</span>
									</div>
									<div class="brz-col s6 brz-padding-medium">
										<span class="brz-left">
											<input id="settings-page-4" class="nav-item-text-inverse brz-border brz-text-grey brz-small brz-card" value="'.$page['page_web'].'">
										</span>
									</div>	 
								</div>
							</div>						
							<div class="brz-border-bottom brz-padding brz-border-super-grey">			
								<div class="brz-clear brz-padding-16">			
									<div class="brz-col s4 brz-padding-medium">
										<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Description'].'</span>
									</div>
									<div class="brz-col s6 brz-padding-medium">
										<span class="brz-left">
											<textarea style="max-width:200px!important;width:200px!important;height:150px;" id="settings-page-5" class="nav-item-text-inverse brz-border brz-text-grey brz-small brz-card">'.$page['page_description'].'</textarea>
										</span>
									</div>	 
								</div>
							</div>							
							<div class="brz-margin brz-clear">
							    <span id="settings-content-space-1"></span>
								<span id="settings-content-mess-1"></span>
								<div id="settings-content-save-1" onclick="savePage('.$page['page_id'].',1);" class="brz-round brz-right brz-padding-neo2  brz-tag brz-blue brz-small brz-hover-blue-hd brz-cursor-pointer brz-text-white brz-text-bold">'.$TEXT['_uni-Save_changes'].'</div>
							</div>						
				    </div>' ;
					
				// Page settings
				} elseif($_POST['t'] == 3) {
				    $TEXT['posts'] = '<div class="brz-new-container brz-text-black brz-padding-8 brz-padding"><span class="brz-text-bold">'.$TEXT['_uni-Edit_Page_username'].'</span></div>        
						<div class="brz-new-container brz-small brz-text-black brz-padding-8 brz-padding">				
							<div class="brz-border-bottom brz-padding brz-border-super-grey">			
								<div class="brz-clear brz-padding-16">			
									<div class="brz-col s4 brz-padding-medium">
										<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Page_username'].'</span>
									</div>
									<div class="brz-col s6 brz-padding-medium">
										<span class="brz-left">
											<input placeholder="'.$TEXT['_uni-Page_username_add'].'" id="settings-page-1" class="nav-item-text-inverse brz-border brz-text-grey brz-small brz-card" value="'.$page['page_username'].'">
										</span>
									</div>	 
								</div>
								<div class="brz-center brz-margin brz-text-super-grey brz-info-mar2">
                                    '.$TEXT['_uni-TTL-Page_username'].'								
								</div>
							</div>						
							<div class="brz-margin brz-clear">
							    <span id="settings-content-space-1"></span>
								<span id="settings-content-mess-1"></span>
								<div id="settings-content-save-1" onclick="savePage('.$page['page_id'].',3);" class="brz-round brz-right brz-padding-neo2  brz-tag brz-blue brz-small brz-hover-blue-hd brz-cursor-pointer brz-text-white brz-text-bold">'.$TEXT['_uni-Save_changes'].'</div>
							</div>						
				    </div>' ;
				} elseif($_POST['t'] == 4) {
				    $TEXT['posts'] = '<div class="brz-new-container brz-text-black brz-padding-8 brz-padding"><span class="brz-text-bold">'.$TEXT['_uni-Add_new_role'].'</span></div>        
						<div class="brz-new-container brz-small brz-text-black brz-padding-8 brz-padding">				
							<div class="brz-border-bottom brz-padding brz-border-super-grey">			
								<div class="brz-clear brz-padding-16">			
									<div class="brz-col s4 brz-padding-medium">
										<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Select_role'].'</span>
									</div>
									<div class="brz-col s7 brz-padding-medium">
										<span class="brz-left">
											<select id="settings-page-2" class="wide small wide brz-small">
											    <option value="" class="brz-text-bold brz-text-red" disabled selected>'.$TEXT['_uni-Select_role_type'].'</option>
											    <option value="2" data-cs="'.$TEXT['_uni-ttl-Analyst'].'" class="brz-text-bold brz-text-red">'.$TEXT['_uni-Analyst'].'</option>
											    <option value="3" data-cs="'.$TEXT['_uni-ttl-Moderator'].'" class="brz-text-bold brz-text-red">'.$TEXT['_uni-Moderator'].'</option>
											    <option value="4" data-cs="'.$TEXT['_uni-ttl-Editor'].'fd" class="brz-text-bold brz-text-red">'.$TEXT['_uni-Editor'].'</option>
											    <option value="5" data-cs="'.$TEXT['_uni-ttl-Admin'].'" class="brz-text-bold brz-text-red">'.$TEXT['_uni-Admin'].'</option>
										    </select>
										</span>
									</div>							
								</div>
								<script>
									$(\'#settings-page-2\').on(\'change\', function() {
										
										$(\'#page_role_container_tipe\').html(function() {return $(\'#settings-page-2\').find(":selected").attr("data-cs");});
									})
								</script>
								<div id="page_role_container_tipe" class="brz-center brz-margin brz-text-super-grey brz-info-mar2">								
								</div>
							</div>
							<div class="brz-border-bottom brz-padding brz-border-super-grey">			
								<div class="brz-clear brz-padding-16">			
									<div class="brz-col s4 brz-padding-medium">
										<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-User_username'].'</span>
									</div>
									<div class="brz-col s6 brz-padding-medium">
										<span class="brz-left">
											<input placeholder="'.$TEXT['_uni-Enter_username'].'" id="settings-page-1" class="nav-item-text-inverse brz-border brz-text-grey brz-small brz-card" value="">
										</span>
									</div>	 
								</div>
								<div class="brz-center brz-margin brz-text-super-grey brz-info-mar2">
                                    '.$TEXT['_uni-TTL-Add_new_role'].'								
								</div>
							</div>						
							<div class="brz-margin brz-clear">
							    <span id="settings-content-space-1"></span>
								<span id="settings-content-mess-1"></span>
								<div id="settings-content-save-1" onclick="savePage('.$page['page_id'].',4);" class="brz-round brz-right brz-padding-neo2  brz-tag brz-blue brz-small brz-hover-blue-hd brz-cursor-pointer brz-text-white brz-text-bold">'.$TEXT['_uni-Add_role'].'</div>
							</div>						
				    </div>' ;					
					
					
				}
		
				$main_body = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
				
				$TEXT['content'] = ($_POST['t'] == 0) ? display('../../../themes/'.$TEXT['theme'].'/html/navigations/page_settings'.$TEXT['templates_extension']) : '';
	
	            $right_body = ($_POST['t'] == 0) ? display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']) : '';
				
				echo $pre_load.$main_body.$right_body;

			
			} else {
			    echo showError($TEXT['_uni-settings_rights_page_fail']); 
			}

	    } elseif($_POST['ff'] == '11') {	
			
			// Add start up
		    $from = (isset($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
			
		   	// Fetch page role
			$page_role = $profile->getPageRole($user['idu'],$_POST['p']);
		
			// Get page
			$page = $profile->getPage($_POST['p']);
			
		    // Allow user to add members on the basis of group privacy
		    if($page_role['page_role'] > 2) {
			  
			    $pages = new pages();
				$pages->db = $db;
				$pages->followings = $profile->followings;
				$pages->settings = $page_settings;
				
				$TEXT['content'] = ($from > 0) ? '' : '<div class="brz-new-container brz-text-black brz-padding-8 brz-padding"><span class="brz-text-bold">'.$TEXT['_uni-Page_roles'].'</span></div>' ;
				
			    // Get taggesd posts
			    $TEXT['posts'] = $pages->pageRoles($page,$page_role['page_role'],$from,$user);
			
			    // Add posts to main body
			    echo (!$from) ? display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']) : $TEXT['posts'];
				
			
			} else {
				$TEXT['posts'] = bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-No_allow_roles']); 
			    echo $pre_load.display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
			}

		} 
		
	}
// No credentials	
} else {
	echo showError($TEXT['lang_error_connection2']);
}
?>