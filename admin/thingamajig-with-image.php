<?php 
global $wpdb;
define("LabelPlural", "Thingamajigs");
define("LabelSingular", "Thingamajig");

if (isset($_POST['action'])) {

	$Name1AutoID		=$_POST['Name1AutoID'];
	$Name1Title			=$_POST['Name1Title'];
	$Name1Text			=$_POST['Name1Text'];
	$Name1Type			=$_POST['Name1Type'];
	$Name1Price			=$_POST['Name1Price'];
	$Name1DateCreated	=$_POST['Name1DateCreated'];
	$Name1DateUpdated	=$_POST['Name1DateUpdated'];
	$Name1Active		=$_POST['Name1Active'];

	// Error checking
	$error = "";
	$have_error = false;
	if(trim($Name1Title) == ""){
		$error .= "<b><i>". LabelSingular ." Name is required</i></b><br>";
		$have_error = true;
	}

	// Add Files
	if($_FILES['Name1Image']['tmp_name'] != ""){
		// Might as well grab the file type while we are at it
		if ($_FILES['Name1Image']['type'] == "image/jpeg") { $file_extension = ".jpg"; } 
		elseif ($_FILES['Name1Image']['type'] == "image/gif") { $file_extension = ".gif"; } 
		elseif ($_FILES['Name1Image']['type'] == "image/png"){ $file_extension = ".png"; }
		else {
			$error .= "<b><i>". __("Please upload an image file only", rb_blank_TEXTDOMAIN) . "</i></b><br />";
			$have_error = true;
		}
	}
	
	$action = $_POST['action'];
	switch($action) {

	// Add
	case 'addRecord':
	
		if($have_error){
        	echo "<div id=\"message\" class=\"error\"><p>". __("Error creating ". LabelSingular, rb_blank_TEXTDOMAIN) ."., ". __("please ensure you have filled out all required fields", rb_blank_TEXTDOMAIN) .".</p><p>".$error."</p></div>\n"; 
		} else { // Good to go...

			// Is there an Image to Upload 
			if ($_FILES['Name1Image']['tmp_name'] != "") {
				$Name1Image_FileName = rb_blank_safename($_FILES['Name1Image']['tmp_name'])."-". rb_blank_random() . $file_extension;
				if(move_uploaded_file($_FILES['Name1Image']['tmp_name'], rb_blank_UPLOADPATH ."/". $Name1Image_FileName)){
					$Name1Image = $Name1Image_FileName;
				} else { 
					$Name1Image = "";
				}
			}

			// Create Record
			$insert = "INSERT INTO " . table_blank_name1 .
				" (Name1Title, Name1Text, Name1Type, Name1Image, Name1Price, Name1DateUpdated, Name1Active)" .
				"VALUES ('" . $wpdb->escape($Name1Title) . "','" . $wpdb->escape($Name1Text) . "','" . $wpdb->escape($Name1Type) . "','" . $wpdb->escape($Name1Image) . "','" . $wpdb->escape($Name1Price) . "','" . $wpdb->escape($Name1DateUpdated) . "','" . $wpdb->escape($Name1Active) . "')";
		    $results = $wpdb->query($insert);
			$Name1AutoID = $wpdb->insert_id;

        	echo "<div id=\"message\" class=\"updated\"><p>". __("New ". LabelSingular ." added successfully", rb_blank_TEXTDOMAIN) ."!  <a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&action=editRecord&Name1AutoID=". $Name1AutoID ."\">". __("You may now load information to the record", rb_blank_TEXTDOMAIN) ."</a>.</p></div>\n"; 
		}
		rb_display_list();
		exit;
	break;
	
	// Edit
	case 'editRecord':
		if($have_error || empty($Name1AutoID)){
        	echo "<div id=\"message\" class=\"error\"><p>". __("Error creating ". LabelSingular, rb_blank_TEXTDOMAIN) .", ". __("please ensure you have filled out all required fields", rb_blank_TEXTDOMAIN) .".</p><p>".$error."</p></div>\n"; 
		} else { // Good to go...

			// Is there an Name1Image to Upload 
			if ($_FILES['Name1Image']['tmp_name'] != "") {
				$Name1Image_FileName = rb_blank_safename($_FILES['Name1Image']['tmp_name'])."-".rb_blank_random().$file_extension;
				if(move_uploaded_file($_FILES['Name1Image']['tmp_name'], rb_blank_UPLOADPATH ."/". $Name1Image_FileName)){
					$Name1Image = $Name1Image_FileName;
					$update = "UPDATE " . table_blank_name1 . " SET Name1Image ='" . $wpdb->escape($Name1Image) . "' WHERE Name1AutoID=$Name1AutoID";
					$results = $wpdb->query($update);
				}
			}

			// Update Record
			$update = "UPDATE " . table_blank_name1 . " SET 
				Name1Title='" . $wpdb->escape($Name1Title) . "',
				Name1Text='" . $wpdb->escape($Name1Text) . "',
				Name1Type='" . $wpdb->escape($Name1Type) . "',
				Name1Price='" . $wpdb->escape($Name1Price) . "',
				Name1DateCreated='" . $wpdb->escape($Name1DateCreated) . "',
				Name1DateUpdated='" . $wpdb->escape($Name1DateUpdated) . "',
				Name1Active='" . $wpdb->escape($Name1Active) . "'
				WHERE Name1AutoID=$Name1AutoID";
			$results = $wpdb->query($update);

		  echo "<div id=\"message\" class=\"updated\"><p>". __(LabelSingular ." updated successfully", rb_blank_TEXTDOMAIN) ."!</p></div>\n";
		}
		
		rb_display_list();
		exit;
	break;

	// Delete bulk
	case 'deleteRecord':
		foreach($_POST as $Name1AutoID) {
		  if (is_numeric($Name1AutoID)) {
			// Verify Record
			$queryDelete = "SELECT Name1AutoID, Name1Title FROM ". table_blank_name1 ." WHERE Name1AutoID =  \"". $Name1AutoID ."\"";
			$resultsDelete = mysql_query($queryDelete);
			while ($dataDelete = mysql_fetch_array($resultsDelete)) {
		
				// Remove Record
				$delete = "DELETE FROM " . table_blank_name1 . " WHERE Name1AutoID = \"". $Name1AutoID ."\"";
				$results = $wpdb->query($delete);
				
			echo "<div id=\"message\" class=\"updated\"><p>". __(LabelSingular ." <strong>". $dataDelete['Name1Title'] ."</strong> deleted successfully", rb_blank_TEXTDOMAIN) ."!</p></div>\n";
					
			} // is there record?
		  } // it was numeric
		}
		rb_display_list();
		exit;
	break;
	
	}
}
elseif ($_GET['action'] == "deleteImage") {
	$Name1AutoID = $_GET['Name1AutoID'];

	// Verify Record
	$update = "UPDATE ". table_blank_name1 ." SET Name1Image = NULL WHERE Name1AutoID=$Name1AutoID";
	$results = $wpdb->query($update);

	  echo "<div id=\"message\" class=\"updated\"><p>". __("Unlinked image from ". LabelSingular ." successfully", rb_blank_TEXTDOMAIN) ."!</p></div>\n";

	rb_display_list();

}
elseif ($_GET['action'] == "deleteRecord") {
	$Name1AutoID = $_GET['Name1AutoID'];
	if (is_numeric($Name1AutoID)) {
		// Verify Record
		$queryDelete = "SELECT Name1AutoID, Name1Title FROM ". table_blank_name1 ." WHERE Name1AutoID =  \"". $Name1AutoID ."\"";
		$resultsDelete = mysql_query($queryDelete);
		while ($dataDelete = mysql_fetch_array($resultsDelete)) {
		
			// Remove Record
			$delete = "DELETE FROM " . table_blank_name1 . " WHERE Name1AutoID = \"". $Name1AutoID ."\"";
			$results = $wpdb->query($delete);
			
		echo "<div id=\"message\" class=\"updated\"><p>". __(LabelSingular ." <strong>". $dataDelete['Name1Title'] ."</strong> deleted successfully", rb_blank_TEXTDOMAIN) ."!</p></div>\n";
				
		} // is there record?
	} // it was numeric
	rb_display_list();

}
elseif (($_GET['action'] == "editRecord") || ($_GET['action'] == "add")) {
	$action = $_GET['action'];
	$Name1AutoID = $_GET['Name1AutoID'];

	rb_display_manage($Name1AutoID);

} else {
	rb_display_list();
}


