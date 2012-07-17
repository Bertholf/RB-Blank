<?php 
/*
  Plugin Name: RB BLANK
  Plugin URI: http://rbplugin.com/wordpress/blank/
  Description: A blank plugin for WordPress.
  Author: Rob Bertholf, rob@bertholf.com
  Author URI: http://rbplugin.com/
  Text Domain: rb-blank
  Version: 0.1
*/
$rb_blank_VERSION = "0.1";


// *************************************************************************************************** //
	
	// Kick it off
		if (!session_id())
		session_start();
		
		if ( ! isset($GLOBALS['wp_version']) || version_compare($GLOBALS['wp_version'], '2.8', '<') ) { // if less than 2.8 
			echo "<div class=\"error\" style=\"margin-top:30px;\"><p>This plugin requires WordPress version 2.8 or newer.</p></div>\n";
		return;
		}
	
	// Avoid direct calls to this file, because now WP core and framework has been used
		if ( !function_exists('add_action') ) {
			header('Status: 403 Forbidden');
			header('HTTP/1.1 403 Forbidden');
			exit();
		}
		
	// Plugin Definitions
		define("rb_blank_VERSION", $rb_blank_VERSION); // e.g. 1.0
		define("rb_blank_BASENAME", plugin_basename(__FILE__) );  // rb-blank/rb-blank.php
		$rb_blank_WPURL = get_bloginfo("wpurl"); // http://domain.com/wordpress
		$rb_blank_WPUPLOADARRAY = wp_upload_dir(); // Array  $rb_blank_WPUPLOADARRAY['baseurl'] $rb_blank_WPUPLOADARRAY['basedir']
		define("rb_blank_BASEDIR", get_bloginfo("wpurl") ."/". PLUGINDIR ."/". dirname( plugin_basename(__FILE__) ) ."/" );  // http://domain.com/wordpress/wp-content/plugins/rb-blank/
		define("rb_blank_UPLOADDIR", $rb_blank_WPUPLOADARRAY['baseurl'] );  // http://domain.com/wordpress/wp-content/uploads/blank/
		define("rb_blank_UPLOADPATH", $rb_blank_WPUPLOADARRAY['basedir'] ); // /home/content/99/6048999/html/domain.com/wordpress/wp-content/uploads/profile-media/
		define("rb_blank_TEXTDOMAIN", basename(dirname( __FILE__ )) ); //   rb-blank
	
	// Call Language Options
		add_action("init", "rb_blank_loadtranslation");
			function rb_blank_loadtranslation() {
				load_plugin_textdomain( rb_blank_TEXTDOMAIN, false, basename( dirname( __FILE__ ) ) . "/translation/"); 
			}
		
	// Set Table Names
		if (!defined("table_blank_name1"))
			define("table_blank_name1", "rb_blank_name1");
		if (!defined("table_blank_name2"))
			define("table_blank_name2", "rb_blank_name2");
		if (!defined("table_blank_name3"))
			define("table_blank_name3", "rb_blank_name3");

	// Call default functions
		include_once(dirname(__FILE__).'/functions.php');
	
	// Does it need a diaper change?
		include_once(dirname(__FILE__).'/upgrade.php');

// *************************************************************************************************** //
// Creating tables on plugin activation

	function rb_blank_install() {
		// Required for all WordPress database manipulations
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		// Set Default Options
			$rb_blank_options_arr = array(
				"rb_blank_option_name1" => "",
				"rb_blank_option_name2" => 1,
				"rb_blank_option_name3" => true
				);

		// Update the options in the database
			update_option("rb_blank_options", $rb_blank_options_arr);
			
		// Hold the version in a seprate option
			add_option("rb_blank_version", $rb_blank_VERSION);


		/****************  Create Tables in Database ***************/
	
		// Table Name1
		if ($wpdb->get_var("show tables like '". table_blank_name1 ."'") != table_blank_name1) { // No, Create
			$sql = "CREATE TABLE ". table_blank_name1 ." (
				Name1AutoID BIGINT(20) NOT NULL AUTO_INCREMENT,
				Name1Title VARCHAR(255),
				Name1Text TEXT,
				Name1Type INT(10) NOT NULL DEFAULT '0',
				Name1Image VARCHAR(255),
				Name1Price DECIMAL(12,2),
				Name1DateCreated TIMESTAMP DEFAULT NOW(),
				Name1DateUpdated TIMESTAMP,
				Name1Active INT(10) NOT NULL DEFAULT '0',
				PRIMARY KEY (Name1AutoID)
				);";
			dbDelta($sql);
		}
		
		// Table Name2 (Type)
		if ($wpdb->get_var("show tables like '". table_blank_name2 ."'") != table_blank_name2) { // No, Create
			$sqlTypePayment = "CREATE TABLE ". table_blank_name2 ." (
				Name2AutoID BIGINT(20) NOT NULL AUTO_INCREMENT,
				Name2Title VARCHAR(255),
				Name2Text TEXT,
				PRIMARY KEY (Name2AutoID)
				);";
			dbDelta($sqlTypePayment);
			// Populate table with initial values
			$results = $wpdb->query("INSERT INTO " . table_blank_name2 . " (Name2Title, Name2Text) VALUES ('Value 1 Name','Value 1 Description')");
			$results = $wpdb->query("INSERT INTO " . table_blank_name2 . " (Name2Title, Name2Text) VALUES ('Value 2 Name','Value 2 Description')");
			$results = $wpdb->query("INSERT INTO " . table_blank_name2 . " (Name2Title, Name2Text) VALUES ('Value 3 Name','Value 3 Description')");
		}
	
		// Table Name3 (With Image)
		if ($wpdb->get_var("show tables like '". table_blank_name3 ."'") != table_blank_name3) { // No, Create
			$sqlTypePayment = "CREATE TABLE ". table_blank_name3 ." (
				Name3AutoID BIGINT(20) NOT NULL AUTO_INCREMENT,
				Name3Title VARCHAR(255),
				Name3Text TEXT,
				Name3Image VARCHAR(255),
				Name3Active INT(10) NOT NULL DEFAULT '0',
				PRIMARY KEY (Name2AutoID)
				);";
			dbDelta($sqlTypePayment);
		}

	}
	
	//Activate Install Hook
	register_activation_hook(__FILE__,'rb_blank_install');


