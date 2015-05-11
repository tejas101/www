<?php
set_post_thumbnail_size( 50, 50, true); // Normal Post Thumbnails

if (of_get_option('of_wpmumode')==0) {
	add_image_size( 'gabfire', 1024, 9999 ); /* Featured Big Image - defined to be used with timthumb */
} else {
	/* Theme thumbnail sizes for WordPress multi user
	 * network installations. The image sizes below will  
	 * be used only when WPMU mode is activated on 
	 * them
	 */
	add_image_size( 'an-featured', 478, 270, true ); // Featured Slider
	add_image_size( 'an-feathumb', 73, 50, true ); // Featured Thumb
	add_image_size( 'an-belowfea', 110, 77, true ); // Below featured
	add_image_size( 'an-generic', 80, 63, true ); /* Right hand of featured - secndary content right column - default archive - magazine archive mid column */
	add_image_size( 'an-scndrmid', 110, 66, true ); // Secondary content mid column
	add_image_size( 'an-midtabs', 216, 120, true ); // tabbed content
	add_image_size( 'an-mag2', 120, 90, true ); // magazine layout - bottom of big thumb
	add_image_size( 'an-mag1', 500, 9999 ); // magazine layout - big thumb
	add_image_size( 'an-vs', 280, 220 ); // video slider
	add_image_size( 'an-arc2col', 60, 40,true ); // 2 column archive thumbs
	add_image_size( 'an-media', 208, 180, true ); // Media template thumbnails
	add_image_size( 'an-media-overlay', 638, 9999 ); // Overlay image size for media archive
	add_image_size( 'an-sidebarmedia', 118, 9999 ); // Overlay image size for media archive
	add_image_size( 'an-innerslide', 479, 9999 ); // Archive Pages
	add_image_size( 'an-subnews', 153, 100, true ); // Subnews
	add_image_size( 'ajaxtabs', 30, 30, true ); // Ajaxtabs Widget
}