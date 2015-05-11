<?php
/**
 * Config file.
 */

global $post;

$wpbfsb_config = array(

	'settings_page_options'          => array(
		'tabs' => array(

			'layout'   => array(
				'label'    => __( 'Layout', 'wpbfsb' ),
				'sections' => array(

					'full-width-layout'       => array(
						'metabox_position' => 'normal',
						'label'            => __( 'Full Width Layout', 'wpbfsb' ),
						'fields'           => array(

							'position'              => array(
								'label'             => _x( 'Position', 'Field option', 'wpbfsb' ),
								'type'              => 'position',
								'default'           => 'vertical-right',
								'sanitize_callback' => 'sanitize_text_field',
								'classes'           => array( 'regular-text', 'disabled' ),
							),
							// icon quantity field
							'icon-quantity'         => array(
								'label'                 => _x( 'Icon Quantity', 'Field option', 'wpbfsb' ),
								'type'                  => 'range',
								'additional_parameters' => array(
									'min' => 0,
									'max' => wp_count_posts( 'wpbfsb' )->publish
								),
								'sanitize_callback'     => 'intval',
								'default'               => 5,
							),
							'icon-preview'          => array(
								'label' => _x( 'Icon Preview', 'Field option', 'wpbfsb' ),
								'type'  => 'icon_preview',
							),
							// icon size
							'icon-size'             => array(
								'label'                 => _x( 'Icon Font Size', 'Field option', 'wpbfsb' ),
								'type'                  => 'number',
								'additional_parameters' => array(
									'min' => 0,
									'max' => 100,
								),
								'default'               => 30,
								'sanitize_callback'     => 'intval',
								'after_input'           => _x( 'px', 'short form of "pixels"', 'wpbfsb' ),
							),
							'button-size'           => array(
								'label'                 => _x( 'Button Size', 'Field option', 'wpbfsb' ),
								'type'                  => 'range',
								'additional_parameters' => array(
									'min' => 0,
									'max' => 200,
								),
								'sanitize_callback'     => 'intval',
								'default'               => 60,
								'after_input'           => _x( 'px', 'short form of "pixels"', 'wpbfsb' ),
							),
							'from-left'             => array(
								'label'                 => _x( 'Margin left', 'Field option', 'wpbfsb' ),
								'type'                  => 'range',
								'additional_parameters' => array(
									'min' => 0,
									'max' => 100,
								),
								'sanitize_callback'     => 'intval',
								'default'               => 20,
								'after_input'           => _x( '%', 'Percent sign', 'wpbfsb' ),
								'description'           => __( 'Because there is no natural command to center an element horizontally you have to set a margin from left manually.', 'wpbfsb' ),
							),
							'from-top'              => array(
								'label'                 => _x( 'Margin top', 'Field option', 'wpbfsb' ),
								'type'                  => 'range',
								'additional_parameters' => array(
									'min' => 0,
									'max' => 100,
								),
								'sanitize_callback'     => 'intval',
								'default'               => 20,
								'after_input'           => _x( '%', 'Percent sign', 'wpbfsb' ),
								'description'           => __( 'Because there is no natural command to center an element vertically you have to set a margin from top manually.', 'wpbfsb' ),
							),
							'display-shares'        => array(
								'label'             => _x( 'Display shares', 'Field option', 'wpbfsb' ),
								'type'              => 'checkbox',
								'default'           => true,
								'sanitize_callback' => 'intval',
								'description'       => __( 'Please note that this can slow down your page views (if you are not using a caching plugin). This is because WordPress must ping the social media service to retrieve the current number of shares. Read more about this on the settings tab.', 'wpbfsb' )
							),
							'shares-font-size'      => array(
								'label'                 => _x( 'Shares Font Size', 'Field option', 'wpbfsb' ),
								'type'                  => 'number',
								'additional_parameters' => array(
									'min' => 0,
									'max' => 100,
								),
								'default'               => 10,
								'sanitize_callback'     => 'intval',
								'after_input'           => _x( 'px', 'short form of "pixels"', 'wpbfsb' ),
							),
							'share-text-font-size'  => array(
								'label'                 => _x( 'Share Text Font Size', 'Field option', 'wpbfsb' ),
								'type'                  => 'number',
								'additional_parameters' => array(
									'min' => 0,
									'max' => 100,
								),
								'default'               => 12,
								'sanitize_callback'     => 'intval',
								'after_input'           => _x( 'px', 'short form of "pixels"', 'wpbfsb' ),
							),
							'share-text-font-color' => array(
								'label'             => _x( 'Share Text Font Color', 'Field option', 'wpbfsb' ),
								'type'              => 'color',
								'default'           => '#ffffff',
								'sanitize_callback' => 'sanitize_text_field',
							),
							'share-info-text'       => array(
								'label'             => _x( 'Share Info Text', 'Field option', 'wpbfsb' ),
								'type'              => 'text',
								'default'           => __( 'Share this!', 'wbpfsb' ),
								'sanitize_callback' => 'sanitize_text_field',
								'description'       => __( 'You can use the variable <code>$platform</code> to include the name of the platform for the tooltip (only works if "Tooltip" was chosen above). Example: <code>Share this on $platform!</code>', 'wbpfsb' )
							),
						),
					),
					'responsive-width-layout' => array(
						'metabox_position' => 'normal',
						'label'            => __( 'Responsive Width Layout', 'wpbfsb' ),
						'fields'           => array(

							'responsive-width'      => array(
								'label'                 => _x( 'Responsive width', 'Field option', 'wpbfsb' ),
								'type'                  => 'number',
								'additional_parameters' => array(
									'min' => 0,
									'max' => 3000,
								),
								'default'               => 968,
								'sanitize_callback'     => 'intval',
								'after_input'           => _x( 'px', 'short form of "pixels"', 'wpbfsb' ),
								'description'           => __( 'The maximum width of the browser window when the responsive layout should be applied.', 'wpbfsb' ),
							),
							'position'              => array(
								'label'             => _x( 'Position', 'Field option', 'wpbfsb' ),
								'type'              => 'position',
								'default'           => 'horizontal-bottom-right',
								'sanitize_callback' => 'sanitize_text_field',
								'classes'           => array( 'regular-text', 'disabled' ),
							),
							// icon quantity field
							'icon-quantity'         => array(
								'label'                 => _x( 'Icon Quantity', 'Field option', 'wpbfsb' ),
								'type'                  => 'range',
								'additional_parameters' => array(
									'min' => 0,
									'max' => wp_count_posts( 'wpbfsb' )->publish
								),
								'sanitize_callback'     => 'intval',
								'default'               => 5,
							),
							'icon-preview'          => array(
								'label' => _x( 'Icon Preview', 'Field option', 'wpbfsb' ),
								'type'  => 'icon_preview',
							),
							// icon size
							'icon-size'             => array(
								'label'                 => _x( 'Icon Font Size', 'Field option', 'wpbfsb' ),
								'type'                  => 'number',
								'additional_parameters' => array(
									'min' => 0,
									'max' => 100,
								),
								'default'               => 15,
								'sanitize_callback'     => 'intval',
								'after_input'           => _x( 'px', 'short form of "pixels"', 'wpbfsb' ),
							),
							'button-size'           => array(
								'label'                 => _x( 'Button Size', 'Field option', 'wpbfsb' ),
								'type'                  => 'range',
								'additional_parameters' => array(
									'min' => 0,
									'max' => 200,
								),
								'sanitize_callback'     => 'intval',
								'default'               => 30,
								'after_input'           => _x( 'px', 'short form of "pixels"', 'wpbfsb' ),
							),
							'from-left'             => array(
								'label'                 => _x( 'Margin left', 'Field option', 'wpbfsb' ),
								'type'                  => 'range',
								'additional_parameters' => array(
									'min' => 0,
									'max' => 100,
								),
								'sanitize_callback'     => 'intval',
								'default'               => 20,
								'after_input'           => _x( '%', 'Percent sign', 'wpbfsb' ),
								'description'           => __( 'Because there is no natural command to center an element horizontally you have to set a margin from left manually.', 'wpbfsb' ),
							),
							'from-top'              => array(
								'label'                 => _x( 'Margin top', 'Field option', 'wpbfsb' ),
								'type'                  => 'range',
								'additional_parameters' => array(
									'min' => 0,
									'max' => 100,
								),
								'sanitize_callback'     => 'intval',
								'default'               => 20,
								'after_input'           => _x( '%', 'Percent sign', 'wpbfsb' ),
								'description'           => __( 'Because there is no natural command to center an element vertically you have to set a margin from top manually.', 'wpbfsb' ),
							),
							'display-shares'        => array(
								'label'             => _x( 'Display shares', 'Field option', 'wpbfsb' ),
								'type'              => 'checkbox',
								'sanitize_callback' => 'intval',
								'default'           => false,
								'description'       => __( 'Please note that this can slow down your page views (if you are not using a caching plugin). This is because WordPress must ping the social media service to retrieve the current number of shares. Read more about this on the settings tab.', 'wpbfsb' )

							),
							'shares-font-size'      => array(
								'label'                 => _x( 'Shares Font Size', 'Field option', 'wpbfsb' ),
								'type'                  => 'number',
								'additional_parameters' => array(
									'min' => 0,
									'max' => 100,
								),
								'default'               => 8,
								'sanitize_callback'     => 'intval',
								'after_input'           => _x( 'px', 'short form of "pixels"', 'wpbfsb' ),
							),
							'share-text-font-size'  => array(
								'label'                 => _x( 'Share Text Font Size', 'Field option', 'wpbfsb' ),
								'type'                  => 'number',
								'additional_parameters' => array(
									'min' => 0,
									'max' => 100,
								),
								'default'               => 10,
								'sanitize_callback'     => 'intval',
								'after_input'           => _x( 'px', 'short form of "pixels"', 'wpbfsb' ),
							),
							'share-text-font-color' => array(
								'label'             => _x( 'Share Text Font Color', 'Field option', 'wpbfsb' ),
								'type'              => 'color',
								'default'           => '#ffffff',
								'sanitize_callback' => 'sanitize_text_field',
							),
							'share-info-text'       => array(
								'label'             => _x( 'Share Info Text', 'Field option', 'wpbfsb' ),
								'type'              => 'text',
								'default'           => __( 'Share', 'wbpfsb' ),
								'sanitize_callback' => 'sanitize_text_field',
								'description'       => __( 'You can use the variable <code>$platform</code> to include the name of the platform for the tooltip (only works if "Tooltip" was chosen above). Example: <code>Share this on $platform!</code>', 'wbpfsb' )
							),

						),
					),
					'other'                   => array(
						'metabox_position' => 'normal',
						'label'            => __( 'Other Layout Settings', 'wpbfsb' ),
						'fields'           => array(

							'custom-css' => array(
								'label'             => _x( 'Custom CSS', 'Field option', 'wpbfsb' ),
								'type'              => 'textarea',
								'sanitize_callback' => 'wp_strip_all_tags',
							),
							'share-info' => array(
								'label'             => _x( 'Show Share Info', 'Field option', 'wpbfsb' ),
								'type'              => 'select',
								'options'           => array(
									WPBFSB_SHARE_INFO_NONE    => _x( 'Don\'t show', 'label if nothing was selected in the share info field', 'wpbfsb' ),
									WPBFSB_SHARE_INFO_TEXT    => __( 'Text', 'wpbfsb' ),
									WPBFSB_SHARE_INFO_TOOLTIP => __( 'Tooltip', 'wpbfsb' ),
								),
								'default'           => 0,
								'sanitize_callback' => 'intval',
								'description'       => __( 'For example, some themes are using the same Facebook, Twitter and Google+ icons to link to profile pages. To avoid confusing the user you can define your own share info here.', 'wbpfsb' )
							),
						),
					),
					'about'                   => array(
						'metabox_position' => 'side',
						'metabox_view'     => 'backend-settings-page-about-metabox',
						'label'            => __( 'About', 'wpbfsb' ),
						'fields'           => array(),
					),
					'social'                  => array(
						'metabox_position' => 'side',
						'metabox_view'     => 'backend-settings-page-social-metabox',
						'label'            => __( 'Share it now!', 'wpbfsb' ),
						'fields'           => array(),
					),
					'help'                    => array(
						'metabox_position' => 'side',
						'metabox_view'     => 'backend-settings-page-help-metabox',
						'label'            => __( 'Need help?', 'wpbfsb' ),
						'fields'           => array(),
					),
					'newsletter'              => array(
						'metabox_position' => 'side',
						'metabox_view'     => 'backend-settings-page-newsletter-metabox',
						'label'            => __( 'Newsletter', 'wpbfsb' ),
						'fields'           => array(),
					),
				),
			),
			'settings' => array(
				'label'    => __( 'Settings', 'wpbfsb' ),
				'sections' => array(
					'schema'     => array(
						'metabox_position' => 'normal',
						'label'            => __( 'Schema.org', 'wpbfsb' ),
						'fields'           => array(

							'active' => array(
								'label' => _x( 'Enable schema.org', 'Field option', 'wpbfsb' ),
								'type'  => 'checkbox',
							),
						),
					),
					'visibility' => array(
						'metabox_position' => 'normal',
						'label'            => __( 'Visibility', 'wpbfsb' ),
						'fields'           => array(

							'homepage'  => array(
								'label'   => _x( 'Show on home page', 'Field option', 'wpbfsb' ),
								'type'    => 'checkbox',
								'default' => true,
							),
							'frontpage' => array(
								'label'   => _x( 'Show on front page', 'Field option', 'wpbfsb' ),
								'type'    => 'checkbox',
								'default' => true,
							),
							'category'  => array(
								'label'   => _x( 'Show on category pages', 'Field option', 'wpbfsb' ),
								'type'    => 'checkbox',
								'default' => true,
							),
							'tag'       => array(
								'label'   => _x( 'Show on tag pages', 'Field option', 'wpbfsb' ),
								'type'    => 'checkbox',
								'default' => true,
							),
							'taxonomy'  => array(
								'label'   => _x( 'Show on taxonomy pages', 'Field option', 'wpbfsb' ),
								'type'    => 'checkbox',
								'default' => true,
							),
							'author'    => array(
								'label'   => _x( 'Show on author pages', 'Field option', 'wpbfsb' ),
								'type'    => 'checkbox',
								'default' => true,
							),
							'date'      => array(
								'label'   => _x( 'Show on date based archive pages', 'Field option', 'wpbfsb' ),
								'type'    => 'checkbox',
								'default' => true,
							),
							'search'    => array(
								'label'   => _x( 'Show on search pages', 'Field option', 'wpbfsb' ),
								'type'    => 'checkbox',
								'default' => true,
							),
							'404'       => array(
								'label'   => _x( 'Show on 404 pages', 'Field option', 'wpbfsb' ),
								'type'    => 'checkbox',
								'default' => false,
							),
						),
					),
					'ping'       => array(
						'metabox_position' => 'normal',
						'label'            => __( 'Ping Service', 'wpbfsb' ),
						'fields'           => array(

							// set how often the numbers of shares are retrieved
							'refresh-rate' => array(
								'label'                 => _x( 'Refresh rate / Caching', 'Field option', 'wpbfsb' ),
								'type'                  => 'number',
								'additional_parameters' => array(
									'min' => 1,
								),
								'default'               => 60,
								'sanitize_callback'     => 'intval',
								'after_input'           => _x( 'min.', 'short form of "minutes"', 'wpbfsb' ),
								'description'           => __( 'Set how often WordPress should ping the social media service to ask for a new number of shares. Please note that, if a caching plugin is active and if it is very aggressive, the number of shares will not be updated until the cache has been flushed manually or automatically. If you want to deactivate this functionality just don\'t display the counter on your layout.', 'wpbfsb' ),
							),
							'flush-cache'  => array(
								'label'       => '',
								'type'        => 'info',
								'description' => '<a href="' . esc_url( admin_url( 'options-general.php?page=wpbfsb&tab=settings&wpbfsb_action=flushcache&nonce=' . wp_create_nonce( 'wpbfsb_flushcache' ) ) ) . '" class="button wpbfsb-flushcache"><span class="dashicons dashicons-trash"></span>' . esc_html( __( 'Flush Cache now', 'wpbfsb' ) ) . '</a>',
							),
							'timeout'      => array(
								'label'                 => _x( 'Timeout', 'Field option', 'wpbfsb' ),
								'type'                  => 'number',
								'additional_parameters' => array(
									'min' => 1,
									'max' => 30,
								),
								'default'               => 3,
								'sanitize_callback'     => 'intval',
								'after_input'           => _x( 'sec.', 'short form of "seconds"', 'wpbfsb' ),
								'description'           => __( 'When a social media service is unavailable WordPress will timeout after the above time. If your WordPress installation is not able to retrieve any data, increasing the timeout could help. But note that increasing the timeout can result in a slower page load. However, the results will be cached for some time (see the "Refresh rate" settings field).', 'wpbfsb' ),
							),
						),
					),
					'other'      => array(
						'metabox_position' => 'normal',
						'label'            => __( 'Updates', 'wpbfsb' ),
						'fields'           => array(

							'purchase-code' => array(
								'label'             => _x( 'Purchase code', 'Field option', 'wpbfsb' ),
								'type'              => 'text',
								'sanitize_callback' => 'sanitize_text_field',
								'description'       => __( 'Please enter your purchase code here to get automatic updates.', 'wpbfsb' ) . ' <a href="http://wp-buddy.com/blog/where-to-find-your-purchase-code/" target="_blank">' . esc_html( __( 'If you don\' know where you can find your purchase code, please click here.', 'wpbfsb' ) ) . '</a>',
							),

						),
					),
					'about'      => array(
						'metabox_position' => 'side',
						'metabox_view'     => 'backend-settings-page-about-metabox',
						'label'            => __( 'About', 'wpbfsb' ),
						'fields'           => array(),
					),
					'social'     => array(
						'metabox_position' => 'side',
						'metabox_view'     => 'backend-settings-page-social-metabox',
						'label'            => __( 'Share it now!', 'wpbfsb' ),
						'fields'           => array(),
					),
					'help'       => array(
						'metabox_position' => 'side',
						'metabox_view'     => 'backend-settings-page-help-metabox',
						'label'            => __( 'Need help?', 'wpbfsb' ),
						'fields'           => array(),
					),
					'newsletter' => array(
						'metabox_position' => 'side',
						'metabox_view'     => 'backend-settings-page-newsletter-metabox',
						'label'            => __( 'Newsletter', 'wpbfsb' ),
						'fields'           => array(),
					),
				),
			),
		)
	),
	'metabox_settings_options'       => array(
		'tabs' => array(

			'general' => array(
				'label'    => '', // General
				'sections' => array(

					'button' => array(
						'label'  => '', // Button settings
						'fields' => array(

							'icon-preview'   => array(
								'label' => _x( 'Icon Preview', 'Field option', 'wpbfsb' ),
								'type'  => 'icon_preview',
							),
							'bg-color'       => array(
								'label'             => _x( 'Background Color', 'Field option', 'wpbfsb' ),
								'type'              => 'color',
								'sanitize_callback' => 'sanitize_text_field',
							),
							'bg-color-hover' => array(
								'label'             => _x( 'Background Hover-Color', 'Field option', 'wpbfsb' ),
								'type'              => 'color',
								'sanitize_callback' => 'sanitize_text_field',
							),
							'icon-color'     => array(
								'label'             => _x( 'Icon Color', 'Field option', 'wpbfsb' ),
								'type'              => 'color',
								'sanitize_callback' => 'sanitize_text_field',
								'description'       => __( 'Please note that this setting has no affect when you are using an picture.', 'wpbfsb' )
							),
							'font-icon'      => array(
								'label'             => _x( 'Icon', 'Field option', 'wpbfsb' ),
								'type'              => 'icons',
								'sanitize_callback' => 'sanitize_text_field',
							),
							'icon-url'       => array(
								'label'             => _x( 'Icon URL (optional)', 'Field option', 'wpbfsb' ),
								'type'              => 'media',
								'sanitize_callback' => 'esc_url_raw',
								'placeholder'       => 'http://',
								'description'       => __( 'If you want to set an image URL instead of an icon font element please deselect the font first (red cross above).', 'wpbfsb' )
							),
							'link'           => array(
								'label'             => _x( 'OnClick Event', 'Field option', 'wpbfsb' ),
								'type'              => 'text',
								'sanitize_callback' => 'sanitize_text_field',
								'placeholder'       => 'http://',
								'description'       => __( 'Use <strong>$site_url</strong> as a placeholder for the (URL-encoded) URL, <strong>$site_title</strong> for the page title.', 'wpbfsb' ),
							),
							'remote'         => array(
								'label'             => _x( 'Remote Function', 'Field option', 'wpbfsb' ),
								'type'              => 'select',
								'options'           => array_merge( array( 'none' => _x( 'None', 'Expression when no remote function was set', 'wpbfsb' ) ), wp_list_pluck( WPB_Fixed_Social_Share_Model_Remote::get_remote_functions(), 'label' ) ),
								'sanitize_callback' => 'sanitize_text_field',
								'description'       => __( 'The function that is used to retrieve the number of likes, pins, etc.', 'wpbfsb' ) . ' <a href="http://wp-buddy.com/documentation/plugins/fixed-wordpress-social-share-buttons/add-function-retrieve-data-social-media-service/" target="_blank">' . esc_html( __( 'Click here to learn more about how to add more functions.', 'wpbfsb' ) . '</a>' ),
							),
							'css-classes'    => array(
								'label'             => _x( 'CSS Classes', 'Field option', 'wpbfsb' ),
								'type'              => 'text',
								'sanitize_callback' => 'sanitize_text_field',
								'placeholder'       => __( 'Separated by a space', 'wpbfsb' ),
							),
							'style'          => array(
								'label'             => _x( 'CSS Styles', 'Field option', 'wpbfsb' ),
								'type'              => 'textarea',
								'sanitize_callback' => 'wp_strip_all_tags',
								'description'       => sprintf( __( 'Use the syntax %s to address this button.', 'wpbfsb' ), ( ( isset( $post ) ) ? '<strong>.wpbfsb .wpbfsb-button-named-' . sanitize_key( $post->post_title ) . ' {}</strong>' : '?' ) ),
							),
							'schema'         => array(
								'label'             => _x( 'Schema.org Interaction Type', 'Field option', 'wpbfsb' ),
								'type'              => 'select',
								'options'           => array(
									''               => _x( 'None', 'Expression when no schema.org interaction type was selected', 'wpbfsb' ),
									'UserLikes'      => 'UserLikes',
									'UserTweets'     => 'UserTweets',
									'UserPlusOnes'   => 'UserPlusOnes',
									'UserBlocks'     => 'UserBlocks',
									'UserCheckins'   => 'UserCheckins',
									'UserComments'   => 'UserComments',
									'UserDownloads'  => 'UserDownloads',
									'UserPageVisits' => 'UserPageVisits',
									'UserPlays'      => 'UserPlays',
								),
								'sanitize_callback' => 'sanitize_text_field',
							),
							'threshold'      => array(
								'label'                 => _x( 'Threshold', 'Field option', 'wpbfsb' ),
								'type'                  => 'number',
								'additional_parameters' => array(
									'min' => 0,
								),
								'default'               => 0,
								'sanitize_callback'     => 'intval',
								'after_input'           => _x( 'shares', 'Field option', 'wpbfsb' ),
								'description'           => __( 'If you want to show the number of shares only when a certain threshold (number of likes, shares, etc.) has been reached, then please define a number here. For example: if you want to show the number of Facebook Likes only when at least 10 user has shared it 10 times then you have to set the threshold to 9.', 'wpbfsb' ),
							),
						),
					),
				),
			)
		)
	),
	'posts_metabox_settings_options' => array(
		'tabs' => array(

			'general' => array(
				'label'    => '', // General
				'sections' => array(

					'button' => array(
						'label'  => '', // Button settings
						'fields' => array(

							'hide-icons' => array(
								'label'             => _x( 'Hide icons on this page', 'Field option', 'wpbfsb' ),
								'type'              => 'checkbox',
								'sanitize_callback' => 'intval',
							),

						),
					),
				),
			)
		)
	),
	'buttons'                        => array(
		'facebook'   => array(
			'title'      => __( 'Facebook', 'wpbfsb' ),
			'menu_order' => 100,
			'post_meta'  => array(
				'_wpbfsb_button_font-icon'   => 'wpbfsb-icon-facebook',
				'_wpbfsb_button_link'        => 'javascript:void(window.open(\'http://www.facebook.com/sharer/sharer.php?u=$site_url\', \'Facebook\', \'width=650,height=200,scrollbars=yes\'));',
				'_wpbfsb_button_remote'      => 'facebook',
				'_wpbfsb_button_css-classes' => '',
				'_wpbfsb_button_schema'      => 'UserLikes',
				'_wpbfsb_title'              => 'facebook',
				'_wpbfsb_button_bg-color'    => '#3b5997',
				'_wpbfsb_button_icon-color'  => '#ffffff',
			),
		),
		'twitter'    => array(
			'title'      => __( 'Twitter', 'wpbfsb' ),
			'menu_order' => 120,
			'post_meta'  => array(
				'_wpbfsb_button_font-icon'   => 'wpbfsb-icon-twitter',
				'_wpbfsb_button_link'        => 'javascript:void(window.open(\'http://twitter.com/home?status=$site_title $site_url\', \'Twitter\', \'width=650,height=400,scrollbars=yes\'));',
				'_wpbfsb_button_remote'      => 'twitter',
				'_wpbfsb_button_css-classes' => '',
				'_wpbfsb_button_schema'      => 'UserTweets',
				'_wpbfsb_title'              => 'twitter',
				'_wpbfsb_button_bg-color'    => '#327ead',
				'_wpbfsb_button_icon-color'  => '#ffffff',
			),
		),
		'pinterest'  => array(
			'title'      => __( 'Pinterest', 'wpbfsb' ),
			'menu_order' => 140,
			'post_meta'  => array(
				'_wpbfsb_button_font-icon'   => 'wpbfsb-icon-pinterest',
				'_wpbfsb_button_link'        => 'javascript:void(window.open(\'http://pinterest.com/pin/create/button/?url=$site_url&media=$featured_image&description=$site_title\', \'Pinterest\', \'width=650,height=500,scrollbars=yes\'));',
				'_wpbfsb_button_remote'      => 'pinterest',
				'_wpbfsb_button_css-classes' => '',
				'_wpbfsb_title'              => 'pinterest',
				'_wpbfsb_button_bg-color'    => '#cb2027',
				'_wpbfsb_button_icon-color'  => '#ffffff',
			),
		),
		'googleplus' => array(
			'title'      => __( 'Google+', 'wpbfsb' ),
			'menu_order' => 160,
			'post_meta'  => array(
				'_wpbfsb_button_font-icon'   => 'wpbfsb-icon-googleplus',
				'_wpbfsb_button_link'        => 'javascript:void(window.open(\'https://plusone.google.com/_/+1/confirm?url=$site_url\', \'Plus One\', \'width=650,height=200,scrollbars=yes\'));',
				'_wpbfsb_button_remote'      => 'googleplus',
				'_wpbfsb_button_css-classes' => '',
				'_wpbfsb_button_schema'      => 'UserPlusOnes',
				'_wpbfsb_title'              => 'googleplus',
				'_wpbfsb_button_bg-color'    => '#d64937',
				'_wpbfsb_button_icon-color'  => '#ffffff',
			),
		),
		'linkedin'   => array(
			'title'      => __( 'LinkedIn', 'wpbfsb' ),
			'menu_order' => 180,
			'post_meta'  => array(
				'_wpbfsb_button_font-icon'   => 'wpbfsb-icon-linkedin',
				'_wpbfsb_button_link'        => 'javascript:void(window.open(\'https://www.linkedin.com/shareArticle?mini=true&url=$site_url&title=$site_title&summary=&source=$site_url\', \'LinkedIn\', \'width=650,height=200,scrollbars=yes\'));',
				'_wpbfsb_button_remote'      => 'linkedin',
				'_wpbfsb_button_css-classes' => '',
				'_wpbfsb_button_schema'      => '',
				'_wpbfsb_title'              => 'linkedin',
				'_wpbfsb_button_bg-color'    => '#0073b2',
				'_wpbfsb_button_icon-color'  => '#ffffff',
			),
		),
		'xing'       => array(
			'title'      => __( 'XING', 'wpbfsb' ),
			'menu_order' => 200,
			'post_meta'  => array(
				'_wpbfsb_button_font-icon'   => 'wpbfsb-icon-xing',
				'_wpbfsb_button_link'        => 'javascript:void(window.open(\'https://www.xing-share.com/app/user?op=share;sc_p=xing-share;url=$site_url\', \'XING\', \'width=650,height=200,scrollbars=yes\'));',
				'_wpbfsb_button_remote'      => '',
				'_wpbfsb_button_css-classes' => '',
				'_wpbfsb_button_schema'      => '',
				'_wpbfsb_title'              => 'xing',
				'_wpbfsb_button_bg-color'    => '#175e60',
				'_wpbfsb_button_icon-color'  => '#ffffff',
			),
		),
		'tumblr'     => array(
			'title'      => __( 'tumblr', 'wpbfsb' ),
			'menu_order' => 220,
			'post_meta'  => array(
				'_wpbfsb_button_font-icon'   => 'wpbfsb-icon-tumblr',
				'_wpbfsb_button_link'        => 'javascript:void(window.open(\'http://www.tumblr.com/share\', \'tumblr\', \'width=650,height=200,scrollbars=yes\'));',
				'_wpbfsb_button_remote'      => '',
				'_wpbfsb_button_css-classes' => '',
				'_wpbfsb_button_schema'      => '',
				'_wpbfsb_title'              => 'tumblr',
				'_wpbfsb_button_bg-color'    => '#35465c',
				'_wpbfsb_button_icon-color'  => '#ffffff',
			),
		),
		'renren'     => array(
			'title'      => __( 'renren', 'wpbfsb' ),
			'menu_order' => 240,
			'post_meta'  => array(
				'_wpbfsb_button_font-icon'   => 'wpbfsb-icon-renren',
				'_wpbfsb_button_link'        => 'javascript:void(window.open(\'http://share.renren.com/share/buttonshare.do?link=$site_url&title=$site_title\', \'renren\', \'width=650,height=200,scrollbars=yes\'));',
				'_wpbfsb_button_remote'      => '',
				'_wpbfsb_button_css-classes' => '',
				'_wpbfsb_button_schema'      => '',
				'_wpbfsb_title'              => 'renren',
				'_wpbfsb_button_bg-color'    => '#005eac',
				'_wpbfsb_button_icon-color'  => '#ffffff',
			),
		),
		'weibo'      => array(
			'title'      => __( 'Weibo', 'wpbfsb' ),
			'menu_order' => 260,
			'post_meta'  => array(
				'_wpbfsb_button_font-icon'   => 'wpbfsb-icon-weibo',
				'_wpbfsb_button_link'        => 'javascript:void(window.open(\'http://service.weibo.com/share/share.php?url=$site_url&appkey=&title=$site_title&pic=&ralateUid=&language=\', \'renren\', \'width=650,height=200,scrollbars=yes\'));',
				'_wpbfsb_button_remote'      => '',
				'_wpbfsb_button_css-classes' => '',
				'_wpbfsb_button_schema'      => '',
				'_wpbfsb_title'              => 'Weibo',
				'_wpbfsb_button_bg-color'    => '#f65555',
				'_wpbfsb_button_icon-color'  => '#ffffff',
			),
		),
		'gittip'     => array(
			'title'      => __( 'Gittip', 'wpbfsb' ),
			'menu_order' => 280,
			'post_meta'  => array(
				'_wpbfsb_button_font-icon'   => 'wpbfsb-icon-gittip',
				'_wpbfsb_button_link'        => 'https://www.gittip.com/YOUR_USER_NAME',
				'_wpbfsb_button_remote'      => '',
				'_wpbfsb_button_css-classes' => '',
				'_wpbfsb_button_schema'      => '',
				'_wpbfsb_title'              => 'Gittip',
				'_wpbfsb_button_bg-color'    => '#653d07',
				'_wpbfsb_button_icon-color'  => '#ffffff',
			),
		),
	),

);

# wp_list_pluck( wp_list_pluck( get_post_types( apply_filters( 'wpbfsb_visibility_post_types_args', array( 'public' => true ) ), 'objects' ), 'labels' ), 'name' )
$wpbfsb_vis_post_types = get_post_types( apply_filters( 'wpbfsb_visibility_post_types_args', array( 'public' => true ) ), 'objects' );

foreach ( $wpbfsb_vis_post_types as $wpbfsb_vis_post_type => $wpbfsb_vis_post_type_obj ) {
	$wpbfsb_config['settings_page_options']['tabs']['settings']['sections']['visibility']['fields'][ $wpbfsb_vis_post_type ] = array(
		'label'   => $wpbfsb_vis_post_type_obj->labels->name,
		'type'    => 'checkbox',
		'default' => true,
	);
}

unset( $wpbfsb_vis_post_types, $wpbfsb_vis_post_type, $wpbfsb_vis_post_type_obj );

$wpbfsb_config = apply_filters( 'wpbfsb_config', $wpbfsb_config );