// *************************************************************************************************** //
// Register Administrative Settings

	if ( is_admin() ){
	
		/****************  Add Options Page Settings Group ***************/
		
		add_action('admin_init', 'rb_blank_register_settings');
			// Register our Array of settings
			function rb_blank_register_settings() {
				register_setting('rb-blank-settings-group', 'rb_blank_options'); //, 'rb_blank_options_validate'
			}
			
			// Validate/Sanitize Data
			function rb_blank_options_validate($input) {
				// Our first value is either 0 or 1
				//$input['option1'] = ( $input['option1'] == 1 ? 1 : 0 );
				
				// Say our second option must be safe text with no HTML tags
				//$input['sometext'] =  wp_filter_nohtml_kses($input['sometext']);
				
				//return $input;
			}	
	
		
		/****************  Settings in Plugin Page ***********************/
	
		add_action( 'plugins_loaded', 'rb_blank_init' );
			// Initialize Settings
			function rb_blank_init() {
			  if ( is_admin() ){
				add_action('admin_menu', 'rb_blank_addsettingspage');
			  }
			}
			function rb_blank_on_load() {
				add_filter( 'plugin_action_links_' . rb_blank_BASENAME, 'rb_blank_filter_plugin_meta', 10, 2 );  
			}
			
			// Add Link to Admin Client
			function rb_blank_filter_plugin_meta($links, $file) {
				if (empty($links))
					return;
				/* create link */
				if ( $file == rb_blank_BASENAME ) {
					array_unshift(
						$links,
						sprintf( '<a href="tools.php?page=%s">%s</a>', rb_blank_BASENAME, __('Settings') )
					);
				}
				return $links;
			}
			
			function rb_blank_addsettingspage() {
				if ( !current_user_can('update_core') )
					return;
				$pagehook = add_management_page( __("Manage Blanks", rb_blank_TEXTDOMAIN), __("Blanks", rb_blank_TEXTDOMAIN), 'update_core', rb_blank_BASENAME, 'rb_blank_menu_settings', '' );
				add_action( 'load-plugins.php', 'rb_blank_on_load' );
			}

		
	
		/****************  Activate Admin Client Hook ***********************/

		add_action('admin_menu','set_rb_blank_menu');
			//Create Admin Menu
			function set_rb_blank_menu(){
				add_menu_page( __("Blank Plugin", rb_blank_TEXTDOMAIN), __("Blank Plugin", rb_blank_TEXTDOMAIN), 1,"rb_blank_menu","rb_blank_menu_dashboard","div");
				add_submenu_page("rb_blank_menu", __("Overview", rb_blank_TEXTDOMAIN), __("Overview", rb_blank_TEXTDOMAIN), 1,"rb_blank_menu", "rb_blank_menu_dashboard");
				add_submenu_page("rb_blank_menu", __("Manage Thingamajigs", rb_blank_TEXTDOMAIN), __("Thingamajig", rb_blank_TEXTDOMAIN), 7,"rb_blank_thingamajig","rb_blank_menu_thingamajig");
				add_submenu_page("rb_blank_menu", __("Manage ThingwithImage", rb_blank_TEXTDOMAIN), __("ThingwithImage", rb_blank_TEXTDOMAIN), 7,"rb_blank_thingamajigimg","rb_blank_menu_thingamajigimg");
				add_submenu_page("rb_blank_menu", __("Edit Settings", rb_blank_TEXTDOMAIN), __("Settings", rb_blank_TEXTDOMAIN), 7,"rb_blank_settings","rb_blank_menu_settings");
			}
	
			//Pages
			function rb_blank_menu_dashboard(){
				include_once('admin/overview.php');
			}
			function rb_blank_menu_thingamajig(){
				include_once('admin/thingamajig.php');
			}
			function rb_blank_menu_thingamajigimg(){
				include_once('admin/thingamajig-with-image.php');
			}
			function rb_blank_menu_settings(){
				include_once('admin/settings.php');
			}

	
		/****************  Add Custom Meta Box to Pages/Posts  *********/
	
		add_action('admin_menu', 'rb_blank_add_custom_box');
			// Add Custom Meta Box to Posts / Pages
			function rb_blank_add_custom_box() {
			   if( function_exists( 'add_meta_box' )) {
				// Add to Posts
				add_meta_box( 'rb_blank_sectionid', __( 'Insert Shortcode', rb_blank_TEXTDOMAIN), 
							'rb_blank_inner_custom_box', 'post', 'advanced' );
				// Add to Pages
				add_meta_box( 'rb_blank_sectionid', __( 'Insert Shortcode', rb_blank_TEXTDOMAIN), 
							'rb_blank_inner_custom_box', 'page', 'advanced' );
			   } else {
				add_action('dbx_post_advanced', 'rb_blank_old_custom_box' );
				add_action('dbx_page_advanced', 'rb_blank_old_custom_box' );
			  }
			}
		   
			// Prints the inner fields for the custom post/page section
			function rb_blank_inner_custom_box() {
				// Use nonce for verification
				echo '<input type="hidden" name="rb_blank_noncename" id="rb_blank_noncename" value="'. wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
			
				echo "<div class=\"submitbox\" id=\"add_ticket_box\">\n";
				// Add Javascript
				echo "<script type=\"text/javascript\">\n";
				echo "	function rb_blank_insertshortcode(){\n";
				echo "		var $rbblank = jQuery.noConflict();\n";
				echo "		str='';\n";

				echo "		rb_blank_recordid = $rbagency('#rb_blank_recordid').val();\n";
				echo "		if(rb_blank_recordid != '')\n";
				echo "		  str+=' rb_blank_recordid = \"'+rb_blank_recordid+'\"';\n";

				echo "		rb_blank_type = $rbagency('#rb_blank_type').val();\n";
				echo "		if(rb_blank_type != '')\n";
				echo "		  str+=' rb_blank_type = \"'+rb_blank_type+'\"';\n";
			
				echo "		send_to_editor('[blank_detail'+str+']');return;\n";
				echo "	}\n";
				// Second Insert Button
				echo "	function rb_blank_insertshortcodenoval(){\n";
				echo "		send_to_editor('[blank_list]');return;\n";
				echo "	}\n";
				echo "</script>\n";

				echo "<table>\n";
				echo "	<tr><td>". __("Record ID", rb_blank_TEXTDOMAIN) .":</td><td><input type=\"text\" id=\"rb_blank_recordid\" name=\"rb_blank_age_start\" value=\"18\" /></td></tr>\n";
				echo "	<tr><td>Type:</td><td><select id=\"rb_blank_type\" name=\"rb_blank_type\">\n";
						global $wpdb;
						$profileDataTypes = mysql_query("SELECT * FROM ". table_blank_name2 ."");
						echo "<option value=\"\">". __("Any Profile Type", rb_blank_TEXTDOMAIN) ."</option>\n";
						while ($dataType = mysql_fetch_array($profileDataTypes)) {
							echo "<option value=\"". $dataType["Name2AutoID"] ."\">". $dataType["Name2Title"] ."</option>";
						}
						echo "</select></td></tr>\n";
				echo "</table>\n";
				echo "<p><input type=\"button\" onclick=\"rb_blank_insertshortcode()\" value=\"". __("Insert Shortcode With Attributes", rb_blank_TEXTDOMAIN) ."\" /></p>\n";
				echo "<p><input type=\"button\" onclick=\"rb_blank_insertshortcodenoval()\" value=\"". __("Insert Shortcode No Attribute", rb_blank_TEXTDOMAIN) ."\" /></p>\n";
				echo "</div>\n";
			}
			
			/* Prints the edit form for pre-WordPress 2.5 post/page */
			function rb_blank_old_custom_box() {
				echo '<div class="dbx-b-ox-wrapper">' . "\n";
				echo '<fieldset id="rb_blank_fieldsetid" class="dbx-box">' . "\n";
				echo "<div class=\"dbx-h-andle-wrapper\"><h3 class=\"dbx-handle\">". __("Profile", rb_blank_TEXTDOMAIN) ."</h3></div>";   
				echo '<div class="dbx-c-ontent-wrapper"><div class="dbx-content">';
				// output editing form
				rb_blank_inner_custom_box();
				// end wrapper
				echo "</div></div></fieldset></div>\n";
			}
			
	} // End Admin Actions



