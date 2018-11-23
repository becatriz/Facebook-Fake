<?php 
// Add settings wizards
function getTab($user,$t,$s = NULL) {
    global $TEXT;
	
	// Return if tab not set
    if(!$t) {return false;}
	
	// Add some identifiers
	if(isset($s) && $s !== 0) {
		
		// Settings saved class
		$class = ($s == 2) ? 'brz-active-nochn' : 'brz-set-item-active';
	    
		$save_type = ($s == 2) ? '<img class="nav-item-text-inverse-big brz-img-mess-no" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKAQMAAAC3/F3+AAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAAAtJREFUeNpjYMAHAAAeAAFlWZ5xAAAAAElFTkSuQmCC"></img> '.$TEXT['_uni-No_changes'] : '<img class="nav-item-text-inverse-big brz-img-mess-saved" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAKAQMAAAC64i25AAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAAAtJREFUeNpjYMAHAAAeAAFlWZ5xAAAAAElFTkSuQmCC"></img> '.$TEXT['_uni-Changes_saved'];
		
		// Settings saved message
		$pencil = '<div id="settings-tab-space-'.$t.'" class="brz-right brz-edit-click brz-clear">
			            '.$save_type.'
					</div>';
			
	} else {
		
		// New
		$class = '';
		
		// Editable message
		$pencil = '<div id="settings-tab-space-'.$t.'" style="min-width:36px;" class="brz-right brz-cursor-pointer brz-edit-click brz-text-pink brz-clear">
			            <i id="settings-tab-space-pencil-1" class="fa fa-pencil brz-text-pink-light" style="display:none;"></i>&nbsp; <span class="brz-right brz-underline-hover">'.$TEXT['_uni-Edit'].'</span>
					</div>';
						
	} 

	// Genreral settings || NAME TAB
	if($t == 1) {
	    return addSampleTab($t,$TEXT['_uni-Name'],fixName(20,$user['username'],$user['first_name'],$user['last_name']),$class,$pencil,'brz-border-black brz-border-top') ;	
	
	// Genreral settings || USERNAME TAB
	} elseif($t == 2) {
	    return addSampleTab($t,$TEXT['_uni-Username'],fixTEXT(20,$user['username']),$class,$pencil,'brz-border-top brz-border-bottom') ;	
	
	// Genreral settings || EMAIL TAB
	} elseif($t == 3) {
	    return addSampleTab($t,$TEXT['_uni-Email'],fixTEXT(20,$user['email']),$class,$pencil,'brz-border-bottom') ;	
	
	// Genreral settings || PASSWORD TAB
	} elseif($t == 4) {
	    $changed = (empty($user['p_chn'])) ? $TEXT['_uni-Never_chan'] : $TEXT['_uni-Changed'].' <span class="timeago" title="'.$user['p_chn'].'" >'.fuzzyStamp($user['p_chn'],1).'<span>';
	    return addSampleTab($t,$TEXT['_uni-Password'],$changed,$class,$pencil,'brz-border-black brz-border-bottom') ;	
	
	// Profile information || PROFFESION TAB
	} elseif($t == 5) {
	    $set = (empty($user['profession'])) ? $TEXT['_uni-Not_set'] : $user['profession'];
	    return addSampleTab($t,$TEXT['_uni-Profession'],$set,$class,$pencil,'brz-border-black brz-border-top') ;	
	
	// Profile information || EDUCATION TAB
	} elseif($t == 6) {
	    $set = (empty($user['study'])) ? $TEXT['_uni-Not_set'] : $user['study'];
	    return addSampleTab($t,$TEXT['_uni-EDU'],$set,$class,$pencil,'brz-border-bottom brz-border-top') ;	
	
	// Profile information || HOMETOWN TAB
	} elseif($t == 7) {
	    $set = (empty($user['from'])) ? $TEXT['_uni-Not_set'] : $user['from'];
	    return addSampleTab($t,$TEXT['_uni-Hometown'],$set,$class,$pencil,'brz-border-bottom') ;	
	
	// Profile information || LIVING CITY TAB
	} elseif($t == 8) {
	    $set = (empty($user['living'])) ? $TEXT['_uni-Not_set'] : $user['living'];
	    return addSampleTab($t,$TEXT['_uni-LIVING'],$set,$class,$pencil,'brz-border-bottom') ;	
	
	// Profile information || INTERESTED IN TAB
	} elseif($t == 9) {
		if(empty($user['interest'])) {
		    $set = $TEXT['_uni-Not_set'];
		} else {
		    $set = ($user['interest'] > 1) ? $TEXT['_uni-Female']: $TEXT['_uni-Male'];
		}
	    return addSampleTab($t,$TEXT['_uni-Interested_in'],$set,$class,$pencil,'brz-border-bottom') ;	
	
	// Profile information || RELATIONSHIP TAB
	} elseif($t == 10) {
		if(empty($user['relationship'])) {
		    $set = $TEXT['_uni-Not_set'];
		} else {
		    $set = ($user['relationship'] > 1) ? $TEXT['_uni-In_a_rel']: $TEXT['_uni-Single'];
		}
	    return addSampleTab($t,$TEXT['_uni-Relationship'],$set,$class,$pencil,'brz-border-bottom') ;	
	
    // Profile information || GENDER TAB
	} elseif($t == 11) {
		if(empty($user['gender'])) {
		    $set = $TEXT['_uni-Not_set'];
		} else {
		    $set = ($user['gender'] > 1) ? $TEXT['_uni-Female']: $TEXT['_uni-Male'];
		}
	    return addSampleTab($t,$TEXT['_uni-Gender'],$set,$class,$pencil,'brz-border-bottom') ;	
	
	// Profile information || WEBSITE TAB
	} elseif($t == 12) {
	    $set = (empty($user['website'])) ? $TEXT['_uni-Not_set'] : $user['website'];
	    return addSampleTab($t,$TEXT['_uni-Website'],$set,$class,$pencil,'brz-border-bottom') ;	
	
	// Profile information || BIRTH TAB
	} elseif($t == 13) {
	    
		// Verify date
 		$set = (empty($user['b_day']) ||  !getBirthday($user['b_day'])) ? $TEXT['_uni-Not_set'] : getBirthday($user['b_day']);
	    return addSampleTab($t,$TEXT['_uni-Bday'],$set,$class,$pencil,'brz-border-bottom') ;	
		
	// Profile information || BIO TAB
	} elseif($t == 14) {
	    $set = (empty($user['bio'])) ? $TEXT['_uni-Not_set'] : fixText(40,$user['bio']);
	    return addSampleTab($t,$TEXT['_uni-Bio'],$set,$class,$pencil,'brz-border-bottom brz-border-black') ;	
	
	// Privacy setings || POSTS TAB
	} elseif($t == 15) {
	    return addSampleTab($t,$TEXT['_uni-TTL-Posts_privacy_sml'],$TEXT['_uni-TTL-Posts_privacy'],$class,$pencil,'brz-border-top brz-border-black') ;	
	
	// Privacy setings || PROFILE TAB
	} elseif($t == 16) {
	    return addSampleTab($t,$TEXT['_uni-TTL-Profile_privacy_sml'],$TEXT['_uni-TTL-Profile_privacy'],$class,$pencil,'brz-border-top brz-border-bottom') ;	
	
	// Privacy setings || CONTACT TAB
	} elseif($t == 17) {
	    return addSampleTab($t,$TEXT['_uni-TTL-Profile-contact-sml'],$TEXT['_uni-TTL-Profile-contact'],$class,$pencil,'brz-border-bottom') ;	
	
	// Privacy setings || INFO TAB
	} elseif($t == 18) {
	    return addSampleTab($t,$TEXT['_uni-TTL-Profile-info-sml'],$TEXT['_uni-TTL-Profile-info'],$class,$pencil,'brz-border-bottom') ;	

    // Privacy setings || SECURITY TAB
	} elseif($t == 19) {
	    return addSampleTab($t,$TEXT['_uni-TTL-Profile-security-sml'],$TEXT['_uni-TTL-Profile-security'],$class,$pencil,'brz-border-bottom') ;	
	
	// Privacy setings || ON BREEZE
	} elseif($t == 20) {
	    return addSampleTab($t,'<img class="nav-item-text-inverse" width="18" height="18" src="'.$TEXT['installation'].'/themes/'.$TEXT['theme'].'/img/icons/not-brz.png"></img> '.sprintf($TEXT['_uni-TTL-not-breeze-sml'],$TEXT['web_name']),sprintf($TEXT['_uni-TTL-not-breeze-desc'],$TEXT['web_name']),$class,$pencil,'brz-border-black brz-border-top') ;	
	
    // Privacy setings || ON EMAIL
	} elseif($t == 21) {
	    return addSampleTab($t,'<img class="nav-item-text-inverse brz-img-not-email" src="'.$TEXT['DATA-IMG-5'].'"></img> '.$TEXT['_uni-TTL-not-email-sml'],$TEXT['_uni-TTL-not-email-desc'],$class,$pencil,'brz-border-bottom brz-border-top') ;	
	
    
	}
}

// Add sample tab
function addSampleTab($t,$name,$val,$class,$pencil,$classes = NULL) {
	return '<div id="settings-tab-'.$t.'" onclick="openTab('.$t.');" class="'.$class.' brz-clear '.$classes.' brz-noselect brz-tiny-4 brz-cursor-pointer brz-show-edit brz-container-widel">
	                <div class="brz-hover-xxlight-grey brz-no-overflow brz-full">
		                <div class="brz-set-item-l brz-left brz-text-bold">'.$name.'</div>
		                <div class="brz-no-overflow brz-set-item-c brz-clear">
                            '.$pencil.'
			                <span id="settings-tab-val-'.$t.'">'.$val.'</span>
			            </div>		
		            </div>
	            </div>';
}
?>