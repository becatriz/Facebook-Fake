<?php
//--------------------------------------------------------------------------------------//
//                          Breeze Social networking platform                           //
//                                     PHP PUBLIC CLASSES                               //
//--------------------------------------------------------------------------------------//

class access {
	
	// Get template
	function getTemplate($name) {	
		global $TEXT;

		// Get template
		return (file_exists('themes/'.$TEXT['theme'].'/html/public/'.$name.$TEXT['templates_extension'])) ? 'themes/'.$TEXT['theme'].'/html/public/'.$name.$TEXT['templates_extension'] : '../../../themes/'.$TEXT['theme'].'/html/public/'.$name.$TEXT['templates_extension'];
	
	}

	// Generate profile avatars (PRIVACY PROTECTED)
	function getImage($id,$privacy,$photo) {
		
		// Privacy check
		if(substr($photo, 0, 7) == "default") return $photo ;
		
		// Else confirm privacy check and return image
		return ($privacy == 1) ? 'private.png' : $photo;
		
	}
	
	// Generate profile navigation
    function genNavigation($user) {
		global $TEXT;
		
		// Set content for theme
        $TEXT['temp-user_id'] = protectXSS($user['idu']);
        $TEXT['temp-image'] = protectXSS($user['image']);
        $TEXT['temp-cover'] = protectXSS($user['cover']);
        $TEXT['temp-last_name'] = protectXSS($user['last_name']);	
	    $TEXT['temp-username'] = protectXSS($user['username']);
        $TEXT['temp-first_name'] = protectXSS($user['first_name']);
        $TEXT['temp-Name_navigation_14'] = protectXSS(fixName(14,$TEXT['temp-username'],$TEXT['temp-first_name'],$TEXT['temp-last_name']));			
        $TEXT['temp-Name_navigation_30'] = protectXSS(fixName(30,$TEXT['temp-username'],$TEXT['temp-first_name'],$TEXT['temp-last_name']));			
        
		// Generate navigation from template 
		return display('themes/'.$TEXT['theme'].'/html/public/main_header'.$TEXT['templates_extension']);

	}

