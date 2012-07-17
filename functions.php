<?php

// *************************************************************************************************** //
// Admin Head Section 

	add_action('admin_head', 'rb_blank_admin_head');
		function rb_blank_admin_head(){
		  if( is_admin() ) {
			  
			// Add Stylesheet to WordPress Admin
			echo "<link rel=\"stylesheet\" href=\"". rb_blank_BASEDIR ."style/admin.css\" type=\"text/css\" media=\"screen\" />\n";
			
		  }
		}
	

// *************************************************************************************************** //
// Page Head Section

	add_action('wp_head', 'rb_blank_inserthead');
		// Call Custom Code to put in header
		function rb_blank_inserthead() {
		  if( !is_admin() ) {
			
			// Insert Code into Header

		  }
		}


// *************************************************************************************************** //
// Add to WordPress Dashboard

	add_action('wp_dashboard_setup', 'rb_blank_add_dashboard' );
		// Hoook into the 'wp_dashboard_setup' action to register our other functions
		// Create the function use in the action hook
		function rb_blank_add_dashboard() {
			global $wp_meta_boxes;
			// Create Dashboard Widgets
			wp_add_dashboard_widget('rb_blank_dashboard_quicklinks', __("RB Blank Updates", rb_blank_TEXTDOMAIN), 'rb_blank_dashboard_quicklinks');
		
			// reorder the boxes - first save the left and right columns into variables
			$left_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
			$right_dashboard = $wp_meta_boxes['dashboard']['side']['core'];
			
			// take a copy of the new widget from the left column
			$rb_blank_dashboard_merge_array = array("rb_blank_dashboard_quicklinks" => $left_dashboard["rb_blank_dashboard_quicklinks"]);
			
			unset($left_dashboard['rb_blank_dashboard_quicklinks']); // remove the new widget from the left column
			$right_dashboard = array_merge($rb_blank_dashboard_merge_array, $right_dashboard); // use array_merge so that the new widget is pushed on to the beginning of the right column's array  
			
			// finally replace the left and right columns with the new reordered versions
			$wp_meta_boxes['dashboard']['normal']['core'] = $left_dashboard; 
			$wp_meta_boxes['dashboard']['side']['core'] = $right_dashboard;
		}

		 // Create the function to output the contents of our Dashboard Widget
		function rb_blank_dashboard_quicklinks() {
			// Display Quicklinks
			$rb_blank_options_arr = get_option('rb_blank_options');
			if (isset($rb_blank_options_arr['dashboardQuickLinks'])) {
			  echo $rb_blank_options_arr['dashboardQuickLinks'];
			}
			$rss = fetch_feed("http://rbplugin.com/feed/");
			// Checks that the object is created correctly 
				if (!is_wp_error($rss)) { 
				// Figure out how many total items there are, but limit it to 5. 
				$maxitems = $rss->get_item_quantity($num_items); 
				// Build an array of all the items, starting with element 0 (first element).
				$rss_items = $rss->get_items(0, $maxitems); 
				}
				echo "<div class=\"feed-searchsocial\">\n";
				if ($maxitems == 0) {
					echo "Empty Feed\n";
				} else {
				  // Loop through each feed item and display each item as a hyperlink.
				  foreach ( $rss_items as $item ) {
					echo "  <div class=\"blogpost\">\n";
					echo "    <h4><a href='". $item->get_permalink() ."' title='Posted ". $item->get_date('j F Y | g:i a') ."' target=\"_blank\">". $item->get_title() ."</a></h4>\n";
					echo "    <div class=\"description\">". $item->get_description() ."</div>\n";
					echo "    <div class=\"clear\"></div>\n";
					echo "  </div>\n";
				  }
				}
				echo "</div>\n";
				echo "<hr />\n";
		} 



// *************************************************************************************************** //
// Add Custom Classes

	add_filter("body_class", "rb_blank_insertbodyclass");
	add_filter("post_class", "rb_blank_insertbodyclass");
		function rb_blank_insertbodyclass($classes) {
			if (substr($_SERVER['REQUEST_URI'], 0, len("/test/")) == "/test/") {
				$classes[] = 'rb-blank-test';
			} else {
				$classes[] = 'rb-blank';
			}
			return $classes;
		}

