<?php 
//--------------------------------------------------------------------------------------//
//                          Fasebook Social networking platform                         //
//                                     PHP ADMIN CLASSES                                //
//--------------------------------------------------------------------------------------//


class AdminLogin {		// Administration Login | Administration Logout
	
	// Properties
	public $db;                         // DATABASE
	public $username;	                // USERNAME
	public $password;	                // PASSWORD 

	function start() {                                        // Start administration login methods           
		global $TEXT;
		
		// Unset everything
		$this->logOut();
		
		// Verify profile
		$profile = $this->checkProfile();
		
		// Administration verified
		if($profile == 1) {
			
			// Set both sessions and cookies
			$_SESSION['a_username'] = $this->username;
			$_SESSION['a_password'] = md5($this->password);
			
			// Log out general user SESSIONs if logged
			unset($_SESSION['username']);
			unset($_SESSION['password']);
		
			// Unset general user Cookies if logged 
			setcookie("username", "", time() + 1 * 1,'/'); 
			setcookie("password", "", time() + 1 * 1,'/');			
			
			// Return success
			return 1;
			
		} else {
			
			// Wrong credentials
			return $TEXT['_uni-admin-1'];	
		}	
	}
	
	function log() {                                          // Direct login (To be used frequently)
		
		// Select administration row
		$profile = $this->db->query(sprintf("SELECT * FROM `admins` WHERE `username` = '%s' AND `password` = '%s'", $this->db->real_escape_string($this->username), $this->db->real_escape_string($this->password)));
		
		// Return administration row if exists else unset everything
		return ($profile->num_rows) ? $profile->fetch_assoc() : $this->logOut();	
	}
	
	function checkProfile() {                                 // Check profile for Full login
	
		$result = $this->db->query(sprintf("SELECT * FROM `admins` WHERE `username` = '%s' AND `password` = '%s'", $this->db->real_escape_string($this->username), $this->db->real_escape_string(md5($this->password))));
		
		return ($result->num_rows) ? 1 : 0;
		
	}
	
	function logOut() {                                       // Carry out SESSIONS and COOKIES RETURN 0
		// Unset administration cookies and sessions
		unset($_SESSION['a_username']);
		unset($_SESSION['a_password']);
		
		return 0;
	}
	
}

class admin {           // Administration functions
	
	function logOut() {                                      // Log out administration
		
		// Unset Administation SESSION
		unset($_SESSION['a_username']);
		unset($_SESSION['a_password']);
		
		return 0;
	}

	function getAdmin() {                                    // Fetch logged administration
		
		// Unset Administation SESSION
		unset($_SESSION['username']);
		unset($_SESSION['password']);
		
		// Set logged out session
		$_SESSION['loggedout'] = 'USER_LOGGED_OUT';
		
		// Unset Cookies
		setcookie("username", "", time() + 1 * 1,'/');
		setcookie("password", "", time() + 1 * 1,'/');
		
		// Else select user using user name as user name
		$user = $this->db->query(sprintf("SELECT * FROM `admins` WHERE `admins`.`username` = '%s' AND `admins`.`password` = '%s' ", $this->db->real_escape_string($this->username), $this->db->real_escape_string($this->password)));
	
	    // Return administration details if exists
		return ($user->num_rows) ?  $user->fetch_assoc() : $this->logOut();

	}
	
    function verifiedBatch($x,$type = 0) {                                   // Return verified batch if profile is verified
		global $TEXT;
		
		// If small icon is requested
		$size = ($type) ? 'width="14px" height"14px"': '';
		
		// Set responsiveness
		$responsive = ($type) ? '' : 'brz-responsive-medium';
		
		// Return verified image if profile is verified
		return ($x) ? '<img class="'.$responsive.' brz-img-verified-xlarge" title="'.$TEXT['_uni-Profile_verified'].'" alt="Image" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEwAAABLAQMAAADgXPPQAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABBJREFUeNpjYBgFo2AUkAwAAzkAAbSm0MAAAAAASUVORK5CYII=" '.$size.'></img>' : '';	
	
	
	}
	
