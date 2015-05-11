<?php
  /*
  Plugin Name: outbrain
  Plugin URI: http://wordpress.org/extend/plugins/outbrain/
  Description: A WordPress plugin to deal with the <a href="http://www.outbrain.com">Outbrain</a> blog posting rating system.
  Author: outbrain
  Version: 8.0.0.0
  Author URI: http://www.outbrain.com
  */

  $ob_pi_directory = '';
  if(basename(dirname(__FILE__)) == 'mu-plugins')
    $ob_pi_directory = 'outbrain/';

  include $ob_pi_directory . 'ob_versionControl.php';

  $outbrain_plugin_version = "8.0.0.0_" . $userType;


  // constants
  $outbrain_start_comment = "<!-- OBSTART:do_NOT_remove_this_comment -->";
  $outbrain_end_comment   = "<!-- OBEND:do_NOT_remove_this_comment   -->";


  // add admin options page
  function outbrain_add_options_page() {
    add_options_page('Outbrain options', 'Outbrain Options', 8, basename(__FILE__), 'outbrain_options_form');
  }

  function getAdminPage() {
    if(function_exists('admin_url')) {
      $url = admin_url("options-general.php") . "?page=outbrain.php";
    } else {
      $url = $_SERVER[ 'REQUEST_URI' ];
    }

    return $url;
  }

  function outbrain_globals_init() {
    if(!defined('WP_CONTENT_URL'))
      define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
    if(!defined('WP_CONTENT_DIR'))
      define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
    if(!defined('WP_PLUGIN_URL'))
      define('WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins');
    if(!defined('WP_PLUGIN_DIR'))
      define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
  }

  // Add settings link on plugin page
  function outbrain_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=outbrain.php">Settings</a>';
    array_unshift($links, $settings_link);

    return $links;
  }

  function outbrain_options_form() {

    global $ob_pi_directory;

    $maxPages = 6;

    /*
    option: outbrain_pages_list
    pages list
    0: is_home (home page)
    1: is_single (single post)
    2: is_page (page)
    3: is_archive (some archive. Category, Author, Date based and Tag pages are all types of Archives)
    */

    $selected_pages      = (isset($_POST[ 'select_pages' ])) ? $_POST[ 'select_pages' ] : get_option("outbrain_pages_list");

    if(isset($_POST[ 'claim' ])) {
      $key = isset($_POST[ 'key' ]) ? $_POST[ 'key' ] : '';
      if($key != '') {
        update_option("outbrain_claim_key", $key);
      }
      die; // end of file
    } else if(isset($_POST[ 'saveClaimStatus' ])) {
      update_option("outbrain_claim_status_num", $_POST[ 'status' ]);
      update_option("outbrain_claim_status_string", $_POST[ 'statusString' ]);
      die; // end of file
    } else if(isset($_POST[ 'reset' ]) && ($_POST[ 'reset' ] == "true")) {
      update_option("outbrain_claim_key", "");
    } else if(isset($_POST[ 'keySave' ]) && ($_POST[ 'keySave' ] == "true")) {
      $key = isset($_POST[ 'key' ]) ? $_POST[ 'key' ] : '';
      if($key != '') {
        update_option("outbrain_claim_key", $key);
        echo "<div id='message' class='updated fade'><p><strong>Key saved!</strong></p></div>";
      }

    } else if(isset($_POST[ 'outbrain_send' ])) { // form sent
      $selected_pages = (isset($_POST[ 'select_pages' ])) ? $_POST[ 'select_pages' ] : array();
      update_option("outbrain_pages_list", $selected_pages);
      ?>
    <div id="message" class="updated fade">
        <p>
            <strong><?php _e('Options saved.'); ?></strong>
        </p>
    </div>
    <?php
    }
    ?>

  <div class="wrap" style="text-align:left;direction:ltr;">

      <table border="0" style="width:100%;">
          <tr>
              <td width="1%" nowrap="nowrap"><h2><?php _e('Outbrain options', 'outbrain') ?></h2></td>
              <td align="right"><a href="http://getsatisfaction.com/outbrain" target="_blank" style="font-size:13px;">Outbrain Support</a></td>
              <td style="width:20px">&nbsp;</td>
          </tr>
      </table>

      <form method="post" id="outbrain_form" name="outbrain_form" action="<?php echo getAdminPage(); ?>">
          <input type="hidden" name="export" id="export" value="false">
          <input type="hidden" name="reset" id="reset" value="false">
          <input type="hidden" name="keySave" id="keySave" value="false">
          <input type="hidden" name="obVersion" id="obVersion" value="<?php echo getVersion(); ?>">
          <input type="hidden" name="obCurrentKey" id="obCurrentKey" value="<?php outbrain_returnClaimCode() ?>">

        <?php
        if(function_exists('wp_nonce_field')) {
          wp_nonce_field('update-options');
        }

        //get the path to plug ins
        $pathOfAdmin = outbrain_get_plugin_admin_path();
        ?>
          <input type="hidden" name="outbrain_send" value="send"/>
          <ul style="position: relative;">
              <li><div id="block_claim" class="option_board_right" style="display:none;">
                  <a href="javascript:void(0)" onclick="toggleStateValidate(this)" class="blockTitle">Verify Blog ownership to Outbrain
                      <span id="claim_title" style="font-weight:bold"> (This blog is already claimed)</span></a>

                  <div id="block_claim_inner" class="block_inner" style="display:none;">
                      <div>
                          Outbrain key is used to verify your blog ownership.<br/>
                          Your blog statistics and widget settings are available for you at <a href="https://my.outbrain.com/manage/add" target="_blank">my.outbrain.com</a>
                      </div>
                      <div id="outbrain_key_insertion">
                          Outbrain Key

                          <?php $key = get_option('outbrain_claim_key'); ?>

                          <input type="text" <?php print((isset($key) && strlen($key) > 0) ? " readonly='readonly' " : ""); ?> size="35" name="key" value="<?php print($key); ?>" onkeyup=""/>

                          <?php if(isset($key) && strlen($key) > 0) { ?>
                            <button type="button" class="button" id="claim_key" name="claim_key" onclick='doClaim("<?php print($key); ?>")'>Claim this blog</button>
                            <button type="button" class="button" id="claim_reset" name="claim_reset" onclick="outbrainReset();">Reset</button>
                          <?php } else { ?>
                            <button type="submit" class="button" id="claim_save" name="claim_save" onclick="outbrainKeySave();">Claim key</button>
                          <?php } ?>



                          <span id="claimLoadingImage">&nbsp;</span>
                      </div>
                      <div id="after_claiming"></div>
                  </div>
              </div></li>

              <li><div id="block_settings" class="option_board_right" style="display:none">
                  <a href="javascript:void(0)" onclick="toggleStateValidate(this)" class="blockTitle"> Settings</a>

                  <div id="block_settings_inner" style="display:none" class="block_inner">

                      <div id="block_pages" class="option_board_down" style="">
                          <a href="javascript:void(0)" onclick="toggleStateValidate(this)" class="blockTitle">Show In</a>

                          <div id="block_pages_inner" style="" class="block_inner">
                            <?php
                            $select_page_texts = array( 'Home page', 'Single post', 'Page', 'Archive (category page, author page, date page and also tag page in WP 2.3+)', 'Attachment', 'Excerpt' );

                            for($i = 0; $i < $maxPages; $i++) {
                              $checked      = (in_array($i, $selected_pages))        ? " checked='checked' " : "";
                              ?>
                                <div class="block_inner"><label><input type="checkbox" name="select_pages[]" <?php echo $checked; ?> value="<?php echo $i; ?>"> <?php echo $select_page_texts[ $i ]; ?> </label></div>
                              <?php
                            }
                            ?>
                          </div>
                      </div>
                  </div>
              </div></li>

              <li><div id="block_additional_setting" class="option_board_down" style="display:none">
                  <a href="javascript:void(0)" onclick="toggleStateValidate(this)" class="blockTitle">Additional Features</a>

                  <div id="block_additional_setting_inner" style="display:block" class="block_inner">
                      <div id="block_additional_instruction" style="display:none">
                          Blog ownership verification is required to enable additional customization features.
                      </div>
                      <ul>
                          <li><div id="block_custom_settings" class="additional_settings" style="display:none;">
                              <a href='https://my.outbrain.com/ln/BlogSettings?key=<?php outbrain_returnEncodeClaimCode()  ?>'> Configure outbrain settings </a>
                          </div></li>
                      </ul>
                  </div>
              </div></li>


          </ul>
          <div id="block_loader" style="text-align:center;width:500px;margin:auto;padding:10px;display:block" class="">
              <img alt="Loading..." width="16" height="16" src="<?php echo $pathOfAdmin, $ob_pi_directory ?>ob_spinner.gif"/>
              <b>Loading...</b>
          </div>

          <p id="block_submit" class="submit options" style="text-align:left;display:none">
              <input type="submit" name="Submit" value="<?php _e('Update Options') ?>"/>
          </p>


      </form>
  </div>
  <script type="text/javascript">
      var pathOfPlug = '<?php echo $pathOfAdmin ?>';
      //check if claim already

      var key = "<?php outbrain_returnClaimCode()  ?>";

      if(key.length > 0) {
          outbrain_isUserClaim(key);
      }
      else {
          outbrain_noClaimMode();//no key - show other options
      }
  </script>
  <?php
  }

  function getVersion() {
    global $outbrain_plugin_version;

    return $outbrain_plugin_version;
  }

  // display the plugin
  function outbrain_display($content) {
    global $post_ID, $outbrain_start_comment, $outbrain_end_comment, $outbrain_plugin_version;

    $fromDB = get_option("outbrain_pages_list");
    $where       = ((isset($fromDB)) && (is_array($fromDB)))           ? $fromDB      : array();    //now get recommendations array


    //basic conditions to display widget on the page.
    $bGenericIsRightPlaceForWidget = (!(is_feed()) && !(is_preview())) && (((is_home()) && (in_array(0, $where))) || ((is_single()) && (!is_attachment()) && (in_array(1, $where))) || ((is_page()) && (in_array(2, $where))) || ((is_archive()) && (in_array(3, $where))) || ((is_attachment()) && (in_array(4, $where))));

    //check conditions and "break" as necessary.
    if(! $bGenericIsRightPlaceForWidget ){return($content); }//return un-modified content. not a right place to put a widget.

    //-- system optimal.
    $installation_time_string = get_option('installation_time');
    $recMode                  = get_option('outbrain_recMode');

    //-- update first time installation date.
    if(!isset($installation_time_string) || (isset($installation_time_string) && empty($installation_time_string))) {
      $installation_time_string = time();
      update_option("installation_time", $installation_time_string);
    }

    //-- fallback for recMode.
    if(!isset($recMode) || (isset($recMode) && empty($recMode))) {
      $recMode = "brn_strip";
      update_option("outbrain_recMode", $recMode);
    }

    //-- inject call to Outbrain Widget. Marvelous!!!
    $content .= $outbrain_start_comment;
    $content .= '<script type=\'text/javascript\' >'
                                                      . ' var OB_platformType=3;'
                                                      . ' var OB_PlugInVer=\'' . $outbrain_plugin_version . '\';'
              . '</script>';
	$content .= '<div class="outbrainwidget">';		  /* MK */
    $content .= '<div class=\'OUTBRAIN\' data-src=\'' . get_permalink($post_ID) . '\' ></div>';
	$content .= '</div>';  /* MK */
    $content .= '<script type=\'text/javascript\' async=\'async\' src=\'' . ((isset($_SERVER[ 'HTTPS' ]) && $_SERVER[ 'HTTPS' ] == "on") ? "https" : "http") . '://widgets.outbrain.com/outbrainLT.js\'></script>';
    $content .= $outbrain_end_comment;

    return $content;
  }

  /**
   * a registered filter-like, handling excerpt content.
   * @param $content
   * @return string presentable
   */
  function outbrain_display_excerpt($content) {
    global $outbrain_start_comment, $outbrain_end_comment;

    $fromDB = get_option("outbrain_pages_list");

    $where = ((isset($fromDB)) && (is_array($fromDB))) ? $fromDB : array();   //  5 is for Excerpt
    if(!in_array(5, $where)){ return($content); }                             //  -- the return, no further process if home page, single post, page, archive(category/tox.) or an attachment.

    //-- embedding into excerpt.
    $pos = strpos($content, $outbrain_start_comment);
    $posEnd = strpos($content, $outbrain_end_comment);
    if ($pos) {
      if ($posEnd == false) {
        $content = str_replace(substr($content, $pos, strlen($content)), '', $content);
      } else {
        $content = str_replace(substr($content, $pos, $posEnd - $pos + strlen($outbrain_end_comment)), '', $content);
      }
    }

    //-- result
    $content = $content . outbrain_display('');

    return $content;
  }

  // print the css / js functions of the options page

  function outbrain_get_plugin_admin_path() {
    $site_url = get_option("siteurl");
    // make sure the url ends with /
    $last = substr($site_url, strlen($site_url) - 1);
    if($last != "/")
      $site_url .= "/";
    // calculate base url based on current directory.
    $base_len = strlen(ABSPATH);
    $suffix   = substr(dirname(__FILE__), $base_len) . "/";
    // fix windows path spectator to url path separator.
    $suffix   = str_replace("\\", "/", $suffix);
    $base_url = $site_url . $suffix;

    return $base_url;
  }

  function outbrain_get_plugin_place() {
    $ref = dirname(__FILE__);

    return $ref;
  }

  function outbrain_admin_script() {
    global $ob_pi_directory;
    if((strpos($_SERVER[ 'QUERY_STRING' ], 'outbrain.php') == false)) {
      // no Outbrain's options page.
      return;
    }

    $base_url = outbrain_get_plugin_admin_path();
    ?>
  <link rel="stylesheet" href="<?php echo $base_url, $ob_pi_directory ?>ob_style.css" type="text/css"/>
  <script type="text/javascript" src="<?php echo $base_url, $ob_pi_directory ?>jquery-1.8.3.min.js"></script>
  <script type="text/javascript" src="<?php echo $base_url, $ob_pi_directory?>ob_script.js"></script>


  <?php
  }

  function outbrain_addClaimCode() {
    $key = get_option('outbrain_claim_key');
    if($key == '') {
      return;
    }
    echo "<meta name='OBKey' content='$key' />\r\n";
  }

  function outbrain_returnClaimCode() {
    $key = get_option('outbrain_claim_key');
    if($key == '') {
      return;
    }
    echo "$key";
  }

  function outbrain_returnEncodeClaimCode() {
    $key = get_option('outbrain_claim_key');
    if($key == '') {
      return;
    }
    $encodeKey = urlencode($key);
    echo "$encodeKey";
  }

  outbrain_globals_init();

  // add filters
  $outbrain_plugin = plugin_basename(__FILE__);

  add_filter("plugin_action_links_$outbrain_plugin", 'outbrain_settings_link');
  add_filter('the_content', 'outbrain_display');
  add_filter('the_excerpt', 'outbrain_display_excerpt');
  add_filter('wp_head', 'outbrain_addClaimCode', 1);
  add_action('admin_menu', 'outbrain_add_options_page');
  add_action('admin_head', 'outbrain_admin_script');

  add_option('outbrain_pages_list', array( 0, 1, 2, 3, 4, 5 ));
  add_option('outbrain_claim_key', '');
  add_option('outbrain_claim_status_num', '');
  add_option('outbrain_claim_status_string', '');
  add_option('outbrain_recMode', $recMode);

?>