// *************************************************************************************************** //
// Add Widgets

	// View Record Detail
	add_action('widgets_init', create_function('', 'return register_widget("rb_blank_widget_detail");'));
	  class rb_blank_widget_detail extends WP_Widget {
			
		// Setup
		function rb_blank_widget_detail() {
			$widget_ops = array('classname' => 'rb_blank_widget_detail', 'description' => __("Displays record detail", rb_blank_TEXTDOMAIN) );
			$this->WP_Widget('rb_blank_widget_detail', __("RB Blank Detail", rb_blank_TEXTDOMAIN), $widget_ops);
		}
	
		// What Displays
		function widget($args, $instance) {		
			extract($args, EXTR_SKIP);
			echo $before_widget;
			$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
				if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };		
			$count = $instance['recordid'];
				if ( empty( $recordid ) ) { $recordid = 1; };		
				
			if (function_exists('rb_blank_detail')) { 
				$atts = array('recordid' => $recordid);
				rb_blank_detail($atts); 
			} else {
				echo "Invalid Function";
			}
			echo $after_widget;
		}
	
		// Update
		function update($new_instance, $old_instance) {				
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['recordid'] = strip_tags($new_instance['recordid']);
			return $instance;
		}
	
		// Form
		function form($instance) {				
			$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
			$title = esc_attr($instance['title']);
			$count = esc_attr($instance['count']);
			
			echo "<p><label for=\"". $this->get_field_id('title') ."\">\"". __('Title:') ."\" <input class=\"widefat\" id=\"". $this->get_field_id('title') ."\" name=\"". $this->get_field_name('title') ."\" type=\"text\" value=\"". $title ."\" /></label></p>\n";
			echo "<p><label for=\"". $this->get_field_id('recordid') ."\">\"". __('Show Record ID:') ."\" <input id=\"". $this->get_field_id('recordid') ."\" name=\"". $this->get_field_name('recordid') ."\" type=\"text\" value=\"". $recordid ."\" /></label></p>\n";
		}
		
	  } // End Widget Class


