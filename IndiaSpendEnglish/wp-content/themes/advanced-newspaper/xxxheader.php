<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="google-site-verification" content="sCh0c_UG8ezTpCMUKLZ-O1tMVB0VXJDh8AZjWLIXlKc" />
<title><?php gab_title(); ?></title>

	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php if ( of_get_option('of_rssaddr') <> '' ) { echo of_get_option('of_rssaddr'); } else { echo bloginfo('rss2_url'); } ?>" />	
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php
		if ( is_singular() && of_get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );
		wp_head();
	?>	
	
	<?php if(file_exists(TEMPLATEPATH . '/custom.css')) { ?>
		<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'template_url' ); ?>/custom.css" />
	<?php } ?>	
	
	<?php if(of_get_option('of_an_customcolors', 0) == 1) {  include_once(TEMPLATEPATH . '/custom-colors.php'); } ?>
	<script type="text/javascript">
function popularpost(id){
var hash="#"+id+"1";
var tag="#"+id;
$("#most_read1").hide();
$("#most_popular1").hide();
$("#most_mail1").hide();
$(hash).show();
$(".most_section a").removeClass('active_most');
$(tag).addClass('active_most');
}
</script>

</head>

<body <?php body_class(); ?>>
<?php global $do_not_duplicate; ?>

<?php
	if(file_exists(TEMPLATEPATH . '/ads/header_728x90/'. current_catID() .'.php') && (is_single() || is_category())) {
		include_once(TEMPLATEPATH . '/ads/header_728x90/'. current_catID() .'.php');
	}
	else {
		include_once(TEMPLATEPATH . '/ads/header_728x90.php');
	}
?>