/* Manage Record *****************************************************/

function rb_display_manage($Name1AutoID) {
  global $wpdb;

  echo "<div class=\"wrap\">\n";
  echo "  <div id=\"rb-overview-icon\" class=\"icon32\"></div>\n";
  echo "  <h2>". __("Manage ". LabelSingular, rb_blank_TEXTDOMAIN) ."</h2>\n";
  echo "  <p><a class=\"button-secondary\" href=\"". admin_url("admin.php?page=". $_GET['page']) ."\">". __("Back to ". LabelSingular ." List", rb_blank_TEXTDOMAIN) ."</a></p>\n";

  if ( !empty($Name1AutoID) && ($Name1AutoID > 0) ) {

	$query = "SELECT * FROM " . table_blank_name1 . " WHERE Name1AutoID='$Name1AutoID'";
	$results = mysql_query($query) or die (__('Error, query failed', rb_blank_TEXTDOMAIN));
	$count = mysql_num_rows($results);
	while ($data = mysql_fetch_array($results)) {
		$Name1AutoID		= $data['Name1AutoID'];
		$Name1Title			= stripslashes($data['Name1Title']);
		$Name1Text			= stripslashes($data['Name1Text']);
		$Name1Type			= $data['Name1Type'];
		$Name1Image			= $data['Name1Image'];
		$Name1Price			= $data['Name1Price'];
		$Name1DateCreated	= $data['Name1DateCreated'];
		$Name1DateUpdated 	= $data['Name1DateUpdated'];
		$Name1Active		= $data['Name1Active'];
	} 
	
	echo "<h3 class=\"title\">". __("Edit", rb_blank_TEXTDOMAIN) ." ". LabelSingular ."</h3>\n";
	echo "<p>". __("Make changes in the form below to edit a", rb_blank_TEXTDOMAIN) ." ". LabelSingular .". <strong>". __("Required fields are marked", rb_blank_TEXTDOMAIN) ."Required fields are marked *</strong></p>\n";

  } else {
		$Name1AutoID		= 0;
		$Name1Title			= "";
		$Name1Text			= "";
		$Name1Type			= 0;
		$Name1Image			= "";
		$Name1Price			= "";
		$Name1DateCreated	= "";
		$Name1DateUpdated	= "";
		$Name1Active		= 1;

	echo "<h3 class=\"title\">Add New ". LabelSingular ."</h3>\n";
	echo "<p>". __("Fill in the form below to add a new", rb_blank_TEXTDOMAIN) ." ". LabelSingular .". <strong>". __("Required fields are marked", rb_blank_TEXTDOMAIN) ." *</strong></p>\n";
  } 
	echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
	echo "<table class=\"form-table\">\n";
	echo "<tbody>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Name", rb_blank_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"Name1Title\" name=\"Name1Title\" value=\"". $Name1Title ."\" class=\"rbformitem rbformtext\" /></td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Description", rb_blank_TEXTDOMAIN) .":</th>\n";
	echo "        <td><textarea id=\"Name1Text\" name=\"Name1Text\" class=\"rbformitem rbformtextarea\">". $Name1Text ."</textarea></td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Price", rb_blank_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"Name1Price\" name=\"Name1Price\" value=\"". $Name1Price ."\" class=\"rbformitem rbformtext\" /></td>\n";
	echo "    </tr>\n";
	/*
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Date Created", rb_blank_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"Name1DateCreated\" name=\"Name1DateCreated\" value=\"". $Name1DateCreated ."\" class=\"rbformitem rbformtext\" /></td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Date Updated", rb_blank_TEXTDOMAIN) .":</th>\n";
	echo "        <td><input type=\"text\" id=\"Name1DateUpdated\" name=\"Name1DateUpdated\" value=\"". $Name1DateUpdated ."\" class=\"rbformitem rbformtext\" /></td>\n";
	echo "    </tr>\n";
	*/
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Type", rb_blank_TEXTDOMAIN) .":</th>\n";
	echo "        <td><select name=\"Name1Type\" id=\"Name1Type\" class=\"rbformitem rbformselect\">\n";
	
					$query1 = "SELECT Name2AutoID, Name2Title FROM ". table_blank_name2 ." ORDER BY Name2Title";
					$results1 = mysql_query($query1);
					$count1 = mysql_num_rows($results1);
					
					if ($count1 > 0) {
						if (empty($Name1Type) || ($Name1Type < 1) ) {
							echo " <option value=\"0\" selected>--</option>\n";
						}
						while ($data1 = mysql_fetch_array($results1)) {
							echo " <option value=\"". $data1["Name2AutoID"] ."\" ". selected($Name1Type, $data1["Name2AutoID"]) .">". $data1["Name2Title"] ."</option>\n";
						}
						echo "</select>\n";
					} else {
						// No Types Loaded
						echo "". __("No Types Identified", rb_blank_TEXTDOMAIN) .".";
					}
					
	echo "        </td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Image", rb_blank_TEXTDOMAIN) .":</th>\n";
	echo "        <td>\n";
					if (isset($Name1Image) && !empty($Name1Image)) { 
					echo "<img src='". rb_blank_UPLOADDIR ."/". $Name1Image ."' style=\"max-height: 200px;\" /><br />";
					echo "<a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&action=deleteImage&Name1AutoID=". $Name1AutoID ."\">Unlink Image</a><br />\n";
					}
	echo "      	<input type=\"file\" id=\"Name1Image\" name=\"Name1Image\" class=\"rbformitem rbformfile\" />\n";
	echo "        </td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\" class=\"rblabel\">". __("Status", rb_emp_TEXTDOMAIN) .":</th>\n";
	echo "        <td><select id=\"Name1Active\" name=\"Name1Active\" class=\"rbformitem rbformselect\">\n";
	echo "			  <option value=\"1\"". selected(1, $Name1Active) .">Active</option>\n";
	echo "			  <option value=\"0\"". selected(0, $Name1Active) .">Inactive</option>\n";
	echo "          </select></td>\n";
	echo "    </tr>\n";
	echo "  </tbody>\n";
	echo "</table>\n";

	if ( !empty($Name1AutoID) && ($Name1AutoID > 0) ) {
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"Name1AutoID\" value=\"". $Name1AutoID ."\" />\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"editRecord\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Update Record", rb_blank_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	} else {
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"addRecord\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Create Record", rb_blank_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	} 
	echo "</form>\n";
	
    echo "</div>\n";

} // End Manage


