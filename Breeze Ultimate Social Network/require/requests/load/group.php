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
	$pre_load = '<script>$("#brz-add-GNav-'.$_POST['ff'].'").find("img").hide();</script>';
	
	// If user doesn't exists
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
	
		// Pass user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		
		// Load group main page
		if($_POST['ff'] == '1') {
		    
			$group = $profile->getGroup($_POST['p']);
			
			// If group exists
			if($group) {
			
				// Get group content
				list($group,$group_user,$group_top,$group_feeds,$about,$d_nav_items) = $profile->getGroupTop($_POST['p'],$user,1);
 
            	// Add group feeds
            	$TEXT['posts'] = $group_feeds;

                // Add group id to html template
			    $TEXT['TEMP_GROUP_ID'] = $group['group_id'];
				
				// Set data for post form
				$TEXT['temp-image'] = $user['image'];
				$TEXT['temp-group-image'] = $group['group_cover'];
				$TEXT['temp-group-name'] = $group['group_name'];
				$TEXT['temp-group-type'] = $profile->getGroupHeading($group['group_privacy']);
		
		        // Parse background images
				parseBackgrounds($TEXT['ACTIVE_BACKGROUNDS']);
		
			    $TEXT['content'] = ($group_user['group_status'] == 1 && $group_user['p_post'] == 0 && ($group_user['group_role'] == 2 || $group['group_posting'] == 1)) ? display('../../../themes/'.$TEXT['theme'].'/html/main/post_form_group'.$TEXT['templates_extension']):'';
			
			    // Parse Navigation for desktops
				$TEXT['TEMP-NAV-ITEMS'] = $d_nav_items;
				$TEXT['TEMP-NAV-SHORTCUTS'] = $profile->getShortcuts($user['idu'],$group_user['group_id'],5);
				$d_nav = '<style>@media screen and (min-width:992px){#P_NAV_LEFT {display:none!important;}}.G-PAGE-NAV{display:block!important;}</style>';
				$d_nav .= display('../../../themes/'.$TEXT['theme'].'/html/navigations/main_group'.$TEXT['templates_extension']);
				$d_nav .= '<script>groupNav();</script>';

				// Display full group
				$main_body = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']).$d_nav;
		
				// Right side body
				$TEXT['content'] = $about.$profile->getPersonalities($user,$page_settings['trendind_per_limit']);
			
            	echo $group_top.$main_body.display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);				
		
				
			} else {		
				echo '<script>loadHome();</script>';
			}
	
        // Load add member wizard		
		} elseif($_POST['ff'] == '2') {		    
			echo '<div class="brz-white">
					<div class="brz-super-grey brz-padding-8 brz-padding brz-medium brz-text-bold brz-border-bottom">
			        	<span onclick="loadModal(0);" class="brz-closebtn brz-hover-text-black brz-text-grey brz-text-bold brz-padding brz-round brz-small">X</span>
			        	'.$TEXT['_uni-Add_members'].'
					</div>
					<div id="new-modal-inner-content" class="brz-clear brz-padding brz-white brz-padding brz-text-super-grey nicescrollit brz-small" style="width:100%;height:200px;overflow:hidden;">
					</div>				
					<div class="brz-center brz-border-top brz-margin">
					    <input id="new-modal-input" style="padding: 0px 0px 0px 35px !important;" class="brz-input brz-no-border brz-margin-top search-icon input-icon brz-text-grey brz-card brz-transparent brz-padding" placeholder="'.$TEXT['_uni-Add_members_ttl'].'">
					</div>				
					<div class="brz-clear brz-white brz-border-top brz-padding brz-text-super-grey brz-margin brz-small">
				    	<div class="brz-text-super-grey brz-text-left">'.$TEXT['_uni-Add_members_ttl2'].'</div>   		
					</div>			
					<script>
						var typingTimer;
						var typingInterval = 1000;
						$(document).on(\'keyup\', \'#new-modal-input\', function(event) { 
						    $("#new-modal-inner-content").html(\'<div class="brz-center"><img class="brz-center" src="'.$TEXT['installation'].'/themes/'.$TEXT['theme'].'/img/icons/loader-small.gif"></img></div>\');						
							clearTimeout(typingTimer);						
							if ($(\'#new-modal-input\').val()) {
            					typingTimer = setTimeout(function() {ajaxProtocol(load_group_file,'.$_POST['p'].',0,0,$("#new-modal-input").val(),0,0,0,0,0,0,0,0,"#new-modal-inner-content",3,0,1,13);}, typingInterval);
							} else {
							    $("#new-modal-inner-content").html("");
							}
						});	
					    $(\'.nicescrollit\').niceScroll();</script>		
				</div>';
			
		// Group search for add members
		} elseif($_POST['ff'] == '3') {	
			
			// Fetch group user
			$group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
			// Get group
			$group = $profile->getGroup($_POST['p']);
		
		    // Allow user to add members on the basis of group privacy
		    if($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu'] || ($group_user['group_status'] == 1 && $group['group_approval_type'] == '1')) {
			    echo $profile->searchMembers($user,$_POST['v1'],$group['group_id'],2,$_POST['f']);			
			} else {
			    echo $TEXT['_uni-No_allow_add_members'];
			}
			
		// Load remove member wizard		
		} elseif($_POST['ff'] == '4') {		    
			echo '<div class="brz-white">
					<div class="brz-super-grey brz-padding-8 brz-padding brz-medium brz-text-bold brz-border-bottom">
			        	<span onclick="loadModal(0);" class="brz-closebtn brz-hover-text-black brz-text-grey brz-text-bold brz-padding brz-round brz-small">X</span>
			        	'.$TEXT['_uni-Remove_members'].'
					</div>
					<div id="new-modal-inner-content" class="brz-clear brz-padding brz-white brz-padding brz-text-super-grey nicescrollit brz-small" style="width:100%;height:200px;overflow:hidden;">
					</div>				
					<div class="brz-center brz-border-top brz-margin">
					    <input id="new-modal-input" style="padding: 0px 0px 0px 35px !important;" class="brz-input brz-no-border brz-margin-top search-icon input-icon brz-text-grey brz-card brz-transparent brz-padding" placeholder="'.$TEXT['_uni-Remove_members_ttl'].'">
					</div>				
					<div class="brz-clear brz-white brz-border-top brz-padding brz-text-super-grey brz-margin brz-small">
				    	<div class="brz-text-super-grey brz-text-left">'.$TEXT['_uni-Remove_members_ttl2'].'</div>   		
					</div>			
					<script>
						var typingTimer;
						var typingInterval = 1000;
						$(document).on(\'keyup\', \'#new-modal-input\', function(event) { 
						    $("#new-modal-inner-content").html(\'<div class="brz-center"><img class="brz-center" src="'.$TEXT['installation'].'/themes/'.$TEXT['theme'].'/img/icons/loader-small.gif"></img></div>\');						
							clearTimeout(typingTimer);						
							if ($(\'#new-modal-input\').val()) {
            					typingTimer = setTimeout(function() {ajaxProtocol(load_group_file,'.$_POST['p'].',0,0,$("#new-modal-input").val(),0,0,0,0,0,0,0,0,"#new-modal-inner-content",5,0,1,13);}, typingInterval);
							} else {
							    $("#new-modal-inner-content").html("");
							}
						});	
					    $(\'.nicescrollit\').niceScroll();</script>		
				</div>	
			';
			
		// Group search for remove members
		} elseif($_POST['ff'] == '5') {	
			
			// Fetch group user
			$group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
			// Get group
			$group = $profile->getGroup($_POST['p']);
		
		    // Allow user to add members on the basis of group privacy
		    if($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu'] || ($group_user['group_status'] == 1 && $group['group_approval_type'] == '1')) {
			    echo $profile->searchMembers($user,$_POST['v1'],$group['group_id'],3);			
			} else {
			    echo $TEXT['_uni-No_allow_add_members'];
			}
			
		// Load member requests
		} elseif($_POST['ff'] == '6') {	
			
			// Add start up
		    $from = (isset($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
		
			// Fetch group user
			$group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
			// Get group
			$group = $profile->getGroup($_POST['p']);
		
		    // Allow user to add members on the basis of group privacy
		    if($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu'] || ($group_user['group_status'] == 1 && $group['group_approval_type'] == '1')) {
			    
				// Get group class
				$group_functions = new groups();
				$group_functions->db = $db;
				$group_functions->followings = $profile->followings;
				
				// Get member requests
				$TEXT['content'] = ($from > 0) ? '' : '<div class="brz-new-container brz-text-black brz-padding-8 brz-padding"><span class="brz-text-bold">'.$TEXT['_uni-Requests'].'</span></div>' ;
				
			    $TEXT['posts'] = $group_functions->getRequests($user,$group['group_id'],$_POST['f'],$page_settings['group_requests_per_page']);
				
				echo ($from > 0) ? $pre_load.$TEXT['posts'] : $pre_load.display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']).'<script>$("#all_posts").addClass("brz-new-container");</script>';
				
			} else {
			    $TEXT['posts'] = bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-No_allow_add_members']); 
			    echo $pre_load.display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']); 
			}
	
		// Load group log
		} elseif($_POST['ff'] == '10') {	
			
			// Add start up
		    $from = (isset($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
		
			// Fetch group user
			$group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
			// Get group
			$group = $profile->getGroup($_POST['p']);
		
		    // Allow user to add members on the basis of group privacy
		    if($group_user['group_status'] == 1 && ($group_user['p_activity'] || $group_user['group_role'] == 2 || $group['group_owner'] == $user['idu'])) {
			    
				// Get group class
				$group_functions = new groups();
				$group_functions->db = $db;
				$group_functions->followings = $profile->followings;
				
				// Get member requests
				$TEXT['content'] = ($from > 0) ? '' : '<div class="brz-new-container brz-text-black brz-padding-8 brz-padding"><span class="brz-text-bold">'.$TEXT['_uni-Activity_log'].'</span></div>' ;
				
			    $TEXT['posts'] = $profile->getGroupLog($group['group_id'],$_POST['f'],$page_settings['group_log_per_page']);
				
				echo ($from > 0) ? $pre_load.$TEXT['posts'] : $pre_load.display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']).'<script>$("#all_posts").addClass("brz-new-container");</script>';
				
			} else {
			    $TEXT['posts'] = bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-No_allow_activity_log']); 
				echo $pre_load.display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
			}
			
		// Load group feeds
	    } elseif($_POST['ff'] == '15') {	
			
			// Add start up
		    $from = (isset($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
		    
			if($_POST['p'] && is_numeric($_POST['p'])) {
				// Fetch group user
				$group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
				// Get group
				$group = $profile->getGroup($_POST['p']);
		
		    	// Allow user to add members on the basis of group privacy
		    	if($group_user['group_status'] == 1 || $group['group_privacy'] == 1) {
                
				    // Add group id to html template
			        $TEXT['TEMP_GROUP_ID'] = $group['group_id'];
					$TEXT['temp-image'] = $user['image'];
					$TEXT['temp-group-image'] = $group['group_cover'];
					$TEXT['temp-group-name'] = $group['group_name'];
		            
					// Parse background images
				    parseBackgrounds($TEXT['ACTIVE_BACKGROUNDS']);
				
                    $TEXT['content'] = ($from == 0 && $group_user['group_status'] == 1 && $group_user['p_post'] == 0 && ($group_user['group_role'] == 2 || $group['group_posting'] == 1)) ? display('../../../themes/'.$TEXT['theme'].'/html/main/post_form_group'.$TEXT['templates_extension']):'';
			
					$TEXT['posts'] = $profile->getGroupFeeds($user,$from,$_POST['p'],NULL,1);
				
					echo ($from > 0) ? $pre_load.$TEXT['posts'] : $pre_load.display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
				
				} else {
			    	$TEXT['posts'] = bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-Private-inf3']); 
					echo $pre_load.display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
				}

			} else {
				// Get group class
				$group_functions = new groups();
				$group_functions->db = $db;
				$group_functions->followings = $profile->followings;

				$groups_joined = $user['group_feeds'];
				
				$TEXT['posts'] = ($groups_joined) ? $profile->getGroupFeeds($user,$from,$groups_joined) : bannerIt('feeds'.mt_rand(1,4),$TEXT['_uni-lang_load_no_feeds'],$TEXT['_uni-No_feeds-inf2']);
				
				$return = ($from > 0) ? $TEXT['posts'] : display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
		       
                // Add more stuff if user lands directly on groups page
                if($_POST['bo'] == 1) {
					
					// Get groups
					$groups_all = $group_functions->getAllGroups($user['group_feeds'],7);
					
                    // Get suggestions and trending users
                    $groups = ($page_settings['groups_on_home']) ? $profile->getGroups($user['idu'],15) : '';
                    $boxed_users = ($page_settings['feature_pe_tren_on_home']) ? $profile->getBoxedUsers($user['idu']) : '';
                    $personalities = ($page_settings['feature_trending_on_home']) ? $profile->getPersonalities($user,$page_settings['trendind_per_limit']) : '';
                    $TEXT['content'] = $groups_all.$boxed_users.$groups.$personalities;
			   
                    $return .= display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);         
                }
                
                echo $pre_load.$return ;

			}
	
	    // Load user permissions
		} elseif($_POST['ff'] == '12') {	
			
			// Fetch group user
			$group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
			
			// fetch target user
			$group_target = $profile->getGroupUser($_POST['v1'],$_POST['p'],$_POST['v1']);
		
			// Get group
			$group = $profile->getGroup($_POST['p']);
		
		    // Allow user to add members on the basis of group privacy
		    if($group_target['group_status'] == '1' && $group['group_owner'] !== $group_target['user_id'] && ($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu'] || ($group_user['group_approval_type'] == 1 && $group_user['group_role'] == 1))) {
			    $inner_content = '<script>
								function checkAdminDynamics(t) {
									if(t == 2) {							
									    $("#group_post_radio0").prop(\'disabled\',true).next().addClass(\'disabled\');
									    $("#group_post_radio1").prop(\'disabled\',true).next().addClass(\'disabled\');
									    $("#group_cover_radio0").prop(\'disabled\',true).next().addClass(\'disabled\');
									    $("#group_cover_radio1").prop(\'disabled\',true).next().addClass(\'disabled\');
									    $("#group_actvity_radio0").prop(\'disabled\',true).next().addClass(\'disabled\');
									    $("#group_actvity_radio1").prop(\'disabled\',true).next().addClass(\'disabled\');
										
									} else {										
									    $("#group_post_radio0").prop(\'disabled\',false).next().removeClass(\'disabled\');
									    $("#group_post_radio1").prop(\'disabled\',false).next().removeClass(\'disabled\');
									    $("#group_cover_radio0").prop(\'disabled\',false).next().removeClass(\'disabled\');
									    $("#group_cover_radio1").prop(\'disabled\',false).next().removeClass(\'disabled\');
									    $("#group_actvity_radio0").prop(\'disabled\',false).next().removeClass(\'disabled\');										
									    $("#group_actvity_radio1").prop(\'disabled\',false).next().removeClass(\'disabled\');										
									}									
								}
								;
								</script>
				                <div class="brz-border-bottom brz-small brz-padding brz-border-super-grey">			
									<div class="brz-clear brz-padding-16">			
										<div class="brz-col s4 brz-padding-medium">
											<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Post_permissions'].'</span>
										</div>
										<div class="brz-col s7 brz-padding-medium">
											<span class="brz-left">
												<div><input type="radio" id="group_post_radio0" name="group_post_radio" value="0" /><label class="brz-small" for="group_post_radio0">'.$TEXT['_uni-Allow_if_ad_set'].'</label></div>
			                               		<div><input type="radio" id="group_post_radio1" name="group_post_radio" value="1" /><label class="brz-small" for="group_post_radio1">'.$TEXT['_uni-Block_posting'].'</label></div>
											</span>
										</div>							
									</div>
								</div>
								<div class="brz-border-bottom brz-small brz-padding brz-border-super-grey">			
									<div class="brz-clear brz-padding-16">			
										<div class="brz-col s4 brz-padding-medium">
											<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Cover_permissions'].'</span>
										</div>
										<div class="brz-col s7 brz-padding-medium">
											<span class="brz-left">
												<div><input type="radio" id="group_cover_radio0" name="group_cover_radio" value="0" /><label class="brz-small" for="group_cover_radio0">'.$TEXT['_uni-Allow_if_ad_set3'].'</label></div>
			                               		<div><input type="radio" id="group_cover_radio1" name="group_cover_radio" value="1" /><label class="brz-small" for="group_cover_radio1">'.$TEXT['_uni-Allow_if_ad_set2'].'</label></div>
											</span>
										</div>							
									</div>
								</div>
								<div class="brz-border-bottom brz-small brz-padding brz-border-super-grey">			
									<div class="brz-clear brz-padding-16">			
										<div class="brz-col s4 brz-padding-medium">
											<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Activity_log'].'</span>
										</div>
										<div class="brz-col s7 brz-padding-medium">
											<span class="brz-left">
												<div><input type="radio" id="group_actvity_radio0" name="group_actvity_radio" value="0" /><label class="brz-small" for="group_actvity_radio0">'.$TEXT['_uni-Allow_if_ad_set3'].'</label></div>
			                               		<div><input type="radio" id="group_actvity_radio1" name="group_actvity_radio" value="1" /><label class="brz-small" for="group_actvity_radio1">'.$TEXT['_uni-Allow_if_ad_set4'].'</label></div>
											</span>
										</div>							
									</div>
								</div>	
								<div class="brz-border-bottom brz-small brz-padding brz-border-super-grey">			
									<div class="brz-clear brz-padding-16">			
										<div class="brz-col s4 brz-padding-medium">
											<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Administration'].'</span>
										</div>
										<div class="brz-col s7 brz-padding-medium">
											<span  class="brz-left">
												<div><input type="radio" onclick="checkAdminDynamics(1);" id="group_admin_radio1" name="group_admin_radio" value="1" /><label class="brz-small" for="group_admin_radio1">'.$TEXT['_uni-Grant_only_standard_user_rights'].'</label></div>
			                               		<div><input type="radio" onclick="checkAdminDynamics(2);" id="group_admin_radio2" name="group_admin_radio" value="2" /><label class="brz-small" for="group_admin_radio2">'.$TEXT['_uni-Grant_adminstration_rights'].'</label></div>
											</span>
										</div>							
									</div>
								</div>
							<div class="brz-margin brz-clear">
							    <span id="settings-content-space-2"></span>
								<span id="settings-content-mess-2"></span>
								<span id="new-modal-inner-content"></span>
								<div id="settings-content-save-2" onclick="editGroupMemberPermissions('.$group['group_id'].','.$_POST['v1'].',1);" class="brz-round brz-right brz-padding-neo2  brz-tag brz-blue brz-small brz-hover-blue-hd brz-cursor-pointer brz-text-white brz-text-bold">'.$TEXT['_uni-Save_changes'].'</div>
							</div>								
								<script>							
								$("#group_post_radio'.$group_target['p_post'].'").prop(\'checked\',true);
								$("#group_cover_radio'.$group_target['p_cover'].'").prop(\'checked\',true);
								$("#group_actvity_radio'.$group_target['p_activity'].'").prop(\'checked\',true);
								$("#group_admin_radio'.$group_target['group_role'].'").prop(\'checked\',true);
								checkAdminDynamics('.$group_target['group_role'].');
                               	refreshElements();							
								</script>
							';
			} elseif($group['group_owner'] == $group_target['user_id']) {
			    $inner_content = showBox($TEXT['_uni-Group_fnder_edit_no']); 
			} else {
				$inner_content = showBox($TEXT['_uni-User_exists_group']);			
			}
			
			echo '<div class="brz-white">
					<div class="brz-super-grey brz-padding-8 brz-padding brz-medium brz-text-bold brz-border-bottom">
			        	<span onclick="loadModal(0);" class="brz-closebtn brz-hover-text-black brz-text-grey brz-text-bold brz-padding brz-round brz-small">X</span>
			        	'.$TEXT['_uni-Edit_members'].'
					</div>
					'.$inner_content.'
				</div>'; 	
	
	    // Load create new group
		} elseif($_POST['ff'] == '14') {	
			echo '<div class="brz-white">
			        <script>
					function createGroup() {
						contentLoader(1,3);
						ajaxProtocol(load_group_file,0,0,0,0,$("#new-group-1").val(),$("input[name=\'new_group_post_radio\']:checked").val(),$("input[name=\'new_group_type_radio\']:checked").val(),0,0,0,0,0,3,16,0,1,30);
					}
					</script>
					<div class="brz-super-grey brz-padding-8 brz-padding brz-medium brz-text-bold brz-border-bottom">
			        	<span onclick="loadModal(0);" class="brz-closebtn brz-hover-text-black brz-text-grey brz-text-bold brz-padding brz-round brz-small">X</span>
			        	'.$TEXT['_uni-Create_group'].'
					</div>				
					<div class="brz-border-bottom brz-small brz-padding brz-border-super-grey">			
						<div class="brz-clear brz-padding-16">			
							<div class="brz-col s4 brz-padding-medium">
								<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Group_name'].'</span>
							</div>
							<div class="brz-col s6 brz-padding-medium">
								<span class="brz-left">
									<input id="new-group-1" class="nav-item-text-inverse brz-border brz-text-grey brz-small brz-card" value="">
								</span>
							</div>	 
						</div>
					</div>							
					<div class="brz-border-bottom brz-small brz-padding brz-border-super-grey">			
						<div class="brz-clear brz-padding-16">			
							<div class="brz-col s4 brz-padding-medium">
								<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Post_permissions'].'</span>
							</div>
							<div class="brz-col s7 brz-padding-medium">
								<span class="brz-left">
									<div><input type="radio" checked id="new_group_post_radio1" name="new_group_post_radio" value="1" /><label class="brz-small" for="new_group_post_radio1">'.$TEXT['_uni-Any_member_11'].'</label></div>
			                        <div><input type="radio" id="new_group_post_radio2" name="new_group_post_radio" value="2" /><label class="brz-small" for="new_group_post_radio2">'.$TEXT['_uni-Any_member_12'].'</label></div>
								</span>
							</div>							
						</div>
					</div>				
					<div class="brz-border-bottom brz-small brz-padding brz-border-super-grey">			
						<div class="brz-clear brz-padding-16">			
							<div class="brz-col s4 brz-padding-medium">
								<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Group_privacy'].'</span>
							</div>
							<div class="brz-col s7 brz-padding-medium">
								<span class="brz-left">
									<div><input type="radio" checked id="new_group_type_radio1" name="new_group_type_radio" value="1" /><label class="brz-small" for="new_group_type_radio1">'.$TEXT['_uni-Group_type_1'].'</label></div>
			                        <div><input type="radio" id="new_group_type_radio2" name="new_group_type_radio" value="2" /><label class="brz-small" for="new_group_type_radio2">'.$TEXT['_uni-Group_type_2'].'</label></div>
			                        <div><input type="radio" id="new_group_type_radio3" name="new_group_type_radio" value="3" /><label class="brz-small" for="new_group_type_radio3">'.$TEXT['_uni-Group_type_3'].'</label></div>
								</span>
							</div>							
						</div>
						<div class="brz-center brz-margin brz-text-super-grey brz-info-mar2">
                            '.$TEXT['_uni-Group_types_details'].'								
					    </div>
					</div>
					<div class="brz-margin brz-clear">
					    <span id="settings-content-space-3"></span>
						<span id="settings-content-mess-3"></span>
						<div id="settings-content-save-3" onclick="createGroup();" class="brz-round brz-right brz-padding-neo2  brz-tag brz-blue brz-small brz-hover-blue-hd brz-cursor-pointer brz-text-white brz-text-bold">'.$TEXT['_uni-Create_group'].'</div>
					</div>			
				</div>
				<script>refreshElements();</script>'; 	
		
		// Create new group
		} elseif($_POST['ff'] == '16') {	
		    
			if(isXSSED($_POST['v2']) || strlen($_POST['v2']) < 6 || strlen($_POST['v2']) > 32) {
			    
				$return = showError($TEXT['_uni-group_val-1']);
		
			// Veriy check box data
		    } elseif(!in_array($_POST['v3'],array("1","2")) || !in_array($_POST['v4'],array("1","2","3"))) {

				$return = showError($TEXT['_uni-error_script']);
	
			} else {
				
				// Get group functions
				$group_functions = new groups();
				$group_functions->db = $db;
				
				$new_group = $group_functions->createGroup($_POST['v2'],$_POST['v3'],$_POST['v4'],$user['idu']);
				
				$return = (!$new_group) ? showError($TEXT['_uni-error_script']) : '<script>loadModal(0);loadGroup('.$new_group.',1,1);</script>';
			
			}
			
			echo $return.'<script>contentLoader(0,3);</script>';
			
		// Load members
		} elseif($_POST['ff'] == '11') {	
			
			// Add start up
		    $from = (isset($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
		
			// Fetch group user
			$group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
			// Get group
			$group = $profile->getGroup($_POST['p']);
		    	
		    // Allow user to add members on the basis of group privacy
		    if($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu'] || $group['group_privacy'] !== 3) {
			  
			    $TEXT['posts'] = $profile->getGroupMembers($group,$_POST['f'],$group_user,$user['idu']);
				
				if($from > 0) {
					echo $pre_load.$TEXT['posts'];
				} else {
		    
					// Get Content box
					$TEXT['temp_standard_content'] = $TEXT['posts'];
					$TEXT['temp_standard_title'] = $TEXT['_uni-Members'];
					$TEXT['temp_standard_title_img'] = 'people';
					$TEXT['temp_standard_id'] = 'people-box-main';
			
					$TEXT['posts'] = display('../../../themes/'.$TEXT['theme'].'/html/main/standard_box'.$TEXT['templates_extension']);
			
					// Full page
					$main_body = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
			   	
					// Display body
					echo $pre_load.$main_body;		
				}	
			
			} else {
				$TEXT['posts'] = bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-No_allow_activity_log']); 
			    echo $pre_load.display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
			}
	
		// Process member requests
		} elseif($_POST['ff'] == '7') {

		    // Allow || Remove member request
		    $type = ($_POST['v2']) ? 1 : 0 ;
		
			// Fetch group user
			$group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
			// Get group
			$group = $profile->getGroup($_POST['p']);
		
		    // Allow user to add members on the basis of group privacy
		    if($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu'] || ($group_user['group_status'] == 1 && $group['group_approval_type'] == '1')) {
			    
				// Get group class
				$group_functions = new groups();
				$group_functions->db = $db;
				$group_functions->followings = $profile->followings;

				// Check user status
			    $target_user = $profile->getGroupUser($_POST['v1'],$_POST['p']);
			
			    // Update request
				echo $group_functions->processRequest($group['group_id'],$user['idu'],$profile->getUserByID($_POST['v1']),$type,$target_user);
				
		    } else {
			    echo showError($TEXT['_uni-No_allow_add_members']); 
			}
		
        // Save group settings		
	    } elseif($_POST['ff'] == '9') {	
			
			    // Fetch group user
			    $group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
			    // Get group
			    $group = $profile->getGroup($_POST['p']);
		
		        // Allow user to save settings
		        if($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu']) {
				
				    if(isXSSED($_POST['v1']) || isXSSED($_POST['v2']) || isXSSED($_POST['v3']) || isXSSED($_POST['v4']) || isXSSED($_POST['v5']) || isXSSED($_POST['v6']) || isXSSED($_POST['v7']) || isXSSED($_POST['v8']) || isXSSED($_POST['v9'])) {
			
					    // If values doesn't meets security requirements
					    $return = showError($TEXT['_uni-error_xss']);
			
			        // Group usernamename check
				    } elseif(!empty($_POST['v1']) && $group['group_username'] !== $_POST['v1'] && ($profile->isUsernameExists($_POST['v1'],1))) {
			    
			            // Verify whether user name exists
			            $return = showError($TEXT['_uni-Username_exists']);
			
			        // Group usernamename check
		            } elseif(!empty($_POST['v1']) && (!ctype_alnum(trim($_POST['v1'])) || is_numeric(trim($_POST['v1'])))) {
			
			            // Allow only valid chars for username
			            $return = showError($TEXT['_uni-signup-9']);
			    
				    // Group usernamename check
				    } elseif(!empty($_POST['v1']) && (strlen($_POST['v1']) < $page_settings['username_min_len'] || strlen($_POST['v1']) > $page_settings['username_max_len'])) {
			
			            $return = showError(sprintf($TEXT['_uni-signup-1'],$page_settings['username_min_len'],$page_settings['username_max_len']));
			
			        // Group name check
				    } elseif(strlen($_POST['v2']) < 6 || strlen($_POST['v2']) > 32) {
			
			            $return = showError($TEXT['_uni-group_val-1']);
			
			        // Group email check
				    } elseif((!filter_var($db->real_escape_string($_POST['v3']), FILTER_VALIDATE_EMAIL) || strlen($_POST['v3']) < 3 || strlen($_POST['v3']) > 62)  && !empty($_POST['v3'])) {
			
			            $return = showError($TEXT['_uni-signup-4']);
			
			        // Group location check
				    } elseif(strlen($_POST['v4'])> 32) { 

				        $return = showError($TEXT['_uni-error_location_len']);	
					
		            // Group website check			
		            } elseif((strlen($_POST['v5']) > 64 || !filter_var($_POST['v5'], FILTER_VALIDATE_URL)) && !empty($_POST['v5'])){ 
				
				        $return = showError($TEXT['_uni-error_web_in']);	
					
				    // Group description check			
				    } elseif(strlen($_POST['v6']) > 2000){ 
				
				        $return = showError($TEXT['_uni-error_bio_in2']);
					
				    // Veriy check box data
		            } elseif(!in_array($_POST['v7'],array("1","2")) || !in_array($_POST['v8'],array("1","2")) || !in_array($_POST['v9'],array("1","2","3"))) {

					    $return = showError($TEXT['_uni-error_script']);
					
				    } else {
					
					    $group_functions = new groups();
				        $group_functions->db = $db;
				    
					    $return = ($group_functions->updateGroup($_POST['p'],$_POST['v1'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5'],$_POST['v6'],$_POST['v7'],$_POST['v8'],$_POST['v9'],$user['idu'])) ? showSuccess($TEXT['_uni-Grop_updatd'],NULL,'loadGroup('.$group['group_id'].',1,1)')  : showNotification($TEXT['_uni-No_changes']);
				    }
				
				    echo $return.'<script>contentLoader(0,1);</script>';
			    } else {
			        echo showError($TEXT['_uni-No_allow_add_members']); 
			    }
			
	    // Save member permissions
	    } elseif($_POST['ff'] == '13') {	
			
			    // Fetch group user
			    $group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
		        // fetch target user
			    $group_target = $profile->getGroupUser($_POST['v1'],$_POST['p'],$_POST['v1']);
			
			    // Get group
			    $group = $profile->getGroup($_POST['p']);
		
		        // Allow user to save settings
		        if(($group_target['group_status'] == 1 && $group_target['user_id'] !== $group['group_owner']) && ($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu'])) {
				
				    if(isXSSED($_POST['v2']) || isXSSED($_POST['v3']) || isXSSED($_POST['v4']) || isXSSED($_POST['v5'])) {
			
					    // If values doesn't meets security requirements
					    $return = showError($TEXT['_uni-error_xss']);
			
				    // Veriy check box data
		            } elseif(!in_array($_POST['v2'],array("1","0")) || !in_array($_POST['v3'],array("1","0"))  || !in_array($_POST['v4'],array("1","0")) || !in_array($_POST['v5'],array("1","2"))) {

					    $return = showError($TEXT['_uni-error_script']);
					
				    } else {
					
					    // Get group functions
					    $group_functions = new groups();
				        $group_functions->db = $db;
				    
					    $return = ($group_functions->updateGroupMember($_POST['p'],$_POST['v2'],$_POST['v3'],$_POST['v4'],$_POST['v5'],$group_target['gid'])) ? showSuccess($TEXT['_uni-Grop_updatd_user']) : showNotification($TEXT['_uni-No_changes']);
				    }
				
				
			    } elseif($group_target['user_id'] == $group['group_owner']) {
			        $return = showError($TEXT['_uni-Group_fnder_edit_no']);
			    } else {
			        $return = ($group_user['group_role'] == 1 || $group_user['group_status'] == 2) ? showError($TEXT['_uni-edit_user_rights_group_fail']) : showError($TEXT['_uni-User_exists_group']); 
			    }

                echo $return.'<script>contentLoader(0,2);</script>';			
			
        // Edit group settings		
	    } elseif($_POST['ff'] == '8') {	
			
			    // Fetch group user
			    $group_user = $profile->getGroupUser($user['idu'],$_POST['p']);
		
			    // Get group
			    $group = $profile->getGroup($_POST['p']);
	
		        if($group_user['group_role'] == 2 || $group['group_owner'] == $user['idu']) {
			    
				    $TEXT['posts'] = '<div class="brz-new-container brz-text-black brz-padding-8 brz-padding"><span class="brz-text-bold">'.$TEXT['_uni-Edit_settings'].'</span></div>        
						<div class="brz-new-container brz-small brz-text-black brz-padding-8 brz-padding">				
						    <div class="brz-border-bottom brz-padding brz-border-super-grey">			
								<div class="brz-clear brz-padding-16">			
									<div class="brz-col s4 brz-padding-medium">
										<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Group_name'].'</span>
									</div>
									<div class="brz-col s6 brz-padding-medium">
										<span class="brz-left">
											<input id="settings-group-1" class="nav-item-text-inverse brz-border brz-text-grey brz-small brz-card" value="'.$group['group_name'].'">
										</span>
									</div>	 
								</div>
							</div>							
							<div class="brz-border-bottom brz-padding brz-border-super-grey">			
								<div class="brz-clear brz-padding-16">			
									<div class="brz-col s4 brz-padding-medium">
										<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Group_email'].'</span>
									</div>
									<div class="brz-col s6 brz-padding-medium">
										<span class="brz-left">
											<input id="settings-group-2" class="nav-item-text-inverse brz-border brz-text-grey brz-small brz-card" value="'.$group['group_email'].'">
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
											<input id="settings-group-3" class="nav-item-text-inverse brz-border brz-text-grey brz-small brz-card" value="'.$group['group_location'].'">
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
											<input id="settings-group-4" class="nav-item-text-inverse brz-border brz-text-grey brz-small brz-card" value="'.$group['group_web'].'">
										</span>
									</div>	 
								</div>
							</div>
							<div class="brz-border-bottom brz-padding brz-border-super-grey">			
								<div class="brz-clear brz-padding-16">			
									<div class="brz-col s4 brz-padding-medium">
										<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Group_username'].'</span>
									</div>
									<div class="brz-col s6 brz-padding-medium">
										<span class="brz-left">
											<input placeholder="'.$TEXT['_uni-Group_username_add'].'" id="settings-group-u" class="nav-item-text-inverse brz-border brz-text-grey brz-small brz-card" value="'.$group['group_username'].'">
										</span>
									</div>	 
								</div>
								<div class="brz-center brz-margin brz-text-super-grey brz-info-mar2">
                                    '.$TEXT['_uni-TTL-Group_username'].'								
								</div>
							</div>							
							<div class="brz-border-bottom brz-padding brz-border-super-grey">			
								<div class="brz-clear brz-padding-16">			
									<div class="brz-col s4 brz-padding-medium">
										<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Description'].'</span>
									</div>
									<div class="brz-col s6 brz-padding-medium">
										<span class="brz-left">
											<textarea style="max-width:200px!important;width:200px!important;height:150px;" id="settings-group-5" class="nav-item-text-inverse brz-border brz-text-grey brz-small brz-card">'.$group['group_description'].'</textarea>
										</span>
									</div>	 
								</div>
							</div>							
							<div class="brz-border-bottom brz-padding brz-border-super-grey">			
								<div class="brz-clear brz-padding-16">			
									<div class="brz-col s4 brz-padding-medium">
										<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Permissions'].'</span>
									</div>
									<div class="brz-col s7 brz-padding-medium">
										<span class="brz-left">
											<div><input type="radio" id="group_approval_radio1" name="group_approval_radio" value="1" /><label class="brz-small" for="group_approval_radio1">'.$TEXT['_uni-Any_member_1'].'</label></div>
			                                <div><input type="radio" id="group_approval_radio2" name="group_approval_radio" value="2" /><label class="brz-small" for="group_approval_radio2">'.$TEXT['_uni-Any_member_2'].'</label></div>
										</span>
									</div>							
								</div>
								<div class="brz-center brz-margin brz-text-super-grey brz-info-mar2">
				    				<div class="brz-text-super-grey brz-text-left">'.$TEXT['_uni-Any_member_inf'].'</div>    		 
								</div>
							</div>			
							<div class="brz-border-bottom brz-padding brz-border-super-grey">			
								<div class="brz-clear brz-padding-16">			
									<div class="brz-col s4 brz-padding-medium">
										<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Post_permissions'].'</span>
									</div>
									<div class="brz-col s7 brz-padding-medium">
										<span class="brz-left">
											<div><input type="radio" id="group_post_radio1" name="group_post_radio" value="1" /><label class="brz-small" for="group_post_radio1">'.$TEXT['_uni-Any_member_11'].'</label></div>
			                                <div><input type="radio" id="group_post_radio2" name="group_post_radio" value="2" /><label class="brz-small" for="group_post_radio2">'.$TEXT['_uni-Any_member_12'].'</label></div>
										</span>
									</div>							
								</div>
							</div>					
							<div class="brz-border-bottom brz-padding brz-border-super-grey">			
								<div class="brz-clear brz-padding-16">			
									<div class="brz-col s4 brz-padding-medium">
										<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Group_privacy'].'</span>
									</div>
									<div class="brz-col s7 brz-padding-medium">
										<span class="brz-left">
											<div><input type="radio" id="group_privacy_radio1" name="group_privacy_radio" value="1" /><label class="brz-small" for="group_privacy_radio1">'.$TEXT['_uni-Group_type_1'].'</label></div>
			                                <div><input type="radio" id="group_privacy_radio2" name="group_privacy_radio" value="2" /><label class="brz-small" for="group_privacy_radio2">'.$TEXT['_uni-Group_type_2'].'</label></div>
			                                <div><input type="radio" id="group_privacy_radio3" name="group_privacy_radio" value="3" /><label class="brz-small" for="group_privacy_radio3">'.$TEXT['_uni-Group_type_3'].'</label></div>
										</span>
									</div>							
								</div>
								<div class="brz-center brz-margin brz-text-super-grey brz-info-mar2">
                                    '.$TEXT['_uni-Group_types_details'].'								
								</div>
								<script>
								$("#group_approval_radio'.$group['group_approval_type'].'").prop(\'checked\',true);
								$("#group_post_radio'.$group['group_posting'].'").prop(\'checked\',true);
								$("#group_privacy_radio'.$group['group_privacy'].'").prop(\'checked\',true);
								</script>
							</div>
							<div class="brz-margin brz-clear">
							    <span id="settings-content-space-1"></span>
								<span id="settings-content-mess-1"></span>
								<div id="settings-content-save-1" onclick="saveGroup('.$group['group_id'].');" class="brz-round brz-right brz-padding-neo2  brz-tag brz-blue brz-small brz-hover-blue-hd brz-cursor-pointer brz-text-white brz-text-bold">'.$TEXT['_uni-Save_changes'].'</div>
							</div>						
						</div>' ;
				    echo $pre_load.display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);		    
			    } else {
			        $TEXT['posts'] = bannerIt('private'.mt_rand(1,4),$TEXT['_uni-PRIVATE'],$TEXT['_uni-settings_rights_group_fail']);
			        echo $pre_load.display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);				
			    }
			
	    // Groups list		
        } elseif($_POST['ff'] == '17') {	
		
		    // Get page class
		    $groups = new groups();
		    $groups->db = $db;
		    $groups->followings = $profile->followings;
	
		    // Add starting point
		    $from = (isset($_POST['f']) && is_numeric($_POST['f'])) ? $_POST['f'] : 0;
		
		    // Search if group exists		
		    echo $groups->getAllGroups($user['group_feeds'],5,$from);	

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