// *************************************************************************************************** //
// Handle Folders

	// Adding a new rule
	add_filter('rewrite_rules_array','rb_blank_rewriteRules');
		function rb_blank_rewriteRules($rules) {
			$newrules = array();
			// Pagination
			$newrules['blank-category/(.*)/([0-9])$'] = 'index.php?type=category&target=$matches[1]&paging=$matches[2]';
			// Type
			$newrules['blank-category/([0-9])$'] = 'index.php?type=category&paging=$matches[1]';
			// Base
			$newrules['blank-category/(.*)$'] = 'index.php?type=category&target=$matches[1]';
			return $newrules + $rules;
		}
		
	// Get Veriables & Identify View Type
	add_action( 'query_vars', 'rb_blank_query_vars' );
		function rb_blank_query_vars( $query_vars ) {
			$query_vars[] = 'type';
			$query_vars[] = 'target';
			$query_vars[] = 'paging';
			$query_vars[] = 'value';
			return $query_vars;
		}
	
	// Set Custom Template
	add_filter('template_include', 'rb_blank_template_include', 1, 1); 
		function rb_blank_template_include( $template ) {
			if ( get_query_var( 'type' ) ) {
			  if (get_query_var( 'type' ) == "search") {
				return dirname(__FILE__) . '/theme/view-search.php'; 
			  } elseif (get_query_var( 'type' ) == "print") {
				return dirname(__FILE__) . '/theme/view-print.php'; 
			  }
			}
			return $template;
		}
	
	// Remember to flush_rules() when adding rules
	add_filter('init','rb_blank_flushrules');
		function rb_blank_flushRules() {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}
	
	
// *************************************************************************************************** //
// General Functions

	function rb_blank_cleanString($string) {
		// Remove trailing dingleberry
		if (substr($string, -1) == ",") {  $string = substr($string, 0, strlen($string)-1); }
		if (substr($string, 0, 1) == ",") { $string = substr($string, 1, strlen($string)-1); }

		// Just Incase
		$string = str_replace(",,", ",", $string);
		return $string;
	}

	function rb_blank_getActiveLanguage() {
		if (function_exists('icl_get_languages')) {
			  // fetches the list of languages
		  $languages = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR');
	
		  $activeLanguage = 'en';
		
		  // runs through the languages of the system, finding the active language
		  foreach($languages as $language) {
			// tests if the language is the active one
			if($language['active'] == 1) {
			  $activeLanguage = $language['language_code'];
			}
		  return "/". $activeLanguage;
		  }
		} else {
		  return "";
		}
	}
	
	function rb_blank_random() {
		return preg_replace("/([0-9])/e","chr((\\1+112))",rand(100000,999999));
	}
	
	function rb_blank_get_userrole() {
		global $current_user;
		get_currentuserinfo();
		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);
		return $user_role;
	};
	
	function rb_blank_convertdatetime($datetime) {
		// Convert
		list($date, $time) = explode(' ', $datetime);
		list($year, $month, $day) = explode('-', $date);
		list($hours, $minutes, $seconds) = explode(':', $time);
		
		$UnixTimestamp = mktime($hours, $minutes, $seconds, $month, $day, $year);
		return $UnixTimestamp;
	}
	
	function rb_blank_makeago($timestamp, $offset){
	  if (isset($timestamp) && !empty($timestamp) && ($timestamp <> "0000-00-00 00:00:00") && ($timestamp <> "943920000")) {
		// Offset
		$timezone_offset = (int)$offset; // Server Time
		$time_altered = time() + $timezone_offset *60 *60;
	
		// Math
		$difference = $time_altered - $timestamp;
		
		//printf("\$timestamp: %d, \$difference: %d\n", $timestamp, $difference);
		$periods = array("sec", "min", "hr", "day", "week", "month", "year", "decade");
		$lengths = array("60","60","24","7","4.35","12","10");
		for($j = 0; $difference >= $lengths[$j]; $j++)
		$difference /= $lengths[$j];
		$difference = round($difference);
		if($difference != 1) $periods[$j].= "s";
		$text = "$difference $periods[$j] ago";
			if ($j > 10) { exit; }
		return $text;
	  } else {
		return "--";
	  }
	}
	
	function rb_blank_get_age($p_strDate) {
		list($Y,$m,$d) = explode("-",$p_strDate);
		return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
	}
	
	function rb_blank_collapseWhiteSpace($string) {
		return preg_replace('/\s+/', ' ', $string);
	}
	
	function rb_blank_safename($filename) {
		$filename = rb_blank_collapseWhiteSpace(trim($filename));
		$filename = str_replace(' ', '-', $filename);
		$filename = preg_replace('/[^a-z0-9-.]/i','',$filename);
		$filename = str_replace('--', '-', $filename);
		return strtolower($filename);
	}
	
	function rb_blank_filenameextension($filename) {
		$pos = strrpos($filename, '.');
		if($pos===false) {
			return false;
		} else {
			return substr($filename, $pos+1);
		}
	}
	
	// Format a string in proper case.
	function rb_blank_strtoproper($someString) {
		return ucwords(strtolower($someString));
	}



