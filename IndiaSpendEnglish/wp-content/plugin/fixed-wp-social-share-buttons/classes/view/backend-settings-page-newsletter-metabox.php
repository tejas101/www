<?php
global $current_user;
get_currentuserinfo();
$name = $current_user->user_firstname;
if ( empty( $name ) ) {
	$name = $current_user->display_name;
}
?>
<div class="wpbuddy-cr-form">
	<label for="text1210658"><?php echo __( 'Your first name', 'wpbfsb' ); ?></label><br />
	<input id="text1210658" name="209681" type="text" value="<?php echo $name; ?>" /><br /><br />
	<label for="text1210692"><?php echo __( 'Your E-Mail address', 'wpbfsb' ); ?></label><br />
	<input id="text1210692" name="email" value="<?php echo $current_user->user_email; ?>" type="text" /><br /><br />
	<a href="https://10955.cleverreach.com/f/54067/wcs/" target="_blank" class="button button-primary"><?php echo __( 'Subscribe for free', 'wpbfsb' ); ?></a>
</div>
<script type="text/javascript">
	/* <![CDATA[ */
	jQuery( document ).ready( function ( $ ) {

		jQuery( '.wpbuddy-cr-form a.button' ).click( function ( e ) {
			e.preventDefault();

			var name = jQuery( '#text1210658' ).val();
			var mail = jQuery( '#text1210692' ).val();

			jQuery( [
				'<form style="display:none;" action="https://10955.cleverreach.com/f/54067/wcs/" method="post" target="_blank">',
				'<input id="text1210692" name="email" value="' + mail + '" type="text"  />',
				'<input id="text1210658" name="209681" type="text" value="' + name + '"  />',
				'</form>'
			].join( '' ) ).appendTo( 'body' )[0].submit();

		} );
	} );
	/* ]]> */
</script>