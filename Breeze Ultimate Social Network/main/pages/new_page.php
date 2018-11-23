<?php
require_once('./main/config.php');             // Import configuration
require_once('./require/main/database.php');   // Import database connection
require_once('./require/main/classes.php');    // Import all classes
require_once('./require/main/settings.php');   // Import settings
require_once('./language.php');                // Import language

// Import user management class
$profile = new main();
$profile->db = $db;	
$profile->settings = $page_settings;

// Check logged user
if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
    
	// Pass user properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];

	// Try fetching logged user
	$user = $profile->getUser();
	
	// If user is logged and exists
	if(!empty($user['idu'])) {
		
		// Generate Main body with navigation
		$TEXT['page_navigation'] = $profile->genNavigation($user,1);
		
		// Input
		$sprint_input = '<div class="brz-padding%s"><input id="%s" class="nav-item-text-inverse brz-border brz-padding-tiny brz-text-grey brz-small brz-card brz-full" value="" placeholder="%s"></div>';
		$sprint_btn = '<div class="brz-padding-tiny">
		                    <div class="brz-small">
							    '.sprintf($TEXT['_uni-Create_a_page_start_ttl'],$TEXT['web_name']).'
							</div>
						<div class="">
							<span onclick="createPage(%s);" id="settings-content-save-%s" class="brz-round brz-padding-tiny2 brz-hover-blue-hd brz-cursor-pointer brz-tag brz-blue brz-tiny-2 brz-text-white brz-text-bold">'.$TEXT['_uni-Get_started'].'</span>
						    <span class="brz-right" id="settings-content-space-%s"></span>
						    <div id="settings-content-mess-%s"></div>
						</div>
					</div>';
		
		// Get categories
		list($institutes, $brands, $artists, $entertainment, $communities) = $profile->getCategories();
		
		// Start body
		$TEXT['page_mainbody'] = '<script>document.title = \''.$TEXT['_uni-Create_a_page'].'\';</script><div id="content-body" name="content-body" class="brz-main brz-clear" style="margin-left:0px;margin-top:44px;width:100%;max-width:1150px;">';
		
		// Add wizards
		$TEXT['page_mainbody'] .= '<div class="brz-white brz-padding-large">
		    <div class="brz-padding-body-create-page"> 
			    
				<div class="brz-container">
				<div class="brz-padding brz-container">
				    <img class="brz-img-page-search" src="'.$TEXT['DATA-IMG-4'].'"> 
					<span class="brz-medium brz-text-bold">'.$TEXT['_uni-Create_a_page'].'</span>
				</div>
				<div class="brz-padding brz-container">
					<span class="brz-small brz-text-super-grey">'.sprintf($TEXT['_uni-Create_a_page_note'],$TEXT['web_name']).'</span>
				</div>				
				</div>
				
				<div class="brz-clear">
					<div class="brz-container brz-full">
						<div class="brz-padding brz-page-third brz-page-wrap">
						    <div style="overflow:hidden;" class="brz-container brz-page-type brz-page-container brz-full brz-border-bold brz-border-body-it"> 
								<div class="brz-padding brz-cursor-pointer brz-center brz-page-container2 brz-hover-body-it brz-body-it-3">  
									<div class="brz-padding-32">
									    <img src="'.$TEXT['installation'].'/themes/'.$TEXT['theme'].'/img/icons/page/store.png" class="brz-page-type-icon" >
									    <div class="brz-page-font brz-padding-top">'.$TEXT['_uni-Page_categories-Local_Place'].'</div>
									</div>
								</div>
								<div class="brz-padding brz-page-container3 brz-page-container2 brz-white">
								    <div class="brz-medium brz-text-bold brz-padding-tiny">'.$TEXT['_uni-Page_categories-Local_Place'].'</div>
									'.sprintf($sprint_input,'-tiny','page_name_1',$TEXT['_uni-Pag_name_plc']).'
									'.sprintf($sprint_input,'-tiny','page_category',$TEXT['_uni-Pag_cat_plc']).'
									'.sprintf($sprint_input,'-tiny','page_address',$TEXT['_uni-Pag_addr_plc']).'		
									'.sprintf($sprint_btn,'1','1','1','1').'
								</div>
							</div>
						</div>
						<div class="brz-padding brz-page-third brz-page-wrap">
							<div class="brz-container brz-page-type brz-page-container brz-full brz-border-bold brz-border-body-it"> 
								<div class="brz-padding brz-cursor-pointer respect-overflow brz-center brz-page-container2 brz-hover-body-it brz-body-it-3">  
									<div class="brz-padding-32">
									    <img src="'.$TEXT['installation'].'/themes/'.$TEXT['theme'].'/img/icons/page/company.png" class="brz-page-type-icon" >
									    <div class="brz-page-font brz-padding-top">'.$TEXT['_uni-Page_categories-institute'].'</div>
									</div>
								</div>
								<div class="brz-padding brz-page-container3 brz-page-container2 brz-white">    
									<div class="brz-medium brz-text-bold brz-padding-tiny">'.$TEXT['_uni-Page_categories-institute'].'</div>
									<div class="brz-padding-tiny brz-clear">
										<select id="pag_cat_2" class="brz-full small wide brz-small">
											<option value="" class="brz-text-bold brz-text-red" disabled selected>'.$TEXT['_uni-Create_a_page_cat_chs'].'</option>
											'.getPageSelects($institutes).'	
										</select> 
									</div> 								
									'.sprintf($sprint_input,'-top brz-padding-tiny','page_name_2',$TEXT['_uni-Pag_name_plc']).'
									'.sprintf($sprint_btn,'2','2','2','2').'									
								</div>
							</div>
						</div>								
						<div class="brz-padding brz-page-third brz-page-wrap">
						    <div style="overflow:hidden;" class="brz-container brz-page-type brz-page-container brz-full brz-border-bold brz-border-body-it"> 
								<div class="brz-padding brz-cursor-pointer brz-center brz-page-container2 brz-hover-body-it brz-body-it-3">  
									<div class="brz-padding-32">
									    <img src="'.$TEXT['installation'].'/themes/'.$TEXT['theme'].'/img/icons/page/brand.png" class="brz-page-type-icon" >
									    <div class="brz-page-font brz-padding-top">'.$TEXT['_uni-Page_categories-brand'].'</div>
									</div>
								</div>
								<div class="brz-padding brz-page-container3 brz-page-container2 brz-white">				    
									<div class="brz-medium brz-text-bold brz-padding-tiny">'.$TEXT['_uni-Page_categories-brand'].'</div>
									<div class="brz-padding-tiny brz-clear">
										<select id="pag_cat_3" class="brz-full small wide brz-small">
											<option value="" class="brz-text-bold brz-text-red" disabled selected>'.$TEXT['_uni-Create_a_page_cat_chs'].'</option>									
											'.getPageSelects($brands).'
											</select> 
									</div> 
									'.sprintf($sprint_input,'-top brz-padding-tiny','page_name_3',$TEXT['_uni-Pag_name_plc']).'
									'.sprintf($sprint_btn,'3','3','3','3').'
								</div>
							</div>
						</div>
						<div class="brz-padding brz-page-third brz-page-wrap">
						    <div style="overflow:hidden;" class="brz-container brz-page-type brz-page-container brz-full brz-border-bold brz-border-body-it"> 
								<div class="brz-padding brz-cursor-pointer brz-center brz-page-container2 brz-hover-body-it brz-body-it-3">  
									<div class="brz-padding-32">
									    <img src="'.$TEXT['installation'].'/themes/'.$TEXT['theme'].'/img/icons/page/artist.png" class="brz-page-type-icon" >
									    <div class="brz-page-font brz-padding-top">'.$TEXT['_uni-Page_categories-Public_Figure'].'</div>
									</div>
								</div>
								<div class="brz-padding brz-page-container3 brz-page-container2 brz-white">								    
									<div class="brz-medium brz-text-bold brz-padding-tiny">'.$TEXT['_uni-Page_categories-Public_Figure'].'</div>
									<div class="brz-padding-tiny brz-clear">
										<select id="pag_cat_4" class="brz-full small wide brz-small">
											<option value="" class="brz-text-bold brz-text-red" disabled selected>'.$TEXT['_uni-Create_a_page_cat_chs'].'</option>											
											'.getPageSelects($artists).'											
										</select> 
									</div> 									
									'.sprintf($sprint_input,'-top brz-padding-tiny','page_name_4',$TEXT['_uni-Pag_name_plc']).'									
									'.sprintf($sprint_btn,'4','4','4','4').'									
								</div>
							</div>
						</div>						
						<div class="brz-padding brz-page-third brz-page-wrap">
						    <div style="overflow:hidden;" class="brz-container brz-page-type brz-page-container brz-full brz-border-bold brz-border-body-it"> 
								<div class="brz-padding brz-cursor-pointer brz-center brz-page-container2 brz-hover-body-it brz-body-it-3">  
									<div class="brz-padding-32">
									    <img src="'.$TEXT['installation'].'/themes/'.$TEXT['theme'].'/img/icons/page/ticket.png" class="brz-page-type-icon" >
									    <div class="brz-page-font brz-padding-top">'.$TEXT['_uni-Page_categories-Entertainment'].'</div>
									</div>
								</div>
								<div class="brz-padding brz-page-container3 brz-page-container2 brz-white">								    
									<div class="brz-medium brz-text-bold brz-padding-tiny">'.$TEXT['_uni-Page_categories-Entertainment'].'</div>
									<div class="brz-padding-tiny brz-clear">
										<select id="pag_cat_5" class="brz-full small wide brz-small">
											<option value="" class="brz-text-bold brz-text-red" disabled selected>'.$TEXT['_uni-Create_a_page_cat_chs'].'</option>											
											'.getPageSelects($entertainment).'											
										</select> 
									</div> 									
									'.sprintf($sprint_input,'-top brz-padding-tiny','page_name_5',$TEXT['_uni-Pag_name_plc']).'									
									'.sprintf($sprint_btn,'5','5','5','5').'									
								</div>
							</div>
						</div>							
						<div class="brz-padding brz-page-third brz-page-wrap">
						    <div style="overflow:hidden;"class="brz-container brz-page-type brz-page-container brz-full brz-border-bold brz-border-body-it"> 
								<div class="brz-padding brz-cursor-pointer brz-center brz-page-container2 brz-hover-body-it brz-body-it-3">  
									<div class="brz-padding-32">
									    <img src="'.$TEXT['installation'].'/themes/'.$TEXT['theme'].'/img/icons/page/placard.png" class="brz-page-type-icon" >
									    <div class="brz-page-font brz-padding-top">'.$TEXT['_uni-Page_categories-Cause_Community'].'</div>
									</div>
								</div>
								<div class="brz-padding brz-page-container3 brz-page-container2 brz-white">								    
									<div class="brz-medium brz-text-bold brz-padding-tiny">'.$TEXT['_uni-Page_categories-Cause_Community'].'</div>
									<div class="brz-padding-tiny brz-clear">
										<select id="pag_cat_6" class="brz-full small wide brz-small">
											<option value="" class="brz-text-bold brz-text-red" disabled selected>'.$TEXT['_uni-Create_a_page_cat_chs'].'</option>
											'.getPageSelects($communities).'											
										</select> 
									</div> 									
									'.sprintf($sprint_input,'-top brz-padding-tiny','page_name_6',$TEXT['_uni-Pag_name_plc']).'									
									'.sprintf($sprint_btn,'6','6','6','6').'									
								</div>
							</div>
						</div>												
					</div>									    								
				</div>		    			
			</div>			
		</div>';
		
		// End body
		$TEXT['page_mainbody'] .= '</div>';

		// Display
	    echo display('themes/'.$TEXT['theme'].'/html/main/main'.$TEXT['templates_extension']);

		// Add notifications type
		require_once('./require/requests/content/add_notifications_type.php');
		echo $function = notifications($user['n_type'],'/require/requests/content/active_notifications.php','/require/requests/content/active_inbox.php') ;
		
    // Display homepage(WRONG COOKIES SET)
	} else {		
		$need_home = 1;
	}
	
} else {
	$need_home = 1;	
}

if(isset($need_home) && $need_home) {
	
	// Get recent logins
	$TEXT['content_main_page'] = (isset($_COOKIE['loggedout'])) ? $profile->getRecentLogins($_COOKIE['loggedout']):$TEXT['content_main_page'];
    
	// Display homepage
    echo display('themes/'.$TEXT['theme'].'/html/home/home'.$TEXT['templates_extension']);

}

// Refresh all JS PLUGINS
echo '<script>refreshElements();</script>' ;
?>