// *************************************************************************************************** //
// Shortcodes

	/****************  List Records  ***************/
	// 
	function rb_blank_list($atts, $content = NULL) {
		/* Example usage:
		if (function_exists('rb_blank_list')) { 
			$atts = array('recordid' => 1, 'type' => 'Value One');
			rb_blank_list($atts); }
		*/
		
			// Get Preferences
			$rb_blank_options_arr = get_option('rb_blank_options');
				$rb_blank_option_name1 = $rb_blank_options_arr['rb_blank_option_name1'];  // Use (int)$rb_blank_options_arr['rb_blank_option_name1']  if integer
				$rb_blank_option_name2 = $rb_blank_options_arr['rb_blank_option_name2'];
	
			// Set It Up	
			global $wp_rewrite;
			extract(shortcode_atts(array(
					"recordid" => NULL,
					"type" => NULL,
					"paging" => NULL,
					"pagingperpage" => NULL
				), $atts));
			
				// Declare Filter String
				$filter = "";
	
	
			/****************  Pagination  ***************/
			// Pagination
			if (!isset($paging) || empty($paging)) {
				$paging = 1; 
				if (get_query_var('paging')) {
					$paging = get_query_var('paging'); 
				} else { 
					preg_match('/[0-9]/', $_SERVER["REQUEST_URI"], $matches, PREG_OFFSET_CAPTURE);
					if ($matches[0][1] > 0) {
						$paging = str_replace("/", "", substr($_SERVER["REQUEST_URI"], $matches[0][1]));
					} else {
						$paging = 1; 
					}
				}
			}
			
			// Configure how many per page
			if (!isset($pagingperpage) || empty($pagingperpage)) { $pagingperpage = $rb_blank_option_name2; } 
	

			/***********  Paginate  **************/

			$items = mysql_num_rows(mysql_query("SELECT Name1Title FROM  ". table_blank_name1 ." WHERE Name1Active = 1 $filter")); // number of total rows in the database
			if($items > 0) {
				$p = new rb_blank_pagination;
				$p->items($items);
				$p->limit($pagingperpage); // Limit entries per page
				$p->target($_SERVER["REQUEST_URI"]);
				$p->currentPage($paging); // Gets and validates the current page
				$p->calculate(); // Calculates what to show
				$p->parameterName('paging');
				$p->adjacents(0); //No. of page away from the current page
				
				if(!isset($paging)) {
					$p->page = 1;
				} else {
					$p->page = $paging;
				}

				//Query for limit paging
				$limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;
			} else {
				$limit = "";
			}

			/*********** Execute Query **************/
			// Query
			$queryList = "SELECT Name1Title FROM  ". table_blank_name1 ." WHERE Name1Active = 1 $filter ORDER BY Name1DateCreated ASC";
			$resultsList = mysql_query($queryList);
			$countList = mysql_num_rows($resultsList);


			/*********** Show Count/Pages **************/
			// Pages
			if($items > 0) {
				echo "    <div class=\"blank-results-pages\">\n";
				echo 		$p->show();  // Echo out the list of paging. 
				echo "    </div>\n";
			}
			// Count
				echo "    <div class=\"blank-results-count\">\n";
				echo "    	". __("Displaying", rb_blank_TEXTDOMAIN) ." <strong>". $countList ."</strong> ". __("of", rb_blank_TEXTDOMAIN) ." ". $items ." ". __(" records", rb_blank_TEXTDOMAIN) ."\n";
				echo "    </div>\n";


			/*********** Display List **************/
			echo "<div id=\"blank-list\">\n";
			while ($dataList = mysql_fetch_array($resultsList)) {
				if ($type == true) {
					// This is a typ
					echo "  <div class=\"layout1\">". $dataList["Name1Title"] ."</div>\n";
				} else {
					// This is a different type
					echo "  <div class=\"layout2\">". $dataList["Name1Title"] ."</div>\n";
				}
			}
			if ($countList < 1) {
				echo __("No Profiles Found", rb_blank_TEXTDOMAIN);
			}
			echo "</div>\n";

	}


	/****************  Display Record  ***************/
	// 
	function rb_blank_display($atts, $content = NULL) {
		/* Example usage:
		if (function_exists('rb_blank_list')) { 
			$atts = array('recordid' => 1, 'type' => 'Value One');
			rb_blank_list($atts); }
		*/
		
		// Get Preferences
		$rb_blank_options_arr = get_option('rb_blank_options');
			$rb_blank_option_name1 = $rb_blank_options_arr['rb_blank_option_name1'];  // Use (int)$rb_blank_options_arr['rb_blank_option_name1']  if integer
			$rb_blank_option_name2 = $rb_blank_options_arr['rb_blank_option_name2'];

		// Set It Up	
		global $wp_rewrite;
		extract(shortcode_atts(array(
				"recordid" => NULL,
				"type" => NULL
			), $atts));
		
			// Declare Filter String
			$filter = "";
			
			// Record ID
			if (isset($recordid) && !empty($recordid)){
				$filter .= " AND Name1AutoID='". $recordid ."'";
			}
	
			// Query
			$queryList = "SELECT Name1Title FROM  ". table_blank_name1 ." WHERE Name1Active = 1 $filter ORDER BY Name1DateCreated ASC";
			$resultsList = mysql_query($queryList);
			$countList = mysql_num_rows($resultsList);
	
			echo "<div id=\"blank-list\">\n";
			while ($dataList = mysql_fetch_array($resultsList)) {
				if ($type == true) {
					// This is a typ
					echo "  <div class=\"layout1\">". $dataList["Name1Title"] ."</div>\n";
				} else {
					// This is a different type
					echo "  <div class=\"layout2\">". $dataList["Name1Title"] ."</div>\n";
				}
			}
			if ($countList < 1) {
				echo __("No Profiles Found", rb_blank_TEXTDOMAIN);
			}
			echo "</div>\n";

	}




