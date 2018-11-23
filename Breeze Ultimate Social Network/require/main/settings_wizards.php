<?php 
// Add settings wizards
function getWizard($user,$t,$s = NULL) {
    global $TEXT;
	
	// Return if tab not set
    if(!$t) {return false;}

	// Genreral settings || NAME WIZARD
	if($t == 1) {
	    
		// Add 2 fields (First name and last name)
	    $inputs = addSampleInput(1,$TEXT['_uni-First_name'],$user['first_name'],'');
	    $inputs .= addSampleInput(2,$TEXT['_uni-Last_name'],$user['last_name'],'');
		
		// Add wizard
		return addSampleWizard($t,$TEXT['_uni-TTL-Name'],$inputs);

    // Genreral settings || USERNAME WIZARD
	} elseif(in_array($t,array(2,3,5,6,7,8,12))) {
		
		// Wizard description
		$wizard_info = array(	
		2 => "_uni-TTL-Username",	
		3 => "_uni-TTL-Email",	
		5 => "_uni-TTL-Profession",	
		6 => "_uni-TTL-Education",	
		7 => "_uni-TTL-Hometown",	
		8 => "_uni-TTL-Living",	
		12 => "_uni-TTL-Website",	
		);
	
		// Wizard input tittle
		$input_tittle = array(	
		2 => "_uni-Username",	
		3 => "_uni-Email",	
		5 => "_uni-Profession",	
		6 => "_uni-EDU",
		7 => "_uni-Hometown",	
		8 => "_uni-Living",	
		12 => "_uni-Website",	
		);
	
		// Wizard input val
		$input_val = array(	
		2 => $user['username'],	
		3 => $user['email'],	
		5 => $user['profession'],	
		6 => $user['study'],	
		7 => $user['from'],	
		8 => $user['living'],	
		12 => $user['website'],	
		);
		
		// Wizard input attribute
		$input_attr = array(	
		2 => '',	
		3 => '',	
		5 => '',	
		6 => '',	
		7 => '',	
		8 => '',	
		12 => '',	
		);
	
	    // Add wizard with one field
		return addSampleWizard($t,$TEXT[$wizard_info[$t]],addSampleInput(1,$TEXT[$input_tittle[$t]],$input_val[$t],$input_attr[$t]));
		
	// Genreral settings || PASSWORD WIZARD 
	} elseif($t == 4) {
	
	    // Add 2 fields (Old,new and repeat)
	    $inputs = addSampleInput(1,$TEXT['_uni-Current'],'','type="password"');
	    $inputs .= addSampleInput(2,$TEXT['_uni-New'],'','type="password"');
	    $inputs .= addSampleInput(3,$TEXT['_uni-Repeat'],'','type="password"');
		
		// Add wizard
		return addSampleWizard($t,$TEXT['_uni-TTL-Password'],$inputs);
		
	// Profile information || Interests WIZARD
	} elseif(in_array($t,array(9,10,11,13))) {
	
    	// Wizard description
		$wizard_info = array(	
		9 => "_uni-TTL-Interest",	
		10 => "_uni-TTL-Relation",	
		11 => "_uni-TTL-Gender",	
		13 => "_uni-TTL-Birthday",
	
		);
	
		// Wizard input tittle
		$input_tittle = array(	
		9 => "_uni-Interested_in",	
		10 => "_uni-Relationship",	
		11 => "_uni-Gender",	
	
		);
		
		if($t == 9) {
		    return addSampleWizard($t,sprintf($TEXT[$wizard_info[$t]],'loadSettings(3);'),addSampleInputSelect(1,$TEXT[$input_tittle[$t]],$user['interest'],array(0,$TEXT['_uni-Not_set']),array(1,$TEXT['_uni-Male']),array(2,$TEXT['_uni-Female']))) ;
		} elseif($t == 10) {
			return addSampleWizard($t,sprintf($TEXT[$wizard_info[$t]],'loadSettings(3);'),addSampleInputSelect(1,$TEXT[$input_tittle[$t]],$user['relationship'],array(0,$TEXT['_uni-Not_set']),array(1,$TEXT['_uni-Single']),array(2,$TEXT['_uni-In_a_rel']))) ;
		} elseif($t == 11) {
			return addSampleWizard($t,sprintf($TEXT[$wizard_info[$t]],'loadSettings(3);'),addSampleInputSelect(1,$TEXT[$input_tittle[$t]],$user['gender'],array(0,$TEXT['_uni-Not_set']),array(1,$TEXT['_uni-Male']),array(2,$TEXT['_uni-Female']))) ;
		} else {
		    
			
			$seprate = explode('-', $user['b_day']);

			if(empty($seprate[1]) || empty($seprate[0]) || empty($seprate[2])) {
			    $seprate[0] = '';
			    $seprate[1] = '';
			    $seprate[2] = '';
			}
			
			// Add 3 fields (First name and last name)
	        $inputs = addSampleInput(1,$TEXT['_uni-Day'],$seprate[0],'');
	        $inputs .= addSampleInput(2,$TEXT['_uni-Month'],$seprate[1],'');
	        $inputs .= addSampleInput(3,$TEXT['_uni-Year'],$seprate[2],'');
			
			
			return addSampleWizard($t,sprintf($TEXT[$wizard_info[$t]],'loadSettings(3);'),$inputs) ;
		
		}
	
	// Profile information || BIO WIZARD 
	} elseif($t == 14) {
	    
		// custom input for bio
		$input = '<div class="brz-col s3 brz-padding-medium">
					<span class="brz-right brz-text-bold brz-text-grey">'.$TEXT['_uni-Bio'].'</span>
				</div>
				<div class="brz-col s6 brz-padding-medium">
					<span class="brz-left">
						<textarea style="max-width:200px!important;width:200px!important;height:150px;" id="settings-input-1" class="nav-item-text-inverse brz-border brz-text-grey brz-small brz-card">'.$user['bio'].'</textarea>
					</span>
				</div>';
				
		return addSampleWizard($t,$TEXT['_uni-TTL-Bio'],$input) ;	

		
	// Privacy settings || POSTS WIZARD 
	} elseif($t == 15) {
	    
		// Add fields
		$inputs = addSampleselectNe(1,$user['p_posts'],$TEXT['_uni-TTL-Posts-sml'],$TEXT['_uni-TTL-Posts-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-posts');
		
		$inputs .= addSampleselectNe(2,$user['p_mention'],$TEXT['_uni-TTL-Mentions-sml'],$TEXT['_uni-TTL-Mentions-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-mentions');

		return addSampleWizard($t,'',$inputs,1) ;	
	
	// Privacy settings || PROFILE WIZARD 
	} elseif($t == 16) {
	    
		// Add fields
		$inputs = addSampleselectNe(1,$user['p_image'],$TEXT['_uni-TTL-image-sml'],$TEXT['_uni-TTL-image-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-image','');
		$inputs .= addSampleselectNe(2,$user['p_cover'],$TEXT['_uni-TTL-cover-sml'],$TEXT['_uni-TTL-cover-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-cover');
		$inputs .= addSampleselectNe(3,$user['p_followers'],$TEXT['_uni-TTL-followers-sml'],$TEXT['_uni-TTL-followers-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-followers');
		$inputs .= addSampleselectNe(4,$user['p_followings'],$TEXT['_uni-TTL-followings-sml'],$TEXT['_uni-TTL-followings-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-followings');

		return addSampleWizard($t,'',$inputs,1) ;	
	
	// Privacy settings || CONTACT WIZARD 
	} elseif($t == 17) {
	    
		// Add fields
		$inputs = addSampleselectNe(1,$user['p_private'],$TEXT['_uni-TTL-profile-sml'],$TEXT['_uni-TTL-profile-desc'],$TEXT['_uni-Requires_approval'],$TEXT['_uni-Public'],'pri-profile','');
		$inputs .= addSampleselectNe(2,$user['p_web'],$TEXT['_uni-TTL-website-sml'],$TEXT['_uni-TTL-website-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-website');
		
		return addSampleWizard($t,'',$inputs,1) ;	
	
	// Privacy settings || INFO WIZARD 
	} elseif($t == 18) {
	    
		// Add fields
		$inputs = addSampleselectNe(1,$user['p_profession'],$TEXT['_uni-TTL-profession-sml'],$TEXT['_uni-TTL-profession-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-profession','');
		$inputs .= addSampleselectNe(2,$user['p_study'],$TEXT['_uni-TTL-study-sml'],$TEXT['_uni-TTL-study-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-study');
		$inputs .= addSampleselectNe(3,$user['p_hometown'],$TEXT['_uni-TTL-hometown-sml'],$TEXT['_uni-TTL-hometown-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-hometown');
		$inputs .= addSampleselectNe(4,$user['p_location'],$TEXT['_uni-TTL-living-sml'],$TEXT['_uni-TTL-living-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-living');
		$inputs .= addSampleselectNe(5,$user['p_interest'],$TEXT['_uni-TTL-interest-sml'],$TEXT['_uni-TTL-interest-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-interest');
		$inputs .= addSampleselectNe(6,$user['p_relationship'],$TEXT['_uni-TTL-relationship-sml'],$TEXT['_uni-TTL-relationship-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-relationship');
		$inputs .= addSampleselectNe(7,$user['p_gender'],$TEXT['_uni-TTL-gender-sml'],$TEXT['_uni-TTL-gender-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-gender');
		$inputs .= addSampleselectNe(8,$user['p_bday'],$TEXT['_uni-TTL-birth-sml'],$TEXT['_uni-TTL-birth-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-birth');
		
		return addSampleWizard($t,'',$inputs,1) ;	
	
	// Privacy settings || MODERATORS WIZARD 
	} elseif($t == 19) {
	    
		return addSampleWizard($t,'',addSampleselectNe(1,$user['p_moderators'],$TEXT['_uni-TTL-moderators-sml'],$TEXT['_uni-TTL-moderators-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'pri-moderators',''),1) ;	
	
	// Notifications settings || ON BREEZE WIZARD 
	} elseif($t == 20) {
	    
		$inputs = addSampleselectNe(1,$user['n_type'],$TEXT['_uni-TTL-not-type-sml'],$TEXT['_uni-TTL-not-type-desc'],$TEXT['_uni-Real_time'],$TEXT['_uni-Manual'],'not-type','');
		$inputs .= addSampleselectNThree(2,$user['n_per_page'],$TEXT['_uni-TTL-not-page-sml'],$TEXT['_uni-TTL-not-page-desc'],15,10,5,'not-page','');
		$inputs .= addSampleselectNe(3,$user['n_accept'],$TEXT['_uni-TTL-not-accet-sml'],$TEXT['_uni-TTL-not-accet-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'not-accet','');
		$inputs .= addSampleselectNe(4,$user['n_follower'],$TEXT['_uni-TTL-not-foll-sml'],$TEXT['_uni-TTL-not-foll-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'not-foll','');
		$inputs .= addSampleselectNe(5,$user['n_like'],$TEXT['_uni-TTL-not-like-sml'],$TEXT['_uni-TTL-not-like-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'not-like','');
		$inputs .= addSampleselectNe(6,$user['n_comment'],$TEXT['_uni-TTL-not-cmmt-sml'],$TEXT['_uni-TTL-not-cmmt-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'not-cmmt','');
		$inputs .= addSampleselectNe(7,$user['n_mention'],$TEXT['_uni-TTL-not-mention-sml'],$TEXT['_uni-TTL-not-mention-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'pri-mentions','');
		
		
		return addSampleWizard($t,'',$inputs,1) ;	
	
	// Notifications settings || EMAIL WIZARD 
	} elseif($t == 21) {
	    
		$inputs = addSampleselectNe(1,$user['e_accept'],$TEXT['_uni-TTL-not-accet-sml'],$TEXT['_uni-TTL-e-accet-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'not-accet','');
		$inputs .= addSampleselectNe(2,$user['e_follower'],$TEXT['_uni-TTL-not-foll-sml'],$TEXT['_uni-TTL-e-foll-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'not-foll','');
		$inputs .= addSampleselectNe(3,$user['e_like'],$TEXT['_uni-TTL-not-like-sml'],$TEXT['_uni-TTL-e-like-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'not-like','');
		$inputs .= addSampleselectNe(4,$user['e_comment'],$TEXT['_uni-TTL-not-cmmt-sml'],$TEXT['_uni-TTL-e-cmmt-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'not-cmmt','');
		$inputs .= addSampleselectNe(5,$user['e_mention'],$TEXT['_uni-TTL-not-mention-sml'],$TEXT['_uni-TTL-e-mention-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'pri-mentions','');
		
		return addSampleWizard($t,'',$inputs,1) ;	
	}
}

// Add select of 3 types
function addSampleselectNe($t,$val,$ttl,$ttl_big,$text_1,$text_0,$img,$classes='') {
    global $TEXT;
	return '<div class="brz-clear brz-padding-8 '.$classes.' brz-full">
					<div style="padding:10px 5px 0px 0px;" class="brz-right brz-clear">
					        <select id="settings-input-'.$t.'" class="small right brz-border brz-right">
						       '.getSelect($val,$text_1,$text_0).'
				     	    </select>
				    </div>
					<div class="brz-padding brz-text-left brz-left brz-left" style="max-width:50%;">
		                <div class="brz-text-black"><img class="nav-item-text-inverse-big brz-img-'.$img.'" alt="" src="'.$TEXT['DATA-IMG-6'].'"></img> '.$ttl.'</div>
						<span class="brz-text-super-grey">'.$ttl_big.'</span>
		            </div>
				</div>';
}

// Add select
function addSampleselectNThree($t,$val,$ttl,$ttl_big,$text_3,$text_1,$text_0,$img,$classes='') {
    global $TEXT;
	return '<div class="brz-clear brz-padding-8 '.$classes.' brz-full">
					<div style="padding:10px 5px 0px 0px;" class="brz-right brz-clear">
					    <select id="settings-input-'.$t.'" class="small right brz-border brz-right">
						    '.getSelVal($val,$text_0,$text_1,$text_3).'
				     	</select>
				    </div>
					<div class="brz-padding brz-text-left brz-left" style="max-width:50%;">
		                <div class="brz-text-black"><img class="nav-item-text-inverse-big brz-img-'.$img.'" alt="" src="'.$TEXT['DATA-IMG-6'].'"></img> '.$ttl.'</div>
						<span class="brz-text-super-grey">'.$ttl_big.'</span>
		            </div>
				</div>';
}

// Add select input
function addSampleInputSelect($t,$ttl,$val,$text_0,$text_1,$text_2) {
    return '<div class="brz-clear brz-padding-16">			
				<div class="brz-col s4 brz-padding-medium">
					<span class="brz-right brz-text-bold brz-text-grey">'.$ttl.'</span>
				</div>
				<div class="brz-col s6">
					<span class="brz-left">
						<select id="settings-input-'.$t.'" class="small brz-border brz-right">
						    <option value="'.$text_0[0].'">'.$text_0[1].'</option>
			                <option value="'.$text_1[0].'">'.$text_1[1].'</option>
			                <option value="'.$text_2[0].'">'.$text_2[1].'</option>
				     	</select>
						<script>$("#settings-input-'.$t.'").val('.$val.');</script>
					</span>
				</div>';
}

// Add text input
function addSampleInput($t,$ttl,$val,$attr) {
    global $TEXT;
	return '<div class="brz-col s4 brz-padding-medium">
				<span class="brz-right brz-text-bold brz-text-grey">'.$ttl.'</span>
			</div>
			<div class="brz-col s6 brz-padding-medium">
				<span class="brz-left">
					<input id="settings-input-'.$t.'" '.$attr.' class="nav-item-text-inverse brz-border brz-text-grey brz-small brz-card" value="'.$val.'" />
				</span>
			</div>';	
}

// Add wizard
function addSampleWizard($t,$ttl,$inputs,$white = NULL) {
    global $TEXT;
	$class = ($white) ? 'brz-white-2' : 'brz-body-it';
	
	return '<div id="settings-content-'.$t.'" class="brz-border-bottom settings-content-class brz-clear '.$class.' brz-tiny-4 brz-container-widel">
	    		<div class="brz-no-overflow brz-full">
		    		<div class="brz-center brz-clear brz-full">
			    		<div class="brz-clear brz-padding-16">			
							'.$inputs.'	 
						</div>			
						<div class="brz-center brz-border-top brz-padding brz-text-super-grey brz-info-mar">
				    		<div class="brz-text-super-grey brz-text-left">'.$ttl.'</div>
				    		<div id="settings-content-mess-'.$t.'"></div> 
						</div>
							
						<div class="brz-right brz-small brz-margin">
							<span id="settings-content-space-'.$t.'"></span>
				    		<div id="settings-content-save-'.$t.'" onclick="saveTab('.$t.');" class="brz-round brz-padding-neo2  brz-tag brz-blue brz-small brz-hover-blue-hd brz-cursor-pointer brz-text-white brz-text-bold" >'.$TEXT['_uni-Save_changes'].'</div>
				    		<button id="settings-content-close-'.$t.'" onclick="closeAll();" class="brz-new_btn brz-show-edit-cancel brz-round brz-padding-neo2 brz-text-bold brz-small brz-text-grey">'.$TEXT['_uni-Cancel'].'</button>
						</div>			
            		</div>			
				</div>
			</div>';	
}
?>