/* List Records *****************************************************/

function rb_display_list(){
  global $wpdb;

  echo "<div class=\"wrap\">\n";
  echo "  <div id=\"rb-overview-icon\" class=\"icon32\"></div>\n";
  echo "  <h2>". __("List", rb_blank_TEXTDOMAIN) ." ". LabelPlural ."</h2>\n";
	
  echo "  <h3 class=\"title\">". __("All Records", rb_blank_TEXTDOMAIN) ."</h3>\n";
		
		// Sort By
        $sort = "";
        if (isset($_GET['sort']) && !empty($_GET['sort'])){
            $sort = $_GET['sort'];
        }
        else {
            $sort = "Name1DateCreated";
        }
		
		// Sort Order
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

		// Filter
		$filter = " WHERE Name1AutoID > 0";
		if (isset($_GET['Name1Title']) && !empty($_GET['Name1Title'])){
			$selectedName1Title = trim($_GET['Name1Title']);
			$filter .= " AND Name1Title LIKE '%". $selectedName1Title ."%'";
		}
		if (isset($_GET['Name1Type']) && !empty($_GET['Name1Type'])){
			$selectedName1Type = $_GET['Name1Type'];
			$filter .= " AND Name1Type='". $selectedName1Type ."'";
		}
		if (is_numeric($_GET['Name1Active'])) {
			$selectedName1Active = $_GET['Name1Active'];
			if ($selectedName1Active == 1) {
				$filter .= " AND Name1Active = 1";
			} elseif ($selectedName1Active == 0) {
				$filter .= " AND Name1Active = 0";
			}
		}

		//Paginate
		$items = mysql_num_rows(mysql_query("SELECT Name1AutoID FROM ". table_blank_name1 ." $filter")); // number of total rows in the database
		if($items > 0) {
			$p = new rb_blank_pagination;
			$p->items($items);
			$p->limit(50); // Limit entries per page
			$p->target("admin.php?". $_SERVER['QUERY_STRING']);
			$p->currentPage($_GET[$p->paging]); // Gets and validates the current page
			$p->calculate(); // Calculates what to show
			$p->parameterName('paging');
			$p->adjacents(1); //No. of page away from the current page
	 
			if(!isset($_GET['paging'])) {
				$p->page = 1;
			} else {
				$p->page = $_GET['paging'];
			}
	 
			//Query for limit paging
			$limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;
		} else {
			$limit = "";
		}
		
        echo "<div class=\"tablenav\">\n";
 		echo "	<div style=\"float: left; \"><p><a class=\"button-secondary\" href=\"". admin_url("admin.php?page=". $_GET['page']) ."&action=add\">". __("Create New Record", rb_blank_TEXTDOMAIN) ."</a></p></div>\n";
        echo "  <div class=\"tablenav-pages\">\n";
				if($items > 0) {
					echo $p->show();  // Echo out the list of paging. 
				}
        echo "  </div>\n";
        echo "</div>\n";


		echo "<table cellspacing=\"0\" class=\"widefat fixed\">\n";
		echo "  <thead>\n";
		echo "    <tr>\n";
		echo "        <td style=\"width: 90%;\" nowrap=\"nowrap\">    \n";               
		echo "        	<form method=\"GET\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
		echo "        		<input type=\"hidden\" name=\"page_index\" id=\"page_index\" value=\"". $_GET['page_index'] ."\" />  \n";
		echo "        		<input type=\"hidden\" name=\"page\" id=\"page\" value=\"". $_GET['page'] ."\" />\n";
		echo "        		<input type=\"hidden\" name=\"type\" value=\"name\" />\n";
		echo "        		". __("Search By", rb_blank_TEXTDOMAIN) .": \n";
		echo "        		". __("Title", rb_blank_TEXTDOMAIN) .": <input type=\"text\" name=\"Name1Title\" value=\"". $selectedName1Title ."\" style=\"width: 100px;\" />\n";
		echo "        		". __("Type", rb_blank_TEXTDOMAIN) .":\n";

								$query1 = "SELECT Name2AutoID, Name2Title FROM ". table_blank_name2 ." ORDER BY Name2Title";
								$results1 = mysql_query($query1);
								$count1 = mysql_num_rows($results1);
								if ($count1 > 0) {
									echo "<select name=\"Name1Type\">\n";
									echo " <option value=\"\">". __("Any Type", rb_blank_TEXTDOMAIN) ."</option>";
									while ($data1 = mysql_fetch_array($results1)) {
										echo " <option value=\"". $data1["Name2AutoID"] ."\" ". selected($Name1Active, $data1["Name2AutoID"]) .">". $data1["Name2Title"] ."</option>\n";
									}
									echo "</select>\n";
								} else {
									// No Types Loaded
									echo "". __("No Types Identified", rb_blank_TEXTDOMAIN) .".";
								}
		echo "        		". __("Any Status", rb_blank_TEXTDOMAIN) .":  <select name=\"Name1Active\">\n";
		echo "					<option value=\"\">". __("Any Status", rb_blank_TEXTDOMAIN) ."</option>";
		echo "					<option value=\"1\"". selected($selectedName1Active, 1) ."\">". __("Active", rb_blank_TEXTDOMAIN) ."</option>\n";
		echo "					<option value=\"0\"". selected($selectedName1Active, 0) ."\">". __("Inactive", rb_blank_TEXTDOMAIN) ."</option>\n";
		echo "        		</select>\n";
		echo "        		<input type=\"submit\" value=\"". __("Filter", rb_blank_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
		echo "          </form>\n";
		echo "        </td>\n";
		echo "        <td style=\"width: 10%;\" nowrap=\"nowrap\">\n";
		echo "        	<form method=\"GET\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
		echo "        		<input type=\"hidden\" name=\"page_index\" id=\"page_index\" value=\"". $_GET['page_index'] ."\" />  \n";
		echo "        		<input type=\"hidden\" name=\"page\" id=\"page\" value=\"". $_GET['page'] ."\" />\n";
		echo "        		<input type=\"submit\" value=\"". __("Clear Filters", rb_blank_TEXTDOMAIN) ."\" class=\"button-secondary\" />\n";
		echo "        	</form>\n";
		echo "        </td>\n";
		echo "        <td>&nbsp;</td>\n";
		echo "    </tr>\n";
		echo "  </thead>\n";
		echo "</table>\n";
		
		echo "<form method=\"post\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";	
		echo "<table cellspacing=\"0\" class=\"widefat fixed\">\n";
		echo "<thead>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\"manage-column column cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"admin.php?page=". $_GET['page'] ."&sort=Name1Title&dir=". $sortDirection ."\">". __("Title", rb_blank_TEXTDOMAIN) ."</a></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"admin.php?page=". $_GET['page'] ."&sort=Name1Price&dir=". $sortDirection ."\">". __("Price", rb_blank_TEXTDOMAIN) ."</a></th>\n";
		echo "        <th class=\"column\" scope=\"col\"><a href=\"admin.php?page=". $_GET['page'] ."&sort=Name1Type&dir=". $sortDirection ."\">". __("Type", rb_blank_TEXTDOMAIN) ."</a></th>\n";
		echo "    </tr>\n";
		echo "</thead>\n";
		echo "<tfoot>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\" columnmanage-column cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Title", rb_blank_TEXTDOMAIN) ."</th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Price", rb_blank_TEXTDOMAIN) ."</th>\n";
		echo "        <th class=\"column\" scope=\"col\">". __("Type", rb_blank_TEXTDOMAIN) ."</th>\n";
		echo "    </tr>\n";
		echo "</tfoot>\n";
		echo "<tbody>\n";

		$query = "SELECT Name1Type, Name1AutoID, Name1Title, Name1Price, Name1Active FROM ". table_blank_name1 ." $filter ORDER BY $sort $dir $limit";
		$results = mysql_query($query) or die ('');
        $count = mysql_num_rows($results);
        while ($data = mysql_fetch_array($results)) {
            $Name1AutoID = $data['Name1AutoID'];
            if ($data['Name1Active'] == 0) { $rowColor = " style='background: #FFEBE8'"; } else { $rowColor = ""; }

		echo "    <tr". $rowColor .">\n";
		echo "        <th class=\"check-column\" scope=\"row\"><input type=\"checkbox\" class=\"administrator\" id=\"". $Name1AutoID ."\" name=\"". $Name1AutoID ."\" value=\"". $Name1AutoID ."\" /></th>\n";
		echo "        <td class=\"column\">". stripslashes($data['Name1Title']) ."\n";
		echo "          <div class=\"row-actions\">\n";
		echo "            <span class=\"edit\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;action=editRecord&amp;Name1AutoID=". $Name1AutoID ."\" title=\"". __("Edit this Record", rb_blank_TEXTDOMAIN) . "\">". __("Edit", rb_blank_TEXTDOMAIN) . "</a> | </span>\n";
		echo "            <span class=\"delete\"><a class=\"submitdelete\" href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;action=deleteRecord&amp;Name1AutoID=". $Name1AutoID ."\"  onclick=\"if ( confirm('". __("You are about to delete this ". LabelSingular, rb_blank_TEXTDOMAIN) . ".\'". __("Cancel", rb_blank_TEXTDOMAIN) . "\' ". __("to stop", rb_blank_TEXTDOMAIN) . ", \'". __("OK", rb_blank_TEXTDOMAIN) . "\' ". __("to delete", rb_blank_TEXTDOMAIN) . ".') ) { return true;}return false;\" title=\"". __("Delete this Record", rb_blank_TEXTDOMAIN) . "\">". __("Delete", rb_blank_TEXTDOMAIN) . "</a> </span>\n";
		echo "          </div>\n";
		echo "        </td>\n";
		echo "        <td class=\"column\">". $data['Name1Price'] ."</td>\n";
		echo "        <td class=\"column\">". stripslashes($data['Name1Type']) ."</td>\n";
		echo "    </tr>\n";
        }
		mysql_free_result($results);
		if ($count < 1) {
			if (isset($filter)) { 
	echo "    <tr>\n";
	echo "        <td class=\"check-column\" scope=\"row\"></th>\n";
	echo "        <td class=\"column\" colspan=\"3\"><p>". __("No records found with this criteria", rb_blank_TEXTDOMAIN) . ".</p></td>\n";
	echo "    </tr>\n";
			} else {
	echo "    <tr>\n";
	echo "        <td class=\"check-column\" scope=\"row\"></th>\n";
	echo "        <td class=\"column\" colspan=\"3\"><p>". __("There aren't any records loaded yet", rb_blank_TEXTDOMAIN) . "!</p></td>\n";
	echo "    </tr>\n";
			}
        } 
	echo "</tbody>\n";
	echo "</table>\n";
	echo "<p class=\"submit\">\n";
	echo "    <input type=\"hidden\" name=\"action\" value=\"deleteRecord\" />\n";
	echo "    <input type=\"submit\" name=\"submit\" value=\"". __("Delete", rb_blank_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
	echo "</p>\n";

    echo "</form>\n";
	
	echo "<div class=\"tablenav\">\n";
	echo "    <div class=\"tablenav-pages\">\n";
			if($items > 0) {
				echo $p->show();  // Echo out the list of paging. 
			}
	echo "    </div>\n";
	echo "</div>\n";

    echo "</div>\n";
}
?>