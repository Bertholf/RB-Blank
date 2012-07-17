<?php
// *************************************************************************************************** //
// Gobble Up The Variables, Set em' Sessions
foreach ($_REQUEST as $key => $value) {
	$_SESSION[$key] = $value;  //$$key = $value;
}

// *************************************************************************************************** //
// Get Search Results

get_header();

if ($_REQUEST["action"] == "search") {
		
	// Do Something
}

get_footer(); 
?>