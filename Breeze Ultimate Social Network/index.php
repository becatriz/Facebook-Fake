<?php
// Start PHP SESSION
session_start();

if(isset($_GET['browse'])) {
	
	// Pass request to browse.php
	require_once('main/browse.php');

// Else check whether thumb is requested	
} elseif(isset($_GET['thumb'])) {
	
	// Pass request to thumb.php
	require_once('thumb.php');
	
// Else check whether extention process is requested 
} elseif(isset($_GET['extend'])) {
	
	// Pass request to thumb.php
	require_once('main/extend.php');
	
// Else check whether administration page is requested 
} elseif(isset($_GET['new']) && $_GET['new'] == 'page') {
	require_once('main/pages/new_page.php');
} elseif(isset($_GET['admin'])) {
	
	// Pass request to admin.php
	require_once('main/admin.php');
	
// Pass outer requests like account activation or password reset
} elseif(isset($_GET['respond'])) {

    // Pass request to responder.php
	require_once('main/respond.php');
	
} else {	

    // Else check whether post is requested
	if(isset($_GET['post']) && is_numeric($_GET['post'])) {
		
		// Pass request to load.php
		require_once('main/load.php');
	
    // Check whether profile is requested	
	} elseif(isset($_GET['profile'])) {
		
		// Pass request to load.php
		require_once('main/load.php');
		
	} else {
		
		// If nothing is set show homepage
	    require_once('main/home.php');
	
	}
}
?>