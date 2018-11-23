<?php
function sizeImage($src, $des, $ty, $mx, $wd, $hi, $qt) {                    // Resize over sized image 

	// Return false if NOT image
	if($wd <= 0 || $hi <= 0) return false; 

	// Save image as it is if its under within width and height
	if($wd <= $mx && $hi <= $mx){
		if(saveImage($src, $des, $ty, $qt)){
			return true;
		}
	}

	// Else Scale image to right size and create a new image
	$image_scale = min($mx/$wd, $mx/$hi);
	$new_width = ceil($image_scale * $wd);
	$new_height = ceil($image_scale * $hi);
	
	// Ready image
	$new_canvas	= imagecreatetruecolor($new_width, $new_height); 

	// Save created image
	if(imagecopyresampled($new_canvas, $src, 0, 0, 0, 0, $new_width, $new_height, $wd, $hi)){
		saveImage($new_canvas, $des, $ty, $qt);	
	}

	return true;
}	

function getCategoryTitle($cid,$db) {
	
	$query_id = $db->query(sprintf("SELECT `cat_name` FROM `categories` WHERE `cid` = '%s'",$db->real_escape_string($cid)));
	
	return ($query_id->num_rows !== 0) ? $query_id->fetch_assoc() : 0;
}	
 
function saveImage($src, $des, $type, $qt) {                                 // Save image
		
	// Select valid mime type and create valid format
	switch(strtolower($type)){
		case 'image/png':
			imagepng($src, $des); return true; 
			break;
		case 'image/gif': 
			imagegif($src, $des); return true; 
			break;          
		case 'image/jpeg': case 'image/pjpeg': 
			imagejpeg($src, $des, $qt); return true; 
			break;
		default: return false;
	}
}
	
function templateSrc($file='',$get_src=0) {                                               // Get template src
    global $TEXT;
	
	// Find file
	if(file_exists('themes/'.$TEXT['theme'].'/theme.php')) {
		$src = '';
	} elseif(file_exists('../themes/'.$TEXT['theme'].'/theme.php')) {
		$src = '../';
	} elseif(file_exists('../../themes/'.$TEXT['theme'].'/theme.php')) {
		$src = '../../';
	} elseif(file_exists('../../../themes/'.$TEXT['theme'].'/theme.php')) {
		$src = '../../../';
	} elseif(file_exists('../../../../themes/'.$TEXT['theme'].'/theme.php')) {
		$src = '../../../../';
	}
	
	// Return SRC
	return ($get_src) ? $src.'themes/'.$TEXT['theme'].'/html' : $src.'themes/'.$TEXT['theme'].'/html'.$file.$TEXT['templates_extension'];
	
}

function emailVerification($db,$settings,$idu,$salt = NULL) {          // Send new response || or verify email
	global $TEXT;
	
	if(is_null($salt)) {

		// Select user
		$user = $db->query(sprintf("SELECT * FROM `users` WHERE `users`.`idu` = '%s' ", $db->real_escape_string($idu)));
		
		// Fetch user
		$result = $user->fetch_assoc();
	  
		// Generate secure random code
		$salted = md5(secureRand(10,TRUE));
		
		// Set activation response
		$db->query(sprintf("UPDATE `users` SET `salt` = '%s' WHERE `users`.`idu` = '%s' AND `users`.`state` = 2 ", $salted, $db->real_escape_string($idu)));

		// Send activation mail
		mailSender($settings, $result['email'], $TEXT['_uni-Activate_account'], sprintf($TEXT['_uni-Activation_mail'], $TEXT['title'], $TEXT['installation'].'/index.php?respond='.$salted.'&type=activation&for='.$result['idu']), $TEXT['web_mail']);
		
		return $TEXT['_uni-login-2'];

	} else {
		
		// Select user
		$user = $db->query(sprintf("SELECT * FROM `users` WHERE `users`.`idu` = '%s' AND `users`.`salt` = '%s' AND `users`.`state` = 2 ", $db->real_escape_string($idu), $db->real_escape_string($salt)));	
		
		// Fetch user
		$result = $user->fetch_assoc();

		// If code MATCHED
		if($user->num_rows) {
	
			// Activate the account
			$db->query(sprintf("UPDATE `users` SET `users`.`salt` = '', `users`.`state` = 1 WHERE `users`.`idu` = '%s' ", $db->real_escape_string($idu)));

			// Delete any pending accounts
			$db->query(sprintf("UPDATE `users` SET `users`.`email` = 'NULL', `users`.`state` = 4 WHERE `users`.`email` = '%s' AND `users`.`state` != 1 ", $db->real_escape_string($result['email'])));

			return 'ACTIVATED';
			
		} else {
			return $TEXT['_uni-E-Mail_verification1'];
		}
	}
}

