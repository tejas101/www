<?php
function gabfire_add_admin(){
    global $_registered_pages;  
    $of_page = array();
    $of_page[] = add_menu_page(__('Gabfire Themes Options', 'optionsframework'), __('Gabfire Themes', 'optionsframework'), 'edit_theme_options', 'gabfire_theme', 'optionsframework_page', get_template_directory_uri() . '/framework/images/gabfire-icon.png', 36 );
    $of_page[] = add_submenu_page( 'gabfire_theme', 'Gabfire Themes Options', 'Theme Options', 'edit_theme_options', 'gabfire_theme');
    $of_page[] = add_submenu_page( 'gabfire_theme', 'Gabfire Blog', 'Gabfire Blog', 'edit_theme_options', 'gabfire_blog', 'gabfire_blog');
    $of_page[] = add_submenu_page( 'gabfire_theme', 'Gabfire New Themes', 'New Themes', 'edit_theme_options', 'gabfire_new', 'gabfire_new'); 
  
    //Adds hidden changelog page.
    $hookname = get_plugin_page_hookname('gabfire_changelog', 'admin.php');  
    if (!empty($hookname)) {  
        add_action($hookname, 'gabfire_changelog_page');
        add_action("admin_print_styles-$hookname",'optionsframework_load_styles');
        add_action("admin_print_scripts-$hookname", 'optionsframework_load_scripts');
    }  
    $_registered_pages[$hookname] = true; 
     
    // Adds actions to hook in the required css and javascript
    foreach($of_page as $page){
        add_action("admin_print_styles-$page",'optionsframework_load_styles');
        add_action("admin_print_scripts-$page", 'optionsframework_load_scripts');
    }
}
add_action('admin_menu','gabfire_add_admin');
  
    
function gabfire_blog(){
?>
    <div class="wrap">
        <div class="metabox-holder">
             
                <?php gab_adminheader(); ?>
                 
                <div id="optionsframework" class="postbox">
  
                                            <div class="group" id="of-option-gabfire">
                                                    <h3>Stay Updated and Get Social with Gabfire Themes</h3>
                                                    <div class="section section-text" style="margin-bottom:10px">                             
                                                        <div class="gab_subscribe">
                                                            <h4 class="heading">STAY CONNECTED</h4>
                                                            <ul>
                                                                <li><a target="_blank" href="http://www.gabfirethemes.com/feed/" class="gab_rss">Subscribe to RSS</a></li>
                                                                <li><a target="_blank" href="http://eepurl.com/dknlQ" class="gab_email">Subscribe to our newsletter</a></li>
                                                                <li><a target="_blank" href="http://www.twitter.com/gabfirethemes" class="gab_twit">Follow on Twitter</a></li>
                                                                <li><a target="_blank" href="http://www.facebook.com/pages/Gabfire-Premium-Themes/330773148827" class="gab_fb">Friend Us on Facebook</a></li>
                                                                <li><a target="_blank" href="http://www.linkedin.com/company/gabfire-themes" class="gab_linkedin">Connect on LinkedIn</a></li>
                                                            </ul>
                                                        </div>
                                                        <div class="gab_explore">
                                                            <h4 class="heading">EXPLORE GABFIRE THEMES</h4>
                                                            <ul>
                                                                <li><a target="_blank" href="http://www.gabfirethemes.com/category/themes">See All Themes</a></li>
                                                                <li><a target="_blank" href="http://www.gabfirethemes.com/services/">Our Services</a></li>
                                                                <li><a target="_blank" href="http://www.gabfirethemes.com/faq/">Frequently Asked Qeustions</a></li>
                                                                <li><a target="_blank" href="http://www.gabfirethemes.com/affiliate-program/">Become an Affiliate</a></li>
                                                                <li><a target="_blank" href="http://www.gabfirethemes.com/contact/">Contact</a></li>
                                                            </ul>
                                                        </div>
                                                        <div class="clear"></div>
                                                    </div>
                                             
                                                    <div style="padding:0 15px 0">
                                                        <?php gabfire_dashboard_widget_function(); ?>
                                                    </div>
             
                                                    <div class="clear"></div>
                                            </div>                        
  
                </div>
        </div>
    </div>
<?php
}
function gabfire_new(){ ?>
<div class="wrap">
    <div class="metabox-holder">
        <?php gab_adminheader(); ?>
         
        <div id="optionsframework" class="postbox" style="height:600px;overflow-x:auto;overflow-y:hidden">
            <iframe src="http://www.gabfirethemes.com/gabfire-all-themes.php" frameborder="0" height="100%" width="100%" scrolling="auto"></iframe> 
        </div>
    </div>
</div>
  
<?php }