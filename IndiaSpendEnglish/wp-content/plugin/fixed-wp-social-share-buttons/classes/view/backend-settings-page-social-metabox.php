<?php
$like_link = 'http://wp-buddy.com/products/plugins/fixed-wordpress-social-share-buttons/';
$name = 'Fixed WordPress Social Share Buttons';
?>
<p>
	<span class="g-plusone" data-size="medium" data-href="<?php echo $like_link; ?>"></span>
</p>

<script type="text/javascript">
	(function () {
		var po = document.createElement( 'script' );
		po.type = 'text/javascript';
		po.async = true;
		po.src = 'https://apis.google.com/js/plusone.js';
		var s = document.getElementsByTagName( 'script' )[0];
		s.parentNode.insertBefore( po, s );
	})();
</script>

<p>
	<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $like_link; ?>" data-text="Check out the <?php echo $name; ?> plugin" data-related="wp_buddy">Tweet</a>
</p>
<script>!function ( d, s, id ) {
		var js, fjs = d.getElementsByTagName( s )[0];
		if ( !d.getElementById( id ) ) {
			js = d.createElement( s );
			js.id = id;
			js.src = "//platform.twitter.com/widgets.js";
			fjs.parentNode.insertBefore( js, fjs );
		}
	}( document, "script", "twitter-wjs" );
</script>

<p>
	<iframe src="//www.facebook.com/plugins/like.php?href=<?php echo urlencode( $like_link ); ?>&amp;send=false&amp;layout=button_count&amp;width=150&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:150px; height:21px;" allowTransparency="true"></iframe>
</p>