<form class="gab_search_style1" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<fieldset>
		<input type="text" id="s" name="s" value="<?php _e('Search in site...','advanced'); ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"/>



		<input type="image" class="submit_style1" src="<?php bloginfo('template_url'); ?>/framework/images/search.png" alt="<?php _e('Search in site...','advanced'); ?>" value="" /> 
	</fieldset>
</form>