	// Get recent photos
    function getPhotos($user) { 
	    global $TEXT;
		
		// Reset
		$images = $rows = array();
		
		// Check privacy
		if($user['p_posts']) {
			
			return $TEXT['_uni-Hidden_information'];
		
		} else {
			
			// Select photos
			$photos = $this->db->query(sprintf("SELECT * FROM `user_posts` WHERE `post_type` = '1' AND `post_by_id` = '%s' ORDER BY `user_posts`.`post_id` DESC LIMIT 5",$this->db->real_escape_string($user['idu'])));
		
			// If photos exists
			if(!empty($photos) && $photos->num_rows) {
			
				// Fetch photos
				while($row = $photos->fetch_assoc()) {
			    	$rows[] = $row;
				}
				
				// Parse images
				foreach($rows as $row) {
					
					$get_images = explode(',', $row['post_content']);
					$images[] = $get_images[0];
					
				}
		
		        // Count images and create responsive modal div
				if(count($images) == 1) {
					$modal_data = '<div id="USER_IMG" class="brz-center brz-padding brz-clear">
		    					<img id="post_view_main_image_1" src="'.$TEXT['installation'].'/thumb.php?src='.$images[0].'&fol=c&w=650&h=300" style="margin-top:5px;max-width:100%;max-height:450px;" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
							</div>';	
								
				} elseif(count($images) == 2) {
					$modal_data = '<div id="USER_IMG" class="brz-center brz-clear">
		    					<img id="post_view_main_image_1" src="'.$TEXT['installation'].'/thumb.php?src='.$images[0].'&fol=c&w=300&h=300" style="margin-top:5px;width: 48.73999%;max-width:100%;max-height:450px;" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_2" src="'.$TEXT['installation'].'/thumb.php?src='.$images[1].'&fol=c&w=300&h=300" style="margin-top:5px;width: 48.73999%;max-width:100%;max-height:450px;" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
							</div>';
						
				} elseif(count($images) == 3) {
					$modal_data = '<div id="USER_IMG" class="brz-center brz-clear">
		    					<img id="post_view_main_image_1" src="'.$TEXT['installation'].'/thumb.php?src='.$images[0].'&fol=c&w=300&h=300" style="margin-top:5px;max-width:100%;width:32.2%;max-height:450px;" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_2" src="'.$TEXT['installation'].'/thumb.php?src='.$images[1].'&fol=c&w=300&h=300" style="margin-top:5px;max-width:100%;max-height:450px;width:32.2%;" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_3" src="'.$TEXT['installation'].'/thumb.php?src='.$images[2].'&fol=c&w=300&h=300" style="margin-top:5px;max-width:100%;max-height:450px;width:32.2%;" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
							</div>';
						
				} elseif(count($images) == 4) {
					$modal_data = '<div id="USER_IMG" class="brz-center brz-clear">
		    					<img id="post_view_main_image_1" src="'.$TEXT['installation'].'/thumb.php?src='.$images[0].'&fol=c&w=300&h=200" style="margin-top:5px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_2" src="'.$TEXT['installation'].'/thumb.php?src='.$images[1].'&fol=c&w=300&h=200" style="margin-top:5px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_3" src="'.$TEXT['installation'].'/thumb.php?src='.$images[2].'&fol=c&w=300&h=200" style="margin-top:2px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_4" src="'.$TEXT['installation'].'/thumb.php?src='.$images[3].'&fol=c&w=300&h=200" style="margin-top:2px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
							</div>';
						
				} elseif(count($images) == 5) {
					$modal_data = '<div id="USER_IMG" class="brz-clear brz-center">
		    					<img id="post_view_main_image_1" src="'.$TEXT['installation'].'/thumb.php?src='.$images[0].'&fol=c&w=300&h=200" style="margin-top:5px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_2" src="'.$TEXT['installation'].'/thumb.php?src='.$images[1].'&fol=c&w=300&h=200" style="margin-top:5px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_3" src="'.$TEXT['installation'].'/thumb.php?src='.$images[2].'&fol=c&w=300&h=300" style="margin-top:2px;max-width:100%;max-height:450px;display:inline-block;width: 32.23999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_4" src="'.$TEXT['installation'].'/thumb.php?src='.$images[3].'&fol=c&w=300&h=300" style="margin-top:2px;max-width:100%;max-height:450px;display:inline-block;width: 32.23999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_5" src="'.$TEXT['installation'].'/thumb.php?src='.$images[4].'&fol=c&w=300&h=300" style="margin-top:2px;max-width:100%;max-height:450px;display:inline-block;width: 32.23999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
						</div>';
						
				} elseif(count($images) == 6) {
					$modal_data = '<div id="USER_IMG" class="brz-clear brz-center">
		    					<img id="post_view_main_image_1" src="'.$TEXT['installation'].'/thumb.php?src='.$images[0].'&fol=c&w=300&h=200" style="margin-top:5px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_2" src="'.$TEXT['installation'].'/thumb.php?src='.$images[1].'&fol=c&w=300&h=200" style="margin-top:5px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_3" src="'.$TEXT['installation'].'/thumb.php?src='.$images[2].'&fol=c&w=300&h=300" style="margin-top:2px;max-width:100%;max-height:450px;display:inline-block;width: 32.23999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_4" src="'.$TEXT['installation'].'/thumb.php?src='.$images[3].'&fol=c&w=300&h=300" style="margin-top:2px;max-width:100%;max-height:450px;display:inline-block;width: 32.23999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_5" src="'.$TEXT['installation'].'/thumb.php?src='.$images[4].'&fol=c&w=300&h=300" style="margin-top:2px;max-width:100%;max-height:450px;display:inline-block;width: 32.23999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
						</div>';
						
				}

				// Add heading and inclose modal
				return '<div id="profile_about_c11" style="overflow:hidden !important;border: 1px #e5e6e9 solid;" class="brz-new-container brz-dettachable brz-detach-to-threequarter brz-detacht-to -brz-padding-8">
				        	
							<div class="brz-border-bottom brz-text-grey brz-border-super-grey brz-text-bold brz-medium brz-padding">'.$TEXT['_uni-REC_PHOTOS'].'</div>
						
							<div style="padding:0px 4px;" class="brz-clear brz-white">'.$modal_data.'
								<div onclick="loadModal(1);loadLogin(1,'.$user['idu'].');" class="brz-center brz-small brz-margin brz-round brz-border brz-cursor-pointer brz-padding brz-body-it brz-text-black brz-opacity brz-hover-opacity-off">'.$TEXT['_uni-Sw_mr_phts'].'</div>
							</div>

						</div>';
					
			}
		}
	}
	