<div class="wrapper">
	<div id="masthead">
		<ul class="mastheadnav dropdown">
			<?php
			if(of_get_option('of_nav1', 0) == 1) { 
				wp_nav_menu( array('theme_location' => 'masthead', 'container' => false, 'items_wrap' => '%3$s'));
			} else { ?>
			
				<li class="date">
					<script type="text/javascript">
						<!--
						var mydate=new Date()
						var year=mydate.getYear()
						if (year < 1000)
						year+=1900
						var day=mydate.getDay()
						var month=mydate.getMonth()
						var daym=mydate.getDate()
						if (daym<10)
						daym="0"+daym
						var dayarray=new Array("<?php _e('Sunday','advanced'); ?>","<?php _e('Monday','advanced'); ?>","<?php _e('Tuesday','advanced'); ?>","<?php _e('Wednesday','advanced'); ?>","<?php _e('Thursday','advanced'); ?>","<?php _e('Friday','advanced'); ?>","<?php _e('Saturday','advanced'); ?>")
						var montharray=new Array("<?php _e('January','advanced'); ?>","<?php _e('February','advanced'); ?>","<?php _e('March','advanced'); ?>","<?php _e('April','advanced'); ?>","<?php _e('May','advanced'); ?>","<?php _e('June','advanced'); ?>","<?php _e('July','advanced'); ?>","<?php _e('August','advanced'); ?>","<?php _e('September','advanced'); ?>","<?php _e('October','advanced'); ?>","<?php _e('November','advanced'); ?>","<?php _e('December','advanced'); ?>")
						document.write(""+dayarray[day]+", "+montharray[month]+" "+daym+", "+year+"")
						// -->
					</script>
				</li>
				<li><a class="show" href="#sconnected"><?php _e('Stay Connected', 'advanced'); ?></a></li>			
			<?php } ?>
		</ul>
		<div class="hide">
			<div id="sconnected"> 
				<?php if ( ! dynamic_sidebar( 'MastheadOverlay' ) ) : ?>
					<h3 class="widgettitle">Widgetized Section</h3>
					<p>Go to Admin &raquo; Appearance &raquo; Widgets &raquo;  and move <strong>Gabfire Widget: Social</strong> into that <strong>MastheadOverlay</strong> zone</p>
				<?php endif; if(of_get_option('of_an_widget', 0) == 1) { echo '<span class="widget">MastheadOverlay</span>'; } ?>
			</div>
		</div>
		
		<div id="search">
			<?php get_search_form(); ?>
		</div><!-- /search -->
		<div class="clear"></div>
		
	</div><!-- /masthead -->

	<div id="header">
		<?php 
		
		/* ******************** IF HEADER WITH A SINGLE IMAGE IS ACTIVATED *************************** */
		if(of_get_option('of_header_type') == 'singleimage') { ?>
			<a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('description'); ?>">
				<img src="<?php echo of_get_option('of_himageurl'); ?>" id="header_banner" alt="<?php bloginfo('name'); ?>" title="<?php bloginfo('name'); ?>"/>
			</a>
		<?php } 
		
		/* ******************** IF HEADER LOGO - BANNER IS ACTIVATED ********************************* */
		elseif(of_get_option('of_header_type') == 'logobanner') { ?>
			
			<div class="logo" style="float:left;margin:0;padding:<?php echo of_get_option('of_padding_top', 0); ?>px 0px <?php echo of_get_option('of_padding_bottom', 0); ?>px <?php echo of_get_option('of_padding_left', 0); ?>px;">	
				<?php if ( of_get_option('of_logo_type', 'text') == 'image') { ?>
					<h1>
						<a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('description'); ?>">
							<img src="<?php echo of_get_option('of_logo'); ?>" alt="<?php bloginfo('name'); ?>" title="<?php bloginfo('name'); ?>"/>
						</a>
					</h1>
				<?php } else { ?>
					<h1>
						<span class="name"><a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('description'); ?>"><?php echo of_get_option('of_logo1'); ?></a></span>
						<span class="slogan"><a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('description'); ?>"><?php echo of_get_option('of_logo2'); ?></a></span>
					</h1>
				<?php } ?>
			</div><!-- logo -->
			
			<div class="banner">
				<?php
					if(file_exists(TEMPLATEPATH . '/ads/header_468x60/'. current_catID() .'.php') && (is_single() || is_category())) {
						include_once(TEMPLATEPATH . '/ads/header_468x60/'. current_catID() .'.php');
					}
					else {
						include_once(TEMPLATEPATH . '/ads/header_468x60.php');
					}
				?>
			</div>
			
			<div class="clear"></div>
		<?php } 
		
		/* ******************** IF HEADER WITH QUOTES IS ACTIVATED ********************************* */
		else { ?>
			<div class="themequote quoteleft">
				<?php if ( of_get_option('of_leftquoteimg') <> '' ) { ?>
					<span class="img">
						<?php if ( of_get_option('of_lquotelink') <> '' ) { echo '<a href="' . of_get_option('of_lquotelink') . '" rel="bookmark">'; } ?>
							<img src="<?php echo of_get_option('of_leftquoteimg'); ?>" alt="" />
						<?php if ( of_get_option('of_lquotelink') <> '' ) { echo '</a>'; } ?>
					</span>
				<?php } ?>
				<span class="quotetext">
					<span class="quotecaption">
						<?php 
						if ( of_get_option('of_lquotelink') <> '' ) { echo '<a href="' . of_get_option('of_lquotelink') . '" rel="bookmark">'; } 
							echo of_get_option('of_leftquotecap');
						if ( of_get_option('of_lquotelink') <> '' ) { echo '</a>'; } 
						?>
					</span>
					<span class="quote">
						<?php 
						if ( of_get_option('of_lquotelink') <> '' ) { echo '<a href="' . of_get_option('of_lquotelink') . '" rel="bookmark">'; } 
							echo of_get_option('of_leftquotetext');
						if ( of_get_option('of_lquotelink') <> '' ) { echo '</a>'; } 
						?>
					</span>
				</span>
			</div><!-- themequote quoteleft -->
			
			<div class="logo" style="padding:<?php echo of_get_option('of_padding_top', 0); ?>px 0px <?php echo of_get_option('of_padding_bottom', 0); ?>px <?php echo of_get_option('of_padding_left', 0); ?>px;">	
				<?php if ( of_get_option('of_logo_type', 'text') == 'image') { ?>
					<h1>
						<a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('description'); ?>">
							<img src="<?php echo of_get_option('of_logo'); ?>" alt="<?php bloginfo('name'); ?>" title="<?php bloginfo('name'); ?>"/>
						</a>
					</h1>
				<?php } else { ?>
					<h1>
						<span class="name"><a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('description'); ?>"><?php echo of_get_option('of_logo1'); ?></a></span>
						<span class="slogan"><a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('description'); ?>"><?php echo of_get_option('of_logo2'); ?></a></span>
					</h1>
				<?php } ?>
			</div><!-- logo -->
			
			<div class="themequote quoteright">
				
				<?php if ( of_get_option('of_rightquoteimg') <> '' ) { ?>
					<span class="img">
						<?php if ( of_get_option('of_rquotelink') <> '' ) { echo '<a href="' . of_get_option('of_rquotelink') . '" rel="bookmark">'; } ?>
							<img src="<?php echo of_get_option('of_rightquoteimg'); ?>" alt="" />
						<?php if ( of_get_option('of_rquotelink') <> '' ) { echo '</a>'; } ?>
					</span>
				<?php } ?>
				
				<span class="quotetext">
					<span class="quotecaption">
						<?php 
						if ( of_get_option('of_rquotelink') <> '' ) { echo '<a href="' . of_get_option('of_rquotelink') . '" rel="bookmark">'; } 
							echo of_get_option('of_rightquotecap');
						if ( of_get_option('of_rquotelink') <> '' ) { echo '</a>'; } 
						?>
					</span>
					<span class="quote">
						<?php 
						if ( of_get_option('of_rquotelink') <> '' ) { echo '<a href="' . of_get_option('of_rquotelink') . '" rel="bookmark">'; } 
							echo of_get_option('of_rightquotetext');
						if ( of_get_option('of_rquotelink') <> '' ) { echo '</a>'; } 
						?>
					</span>
				</span>
				
			</div><!-- themequote quoteright -->
			<div class="clear"></div>
		<?php } ?>
			

	</div><!-- /header -->

	<div id="mainmenu">
		<ul class="mainnav dropdown">
			<?php
			if(of_get_option('of_nav2', 0) == 1) { 
				wp_nav_menu( array('theme_location' => 'primary-navigation', 'container' => false, 'items_wrap' => '%3$s'));
			} else { ?>
			
				<li class="first<?php if(is_home() ) { ?> current-cat<?php } ?>"><a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('description'); ?>"><?php _e('Home','advanced'); ?></a></li>
				<?php wp_list_categories('orderby='. of_get_option('of_order_cats') .'&order='. of_get_option('of_sort_cats') .'&title_li=&exclude='. of_get_option('of_ex_cats')); ?>
			<?php } ?>	
		</ul>
		<div class="clear"></div>
	</div><!-- /mainmenu -->
	
	<div id="submenu">
		<ul class="subnav dropdown">
			<?php
			if(of_get_option('of_nav3', 0) == 1) { 
				wp_nav_menu( array('theme_location' => 'secondary-navigation', 'container' => false, 'items_wrap' => '%3$s'));
			} else { ?>
				<?php wp_list_pages('sort_column=menu_order&title_li=&exclude='. of_get_option('of_ex_pages')); ?>
			<?php } ?>	
		</ul>
		<div class="clear"></div>
	</div><!-- /submenu -->