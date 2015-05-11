<?php
/**
 * Backend Settings Page View
 */
?>
<div class="wrap wpbfsbbackend">
	<h2 class="nav-tab-wrapper">
		<span class="dashicons dashicons dashicons-share"></span>
		<?php
		echo '<span class="title">' . get_admin_page_title() . '</span>';

		foreach ( $this->tabs as $tab_name => $tab_label ) {
			echo '<a class="nav-tab ' . ( ( $tab_name == $this->current_tab ) ? 'nav-tab-active' : '' ) . '" href="' . admin_url( 'options-general.php?page=wpbfsb&tab=' . $tab_name ) . '">' . $tab_label . '</a>';
		}
		?>
	</h2>

	<form action="<?php echo admin_url( 'options.php' ); ?>" method="post">

		<?php
		settings_fields( $this->page_hook . '_' . $this->current_tab );
		wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
		wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
		echo '<span class="option_group" data-option_group="' . $this->page_hook . '_' . $this->current_tab . '"></span>';
		?>

		<div id="poststuff" class="metabox-holder has-right-sidebar">

			<div id="side-info-column" class="inner-sidebar">
				<?php do_meta_boxes( $this->page_hook . '_' . $this->current_tab, 'side', array() ); ?>
			</div>

			<div id="post-body" class="has-sidebar">
				<div id="post-body-content" class="has-sidebar-content">
					<?php do_meta_boxes( $this->page_hook . '_' . $this->current_tab, 'normal', array() ); ?>
				</div>
			</div>

			<br class="clear" />

		</div>
		<!-- /poststuff -->

	</form>
</div>