	// Get similar users
    function getSimilarUsers($user) { 
		global $TEXT,$profile,$page_settings;
		
		// Select users based on first name
		$users = $this->db->query(sprintf("SELECT * FROM `users` WHERE (`users`.`username` LIKE '%s' OR concat_ws(' ', `users`.`first_name`, `users`.`last_name`) LIKE '%s') AND `users`.`state` != 4  AND `users`.`idu` != '%s' ORDER BY `users`.`idu` DESC LIMIT %s", '%'.$this->db->real_escape_string($user['username']).'%', '%'.$this->db->real_escape_string($user['first_name']).'%',$this->db->real_escape_string($user['idu']),$page_settings['public_profile_similar']));

		// If empty try last name
		if($users->num_rows == 0) {
			$users = $this->db->query(sprintf("SELECT * FROM `users` WHERE (`users`.`username` LIKE '%s' OR concat_ws(' ', `users`.`first_name`, `users`.`last_name`) LIKE '%s') AND `users`.`state` != 4  AND `users`.`idu` != '%s' ORDER BY `users`.`idu` DESC LIMIT %s", '%'.$this->db->real_escape_string($user['username']).'%', '%'.$this->db->real_escape_string($user['last_name']).'%',$this->db->real_escape_string($user['idu']),$page_settings['public_profile_similar']));
		}
		
		// If empty select random
		if($users->num_rows == 0) {
			$users = $this->db->query(sprintf("SELECT * FROM `users` WHERE (`users`.`state` != 4  AND `users`.`idu` != '%s') ORDER BY `users`.`idu` DESC LIMIT %s",$this->db->real_escape_string($user['idu']),$page_settings['public_profile_similar']));
		}
		
		// Fetch users
		if(!empty($users)) {
			
			while($row = $users->fetch_assoc()) {
				$results[] = $row;
		    }
			
			// Reset
			$people = '';
			
			foreach($results as $row) {

				// Add user to list
				$people .= '<div class="brz-clear brz-padding">
                                <div class="brz-left">
                                    <a href="'.$TEXT['installation'].'/'.$row['username'].'">
									    <img src="'.$TEXT['installation'].'/thumb.php?src='.$this->getImage($row['idu'],$row['p_image'],$row['image']).'&fol=a&w=45&h=45" class="brz-left brz-border brz-border-super-grey brz-image-margin-right" width="45" height="45">
									</a>
	                            </div>
					            <div class="">
		                            <div class="brz-large-min brz-no-overflow">
			                            <div class="brz-line-o">
											<a href="'.$TEXT['installation'].'/'.$row['username'].'" class="brz-medium brz-text-black brz-cursor-pointer brz-underline-hover">'.fixName(25,$row['username'],$row['first_name'],$row['last_name']).'</a> 
							            </div>
		                            </div>
		                        </div>
	                        </div>';
					
			}
			
			// Add heading and inclose modal
			return '<div id="profile_about_similiar" style="overflow:hidden !important;border: 1px #e5e6e9 solid;" class="brz-new-container brz-padding-8">
							<div class="brz-border-bottom brz-text-grey brz-border-super-grey brz-text-bold brz-medium brz-padding">'.$TEXT['_uni-Similar_users'].'</div>				
							<div class="brz-clear brz-padding-top brz-white">'.$people.'</div>
						</div>';
			
		}
	}
	
