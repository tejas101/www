<?php
/**
 * Backend View
 */
?>
<aside>
	<div id="wpbfsb" class="wpbfsb wpbfsb-position-<?php echo $this->position; ?> wpbfsb-position-responsive-<?php echo $this->position_responsive; ?>" data-icon-quantity-full-width="<?php echo $this->icon_quantity; ?>" data-icon-quantity-responsive="<?php echo $this->icon_quantity_responsive; ?>" data-responsive-width="<?php echo $this->responsive_width; ?>">
		<?php

		echo '<div class="wpbfsb-schema-org" ' . ( ( $this->is_schema_org ) ? 'itemscope itemtype="http://schema.org/CreativeWork"' : '' ) . '>';

		if ( WPBFSB_SHARE_INFO_TEXT == $this->is_share_info ) {
			echo '<button class="wpbfsb-share-info"><span class="wpbfsb-share-info-inner">' . esc_html( str_replace( '$platform', '', apply_filters( 'wpbfsb_button_title', $this->share_info_text, null ) ) ) . '</span><span class="wpbfsb-share-info-responsive-inner">' . esc_html( $this->share_info_text_responsive ) . '</span></button>';
		}

		$i = 0;

		/**
		 * @var WP_Post $button
		 */
		foreach ( $this->buttons as $button ) {
			$i ++;
			$link = get_post_meta( $button->ID, '_wpbfsb_button_link', true );
			$link = str_replace( '$site_url', urlencode( $this->site_url ), $link );
			$link = str_replace( '$site_title', urlencode( $this->site_title ), $link );
			$link = str_replace( '$featured_image', urlencode( $this->featured_image ), $link );

			if ( 'http' == substr( $link, 0, 4 ) ) {
				$link = 'javascript:window.open(\'' . $link . '\');';
			}

			$style = esc_html( str_replace( '\n\r', '', get_post_meta( $button->ID, '_wpbfsb_button_style', true ) ) );

			$classes = esc_html( str_replace( '\n\r', '', get_post_meta( $button->ID, '_wpbfsb_button_css-classes', true ) ) );

			$classes .= ' wpbfsb-button-named-' . sanitize_key( $button->post_title );

			$font_icon = get_post_meta( $button->ID, '_wpbfsb_button_font-icon', true );

			$icon = '';

			if ( ! empty( $font_icon ) ) {

				$icon = '<i class="' . esc_attr( $font_icon ) . '"></i>';
			}
			else {
				$icon_url = get_post_meta( $button->ID, '_wpbfsb_button_icon-url', true );
				if ( ! empty( $icon_url ) ) {
					$icon = '<img class="wpbfsb-icon-image" src="' . esc_url_raw( $icon_url ) . '" alt="' . sprintf( _x( 'Share it with %s!', '%s is the name of the social media plattform (eg. Facebook)', 'wpbfsb' ), esc_attr( $button->post_title ) ) . '" title="' . sprintf( _x( 'Share it with %s!', '%s is the name of the social media plattform (eg. Facebook)', 'wpbfsb' ), esc_attr( $button->post_title ) ) . '" />';
				}
			}

			$remote_function = get_post_meta( $button->ID, '_wpbfsb_button_remote', true );

			/**
			 * Button title
			 */
			$button_title = '';
			if ( WPBFSB_SHARE_INFO_TOOLTIP == $this->is_share_info ) {
				$button_title = str_replace( '$platform', $button->post_title, apply_filters( 'wpbfsb_button_title', $this->share_info_text, $button->ID ) );
				$classes .= ' wpbfsb-tooltips';
			}

			?>
			<button title="<?php esc_attr_e( $button_title ); ?>" onclick="<?php echo $link; ?>" style="<?php echo $style; ?>" class="<?php echo $classes; ?>">
				<?php
				if ( WPBFSB_SHARE_INFO_TOOLTIP == $this->is_share_info ) {
					echo '<span class="wpbfsb-tooltip">' . esc_html( $button_title ) . '</span>';
				}
				echo $icon;
				if ( $this->display_shares OR $this->display_shares_responsive ) {
					$remote_functions = WPB_Fixed_Social_Share_Model_Remote::get_remote_functions();
					if ( isset( $remote_functions[$remote_function]['function'] ) ) {

						$count = call_user_func_array( $remote_functions[$remote_function]['function'], array() );

						if ( $count > intval( get_post_meta( $button->ID, '_wpbfsb_button_threshold', true ) ) ) {
							$schema_org = '';
							if ( $this->is_schema_org ) {
								$schema_org_interaction_type = esc_attr( get_post_meta( $button->ID, '_wpbfsb_button_schema', true ) );
								if ( ! empty( $schema_org_interaction_type ) ) {
									$schema_org = 'itemprop="interactionCount" content="' . $schema_org_interaction_type . ':' . $count . '"';
								}
							}
							echo '<span ' . $schema_org . ' class="wpbfsb-count">(' . $count . ')</span>';
						}
					}
				}
				?>
			</button>
		<?php
		} //endfor

		?>
		<button id="wpbfsb-more-button" class="wpbfsb-more-button" onclick="void(wpbfsb_more_button_toggle());">
			<i class="wpbfsb-icon-chevron-down"></i>
		</button>
		<?php

		if ( $this->is_schema_org ) {
			echo '<meta itemprop="url" content="' . esc_attr( $this->site_url ) . '" />';
		}
		echo '</div>'; // end schema org div
		?>
	</div>
	<div style="clear: both;"></div>
</aside>
<script language="JavaScript">
	/*<![CDATA[*/
	function wpbfsb_more_button_toggle() {
		var el = document.getElementById( 'wpbfsb' ),
				className = 'wpbfsb-animate-in';

		if ( el.classList ) {
			el.classList.toggle( className );
		} else {
			var classes = el.className.split( ' ' );
			var existingIndex = classes.indexOf( className );

			if ( existingIndex >= 0 )
				classes.splice( existingIndex, 1 );
			else
				classes.push( className );

			el.className = classes.join( ' ' );
		}
	}
	/*]]>*/
</script>