// *************************************************************************************************** //
// Image Resizing 

	class rb_blank_image {
	 
	   var $image;
	   var $image_type;
	 
	   function load($filename) {
	 
		  $image_info = getimagesize($filename);
		  $this->image_type = $image_info[2];
		  if( $this->image_type == IMAGETYPE_JPEG ) {
	 
			 $this->image = imagecreatefromjpeg($filename);
		  } elseif( $this->image_type == IMAGETYPE_GIF ) {
	 
			 $this->image = imagecreatefromgif($filename);
		  } elseif( $this->image_type == IMAGETYPE_PNG ) {
	 
			 $this->image = imagecreatefrompng($filename);
		  }
	   }
	   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=NULL) {
	 
		  if( $image_type == IMAGETYPE_JPEG ) {
			 imagejpeg($this->image,$filename,$compression);
		  } elseif( $image_type == IMAGETYPE_GIF ) {
	 
			 imagegif($this->image,$filename);
		  } elseif( $image_type == IMAGETYPE_PNG ) {
	 
			 imagepng($this->image,$filename);
		  }
		  if( $permissions != NULL) {
	 
			 chmod($filename,$permissions);
		  }
	   }
	   function output($image_type=IMAGETYPE_JPEG) {
	 
		  if( $image_type == IMAGETYPE_JPEG ) {
			 imagejpeg($this->image);
		  } elseif( $image_type == IMAGETYPE_GIF ) {
	 
			 imagegif($this->image);
		  } elseif( $image_type == IMAGETYPE_PNG ) {
	 
			 imagepng($this->image);
		  }
	   }
	   function getWidth() {
	 
		  return imagesx($this->image);
	   }
	   function getHeight() {
	 
		  return imagesy($this->image);
	   }
	   function resizeToHeight($height) {
	 
		  $ratio = $height / $this->getHeight();
		  $width = $this->getWidth() * $ratio;
		  $this->resize($width,$height);
	   }
	 
	   function resizeToWidth($width) {
		  $ratio = $width / $this->getWidth();
		  $height = $this->getHeight() * $ratio;
		  $this->resize($width,$height);
	   }
	 
	   function scale($scale) {
		  $width = $this->getWidth() * $scale/100;
		  $height = $this->getHeight() * $scale/100;
		  $this->resize($width,$height);
	   }
	 
	   function resize($width,$height) {
		  $new_image = imagecreatetruecolor($width, $height);
		  imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		  $this->image = $new_image;
	   }      
	 
	   function orientation() {
		  if ($this->getWidth() == $this->getHeight()) {
			  return "square";
		  } elseif ($this->getWidth() > $this->getHeight()) {
			  return "landscape";
		  } else {
			  return "portrait";
		  }
	   }
	 
	 
	}



