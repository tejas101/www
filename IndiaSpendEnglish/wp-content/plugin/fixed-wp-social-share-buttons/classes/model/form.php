<?php
/**
 * Form Model Class.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Form Model.
 *
 * @since  1.0.0
 * @access public
 *
 */
class WPB_Fixed_Social_Share_Model_Form {

	/**
	 * Text field
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args
	 */
	public static function text( $args ) {
		$args = wp_parse_args( $args,
				array(
						'label'                 => '',
						'type'                  => 'text',
						'name'                  => '',
						'disabled'              => false,
						'classes'               => array( 'regular-text' ),
						'value'                 => '',
						'additional_parameters' => array(),
						'default'               => '',
						'after_input'           => '',
						'description'           => '',
						'label_for'             => '',
						'placeholder'           => '',
						'where'                 => '',
						'post_id'               => null
				)
		);

		$additional_parameters = (array) $args['additional_parameters'];

		array_walk( $additional_parameters, function ( &$value, $key ) {
			$value = $key . '="' . $value . '"';
		} );

		$additional_parameters = implode( ' ', $additional_parameters );

		if ( 'in_metabox' == $args['where'] ) {
			$value = get_post_meta( $args['post_id'], $args['label_for'], true );
		}
		else {
			$value = get_option( $args['label_for'], $args['default'] );
		}

		$checkbox_checked = '';
		if ( 'checkbox' == $args['type'] ) {
			$checkbox_checked = checked( (bool) $value, true, false );
			$value            = 1;
		}

		do_action_ref_array( 'wpbfsb_before_' . $args['type'] . '_input', array( &$args, &$value ) );

		echo '<input placeholder="' . $args['placeholder'] . '" ' . $checkbox_checked . ' id="' . $args['label_for'] . '" ' . $additional_parameters . ' class="' . implode( ' ', (array) $args['classes'] ) . '" type="' . $args['type'] . '" value="' . esc_attr( $value ) . '" name="' . $args['label_for'] . '" />';

		echo $args['after_input'];

		if ( $args['description'] ) {
			echo '<p class="description">' . $args['description'] . '</p>';
		}

		do_action_ref_array( 'wpbfsb_after_' . $args['type'] . '_input', array( &$args, &$value ) );

	}


	/**
	 * Number field
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args
	 */
	public static function number( $args ) {
		$args = wp_parse_args( $args,
				array(
						'type'                  => 'number',
						'classes'               => array( 'small-text' ),
						'additional_parameters' => array(
								'min' => '',
								'max' => '',
						),
				)
		);

		self::text( $args );
	}


	/**
	 * Range field
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args
	 */
	public static function range( $args ) {

		if ( isset( $args['after_input'] ) ) {
			$args['after_input'] = '<span class="wpbfsb-current-range"></span>' . $args['after_input'];
		}

		$args = wp_parse_args( $args,
				array(
						'additional_parameters' => array(
								'min' => '',
								'max' => '',
						),
						'type'                  => 'range',
						'after_input'           => '<span class="wpbfsb-current-range"></span>'
				)
		);

		self::text( $args );
	}


	/**
	 * Select field
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args
	 */
	public static function select( $args ) {
		$args = wp_parse_args( $args,
				array(
						'label'                 => '',
						'type'                  => 'select',
						'name'                  => '',
						'disabled'              => false,
						'classes'               => array(),
						'value'                 => '',
						'additional_parameters' => array(),
						'options'               => array(),
						'after_input'           => '',
						'description'           => '',
						'label_for'             => '',
						'default'               => '',
						'where'                 => '',
						'post_id'               => null
				)
		);

		$additional_parameters = (array) $args['additional_parameters'];

		array_walk( $additional_parameters, function ( &$value, $key ) {
			$value = $key . '="' . $value . '"';
		} );

		$additional_parameters = implode( ' ', $additional_parameters );

		if ( 'in_metabox' == $args['where'] ) {
			$selected_option = get_post_meta( $args['post_id'], $args['label_for'], true );
		}
		else {
			$selected_option = get_option( $args['label_for'], $args['default'] );
		}

		do_action_ref_array( 'wpbfsb_before_select_input', array( &$args, &$selected_option ) );

		echo '<select id="' . $args['label_for'] . '" name="' . $args['label_for'] . '" ' . $additional_parameters . ' class="' . implode( ' ', (array) $args['classes'] ) . '">';

		foreach ( (array) $args['options'] as $option_value => $option_label ) {
			echo '<option ' . selected( $selected_option, $option_value, false ) . ' value="' . $option_value . '">' . $option_label . '</option>';
		}

		echo '</select>';

		echo $args['after_input'];

		if ( $args['description'] ) {
			echo '<p class="description">' . $args['description'] . '</p>';
		}

		do_action_ref_array( 'wpbfsb_after_select_input', array( &$args, &$selected_option ) );
	}


