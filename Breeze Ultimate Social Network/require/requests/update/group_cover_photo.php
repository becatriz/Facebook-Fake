<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// Import user class
$profile = new main();
$profile->db = $db;

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get currently logged user
	$user = $profile->getUser();
	
	// Fetch group user
	$group_user = $profile->getGroupUser($user['idu'],$_POST['group-cover-form-id']);
	
	// Is allowed
	$joined = ($group_user['group_status'] == 1 && ($group_user['group_role'] == 2 || $group_user['p_cover'] == 1)) ? 1 : 0;
	
	// If user exists
	if(!empty($user['idu']) && $joined) {	
	
		$image_name = $_FILES['uGc-f-2']['name'];     // File name
		$image_size = $_FILES['uGc-f-2']['size'];     // File size
		$image_temp = $_FILES['uGc-f-2']['tmp_name']; // File temp
	    	
        // Check whether file is selected			
        if(isset($_FILES['uGc-f-2']) && is_uploaded_file($_FILES['uGc-f-2']['tmp_name'])){
			$image_size_info = getimagesize($image_temp);            // Return true if file is a valid image       
		} else {
			$image_size_info = 0;
		}
		
		// Check whether file is processed
		if($image_size_info && $image_size <= $page_settings['max_img_size']*1000000){
			
			$image_width 		= $image_size_info[0];      // Image width
			$image_height 		= $image_size_info[1];      // Image height
			$image_type 		= $image_size_info['mime']; // Image type 
			
			// Create image from available format
			switch($image_type){
				case 'image/png':
			        $image_res =  imagecreatefrompng($image_temp); 
					break;
				case 'image/gif':
				    $image_res =  imagecreatefromgif($image_temp); break;			
				case 'image/jpeg': case 'image/pjpeg':
				    $image_res = imagecreatefromjpeg($image_temp); break;
				default:
				    $image_res = false;
			}
			

			// Fix image orientation if php_exif extensions available
			if(function_exists('exif_read_data')) {
				
				// Get image EXIF data 
				$exif = exif_read_data($image_temp);
		        
				// Check orientation property
				$orientation = (isset($exif['Orientation'])) ? $exif['Orientation'] : '';
				
				// If image has Incorrect orientation then ix it by rotating image
				if(!empty($orientation) && in_array($orientation, array(3, 6, 8))) {
					if($orientation == 3) {
						$image_res = imagerotate($image_res, 180, 0);
					} elseif($orientation == 6) {
						$image_res = imagerotate($image_res, -90, 0);
					} elseif($orientation == 8) {
						$image_res = imagerotate($image_res, 90, 0);
					}						
				}
			}
			
			// If image is created successfully
			if($image_res){
				
				// Image path
				$image_info = pathinfo($image_name);
				
				// Image extension
				$image_extension = strtolower($image_info["extension"]);
				
				// image uploaded name
				$image_name_only = strtolower($image_info["filename"]);
				
				// Generating a unique name for photo using USER_ID + MD5()->TIME()
				$new_file_name = mt_rand(1000, 9999).$user['idu'].md5(time()).'.'.$image_extension; 
				
				// Image full save path
				$image_save_folder 	= $page_settings['folder_group_cover_photos'] . $new_file_name;	
	
	            // Save image after series of size and resolution fixes
				if(sizeImage($image_res, $image_save_folder, $image_type, $page_settings['max_cover_pics'], $image_width, $image_height, $page_settings['jpeg_quality'] )){	            
					
					// Update cover
					$return = $profile->updateGroupCover($user['idu'],$_POST['group-cover-form-id'],$new_file_name);
				
				    $updated = $new_file_name;
					
				} else {
					$return = $TEXT['_uni-ERROR_WHILE_UPLOADING'];
				}
				
				// Free memory
				imagedestroy($image_res);
				
			} else {
				// Invalid format
				$return = showError($TEXT['_uni-Photo-invalid']);
			}		
		} else {
			// Image is not selected or out of size
			$return = ($image_size > $page_settings['max_img_size']*1000000) ? showError(sprintf($TEXT['_uni-Photo-out_of_size'],$page_settings['max_img_size'])) : showError($TEXT['_uni-Photo-not_selected']);		
		}
	} else {
		// User logged out
		$return = (empty($user['idu'])) ? showError($TEXT['lang_error_connection2']) : showError($TEXT['_uni-No_grp_rights_cvr']);
	}
} else {
	// No credentials set
	$return = showError($TEXT['lang_error_connection2']);
}
?>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<script language="javascript" type="text/javascript">
	window.top.window.resetForm("uGc-2");
</script>

<?php if(isset($updated) && $updated) { ?>
<script language="javascript" type="text/javascript">
	window.top.window.loadImageFull('#group_view_cover_<?php echo $_POST['group-cover-form-id'] ; ?>','<?php echo $TEXT['installation'];?>/index.php?thumb=1&src=<?php echo $updated; ?>&fol=f&w=1093&h=300&q=100',0,1);
</script>
<?php } else { ?>
<script language="javascript" type="text/javascript">
	window.top.window.showModal('<?php echo $db->real_escape_string($return); ?>');
	window.top.window.smartLoader(0,'#btn-cover-chn');
</script>
<?php } 
mysqli_close($db);
?>