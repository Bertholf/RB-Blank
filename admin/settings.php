<?php
global $wpdb;

// *************************************************************************************************** //
// Top Client

    echo "<div class=\"wrap\">\n";
    echo "  <div id=\"rb-overview-icon\" class=\"icon32\"></div>\n";
    echo "  <h2>". __("Settings", rb_blank_TEXTDOMAIN) . "</h2>\n";
    echo "  <strong>\n";
    echo "  	<a class=\"button-primary\" href=\"". admin_url("admin.php?page=". $_GET["page"] ."&ConfigID=0") ."\">". __("Overview", rb_blank_TEXTDOMAIN) . "</a> | \n";
    echo "  	<a class=\"button-secondary\" href=\"". admin_url("admin.php?page=". $_GET["page"] ."&ConfigID=1") ."\">". __("Features", rb_blank_TEXTDOMAIN) . "</a> | \n";
    echo "  	<a class=\"button-secondary\" href=\"". admin_url("admin.php?page=". $_GET["page"] ."&ConfigID=2") ."\">". __("Types", rb_blank_TEXTDOMAIN) . "</a> | \n";
    echo "  	<a class=\"button-secondary\" href=\"". admin_url("admin.php?page=". $_GET["page"] ."&ConfigID=99") ."\">". __("Uninstall", rb_blank_TEXTDOMAIN) . "</a>\n";
    echo "  </strong>\n";
	echo "  <hr />\n";
  
if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) ) {
	if($_REQUEST['action'] == 'douninstall') {
		rb_blank_uninstall();
	}
}

if(!isset($_REQUEST['ConfigID']) && empty($_REQUEST['ConfigID'])){ $ConfigID=0;} else { $ConfigID=$_REQUEST['ConfigID']; }

