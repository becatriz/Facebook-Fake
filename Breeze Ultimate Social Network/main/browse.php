<?php
require_once('./main/config.php');             // Import configuration
require_once('./require/main/database.php');   // Import database connection
require_once('./require/main/classes.php');    // Import all classes
require_once('./require/main/settings.php');   // Import settings
require_once('./language.php');                // Import language

// Check whether requested page exists
if(isset($_GET['browse']) && in_array($_GET['browse'],array('welcome','privacy','terms','developers','libs'))) {
	
	// Display requested page
	echo display('themes/'.$TEXT['theme'].'/html/browse/'.$_GET['browse'].$TEXT['templates_extension']);
	
} else {
	
	// Else display homepage
	echo display('themes/'.$TEXT['theme'].'/html/browse/welcome'.$TEXT['templates_extension']);
	
}	
?>