function parseBackgrounds($img_set) {
	global $TEXT;
	
	$backgrounds = explode(',',$img_set);
	
	// Save images in global index
	$TEXT['BACK_1'] = $backgrounds[0];
	$TEXT['BACK_2'] = $backgrounds[1];
	$TEXT['BACK_3'] = $backgrounds[2];
	$TEXT['BACK_4'] = $backgrounds[3];
	$TEXT['BACK_5'] = $backgrounds[4];
	$TEXT['BACK_6'] = $backgrounds[5];
	$TEXT['BACK_7'] = $backgrounds[6];
	$TEXT['BACK_8'] = $backgrounds[7];
	$TEXT['BACK_9'] = $backgrounds[8];
	$TEXT['BACK_10'] = $backgrounds[9];	
}

function mailSender($settings, $to, $subject, $message, $from) {       // Mail sender SMTP + Basic mail function
	
	// Global ARRAY
	global $TEXT;	
	
	// If the SMTP emails option is enabled in the Admin Panel
	if($settings['smtp_email']) {
		
		// Import PHPMailer
		require_once(__DIR__ .'/phpmailer/PHPMailerAutoload.php');

		// Create PHPMailer class 
		$mail = new PHPMailer;
		
		// SMTP settings
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		
		// Set content settings
		$mail->CharSet = 'UTF-8';
		$mail->Debugoutput = 'html';
		
		// Set the HOST of the mail server
		$mail->Host = $settings['smtp_host'];
		
		// SMTP port number
		$mail->Port = $settings['smtp_port'];
		
		// Whether to use SMTP authentication
		$mail->SMTPAuth = ($settings['smtp_auth']) ? true : false;
		
		// USERNAME
		$mail->Username = $settings['smtp_username'];
		
		// PASSWORD
		$mail->Password = $settings['smtp_password'];
		
		// Mail sender
		$mail->setFrom($from, $settings['title']);
		$mail->addReplyTo($from, $settings['title']);
		
		// Send mail to
		$mail->addAddress($to);

		// Subject
		$mail->Subject = $subject;
		
		// HTMLise body
		$mail->msgHTML($message);

		// If sent
		return (!$mail->send()) ? 0 : 1;
		
	} else {
		
		// Use basic mail sending function if SMTP is not enabled
		
		// Set MIME version
		$set  = 'MIME-Version: 1.0' . "\r\n";
		
		// Set content type
		$set .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		
		// Add content
		$set .= 'From: '.$from.'' . "\r\n" .
			    'Reply-To: '.$from . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();
		
		// Send MAIL
		@mail($to, $subject, $message, $set);
	}
}

function bannerIt($img,$t1,$t2,$id = 4513,$fb_icon=NULL) {                           // Fix text length
    global $TEXT;
	
	if($fb_icon) {
		$fb_icon_add = '';
		
	} else {
		$fb_icon_add = '<div class="brz-full brz-container">
							<img class="brz-left brz-img-25" alt="" src="'.$TEXT['installation'].'/themes/'.$TEXT['theme'].'/img/icons/brz.png">
                		</div>';
	}
	return '<div class="brz-padding brz-no-border brz-new-container">
	            '.$fb_icon_add.'
                <div class="brz-padding-16 brz-center brz-full brz-large-min brz-text-bold brz-text-black">
                    <img id="'.$id.'" class="brz-img-'.$img.'" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACVAQMAAACAW5EPAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABpJREFUeNrtwTEBAAAAwqD1T20ND6AAAIAnAwukAAFLjjSfAAAAAElFTkSuQmCC">
                    <div>'.$t1.'</div>
                    <div class="brz-small brz-text-grey brz-opacity">'.$t2.'</div>
                </div>
            </div>';
}

function fixText($length,$a) {                                         // Fix text length

	// Count number of characters
	$len_a = strlen($a);
	
	// If length is less than or equal to allowed length return	
	return ($len_a <= $length) ? protectXSS($a) : substr(protectXSS($a),0,$length-3).'...';

}