// *************************************************************************************************** //
// Add Short Codes

	// Add [blank_list] shortcode
	add_shortcode("blank_list","rb_blank_shortcode_list");
		function rb_blank_shortcode_list($atts, $content = null){
			ob_start();
			rb_blank_list($atts);
			$output_string = ob_get_contents();;
			ob_end_clean();
			return $output_string;
		}

	// Add [blank_detail id=1] shortcode
	add_shortcode("blank_detail","rb_blank_shortcode_detail");
		function rb_blank_shortcode_detail($atts, $content = null){
			ob_start();
			rb_blank_detail($atts);
			$output_string = ob_get_contents();;
			ob_end_clean();
			return $output_string;
		}



// *************************************************************************************************** //
// Intercept Post Data (Rarely used)
	/*
		//Intercept to check for submitted data via one of the forms or from PayPal
		add_action('init', rb_blank_eval_postdata);
			function rb_blank_eval_postdata() {
				if (isset($_POST['rb_blank_step1']) || isset($_POST['rb_blank_step2'])) {
					include("BLANK.php");
				}
				if (isset($_GET['rb_blank_step3'])) {
					include("BLANK.php");
				}
			}
	*/


/****************************************************************/
//Uninstall
	function rb_blank_uninstall() {
		// Required for all WordPress database manipulations
		global $wpdb;
		
		register_uninstall_hook(__FILE__, 'rb_blank_uninstall_action');
			function rb_blank_uninstall_action() {
				//delete_option('create_my_taxonomies');
			}
	
		// Drop the tables
		$wpdb->query("DROP TABLE " . table_blank_name1);
		$wpdb->query("DROP TABLE " . table_blank_name2);

	
		// Final Cleanup
		delete_option('rb_blank_options');
			
		$thepluginfile="rb-blank/rb-blank.php";
		$current = get_settings('active_plugins');
		array_splice($current, array_search( $thepluginfile, $current), 1 );
		update_option('active_plugins', $current);
		do_action('deactivate_' . $thepluginfile );
	
		echo "<div style=\"padding:50px;font-weight:bold;\"><p>". __("Almost done...", rb_blank_TEXTDOMAIN) ."</p><h1>". __("One More Step", rb_blank_TEXTDOMAIN) ."</h1><a href=\"plugins.php?deactivate=true\">". __("Please click here to complete the uninstallation process", rb_blank_TEXTDOMAIN) ."</a></h1></div>";
		die;
	}
?>