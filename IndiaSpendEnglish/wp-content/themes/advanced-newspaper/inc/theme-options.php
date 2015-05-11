<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */
function optionsframework_option_name() {

	// This gets the theme name from the stylesheet
	$themename = wp_get_theme();
	$themename = $themename->{'Name'};
	$themename = preg_replace("/\W/", "_", strtolower($themename) );

	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = $themename;
	update_option( 'optionsframework', $optionsframework_settings );
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

function optionsframework_options() {

	// VARIABLES
	$GLOBALS['template_path'] = OF_DIRECTORY;
	$themename = wp_get_theme();
	$themename = $themename->{'Name'};
	$shortname = "of";
	$themeid = "_an";
	
	// Image Alignment radio box
	$options_thumb_align = array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center"); 

	// Image Links to Options
	$options_image_link_to = array("image" => "The Image","post" => "The Post"); 

	//Default Arrays 
	$options_revealtype = array('OnMouseover', 'OnClick');
	$options_nr = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);
	$options_inslider = array("Disable", "Tag-based", "Site Wide");
	$options_sort = array("ASC" => 'asc', 'desc' => 'desc');
	$options_order = array('id' => "id", 'name' => 'name', "count" => "count");
	$options_logo = array('text' => "Text Based Logo", "image" => "Image Based Logo");
	$options_feaslide = array("scrollUp", "scrollDown", "scrollLeft", "scrollRight", "turnUp", "turnDown", "turnLeft", "turnRight", "fade");

	//Stylesheets Reader
	$alt_stylesheet_path = OF_FILEPATH . '/styles/';
	$alt_stylesheets = array();
	
	// Pull all the categories into an array
	$options_categories = array();  
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
    	$options_categories[$category->cat_ID] = $category->cat_name;
	}
	
	//Access the WordPress Pages via an Array
	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');    
	foreach ($options_pages_obj as $of_page) {
		$options_pages[$of_page->ID] = $of_page->post_name; }
	$options_pages_tmp = array_unshift($options_pages, "Select a page:");
	
	if ( is_dir($alt_stylesheet_path) ) {
		if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
			while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
				if(stristr($alt_stylesheet_file, ".css") !== false) {
					$alt_stylesheets[] = $alt_stylesheet_file;
				}
			}    
		}
	}

	//More Options
	$uploads_arr = wp_upload_dir();
	$all_uploads_path = $uploads_arr['path'];
	$all_uploads = get_option('of_uploads');
	$other_entries = array("Select a number:",1,"2","3",4,"5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
	$body_repeat = array("no-repeat","repeat-x","repeat-y","repeat");
	$body_pos = array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right");
	
	// Pull all the pages into an array
	$options_pages = array();  
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
    	$options_pages[$page->ID] = $page->post_title;
	}
		
	// If using image radio buttons, define a directory path
	$imagepath =  get_bloginfo('stylesheet_directory') . '/images/';
		
	$options = array();
		
	$options[] = array( 'name' => 'General', 'type' => 'heading');
					
		$options[] = array( 'name' => 'Theme Stylesheet',
							'desc' => 'Select your themes color scheme (i.e., active style).',
							'id' => $shortname.'_alt_stylesheet',
							'std' => 'default.css',
							'type' => 'select',
							'options' => $alt_stylesheets);
							
		$url =  get_bloginfo('stylesheet_directory').'/framework/admin/images/';
		$options[] = array( 'name' => 'Select header style',
							'desc' => 'Set your header either to display a single image, logo and banner, or logo with quotes.',
							'id' => $shortname.'_header_type',
							'type' => 'images',
							'options' => array(
								'logobanner' => $url . 'logobanner.gif',
								'singleimage' => $url . 'single-image.gif',
								'quotelogo' => $url . 'quote-logo.gif'));

		$options[] = array( 'name' => 'Header image (if single image selected)',
							'desc' => 'If single image option is selected as header type, upload an image 960px wide.',
							'id' => $shortname.'_himageurl',
							'type' => 'upload');

		$options[] = array( 'name' => 'Logo Type',
							'desc' => 'If text-based logo is selected, set site name and tagline on WordPress General Settings page.',
							'id' => $shortname.'_logo_type',
							'std' => 'Text Based Logo',
							'type' => 'select',
							'options' => $options_logo); 

		$options[] = array( 'name' => 'Custom Logo',
							'desc' => 'If image-based logo is selected, upload a logo for your theme, or specify the image address of your online logo. (http://yoursite.com/logo.png) [Max Width: 360px]',
							'id' => $shortname.'_logo',
							'type' => 'upload');
							
		$options[] = array( 'name' => 'Text Logo',
							'desc' => 'If text-based logo is selected, enter text to display on first row.',
							'id' => $shortname.'_logo1',
							'std' => 'ADVANCED',
							'type' => 'text');	
							
		$options[] = array( 'desc' => 'If text-based logo is selected, enter text to display on second row.',
							'id' => $shortname.'_logo2',
							'std' => 'NEWSPAPER',
							'type' => 'text');	
							
		$options[] = array( 'name' => 'Logo Padding Top',
							'desc' => 'Set a padding value between logo and top line.',
							'id' => $shortname.'_padding_top',
							'std' => 15,
							'class' => 'mini',
							'type' => 'text');	

		$options[] = array( 'name' => 'Logo Padding Bottom',
							'desc' => 'Set a padding value between logo and bottom line.',
							'id' => $shortname.'_padding_bottom',
							'std' => 15,
							'class' => 'mini',
							'type' => 'text');
							
		$options[] = array( 'name' => 'Logo Padding Left',
							'desc' => 'Set a padding value between logo and left line.',
							'id' => $shortname.'_padding_left',
							'std' => 0,
							'type' => 'text');
							
		$options[] = array( 'name' => 'Custom Favicon',
							'desc' => 'Upload a 16px x 16px png / gif image that will represent your website favicon.',
							'id' => $shortname.'_custom_favicon',
							'type' => 'upload'); 
							
		$options[] = array( 'name' => 'RSS',
							'desc' => 'Link to third party feed handler. <br/> [http://www.url.com]',
							'id' => $shortname.'_rssaddr',
							'type' => 'text'); 				
		
		$options[] = array( 'name' => 'Tracking Code',
							'desc' => 'Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.',
							'id' => $shortname.'_google_analytics',
							'type' => 'textarea'); 		
							
	$options[] = array( 'name' => 'Quotes', 'type' => 'heading');					
							
		$options[] = array( 'name' => 'Header Left Quote (only if Header with Quotes is activated)',
							'desc' => 'Left quote caption',
							'id' => $shortname.'_leftquotecap',
							'std' => 'Vladimir Putin',
							'type' => 'text'); 		
							
		$options[] = array( 'desc' => 'Left quote text',
							'id' => $shortname.'_leftquotetext',
							'std' => 'We have one Fatherland, one people and a common future',
							'type' => 'text'); 		

		$options[] = array(	'desc' => 'Left quote image. Max width 80px',
							'id' => $shortname.'_leftquoteimg',
							'type' => 'upload');
							
		$options[] = array( 'desc' => 'Left Quote Link <br /> eg. http://www.yahoo.com',
							'id' => $shortname.'_lquotelink',
							'type' => 'text'); 	
							
		$options[] = array( 'name' => 'Header Right Quote (only if Header with Quotes is activated)',
							'desc' => 'Right quote caption',
							'id' => $shortname.'_rightquotecap',
							'std' => 'Barack Obama',
							'type' => 'text'); 		
							
		$options[] = array( 'desc' => 'Right quote text',
							'id' => $shortname.'_rightquotetext',
							'std' => 'You know, my faith is one that admits some doubt',
							'type' => 'text'); 		

		$options[] = array(	'desc' => 'Right quote image. Max width 80px',
							'id' => $shortname.'_rightquoteimg',
							'type' => 'upload');
							
		$options[] = array( 'desc' => 'Right Quote Link <br /> eg. http://www.yahoo.com',
							'id' => $shortname.'_rquotelink',
							'type' => 'text'); 									
							
	$options[] = array( 'name' => 'Navigation', 'type' => 'heading');			

		$options[] = array( 'name' => 'Custom Navigation',
							'desc' => 'Replace masthead with a custom menu. [If selected, create a <a href="nav-menus.php" target="_blank">custom menu</a>]',
							'id' => $shortname.'_nav1',
							'std' => 0,
							'type' => 'checkbox');		

		$options[] = array( 'desc' => 'Replace primary navigation with a custom menu. [If selected, create a <a href="nav-menus.php" target="_blank">custom menu</a>]',
							'id' => $shortname.'_nav2',
							'std' => 0,
							'type' => 'checkbox');		

		$options[] = array( 'desc' => 'Replace secondary navigation with a custom menu. [If selected, create a <a href="nav-menus.php" target="_blank">custom menu</a>]',
							'id' => $shortname.'_nav3',
							'std' => 0,
							'type' => 'checkbox');	

		$options[] = array( 'desc' => 'Replace footer top navigation with a custom menu. [If selected, create a <a href="nav-menus.php" target="_blank">custom menu</a>]',
							'id' => $shortname.'_nav4',
							'std' => 0,
							'type' => 'checkbox');								
														
		$options[] = array( 'desc' => 'Replace footer bottom navigation with a custom menu. [If selected, create a <a href="nav-menus.php" target="_blank">custom menu</a>]',
							'id' => $shortname.'_nav5',
							'std' => 0,
							'type' => 'checkbox');								
														
		$options[] = array( 'name' => 'Sort Categories',
							'desc' => 'Display categories in ascending or descending order',
							'id' => $shortname.'_sort_cats',
							'type' => 'select',
							'class' => 'mini',
							'options' => $options_sort);

		$options[] = array( 'name' => 'Order Categories',
							'desc' => 'Display categories in alphabetical order, by category ID, or by the count of posts',
							'id' => $shortname.'_order_cats',
							'std' => 'name',
							'type' => 'select',
							'class' => 'mini',
							'options' => $options_order);
							
		$options[] = array( 'name' => 'Exclude Categories',
							'desc' => 'ID number of cat(s) to exclude from navigation (eg 1,2,3,4 - no spaces) <a href="http://www.gabfirethemes.com/how-to-check-category-ids/" target="_blank">How check category/page ID</a>',
							'id' => $shortname.'_ex_cats',
							'class' => 'mini',
							'type' => 'text'); 
							
		$options[] = array( 'name' => 'Exclude Pages',
							'desc' => 'ID number of page(s) to exclude from navigation (eg 1,2,3,4 - no spaces) <a href="http://www.gabfirethemes.com/how-to-check-category-ids/" target="_blank">How check category/page ID</a>',
							'id' => $shortname.'_ex_pages',
							'class' => 'mini',
							'type' => 'text');

	$options[] = array( 'name' => 'Categories', 'type' => 'heading');
		$options[] = array( "name" => "Do not duplicate posts",
							"desc" => "No matter what categories are selected for front page sections, if a post is displayed once do not duplicate it on front page",
							"id" => $shortname."_dnd",
							"std" => 1,
							"type" => "checkbox");	
							
		$options[] = array( 'name' => 'Featured Slider',
							'desc' => 'Select a category for featured entries.',
							'id' => $shortname.$themeid.'_fea_cat',
							'std' => 1,
							'type' => 'select',
							'options' => $options_categories);
							
		$options[] = array( 'desc' => 'Number of Entries to display.',
							'id' => $shortname.$themeid.'_nrfea',
							'std' => 6,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr); 
							
		$options[] = array( 'desc' => 'Featured Tag: Display posts assigned this tag to be listed on featured slider. <br/> [Note: Category will be disregarded if a tag name is filled in].',
							'id' => $shortname.$themeid.'_fea_tag',
							'class' => 'mini',
							'type' => 'text'); 
							
		$options[] = array( 'desc' => 'If this box is checked and featured tag section left empty, entries with custom field name: <strong>featured</strong> and custom field value: <strong>true</strong> will be displayed on slider.',
							'id' => $shortname.$themeid.'_fea_cf',
							'std' => 0,
							'type' => 'checkbox');	
	
		$options[] = array( 'desc' => 'Display most recent post entries on featured slider instead of category or tag.',
							'id' => $shortname.$themeid.'_fea_recent',
							'std' => 0,
							'type' => 'checkbox');	
		
		$options[] = array( 'name' => 'Below Featured',
							'desc' => 'Number of entries to display. <br />Set 0 to disable',
							'desc' => 'Select a category',
							'id' => $shortname.$themeid.'_cat1',
							'type' => 'select',
							'options' => $options_categories);
							
		$options[] = array( 'desc' => 'Number of Entries to display.',
							'id' => $shortname.$themeid.'_nr1',
							'std' => 2,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);

		$options[] = array( 'name' => 'Primary Mid Column',
							'desc' => 'Select a category for right hand of featured column',
							'id' => $shortname.$themeid.'_cat2',
							'type' => 'select',
							'options' => $options_categories);
							
		$options[] = array( 'desc' => 'Number of Entries to display.',
							'id' => $shortname.$themeid.'_nr2',
							'std' => 4,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);
							
		$options[] = array( 'name' => 'Secondary Content Left [Breaking News]',
							'desc' => 'Select a category',
							'id' => $shortname.$themeid.'_cat3',
							'type' => 'select',
							'options' => $options_categories);
							
		$options[] = array( 'desc' => 'Number of entries to display. <br />Set 0 to disable',
							'id' => $shortname.$themeid.'_nr3',
							'std' => 9,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);
							
		$options[] = array(	'desc' => 'Caption to display above entries. If empty; selected category name will be used.',
							'id' => $shortname.$themeid.'_name3',
							'std' => 'Breaking News',
							'type' => 'text',
							'class' => 'mini');
							
		$options[] = array( 'name' => 'Secondary Content Mid Column',
							'desc' => 'Select a category',
							'id' => $shortname.$themeid.'_cat4',
							'type' => 'select',
							'options' => $options_categories);
							
		$options[] = array( 'desc' => 'Number of entries to display. <br />Set 0 to disable',
							'id' => $shortname.$themeid.'_nr4',
							'std' => 4,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);
							
		$options[] = array( 'name' => 'Secondary Content Right Column',
							'desc' => 'Select a category',
							'id' => $shortname.$themeid.'_cat5',
							'type' => 'select',
							'options' => $options_categories);
							
		$options[] = array( 'desc' => 'Number of entries to display. <br />Set 0 to disable',
							'id' => $shortname.$themeid.'_nr5',
							'std' => 1,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);
							
		$options[] = array( 'name' => 'Tabbed Content #1',
							'desc' => 'Select a category',
							'id' => $shortname.$themeid.'_tabcat1',
							'type' => 'select',
							'options' => $options_categories);
							
		$options[] = array( 'desc' => 'Number of entries to display. <br />Set 0 to disable',
							'id' => $shortname.$themeid.'_tabnr1',
							'std' => 4,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);
							
		$options[] = array( 'name' => 'Tabbed Content #2',
							'desc' => 'Select a category',
							'id' => $shortname.$themeid.'_tabcat2',
							'type' => 'select',
							'options' => $options_categories);
							
		$options[] = array( 'desc' => 'Number of entries to display. <br />Set 0 to disable',
							'id' => $shortname.$themeid.'_tabnr2',
							'std' => 4,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);
							
		$options[] = array( 'name' => 'Tabbed Content #3',
							'desc' => 'Select a category',
							'id' => $shortname.$themeid.'_tabcat3',
							'type' => 'select',
							'options' => $options_categories);

		$options[] = array( 'desc' => 'Number of entries to display. <br />Set 0 to disable',
							'id' => $shortname.$themeid.'_tabnr3',
							'std' => 4,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);

		$options[] = array( 'name' => 'Tabbed Content #4',
							'desc' => 'Select a category',
							'id' => $shortname.$themeid.'_tabcat4',
							'type' => 'select',
							'options' => $options_categories);
							
		$options[] = array( 'desc' => 'Number of entries to display. <br />Set 0 to disable',
							'id' => $shortname.$themeid.'_tabnr4',
							'std' => 4,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);

		$options[] = array( 'name' => 'Tabbed Content #5',
							'desc' => 'Select a category',
							'id' => $shortname.$themeid.'_tabcat5',
							'type' => 'select',
							'options' => $options_categories);
							
		$options[] = array( 'desc' => 'Number of entries to display. <br />Set 0 to disable',
							'id' => $shortname.$themeid.'_tabnr5',
							'std' => 4,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);
							
		$options[] = array( 'name' => 'Subnews #1',
							'desc' => 'Select a category',
							'id' => $shortname.$themeid.'_cat11',
							'type' => 'select',
							'options' => $options_categories);
							
		$options[] = array( 'desc' => 'Number of entries to display. <br />Set 0 to disable',
							'id' => $shortname.$themeid.'_nr11',
							'std' => 1,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);
							
		$options[] = array( 'name' => 'Subnews #2',
							'desc' => 'Select a category',
							'id' => $shortname.$themeid.'_cat12',
							'type' => 'select',
							'options' => $options_categories);
							
		$options[] = array( 'desc' => 'Number of entries to display. <br />Set 0 to disable',
							'id' => $shortname.$themeid.'_nr12',
							'std' => 1,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);
							
		$options[] = array( 'name' => 'Subnews #3',
							'desc' => 'Select a category',
							'id' => $shortname.$themeid.'_cat13',
							'type' => 'select',
							'options' => $options_categories);
							
		$options[] = array( 'desc' => 'Number of entries to display. <br />Set 0 to disable',
							'id' => $shortname.$themeid.'_nr13',
							'std' => 1,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);
				
		$options[] = array( 'name' => 'Subnews #4',
							'desc' => 'Select a category',
							'id' => $shortname.$themeid.'_cat14',
							'type' => 'select',
							'options' => $options_categories);
							
		$options[] = array( 'desc' => 'Number of entries to display. <br />Set 0 to disable',
							'id' => $shortname.$themeid.'_nr14',
							'std' => 1,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);
							
		$options[] = array( 'name' => 'Subnews #5',
							'desc' => 'Select a category',
							'id' => $shortname.$themeid.'_cat15',
							'type' => 'select',
							'options' => $options_categories);
							
		$options[] = array( 'desc' => 'Number of entries to display. <br />Set 0 to disable',
							'id' => $shortname.$themeid.'_nr15',
							'std' => 1,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);
							
		$options[] = array( 'name' => 'Subnews #6',
							'desc' => 'Select a category',
							'id' => $shortname.$themeid.'_cat16',
							'type' => 'select',
							'options' => $options_categories);
							
		$options[] = array( 'desc' => 'Number of entries to display. <br />Set 0 to disable',
							'id' => $shortname.$themeid.'_nr16',
							'std' => 1,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);
							
		$options[] = array( 'name' => 'Subnews #7',
							'desc' => 'Select a category',
							'id' => $shortname.$themeid.'_cat17',
							'type' => 'select',
							'options' => $options_categories);
							
		$options[] = array( 'desc' => 'Number of entries to display. <br />Set 0 to disable',
							'id' => $shortname.$themeid.'_nr17',
							'std' => 1,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);
							
		$options[] = array( 'name' => 'Subnews #8',
							'desc' => 'Select a category',
							'id' => $shortname.$themeid.'_cat18',
							'type' => 'select',
							'options' => $options_categories);
							
		$options[] = array( 'desc' => 'Number of entries to display. <br />Set 0 to disable',
							'id' => $shortname.$themeid.'_nr18',
							'std' => 1,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);
							
		$options[] = array( 'name' => 'Homepage -Sidebar Videos',
							'desc' => 'Number of videos to display on homepage sidebar. <br/> Videos will be automatically pulled based on custom field.',
							'id' => $shortname.$themeid.'_nr20',
							'std' => 5,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);

		$options[] = array( 'name' => 'Innerpage - Mediabar',
							'desc' => 'Select number of posts to display on innerpages - media gallery in sidebar section. Set 0 to disable. <br/> Videos will be automatically pulled based on custom field.',
							'id' => $shortname.$themeid.'_nr19',
							'std' => 6,
							'class' => 'mini',
							'type' => 'select',
							'options' => $options_nr);	

							
	$options[] = array( 'name' => 'Sliders', 'type' => 'heading');
				
			$options[] = array( 'name' => 'Featured Slider',
								'desc' => 'Slide function',
								'id' => $shortname.$themeid.'_sfunc',
								'std' => 'fade',
								'type' => 'select',
								'options' => $options_feaslide);
					
			$options[] = array( 'desc' => 'Auto rotation speed. Slide to next slide in X seconds',
								'id' => $shortname.$themeid.'_featimeout',
								'std' => 15,
								'type' => 'select',
								'class' => 'mini',
								'options' => $options_nr);
								
			$options[] = array( 'desc' => 'The transition speed (in seconds)',
								'id' => $shortname.$themeid.'_feaspeed',
								'std' => 1,
								'type' => 'select',
								'class' => 'mini',
								'options' => $options_nr);

			$options[] = array( "name" => "Inner-Page Slider",
								"desc" => "Automatically create slideshow of uploaded photos in post entries to be displayed below post title. [Note: Select options include displaying slider site-wide, tag-based, or to disable completely].",
								"id" => $shortname."_inslider",
								"std" => "Disable",
								"type" => "select",
								"class" => "mini",
								"options" => $options_inslider);
								
			$options[] = array( "desc" => "If tag-based option is selected, display posts assigned this tag to be shown in inner-page slider. <br/> [Note: Posts with multiple image attachments and tagged with this key will display within slider].",
								"id" => $shortname."_inslider_tag",
								"std" => "",
								"class" => "mini",
								"type" => "text"); 
								
			$options[] = array( "desc" => "Enable auto rotation for innerpage slider.",
								"id" => $shortname.$themeid."_inner_rotate",
								"std" => 0,
								"type" => "checkbox");	
								
			$options[] = array( "desc" => "(If auto rotate enabled) define pause time between 2 slides. [in seconds]",
								"id" => $shortname.$themeid."_inner_pause",
								"std" => "5",
								"class" => "mini",
								"type" => "text");

	$options[] = array( 'name' => 'Image Handling', 'type' => 'heading');

		$options[] = array( 'name' => 'Catch First Image',
							'desc' => 'If enabled, built-in theme functions will scan post from top to bottom, catch the first image, and auto-generate a thumbnail for posts that do not have an image attached or custom field defined.',
							'id' => $shortname.'_catch_img',
							'std' => 0,
							'type' => 'checkbox');

		$options[] = array( 'desc' => 'Disable TimThumb and use WordPress Post Thumbnails instead [Check only if you are having problems with front page thumbnails].',
							'id' => $shortname.'_wpmumode',
							'std' => 0,
							'type' => 'checkbox');								
							
		$options[] = array( "name" => "Enable images on subnews",
							"desc" => "Check this box to display thumbnails on 8 vertical subnews blocks",
							"id" => $shortname.$themeid."_subphotos",
							"std" => 0,
							"type" => "checkbox");							
							
		$options[] = array( 'name' => 'Default \'No Image Found\' photo',
							'desc' => 'Enable on Featured slider',
							'id' => $shortname.$themeid.'_df1',
							'std' => 1,
							'type' => 'checkbox');
							
		$options[] = array( 'desc' => 'Enable on Media archive pages',
							'id' => $shortname.$themeid.'_df2',
							'std' => 1,
							'type' => 'checkbox');

	$options[] = array( 'name' => 'Footer', 'type' => 'heading');

		$options[] = array( 'name' => 'Enable Footer Text 1',
							'desc' => 'Activate to add the custom text on footer.',
							'id' => $shortname.$themeid.'_footer1',
							'std' => 0,
							'type' => 'checkbox');    

		$options[] = array( 'name' => 'Custom Text (Left)',
							'desc' => 'Custom HTML and Text that will appear in the footer of your theme.',
							'id' => $shortname.$themeid.'_footer1_text',
							'type' => 'textarea');
								
		$options[] = array( 'name' => 'Enable Custom Footer (Right)',
							'desc' => 'Activate to add the custom text below to the theme footer.',
							'id' => $shortname.$themeid.'_footer2',
							'std' => 0,
							'type' => 'checkbox');    

		$options[] = array( 'name' => 'Custom Text (Right)',
							'desc' => 'Custom HTML and Text that will appear in the footer of your theme.',
							'id' => $shortname.$themeid.'_footer2_text',
							'type' => 'textarea');

	$options[] = array( 'name' => 'Manage Ads', 'type' => 'heading');	
				
		$options[] = array( 'name' => 'Header Ad 728x60',
							'desc' => '728x60 ad code to top of site.',
							'id' => $shortname.$themeid.'_ad0',
							'type' => 'textarea');	
							
		$options[] = array( 'name' => 'Header Ad 468x60',
							'desc' => '468x60 ad code for header.',
							'id' => $shortname.$themeid.'_ad1',
							'type' => 'textarea');	
							
		$options[] = array( 'name' => 'Mainpage 120x600',
							'desc' => '120x600 ad mainpage - primary right.',
							'id' => $shortname.$themeid.'_ad2',
							'type' => 'textarea');	

		$options[] = array( 'name' => 'Mainpage 300x250 - top',
							'desc' => '300x250 ad mainpage - secondary right.',
							'id' => $shortname.$themeid.'_ad3',
							'type' => 'textarea');

		$options[] = array( 'name' => 'Mainpage 300x250 - bottom',
							'desc' => '300x250 ad mainpage - subnews right.',
							'id' => $shortname.$themeid.'_ad4',
							'type' => 'textarea');
							
		$options[] = array( 'name' => 'Innerpage 300x250 Sidebar - Top',
							'desc' => '300x250 sidebar ad - top.',
							'id' => $shortname.$themeid.'_ad5',
							'type' => 'textarea');
							
		$options[] = array( 'name' => 'Innerpage 300x250 Sidebar - Bottom',
							'desc' => '300x250 sidebar ad - bottom.',
							'id' => $shortname.$themeid.'_ad6',
							'type' => 'textarea');
							
		$options[] = array( 'name' => 'Innerpage 120x600 Sidebar',
							'desc' => '120x600 innerpage sidebar (single, post, page, default category template sidebars).',
							'id' => $shortname.$themeid.'_ad7',
							'type' => 'textarea');
							
		$options[] = array( 'name' => 'Magazine page layout - 120x600 Sidebar',
							'desc' => '120x600 magazine template sidebar ad.',
							'id' => $shortname.$themeid.'_ad8',
							'type' => 'textarea');

							
	$options[] = array( 'name' => 'Miscellaneous', 'type' => 'heading');	
					
		$options[] = array(	'name' => 'Archive - Media Category Template',
							'desc' => 'ID number of cat(s) to use media gallery template (seperate with comma if more than 1 category is entered)',
							'id' => $shortname.$themeid.'_mediatmp',
							'class' => 'mini',
							'type' => 'text'); 	
							
		$options[] = array(	'name' => 'Archive - 2 Column Category Template',
							'desc' => 'ID number of cat(s) to use 2 column category template (seperate with comma if more than 1 category is entered)',
							'id' => $shortname.$themeid.'_2col',
							'class' => 'mini',
							'type' => 'text'); 	
							
		$options[] = array(	'name' => 'Archive - Magazine Style Category Template',
							'desc' => 'ID number of cat(s) to use magazine style category template (seperate with comma if more than 1 category is entered)',
							'id' => $shortname.$themeid.'_mag',
							'class' => 'mini',
							'type' => 'text'); 
							
		$options[] = array( 'name' => 'Post Meta',
						'desc' => 'Display post details below single post page',
						'id' => $shortname.'_entry_meta',
						'std' => 0,
						'type' => 'checkbox');

		$options[] = array( "name" => "Facebook Meta Tags",
							"desc" => "Extract Facebook meta tags on header [Enable only if you are going to use Gabfire Share Widget]",
							"id" => $shortname."_fbonhead",
							"std" => 0,
							"type" => "checkbox");	

		$options[] = array( "name" => "Shortcodes",
							"desc" => "Enable shortcodes functionality",
							"id" => $shortname."_shortcodes",
							"std" => 0,
							"type" => "checkbox");								
							
		$options[] = array( "name" => "Widget Map",
							"desc" => "Display the location of widgets on front page. After checking widget locations <strong>be sure to uncheck this option</strong>",
							"id" => $shortname."_widget",
							"std" => 0,
							"type" => "checkbox");
								
	$options[] = array( 'name' => 'Custom Styling', 'type' => 'heading');	
	
	
		$options[] = array( 'name' => 'Custom CSS',
							'desc' => 'If you have custom CSS code you wish to apply, enter it in this block.',
							'id' => $shortname.'_custom_css',
							'type' => 'textarea');

		$options[] = array( 'desc' => 'Enable custom styles.',
							'id' => $shortname.$themeid.'_customcolors',
							'std' => 0,
							'type' => 'checkbox');
							
			$options[] = array( 'desc' => 'Link Color',
								'id' => $shortname.$themeid.'_linkclr',
								'type' => 'color');
								
			$options[] = array( 'desc' => 'Post title link color',
								'id' => $shortname.$themeid.'_ptaclr',
								'type' => 'color');
								
			$options[] = array( 'desc' => 'Post title hovered link color',
								'id' => $shortname.$themeid.'_ptahovclr',
								'type' => 'color');
								
			$options[] = array( 'desc' => 'Hyperlinked widget title color',
								'id' => $shortname.$themeid.'_hwidgetclr',
								'type' => 'color');
								
			$options[] = array( 'desc' => 'Masthead date color',
								'id' => $shortname.$themeid.'_mhdate',
								'type' => 'color');
								
			$options[] = array( 'desc' => 'Masthead, custom menu; active/hover color',
								'id' => $shortname.$themeid.'_mhmenu',
								'type' => 'color');
								
			$options[] = array( 'desc' => 'If text based logo is activated, select a color for second row text',
								'id' => $shortname.$themeid.'_sloganclr',
								'type' => 'color');

			$options[] = array( 'desc' => 'Quotes caption color',
								'id' => $shortname.$themeid.'_quotecapclr',
								'type' => 'color');					
								
			$options[] = array( 'desc' => 'Main navigation active/hover color',
								'id' => $shortname.$themeid.'_mnavclr',
								'type' => 'color');					
								
			$options[] = array( 'desc' => 'Featured slider, small thumbnail top border color',
								'id' => $shortname.$themeid.'_thumbbrdclr',
								'type' => 'color');					
								
			$options[] = array( 'desc' => 'Innper page sidebar, widget title color',
								'id' => $shortname.$themeid.'_hwidgetinclr',
								'type' => 'color');		

			$options[] = array( 'desc' => 'Innper page sidebar, widget title background color',
								'id' => $shortname.$themeid.'_hwidgetbgclr',
								'type' => 'color');									
								
			$options[] = array( 'desc' => 'Border left color on of post title on inner pages',
								'id' => $shortname.$themeid.'_titlebrdclr',
								'type' => 'color');	
								
					
	update_option('of_template',$options); 					  
	update_option('of_themename',$themename);   
	update_option('of_shortname',$shortname);

	return $options;
}
?>