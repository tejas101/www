jQuery( document ).ready( function () {

	/************************************************************
	 * Settings page functions
	 ************************************************************/

	jQuery( '.wpbfsb-current-range' ).each( function () {
		var value = jQuery( this ).parent().find( 'input[type="range"]' ).val();
		jQuery( this ).text( value );
	} );

	jQuery( 'input[type="range"]' ).on( 'change', function () {
		jQuery( this ).parent().find( '.wpbfsb-current-range' ).text( jQuery( this ).val() );
	} );

	jQuery( '.wpbfsb-position-select-box' ).clicktoggle( function () {
		jQuery( this ).parents( 'td' ).find( '.wpbfsb-position-select-box' ).removeClass( 'wpbfsb-position-select-box-active' );
		jQuery( this ).addClass( 'wpbfsb-position-select-box-active' );
		jQuery( this ).parents( 'td' ).find( 'input[type="text"]' ).val( jQuery( this ).data( 'position' ) ).toggle_ranges();
	}, function () {
		jQuery( this ).removeClass( 'wpbfsb-position-select-box-active' );
		jQuery( this ).parents( 'td' ).find( 'input[type="text"]' ).val( '' ).toggle_ranges();
	} );


	/**
	 * Pre Select the position select item and trigger a 'change'
	 */
	jQuery( '#wpbfsb_full-width-layout_position, #wpbfsb_responsive-width-layout_position' ).trigger( 'wpbfsb_change' ).each( function () {
		var style_class = jQuery( this ).val();
		jQuery( this ).parents( 'td' ).find( '.wpbfsb-position-select-box-' + style_class ).addClass( 'wpbfsb-position-select-box-active' );

		jQuery( this ).toggle_ranges();
	} );


	/**
	 * Toggle Shares
	 */
	jQuery( '#wpbfsb_full-width-layout_display-shares, #wpbfsb_responsive-width-layout_display-shares' ).each( function () {
		jQuery( this ).toggle_shares().on( 'click', function () {
			jQuery( this ).toggle_shares();
		} );
	} );


	/**
	 * Preview buttons
	 */
	jQuery( '.wpbfsb-icon-preview' ).click( function ( e ) {
		e.preventDefault();
	} );


	/**
	 * Change Preview
	 */
	jQuery( '#wpbfsb_full-width-layout_icon-size, #wpbfsb_responsive-width-layout_icon-size, #wpbfsb_full-width-layout_button-size, #wpbfsb_responsive-width-layout_button-size, #wpbfsb_responsive-width-layout_shares-font-size, #wpbfsb_full-width-layout_shares-font-size, #wpbfsb_full-width-layout_display-shares, #wpbfsb_responsive-width-layout_display-shares' ).on( 'change',function () {

		var exp = 'full-width';
		if ( jQuery( this ).attr( 'id' ).indexOf( 'responsive' ) != -1 ) {
			exp = 'responsive-width';
		}

		var table_obj = jQuery( this ).parent().parent().parent();
		var preview_obj = table_obj.find( '.wpbfsb-icon-preview' );

		var width = table_obj.find( '#wpbfsb_' + exp + '-layout_button-size' ).val();

		var icon_font_size = table_obj.find( '#wpbfsb_' + exp + '-layout_icon-size' ).val();
		var shares_font_size = table_obj.find( '#wpbfsb_' + exp + '-layout_shares-font-size' ).val();

		var display_shares = table_obj.find( '#wpbfsb_' + exp + '-layout_display-shares' ).is( ':checked' );
		if ( display_shares ) {
			display_shares = 'block';
		} else {
			display_shares = 'none';
		}

		preview_obj.find( 'button' ).css( 'width', width + 'px' ).css( 'height', width + 'px' );
		preview_obj.find( 'i' ).css( 'font-size', icon_font_size + 'px' );
		preview_obj.find( '.wpbfsb-count' ).css( 'font-size', shares_font_size + 'px' ).css( 'display', display_shares );
	} ).trigger( 'change' );

	/*
	 * Postbox AJAX
	 */

	/* close postboxes that should be closed */
	jQuery( '.if-js-closed' ).removeClass( 'if-js-closed' ).addClass( 'closed' );

	/* postboxes setup */
	if ( typeof postboxes !== 'undefined' ) {
		postboxes.add_postbox_toggles( jQuery( '.option_group' ).data( 'option_group' ) );
	}


	/*
	 * Show/Hide Share Info
	 */
	function show_hide_share_info() {
		var checkbox = jQuery( '#wpbfsb_other_share-info' );
		var text_fields = jQuery( '#wpbfsb_responsive-width-layout_share-info-text, #wpbfsb_full-width-layout_share-info-text, #wpbfsb_responsive-width-layout_share-text-font-size, #wpbfsb_responsive-width-layout_share-text-font-color, #wpbfsb_full-width-layout_share-text-font-size, #wpbfsb_full-width-layout_share-text-font-color' );

		if ( '0' != checkbox.val() ) {
			text_fields.each( function () {
				jQuery( this ).closest( 'tr' ).fadeIn( 500 )
			} );
		} else {
			text_fields.each( function () {
				jQuery( this ).closest( 'tr' ).fadeOut( 500 );
			} );
		}

		if ( '2' == checkbox.val() ) {
			text_fields.each( function () {
				jQuery( this ).closest( 'tr' ).find( '.description' ).fadeIn( 500 );
			} );
		} else {

			text_fields.each( function () {
				jQuery( this ).closest( 'tr' ).find( '.description' ).fadeOut( 500 );
			} );
		}
	}

	show_hide_share_info();

	jQuery( '#wpbfsb_other_share-info' ).change( function () {
		show_hide_share_info();
	} );


	/************************************************************
	 * post.php and post-new.php functions
	 ************************************************************/

	jQuery( '.wpbfsb-icon' ).click( function () {

		var icon = jQuery( this ).data( 'icon' );
		var input = jQuery( this ).parent().parent().find( 'input' );

		jQuery( '.wpbfsb-icon' ).removeClass( 'wpbfsb-icon-selected' );

		if ( jQuery( this ).is( ':last-child' ) ) {
			input.val( '' );
			color_changer( jQuery( this ) );
			return;
		}

		input.val( icon );

		jQuery( this ).addClass( 'wpbfsb-icon-selected' );

		color_changer( jQuery( this ) );
	} );


	var custom_uploader;

	jQuery( '.wpbfsb-select-media' ).click( function ( e ) {
		e.preventDefault();

		_custom_media = true;

		button = jQuery( this );

		if ( custom_uploader ) {
			custom_uploader.open();
			return true;
		}

		var title = jQuery( this ).data( 'window_title' );
		var button_name = jQuery( this ).data( 'window_button_name' );

		//Extend the wp.media object
		custom_uploader = wp.media.frames.file_frame = wp.media( {
			'title'   : title,
			'button'  : {
				'text': button_name
			},
			'multiple': false
		} );

		//When a file is selected, grab the URL and set it as the text field's value
		custom_uploader.on( 'select', function () {
			attachment = custom_uploader.state().get( 'selection' ).first().toJSON();
			button.parent().find( 'input' ).val( attachment.url );
			color_changer( button );
		} );

		custom_uploader.open();
		return false;
	} );


	jQuery( '.add_media' ).on( 'click', function () {
		_custom_media = false;
	} );


	function color_changer( obj ) {
		var table = obj.parents( 'table' );
		var bg_color = table.find( '#_wpbfsb_button_bg-color' ).val();
		var icon_color = table.find( '#_wpbfsb_button_icon-color' ).val();

		var preview = jQuery( '.wpbfsb-icon-preview' );
		preview.css( 'minHeight', preview.find( 'button' ).height() );

		preview.find( 'button' ).css( 'background-color', bg_color ).css( 'color', icon_color );

		if ( jQuery( '#_wpbfsb_button_css-classes' ).length != 0 ) {
			preview.find( 'button' ).addClass( jQuery( '#_wpbfsb_button_css-classes' ).val() );
		}

		var icon_obj = table.find( '#_wpbfsb_button_font-icon' );

		if ( icon_obj.length != 0 ) {
			var icon = icon_obj.val();

			if ( icon == '' ) {
				var icon_url = jQuery( '#_wpbfsb_button_icon-url' ).val();
				if ( icon_url != '' && preview.find( 'img' ).length == 0 ) {
					preview.find( 'i' ).attr( 'class', '' ).after( '<img src="' + icon_url + '" />' );
				}
			} else {
				preview.find( 'img' ).remove();
				preview.find( 'i' ).attr( 'class', '' ).addClass( jQuery( '.wpbfsb-icon-selected' ).data( 'icon' ) );
			}
		}
	}

	color_changer( jQuery( '#_wpbfsb_button_bg-color' ) );

	jQuery( '#_wpbfsb_button_css-classes' ).focusout( function () {
		color_changer( jQuery( this ) );
	} );


	/************************************************************
	 * General
	 ************************************************************/

	jQuery( 'a.wpbfsb-clear-color' ).click( function ( event ) {
		event.preventDefault();
		jQuery( this ).parent().find( 'input' ).val( '' );
	} );

	if ( jQuery.isFunction( jQuery.fn.wpColorPicker ) ) {
		jQuery( 'input.wpbfsb-color-picker' ).wpColorPicker( {
			'change': function () {
				color_changer( jQuery( this ) );
			}
		} );
	}
} );