	function search($from,$val,$filter = 0) {                // Return search results
		global $TEXT ;
		
		// Limit + 1 to check more results availability
		$limit = $this->settings['search_results_per_page'] + 1;
		$verifieds = $results = array(); 
		
		// Add filter
		if($filter == 1) {             // Verified emails
			$add_filter = 'AND `users`.`state` = \'1\' ';
		} elseif($filter == 2) {       // none verified emails
			$add_filter = 'AND `users`.`state` = \'2\' ';
		} elseif($filter == 3) {       // Spam emails
			$add_filter = 'AND `users`.`state` = \'4\' ';
		} elseif($filter == 4) {       // Suspended by administrations
			$add_filter = 'AND `users`.`state` = \'3\' ';
		} else {
			$add_filter = '';
		}	
		
		// Set start up and header
		if(is_numeric($from) && $from > 0 ) { 
			$from = 'AND idu < \''.$this->db->real_escape_string($from).'\'';
			$header = '<div class="brz-opacity brz-tiny-2 brz-text-black brz-super-grey brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-More_users'].'</div>';
		} else {
			$header = '<div class="brz-opacity brz-tiny-2 brz-text-black brz-super-grey brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-RESULTS'].'</div>';
			$from = '';
		}
		
        $rows = array();
		
		// Select from general users
		$users = $this->db->query(sprintf("SELECT * FROM `users` WHERE (`users`.`username` LIKE '%s' OR concat_ws(' ', `users`.`first_name`, `users`.`last_name`) LIKE '%s') %s $add_filter ORDER BY `users`.`idu` DESC LIMIT %s", '%'.$this->db->real_escape_string($val).'%', '%'.$this->db->real_escape_string($val).'%',$from,$limit));
		
		if(!empty($users)) {
			
			// Fetch users
			while($row = $users->fetch_assoc()) {
				$rows[] = $row;
		    }
			
		}	
		
		// Check for more results
		$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL;

		// If results exists
		if(!empty($rows)) {

			$people = '<div class="brz-white brz-new-container">'.$header;
            
			// Import main class functions
			if(!class_exists('main')) {
				require_once(__DIR__ . '/classes.php');
			}
			
			$main = new main;
			$main->db = $this->db;
		    
			// Generate user from each row
		    foreach($rows as $row) {
				     
					// Add user to list
					$people .= '<div class="brz-padding brz-border-top brz-border-super-grey brz-clear">
					                <div class="brz-left">
									    <img src="'.$TEXT['installation'].'/thumb.php?src='.$row['image'].'&fol=a&w=35&h=35">
									</div>
									<div class="">
									    <div class="brz-no-overlow">
										    <div class="brz-right brz-small">
											    <span onclick="editProfileAdmin('.$row['idu'].')" class="brz-cursor-pointer brz-text-pink brz-underline-hover brz-clear">
			                                        '.$TEXT['_uni-Edit'].'
					                            </span>
											</div>
										    <span onclick="editProfileAdmin('.$row['idu'].')" class="brz-text-blue-dark brz-small brz-padding brz-text-bold brz-underline-hover brz-cursor-pointer">'.fixName(80,$row['username'],$row['first_name'],$row['last_name']).' <span class="nav-item-text-inverse">'.$this->verifiedBatch($row['verified'],1).'</span></span>
										</div>
									</div>
					            </div>';
					
					// Set last processed id	
				    $last = $row['idu'];
					
			}
			
	        $people .= '</div>';
		
            // Add load more function if more results exists
			$people .= ($loadmore) ? addLoadmore($this->settings['inf_scroll'],$TEXT['_uni-ttl_more-results'],'searchAdmin('.$last.','.$filter.',6);') : closeBody($TEXT['_uni-No_more-results']);

			// Return results
			return $people;	
			
		} else {
			// Return no users found
			return bannerIt('search'.mt_rand(1,4),$TEXT['_uni-No_searchp'],sprintf($TEXT['_uni-No_searchp3_i'],$val));
		}
	}
	
    function deleteUser($id) {                               // Delete user
		
		// Escape ID
		$user_id_esc = $this->db->real_escape_string($id);
		
		// Delete the user from the database
		$this->db->query("DELETE FROM `users` WHERE `users`.`idu` = '$user_id_esc' ");

		// If the user deleted
		if($this->db->affected_rows) {
			
			// Select posts
			$user_posts = $this->db->query("SELECT * FROM `user_posts` WHERE `user_posts`.`post_by_id` = '$user_id_esc' ");
			
			// Fetch posts if exists
			if(!empty($user_posts)) {
				
				$rows = array();
				
				// Create ARRAYs 
			    while($row = $user_posts->fetch_assoc()) {
			        
					$rows[] = $row['post_id'];
    
	
					// Delete linked content
					if($post['post_type'] == 1) { 

                    	$images = explode(',', $row['post_content']);
					
						// Delete Post photo
						foreach($images as $image) {
							unlink("../../../uploads/posts/photos/".$image);
						}
					
			    	} elseif($row['post_type'] == 2) {
						unlink("../../../uploads/posts/videos/".$row['post_content']);               // Delete Post video
					} elseif($row['post_type'] == 3) {
						unlink("../../../uploads/profile/main/".$row['post_content']);               // DEL-Profile photo if it's not in use
					} elseif($row['post_type'] == 5) {
						unlink("../../../uploads/profile/covers/".$row['post_content']);             // DEL-Cover photo if it's not in use
					}
				
		        }
				
				$user_posts->close();
				
				// Implode results make usable in MySQL IN Clause
				$user_posts_ids = implode(',', $user_posts_is);
		
			    // Delete user's post loves and post's comments
			    $this->db->query("DELETE FROM `post_loves` WHERE `post_id` IN ({$user_posts_ids})");
			    $this->db->query("DELETE FROM `post_comments` WHERE `post_id` IN ({$user_posts_ids})");
				
			}
	
			// Update loves of posts that user has loved
			$this->db->query("UPDATE `user_posts` SET `user_posts`.`post_loves` = `user_posts`.`post_loves`-1 WHERE `post_id` IN (SELECT `post_id` FROM `post_loves` WHERE `post_loves`.`by_id` = '$user_id_esc' )");
			
			// Update comments 
			$this->db->query("UPDATE `user_posts` SET `user_posts`.`post_comments` = `user_posts`.`post_loves`-1 WHERE `post_id` IN (SELECT `post_id` FROM `post_comments` WHERE `post_comments`.`by_id` = '$user_id_esc' )");
			
			// Delete all the comments
			$this->db->query("DELETE FROM `post_comments` WHERE `post_comments`.`by_id` = '$user_id_esc' ");
			
			// Delete the loves
			$this->db->query("DELETE FROM `post_loves` WHERE `post_loves`.`by_id` = '$user_id_esc' ");
			
			// Delete the reports
			$this->db->query("DELETE FROM `reports` WHERE `by` = '$user_id_esc' ");
			
			// Delete all the friendships
			$this->db->query("DELETE FROM `friendships` WHERE `user1` = '$user_id_esc' OR `user2` = '$user_id_esc' ");
			
			// Delete chat messages
			$this->db->query("DELETE FROM `chat_messages` WHERE `by` = '$user_id_esc' ");
			
			// Left chat forms
			$this->db->query("DELETE FROM `chat_users` WHERE `uid` = '$user_id_esc' ");
			
			// Delete all the notifications
			$this->db->query("DELETE FROM `notifications` WHERE `not_from` = '$user_id_esc' OR `not_to` = '$user_id_esc' ");
		}
	}

	function executeReport($report_id,$safe = NULL) {	     // Mark safe or execute reported content
		
		// Select report
		$report_se = $this->db->query("SELECT * FROM `reports` WHERE `reports`.`id` = '{$this->db->real_escape_string($report_id)}' ");

		// If report exists
		if(!empty($report_se)) {
			$report = $report_se->fetch_assoc();
			$db_esc_content_id = $this->db->real_escape_string($report['content_id']);
		}
		
		// Close report as we are going to delete this report
		$report_se->close();
		
		// Delete report as well
		$this->db->query("DELETE FROM `reports` WHERE `reports`.`id` = '{$this->db->real_escape_string($report_id)}'");

		
		if(!empty($report) && $report['type'] == 1) {		
			
			if(!$safe) {
				// Delete user
			    $perforom = $this->deleteUser($report['content_id']);
			} else {
				$this->db->query("UPDATE `users` SET `safe` = '1' WHERE `users`.`idu` = '$db_esc_content_id' ");
			}
	
		} elseif(!empty($report) && $report['type'] == 2) {
			
			if(!$safe) {
			
				if(!class_exists('main')) {
					require_once(__DIR__ . '/classes.php');
			    }
			
				// Main class
				$import = new main;
				$import->db = $this->db;
			
				// Create owner rights
				$reported = array();
				$reported['idu'] =  $report['content_owner'];
				$reported['image'] = 'FORCE[]DELETE';
				$reported['cover'] = 'FORCE[]DELETE';
			
				// Delete post
				$perforom = $import->deletePost($report['content_id'],$reported);
				
			} else {
				$this->db->query("UPDATE `user_posts` SET `safe` = '1' WHERE `user_posts`.`post_id` = '$db_esc_content_id' ");
			}
			
		}elseif(!empty($report) && $report['type'] == 3) {
			
			if(!$safe) {
                
				if(!class_exists('main')) {
			        require_once(__DIR__ . '/classes.php');
		        }
				
				// Main class
				$import = new main;
				$import->db = $this->db;
			
				// Create owner rights 
				$reported = array();
				$reported['idu'] = $report['content_owner'];
			
				// Delete comment
				$perforom = $import->deleteComment($report['content_id'],$reported);
				
			} else {
				$this->db->query("UPDATE `post_comments` SET `safe` = '1' WHERE `post_comments`.`post_id` = '$db_esc_content_id' ");
			}	
		}
		
		return '';
	}
	
	function getReports($admin,$from = 0,$filter = 1) {      // Get reports
		// filter 1 = ALL | filter 2 = Users | filter 3 = Posts | filter 4 = comments
        global $TEXT,$page_settings;

		// Set starting points and header
		if(is_numeric($from) && $from > 0) {
			$start ='AND `reports`.`id` < \''.$this->db->real_escape_string($from).'\' ';
			$head = '<div class="brz-opacity brz-tiny-2 brz-text-black brz-super-grey brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-MORE_REPORTS'].'</div>';
		} else {
			$start = '';
			$head = '<div class="brz-opacity brz-tiny-2 brz-text-black brz-super-grey brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-MANAGE_REPORTS'].'</div>';
		}
		
		// Add filter
		if($filter == 2) {
			$fil = 'AND `reports`.`type` = \'1\''; // Users
		}elseif($filter == 3) { 
		    $fil = 'AND `reports`.`type` = \'2\''; // Posts
		}elseif($filter == 4) { 
		    $fil = 'AND `reports`.`type` = \'3\''; // Comments
		} else {
			$fil ='';
		}
		
		// select reports
		$reports = $this->db->query("SELECT * FROM `reports`, `users` WHERE `reports`.`from` = `users`.`idu` $fil $start ORDER BY `reports`.`id` DESC LIMIT 10");
		
		if($reports->num_rows) {
            
			// Fetch report rows
			while($row = $reports->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			// Delete load more identifier
			$loadmore = (array_key_exists(4, $rows)) ? array_pop($rows) : NULL;

			// Add header
		    $content = '<div class="brz-white brz-new-container">'.$head;
			
			if(!class_exists('main')) {
			    require_once(__DIR__ . '/classes.php');
		    }
			
			// Intilize main functioning class
			$functions = new main();
			$functions->db = $this->db;
			
			// Pass credentials
			$functions->username = $this->username;
			$functions->password = $this->password;
	
	        $sprint_filler_coll_tab = '<div class="brz-clear brz-tiny-4 brz-border-top brz-container-widel">
	                                        <div class="brz-no-overflow brz-full">
		                                        <div class="brz-set-item-l brz-left"> %s </div>
		                                        <div class="brz-no-overflow brz-set-item-c brz-clear">
			                                        %s
			                                    </div>		
		                                    </div>
	                                    </div>';
			// Generate report from each row
			foreach($rows as $row) {
				
				// Reported content owner
				$ownere = $this->db->real_escape_string($row['content_owner']);
				
				// Reported content ID
				$content_id_esc = $this->db->real_escape_string($row['content_id']);
			    
				// Select content owner
				$content_owner = $this->db->query("SELECT * FROM `users` WHERE `users`.`idu` = '$ownere' LIMIT 1");

                // Reset					
				$comments = $report_content = $image_download = $post_tags = $post_text = '';
				
				// Fetch content owner
				if($content_owner->num_rows) {
					$owner = $content_owner->fetch_assoc();
				}
		
		        // Add headings || viewable functions and more stuff to report
				if($row['type'] == 1) {
					$heading = $TEXT['_uni-Reported_user'];
					
					$user = $functions->getUserByID($row['content_id']);
					
					$bios_text = $prof_text = $home_text = $livi_text = $educ_text = $webs_text = '';
					
					if(!empty($user['profession'])) {
					    $prof_text = sprintf($sprint_filler_coll_tab,$TEXT['_uni-Profession'],$user['profession']);					
				    }
					
					if(!empty($user['from'])) {
					    $home_text = sprintf($sprint_filler_coll_tab,$TEXT['_uni-Hometown'],$user['from']);					
				    }	
				
					if(!empty($user['living'])) {
					    $livi_text = sprintf($sprint_filler_coll_tab,$TEXT['_uni-Living'],$user['living']);					
				    }
					
					if(!empty($user['study'])) {
					    $educ_text = sprintf($sprint_filler_coll_tab,$TEXT['_uni-EDU'],$user['study']);					
				    }

					if(!empty($user['website'])) {
					    $webs_text = sprintf($sprint_filler_coll_tab,$TEXT['_uni-Website'],$functions->parseText($user['website']));					
				    }	

					if(!empty($user['bio'])) {
					    $bios_text = sprintf($sprint_filler_coll_tab,$TEXT['_uni-Bio'],$user['bio']);					
				    }					
					
					$report_content = $prof_text.$home_text.$livi_text.$educ_text.$webs_text.$bios_text.sprintf($sprint_filler_coll_tab,$TEXT['_uni-Avatar'],'<a href="'.$TEXT['installation'].'/uploads/profile/main/'.$user['image'].'" title="'.$TEXT['_uni-ttl_dwn_post'].'" download="" class="brz-tiny-2 brz-text-blue-dark brz-underline-hover" href="javascript:void(0);">'.$TEXT['_uni-Download_attch'].'</a>').sprintf($sprint_filler_coll_tab,$TEXT['_uni-TTL-pc-sml'],'<a href="'.$TEXT['installation'].'/uploads/profile/covers/'.$user['cover'].'" title="'.$TEXT['_uni-ttl_dwn_post'].'" download="" class="brz-tiny-2 brz-text-blue-dark brz-underline-hover" href="javascript:void(0);">'.$TEXT['_uni-Download_attch'].'</a>') ;
					
				}elseif($row['type'] == 2) {
					$heading = $TEXT['_uni-Reported_post'];
					$viewable = '<span class="brz-btn brz-round brz-margin-top-small brz-blue-hd brz-hover-green" onclick="$(\'#report_'.$row['id'].'\').remove();$(\'#report_'.$row['id'].'\').next().remove();$(\'#'.$row['id'].'_c23asd78\').remove();loadPost('.$row['content_id'].')" ><i class="fa fa-external-link" ></i> <span class="brz-hide-small">'.$TEXT['_uni-View'].'</span></span>';
				    
					// Add title
			        $title_set = array("0" => "_uni-Status_updates","1" => "_uni-Photo",	"2" => "_uni-Grouped_chats",	"3" => "_uni-profile_photo","4" => "_uni-shared_video","5" => "_uni-profile_cover",);			

					$post = $functions->getPostByID($row['content_id']);
				
					if(in_array($post['post_type'],array("1","3","5"))) {
			
						if($post['post_type'] == 1) {         // Photo
							$path = $TEXT['installation'].'/uploads/posts/photos/'.$post['post_content'];
						} elseif($post['post_type'] == 3) {   // Profile image
			    			$path = $TEXT['installation'].'/uploads/profile/main/'.$post['post_content'];
		    			} elseif($post['post_type'] == 5) {   // Cover photo
			    			$path = $TEXT['installation'].'/uploads/profile/covers/'.$post['post_content'];
		    			}
			
            			// Generate download button			
		   	 			$download = '<a href="'.$path.'" title="'.$TEXT['_uni-ttl_dwn_post'].'" download="" class="brz-tiny-2 brz-text-blue-dark brz-underline-hover" href="javascript:void(0);">'.$TEXT['_uni-Download_attch'].'</a>';
		   	 	
					    $image_download = sprintf($sprint_filler_coll_tab,$TEXT['_uni-TYsPE2'],$download);

					}
					
					
					$parsed = $functions->parseText($post['post_text'],1);
					
					if(!empty($parsed)) {
					    $post_text = sprintf($sprint_filler_coll_tab,$TEXT['_uni-TYPE2sd3'],$functions->parseText($post['post_text'],1));	
					}
					
					if(!empty($post['post_tags'])) {
					    $post_tags = sprintf($sprint_filler_coll_tab,$TEXT['_uni-TYPE2ssd3'],$post['post_tags']);	
					}
					
				    $report_content = sprintf($sprint_filler_coll_tab,$TEXT['_uni-TYPE2'],$TEXT[$title_set[$post['post_type']]])
					                  .sprintf($sprint_filler_coll_tab,$TEXT['_uni-TYPE222'],'<span class="timeago" title="'.$post['post_time'].'">'.$post['post_time'].'</span>')
									  .$post_tags.$post_text.$image_download.'
										';
				
				}elseif($row['type'] == 3) {
					
					$heading = $TEXT['_uni-Reported_comment'];
					$comment = $functions->getCommentByID($row['content_id']);
					$report_content = sprintf($sprint_filler_coll_tab,$TEXT['_uni-TYPE222'],'<span class="timeago" title="'.$comment['time'].'">'.$comment['time'].'</span>').sprintf($sprint_filler_coll_tab,$TEXT['_uni-Comment_text'],$functions->parseText($comment['comment_text'],1));				
				}
				
				// Reset
				$val1 = $val2 = $val3 = $val4 = '';
				
				// Add report marks checked by user
				if($row['val1']) { $val1 = '<li><i class="fa fa-certificate brz-text-red"></i> '.$TEXT['_uni-Report-1'].'</li>'; }
				
				if($row['val2']) { $val2 = '<li><i class="fa fa-certificate brz-text-red"></i> '.$TEXT['_uni-Report-2'].'</li>'; }
				
				if($row['val3']) { $val3 = '<li><i class="fa fa-certificate brz-text-red"></i> '.$TEXT['_uni-Report-3'].'</li>'; }
				
				if($row['val4']) { $val4 = '<li><i class="fa fa-certificate brz-text-red"></i> '.$TEXT['_uni-Report-4'].'</li>'; }
				
				// Add report
				$content .= '<div id="report_'.$row['id'].'" class="brz-padding brz-border-top brz-border-super-grey brz-clear">
					                <div class="brz-left">
									    <img class="brz-image-margin-right brz-border brz-border-super-grey" onclick="editProfileAdmin('.$row['idu'].')" src="'.$TEXT['installation'].'/thumb.php?src='.$row['image'].'&fol=a&w=35&h=35">
									</div>
									<div class="brz-right brz-small">
										<span onclick="userDescription(\''.$row['id'].'_c23asd78\')" class="brz-cursor-pointer brz-text-pink brz-underline-hover brz-clear">
			                                <i class="fa fa-chevron-down brz-text-blue-dark brz-opacity brz-hover-opacity-of rotateable"></i>
					                    </span>
									</div>
	                                <div class="">
		                                <div class="brz-small brz-no-overflow">
			                                <div class="brz-line-o">
												<span onclick="editProfileAdmin('.$row['idu'].')" title="'.sprintf($TEXT['_uni-Profile_load_text2'],fixName(100,$row['username'],$row['first_name'],$row['last_name'])).'" class="brz-text-bold brz-cursor-pointer brz-text-blue-dark brz-underline-hover">
													'.fixName(14,$row['username'],$row['first_name'],$row['last_name']).' <span class="nav-item-text-inverse">'.$this->verifiedBatch($row['verified'],1).'
												</span>
											</div>
				                            <span class="">
					                            <span class="brz-text-super-grey brz-small">'.$heading.'</span>
				                            </span>	                               
		                                </div>
									</div>
					            </div>
								<div id="'.$row['id'].'_c23asd78" style="display:none;" class="brz-white-3">
									<div class="brz-text-grey brz-small brz-clear brz-padding">
									    <div class="brz-center">										
											<div class="brz-padding brz-border brz-text-blue-dark">
    											<div class="brz-large-min">'.$TEXT['_uni-Report'].'</div>
    											<div>'.$TEXT['_uni-Pending_reports_ttl'].' <span class="brz-small timeago" title="'.$row['time'].'">'.$row['time'].'</span></div>
											</div>			
										</div>
										<br>
								        <ul class="brz-ul brz-line-o brz-clear ">
											'.$val1.$val2.$val3.$val4.'
		                                </ul>										
										<hr class="brz-margin">										
										<div class="brz-text-black brz-padding"><span class="brz-medium">'.$TEXT['_uni-Reported_content'].'</span> : <span class="brz-text-super-grey">'.$heading.'</span></div>
										'.$report_content.'				
										<hr class="brz-margin">								
										<div class="brz-left">
									        <img class="brz-image-margin-right" onclick="editProfileAdmin('.$owner['idu'].')" src="'.$TEXT['installation'].'/thumb.php?src='.$owner['image'].'&fol=a&w=35&h=35">
									    </div>									
	                                    <div class="">
		                                    <div class="brz-small brz-no-overflow">
			                                    <div class="brz-line-o">
												    <span onclick="editProfileAdmin('.$owner['idu'].')" title="'.sprintf($TEXT['_uni-Profile_load_text2'],fixName(100,$owner['username'],$owner['first_name'],$owner['last_name'])).'" class="brz-text-bold brz-cursor-pointer brz-text-blue-dark brz-underline-hover">
													    '.fixName(14,$owner['username'],$owner['first_name'],$owner['last_name']).' <span class="nav-item-text-inverse">'.$this->verifiedBatch($owner['verified'],1).'
												    </span>
											    </div>
				                                <span class="">
					                                <span class="brz-text-super-grey brz-small">'.$TEXT['_uni-Content_owner'].'</span>
				                                </span>	                               
		                                    </div>
                                        </div>										
										<hr class="brz-margin">
										<div class="brz-center brz-padding">									
										    <button class="brz-new_btn brz-round brz-padding-standard brz-text-bold brz-act-it brz-tiny-2 brz-text-grey" onclick="$(\'#report_'.$row['id'].'\').remove();$(\'#'.$row['id'].'_c23asd78\').remove();executeReport('.$row['id'].',1)"><img class="nav-item-text-inverse-big brz-img-following" alt="" src="'.$TEXT['DATA-IMG-7'].'">&nbsp;'.$TEXT['_uni-Mark_safe'].'</button>								    
											<button class="brz-new_btn brz-round brz-padding-standard brz-text-bold brz-act-it brz-tiny-2 brz-text-grey" onclick="$(\'#report_'.$row['id'].'\').remove();$(\'#'.$row['id'].'_c23asd78\').remove();executeReport('.$row['id'].',0)"><img class="nav-item-text-inverse-big brz-img-trash" alt="" src="'.$TEXT['DATA-IMG-7'].'">&nbsp;'.$TEXT['_uni-Delete_content'].'</button>									        
										</div> 
										<div class="brz-tiny brz-center">'.$TEXT['_uni-Report_t_notice'].'</div>
									</div>
								</div>';
				
				// Last processed id
				$from = $row['id'];
			}

			// Close container
            $content .= '</div>';
			
			// Add load more function if more results exists
			$content .= ($loadmore) ? addLoadmore($page_settings['inf_scroll'],$TEXT['_uni-ttl_more-results'],'loadReports('.$from.','.$filter.',6);') : closeBody($TEXT['lang_load_no_more_reports']);

		} else {
			// Return no users found
			return bannerIt('report'.mt_rand(1,4),$TEXT['_uni-No_searchp'],$TEXT['_uni-No_searchp3_csdci']);
		}
		return $content;
	}
	
	function editUser($id) {                                 // Return edit user profile page
		global $TEXT;

		// Select user
		$username = $this->db->query(sprintf("SELECT * FROM `users` WHERE `users`.`idu` = '%s' ", $this->db->real_escape_string($id)));	
		
		if($username->num_rows) {
			// Fetch user if exists
			$user = $username->fetch_assoc();
		} else {
			return '';
		}
		
		// If user exists
		if(!empty($user['idu'])) {
			
			// Check user state
			$suspended = ($user['state'] == 4 || $user['state'] == 3) ? '1' : '0';
			
			// Check IP address
			$ip = ($user['ip'] !== '') ? protectXSS($user['ip']) : '<i class="fa fa-exclamation-circle brz-text-red"></i>';
			
			// generate content
			return '<div class="brz-white brz-new-container">
						<div class="brz-opacity brz-tiny-2 brz-border-bottom brz-text-black brz-super-grey brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-EDIT_PROFILE'].'</div>				
						<div class="settings-content-class brz-clear brz-tiny-4 brz-container-widel">
	    					<div class="brz-no-overflow brz-full">
		    					<div class="brz-center brz-clear brz-full">
			    					<div class="brz-clear brz-padding-16">								    
										'.$this->getSelected($TEXT['_uni-Suspend'],'uUSAA-v7',getSelect($suspended,$TEXT['_uni-Yes'],$TEXT['_uni-No']),'<hr class="brz-margin">').'
										
										'.$this->enrollInput($TEXT['_uni-Username'],'uUSAA-vv1',protectXSS($user['username']),protectXSS($user['username']),'<hr class="brz-margin">').'
								
										'.$this->enrollInput($TEXT['_uni-First_name'],'uUSAA-vv2',protectXSS($user['first_name']),protectXSS($user['first_name']),'<hr class="brz-margin">').'
										
										'.$this->enrollInput($TEXT['_uni-Last_name'],'uUSAA-vv3',protectXSS($user['last_name']),protectXSS($user['last_name']),'<hr class="brz-margin">').'
										
										'.$this->enrollInput($TEXT['_uni-Email'],'uUSAA-vv4',protectXSS($user['email']),protectXSS($user['email']),'<hr class="brz-margin">').'
										
										'.$this->enrollInput($TEXT['_uni-From'],'uUSAA-vv5',protectXSS($user['from']),protectXSS($user['from']),'<hr class="brz-margin">').'
										
										'.$this->enrollInput($TEXT['_uni-Current_location'],'uUSAA-vv6',protectXSS($user['living']),protectXSS($user['living']),'<hr class="brz-margin">').'
										
										'.$this->enrollInput($TEXT['_uni-EDU'],'uUSAA-ss1',protectXSS($user['study']),protectXSS($user['study']),'<hr class="brz-margin">').'
										
										'.$this->enrollInput($TEXT['_uni-Profession'],'uUSAA-ss3',protectXSS($user['profession']),protectXSS($user['profession']),'<hr class="brz-margin">').'
										
										'.$this->enrollInput($TEXT['_uni-Website'],'uUSAA-ss2',protectXSS($user['website']),protectXSS($user['website']),'<hr class="brz-margin">').'					
				
										'.$this->getSelected($TEXT['_uni-Verified'],'uUSAA-v2',getSelect($user['verified'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Email_verified'],'uUSAA-v3',getSelect($user['state'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'').'
						
										<br><div class="brz-padding brz-super-grey brz-border brz-small brz-text-bold  brz-text-black brz-opacity brz-border-super-grey brz-border-blue">
				            				'.$TEXT['_uni-Block_reporting'].'
				        				</div><br>
										
										'.$this->getSelected($TEXT['_uni-Users'],'uUSAA-v4',getSelect($user['b_users'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Posts'],'uUSAA-v5',getSelect($user['b_posts'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Comments'],'uUSAA-v6',getSelect($user['b_comments'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'<hr class="brz-margin">').'
												
									    <div class="brz-padding-8 brz-container">
										    <div class="brz-col s4 brz-padding-medium">
											    <span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-User_ip'].':</span>
										    </div>
										    <div class="brz-col s6 brz-padding-medium">
											    <span class="brz-left">
												    '.$ip.'
											    </span>
										    </div>
									    </div>
										<div id="errors-eb"></div>
										<div class="brz-right brz-small brz-margin">
											<span></span>
				    						<div onclick="uUSAA('.$user['idu'].')"id="update-status-form-submit" name="update-status-form-submit" class="brz-round brz-padding-neo2  brz-tag brz-blue brz-small brz-hover-blue-hd brz-cursor-pointer brz-text-white brz-text-bold" >'.$TEXT['_uni-Save_changes'].'</div>
				    					</div>	
										<br><br>
										<br><br>				
									</div>
								</div>
							</div>
						</div>
					</div>
					<br><br><br><br>
				    ';
		}
	} 
	
	function updateUserprofile($id,$state,$verify,$b_users,$b_posts,$b_comments,$v8,$v9,$v10,$v11,$v12,$v13,$v14,$v15,$v16) {// Save user edit		
		global $TEXT,$page_settings;
		
		if(!class_exists('main')) {
			require_once(__DIR__ . '/classes.php');
		}
	
		$profile = new main();
		$profile->db = $this->db;
		
		// Select user
		$username = $this->db->query(sprintf("SELECT * FROM `users` WHERE `users`.`idu` = '%s' ", $this->db->real_escape_string($id)));	
		
		if($username->num_rows !== 0) {
			
			// Fetch user if exists
			$user = $username->fetch_assoc();
		
		} else {
			
			// Else return 0
			return '';
		}
		
        // Validation		
		if(!filter_var($this->db->real_escape_string($v11), FILTER_VALIDATE_EMAIL)) {
			
			// Invalid email
			return showError($TEXT['_uni-signup-4']);
			
		} elseif(strlen(trim($v9)) > 15) {
			
			// If first name is out no length
			return showError($TEXT['_uni-error_firstname_len']);
			
		} elseif(strlen(trim($v10)) > 15) {
			
			// If last name is out of length
			return showError($TEXT['_uni-error_lastname_len']);
			
		} elseif(!filter_var($this->db->real_escape_string($v11), FILTER_VALIDATE_EMAIL) || strlen(trim($v11)) < 3 || strlen(trim($v11) > 62)) {
			
			// Invalid email
			return showError($TEXT['_uni-signup-4']);
			
		} elseif($profile->isEmailExists($v11) && $user['email'] !== $v11) {
			
			// Verify whether user name exists
			return showError($TEXT['_uni-signup-6']);
			
		} elseif(isXSSED($v16) || strlen($v16)> 32) {
			
			// If values doesn't meets security requirementsor out of length
			return showError($TEXT['_uni-error_profession_len']);
			
		} elseif(isXSSED($v13) || strlen($v13)> 32) {
			
			// If values doesn't meets security requirementsor out of length
			return showError($TEXT['_uni-error_living_len']);
			
		} elseif(isXSSED($v12) || strlen($v12)> 32) {
			
			// If values doesn't meets security requirementsor out of length
			return showError($TEXT['_uni-error_hometown_len']);
			
		} elseif(isXSSED($v15) || strlen($v14)> 32) {
			
			// If values doesn't meets security requirementsor out of length
			return showError($TEXT['_uni-error_education_len']);
			
		} elseif(!empty($username) && $profile->isUsernameExists($v8) && $user['username'] !== $v8) {
			
			// Username already in use
			return showError($TEXT['_uni-Username_exists']);
			
		} elseif(strlen($v15) > 64 || (!filter_var($v15, FILTER_VALIDATE_URL) && !empty($v15))) {
			
			return showError($TEXT['_uni-error_web_in']);
			
		} elseif(strlen(trim($v8)) < $page_settings['username_min_len'] || strlen(trim($v8)) > $page_settings['username_max_len']) {
			
			// Verify whether user name is within allowed length
			return showError(sprintf($TEXT['_uni-signup-1'],$page_settings['username_min_len'],$page_settings['username_max_len']));
				
		} elseif(!ctype_alnum(trim($v8)) || is_numeric(trim($v8))) {
			
			// Allow only valid chars for username
			return showError($TEXT['_uni-signup-9']);
			
		} elseif(!empty($user['idu'])) {
			
			// Update profile
			$this->db->query(sprintf("UPDATE `users` SET `study` = '%s', `website` = '%s', `profession` = '%s', `state` = '%s', `verified` = '%s', `b_users` = '%s', `b_posts` = '%s', `b_comments` = '%s' , `username` = '%s' , `first_name` = '%s' , `last_name` = '%s' , `email` = '%s' , `from` = '%s' , `living` = '%s' WHERE `users`.`idu` = '%s' ", 
			$this->db->real_escape_string($v14),$this->db->real_escape_string($v15),$this->db->real_escape_string($v16),$this->db->real_escape_string($state), $this->db->real_escape_string($verify), $this->db->real_escape_string($b_users), 
		    $this->db->real_escape_string($b_posts), $this->db->real_escape_string($b_comments), $this->db->real_escape_string(protectInput($v8)),
			$this->db->real_escape_string(protectInput($v9)) , $this->db->real_escape_string(protectInput($v10)), $this->db->real_escape_string(protectInput($v11)),
			$this->db->real_escape_string(protectInput($v12)),$this->db->real_escape_string(protectInput($v13)),$this->db->real_escape_string($user['idu'])));	
		
	        // Return if affected 
		    return ($this->db->affected_rows) ? showSuccess($TEXT['_uni_admin_settings_success3-1']) : showNotification($TEXT['_uni-No_changes']);
		}		
	
	}
	
	function getEditAdmin($admin) {                          // Edit administration password
		global $TEXT;
		
		// Generate content
		return '<div class="brz-white brz-new-container">
		            <div class="brz-opacity brz-tiny-2 brz-border-bottom  brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-UPDATE_PASSWORD'].'</div>		            
						<div class="brz-border-bottom settings-content-class brz-clear brz-tiny-4 brz-container-widel">
	    					<div class="brz-no-overflow brz-full">
		    					<div class="brz-center brz-clear brz-full">
			    					<div class="brz-clear brz-padding-16">							
										'.$this->enrollInput($TEXT['_uni-Old_password'],'uS6-old',$TEXT['_uni-Old_password'],'','<hr class="brz-margin">','password').'
										
										'.$this->enrollInput($TEXT['_uni-New_password'],'uS6-new',$TEXT['_uni-Add_a_new_pass'],'','<hr class="brz-margin">','password').'
										
										'.$this->enrollInput($TEXT['_uni-Retype_new_password'],'uS6-re',$TEXT['_uni-Repeat'],'','','password').'
									<div id="errors-eb"></div>
									<div class="brz-right brz-small brz-margin">
										<span></span>
				    					<div onclick="uAdmin();"id="update-status-form-submit" name="update-status-form-submit" class="brz-round brz-padding-neo2  brz-tag brz-blue brz-small brz-hover-blue-hd brz-cursor-pointer brz-text-white brz-text-bold" >'.$TEXT['_uni-Save_changes'].'</div>
				    				</div>	
									<br><br>
									<br><br>
            					</div>			
							</div>
						</div>
					</div>
				</div>';
	}
	
	function passwordMatches($id,$pass) {                    // Accept MD5(PASS) Return 1 if matches inSESSION admin
		
		// Try Selecting profile using password and username
		$profile = $this->db->query(sprintf("SELECT * FROM `admins` WHERE `admins`.`id` = '%s' AND `admins`.`password` = '%s'", $this->db->real_escape_string($id),$this->db->real_escape_string($pass)));		
	    
		// Return test results
		return ($profile->num_rows) ? 1 : 0;
		
	}	

	function updateAdmin($admin,$v1,$v2,$v3) {               // Update administration password
	    // | $v1 = Old Password | $v2 = New password | $v3 = Retyped new password |
	    
		global $TEXT;
		
		// Count length
		$len = strlen($v2);
		
		// Encrypt passwords (Make cookies,SESSIONS and Database unreadable)
		$new = md5($v2);$old = md5($v1);
		
		if($len > 32 || $len < 6) {
			
			// Password out of length
			return showError($TEXT['_uni-error_password_len']);
			
		} elseif($v2 !== $v3) {
			
			// New password and retyped new password doesn't matches
			return showError($TEXT['_uni-error_password_match']);
			
		} elseif($this->passwordMatches($admin['id'],$old) !== 1) {
			
			// Old password is incorrect
			return showError($TEXT['_uni-error_old_not2']);
			
		} else {
			
			// Update
			$this->db->query(sprintf("UPDATE `admins` SET `admins`.`password` = '%s' WHERE `admins`.`id` = '%s'", $this->db->real_escape_string($new), $this->db->real_escape_string($admin['id'])));
		
			if($this->db->affected_rows) {
				
				// Update SESSION
				$_SESSION['a_password'] = $new;
                return showSuccess($TEXT['_uni-Updated_pass_s']);
				
		    } else {
				// Else return no changes notifier
				return showNotification($TEXT['_uni-No_changes']);
		    }
		}
	} 
	
	function newRegsettings($admin,$settings) {              // Edit user settings
		global $TEXT;
		
		// Generate content
		return '<div class="brz-white brz-new-container">
		            <div class="brz-opacity brz-tiny-2 brz-text-black brz-border-bottom brz-super-grey brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-USER_SETTINGS'].'</div>		            
						<div class="brz-border-bottom settings-content-class brz-clear brz-tiny-4 brz-container-widel">
	    					<div class="brz-no-overflow brz-full">
		    					<div class="brz-center brz-clear brz-full">
			    					<div class="brz-clear brz-padding-16">										
	                                    '.$this->getSelected($TEXT['_uni-Enable_Captcha'],'usa-v1',getSelect($settings['captcha'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'<hr class="brz-margin">').'
	                                    
										'.$this->getSelected($TEXT['_uni-Enable_E-Mail_verification'],'usa-v2',getSelect($settings['emails_verification'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'').'                                   
									    <br><div class="brz-padding brz-super-grey brz-border brz-small brz-text-bold brz-text-black brz-opacity brz-border-super-grey brz-border-blue">
				            				'.$TEXT['_uni-Username_Password'].'
				        				</div><br>
										
										'.$this->getSelected($TEXT['_uni-Username_Minimum_length'],'usa-v3',getSelVal($settings['username_min_len'],"4","6","8"),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Username_Maximum_length'],'usa-v4',getSelVal($settings['username_max_len'],"15","20","32"),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Password_Minimum_length'],'usa-v5',getSelVal($settings['password_min_len'],"4","6","8"),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Password_Maximum_length'],'usa-v6',getSelVal($settings['password_max_len'],"15","20","32"),'').'
										
										<br><div class="brz-padding brz-super-grey brz-border brz-small brz-text-bold brz-text-black brz-opacity brz-border-super-grey brz-border-blue">
				            				'.$TEXT['_uni-Defaults_for_new_users'].'
				        				</div><br>
										
										'.$this->getSelected($TEXT['_uni-Verified'],'usa-v7',getSelect($settings['def_p_verified'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'').'

										<br><div class="brz-padding brz-super-grey brz-border brz-small brz-text-bold brz-text-black brz-opacity brz-border-super-grey brz-border-blue">
				            				'.$TEXT['_uni-Notifications_settings'].'
				        				</div><br>
										
										'.$this->getSelected($TEXT['_uni-Notifications_type'],'usa-v8',getSelect($settings['def_n_type'],$TEXT['_uni-Real_time'],$TEXT['_uni-Manual']),'<hr class="brz-margin">').'
											
										'.$this->getSelected($TEXT['_uni-Notifications_per_page'],'usa-v9',getSelVal($settings['def_n_per_page'],'5','10','15'),'').'
										
										<br><div class="brz-padding brz-super-grey brz-border brz-small brz-text-bold brz-text-black brz-opacity brz-border-super-grey brz-border-blue">
				            				'.$TEXT['_uni-Notifications_me_when2'].'
				        				</div><br>
										
										'.$this->getSelected($TEXT['_uni-Accept_my_re2'],'usa-v10',getSelect($settings['def_n_accept'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Follow_me2'],'usa-v11',getSelect($settings['def_n_follower'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Likes_post2'],'usa-v12',getSelect($settings['def_n_like'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Comment_post2'],'usa-v13',getSelect($settings['def_n_comment'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Block_moderators'],'usa-v14',getSelect($settings['def_p_moderators'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'').'
										
										<br><div class="brz-padding brz-super-grey brz-border brz-small brz-text-bold brz-text-black brz-opacity brz-border-super-grey brz-border-blue">
				            				'.$TEXT['_uni-Privacy_settings'].'
				        				</div><br>
										
										'.$this->getSelected($TEXT['_uni-Can_follow2'],'usa-v15',getSelect($settings['def_p_private'],$TEXT['_uni-Requires_approval2'],$TEXT['_uni-Yes']),'').'
										
										<br><div class="brz-padding brz-super-grey brz-border brz-small brz-text-bold brz-text-black brz-opacity brz-border-super-grey brz-border-blue">
				            				'.$TEXT['_uni-Who_can_see2'].'
				        				</div><br>
										
										'.$this->getSelected($TEXT['_uni-Posts'].' / '.$TEXT['_uni-Gallery'],'usa-v16',getSelect($settings['def_p_posts'],$TEXT['_uni-Followers'],$TEXT['_uni-Public']),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Followers'],'usa-v17',getSelect($settings['def_p_followers'],$TEXT['_uni-Followers'],$TEXT['_uni-Public']),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-People_you_follows'],'usa-v18',getSelect($settings['def_p_followings'],$TEXT['_uni-Followers'],$TEXT['_uni-Public']),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Profession'],'usa-v19',getSelect($settings['def_p_profession'],$TEXT['_uni-Followers'],$TEXT['_uni-Public']),'<hr class="brz-margin">').'

										'.$this->getSelected($TEXT['_uni-Hometown'],'usa-v20',getSelect($settings['def_p_hometown'],$TEXT['_uni-Followers'],$TEXT['_uni-Public']),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Current_location'],'usa-v21',getSelect($settings['def_p_location'],$TEXT['_uni-Followers'],$TEXT['_uni-Public']),'').'
										
										<br><div class="brz-padding brz-super-grey brz-border brz-small brz-text-bold brz-text-black brz-opacity brz-border-super-grey brz-border-blue">
				            				'.$TEXT['_uni-Profile_search_results'].'
				        				</div><br>
										
										'.$this->getSelected($TEXT['_uni-Maximum_posts_included'],'usa-v22',getSelVal($settings['def_r_posts_per_page'],"5","8","12"),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Maximum_followers_included'],'usa-v23',getSelVal($settings['def_r_followers_per_page'],"8","10","15"),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Maximum_followings_included'],'usa-v24',getSelVal($settings['def_r_followings_per_page'],"8","10","15"),'').'
										
										<br><div class="brz-padding brz-super-grey brz-border brz-small brz-text-bold brz-text-black brz-opacity brz-border-super-grey brz-border-blue">
				            				'.$TEXT['_uni-Block_new_users_reporting'].'
				        				</div><br>
										
										'.$this->getSelected($TEXT['_uni-Users'],'usa-v25',getSelect($settings['def_b_users'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Posts'],'usa-v26',getSelect($settings['def_b_posts'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Comments'],'usa-v27',getSelect($settings['def_b_comments'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'').'
										
										<br><div class="brz-padding brz-super-grey brz-border brz-small brz-text-bold brz-text-black brz-opacity brz-border-super-grey brz-border-blue">
				            				'.$TEXT['_uni-SMTP_conf'].'
				        				</div><br>

                                        '.$this->getSelected($TEXT['_uni-Use_SMTP_integration'],'usa-v28',getSelect($settings['smtp_email'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'<hr class="brz-margin">').'										
                                        
										'.$this->getSelected($TEXT['_uni-Requires_SMTP_auth'],'usa-v29',getSelect($settings['smtp_auth'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'<hr class="brz-margin">').'										
										
										'.$this->enrollInput($TEXT['_uni-SMTP_host'],'usa-v30',$TEXT['_uni-Your_SMTP_host'],$settings['smtp_host'],'<hr class="brz-margin">').'
										
										'.$this->enrollInput($TEXT['_uni-SMTP_port'],'usa-v31',$TEXT['_uni-SMTP_port_emails'],$settings['smtp_port'],'<hr class="brz-margin">').'
										
										'.$this->enrollInput($TEXT['_uni-SMTP_user'],'usa-v32',$TEXT['_uni-Your_email_SMTP_username'],$settings['smtp_username'],'<hr class="brz-margin">').'
										
										'.$this->enrollInput($TEXT['_uni-SMTP_pass'],'usa-v33',$TEXT['_uni-Your_email_password'],$settings['smtp_password'],'').'
				        					
									</div>			
									<div id="errors-eb"></div>
									<div class="brz-right brz-small brz-margin">
										<span></span>
				    					<div onclick="uUSA()" id="update-status-form-submit" name="update-status-form-submit" class="brz-round brz-padding-neo2  brz-tag brz-blue brz-small brz-hover-blue-hd brz-cursor-pointer brz-text-white brz-text-bold" >'.$TEXT['_uni-Save_changes'].'</div>
				    				</div>	
									<br><br>
									<br><br>
            					</div>			
							</div>
						</div>
					</div>
				</div>
				<br><br><br><br><br><br>
				</div>
				';		
	}
	
	function getWebsettings($admin,$settings) {              // Edit website settings
		global $TEXT;				

		return '<div class="brz-white brz-new-container">
		            <div class="brz-opacity brz-tiny-2 brz-text-black brz-border-bottom brz-super-grey brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-WEBSITE_SETTINGS'].'</div> 
						<div class="brz-border-bottom settings-content-class brz-clear brz-tiny-4 brz-container-widel">
	    					<div class="brz-no-overflow brz-full">
		    					<div class="brz-center brz-clear brz-full">
			    					<div class="brz-clear brz-padding-16">			
																		
										'.$this->enrollInput($TEXT['_uni-Website_name'],'web_name',$TEXT['_uni-Name_brand'],$settings['web_name'],'<hr class="brz-margin">').'
										
										'.$this->enrollInput($TEXT['_uni-Website_title'],'web_title',$TEXT['_uni-Title_pages'],$settings['title'],'<hr class="brz-margin">').'
										
										'.$this->enrollInput($TEXT['_uni-Font_colours_welcome'],'font_colours',$TEXT['_uni-Contrast_background'],$settings['font_colors_welcome'],'').'
				        				
										<br>
										<div class="brz-padding brz-super-grey brz-border brz-small brz-text-bold  brz-text-black brz-opacity brz-border-super-grey brz-border-blue">
				            				'.$TEXT['_uni-Limit_content_loads'].'
				        				</div>
										<br>
										
										'.$this->getSelected($TEXT['_uni-Posts_per_page'],'p_per_page',getSelVal($settings['posts_per_page'],'5','10','15'),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Photos_per_page_Gallery'],'p_per_page_2',getSelVal($settings['photos_per_page'],'9','12','18'),'<hr class="brz-margin">').'
									
										'.$this->getSelected($TEXT['_uni-Followers_Following_page'],'f_per_page',getSelVal($settings['results_per_page'],'10','15','20'),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Lovers_per_page'],'l_per_page',getSelVal($settings['lovers_per_page'],'10','15','20'),'<hr class="brz-margin">').'
									
										'.$this->getSelected($TEXT['_uni-Comments_per_widget'],'c_per_page',getSelVal($settings['comments_per_widget'],'6','12','18'),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Chats_per_widget'],'ch_per_page',getSelVal($settings['chats_per_page'],'6','12','18'),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Search_results_page_Gen'],'s_r_per_page',getSelVal($settings['search_results_per_page'],'15','20','35'),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Maximum_post'],'p_c_length2',getSelVal($settings['max_post_length'],'500','1000','2000'),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Maximum_Ccomments'],'m_c_length',getSelVal($settings['max_comment_length'],'100','150','200'),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Maximum_chat_message'],'m_c_m_length',getSelVal($settings['max_message_length'],'1000','2000','5000'),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Maximum_chat_message'],'m_c_m_length',getSelVal($settings['max_message_length'],'1000','2000','5000'),'').'
										
										<br>
				        				<div class="brz-padding brz-super-grey brz-border brz-small brz-text-bold  brz-text-black brz-opacity brz-border-super-grey brz-border-blue">
				            				'.$TEXT['_uni-IMAGE_SETTINGS'].'
				        				</div>
										<br>
										
										'.$this->getSelected($TEXT['_uni-Image_quality'],'i_u_quality',getSelVal($settings['jpeg_quality'],'75','90','100'),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Image_Size'],'i_u_size',getSelVal($settings['max_img_size'],'2','5','10'),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Maximum_post_image_size'],'m_p_i_length',getSelVal($settings['max_image_size'],'1280','2000','10000'),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Maximum_profile_image_size'],'m_p_i_length_2',getSelVal($settings['max_main_pics'],'1280','2000','10000'),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Maximum_cover_image_size'],'m_c_i_length',getSelVal($settings['max_cover_pics'],'1280','2000','10000'),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Maximum_chat_image_size'],'m_ccc_i_length',getSelVal($settings['max_chat_icons'],'1280','2000','10000'),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-Maximum_chat_cover_size'],'m_ccc_c_length',getSelVal($settings['max_chat_covers'],'1280','2000','10000'),'').'
	                                    <br>
				        				<div class="brz-padding brz-super-grey brz-border brz-small brz-text-bold  brz-text-black brz-opacity brz-border-super-grey brz-border-blue">
				            				'.$TEXT['_uni-EXTRA_FEATURES'].'
				        				</div>
										<br>
										'.$this->getSelected($TEXT['_uni-Mentions-title'],'mens_type',getSelect($settings['mentions_type'],$TEXT['_uni-Mentions-2'],$TEXT['_uni-Mentions-1']),'<hr class="brz-margin">').'
										
										'.$this->getSelected($TEXT['_uni-inf_scroll'],'inf_scrolling',getSelect($settings['inf_scroll'],$TEXT['_uni-Yes'],$TEXT['_uni-No']),'').'
									</div>						
									<div id="errors-eb"></div>
									<div class="brz-right brz-small brz-margin">
										<span></span>
				    					<div onclick="uWS();"id="update-status-form-submit" name="update-status-form-submit" class="brz-round brz-padding-neo2  brz-tag brz-blue brz-small brz-hover-blue-hd brz-cursor-pointer brz-text-white brz-text-bold" >'.$TEXT['_uni-Save_changes'].'</div>
				    				</div>	
									<br><br>
									<br><br>
            					</div>			
							</div>
						</div>
					</div>
				</div>';		
	}
	
	function getManageadds($admin,$settings) {               // Adds manager (sponsors)
		global $TEXT;

		return '<div class="brz-white brz-new-container">
		            <div class="brz-opacity brz-tiny-2 brz-text-black brz-border-bottom brz-super-grey brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-MANAGE_SPONSORS'].'</div>            
						<div class="brz-border-bottom settings-content-class brz-clear brz-tiny-4 brz-container-widel">
	    					<div class="brz-no-overflow brz-full">
		    					<div class="brz-center brz-clear brz-full">
			    					<div class="brz-clear brz-padding-16">			
	
	                                    <br>
				        				<div class="brz-padding brz-super-grey brz-border brz-small brz-text-bold brz-text-black brz-opacity brz-border-super-grey brz-border-blue">
				            				'.$TEXT['_uni-FIXED_ADS'].'
				        				</div>
										<br>
										
										<div class="brz-padding-16 brz-padding">
				            				'.$TEXT['_uni-fixed_add_ttl2'].' 
				           				 <br><textarea id="sponsor1" class="brz-margin-top-small brz-border brz-padding-large brz-hover-light-grey brz-text-responsive" style="width:100%;min-width:100%;max-width:100%" placeholder="'.$TEXT['_uni-fixed_add1'].'">'.$settings['fi_add_search'].'</textarea>
				        				</div>
				
				        				<div class="brz-padding-16 brz-padding">
				            				'.$TEXT['_uni-fixed_add_ttl2'].' 
				            				<br><textarea id="sponsor2" class="brz-margin-top-small brz-border brz-padding-large brz-hover-light-grey brz-text-responsive" style="width:100%;min-width:100%;max-width:100%" placeholder="'.$TEXT['_uni-fixed_add2'].'">'.$settings['fi_add_trending'].'</textarea>
				       				    </div>
					
				        				<div class="brz-padding-16 brz-padding">
				            				'.$TEXT['_uni-fixed_add_ttl2'].' 
				            				<br><textarea id="sponsor3" class="brz-margin-top-small brz-border brz-padding-large brz-hover-light-grey brz-text-responsive" style="width:100%;min-width:100%;max-width:100%" placeholder="'.$TEXT['_uni-fixed_add6'].'">'.$settings['fi_add_home1'].'</textarea>
				        				</div>

				        				<div class="brz-padding-16 brz-padding">
				            				'.$TEXT['_uni-fixed_add_ttl3'].' 
				            				<br><textarea id="sponsor4" class="brz-margin-top-small brz-border brz-padding-large brz-hover-light-grey brz-text-responsive" style="width:100%;min-width:100%;max-width:100%" placeholder="'.$TEXT['_uni-fixed_add3'].'">'.$settings['fi_add_feed'].'</textarea>
				        				</div>
				
				        				<div class="brz-padding-16 brz-padding">
				            				'.$TEXT['_uni-fixed_add_ttl4'].' 
				            				<br><textarea id="sponsor5" class="brz-margin-top-small brz-border brz-padding-large brz-hover-light-grey brz-text-responsive" style="width:100%;min-width:100%;max-width:100%" placeholder="'.$TEXT['_uni-fixed_add4'].'">'.$settings['fi_add_post'].'</textarea>
				        				</div>
					
				        				<div class="brz-padding-16 brz-padding">
				            				'.$TEXT['_uni-fixed_add_ttl4'].' 
				            				<br><textarea id="sponsor6" class="brz-margin-top-small brz-border brz-padding-large brz-hover-light-grey brz-text-responsive" style="width:100%;min-width:100%;max-width:100%" placeholder="'.$TEXT['_uni-fixed_add5'].'">'.$settings['fi_add_relatives'].'</textarea>
				        				</div>
										
	                                    <br>
				        				<div class="brz-padding brz-super-grey brz-border brz-small brz-text-bold brz-text-black brz-opacity brz-border-super-grey brz-border-blue">
				            				'.$TEXT['_uni-Pop_adds2'].'
				        				</div>
										<br>
									
										<div class="brz-padding-16 brz-padding">
				            				'.$TEXT['_uni-fixed_add_ttl1'].' 
				            				<br><textarea id="sponsor7" class="brz-margin-top-small brz-border brz-padding-large brz-hover-light-grey brz-text-responsive" style="width:100%;min-width:100%;max-width:100%" placeholder="'.$TEXT['_uni-fixed_add7'].'">'.$settings['po_add_visit'].'</textarea>
				        				</div>
							
				        				<div class="brz-padding-16 brz-padding">
				            				'.$TEXT['_uni-fixed_add_ttl1'].' 
				            				<br><textarea id="sponsor8" class="brz-margin-top-small brz-border brz-padding-large brz-hover-light-grey brz-text-responsive" style="width:100%;min-width:100%;max-width:100%" placeholder="'.$TEXT['_uni-fixed_add2'].'">'.$settings['po_add_trending'].'</textarea>
				        				</div>
				
				        				<div class="brz-padding-16 brz-padding">
				            				'.$TEXT['_uni-fixed_add_ttl2'].' 
				            				<br><textarea id="sponsor9" class="brz-margin-top-small brz-border brz-padding-large brz-hover-light-grey brz-text-responsive" style="width:100%;min-width:100%;max-width:100%" placeholder="'.$TEXT['_uni-fixed_add6'].'">'.$settings['po_add_home'].'</textarea>
				        				</div>
				
				        				<div class="brz-padding-16 brz-padding">
				            				'.$TEXT['_uni-fixed_add_ttl2'].' 
				            				<br><textarea id="sponsor10" class="brz-margin-top-small brz-border brz-padding-large brz-hover-light-grey brz-text-responsive" style="width:100%;min-width:100%;max-width:100%" placeholder="'.$TEXT['_uni-fixed_add8'].'">'.$settings['po_add_out'].'</textarea>
				        				</div>				

				        				<div class="brz-padding-16 brz-padding">
				            				'.$TEXT['_uni-fixed_add_ttl3'].' 
				            				<br><textarea id="sponsor11" class="brz-margin-top-small brz-border brz-padding-large brz-hover-light-grey brz-text-responsive" style="width:100%;min-width:100%;max-width:100%" placeholder="'.$TEXT['_uni-fixed_add9'].'">'.$settings['po_add_conn_user'].'</textarea>
				        				</div>
				
				        				<div class="brz-padding-16 brz-padding">
				            				'.$TEXT['_uni-fixed_add_ttl3'].' 
				            				<br><textarea id="sponsor12" class="brz-margin-top-small brz-border brz-padding-large brz-hover-light-grey brz-text-responsive" style="width:100%;min-width:100%;max-width:100%" placeholder="'.$TEXT['_uni-fixed_add10'].'">'.$settings['po_add_conn_post'].'</textarea>
				        				</div>	
										
									</div>		
									<div id="errors-eb"></div>
									<div class="brz-right brz-small brz-margin">
										<span></span>
				    					<div onclick="saveAdds();"id="update-status-form-submit" name="update-status-form-submit" class="brz-round brz-padding-neo2  brz-tag brz-blue brz-small brz-hover-blue-hd brz-cursor-pointer brz-text-white brz-text-bold" >'.$TEXT['_uni-Save_changes'].'</div>
				    				</div>	
									<br><br>
									<br><br>
            					</div>			
							</div>
						</div>
					</div>
				</div>';		
	}
	
    function updateSettings($settings_set,$keys_set,$protection_set) { // Update Settings universal function
		global $TEXT;

		if (is_array($settings_set)) {
			
			//	Reset
			$i = $changes = 0 ;

			foreach ($settings_set as $setting) {
			
				// Enable disable protection on fields and bind values and update settings
				$this->db->query(sprintf("UPDATE `settings` SET `settings`.`value` = '%s' WHERE `settings`.`key` = '%s'",  $this->db->real_escape_string(((in_array($i, $protection_set)) ? protectInput($setting) : $setting)), $this->db->real_escape_string($keys_set[$i])));
			
				// Verify changes made
				if ($this->db->affected_rows) {
					$changes = TRUE;
				}

				// Next
				$i++ ;

			}

			// Check for affected rows and return message
		    return ($changes) ? showSuccess($TEXT[$keys_set[$i]]) : showNotification($TEXT['_uni-No_changes']); 
			
		} else {
			
			// Error not a array
		    return showError($TEXT['_uni-No_changes']); 

		}
	}
	
	function genNavigation($admin) {                         // Fetch administration navigation
		global $TEXT;

		// Generate navigation from template 
		return display('themes/'.$TEXT['theme'].'/html/navigations/main_admin'.$TEXT['templates_extension']);
		
	}

	function manageUsers($from,$filter = 0) {                // Manage users
		global $TEXT ;
		
		// Limit
		$limit = 31;

		// Set starting point
		if(is_numeric($from) && $from > 0 ) {
			$start = 'WHERE idu < \''.$this->db->real_escape_string($from).'\'';
            $and = 'AND';
			$header = '<div class="brz-opacity brz-tiny-2 brz-text-black brz-super-grey brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-More_users'].'</div>';		
		} else {
		    $header = '<div class="brz-opacity brz-tiny-2 brz-text-black brz-super-grey brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-MANAGE_USERS'].'</div>';
			$start = '';
			$and = 'WHERE';
		}
		
		// Add filter
		if($filter == 1) {
			$add_filter = '`users`.`verified` = \'1\'';        // Verified users
		} elseif($filter == 2) { 
		    $add_filter = '`users`.`state` = \'1\'';           // Verified emails
		} elseif($filter == 3) { 
		    $add_filter = '`users`.`state` = \'3\'';           // Suspended
		} elseif($filter == 4) { 
		    $add_filter = '`users`.`state` = \'4\'';           // Using Spam emails
		} elseif($filter == 5) { 
		    $add_filter = '`users`.`state` = \'2\'';           // Non verified emails
		} elseif($filter == 6) { 
		    $add_filter = '`users`.`ip` = \'\'';               // Without IP info
		} elseif($filter == 7) { 
		    $add_filter = '`users`.`safe` = \'1\'';            // Marked safe
		} elseif($filter == 8) { 
		    $add_filter = '`users`.`verified` = \'0\'';        // Not verified
		} elseif($filter == 10) { 
		    $add_filter = '`users`.`state` NOT IN(4,3)';       // Not suspended 
		} else {
			$add_filter = '';
			$and = '';
		}
		
		// Check whether filter is set to show oldest
		if($filter == 9) {
			
		    // Add starting point	
			$start = (is_numeric($from) && $from > 0 ) ? 'WHERE idu > \''.$this->db->real_escape_string($from).'\'' : '' ;		
			
			// reverse order
			$add_filter = 'ORDER BY `users`.`idu` ASC LIMIT 31';
			
		} else {
			$add_filter = $add_filter.' ORDER BY `users`.`idu` DESC LIMIT 31';
		}
	
	    // Select users
		$result = $this->db->query("SELECT * FROM `users` $start $and $add_filter ");

	    // Reset
		$rows = array(); 
    
		// If users exists
		if(!empty($result) && $result->num_rows) {
			
			// Fetch users
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			// Check for more results
			$loadmore = (array_key_exists($limit-1, $rows)) ? array_pop($rows) : NULL ;
			
			// Add Header if available
			$people = '<div class="brz-white brz-new-container">'.$header;
			
			if(!class_exists('main')) {
				require_once(__DIR__ . '/classes.php');
			}
			
			// Create main class
			$main = new main;
			$main->db = $this->db;
			
			// Generate user from each row
		    foreach($rows as $row) {
				     
					// Add user to list
					$people .= '<div class="brz-padding brz-border-top brz-border-super-grey brz-clear">
					                <div class="brz-left">
									    <img class="brz-border brz-border-super-grey" src="'.$TEXT['installation'].'/thumb.php?src='.$row['image'].'&fol=a&w=35&h=35">
									</div>
									<div class="">
									    <div class="brz-no-overlow">
										    <div class="brz-right brz-small">
											    <span onclick="editProfileAdmin('.$row['idu'].')" class="brz-cursor-pointer brz-text-pink brz-underline-hover brz-clear">
			                                        '.$TEXT['_uni-Edit'].'
					                            </span>
											</div>
										    <span onclick="editProfileAdmin('.$row['idu'].')" class="brz-text-blue-dark brz-small brz-padding brz-text-bold brz-underline-hover brz-cursor-pointer">'.fixName(80,$row['username'],$row['first_name'],$row['last_name']).' <span class="nav-item-text-inverse">'.$this->verifiedBatch($row['verified'],1).'</span></span>
										</div>
									</div>
					            </div>';
					
					// Set last processed id	
				    $last = $row['idu'];
					
			}
			
	        $people .= '</div>';
			
            // Add load more function if more results exists
			$people .= ($loadmore) ? addLoadmore($this->settings['inf_scroll'],'','manageUsers('.$last.','.$filter.',6);') : closeBody($TEXT['_uni-No_more-users']);
			
			// Return accordions
			return $people;	
			
		} else {			
			
			// Else no users
			return bannerIt('users'.mt_rand(1,4),$TEXT['_uni-No_searchp'],$TEXT['_uni-No_searchp3_cci']);				   
		}	
	}
	
	function getRegChart($admin,$type) {                     // Administration home | Throw Listed STATS
		global $TEXT;
		
        // Select Query
		$query = "SELECT(SELECT COUNT(idu) FROM `users` ) AS total_regs,
					(SELECT COUNT(page_id) FROM pages ) AS total_pages,
					(SELECT COUNT(group_id) FROM groups ) AS total_groups,
					(SELECT COUNT(post_id) FROM user_posts ) AS total_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 1 ) AS total_photos,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 ) AS total_youtubeshares,
					(SELECT COUNT(id) FROM reports ) AS total_reports,				
					(SELECT COUNT(idu) FROM `users` WHERE CURDATE() = `date` AND `state` != 4) AS today_regs,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` != 4 AND CURDATE() = date(`post_time`)) AS today_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 AND CURDATE() = date(`post_time`)) AS today_youtubeshares,
					(SELECT COUNT(id) FROM reports  WHERE CURDATE() = date(`time`)) AS today_reports,					
					(SELECT COUNT(idu) FROM `users` WHERE DATE_SUB(CURDATE(), INTERVAL 1 DAY) = `date` AND `state` != 4) AS yesterday_regs,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` != 4 AND DATE_SUB(CURDATE(), INTERVAL 1 DAY) = date(`post_time`)) AS yesterday_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 AND DATE_SUB(CURDATE(), INTERVAL 1 DAY) = date(`post_time`)) AS yesterday_youtubeshares,
					(SELECT COUNT(id) FROM reports WHERE DATE_SUB(CURDATE(), INTERVAL 1 DAY) = date(`time`)) AS yesterday_reports,		
					(SELECT COUNT(idu) FROM `users` WHERE DATE_SUB(CURDATE(), INTERVAL 2 DAY) = `date` AND `state` != 4) AS d2_regs,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` != 4 AND DATE_SUB(CURDATE(), INTERVAL 2 DAY) = date(`post_time`)) AS d2_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 AND DATE_SUB(CURDATE(), INTERVAL 2 DAY) = date(`post_time`)) AS d2_youtubeshares,
					(SELECT COUNT(id) FROM reports WHERE DATE_SUB(CURDATE(), INTERVAL 2 DAY) = date(`time`)) AS d2_reports,					
					(SELECT COUNT(idu) FROM `users` WHERE DATE_SUB(CURDATE(), INTERVAL 3 DAY) = `date` AND `state` != 4) AS d3_regs,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` != 4 AND DATE_SUB(CURDATE(), INTERVAL 3 DAY) = date(`post_time`)) AS d3_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 AND DATE_SUB(CURDATE(), INTERVAL 3 DAY) = date(`post_time`)) AS d3_youtubeshares,
					(SELECT COUNT(id) FROM reports WHERE DATE_SUB(CURDATE(), INTERVAL 3 DAY) = date(`time`)) AS d3_reports,
					(SELECT COUNT(idu) FROM `users` WHERE DATE_SUB(CURDATE(), INTERVAL 4 DAY) = `date` AND `state` != 4) AS d4_regs,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` != 4 AND DATE_SUB(CURDATE(), INTERVAL 4 DAY) = date(`post_time`)) AS d4_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 AND DATE_SUB(CURDATE(), INTERVAL 4 DAY) = date(`post_time`)) AS d4_youtubeshares,
					(SELECT COUNT(id) FROM reports WHERE DATE_SUB(CURDATE(), INTERVAL 4 DAY) = date(`time`)) AS d4_reports,
					(SELECT COUNT(idu) FROM `users` WHERE DATE_SUB(CURDATE(), INTERVAL 5 DAY) = `date` AND `state` != 4) AS d5_regs,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` != 4 AND DATE_SUB(CURDATE(), INTERVAL 5 DAY) = date(`post_time`)) AS d5_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 AND DATE_SUB(CURDATE(), INTERVAL 5 DAY) = date(`post_time`)) AS d5_youtubeshares,
					(SELECT COUNT(id) FROM reports WHERE DATE_SUB(CURDATE(), INTERVAL 5 DAY) = date(`time`)) AS d5_reports,
					(SELECT COUNT(idu) FROM `users` WHERE DATE_SUB(CURDATE(), INTERVAL 6 DAY) = `date` AND `state` != 4) AS d6_regs,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` != 4 AND DATE_SUB(CURDATE(), INTERVAL 6 DAY) = date(`post_time`)) AS d6_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 AND DATE_SUB(CURDATE(), INTERVAL 6 DAY) = date(`post_time`)) AS d6_youtubeshares,
					(SELECT COUNT(id) FROM reports WHERE DATE_SUB(CURDATE(), INTERVAL 6 DAY) = date(`time`)) AS d6_reports,
					(SELECT COUNT(idu) FROM `users` WHERE DATE_SUB(CURDATE(), INTERVAL 7 DAY) = `date` AND `state` != 4) AS d7_regs,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` != 4 AND DATE_SUB(CURDATE(), INTERVAL 7 DAY) = date(`post_time`)) AS d7_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 AND DATE_SUB(CURDATE(), INTERVAL 7 DAY) = date(`post_time`)) AS d7_youtubeshares,
					(SELECT COUNT(id) FROM reports WHERE DATE_SUB(CURDATE(), INTERVAL 7 DAY) = date(`time`)) AS d7_reports,
					(SELECT COUNT(idu) FROM `users` WHERE `state` != 4 AND date(`date`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()) AS lt_regs,
					(SELECT COUNT(page_id) FROM `pages` WHERE date(`time`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()) AS lt_pages,					
					(SELECT COUNT(group_id) FROM `groups` WHERE date(`time`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()) AS lt_groups,
					(SELECT COUNT(post_id) FROM user_posts WHERE date(`post_time`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()) AS lt_posts,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 1 AND date(`post_time`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()) AS lt_photos,
					(SELECT COUNT(post_id) FROM user_posts WHERE `post_type` = 4 AND date(`post_time`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()) AS lt_youtubeshares,
					(SELECT COUNT(id) FROM reports WHERE date(`time`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()) AS lt_reports					
					";
	
		// Query and fetch results
		$result = $this->db->query($query);
	
		// Get statics list and assign 
		
		list($stats['total_regs'],$stats['total_pages'],$stats['total_groups'],$stats['total_posts'],$stats['total_photos'],$stats['total_youtubeshares'],$stats['total_reports'],	
	
		$stats['today_regs'],$stats['today_posts'],$stats['today_youtubeshares'],$stats['today_reports'],
	
		$stats['yesterday_regs'],$stats['yesterday_posts'],$stats['yesterday_youtubeshares'],$stats['yesterday_reports'],
		
		$stats['d2_regs'],$stats['d2_posts'],$stats['d2_youtubeshares'],$stats['d2_reports'],
		
		$stats['d3_regs'],$stats['d3_posts'],$stats['d3_youtubeshares'],$stats['d3_reports'],
		
		$stats['d4_regs'],$stats['d4_posts'],$stats['d4_youtubeshares'],$stats['d4_reports'],
		
		$stats['d5_regs'],$stats['d5_posts'],$stats['d5_youtubeshares'],$stats['d5_reports'],
		
		$stats['d6_regs'],$stats['d6_posts'],$stats['d6_youtubeshares'],$stats['d6_reports'],
		
		$stats['d7_regs'],$stats['d7_posts'],$stats['d7_youtubeshares'],$stats['d7_reports'],
		
		$stats['lt_regs'],$stats['lt_pages'],$stats['lt_groups'],$stats['lt_posts'],$stats['lt_photos'],$stats['lt_youtubeshares'],$stats['lt_reports']

		) = $result->fetch_row() ;		
		
		// Percentage calculations
		$per_users = substr($stats['lt_regs']/$stats['total_regs']*100,0,5);
		$per_pages = substr($stats['lt_pages']/$stats['total_pages']*100,0,5);
		$per_groups = substr($stats['lt_groups']/$stats['total_groups']*100,0,5);
		$per_posts = substr($stats['lt_posts']/$stats['total_posts']*100,0,5);
		$per_photos = substr($stats['lt_photos']/$stats['total_photos']*100,0,5);
		$per_yts = substr($stats['lt_youtubeshares']/$stats['total_youtubeshares']*100,0,5);
		$per_rep = substr($stats['lt_reports']/$stats['total_reports']*100,0,5);

		// Storage calculation
		$avatars = round(getFolderSize('../../../uploads/profile/main')/1024/1024);	
		$covers = round(getFolderSize('../../../uploads/profile/covers')/1024/1024 + getFolderSize('../../../uploads/groups')/1024/1024);		
		$uploads = round(getFolderSize('../../../uploads/posts/photos')/1024/1024);	
		$chats = round(getFolderSize('../../../uploads/chats')/1024/1024);	
		$pages1 = round(getFolderSize('../../../uploads/pages/covers')/1024/1024);	
		$pages2 = round(getFolderSize('../../../uploads/pages/main')/1024/1024);	
		$pages = round($pages1+$pages2);	
		$cached = getFolderSize('../../../cache');				
		$temp_files = getFolderSize('../../../main/logs');				
		$total = round($avatars + $covers + $pages + $uploads + $chats);
		$per_cah = substr($cached/1024/1024/$total*100,0,5);
		
		// Time to last seven days
		$dateString = date('Y-m-d',time()); // for example
		$back = date('Y-m-d', strtotime("$dateString -7 days"));
		$days = '';
		$period = new DatePeriod(new DateTime($back), new DateInterval('P1D'), 7 );
     
	    // Today date
		$date = date('Y-m-d'); 

		// Create days
		foreach ($period as $day) { $days .= '"'.substr($day->format('l'),0,3).'",';}
	
	    // Plot graphs onscreen
		return  '<div class="brz-padding-16"><span class="brz-opacity brz-medium brz-text-blue-dark brz-padding brz-text-bold">'.$TEXT['_uni-PERFORMANCE'].'</span></div>		
				<div class="brz-padding"><canvas id="content_gt_graph" height="200"></canvas></div></div>
				<script>
					var ctx = document.getElementById("content_gt_graph");
					var myChart = new Chart(ctx, {
   						type: \'line\',
   						data: {
   						labels: ['.$days.'],
   						datasets: [{
   							label: \''.$TEXT['web_name'].'\',
   							data: ['.$stats['d7_posts'].', '.$stats['d6_posts'].', '.$stats['d5_posts'].', '.$stats['d4_posts'].', '.$stats['d3_posts'].' , '.$stats['d2_posts'].','.$stats['yesterday_posts'].','.$stats['today_posts'].'],
							borderColor: \'rgb(0, 204, 99)\',borderWidth: 1,fill:false,backgroundColor: "#fff",borderDashOffset: 0.0,borderJoinStyle: \'miter\',pointBorderColor: "rgba(75,192,192,1)",pointBackgroundColor: "#fff",pointBorderWidth: 1,pointHoverRadius: 5,pointHoverBackgroundColor: "rgb(0, 255, 153)",pointHoverBorderColor: "rgba(220,220,220,1)",pointHoverBorderWidth: 2,pointRadius: 3,pointHitRadius: 10,	
			    		},{
   							label: \''.$TEXT['_uni-Outer_sources'].'\',
   							data: ['.$stats['d7_youtubeshares'].', '.$stats['d6_youtubeshares'].', '.$stats['d5_youtubeshares'].', '.$stats['d4_youtubeshares'].', '.$stats['d3_youtubeshares'].' , '.$stats['d2_youtubeshares'].','.$stats['yesterday_youtubeshares'].','.$stats['today_youtubeshares'].'],
   							borderColor: \'rgb(230, 230, 0)\',borderWidth: 1,fill:false,backgroundColor: "#fff",borderDashOffset: 0.0,borderJoinStyle: \'miter\',pointBorderColor: "rgb(230, 230, 0)",pointBackgroundColor: "#fff",pointBorderWidth: 1,pointHoverRadius: 5,pointHoverBackgroundColor: "rgb(255, 255, 26)",pointHoverBorderColor: "rgb(230, 230, 0)",pointHoverBorderWidth: 2,pointRadius: 3,pointHitRadius: 10,	
			    		},{
   							label: \''.$TEXT['_uni-Pending_issues'].'\',
   							data: ['.$stats['d7_reports'].', '.$stats['d6_reports'].', '.$stats['d5_reports'].', '.$stats['d4_reports'].', '.$stats['d3_reports'].' , '.$stats['d2_reports'].','.$stats['yesterday_reports'].','.$stats['today_reports'].'],
   							borderColor: \'rgb(255, 51, 0)\',borderWidth: 1,fill:false,backgroundColor: "#fff",borderDashOffset: 0.0,borderJoinStyle: \'miter\',pointBorderColor: "rgb(204, 41, 0)",pointBackgroundColor: "#fff",pointBorderWidth: 1,pointHoverRadius: 5,pointHoverBackgroundColor: "rgb(255, 71, 26)",pointHoverBorderColor: "rgb(179, 36, 0)",pointHoverBorderWidth: 2,pointRadius: 3,pointHitRadius: 10,	
			    		}]
   		    		}});
   				</script>
                <div class="brz-padding-16"><span class="brz-opacity brz-medium brz-text-blue-dark brz-padding brz-text-bold">'.$TEXT['_uni-STORAGE'].'</span> <span class="brz-small brz-text-super-grey">'.sprintf($TEXT['_uni-sprint_storage'],$total).'</span></div>				
	            <div class="brz-padding"><canvas id="storage_gt_pie" width="400" height="200"></canvas></div>
   				<script>
   					var ctx = document.getElementById("storage_gt_pie");
   					var data = {
                        	labels: ["'.$TEXT['_uni-Avatars'].'","'.$TEXT['_uni-Covers'].'","'.$TEXT['_uni-Posts'].'","'.$TEXT['_uni-Chats'].'","'.$TEXT['_uni-Pages'].'"],
                        	datasets: [{data: ['.$avatars.', '.$covers.', '.$uploads.', '.$chats.', '.$pages.'],backgroundColor: ["#FF6384","#36A2EB","#FFCE56","#47d147","#eaa1b9"],hoverBackgroundColor: ["#ff1a4b","#2e9fea","#ffb700","#33cc33","#d1c2c9"]}]
						};
		
					var myPieChart = new Chart(ctx,{type: \'pie\',data: data
					});
				</script>
				<div class="brz-padding-16"><span class="brz-opacity brz-medium brz-text-blue-dark brz-padding brz-text-bold">'.$TEXT['_uni-REGISTRATIONS'].'</span></div>
		        <div class="brz-padding"><canvas id="reg_gt_graph" height="200"></canvas></div></div>
				<script>
					var ctx = document.getElementById("reg_gt_graph");
					var myChart = new Chart(ctx, {
   						type: \'line\',
   						data: {
   						labels: ['.$days.'],
   						datasets: [{
   							label: \''.$TEXT['_uni-New_Registrations'].'\',
   							data: ['.$stats['d7_regs'].', '.$stats['d6_regs'].', '.$stats['d5_regs'].', '.$stats['d4_regs'].', '.$stats['d3_regs'].' , '.$stats['d2_regs'].','.$stats['yesterday_regs'].','.$stats['today_regs'].'],
							fill:true,borderColor: \'rgb(0, 102, 204)\',borderWidth: 1,backgroundColor: "rgba(75,192,192,0.4)",borderDashOffset: 0.0,borderJoinStyle: \'miter\',pointBorderColor: "rgba(75,192,192,1)",pointBackgroundColor: "#fff",pointBorderWidth: 1,pointHoverRadius: 5,pointHoverBackgroundColor: "rgba(75,192,192,1)",pointHoverBorderColor: "rgba(220,220,220,1)",pointHoverBorderWidth: 2,pointRadius: 3,pointHitRadius: 10,	
			    		}]
   		    		}});
   				</script>	
				<div class="brz-padding-16"><span class="brz-opacity brz-medium brz-text-blue-dark brz-padding brz-text-bold">'.$TEXT['_uni-CONTENT'].'</span></div>
				<div class="brz-padding"><canvas id="content_gt_chart" width="400" height="200"></canvas></div>	
				<div class="brz-hide">
				    <div id="QUICK_DETACH" class="brz-new-container brz-dettachable brz-detach-to-threequarter brz-white brz-padding">
					    <div class="brz-opacity brz-tiny-2 brz-text-grey brz-padding brz-text-bold">
						    '.$TEXT['_uni-All_time_stocked'].'
                        </div>
						
					    <div class="brz-padding brz-line-o">
                            <div class="brz-xxlarge brz-full brz-text-blue-grey">'.readable($stats['total_regs']).' <span class="brz-small">'.$TEXT['_uni-Users'].'</span></div>
                            <div class="brz-small brz-full brz-text-super-grey"><span class="brz-text-green brz-italic">'.$per_users.'%</span> '.$TEXT['_uni-from_last_month'].'</div>			
                		</div>
						<hr style="margin:10px;">
					    <div class="brz-padding brz-line-o">
                            <div class="brz-xxlarge brz-full brz-text-blue-grey">'.readable($stats['total_pages']).' <span class="brz-small">'.$TEXT['_uni-Pages'].'</span></div>
                            <div class="brz-small brz-full brz-text-super-grey"><span class="brz-text-green brz-italic">'.$per_pages.'%</span> '.$TEXT['_uni-from_last_month'].'</div>			
                		</div>
                        <hr style="margin:10px;">						
					    <div class="brz-padding brz-line-o">
                            <div class="brz-xxlarge brz-full brz-text-blue-grey">'.readable($stats['total_groups']).' <span class="brz-small">'.$TEXT['_uni-Groups'].'</span></div>
                            <div class="brz-small brz-full brz-text-super-grey"><span class="brz-text-green brz-italic">'.$per_groups.'%</span> '.$TEXT['_uni-from_last_month'].'</div>			
                		</div>						
                        <hr style="margin:10px;">
					    <div class="brz-padding brz-line-o">
                            <div class="brz-xxlarge brz-full brz-text-blue-grey">'.readable($stats['total_posts']).' <span class="brz-small">'.$TEXT['_uni-Posts'].'</span></div>
                            <div class="brz-small brz-full brz-text-super-grey"><span class="brz-text-green brz-italic">'.$per_posts.'%</span> '.$TEXT['_uni-from_last_month'].'</div>			
                		</div>
                        <hr style="margin:10px;">
					    <div class="brz-padding brz-line-o">
                            <div class="brz-xxlarge brz-full brz-text-blue-grey">'.readable($stats['total_photos']).' <span class="brz-small">'.$TEXT['_uni-Photos'].'</span></div>
                            <div class="brz-small brz-full brz-text-super-grey"><span class="brz-text-green brz-italic">'.$per_photos.'%</span> '.$TEXT['_uni-from_last_month'].'</div>			
                		</div>	
						<hr style="margin:10px;">
					    <div class="brz-padding brz-line-o">
                            <div class="brz-xxlarge brz-full brz-text-blue-grey">'.readable($stats['total_youtubeshares']).' <span class="brz-small">'.$TEXT['_uni-Youtube_shares'].'</span></div>
                            <div class="brz-small brz-full brz-text-super-grey"><span class="brz-text-green brz-italic">'.$per_yts.'%</span> '.$TEXT['_uni-from_last_month'].'</div>			
                		</div>
				   </div>
				   
				   <div id="QUICK_DETACH_2" class="brz-new-container crackable brz-white brz-padding">
					    <div class="brz-opacity brz-tiny-2 brz-text-grey brz-padding brz-text-bold">
						    '.$TEXT['_uni-REPORTS'].'
                        </div>
						
					    <div class="brz-padding brz-line-o">
                            <div class="brz-xxlarge brz-full brz-text-blue-grey">'.readable($stats['total_reports']).' <span class="brz-small">'.$TEXT['_uni-Reports'].'</span></div>
                            <div class="brz-small brz-full brz-text-super-grey"><span class="brz-text-red brz-italic">'.$per_rep.'%</span> '.$TEXT['_uni-from_last_month'].'</div>			
                		</div>				
				   </div>
				   
				   <div id="QUICK_DETACH_3" class="brz-new-container crackable brz-white brz-padding">
					    <div class="brz-opacity brz-tiny-2 brz-text-grey brz-padding brz-text-bold">
						    '.$TEXT['_uni-CACHE_INFO'].'
                        </div>
						
					    <div class="brz-padding brz-line-o">
                            <div class="brz-xxlarge brz-full brz-text-blue-grey">'.readableBytes($cached).' <span class="brz-small">'.$TEXT['_uni-Storage_cached'].'</span></div>
                            <div class="brz-small brz-full brz-text-super-grey"><span class="brz-text-green brz-italic">'.$per_cah.'%</span> '.$TEXT['_uni-cache_sp'].'</div>			
                		</div>				
				   </div>
				   
				   <div id="QUICK_DETACH_4" class="brz-new-container crackable brz-white brz-padding">
					    <div class="brz-opacity brz-tiny-2 brz-text-grey brz-padding brz-text-bold">
						    '.$TEXT['_uni-TEMP_FILES'].'
                        </div>
						
					    <div id="TEMP_FILE_CONTAINER" class="brz-padding brz-clear brz-line-o">
                            <div class="brz-xxlarge brz-full brz-text-blue-grey">'.readableBytes($temp_files).' <span onclick="clearTemp();" id="settings-content-save-%s" class="brz-round brz-right brz-padding-tiny2 brz-hover-blue-hd brz-cursor-pointer brz-tag brz-blue brz-tiny-2 brz-text-white brz-text-bold">'.$TEXT['_uni-Clear'].'</span></div>
                        </div>				
				   </div>
				   
				</div>
				
				<script>
   					var ctx = document.getElementById("content_gt_chart");
   					var myChart = new Chart(ctx, {
   					    type: \'bar\',
   					    data: {
   					    labels: ["'.$TEXT['_uni-Users'].'", "'.$TEXT['_uni-Pages'].'", "'.$TEXT['_uni-Groups'].'", "'.$TEXT['_uni-Posts'].'", "'.$TEXT['_uni-Photos'].'", "'.$TEXT['_uni-Youtube'].'", "'.$TEXT['_uni-Reports'].'"],
   					    datasets: [{
   					        label: \''.$TEXT['_uni-Total'].'\',
   					        data: ['.$stats['total_regs'].', '.$stats['total_pages'].', '.$stats['total_groups'].', '.$stats['total_posts'].', '.$stats['total_photos'].', '.$stats['total_youtubeshares'].', '.$stats['total_reports'].'],
   					        backgroundColor: [\'rgba(54, 162, 235, 0.2)\',\'#ffc7da\',\'rgba(54, 198, 98, 0.2)\',\'rgba(255, 206, 86, 0.2)\',\'rgba(75, 192, 192, 0.2)\',\'rgba(153, 102, 255, 0.2)\',\'rgba(255, 99, 132, 0.2)\'],borderColor: [\'rgba(54, 162, 235, 1)\',\'#eaa1b9\',\'rgba(54, 198, 122, 1)\',\'rgba(255, 206, 86, 1)\',\'rgba(75, 192, 192, 1)\',\'rgba(153, 102, 255, 1)\',\'rgba(255,99,132,1)\'],borderWidth: 1}]
   					    },
   					    options: {scales: {yAxes: [{ticks: {beginAtZero:true}}]}}
					});	
					$("#RIGHT_LANGUAGE").before($("#QUICK_DETACH").detach()).before($("#QUICK_DETACH_2").detach()).before($("#QUICK_DETACH_3").detach()).before($("#QUICK_DETACH_4").detach());
					addCrack();
				</script>';
			
	}

	function applyTheme($theme) {	                         // Apply website theme
		
		// Update theme if exists
        return (file_exists('themes/'.$theme.'/theme.php')) ? $this->db->query(sprintf( "UPDATE `settings` SET `value` = '%s' WHERE `key` = 'theme'",$this->db->real_escape_string($theme))) : TRUE;		
	
	}
	
	function getPostBackgrounds($admin) {                    // Fetch post backgrounds
	    global $TEXT;
	    
        // Search for .jpg files
        $backgrounds = glob('../../../uploads/posts/backgrounds/' . '*.jpg', GLOB_BRACE);
	    
		// Add header
		$active = '<div class="brz-white brz-new-container"><div class="brz-opacity brz-tiny-2 brz-text-black brz-super-grey brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-ACTIVE_BACKGROUNDS2'].'</div><div class="brz-row-padding brz-section">';
		
		foreach(explode(',',$TEXT['ACTIVE_BACKGROUNDS']) as $image) {
			
			// Build responsive gallery from trending posts
			$active .= '<div class="brz-col brz-padding-8" style="width:20%;">
                            <img class="brz-round-xlarge" onclick="reorderBackground(\''.str_replace('.jpg','',$image).'\');" src="'.$TEXT['installation'].'/thumb.php?src='.$image.'.jpg&fol=bb&w=252&h=192" style="width:100%;cursor:pointer">
                        </div>';
		}
		
		$active .= '</div></div>';
		
		// Add header
		$available = '<div class="brz-white brz-new-container"><div class="brz-opacity brz-tiny-2 brz-text-black brz-super-grey brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-AVAILABLE_BACKGROUNDS'].'</div><div class="brz-row-padding brz-section">
		              <div onclick="$(\'#add_background_file\').click();" id="add_background_trigger" class="brz-col brz-opacity brz-hover-opacity-off s3 brz-padding-8">
                        <img class="brz-round" src="'.$TEXT['installation'].'/thumb.php?src=add-image.png&fol=bb&w=252&h=192" style="width:100%;cursor:pointer">
                      </div>';
		
		foreach(array_reverse($backgrounds) as $image) {
			
			$image = str_replace('../../../uploads/posts/backgrounds/','',$image);
			
			if(!in_array(str_replace('.jpg','',$image),explode(',',$TEXT['ACTIVE_BACKGROUNDS']))) {
			
			    if(!is_numeric(str_replace('.jpg','',$image))) {
					$c1 = 'brz-display-container';
					$c2 = '<span class="brz-display-topright brz-tag brz-red brz-round brz-card-2 brz-tiny">'.$TEXT['_uni-New'].'</span>';
				} else {
					$c1 = $c2 = '';
				}
			
				// Build responsive gallery from trending posts
				$available .= '<div class="brz-col s3 '.$c1.' brz-padding-8">
                            	<img class="brz-round" src="'.$TEXT['installation'].'/thumb.php?src='.$image.'&fol=bb&w=252&h=192" onclick="activateBackground(\''.str_replace('.jpg','',$image).'\');" style="width:100%;cursor:pointer">
                        	    '.$c2.'
							</div>';
						
			}
		}
		
		// Add photo upload form
		$available .= '</div></div>
						<form id="add_background" name="add_background" action="'.$TEXT['installation'].'/require/requests/update/add-background.php" onsubmit ="return false;" method="POST" enctype="multipart/form-data" target="add_background_target">   
                            <input style="display:none!important;" name="add_background_file" id="add_background_file" type="file"/>
                            <iframe id="add_background_target" name="add_background_target" src="" style="display: none"></iframe>
                        </form>
						<span id="addBackground_error"></span>
						<script>
						$("#add_background_file").on(\'change\', function(event){	
							$(document).on(\'change\', \':file\', function () {
								if($("#add_background_file").val()) {
									addBackground();
								}
							}); 
						});					
						</script>';
		
		return $active.$available;
	
	}
	
	function activateBackground($image) {                    // Activate post background
	    global $TEXT;
	    
	    // Confirm availability
		if(file_exists('../../../uploads/posts/backgrounds/'.$image.'.jpg')) {
			
			
			if(!is_numeric($image)) {
				rename('../../../uploads/posts/backgrounds/'.$image.'.jpg','../../../uploads/posts/backgrounds/'.str_replace('n-','',$image).'.jpg');
			    $image  = str_replace('n-','',$image);
			}
	
			// Get active backgrounds
			$backgrounds = explode(',',$TEXT['ACTIVE_BACKGROUNDS']);
			
			// Add background
			array_unshift($backgrounds, $image);
			
			// Maximum 10 backgrounds
			if(count($backgrounds) > 10) {
				array_pop($backgrounds);
			}
			
			// Update backgrounds
			$this->db->query(sprintf("UPDATE `settings` SET `value` = '%s' WHERE `key` = 'post_backgrounds'",$this->db->real_escape_string(implode(',',$backgrounds))));
			
		}

		// Load backgrounds wizard
		return '<script>loadBackgrounds();</script>';
	
	}
	
	function saveLanguage($name) {                          // Actiate language as default
	    global $TEXT;

		// Confirm availability
		if(file_exists('../../../languages/'.$name.'.php')) {
			
			// Update backgrounds
			$this->db->query(sprintf("UPDATE `settings` SET `value` = '%s' WHERE `key` = 'default_lang'",$this->db->real_escape_string($name)));
			
		}

		// Load backgrounds wizard
		return '<script>bodyLoader(\'content-body\');loadLanguages();</script>';	
	}
	
	function reorderBackground($image) {                     // Re-order post background
	    global $TEXT;

		// Get active backgrounds
		$backgrounds = explode(',',$TEXT['ACTIVE_BACKGROUNDS']);
		
		// Remove from list
		if (false !== $key = array_search($image, $backgrounds)) {
            unset($backgrounds[$key]);
        }
		
		// Add background
		array_unshift($backgrounds, $image);

		// Maximum 10 backgrounds
		if(count($backgrounds) > 10) {
			array_pop($backgrounds);
		}

		// Update backgrounds
		$this->db->query(sprintf("UPDATE `settings` SET `value` = '%s' WHERE `key` = 'post_backgrounds'",$this->db->real_escape_string(implode(',',$backgrounds))));

		// Load backgrounds wizard
		return '<script>loadBackgrounds();</script>';
	
	}
	
	function loadLanguages() {                                  // Fetch available languages
		global $TEXT,$page_settings;
		
		// Search languages in directory
		$languages = glob('../../../languages/' . '*.php', GLOB_BRACE);
	
		// Add animation class to page
		$all = '<div class="brz-new-container brz-white"><div class="brz-opacity brz-white brz-tiny-2 brz-super-grey brz-text-black brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-MANAGE_LANGUAGES'].'</div>';
		
		// Import platform info
		include(__DIR__ .'/platform.php');	
		
		$active_lang  = $TEXT['LANG_SETTINGS_FILE_NAME'];
		
		// Save important strings
		$active = $TEXT['_uni-Default'];
		$default = $TEXT['_uni-Make_default'];
		$support_error = $TEXT['_uni-Lang_support_error'];
		$installation = $TEXT['installation'];
		
		foreach($languages as $language) {
		
			// Include language file to et information
			include($language);

			// Check language language supports this versions
		    if(!in_array($PLATFORM['OLD_LANG_VERSION_CODES'],$TEXT['LANG_SETTINGS_VERION_CODE'])) {
				
				// If language doesn't supports add message 
				$lang_error = '<div class="brz-padding-8">
									<div class="brz-padding brz-leftbar brz-border-amber brz-round brz-sand">
										'.sprintf($support_error,$TEXT['LANG_SETTINGS_URL']).'
									</div>
								</div>';	
			} else {
				$lang_error = '';
			}

			// Check whether language is default
			if($TEXT['LANG_SETTINGS_FILE_NAME'] == $page_settings['default_lang']) {
				$state = '<span class="brz-round brz-padding-tiny2 brz-tag brz-blue brz-tiny-2 brz-text-white brz-text-bold">
							'.$active.'
					    </span>';
			} else {
				$state = '<a class="brz-new_btn brz-round brz-padding-standard brz-text-bold brz-act-it brz-tiny-2 brz-text-grey brz-cursor-pointer" onclick="saveLanguage(\''.$TEXT['LANG_SETTINGS_FILE_NAME'].'\')">
							'.$default.'
					    </a>';
			}
			
			// Generate language in list
			$all .= '<div class="brz-padding brz-padding-8 brz-border-top brz-border-super-grey brz-clear">
					            <div class="brz-left">
									    <i class="brz-large brz-padding fa fa-language brz-text-grey"></i>
									</div>
									<div class="brz-right brz-small">
									    '.$state.'
									</div>
	                                <div class="">
		                                <div class="brz-small brz-no-overflow">
			                                <div class="brz-line-o">
												<a target="_BLANK" class="brz-text-bold brz-text-blue-dark brz-underline-hover">
													'.protectXSS($TEXT['LANG_SETTINGS_NAME']).'
												</a>
											</div>
				                            <span class=""> 
											     '.protectXSS($TEXT['LANG_SETTINGS_AUTHOR']).'
												'.$lang_error.'
					                        </span>											
		                                </div>
									</div>
					            </div>';	
		}
		
		$all .= '</div>';

		// Return themes
		return $all;
	}
	
	function loadCategoris() {                                   // Fetch available categories
	    global $TEXT;

		// Select all categories
		$cats = $this->db->query("SELECT * FROM `categories` ORDER BY `cat_name`");
	
		// Reset
		$institutes = $brands = $artists = $entertainment = $communities  = array();
		
		// Add headers
		$institutes_cats = '<div onclick="userDescription(\'institutes_cats-all\');" class="brz-new-container brz-white"><div class="brz-opacity brz-white brz-tiny-2 brz-super-grey brz-text-black brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-Page_categories-institute'].'<span  class="brz-cursor-pointer brz-right brz-text-pink brz-underline-hover brz-clear"><i class="fa fa-chevron-down brz-text-blue-dark brz-opacity brz-hover-opacity-of rotateable"></i></span></div><div style="display:none;" id="institutes_cats-all">';
		$brands_cats = '<div onclick="userDescription(\'brands_cats-all\');" class="brz-new-container brz-white"><div class="brz-opacity brz-white brz-tiny-2 brz-super-grey brz-text-black brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-Page_categories-brand'].'<span  class="brz-cursor-pointer brz-right brz-text-pink brz-underline-hover brz-clear"><i class="fa fa-chevron-down brz-text-blue-dark brz-opacity brz-hover-opacity-of rotateable"></i></span></div><div style="display:none;" id="brands_cats-all">';
		$artists_cats = '<div onclick="userDescription(\'artists_cats-all\');" class="brz-new-container brz-white"><div class="brz-opacity brz-white brz-tiny-2 brz-super-grey brz-text-black brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-Page_categories-Public_Figure'].'<span  class="brz-cursor-pointer brz-right brz-text-pink brz-underline-hover brz-clear"><i class="fa fa-chevron-down brz-text-blue-dark brz-opacity brz-hover-opacity-of rotateable"></i></span></div><div style="display:none;" id="artists_cats-all">';
		$entertainment_cats = '<div onclick="userDescription(\'entertainment_cats-all\');" class="brz-new-container brz-white"><div class="brz-opacity brz-white brz-tiny-2 brz-super-grey brz-text-black brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-Page_categories-Entertainment'].'<span  class="brz-cursor-pointer brz-right brz-text-pink brz-underline-hover brz-clear"><i class="fa fa-chevron-down brz-text-blue-dark brz-opacity brz-hover-opacity-of rotateable"></i></span></div><div style="display:none;" id="entertainment_cats-all">';
		$communities_cats  = '<div onclick="userDescription(\'communities_cats-all\');" class="brz-new-container brz-white"><div class="brz-opacity brz-white brz-tiny-2 brz-super-grey brz-text-black brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-Page_categories-Cause_Community'].'<span  class="brz-cursor-pointer brz-right brz-text-pink brz-underline-hover brz-clear"><i class="fa fa-chevron-down brz-text-blue-dark brz-opacity brz-hover-opacity-of rotateable"></i></span></div><div style="display:none;" id="communities_cats-all">';
		
		// If not empty
	    if(!empty($cats) && $cats->num_rows) {
	    
		    // Fetch cats
			while($row = $cats->fetch_assoc()) {
				
				switch ($row['cat_sub']) {	
						
					case '2':
					    $institutes[] = $row;
						break;
					case '3':
					    $brands[] = $row;
						break;
					case '4':
					    $artists[] = $row;
						break;
					case '5':
					    $entertainment[] = $row;
						break;
					case '6':
					    $communities[] = $row;
						break;						
		
				}
				
				// For foreach loop
				$rows[] = $row;
				
			} 
			
			foreach($rows as $cat) {

			    if($cat['cat_type']) {
			        $state = '<a onclick="delCat(\''.$cat['cid'].'\');" class="brz-new_btn brz-round brz-cursor-pointer brz-padding-standard brz-text-bold brz-act-it brz-tiny-2 brz-text-grey brz-cursor-pointer">
							    '.$TEXT['_uni-Delete'].'
					        </a>';
				} else {
					$state = '<span title="'.$TEXT['_uni-category_fixed'].'" class="brz-tag brz-round brz-pink-official brz-tiny">!</span>';
				}
				
				// Generate list
				$add_new = '<div class="brz-padding brz-padding-8 brz-border-top brz-border-super-grey brz-clear">
								<div class="brz-right brz-small">
									'.$state.'
								</div>
	                            <div class="">
		                            <div class="brz-small brz-no-overflow">
			                            <div class="brz-line-o">
											<span class="brz-text-bold">
			                                    '.protectXSS($TEXT[$cat['cat_name']]).'
											</span>
										</div>										
		                            </div>
								</div>
					        </div>';
					
				// Categorize			
				switch ($cat['cat_sub']) {	
						
					case '2':
					    $institutes_cats .= $add_new;
						break;
					case '3':
					    $brands_cats .= $add_new;
						break;
					case '4':
					    $artists_cats .= $add_new;
						break;
					case '5':
					    $entertainment_cats .= $add_new;
						break;
					case '6':
					    $communities_cats .= $add_new;
						break;						
				}				
			}
		}
		
		// Add category wizard
		$sprint_wizard = '<div class="brz-padding brz-small brz-clear brz-padding-16 brz-border-top brz-clear">
							
							<div class="brz-center" onclick="$(this).slideUp(100,function(){$(this).next().slideDown(100);})" >
							    <span class="brz-new_btn brz-round brz-cursor-pointer brz-padding-standard brz-text-bold brz-act-it brz-tiny-2 brz-text-grey brz-cursor-pointer">
									'.$TEXT['_uni-Add_a_category'].'
					    		</span>
							</div>
							
							<div class="brz-center" style="display:none;" id="open_wizard_%s">
								<tr>
									<td>
										<span class="brz-text-grey brz-text-bold">'.$TEXT['_uni-lang_index'].'</span>
									</td>
									<td>
										<input id="add_cat_%s" class="brz-border brz-text-grey brz-small brz-welcome-input" placeholder="'.$TEXT['_uni-Add_category_eg'].'" value="" ></input>
									</td>
									<td>
										<span onclick="addCat(%s);" class="brz-round brz-padding-tiny2 brz-hover-blue-hd brz-cursor-pointer brz-tag brz-blue brz-tiny-2 brz-text-white brz-text-bold">
										'.$TEXT['_uni-Add_category'].'
					    			     </span>
									</td>
								</tr>
							</div>
						</div>
					    </div>';
		
        // Add wizards and close containers		
		$institutes_cats .= sprintf($sprint_wizard,'2','2','2').'</div>';
		$brands_cats .= sprintf($sprint_wizard,'3','3','3').'</div>';
		$artists_cats .= sprintf($sprint_wizard,'4','4','4').'</div>';
		$entertainment_cats .= sprintf($sprint_wizard,'5','5','5').'</div>';
		$communities_cats .= sprintf($sprint_wizard,'6','6','6').'</div>';

		return $institutes_cats.$brands_cats.$artists_cats.$entertainment_cats.$communities_cats;

	}
	
	function addCategory($name,$id) { 
        global $TEXT,$page_settings; 
		
		// No index
		if(empty($name)) {
			
			return showBox($TEXT['_uni-Add_index_err']);
		
		// Index not set
		} elseif(!isset($TEXT[$this->db->real_escape_string($name)])) {
			
			return showBox($TEXT['_uni-Add_index_err2'].$this->db->real_escape_string($name));
		
		// Parent category not found
		} elseif(!in_array($id,array(2,3,4,5,6))) {
			
			return showBox($TEXT['_uni-Add_index_err3']);
		
		// Confirmed
		} else {
			
			// Add new category
			$this->db->query(sprintf("INSERT INTO `categories` (`cid`, `cat_name`, `cat_sub`, `cat_type`) VALUES (NULL, '%s', '%s', '1');",$this->db->real_escape_string(substr(protectXSS($name),0,32)),$this->db->real_escape_string($id)));

			return showBox($TEXT['_uni-Added_cat_success']);
			
		}
	
	}
	
	function deleteCategory($cid) {
		global $TEXT;
		
		$this->db->query(sprintf("DELETE FROM `categories` WHERE `cid` = '%s' AND `cat_type` = '1'",$this->db->real_escape_string($cid)));
		
		return ($this->db->affected_rows) ? showBox($TEXT['_uni-Delete_index_done']) : showBox($TEXT['_uni-Delete_index_err']) ;
			
	}
	
	function websitePatches() {      
        global $TEXT,$page_settings;
		
        // Select all patches
		$get_updates = $this->db->query("SELECT * FROM `patches` ORDER BY `p_date` DESC");
	    
		// Reset
		$installed = $installed_exts = array();

		$installed_ups = '<div class="brz-new-container brz-white"><div class="brz-opacity brz-white brz-tiny-2 brz-super-grey brz-text-black brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-APPLIED_PATCHES'].'</div><div id="INS_UPDATES">';
		
		$installed_ups .= '<div id="UP_UPLOAD" class="brz-padding brz-cursor-pointer brz-padding-8 brz-border-top brz-border-super-grey brz-clear">
					            <div onclick="$(\'#EXTENSION\').click();" class="brz-left brz-opacity brz-hover-opacity-off">
									<img src="'.$TEXT['installation'].'/uploads/posts/backgrounds/add-image.png" class="brz-image-margin-right brz-image" width="60"></i>
								</div>
	                            <div onclick="$(\'#EXTENSION\').click();" class="">
		                            <div class="brz-small brz-no-overflow">
			                            <div class="">
											<span class="brz-text-bold brz-text-blue-dark">
												<span class="brz-mind-blue-text">'.$TEXT['_uni-apply_patch'].'</span>
											</span>
										</div>	
										<span class=""> 
											<span class="brz-text-super-grey brz-small">'.$TEXT['_uni-apply_patch2'].'</span>
					                    </span>										
		                            </div>
								</div>
								<form id="EXT_FORM" name="EXT_FORM" action="'.$TEXT['installation'].'/require/requests/update/apply-patch.php" onsubmit ="return false;" method="POST" enctype="multipart/form-data" target="EXT_FORM_TARGET">   
                                    <input style="display:none!important;" name="EXTENSION" id="EXTENSION" type="file"/>
                                    <iframe id="EXT_FORM_TARGET" name="EXT_FORM_TARGET" src="" style="display: none"></iframe>
								</form>
								<script>
									$("#EXTENSION").on(\'change\', function(event){	
										$(document).on(\'change\', \':file\', function () {
											if($("#EXTENSION")[0].files.length) {
												applyPatch();
												//$("EXTENSION_SELF").val($("#EXTENSION").val());
											}
										}); 
									});					
								</script>
					        </div>';
							
		// If patches exists
		if(!empty($get_updates) && $get_updates->num_rows) {
			
			// Fetch patches
			while($row = $get_updates->fetch_assoc()) {
				$installed[] = $row;		
			}
			
			foreach($installed as $update) {

				// Generate patches in list
				$installed_ups .= '<div class="brz-padding brz-padding-8 brz-border-top brz-border-super-grey brz-clear">
					            <div class="brz-left">
									<div class="brz-left">
                                        <span class="brz-padding"><i class="fa brz-text-body-it fa-code-fork"></i></span>
	                                </div>
								</div>
	                            <div class="">
		                            <div class="brz-small brz-no-overflow">
			                            <div class="brz-line-o">
											<span class="brz-text-bold brz-text-blue-dark">
												'.$TEXT['_uni-Patch'].' '.protectXSS($update['p_name_main']).'
											</span>
										</div>
				                        <span class=""> 
											'.addStamp($update['p_date']).' <span class="brz-text-super-grey brz-small">| '.($update['p_description']).'</span>
					                    </span>											
		                            </div>
								</div>
					        </div>';

			}
		}
	
		// Close titles
		$installed_ups .= '</div></div>';	
	
		// Return patches
		return $installed_ups;
		
	}
	
	function websiteUpdates() {      
        global $TEXT,$page_settings;
		
        // Select all updates
		$get_updates = $this->db->query("SELECT * FROM `updates` ORDER BY `u_date` DESC");
	    
		// Reset
		$installed = $installed_exts = array();
		
		// Check for active extensions before updating website
		$check = $this->db->query("SELECT * FROM `extensions` WHERE `ext_status` = '1' ");
		
		$error = ($check->num_rows) ? addLog($TEXT['_uni-ext-disabl-first'],1) : '' ;

		$installed_ups = '<div class="brz-new-container brz-white"><div class="brz-opacity brz-white brz-tiny-2 brz-super-grey brz-text-black brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-INSTALLED_UPDATES'].'</div>'.$error.'<div id="INS_UPDATES">';
		
		$installed_ups .= '<div id="UP_UPLOAD" class="brz-padding brz-cursor-pointer brz-padding-8 brz-border-top brz-border-super-grey brz-clear">
					            <div onclick="$(\'#EXTENSION\').click();" class="brz-left brz-opacity brz-hover-opacity-off">
									<img src="'.$TEXT['installation'].'/uploads/posts/backgrounds/add-image.png" class="brz-image-margin-right brz-image" width="60"></i>
								</div>
	                            <div onclick="$(\'#EXTENSION\').click();" class="">
		                            <div class="brz-small brz-no-overflow">
			                            <div class="">
											<span class="brz-text-bold brz-text-blue-dark">
												<span class="brz-mind-blue-text">'.$TEXT['_uni-install_update'].'</span>
											</span>
										</div>	
										<span class=""> 
											<span class="brz-text-super-grey brz-small">'.$TEXT['_uni-install_update_for'].'</span>
					                    </span>										
		                            </div>
								</div>
								<form id="EXT_FORM" name="EXT_FORM" action="'.$TEXT['installation'].'/require/requests/update/upload-update.php" onsubmit ="return false;" method="POST" enctype="multipart/form-data" target="EXT_FORM_TARGET">   
                                    <input style="display:none!important;" name="EXTENSION" id="EXTENSION" type="file"/>
                                    <iframe id="EXT_FORM_TARGET" name="EXT_FORM_TARGET" src="" style="display: none"></iframe>
								</form>
								<script>
									$("#EXTENSION").on(\'change\', function(event){	
										$(document).on(\'change\', \':file\', function () {
											if($("#EXTENSION")[0].files.length) {
												uploadUpdate();
												//$("EXTENSION_SELF").val($("#EXTENSION").val());
											}
										}); 
									});					
								</script>
					        </div>';
							
		// If updates exists
		if(!empty($get_updates) && $get_updates->num_rows) {
			
			// Fetch updates
			while($row = $get_updates->fetch_assoc()) {
				$installed[] = $row;		
			}
			
			foreach($installed as $update) {

				// Generate updates in list
				$installed_ups .= '<div class="brz-padding brz-padding-8 brz-border-top brz-border-super-grey brz-clear">
					            <div class="brz-left">
									<div class="brz-left">
                                        <span class="brz-padding"><i class="fa brz-text-body-it fa-undo"></i></span>
	                                </div>
								</div>
								<div class="brz-right brz-small">
									'.$update['u_version'].'
								</div>
	                            <div class="">
		                            <div class="brz-small brz-no-overflow">
			                            <div class="brz-line-o">
											<span class="brz-text-bold brz-text-blue-dark">
												'.$TEXT['_uni-Update'].' '.protectXSS($update['u_version']).'
											</span>
										</div>
				                        <span class=""> 
											'.addStamp($update['u_date']).' <span class="brz-text-super-grey brz-small">| '.($update['u_description']).'</span>
					                    </span>											
		                            </div>
								</div>
					        </div>';

			}
			
		}
			
		// Close titles
		$installed_ups .= '</div></div>';	
	
		// Return updates
		return $installed_ups;
		
	}
	
	function loadExtensions() {                                  
		global $TEXT,$page_settings;
		
        // Select all extensions
		$exts = $this->db->query("SELECT * FROM `extensions` ORDER BY `ext_update`DESC ");
	    
		// Reset
		$installed = $available = $rows = array();
		$installed_exts = $available_exts = '';

		$available_exts = '<div class="brz-new-container brz-white"><div class="brz-opacity brz-white brz-tiny-2 brz-super-grey brz-text-black brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-AVAILABLE_EXTENSIONS'].'</div><div id="INS_EXTS">';

		// If extensions exists
		if(!empty($exts) && $exts->num_rows) {
			
			// Fetch extensions
			while($row = $exts->fetch_assoc()) {
				
				// Categorize
				if($row['ext_status']) {
					$installed[] = $row;
				} else {
					$available[] = $row;
				}    
				
				// For foreach loop
				$rows[] = $row;
			}
			
			// Add titles
			$installed_exts = (empty($installed)) ? '' : '<div class="brz-new-container brz-white"><div class="brz-opacity brz-white brz-tiny-2 brz-super-grey brz-text-black brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-INSTALLED_EXTENSIONS'].'</div><div id="AVA_EXTS">';
			
			foreach($rows as $ext) {

				// Check whether extension is installed
				if($ext['ext_status']) {
					$state = '<span onclick="updateExtension(\''.$ext['ext_name'].'\',0);" class="brz-round brz-padding-tiny2 brz-hover-blue-hd brz-cursor-pointer brz-tag brz-blue brz-tiny-2 brz-text-white brz-text-bold">
								'.$TEXT['_uni-UnInstall'].'
					    	</span>';
				} else {
					$state = '<a onclick="updateExtension(\''.$ext['ext_name'].'\',1);" class="brz-new_btn brz-round brz-cursor-pointer brz-padding-standard brz-text-bold brz-act-it brz-tiny-2 brz-text-grey brz-cursor-pointer">
								'.$TEXT['_uni-Install'].'
					    	</a>';
				}
			
				// Generate language in list
				$add_new = '<div class="brz-padding brz-padding-8 brz-border-top brz-border-super-grey brz-clear">
					            <div class="brz-left">
									<img src="'.$TEXT['installation'].'/extensions/'.$ext['ext_name'].'/icon.png" class="brz-image-margin-right brz-image" width="60" height="60"></i>
								</div>
								<div class="brz-right brz-small">
									'.$state.'
								</div>
	                            <div class="">
		                            <div class="brz-small brz-no-overflow">
			                            <div class="brz-line-o">
											<span class="brz-text-bold brz-text-blue-dark">
												'.protectXSS($ext['ext_name']).' | '.protectXSS($ext['ext_author']).'
											</span>
										</div>
				                        <span class=""> 
											'.addStamp($ext['ext_update']).' <span class="brz-text-super-grey brz-small">| '.protectXSS($ext['ext_description']).'</span>
					                    </span>											
		                            </div>
								</div>
					        </div>';
								
								
				// Categorize
				if($ext['ext_status']) {
					$installed_exts .= $add_new;
				} else {
					$available_exts .= $add_new;
				}

			}

			$installed_exts .= (empty($installed)) ? '' : '</div></div>';
			
		}
		
		// Add new extension wizard
		$available_exts .= '<div id="EXT_UPLOAD" class="brz-padding brz-cursor-pointer brz-padding-8 brz-border-top brz-border-super-grey brz-clear">
					            <div onclick="$(\'#EXTENSION\').click();" class="brz-left brz-opacity brz-hover-opacity-off">
									<img src="'.$TEXT['installation'].'/uploads/posts/backgrounds/add-image.png" class="brz-image-margin-right brz-image" width="60"></i>
								</div>
	                            <div onclick="$(\'#EXTENSION\').click();" class="">
		                            <div class="brz-small brz-no-overflow">
			                            <div class="">
											<span class="brz-text-bold brz-text-blue-dark">
												<span class="brz-mind-blue-text">'.$TEXT['_uni-Upload_extensions'].'</span>
											</span>
										</div>	
										<span class=""> 
											<span class="brz-text-super-grey brz-small">'.$TEXT['_uni-ext-format'].'</span>
					                    </span>										
		                            </div>
								</div>
								<form id="EXT_FORM" name="EXT_FORM" action="'.$TEXT['installation'].'/require/requests/update/upload-extension.php" onsubmit ="return false;" method="POST" enctype="multipart/form-data" target="EXT_FORM_TARGET">   
                                    <input style="display:none!important;" name="EXTENSION" id="EXTENSION" type="file"/>
                                    <iframe id="EXT_FORM_TARGET" name="EXT_FORM_TARGET" src="" style="display: none"></iframe>
								</form>
								<script>
									$("#EXTENSION").on(\'change\', function(event){	
										$(document).on(\'change\', \':file\', function () {
											if($("#EXTENSION")[0].files.length) {
												uploadExtension();
												//$("EXTENSION_SELF").val($("#EXTENSION").val());
											}
										}); 
									});					
								</script>
					        </div>';
			
		// Close titles
		$available_exts .= '</div></div>';	
	
		// Return extensions
		return $installed_exts.$available_exts;
		
	}
	
	function getThemes() {                                   // Fetch available themes
		global $TEXT;
		
		// Open directory
		$directory = opendir('../../../themes/');
		
		// If directory exists
		if($directory) {
			
			// Reset
			$listThemes = array();$theme_error = '';
			
			// Add animation class to page
			$all = '<div class="brz-new-container brz-white"><div class="brz-opacity brz-white brz-tiny-2 brz-super-grey brz-text-black brz-padding-16 brz-padding brz-text-bold">'.$TEXT['_uni-MANAGE_THEMES'].'</div>';
			
			while(FALSE !== ($theme = readdir($directory)))  {
				
				// Check whether theme.php file exists
				if($theme != '.' && $theme != '..'  && $theme != '../..' && file_exists('../../../themes/'.$theme.'/theme.php')) {
					
					// Add theme to list
					$listThemes[] = $theme;
					
					// Import theme information
					include('../../../themes/'.$theme.'/theme.php');
					
					// Import platform info
					include(__DIR__ .'/platform.php');
					
					// Check whether theme supports this versions
					if(!isset($theme_support[$PLATFORM['VERSION']]) || $theme_support[$PLATFORM['VERSION']] !== 1) {
						
						// If theme doesn't supports add message 
						$theme_error = '<div class="brz-padding-8">
											<div class="brz-padding brz-leftbar brz-border-amber brz-round brz-sand">
													'.sprintf($TEXT['_uni-Theme_support_error'],$theme_update_url).'
											</div>
										</div>';
					
					}
					
					// Check whether this already active else add activating URL
					if($TEXT['theme'] == $theme) {
						$state = '<span class="brz-round brz-padding-tiny2 brz-tag brz-blue brz-tiny-2 brz-text-white brz-text-bold" href="'.$TEXT['installation'].'/?admin=apply&theme='.$theme.'">
									'.$TEXT['_uni-ACTIVE'].'
					              </span>';
					} else {
						$state = '<a class="brz-new_btn brz-round brz-padding-standard brz-text-bold brz-act-it brz-tiny-2 brz-text-grey" href="'.$TEXT['installation'].'/?admin=apply&theme='.$theme.'">
									<img class="nav-item-text-inverse-big brz-img-theme" alt="" src="'.$TEXT['DATA-IMG-7'].'"> '.$TEXT['_uni-ACTIVATE'].'
					              </a>';
					}
					
					// Generate theme
					$all .= '<div class="brz-padding brz-border-top brz-border-super-grey brz-clear">
					                <div class="brz-left">
									    <img class="brz-image-margin-right brz-image" src="'.$TEXT['installation'].'/themes/'.$theme.'/theme.png" width="60" height="60">
									</div>
									<div class="brz-right brz-small">
									    '.$state.'
									</div>
	                                <div class="">
		                                <div class="brz-small brz-no-overflow">
			                                <div class="brz-line-o">
												<a href="'.protectXSS($theme_extra_url).'" target="_BLANK" class="brz-text-bold brz-text-blue-dark brz-underline-hover">
													'.protectXSS($theme_name).' | '.protectXSS($theme_developer).'
												</a>
											</div>
				                            <span class="">
											    '.protectXSS($theme_version).' - '.protectXSS($theme_released).'  
											    '.$theme_error.'
					                            <span class="brz-text-super-grey brz-small">'.protectXSS($theme_description).'</span>
				                            </span>	                               
		                                </div>
									</div>
					            </div>';
				}
			}
			
			// Close animation container
			$all .= '</div>';

			// Close themes directory
			closedir($directory);
			
			// Return themes
			return $all;
		}
	}
	
	function getSelected($title,$id,$tex,$end) {             // Get select  
		return '<div class="brz-padding-16 brz-container">			
						<div class="brz-col s4 brz-padding-medium">
							<span class="brz-right brz-text-bold brz-text-grey">'.$title.':</span>
						</div>
						<div class="brz-col s6">
							<span class="brz-left">
								<select id="'.$id.'" class="small brz-right">
						    		'.$tex.'
								</select>
							</span>
						</div>
					</div>										
					'.$end;
	}
	
	function enrollInput($title,$id,$tex,$val,$end,$type="text") {// Get input                   
		return '<div class="brz-padding-16 brz-container">			
						<div class="brz-col s4 brz-padding-medium">
							<span class="brz-right brz-text-bold brz-text-grey">'.$title.':</span>
						</div>
						<div class="brz-col s6">
							<span class="brz-left">
								<input id="'.$id.'" type="'.$type.'" class="nav-item-text-inverse brz-border brz-text-grey brz-small brz-card" placeholder="'.$tex.'" value="'.$val.'" />
							</span>
						</div>
					</div>										
					'.$end;
	}	
	
}

// Add global uncions if don' exists
if(!function_exists('emailVerification')) {
	require_once(__DIR__ . '/functions.php');
}
?>