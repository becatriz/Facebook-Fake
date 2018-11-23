<?php 
// Connect database
$db = new mysqli($SET['database_host'], $SET['database_user'], $SET['database_pass'], $SET['database_name']);

if ($db->connect_errno) {

    die('<br><br><div align="center"><h1 style="color:#666;font-weight:normal;">Failed to connect MySQL <span style="font-size:40px;background:#F65868;color:#fff;border-radius:4px;padding:2px;">'.$db->connect_errno.'</span></h1></div>');
	
} else {
	
	// If connected without any errors set DB CHARSET
    $db->set_charset("utf8");	
}
?>