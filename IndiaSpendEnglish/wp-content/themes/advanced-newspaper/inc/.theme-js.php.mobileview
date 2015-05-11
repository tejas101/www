<?php
if (!is_admin()) add_action( 'wp_print_scripts', 'gabfire_js_init' );
if (!function_exists('gabfire_js_init')) {
	function gabfire_js_init() {
		wp_deregister_script( 'jquery' );  
		wp_enqueue_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
		wp_enqueue_script('jquery-migrate', GABFIRE_JS_DIR .'/jquery-migrate-1.0.0.min.js', array('jquery'), '');
		if(is_home()) {
			wp_enqueue_script('jcycle', GABFIRE_JS_DIR .'/jquery.cycle.all.min.js',array( 'jquery' ));
		}
		
		if (is_single()) { 
			wp_enqueue_script('slides.min', GABFIRE_JS_DIR .'/slides.min.jquery.js',array( 'jquery' ));	
		}
		wp_enqueue_script('jquerytools', GABFIRE_JS_DIR .'/jquery.tools.min.js',array( 'jquery' ));	
		wp_enqueue_script('flowplayer', GABFIRE_JS_DIR .'/flowplayer/flowplayer-3.2.6.min.js');
		wp_enqueue_script('superfish', GABFIRE_JS_DIR .'/superfish-1.4.8.js');
		wp_enqueue_script('fancybox', GABFIRE_JS_DIR .'/fancybox/jquery.fancybox-1.3.4.pack.js');
		wp_enqueue_script('fancyboxmw', GABFIRE_JS_DIR .'/fancybox/jquery.mousewheel-3.0.4.pack.js');		
	}
}

if (!is_admin()) add_action( 'wp_print_styles', 'gabfire_css_init' );
	if (!function_exists('gabfire_css_init')) {
		function gabfire_css_init() {
			wp_enqueue_style('fancyboxcss', GABFIRE_JS_DIR .'/fancybox/jquery.fancybox-1.3.4.css');
		}
}