<?php
global $wpdb;

// *************************************************************************************************** //
// Upgrade to 0.2
	if (get_option('rb_blank_version') == "0.1") { 

		// Example Add Fields
		//$results = $wpdb->query("ALTER TABLE ". table_blank_name1 ." ADD Name1Test TEXT");
		
		// Example Change Fields
		//$results = $wpdb->query("ALTER TABLE ". table_blank_name1 ." CHANGE Name1Test Name1TestNew TEXT");
		
		// Updating version number!
		update_option('rb_blank_version', "0.2");
	}


// Ensure directory is setup
if (!is_dir(rb_blank_UPLOADPATH)) {
	mkdir(rb_blank_UPLOADPATH, 0755);
	chmod(rb_blank_UPLOADPATH, 0777);
}
?>