if ($ConfigID == 0) {
	
// *************************************************************************************************** //
// Overview Page

    echo "	  <h3>Overview</h3>\n";
    echo "      <ul>\n";
    echo "  	  <li><a href=\"?page=". $_GET["page"] ."&ConfigID=0\">". __("Overview", rb_blank_TEXTDOMAIN) . "</a></li>\n";
    echo "  	  <li><a href=\"?page=". $_GET["page"] ."&ConfigID=1\">". __("Features", rb_blank_TEXTDOMAIN) . "</a></li>\n";
    echo "  	  <li><a href=\"?page=". $_GET["page"] ."&ConfigID=2\">". __("Types", rb_blank_TEXTDOMAIN) . "</a></li>\n";
    echo "  	  <li><a href=\"?page=". $_GET["page"] ."&ConfigID=99\">". __("Uninstall", rb_blank_TEXTDOMAIN) . "</a></li>\n";
    echo "      </ul>\n";

	echo "<hr />\n";
}
elseif ($ConfigID == 1) {

// *************************************************************************************************** //
// Manage Settings

    echo "<h3>". __("Settings", rb_blank_TEXTDOMAIN) . "</h3>\n";

		echo "<form method=\"post\" action=\"options.php\">\n";
		settings_fields( 'rb-blank-settings-group' ); 
        //do_settings( 'rb-blank-settings-group' );
		$rb_blank_options_arr = get_option('rb_blank_options');

		// If Value 1 has no value, add a value... now thats value!
		$rb_blank_option_name1 = $rb_blank_options_arr['rb_blank_option_name1'];
			if (empty($rb_blank_option_name1)) { $rb_blank_option_name1 = "Default Value 1"; }

		// Begin
		echo "<table class=\"form-table\">\n";
		
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" class=\"rblabel\">"; _e('Database Version', rb_blank_TEXTDOMAIN); echo "</th>\n";
		echo "   <td><input name=\"rb_blank_version\" value=\"". rb_blank_VERSION ."\" disabled /></td>\n";
		echo " </tr>\n";
		
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" class=\"rblabel\">". __('Option 1', rb_blank_TEXTDOMAIN); echo "</th>\n";
		echo "   <td><input name=\"rb_blank_options[rb_blank_option_name1]\" value=\"". $rb_blank_option_name1 ."\" class=\"rbformitem rbformtext\" /></td>\n";
		echo " </tr>\n";
		
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" class=\"rblabel\">". __('Option 2', rb_blank_TEXTDOMAIN); echo "</th>\n";
		echo "   <td><input name=\"rb_blank_options[rb_blank_option_name2]\" value=\"". $rb_blank_options_arr['rb_blank_option_name2'] ."\" class=\"rbformitem rbformtext\" /></td>\n";
		echo " </tr>\n";
		
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" class=\"rblabel\">". __('Option 3', rb_blank_TEXTDOMAIN); echo "</th>\n";
		echo "   <td><input name=\"rb_blank_options[rb_blank_option_name3]\" value=\"". $rb_blank_options_arr['rb_blank_option_name3'] ."\" class=\"rbformitem rbformtext\" /></td>\n";
		echo " </tr>\n";
		
		/* Options 
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" colspan=\"2\"><h3>". __('Location Options', rb_blank_TEXTDOMAIN); echo "</h3></th>\n";
		echo " </tr>\n";
		
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" class=\"rblabel\">". __('Default Country', rb_blank_TEXTDOMAIN) ."</th>\n";
		echo "   <td><input name=\"rb_blank_options[rb_blank_option_locationcountry]\" value=\"". $rb_blank_option_locationcountry ."\" class=\"rbforminput\" /></td>\n";
		echo " </tr>\n";
		
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" class=\"rblabel\">". __('Server Timezone', rb_blank_TEXTDOMAIN) ."</th>\n";
		echo "   <td>\n";
		echo "     <select name=\"rb_blank_options[rb_blank_option_locationtimezone]\" class=\"rbformitem rbformselect\">\n";
		echo "       <option value=\"+12\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], +12) ."> UTC+12</option>\n";
		echo "       <option value=\"+11\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], +11) ."> UTC+11</option>\n";
		echo "       <option value=\"+10\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], +10) ."> UTC+10</option>\n";
		echo "       <option value=\"+9\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], +9) ."> UTC+9</option>\n";
		echo "       <option value=\"+8\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], +8) ."> UTC+8</option>\n";
		echo "       <option value=\"+7\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], +7) ."> UTC+7</option>\n";
		echo "       <option value=\"+6\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], +6) ."> UTC+6</option>\n";
		echo "       <option value=\"+5\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], +5) ."> UTC+5</option>\n";
		echo "       <option value=\"+4\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], +4) ."> UTC+4</option>\n";
		echo "       <option value=\"+3\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], +3) ."> UTC+3</option>\n";
		echo "       <option value=\"+2\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], +2) ."> UTC+2</option>\n";
		echo "       <option value=\"+1\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], +1) ."> UTC+1</option>\n";
		echo "       <option value=\"0\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], 0) ."> UTC 0</option>\n";
		echo "       <option value=\"-1\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], -1) ."> UTC-1</option>\n";
		echo "       <option value=\"-2\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], -2) ."> UTC-2</option>\n";
		echo "       <option value=\"-3\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], -3) ."> UTC-3</option>\n";
		echo "       <option value=\"-4\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], -4) ."> UTC-4</option>\n";
		echo "       <option value=\"-5\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], -5) ."> UTC-5</option>\n";
		echo "       <option value=\"-6\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], -6) ."> UTC-6</option>\n";
		echo "       <option value=\"-7\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], -7) ."> UTC-7</option>\n";
		echo "       <option value=\"-8\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], -8) ."> UTC-8</option>\n";
		echo "       <option value=\"-9\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], -9) ."> UTC-9</option>\n";
		echo "       <option value=\"-10\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], -10) ."> UTC-10</option>\n";
		echo "       <option value=\"-11\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], -11) ."> UTC-11</option>\n";
		echo "       <option value=\"-12\" ". selected($rb_blank_options_arr['rb_blank_option_locationtimezone'], -12) ."> UTC-12</option>\n";
		echo "     </select> (<a href=\"http://www.worldtimezone.com/index24.php\" target=\"_blank\">Find</a>)\n";
		echo "   </td>\n";
		echo " </tr>\n";
		
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" class=\"rblabel\">". __('Show Checkbox', rb_blank_TEXTDOMAIN) ."</th>\n";
		echo "   <td>\n";
		echo "     <input type=\"checkbox\" name=\"rb_blank_options[rb_blank_option_showcheckbox]\" value=\"1\" "; checked($rb_blank_options_arr['rb_blank_option_showcheckbox'], 1); echo "/> Example Checkbox<br />\n";
		echo "   </td>\n";
		echo " </tr>\n";
		*/
		
		
		echo "</table>\n";
		echo "<input type=\"submit\" class=\"button-primary\" value=\""; _e('Save Changes'); echo "\" />\n";
		
		echo "</form>\n";


}	 // End	
elseif ($ConfigID == 2) {
// *************************************************************************************************** //
// Manage Types

	/** Identify Labels **/
	define("LabelPlural", __("Types", rb_blank_TEXTDOMAIN));
	define("LabelSingular", __("Type", rb_blank_TEXTDOMAIN));

  /* Initial Registration [RESPOND TO POST] ***********/ 
  if ( isset($_POST['action']) ) {
	
		$Name2AutoID 	= $_POST['Name2AutoID'];
		$Name2Title 	= $_POST['Name2Title'];
		$Name2Text 		= $_POST['Name2Text'];

		// Error checking
		$error = "";
		$have_error = false;
		if(trim($Name2Title) == ""){
			$error .= "<b><i>". __(LabelSingular ." name is required", rb_blank_TEXTDOMAIN) . ".</i></b><br>";
			$have_error = true;
		}

		$action = $_POST['action'];
		switch($action) {
	
		// Add
		case 'addRecord':
			if($have_error){
				echo ("<div id=\"message\" class=\"error\"><p>". sprintf(__("Error creating %1$s, please ensure you have filled out all required fields", rb_blank_TEXTDOMAIN), LabelPlural) .".</p><p>".$error."</p></div>"); 
			} else {
		
				// Create Record
				$insert = "INSERT INTO ". table_blank_name2 ." (Name2Title, Name2Text) VALUES ('" . $wpdb->escape($Name2Title) . "', '" . $wpdb->escape($Name2Text) . "')";
				$results = $wpdb->query($insert);
				$lastid = $wpdb->insert_id;
				
				echo ("<div id=\"message\" class=\"updated\"><p>". sprintf(__("%1$s <strong>added</strong> successfully! You may now %1$s Load Information to the record", rb_blank_TEXTDOMAIN), LabelSingular, "<a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&action=editRecord&LoginTypeID=". $lastid ."\">") .".</a></p><p>".$error."</p></div>"); 
			}

		break;
	
		// Manage
		case 'editRecord':
			if($have_error){
				echo ("<div id=\"message\" class=\"error\"><p>". sprintf(__("Error creating %1$s, please ensure you have filled out all required fields", rb_blank_TEXTDOMAIN), LabelPlural) .".</p><p>".$error."</p></div>"); 
			} else {
				$update = "UPDATE ". table_blank_name2 ." 
							SET 
								Name2Title='". $wpdb->escape($Name2Title) ."', 
								Name2Text='". $wpdb->escape($Name2Text) ."'
							WHERE Name2AutoID='$Name2AutoID'";
				$updated = $wpdb->query($update);

				echo ("<div id=\"message\" class=\"updated\"><p>". sprintf(__("%1$s <strong>updated</strong> successfully", rb_blank_TEXTDOMAIN), LabelSingular) ."!</p><p>".$error."</p></div>"); 
		
				// Clear It
				$Name2AutoID	= 0;
				$Name2Title		= "";
				$Name2Text		= "";
			}
		break;

		// Delete bulk
		case 'deleteRecord':
			foreach($_POST as $Name2AutoID) {
			  if (is_numeric($Name2AutoID)) {
				// Verify Record
				$queryDelete = "SELECT Name2AutoID, Name2Title FROM ". table_blank_name2 ." WHERE Name2AutoID =  \"". $Name2AutoID ."\"";
				$resultsDelete = mysql_query($queryDelete);
				while ($dataDelete = mysql_fetch_array($resultsDelete)) {
			
					// Remove Record
					$delete = "DELETE FROM " . table_blank_name2 . " WHERE Name2AutoID = \"". $Name2AutoID ."\"";
					$results = $wpdb->query($delete);
					
					echo "<div id=\"message\" class=\"updated\"><p>". __(LabelSingular ." <strong>". $dataDelete['Name2Title'] ."</strong> deleted successfully", rb_blank_TEXTDOMAIN) ."!</p></div>\n";
						
				} // while
			  } // it was numeric
			} // for each
		break;

		} // Switch
		
  } // Action Post
  elseif ($_GET['action'] == "deleteRecord") {
	
	$Name2AutoID = $_GET['Name2AutoID'];

	  if (is_numeric($Name2AutoID)) {
		// Verify Record
		$queryDelete = "SELECT Name2AutoID, Name2Title FROM ". table_blank_name2 ." WHERE Name2AutoID =  \"". $Name2AutoID ."\"";
		$resultsDelete = mysql_query($queryDelete);
		while ($dataDelete = mysql_fetch_array($resultsDelete)) {
	
			// Remove Record
			$delete = "DELETE FROM " . table_blank_name2 . " WHERE Name2AutoID = \"". $Name2AutoID ."\"";
			$results = $wpdb->query($delete);
			
			echo "<div id=\"message\" class=\"updated\"><p>". __(LabelSingular ." <strong>". $dataDelete['Name2Title'] ."</strong> deleted successfully", rb_blank_TEXTDOMAIN) ."!</p></div>\n";
				
		} // is there record?
	  } // it was numeric
  }
  elseif ($_GET['action'] == "editRecord") {

		$action = $_GET['action'];
		$Name2AutoID = $_GET['Name2AutoID'];
		
		if ( $Name2AutoID > 0) {

			$query = "SELECT * FROM ". table_blank_name2 ." WHERE Name2AutoID='$Name2AutoID'";
			$results = mysql_query($query) or die (__('Error, query failed', rb_blank_TEXTDOMAIN));
			$count = mysql_num_rows($results);
			while ($data = mysql_fetch_array($results)) {
				$Name2AutoID	=$data['Name2AutoID'];
				$Name2Title		=stripslashes($data['Name2Title']);
			} 
		
  			echo "<p><a class=\"button-secondary\" href=\"". admin_url("admin.php?page=". $_GET["page"] ."&ConfigID=2") ."\">". __("Create New ". LabelSingular, rb_blank_TEXTDOMAIN) ."</a></p>\n";
			echo "<h3 class=\"title\">". sprintf(__("Edit %1$s", rb_blank_TEXTDOMAIN), LabelPlural) ."</h3>\n";
			echo "<p>". sprintf(__("Fill in the form below to add a new record %1$s", rb_blank_TEXTDOMAIN), LabelPlural) .". <strong>". __("Required fields are marked", rb_blank_TEXTDOMAIN) ." *</strong></p>\n";
		}
  } else {
		
			$Name2AutoID	= 0;
			$Name2Title		= "";
			$Name2Text		= "";
			
			echo "<h3>". sprintf(__("Create New %1$s", rb_blank_TEXTDOMAIN), LabelPlural) ."</h3>\n";
			echo "<p>". __("Make changes in the form below to edit a ", rb_blank_TEXTDOMAIN) ." ". LabelSingular .". <strong>". __("Required fields are marked", rb_blank_TEXTDOMAIN) ." *</strong></p>\n";
  }
  
	echo "<form method=\"post\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
	echo "<table class=\"form-table\">\n";
	echo "<tbody>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Title", rb_blank_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"Name2Title\" name=\"Name2Title\" value=\"". $Name2Title ."\" /></td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Description", rb_blank_TEXTDOMAIN) .":</th>\n";
	echo "        <td><textarea id=\"Name1Text\" name=\"Name2Text\" class=\"rbformitem rbformtextarea\">". $Name2Text ."</textarea></td>\n";
	echo "    </tr>\n";
	echo "  </tbody>\n";
	echo "</table>\n";

	if ( $Name2AutoID > 0) {
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"Name2AutoID\" value=\"". $Name2AutoID ."\" />\n";
	echo "     <input type=\"hidden\" name=\"ConfigID\" value=\"". $ConfigID ."\" />\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"editRecord\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Update Record", rb_blank_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	} else {
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"addRecord\" />\n";
	echo "     <input type=\"hidden\" name=\"ConfigID\" value=\"". $ConfigID ."\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Create Record", rb_blank_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	} 
	echo "</form>\n";
	

	echo "<hr />\n";
	echo "<h3 class=\"title\">". __("All Records", rb_blank_TEXTDOMAIN) ."</h3>\n";
	
		/******** Sort Order ************/
		$sort = "";
		if (isset($_GET['sort']) && !empty($_GET['sort'])){
			$sort = $_GET['sort'];
		}
		else {
			$sort = "Name2Title";
		}
		
		/******** Direction ************/
		$dir = "";
		if (isset($_GET['dir']) && !empty($_GET['dir'])){
			$dir = $_GET['dir'];
			if ($dir == "desc" || !isset($dir) || empty($dir)){
			   $sortDirection = "asc";
			   } else {
			   $sortDirection = "desc";
			} 
		} else {
			   $sortDirection = "desc";
			   $dir = "asc";
		}
	
		echo "<form method=\"post\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";	
		echo "<table cellspacing=\"0\" class=\"widefat fixed\">\n";
		echo "<thead>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\"manage-column column cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&sort=Name2Title&dir=". $sortDirection ."&ConfigID=". $ConfigID ."\">". __("Title", rb_blank_TEXTDOMAIN) ."</a></th>\n";
		echo "    </tr>\n";
		echo "</thead>\n";
		echo "<tfoot>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\" columnmanage-column cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Title", rb_blank_TEXTDOMAIN) ."</th>\n";
		echo "    </tr>\n";
		echo "</tfoot>\n";
		echo "<tbody>\n";
	
		$query = "SELECT * FROM ". table_blank_name2 ." ORDER BY $sort $dir";
		$results = mysql_query($query) or die ( __("Error, query failed", rb_blank_TEXTDOMAIN ));
		$count = mysql_num_rows($results);
		while ($data = mysql_fetch_array($results)) {
			$Name2AutoID	=$data['Name2AutoID'];
		echo "    <tr>\n";
		echo "        <th class=\"check-column\" scope=\"row\"><input type=\"checkbox\" class=\"administrator\" id=\"". $Name2AutoID ."\" name=\"". $Name2AutoID ."\" value=\"". $Name2AutoID ."\" /></th>\n";
		echo "        <td class=\"column\">". stripslashes($data['Name2Title']) ."\n";
		echo "          <div class=\"row-actions\">\n";
		echo "            <span class=\"edit\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;action=editRecord&amp;Name2AutoID=". $Name2AutoID ."&amp;ConfigID=". $ConfigID ."\" title=\"". __("Edit this Record", rb_blank_TEXTDOMAIN) . "\">". __("Edit", rb_blank_TEXTDOMAIN) . "</a> | </span>\n";
		echo "            <span class=\"delete\"><a class=\"submitdelete\" href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;action=deleteRecord&amp;Name2AutoID=". $Name2AutoID ."&amp;ConfigID=". $ConfigID ."\"  onclick=\"if ( confirm('". __("You are about to delete this ". LabelSingular, rb_blank_TEXTDOMAIN) . ".\'". __("Cancel", rb_blank_TEXTDOMAIN) . "\' ". __("to stop", rb_blank_TEXTDOMAIN) . ", \'". __("OK", rb_blank_TEXTDOMAIN) . "\' ". __("to delete", rb_blank_TEXTDOMAIN) . ".') ) { return true;}return false;\" title=\"". __("Delete this Record", rb_blank_TEXTDOMAIN) . "\">". __("Delete", rb_blank_TEXTDOMAIN) . "</a> </span>\n";
		echo "          </div>\n";
		echo "        </td>\n";
		echo "    </tr>\n";
		}
		mysql_free_result($results);
		if ($count < 1) {
		echo "    <tr>\n";
		echo "        <td class=\"check-column\" scope=\"row\"></th>\n";
		echo "        <td class=\"column\" colspan=\"2\"><p>". __("There aren't any records loaded yet", rb_blank_TEXTDOMAIN) . "!</p></td>\n";
		echo "    </tr>\n";
		}
		echo "</tbody>\n";
		echo "</table>\n";
		echo "<p class=\"submit\">\n";
		echo "    <input type=\"hidden\" name=\"action\" value=\"deleteRecord\" />\n";
		echo "    <input type=\"submit\" name=\"submit\" value=\"". __("Delete", rb_blank_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
		echo "</p>\n";
		
   		echo "</form>\n";


}	 // End	
elseif ($ConfigID == 99) {
	
	echo "    <h3>". __("Uninstall", rb_blank_TEXTDOMAIN) ."</h3>\n";
	echo "    <div>". __("Are you sure you want to uninstall?", rb_blank_TEXTDOMAIN) ."</div>\n";
	echo "	<div><a href=\"?page=". $_GET["page"] ."&action=douninstall\">". __("Yes! Uninstall", rb_blank_TEXTDOMAIN) ."</a></div>\n";

}	 // End	
echo "</div>\n";
?>