function fixName($length,$v1,$v2,$v3) {                                // Profile naming

	global $TEXT;

	// Counting lengths
	$len_a = strlen(trim($v1));
	$len_b = strlen(trim($v2));
	$len_c = strlen(trim($v3));
	
	// Add protection
    $a = protectXSS($v1);
    $b = protectXSS($v2);
    $c = protectXSS($v3);

	// If $c or $b is empty use only $c
	if($len_b == 0 || $len_c == 0) {
		if($len_a  > 0) {
			
			return ($len_a <= $length) ? $a : substr($a,0,$length-2).'..';

		} else {

			// Else validate $b
			if($len_b > 0) {
				
				return($len_a <= $length) ? $b : substr($b,0,$length-2).'..';

            // Further validate $c					
			} elseif($len_c > 0) {
				
				return ($len_c <= $length) ? $c : substr($c,0,$length-2).'..';

            // No-Name				
			} else {
				return $TEXT['lang_error_noname'];					
			}	
		}

	// Else fetch $c or $b
	} elseif($len_b + $len_c > 3) {
		
		return ($len_b + $len_c <= $length) ? $b.' '.$c : substr($b.' '.$c,0,$length-2).'..';
		
	// Else return No-name
	} else {
		return $TEXT['lang_error_noname'];
	}		
}	
	
function readAble($value) {                                            // Convert large numbers to 9.9k...

	// Already readable
	if($value == 0 || $value < 0) return 0;

	// If less than 10k return as it is
	elseif($value < 10000) return $value;
  
	// Covert to thousands
	elseif($value < 1000000) return substr(($value / 1000),0,5).' k';
   
	// Covert to millions
	elseif($value < 10000000) return substr(($value / 1000000),0,5).' m';
 
	// Covert to billions
	else return substr(($value / 10000000),0,5).' b';
    
}

function readableBytes($bytes) {                                       // Convert large bytes to 9.9MB...
   
   // Already readable
   if ($bytes < 1024) return $bytes.' Bytes';
   
   // Covert to KBs
   elseif ($bytes < 1048576) return round($bytes / 1024, 2).' KB';
   
   // Covert to MBs
   elseif ($bytes < 1073741824) return round($bytes / 1048576, 2).' MB';
   
   // Covert to GBs
   elseif ($bytes < 1099511627776) return round($bytes / 1073741824, 2).' GB';
   
   // Covert to TBs
   else return round($bytes / 1099511627776, 2).' TB';
}