	/**
	 * Checkbox field
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args
	 */
	public static function checkbox( $args ) {
		$args = wp_parse_args( $args,
				array(
						'type'    => 'checkbox',
						'classes' => array(),
				)
		);

		self::text( $args );
	}

	/**
	 * Color field
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args
	 */
	public static function color( $args ) {
		$args['type'] = 'text';
		$args         = wp_parse_args( $args,
				array(
						'type'    => 'color',
						'classes' => array( 'wpbfsb-color-picker' )
				)
		);

		self::text( $args );
	}


	/**
	 * Renders a textrea field
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args
	 *
	 * @return void
	 *
	 */
	public static function textarea( $args ) {
		$args = wp_parse_args( $args,
				array(
						'label'                 => '',
						'type'                  => 'textarea',
						'name'                  => '',
						'disabled'              => false,
						'classes'               => array( 'large-text', 'code' ),
						'value'                 => '',
						'additional_parameters' => array(),
						'default'               => '',
						'after_input'           => '',
						'description'           => '',
						'label_for'             => '',
						'placeholder'           => '',
						'where'                 => '',
						'post_id'               => null
				)
		);

		$additional_parameters = (array) $args['additional_parameters'];

		array_walk( $additional_parameters, function ( &$value, $key ) {
			$value = $key . '="' . $value . '"';
		} );

		$additional_parameters = implode( ' ', $additional_parameters );

		if ( 'in_metabox' == $args['where'] ) {
			$value = get_post_meta( $args['post_id'], $args['label_for'], true );
		}
		else {
			$value = get_option( $args['label_for'], $args['default'] );
		}

		do_action_ref_array( 'wpbfsb_before_textarea_input', array( &$args, &$value ) );

		echo '<textarea cols="200" rows="10" placeholder="' . $args['placeholder'] . '" id="' . $args['label_for'] . '" ' . $additional_parameters . ' class="' . implode( ' ', (array) $args['classes'] ) . '" name="' . $args['label_for'] . '">' . esc_textarea( $value ) . '</textarea>';

		echo $args['after_input'];

		if ( $args['description'] ) {
			echo '<p class="description">' . $args['description'] . '</p>';
		}

		do_action_ref_array( 'wpbfsb_after_textarea_input', array( &$args, &$value ) );

	}


	/**
	 * Icon selection
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args
	 *
	 * @return void
	 */
	public static function icons( $args ) {
		$args['type'] = 'text';

		add_action( 'wpbfsb_before_text_input', array( 'WPB_Fixed_Social_Share_Model_Form', 'render_icon_list' ), 10, 2 );

		self::text( $args );

		remove_action( 'wpbfsb_before_text_input', array( 'WPB_Fixed_Social_Share_Model_Form', 'render_icon_list' ), 10, 2 );

	}


	/**
	 * Renders the icon list
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args Field args
	 * @param mixed $value
	 *
	 * @return void
	 */
	public static function render_icon_list( $args = array(), $value = '' ) {

		$original_icons = apply_filters( 'wpbfsb_original_icons', array(
				'facebook',
				'twitter',
				'pinterest',
				'googleplus',
				'linkedin',
				'xing',
				'tumblr',
				'renren',
				'weibo',
				'gittip'
		) );

		$icons = apply_filters( 'wpbfsb_icons', $original_icons );

		$flip_original_icons = array_flip( $original_icons );

		// add close icon
		$icons[] = 'times';

		echo '<div class="wpbfsb-icons">';
		foreach ( $icons as $icon ) {
			$classes = array( 'wpbfsb-icon', 'wpbfsb-icon-' . $icon, 'wpbfsb-' . $icon );

			$data_icon = 'wpbfsb-icon-' . $icon;

			// Check if a new icon was added. If so: add it to the list of classes
			if ( ! isset( $flip_original_icons[$icon] ) ) {
				$classes[] = $icon;
				$data_icon = $icon;
			}

			if ( $value == $data_icon ) {
				$classes[] = 'wpbfsb-icon-selected';
			}


			echo '<span data-icon="' . $data_icon . '" class="' . implode( ' ', $classes ) . '"></span>';
		}
		echo '</div>';
	}


	/**
	 * Renders a media field
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param  array $args
	 */
	public static function media( $args ) {
		$args['type']        = 'text';
		$args['after_input'] = '<a href="#" data-window_title="' . __( 'Select an icon', 'wpbfsb' ) . '" data-window_button_name="' . __( 'Select as icon', 'wpbfsb' ) . '" class="button button-primary wpbfsb-select-media" >' . __( 'Select', 'wpbfsb' ) . '</a>';
		self::text( $args );
	}