// *************************************************************************************************** //
// Pagination

	class rb_blank_pagination {
			/*Default values*/
			var $total_pages = -1;//items
			var $limit = NULL;
			var $target = ""; 
			var $page = 1;
			var $adjacents = 0;
			var $showCounter = false;
			var $className = "pagination";
			var $parameterName = "page";
			var $urlF = false;//urlFriendly
	
			/*Buttons next and previous*/
			var $nextT = "Next";
			var $nextI = "&#187;"; //&#9658;
			var $prevT = "Previous";
			var $prevI = "&#171;"; //&#9668;
	
			/*****/
			var $calculate = false;
			
			#Total items
			function items($value){$this->total_pages = (int) $value;}
			
			#how many items to show per page
			function limit($value){$this->limit = (int) $value;}
			
	
			#Page to sent the page value
			function target($value){$this->target = $value;}
			
			#Current page
			function currentPage($value){$this->page = (int) $value;}
			
			#How many adjacent pages should be shown on each side of the current page?
			function adjacents($value){$this->adjacents = (int) $value;}
			
			#show counter?
			function showCounter($value=""){$this->showCounter=($value===true)?true:false;}
	
			#to change the class name of the pagination div
			function changeClass($value=""){$this->className=$value;}
	
			function nextLabel($value){$this->nextT = $value;}
			function nextIcon($value){$this->nextI = $value;}
			function prevLabel($value){$this->prevT = $value;}
			function prevIcon($value){$this->prevI = $value;}
	
			#to change the class name of the pagination div
			function parameterName($value=""){$this->parameterName=$value;}
	
			#to change urlFriendly
			function urlFriendly($value="%"){
					if(eregi('^ *$',$value)){
							$this->urlF=false;
							return false;
						}
					$this->urlF=$value;
				}
			
			var $pagination;
	
			function pagination(){}
			function show(){
					if(!$this->calculate)
						if($this->calculate())
							echo "<div class=\"$this->className\">$this->pagination</div>\n";
				}
			function getOutput(){
					if(!$this->calculate)
						if($this->calculate())
							return "<div class=\"$this->className\">$this->pagination</div>\n";
				}
			function get_pagenum_link($id) {
				if (substr($this->target, 0, 9) == "admin.php") {
					// We are in Admin
				
					if (strpos($this->target,'?') === false) {
						if ($this->urlF) {
							return str_replace($this->urlF,$id,$this->target);
						} else {
							return "$this->target?$this->parameterName=$id";
						}
					} else {
							return "$this->target&$this->parameterName=$id";
					}
				
				} else {
					// We are in Page
				
					preg_match('/[0-9]/', $this->target, $matches, PREG_OFFSET_CAPTURE);
					if ($matches[0][1] > 0) {
						return substr($this->target, 0, $matches[0][1]) ."/$id/";
					} else {
						return "$this->target/$id/";
					}
					
				} // End Admin/Page Toggle
			}
			
			function calculate(){
					$this->pagination = "";
					$this->calculate == true;
					$error = false;
					if($this->urlF and $this->urlF != '%' and strpos($this->target,$this->urlF)===false){
							//Es necesario especificar el comodin para sustituir
							echo "Especificaste un wildcard para sustituir, pero no existe en el target<br />";
							$error = true;
						}elseif($this->urlF and $this->urlF == '%' and strpos($this->target,$this->urlF)===false){
							echo "Es necesario especificar en el target el comodin % para sustituir el número de página<br />";
							$error = true;
						}
	
					if($this->total_pages < 0){
							echo "It is necessary to specify the <strong>number of pages</strong> (\$class->items(1000))<br />";
							$error = true;
						}
					if($this->limit == NULL){
							echo "It is necessary to specify the <strong>limit of items</strong> to show per page (\$class->limit(10))<br />";
							$error = true;
						}
					if($error)return false;
					
					$n = trim('<span>'. $this->nextT.'</span> '.$this->nextI);
					$p = trim($this->prevI.' <span>'.$this->prevT .'</span>');
					
					/* Setup vars for query. */
					if($this->page) 
						$start = ($this->page - 1) * $this->limit;      //first item to display on this page
					else
						$start = 0;                               		//if no page var is given, set start to 0
				
					/* Setup page vars for display. */
					$prev = $this->page - 1;                            //previous page is page - 1
					$next = $this->page + 1;                            //next page is page + 1
					$lastpage = ceil($this->total_pages/$this->limit);  //lastpage is = total pages / items per page, rounded up.
					$lpm1 = $lastpage - 1;                        		//last page minus 1
					
					/* 
						Now we apply our rules and draw the pagination object. 
						We're actually saving the code to a variable in case we want to draw it more than once.
					*/
					
					if($lastpage > 1){
							if($this->page){
									//anterior button
									if($this->page > 1)
											$this->pagination .= "<a href=\"".$this->get_pagenum_link($prev)."\" class=\"pagedir prev\">$p</a>";
										else
											$this->pagination .= "<span class=\"pagedir disabled\">$p</span>";
								}
							//pages	
							if ($lastpage < 7 + ($this->adjacents * 2)){//not enough pages to bother breaking it up
									for ($counter = 1; $counter <= $lastpage; $counter++){
											if ($counter == $this->page)
													$this->pagination .= "<span class=\"pageno current\">$counter</span>";
												else
													$this->pagination .= "<a class=\"pageno\" href=\"".$this->get_pagenum_link($counter)."\">$counter</a>";
										}
								}
							elseif($lastpage > 5 + ($this->adjacents * 2)){//enough pages to hide some
									//close to beginning; only hide later pages
									if($this->page < 1 + ($this->adjacents * 2)){
											for ($counter = 1; $counter < 4 + ($this->adjacents * 2); $counter++){
													if ($counter == $this->page)
															$this->pagination .= "<span class=\"current\">$counter</span>";
														else
															$this->pagination .= "<a href=\"".$this->get_pagenum_link($counter)."\">$counter</a>";
												}
											$this->pagination .= "...";
											$this->pagination .= "<a href=\"".$this->get_pagenum_link($lpm1)."\">$lpm1</a>";
											$this->pagination .= "<a href=\"".$this->get_pagenum_link($lastpage)."\">$lastpage</a>";
										}
									//in middle; hide some front and some back
									elseif($lastpage - ($this->adjacents * 2) > $this->page && $this->page > ($this->adjacents * 2)){
											$this->pagination .= "<a href=\"".$this->get_pagenum_link(1)."\">1</a>";
											$this->pagination .= "<a href=\"".$this->get_pagenum_link(2)."\">2</a>";
											$this->pagination .= "...";
											for ($counter = $this->page - $this->adjacents; $counter <= $this->page + $this->adjacents; $counter++)
												if ($counter == $this->page)
														$this->pagination .= "<span class=\"current\">$counter</span>";
													else
														$this->pagination .= "<a href=\"".$this->get_pagenum_link($counter)."\">$counter</a>";
											$this->pagination .= "...";
											$this->pagination .= "<a href=\"".$this->get_pagenum_link($lpm1)."\">$lpm1</a>";
											$this->pagination .= "<a href=\"".$this->get_pagenum_link($lastpage)."\">$lastpage</a>";
										}
									//close to end; only hide early pages
									else{
											$this->pagination .= "<a href=\"".$this->get_pagenum_link(1)."\">1</a>";
											$this->pagination .= "<a href=\"".$this->get_pagenum_link(2)."\">2</a>";
											$this->pagination .= "...";
											for ($counter = $lastpage - (2 + ($this->adjacents * 2)); $counter <= $lastpage; $counter++)
												if ($counter == $this->page)
														$this->pagination .= "<span class=\"current\">$counter</span>";
													else
														$this->pagination .= "<a href=\"".$this->get_pagenum_link($counter)."\">$counter</a>";
										}
								}
							if($this->page){
									//siguiente button
									if ($this->page < $counter - 1)
											$this->pagination .= "<a href=\"".$this->get_pagenum_link($next)."\" class=\"pagedir next\">$n</a>";
										else
											$this->pagination .= "<span class=\"pagedir disabled\">$n</span>";
										if($this->showCounter)$this->pagination .= "<div class=\"pagedir pagination_data\">($this->total_pages Pages)</div>";
								}
						}
	
					return true;
				}
		}
?>