/**
 * Hide/Show the Margin Ranges. Triggerd on click @ any of the .wpbfsb-position-select-box divs.
 */
jQuery.fn.toggle_ranges = function () {
	var val = jQuery( this ).val();
	var field = 'full-width';

	if ( jQuery( this ).attr( 'id' ).indexOf( 'responsive' ) != -1 ) {
		field = 'responsive-width';
	}

	var margin_top_field = jQuery( '#wpbfsb_' + field + '-layout_from-top' );
	var margin_left_field = jQuery( '#wpbfsb_' + field + '-layout_from-left' );

	if ( 'vertical-left' == val || 'vertical-right' == val ) {
		margin_left_field.parent().parent().hide();
		margin_top_field.parent().parent().show();
	}
	else if ( 'horizontal-top' == val || 'horizontal-bottom' == val ) {
		margin_left_field.parent().parent().show();
		margin_top_field.parent().parent().hide();
	} else {
		margin_left_field.parent().parent().hide();
		margin_top_field.parent().parent().hide();
	}
	return this;
};


/**
 * Toogle Shares
 */

jQuery.fn.toggle_shares = function () {
	var next = jQuery( this ).parents( 'tr' ).next();

	if ( jQuery( this ).is( ':checked' ) ) {
		next.show();
	} else {
		next.hide();
	}

	return this;
};


jQuery.fn.clicktoggle = function ( a, b ) {
	return this.each( function () {
		var clicked = false;
		jQuery( this ).click( function () {
			if ( clicked ) {
				clicked = false;
				return b.apply( this, arguments );
			}
			clicked = true;
			return a.apply( this, arguments );
		} );
	} );
};