	/**
	 * Renders a media field
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param  array $args
	 */
	public static function position( $args ) {
		$args['type'] = 'text';

		add_action( 'wpbfsb_before_text_input', array( 'WPB_Fixed_Social_Share_Model_Form', 'render_position_select' ), 10, 2 );

		self::text( $args );

		remove_action( 'wpbfsb_before_text_input', array( 'WPB_Fixed_Social_Share_Model_Form', 'render_position_select' ), 10, 2 );
	}


	/**
	 * Renders the icon list
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args Field args
	 * @param mixed $value
	 *
	 * @return void
	 */
	public static function render_position_select( $args = array(), $value = '' ) {
		?>
		<div class="wpbfsb-position-select">
			<div class="wpbfsb-position-select-address"><span><?php bloginfo( 'url' ); ?></span></div>
			<div class="wpbfsb-position-select-content">
				<div class="wpbfsb-position-select-content-line wpbfsb-position-select-content-line-0"></div>
				<div class="wpbfsb-position-select-content-line wpbfsb-position-select-content-line-1"></div>
				<div class="wpbfsb-position-select-content-line wpbfsb-position-select-content-line-2"></div>
				<div class="wpbfsb-position-select-content-line wpbfsb-position-select-content-line-3"></div>
				<div class="wpbfsb-position-select-content-line wpbfsb-position-select-content-line-4"></div>
				<div class="wpbfsb-position-select-content-line wpbfsb-position-select-content-line-5"></div>
				<div class="wpbfsb-position-select-content-line wpbfsb-position-select-content-line-6"></div>
				<div class=" wpbfsb-position-select-content-line wpbfsb-position-select-content-line-7 wpbfsb-position-select-box wpbfsb-position-select-box-after-content" data-position="after-content"></div>
			</div>
			<div class="wpbfsb-position-select-box wpbfsb-position-select-box-horizontal wpbfsb-position-select-box-horizontal-top-left" data-position="horizontal-top-left"></div>
			<div class="wpbfsb-position-select-box wpbfsb-position-select-box-horizontal wpbfsb-position-select-box-horizontal-top-right" data-position="horizontal-top-right"></div>
			<div class="wpbfsb-position-select-box wpbfsb-position-select-box-horizontal wpbfsb-position-select-box-horizontal-top" data-position="horizontal-top"></div>
			<div class="wpbfsb-position-select-box wpbfsb-position-select-box-horizontal wpbfsb-position-select-box-horizontal-bottom-left" data-position="horizontal-bottom-left"></div>
			<div class="wpbfsb-position-select-box wpbfsb-position-select-box-horizontal wpbfsb-position-select-box-horizontal-bottom-right" data-position="horizontal-bottom-right"></div>
			<div class="wpbfsb-position-select-box wpbfsb-position-select-box-horizontal wpbfsb-position-select-box-horizontal-bottom" data-position="horizontal-bottom"></div>

			<div class="wpbfsb-position-select-box wpbfsb-position-select-box-vertical wpbfsb-position-select-box-vertical-left" data-position="vertical-left"></div>
			<div class="wpbfsb-position-select-box wpbfsb-position-select-box-vertical wpbfsb-position-select-box-vertical-right" data-position="vertical-right"></div>
			<div class="wpbfsb-position-select-box wpbfsb-position-select-box-vertical wpbfsb-position-select-box-vertical-right-top" data-position="vertical-right-top"></div>
			<div class="wpbfsb-position-select-box wpbfsb-position-select-box-vertical wpbfsb-position-select-box-vertical-right-bottom" data-position="vertical-right-bottom"></div>
			<div class="wpbfsb-position-select-box wpbfsb-position-select-box-vertical wpbfsb-position-select-box-vertical-left-top" data-position="vertical-left-top"></div>
			<div class="wpbfsb-position-select-box wpbfsb-position-select-box-vertical wpbfsb-position-select-box-vertical-left-bottom" data-position="vertical-left-bottom"></div>
		</div>
	<?php
	}


	/**
	 *
	 * Renders an info field
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args
	 */
	public static function info( $args ) {
		$args = wp_parse_args( $args,
				array(
						'label'       => '',
						'type'        => 'info',
						'classes'     => array(),
						'after_input' => '',
						'description' => '',
						'label_for'   => '',
				)
		);

		do_action_ref_array( 'wpbfsb_before_' . $args['type'] . '_input', array( &$args, '' ) );

		echo '<p class="' . implode( ' ', (array) $args['classes'] ) . '">' . $args['description'] . '</p>';

		echo $args['after_input'];

		do_action_ref_array( 'wpbfsb_after_' . $args['type'] . '_input', array( &$args, '' ) );
	}


	/**
	 * Renders the icon preview
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args
	 *
	 * @return void
	 */
	public static function icon_preview( $args ) {
		?>
		<div class="wpbfsb-icon-preview">
			<button>
				<i class="wpbfsb-icon-facebook"></i>
				<span class="wpbfsb-count">(39)</span>
			</button>
		</div>
	<?php
	}
}