	// List followers/followings
    function getUsers($type,$user) { 
	    global $TEXT,$profile,$page_settings;
		// TYPE 1 : Followers
		// TYPE 0 : Followings
	
		$people = implode(',', ($type == 0) ? $profile->listFollowings($user['idu']) : $profile->listFollowers($user['idu']));
	    
		// Select users
		$result = $this->db->query(sprintf("SELECT * FROM `users` WHERE `users`.`idu` IN (%s) ORDER BY `users`.`idu` DESC LIMIT %s", $people, $page_settings['public_profile_followers']));

	    // Reset
		$rows = array(); 
	    
		// If users exists
		if(!empty($result) && $result->num_rows) {
			
			// Fetch users
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			// Add Header if available
			$people = '';

			// Generate user from each row
		    foreach($rows as $row) {
				$people .= '<a href="'.$TEXT['installation'].'/'.$row['username'].'">
				                <img src="'.$TEXT['installation'].'/thumb.php?src='.$this->getImage($row['idu'],$row['p_image'],$row['image']).'&fol=a&w=35&h=35" class="brz-circle">
				            </a>';				
			}
			
			return $people;
			
		} else {
			return $TEXT['_uni-No_det_show'];
		}
	}
	
    function getFavourites($row) { 
	    global $TEXT;
		
		// Set name
		$TEXT['temp-Name'] = fixName(25,$row['username'],$row['first_name'],$row['last_name']);

		// Get list of followers
		$TEXT['temp-followers'] = ($row['p_followers']) ? $TEXT['_uni-Hidden_information'] : $this->getUsers(1,$row);
		
		// Get list of followings
		$TEXT['temp-followings'] = ($row['p_followings']) ? $TEXT['_uni-Hidden_information'] : $this->getUsers(0,$row);

		// Display favourites
		return display($this->getTemplate('favourites_profile'));
	
	}
	
	// Get user about
    function getAbout($row) { 
	    global $TEXT;
		
		// User name
		$TEXT['temp-Name'] = fixName(32,$row['username'],$row['first_name'],$row['last_name']);
		
		// User bio
		$TEXT['temp-Bio'] = (empty($row['bio'])) ? $TEXT['_uni-No_addt_det_show'] : $row['bio'];
		
		// PRofession
		$TEXT['temp-Work'] = ($row['p_profession'] || empty($row['profession'])) ? $TEXT['_uni-Hidden_information'] : $row['profession'];
		
		// Education
		$TEXT['temp-Education'] = ($row['p_study'] || empty($row['study'])) ? $TEXT['_uni-Hidden_information'] : $row['study'];
		
		// Hometown
		$TEXT['temp-Hometown'] = ($row['p_hometown'] || empty($row['hometown'])) ? $TEXT['_uni-Hidden_information'] : $row['hometown'];
		
        // Current city(lIVING)
		$TEXT['temp-Living'] = ($row['p_location'] || empty($row['living'])) ? $TEXT['_uni-Hidden_information'] : $row['living'];
	
		// Display about section
		return display($this->getTemplate('about_profile'));
	
	}
	
