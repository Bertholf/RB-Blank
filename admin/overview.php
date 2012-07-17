<?php
	global $wpdb;
	$rb_blank_options_arr = get_option('rb_blank_options');
	get_currentuserinfo(); global $user_level;

echo "<div class=\"wrap\">\n";
echo "  <div id=\"rb-overview-icon\" class=\"icon32\"></div>\n";
echo "  <h2>Dashboard</h2>\n";
echo "  <p>". __("You are using version", rb_blank_TEXTDOMAIN) . " <b>". rb_blank_VERSION ."</b></p>\n";
  
echo "  <div class=\"boxblock-holder\">\n";
  
echo "    <div class=\"boxblock-container\" style=\"width: 46%;\">\n";
    
echo "     <div class=\"boxblock\">\n";
echo "        <h3>". __("Recent Activity", rb_blank_TEXTDOMAIN) . "</h3>\n";
echo "        <div class=\"inner\">\n";

			   if ($user_level > 7) {
						
						// Recent Actiity
						echo "<p class=\"sub\">". __("List", rb_blank_TEXTDOMAIN) . ":</p>";
						echo "<div style=\"border-top: 2px solid #c0c0c0; \" class=\"client\">";
						
						$query = "SELECT Name1AutoID, Name1Title, Name1DateCreated FROM ". table_blank_name1 ." ORDER BY Name1DateCreated DESC LIMIT 0,10";
						$results = mysql_query($query) or die (__('Error, query failed', rb_blank_TEXTDOMAIN));
						$count = mysql_num_rows($results);
						while ($data = mysql_fetch_array($results)) {
							echo "<div style=\"border-bottom: 1px solid #e1e1e1; line-height: 22px; \" class=\"client\">";
							echo " <div style=\"font-size: 8px; float: left; width: 100px; line-height: 22px; \"><em>" . $data['Name1DateCreated'] . "</em></div>";
							echo " <div style=\"float: left; width: 150px; \"><a href=\"?page=rb_blank_clients&action=editRecord&Name1AutoID=". $data['Name1AutoID'] ."\">". stripslashes($data['Name1Title']) . "</a></div>"; 
							echo " <div style=\"clear: both; \"></div>";
							echo "</div>";
						}
						mysql_free_result($results);
						if ($count < 1) {
							echo "". __("There are currently no records added", rb_blank_TEXTDOMAIN) . ".";
						}
						echo "</div>";
			   }
echo "        </div>\n"; 
echo "     </div>\n"; 

echo "    </div>\n"; 
  
echo "    <div class=\"boxblock-container\" style=\"width: 46%;\">\n";

echo "     <div class=\"boxblock\">\n";
echo "        <h3>". __("Actions", rb_blank_TEXTDOMAIN) . "</h3>\n";
echo "        <div class=\"inner\">\n";
echo "			<p><a href='?page=rb_blank_thingamajig' class=\"button-secondary\">Thingamajig</a> - Manage Thingamajigs.</p>";
echo "        </div>\n"; 
echo "     </div>\n"; 

echo "    </div>\n"; 

echo "    <div class=\"clear\"></div>\n"; 

echo "    <div class=\"boxblock-container\" style=\"width: 93%;\">\n"; 

echo "     <div class=\"boxblock\">\n"; 
echo "        <div class=\"inner\">\n"; 
echo "            <p>". __("WordPress Plugins by ", rb_blank_TEXTDOMAIN) . " <a href=\"http://rbplugin.com\" target=\"_blank\">Rob Bertholf</a>.</p>\n"; 
echo "        </div>\n"; 
echo "     </div>\n"; 
     
echo "    </div><!-- .container -->\n"; 

echo " </div>\n"; 
echo "</div>\n"; 
?>