function getUserIP() {                                                 // RETURN user remote address
	
	// return address if available
	return ($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
	
}

function reorderArray($a, $b) {                                        // Reorder array
	return ($a['time'] > $b['time'] && $a['time'] !== $b['time']) ? -1 : ($a['time'] == $b['time']) ? 0 : 1;  
}
	
function protectInput($input) {                                        // Security protection while saving data
	
	return htmlspecialchars(trim(preg_replace("/(\r?\n){2,}/", "\n\n", $input)));
	
}	

function protectXSS($input) {                                          // Security protection while output data 
	
	return strip_tags($input);	

}
	
function isXSSED($input) {                                             // Custom input blocker

    /*
	All user inputs cross through this check you can add custom validation here.
	Function must return 0 if input is safe else return 1 to terminate saving 
	process
	
	// Example
	if(customValidate($input) == 'PASS') {
		return 0 ;       // Input is safe
	} else {
		return 1;        // Input is not safe, this will prevent adding input to database
	}
	
	*/
	
	return 0;
	
}
	
function showError($text, $ttl = NULL, $ground_set = NULL,$onclick='') {          // Notifier - (Modal)
    
	global $TEXT;
	
	// Add title
	$title = ($ttl) ? $ttl : ($ground_set) ? $ground_set : $TEXT['_uni-c_d_this'];
	
	return '<div id="modal-info-on" class="brz-modal">
	            <div class="brz-modal-content2 brz-animate-zoom brz-clear brz-card-8" style="height:auto;">
		            <div class="brz-border brz-border-pink">
				        <div id="modal-title" class="brz-pink-light brz-clear brz-padding brz-medium brz-text-bold brz-full">
			    	        <span class="brz-left">'.$title.'</span>
				        </div>
				        <div id="modal-text" class="brz-white brz-clear brz-padding brz-small brz-full">
			    	        <span class="brz-left">'.$text.'</span>
				        </div>
				        <div id="modal-btns" class="brz-super-grey brz-clear brz-border-top brz-padding brz-small brz-full">
			    	        <div onclick="'.$onclick.'" class="brz-tag brz-modal-close brz-blue brz-hover-blue-hd brz-padding brz-cursor-pointer brz-right brz-text-white brz-text-bold brz-small">'.$TEXT['_uni-OK'].'</div>
				        </div>
			        </div>	
	            </div>
	        </div>';

}
	
function showSuccess($text,$ttl = NULL,$onclick='') {                              // Notifier - SUCCESS
    global $TEXT;

	// Create success box
	return showError($text, $ttl = NULL, $TEXT['_uni-done_msg'],$onclick);
	
}
	
function showNotification($text) {                                     // Notifier - INFORM
	global $TEXT;

	// Create success box
	return showError($text, $ttl = NULL, $TEXT['_uni-no_change_msg']);
}	

function showBox($text) {                                              // Notifier - Standard
	return '<div class="brz-padding brz-card brz-white brz-round brz-margin brz-small" style="margin-top:15px">'.$text.'</div>';
}

function closeBox($text) {                                             // Close notifier - Standard box
	return '<div style="width:100%;" class="brz-padding-8 brz-text-bold brz-left brz-small brz-padding brz-super-grey brz-border-bottom brz-text-grey brz-border-super-grey">
		     '.$text.'
			 </div>';
}

function closeBody($text,$white = NULL) {                              // Ultimate Close notifier 
    $color = ($white) ? 'brz-white' : 'brz-body-it-new';                                     
	return '<div class="brz-center" style="margin-top:40px!important;"><span class="brz-padding brz-small brz-text-super-grey '.$color.'">'.$text.'</span><hr style="border-color:#C3C1C1!important;margin:-12px 20px auto 20px ;z-index:0;"></div><br><br>';
}

function chatNotification($text) {                                     // Notifier - For chats
	// Notification template
	return '<div style="width:100%">
				<div align="center">
					<div class="brz-animate-zoom brz-opacity brz-margin brz-text-grey brz-padding brz-tiny-3">
						'.$text.'
					</div>
				</div>
			</div>';
}

function addStamp($time,$type=0) {
    global $TEXT;
	
	// Explode date
    $seprate = explode('-', date('d-m-Y',strtotime($time)));

    if (date('Ymd') == date('Ymd', strtotime($time)))  {
        $stamp = $TEXT['_uni-Today'];	
    } else if(date('Ymd', strtotime($time)) == date('Ymd', strtotime('yesterday'))) {
        $stamp = $TEXT['_uni-Yesterday'];	
    } elseif(date('Y', time()) === date('Y', strtotime($time))) {
        $stamp = $TEXT["_uni-Month-".intval($seprate[1])].' '.$seprate[0];
    } else {
        $stamp = $TEXT["_uni-Month-".intval($seprate[1])].' '.$seprate[0].', '.$seprate[2];
    }
	
	return $stamp;
	
}

function addLog($val,$type=0) {
    $style = ($type) ? 'brz-pale-red' : 'brz-white';
    $st2 = ($type) ? ' fa-times brz-text-red ' : ' fa-chevron-right brz-text-green';
    return '<div class="brz-padding brz-small '.$style.'">
					<pre style="margin:0px!important;padding:0px!important;"><i class="fa'.$st2.'"></i> '.$val.'</pre>
				</div> ';
		
}

function secureRand($len,$strong = FALSE) {                            // Secure random string generator	
	
	// If secure random is requested 
	if($strong && function_exists('openssl_random_pseudo_bytes')) {
		
		// Generate secure random string (requires Open SSL)
		return substr(str_shuffle(bin2hex(openssl_random_pseudo_bytes(16))),0,$len);
		
	} elseif($strong && !function_exists('openssl_random_pseudo_bytes')) {
		
		// If Open SSL functions doesn't exists generate random string as secure as possible
		return substr(str_shuffle(mt_rand(11001,99999).str_shuffle("CeNVAsa3EpYR2").mt_rand(11001,99999).str_shuffle("85mvKqOgcZfy").str_shuffle("9n_Pli4BSUtD6X").str_shuffle("GFJ1kIhxM0HoTzubrQd7Lj")),0,$len);
		
	} else {
		
		// Medium strength
		return substr(str_shuffle("CeNVAsa3EpYR285mvKqOgcZfy9n_Pli4BSUtD6XGFJ1kIhxM0HoTzubrQd7Lj"),0,$len).$ssl;
	
	}
}

function listPost($p_id,$u_idu,$u_ttl,$u_nm,$t_m,$Edited,$pt_ns,$p_t,$p_con,$buttons,$details,$profile_picture,$heading_title,$addon='',$function=array('loadProfile','a')) { // List post
	global $TEXT;
	
	return '<div id="post_view_'.$p_id.'" class="brz-new-container">
				<div class="brz-clear" style="padding:13px 13px 0px 13px;">			
					<div class="brz-full">			
						<div class="brz-left">
					    	<img class="brz-round-xlarge brz-cursor-pointer brz-left brz-image-margin-right" onclick="loadProfile('.$u_idu.');" title="'.$u_ttl.'" src="'.$TEXT['installation'].'/thumb.php?src='.$profile_picture.'&fol='.$function[1].'&w=46&h=46" alt="..." width="46" height="46">
	    				</div>			
						<div class="">						
							<div class="brz-no-overflow">
								<div class="brz-line-o">
									<span onclick="'.$function[0].'('.$u_idu.');" title="'.$u_ttl.'" >
										<span class="brz-medium brz-cursor-pointer brz-text-bold brz-text-blue-dark brz-underline-hover">'.$u_nm.'</span>	
									</span>				
									<span class="brz-small brz-hide-large brz-text-grey">'.$heading_title.'</span>
									<span class="brz-medium brz-hide-small brz-hide-medium brz-text-grey">'.$heading_title.'</span>	
									'.$addon.'						
						        </div>				
								'.$Edited.'				
	    						<span title="'.$t_m.'" class="brz-small nav-item-text-inverse-big-2 timeago brz-text-underline brz-hover-text-black brz-cursor-pointer brz-opacity brz-text-grey" >
									'.$t_m.' 
								</span>
							</div>
						</div>
					</div>	
        			<div id="post_view_text_'.$p_id.'" class="brz-clear brz-padding-8 brz-text-responsive">
		    			<div class="brz-hide">'.$pt_ns.'</div>
						<div class="brz-c-text"></div>
						<div class="brz-hide">'.$p_t.'</div>
					</div>
				</div>	
			    '.$p_con.'
				<div class="brz-clear" class="brz-padding-top">	
					<div id="post_view_button_'.$p_id.'" class="brz-clear brz-small brz-padding-top brz-padding brz-text-bold">
						'.$buttons.'		
				   	</div>
				</div>
                '.$details.'								
			</div>';
}

function listUserCaps($id,$title,$img,$name,$v_batch,$inf,$rel_btn,$small = NULL,$grouped=NULL) {  // List user
    global $TEXT;
	
	$add_container_hoverable = $add_container = $text2 = '';
	
	$class = (!$small) ? 'brz-half': 'brz-full';
	
	if($grouped) {
	    $add_container = 'brz-display-container';
	}
	
	if($small) {
	    $size = '&w=30&h=30';
	    $size_visible = 'width:30px;height:30px;';
		$text1 = 'brz-small';
	} else {
	    $size = '&w=50&h=50';
	    $size_visible = 'width:50px;height:50px;';
	    $text1 = 'brz-medium';
	    $text2 = 'brz-user-padding';
	}
	
	if(empty($inf)) {
	$inf = '<span class="brz-text-white">Nothing to show</span>';}
	
	return '<div class="'.$text2.' '.$add_container.' '.$class.' brz-clear brz-white">
                <img onclick="loadProfile('.$id.')" title="'.$title.'" src="'.$TEXT['installation'].'/thumb.php?src='.$img.'&fol=a'.$size.'" alt="..." class="brz-round brz-border brz-border-super-grey brz-left brz-margin-right-small" style="'.$size_visible.'">
                <div onclick="loadProfile('.$id.')" title="'.$title.'" style="position: relative;bottom: 5px;" class="'.$text1.' brz-right-top brz-cursor-pointer brz-text-bold brz-text-blue-dark brz-underline-hover">
				    '.$name.'
				    '.$v_batch.'
				</div>
                <span style="position: relative;bottom: 12px;left:2px;" class="brz-small brz-opacity brz-text-grey">
				    '.$inf.'
				</span>
                <span class="brz-right nav-item-text-inverse-big">
                    '.$rel_btn.'
                </span>
            </div>';
}

function listVideo($id,$nm,$img,$ab3) {       // List video for search page
    global $TEXT;
	
	return '<div class="brz-padding-nots brz-white">
	            <div class="brz-clear">
	                <div style="min-width:50px;min-height:50px;" onclick="loadPost('.$id.');" class="brz-left brz-image-margin-right brz-search-picture brz-cursor-pointer">
		                <img id="MAIN_SEARCH_IMG_'.$id.'" class="brz-border brz-border-super-grey" src="'.$TEXT['installation'].'/thumb.php?src='.$img.'&fol=c&w=90&h=90">
		            </div>
			        <span class="">
			            <div class="brz-search-padding">
	                        <div class="brz-clear">
						        <div onclick="loadPost('.$id.');" class="brz-text-ell brz-cursor-pointer brz-hvr-black-btn brz-text-bold brz-responsive-zbig-styled brz-text-black">
									<span id="MAIN_SEARCH_IMG_TT1_'.$id.'" >'.$nm.'</span>							
						        </div>
						        <div class="brz-text-super-grey brz-tiny-3 brz-line-o">
						            <div id="MAIN_SEARCH_IMG_T1_'.$id.'" class="brz-text-black" title="'.$TEXT['_uni-Youtube_channel'].'"></div>
						           <div>'.$ab3.'</div>
						        </div>
					        </div>
	                    </div>
			        </span>
	            </div>
	        </div><hr>';
}

function getPageSelects($array) {
	global $TEXT;
	
	$return = '';
	
	foreach($array as $row) {
		
		$return .= '<option value="'.$row['cid'].'">'.$TEXT[$row['cat_name']].'</option>';
		
	}
	
	return $return;
	
}

function listMainUser($id,$nm,$u_nm,$img,$title,$v_bt,$ab1,$ab2,$ab3,$r_btn,$grp=NULL) {       // List user for search page
    global $TEXT;
	
	$r_id = mt_rand().'_S_USER';
	
	$function_1 = 'loadGroup('.$id.',1,1);' ;
	
	$img_src = 'f' ;
	
	$drop_down_functions = $u_nm ;

	if(!$grp) {
		$img_src = 'a' ;
		$function_1 = 'loadProfile('.$id.');' ; 
		$drop_down_functions = '<a onclick="profileLoadTimeline('.$id.');javascript:void(0);" class="brz-tiny-2 brz-hover-blue-hd brz-hover-text-white" href="javascript:void(0);">'.$TEXT['_uni-Timeline'].'</a>
                                <a onclick="profileLoadGallery('.$id.');javascript:void(0);" class="brz-tiny-2 brz-hover-blue-hd brz-hover-text-white" href="javascript:void(0);">'.$TEXT['_uni-Gallery'].'</a>
                                <a onclick="profileLoadFollowers('.$id.',0,0);javascript:void(0);" class="brz-tiny-2 brz-hover-blue-hd brz-hover-text-white" href="javascript:void(0);">'.$TEXT['_uni-Followers'].'</a>
                                <a onclick="profileLoadFollowings('.$id.',0,1);javascript:void(0);" class="brz-tiny-2 brz-hover-blue-hd brz-hover-text-white" href="javascript:void(0);">'.$TEXT['_uni-Followings'].'</a>
								<a onclick="profileLoadAbout('.$id.');javascript:void(0);" class="brz-tiny-2 brz-hover-blue-hd brz-hover-text-white" href="javascript:void(0);">'.$TEXT['_uni-About'].'</a>	    	
								<hr style="margin:5px;">
								<a onclick="copyToClipboard(\''.$TEXT['installation'].'/'.$u_nm.'\',\''.$TEXT['_uni-Profile_URL_copied'].'\');javascript:void(0);" class="brz-tiny-2 brz-hover-blue-hd brz-hover-text-white" href="javascript:void(0);">'.$TEXT['_uni-Copy_URL_profile'].'</a>
								';
	}
	
	return '<div class="brz-padding-nots brz-white">
	            <div class="brz-clear">
	                <div style="min-width:50px;min-height:50px;" onclick="'.$function_1.'" title="'.$title.'" class="brz-left brz-image-margin-right brz-search-picture brz-cursor-pointer">
		                <img class="brz-border brz-border-super-grey" src="'.$TEXT['installation'].'/thumb.php?src='.$img.'&fol='.$img_src.'&w=90&h=90">
		            </div>
			        <span class="">
			            <div class="brz-search-padding">
	                        <div class="brz-clear">
					            <div class="brz-right brz-search-l-class">
						            '.$r_btn.'<div  class="brz-dropdown-click brz-hide-small brz-search-l-class" onclick="$(\'#'.$r_id.'\').toggleClass(\'brz-show\');">
                                	<button id="focc228a295035bf70" class="brz-new_btn brz-round brz-padding-standard brz-text-bold brz-tiny-2 brz-text-grey"><img class="nav-item-text-inverse-big brz-img-dropit" alt="" src="'.$TEXT['DATA-IMG-2'].'"></button>
                                	<div class="brz-container">
									<div id="'.$r_id.'" class="brz-dropdown-content brz-left brz-transparent" style="right:0!important;" >  			    	
										<div style="height:12px;"><img style="position:absolute;top:0px;right:7px;" class="brz-img-drop-down-cat" src="'.$TEXT['DATA-IMG-9'].'"></div>
						                <div class="brz-white brz-border brz-padding-8 brz-card-2">
											'.$drop_down_functions.'
										</div>
									</div>
									</div>
									
                                    </div>
						        </div>
						        <div title="'.$title.'" onclick="'.$function_1.'" class="brz-text-ell brz-cursor-pointer brz-hvr-black-btn brz-text-bold brz-responsive-zbig-styled brz-text-black">
									<span >'.$nm.'</span>
									'.$v_bt.'
						        </div>
						        <div class="brz-text-super-grey brz-tiny-3 brz-line-o">
						            <div class="brz-text-black">'.$ab1.'</div>
						           <div>'.$ab2.'</div>
						           <div class="brz-hide-small">'.$ab3.'</div>
						        </div>
					        </div>
	                    </div>
			        </span>
	            </div>
	        </div><hr>';
}

function listChatCaps($id,$title,$img,$name,$inf,$rel_btn) {                         // List chat
    global $TEXT;
	return '<div class="brz-user-padding brz-half brz-white">
                <img onclick="loadChat('.$id.')" title="'.$title.'" src="'.$img.'" alt="..." class="brz-left brz-border brz-border-super-grey brz-margin-right-small" width="46" height="46">
                <div onclick="loadChat('.$id.')" title="'.$title.'" style="position: relative;bottom: 5px;" class="brz-brz-medium brz-right-top brz-cursor-pointer brz-text-bold brz-text-blue-dark brz-underline-hover">
				    '.$name.'
				</div>
				
                <span style="position: relative;bottom: 12px;left:2px;" class="brz-small brz-opacity brz-text-grey">
				    '.$inf.'
				</span>
                <span class="brz-right nav-item-text-inverse-big">
                    '.$rel_btn.'
                </span>
            </div>';
}
	
function nameCategory($page_cat,$page_cat_2,$all_cats,$db) {
    global $TEXT;
	
    if($page_cat == 1) {
		return $page_cat_2;
	} else {
			
		if(in_array($page_cat_2,$all_cats)) {
		
			$cat_index = getCategoryTitle($page_cat_2,$db);
	
			// Add Name
		    return $TEXT[$cat_index['cat_name']];

		}
	}
	
}

		
function listComment($id,$p_id,$ttl,$name,$img,$text,$actions) {                     // List comment
    global $TEXT;
	return '<div style="margin-top:6px;" id="comment-id-'.$id.'">
                <div class="brz-clear">
                    <div class="brz-left">
                        <img title="'.$ttl.'" onclick="aOverlow();s23u89dssh();'.$p_id.'" src="'.$TEXT['installation'].'/thumb.php?src='.$img.'&w=35&h=35" alt="..." class="brz-left brz-border brz-border-super-grey brz-image-margin-right" width="35" height="35">
	                </div>
					<div class="">
		                <div class="brz-small brz-no-overflow">
			                <div class="brz-line-o">
							    <span title="'.$ttl.'" onclick="aOverlow();s23u89dssh();'.$p_id.'" class="brz-text-bold brz-text-blue-dark brz-cursor-pointer brz-underline-hover">'.$name.'</span> 
								<span class="brz-text-black">
								    '.$text.'
								</span>
							</div>
				            <span class="brz-small">
				                '.$actions.'
				            </span>
		                </div>
		            </div>
	            </div>
            </div>';
}
	
function addLoadmore($auto,$title,$function) {                                       // Add load more function
	global $TEXT;
	// Check whether infinite scrolling is enabled
	$auto_load = (!$auto) ? '' : 'class="AUTO-LOAD"';
	
	return '<div id="last_post_preload" class="load last_post_preload" style="margin-top:15px;width:70%;left:15%"></div>
			<div '.$auto_load.' id="load-more-data" align="center" >
				<button id="pre-loader-starter" title="'.$title.'" onclick="'.$function.'" class="load-more-data brz-cursor-pointer brz-round brz-margin brz-new_btn"><i class="fa fa-plus" ></i> '.$TEXT['_uni-More'].'</button>
				<br>
			</div>';
			
}

function getSelVal($value,$text_0,$text_1,$text_3) {                                 // Generate Selects || ACCEPT 3 VALs	
		
	if($value == $text_0) {

		$te1 = $text_0; $va1 = $text_0;
		$te2 = $text_1; $va2 = $text_1;
		$te3 = $text_3; $va3 = $text_3;

	} elseif($value == $text_1) {

		$te1 = $text_1; $va1 = $text_1;
		$te2 = $text_3; $va2 = $text_3;
		$te3 = $text_0; $va3 = $text_0;
	
	} else {
	
		$te1 = $text_3; $va1 = $text_3;
		$te2 = $text_0; $va2 = $text_0;
		$te3 = $text_1; $va3 = $text_1;	

	}
	return '<option value="'.$va1.'">'.$te1.'</option>
			<option value="'.$va2.'">'.$te2.'</option>
			<option value="'.$va3.'">'.$te3.'</option>';	
}
	
function getSelect($value,$text_1,$text_0) {                                         // Generate Selects || ACCEPT 2 VALs

	if($value == 1) {
		$te1 = $text_1 ;
		$va1 = '1';
		$te2 = $text_0 ;
		$va2 = '0';
	} else {
		$te1 = $text_0 ;
		$va1 = '0';
		$te2 = $text_1 ;
		$va2 = '1';
	}
	return '<option value="'.$va1.'">'.$te1.'</option>
		    <option value="'.$va2.'">'.$te2.'</option>';	
}	

function countFollows($page_id,$db) {                                       // Number Followers
	
	// Count likes
	$follows = $db->query(sprintf("SELECT COUNT(*) FROM `page_users` WHERE `page_users`.`page_id` = '%s' ", $db->real_escape_string($page_id)));

	list($numbers) = $follows->fetch_row();
	
	// Return number of rows
	return $numbers;
	
}

function countLikes($page_id,$db) {                                         // Number likes

	// Count likes
	$likes = $db->query(sprintf("SELECT COUNT(*) FROM `page_likes` WHERE `page_likes`.`page_id` = '%s' ", $db->real_escape_string($page_id)));

	list($numbers) = $likes->fetch_row();
	
	// Return number of rows
	return $numbers;
	
}

function numberGroupMembers($group_id,$db) {                                         // Number memebers of group

	// Count members
	$members = $db->query(sprintf("SELECT COUNT(*) FROM `group_users` WHERE `group_users`.`group_status` = '1' AND `group_users`.`group_id` = '%s' ", $db->real_escape_string($group_id)));

	list($numbers) = $members->fetch_row();
	
	// Return number of rows
	return $numbers;
	
}

function linkHref($links) {                                                          // Parse URLs            
	
    // Check URL for "http://"
	$link = (substr($links[1], 0, 4) == 'www.') ? 'http://'.$links[1] : $links[1];
	
	return '<a href="'.$link.'" target="_blank" class="brz-tiny-2 brz-text-blue-dark brz-underline-hover">'.str_replace(array('http://','https://'), '', $link).'</a>';

}

function getBirthday($date) {                                                        // Parse Birthdate
    global $TEXT;
	
	// Explode date
	$seprate = explode('-', $date);

	// Start checking the values
	return (!empty($seprate[1]) && !empty($seprate[0]) && !empty($seprate[2])) ? $TEXT["_uni-Month-".intval($seprate[1])].' '.$seprate[0].', '.$seprate[2]:FALSE;

}

function fuzzyStamp($time,$now = NULL,$type = '') {                                  // Convert INT to fuzzy time stamps
	global $TEXT;
	
	// Time gap
	$ago = time() - $time; 
	
	// IF on same second show active
	$return = ($now) ? $TEXT['_uni-just_now'] : $TEXT['_uni-Online'];
	
	// Time array
	$second_set = array(
	    $TEXT['_uni-Time7'.$type] => 31536000, 
		$TEXT['_uni-Time6'.$type] => 2628000,
		$TEXT['_uni-Time5'.$type] => 604800, 
		$TEXT['_uni-Time4'.$type] => 86400, 
		$TEXT['_uni-Time3'.$type] => 3600, 
		$TEXT['_uni-Time2'.$type] => 60, 
		$TEXT['_uni-Time1'.$type] => 1 );
	
    $ago_it = (empty($type)) ? $TEXT['_uni-ago'] : '';	
	
	foreach($second_set as $val => $seconds) {

	    // If gap unders
		if($seconds <= $ago) {
			
			// Time gap
			$return = floor($ago / $seconds).$val.' '.$ago_it;
			
			break;	
		}
		
		// Else loop
	}
	
	return $return;	
}

function getFolderSize($dir) {                                                       // Get folder size
    
	// Reset
	$size = 0;
	
	// Loope for each file and count size
    foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
        $size += is_file($each) ? filesize($each) : getFolderSize($each);
    }
	
	// Return total Bytes count
    return $size;
}

function display($file,$opened = 0,$return = 0) {                                                // Template Parser
    
	if(!$opened) {
	
        // Open template file	
	    $display_opened = fopen($file, 'r');
	
	    // Read contents
	    $display = @fread($display_opened, filesize($file));
	
		// Close template file
		fclose($display_opened);
	
	} else {
		$display  = $opened;
	}
	
	// Return template contents is requested
	if($return) {
		
		return $display;
		
	// Else parse ull template
	} else {

		// Parse template contents
		$display = preg_replace_callback('/{\$TEXT->(.+?)}/i', create_function('$matches', 'global $TEXT; return $TEXT[$matches[1]];'), $display);	

		// Return parsed display
		return $display;
		
	}
	
}
?>