	// Get profile top
    function profileTop($row) {
        global $TEXT,$profile;
		
		// Get user profile picture
		$profile_picture = $this->getImage($row['idu'],$row['p_image'],$row['image']);
	
		// Get user cover photo
		$cover_photo = $this->getImage($row['idu'],$row['p_cover'],$row['cover']);	

		$followers_count1 = (!$row['followers']) ? '' : '<img onclick="loadModal(1);loadLogin(1,'.$row['idu'].');" class="brz-img-followed" alt="" src="'.$TEXT['DATA-IMG-1'].'"> <span onclick="profileLoadFollowers('.$row['idu'].',0,0);" class="nav-item-text-inverse brz-underline-hover brz-cursor-pointer brz-text-bold brz-small brz-text-blue-dark">'.readAble($row['followers']).'</span>';
		$followers_count2 = (!$row['followers']) ? '': '<img onclick="loadModal(1);loadLogin(1,'.$row['idu'].');" class="brz-img-followed" alt="" src="'.$TEXT['DATA-IMG-1'].'"> <span onclick="profileLoadFollowers('.$row['idu'].',0,0);" class="nav-item-text-inverse brz-underline-hover brz-cursor-pointer brz-small">'.readAble($row['followers']).' <span class="brz-hide-medium brz-hide-small">'.$TEXT['_uni-Followers'].'</span></span>';
		$photos_count1 = (!$row['photos']) ? '': '<img onclick="loadModal(1);loadLogin(1,'.$row['idu'].');" class="brz-img-photos" alt="" src="'.$TEXT['DATA-IMG-1'].'"> <span onclick="profileLoadGallery('.$row['idu'].');" class="nav-item-text-inverse brz-underline-hover brz-cursor-pointer brz-text-bold brz-small brz-text-blue-dark">'.readAble($row['photos']).'</span>';
		$photos_count2 = (!$row['photos']) ? '': '<img onclick="loadModal(1);loadLogin(1,'.$row['idu'].');" class="brz-img-photos" alt="" src="'.$TEXT['DATA-IMG-1'].'"> <span onclick="profileLoadGallery('.$row['idu'].');" class="nav-item-text-inverse brz-underline-hover brz-cursor-pointer brz-small">'.readAble($row['photos']).' <span class="brz-hide-medium brz-hide-small">'.$TEXT['_uni-Photos'].'</span></span>';
		$posts_count1 = (!$row['posts']) ? '': '<img onclick="loadModal(1);loadLogin(1,'.$row['idu'].');" class="brz-img-posts" alt="" src="'.$TEXT['DATA-IMG-1'].'"> <span onclick="profileLoadTimeline('.$row['idu'].');" class="nav-item-text-inverse brz-underline-hover brz-cursor-pointer brz-text-bold brz-small brz-text-blue-dark">'.readAble($row['posts']).'</span>';
		$posts_count2 = (!$row['posts']) ? '': '<img onclick="loadModal(1);loadLogin(1,'.$row['idu'].');" class="brz-img-posts" alt="" src="'.$TEXT['DATA-IMG-1'].'"> <span onclick="profileLoadTimeline('.$row['idu'].');" class="nav-item-text-inverse brz-underline-hover brz-cursor-pointer brz-small">'.readAble($row['posts']).' <span class="brz-hide-medium brz-hide-small">'.$TEXT['_uni-Posts'].'</span></span>';
			
		// Build profile page
        $content = '<div class="brz-display-container brz-new-container-3 brz-clear">
	                        <div class="brz-img-pre-loader-cover">
		                        <img id="profile_view_cover_'.$row['idu'].'" style="width:100%;min-height:130px!important;" src="'.$TEXT['installation'].'/thumb.php?src='.$cover_photo.'&fol=b&w=1093&h=381&q=100" class="brz-image brz-display-container brz-round brz-animate-opacity brz-align-center">
	                             
								<div class="brz-display-middle brz-hide-small brz-center brz-margin-top-n2 brz-small">
								    <div class="brz-round brz-black-opacity brz-public-container brz-margin brz-padding-8 brz-padding">
									    <div class="brz-medium brz-text-bold">'.sprintf($TEXT['_uni-s_is_on_s'] ,fixName(25,$row['username'],$row['first_name'],$row['last_name']),$TEXT['web_name']).'</div>
									    <div class="brz-small brz-text-bold">'.sprintf($TEXT['_uni-To_cnn_s_tdy'],$TEXT['web_name']).'</div>								
										<div class="brz-padding-8 brz-hide-medium">
											<div onclick="loadModal(1);loadLogin(1,'.$row['idu'].');" style="width:95px;" class="brz-tag brz-round brz-blue brz-hover-blue-hd brz-cursor-pointer brz-text-white brz-text-bold brz-small brz-padding-8">'.$TEXT['_uni-LOG_IN'].'</div>
										</div>									
										<div class="brz-small brz-text-bold brz-hide-medium">'.$TEXT['_uni-or'].'</div>								
										<div class="brz-padding-8 brz-hide-medium">
											<div onclick="loadModal(1);loadLogin(1,'.$row['idu'].');" style="width:95px;" class="brz-tag brz-round brz-green brz-hover-green-dark brz-cursor-pointer brz-text-white brz-text-bold brz-small brz-padding-8">'.$TEXT['_uni-SIGN_UP'].'</div>
										</div>					
									</div>
								</div>			
							</div>
							<div class="brz-display-bottomleft brz-profile-picture brz-margin brz-wide brz-text-light-grey brz-center">
        						<img style="min-width:70px;min-height:70px;" id="profile_view_main_'.$row['idu'].'" src="'.$TEXT['installation'].'/thumb.php?src='.$profile_picture.'&fol=a&w=245&h=245&q=100" class="brz-left brz-display-container brz-border-bold brz-border-white brz-card-2 brz-profile-picture brz-round">
							</div>
							<span class="brz-responsive-xbig-styled brz-hide-medium brz-right brz-hide-large brz-text-white brz-text-bold" style="text-shadow: 0 0 2px rgba(0,0,0,.8);position:relative;right:20px;bottom:30px;font-family: Helvetica, Arial, sans-serif;" >
								&nbsp;'.$profile->verifiedBatch($row['verified']).' '.fixName(25,$row['username'],$row['first_name'],$row['last_name']).'
							</span>
		                    <div class="brz-display-bottom brz-border-bottom brz-border-super-grey brz-super-grey brz-clear" style="width:100%;">
		                        <span class="brz-hide-medium brz-hide-large brz-display-bottomright brz-margin-cat">
									'.$followers_count1.'
									'.$photos_count1.'
									'.$posts_count1.'
								</span>
								<ul class="brz-navbar brz-large brz-super-grey brz-right">
                                    <li onclick="loadModal(1);loadLogin(1,'.$row['idu'].');" class="brz-hide-small brz-padding-8 brz-hvr-active brz-hvr-active-1 brz-text-grey"><a id="profile_view_tab_5" class="brz-responsive-new-styled brz-element-profile-tab" href="javascript:returnFalse();">'.$TEXT['_uni-About'].'</a></li>     
									<li onclick="loadModal(1);loadLogin(1,'.$row['idu'].');" class="brz-hide-small brz-padding-8 brz-hvr-active brz-text-grey"><a id="profile_view_tab_1" class="brz-element-profile-tab brz-responsive-new-styled" href="javascript:returnFalse();">'.$TEXT['_uni-Timeline'].'</a></li>
                                    <li onclick="loadModal(1);loadLogin(1,'.$row['idu'].');" class="brz-hide-small brz-padding-8 brz-hvr-active brz-text-grey"><a id="profile_view_tab_2" class="brz-responsive-new-styled brz-element-profile-tab" href="javascript:returnFalse();">'.$TEXT['_uni-Photos'].'</a></li>
                                    <li onclick="loadModal(1);loadLogin(1,'.$row['idu'].');" class="brz-hide-small brz-hvr-active brz-padding-8 brz-text-grey"><a id="profile_view_tab_3" class="brz-responsive-new-styled brz-element-profile-tab" href="javascript:returnFalse();">'.$TEXT['_uni-Followers'].'</a></li>
                                    <li onclick="loadModal(1);loadLogin(1,'.$row['idu'].');" class="brz-hide-small brz-hvr-active brz-padding-8 brz-text-grey"><a id="profile_view_tab_4" class="brz-responsive-new-styled brz-element-profile-tab" href="javascript:returnFalse();">'.$TEXT['_uni-Followings'].'</a></li>
                                </ul>
		                    </div>    
                        </div>';
			
        // Return profile top			
		return $content;
	
	}		
}

// Add global uncions if don' exists
if(!function_exists('emailVerification')) {
	require_once(__DIR__ . '/functions.php');
}
?>